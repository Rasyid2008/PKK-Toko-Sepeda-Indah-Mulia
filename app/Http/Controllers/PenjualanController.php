<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Sepeda;
use App\Models\StokSepeda;
use App\Models\mutasi_stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PenjualanController extends Controller
{
    public function index()
    {
        // Menu: Laporan Penjualan
        $penjualans = Penjualan::latest()->get();
        return view('penjualan.index', compact('penjualans'));
    }

    public function laporan(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai', now()->startOfMonth()->toDateString());
        $tanggalSelesai = $request->input('tanggal_selesai', now()->toDateString());

        $penjualans = Penjualan::with('detailPenjualan.sepeda')
            ->where('status', 'lunas')
            ->whereBetween('tanggal_transaksi', [
                $tanggalMulai . ' 00:00:00',
                $tanggalSelesai . ' 23:59:59',
            ])
            ->latest()
            ->get();

        return view('laporan.penjualan', [
            'penjualans' => $penjualans,
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
            'total_transaksi' => $penjualans->count(),
            'total_penjualan' => $penjualans->sum('total_bayar'),
        ]);
    }

    public function create()
    {
        // Menu: Penjualan (Halaman Transaksi Kasir)
        $sepedas = Sepeda::with('stok')->get();
        return view('penjualan.create', compact('sepedas'));
    }

    public function formPesanan(Sepeda $sepeda)
    {
        $sepeda->load('stok');

        abort_unless($sepeda->stok && $sepeda->stok->jumlah_stok > 0, 404);

        return view('pesanan.create', compact('sepeda'));
    }

    public function simpanPesanan(Request $request, Sepeda $sepeda)
    {
        $data = $request->validate([
            'nama_pembeli' => 'required|string|max:150',
            'telepon_pembeli' => 'required|string|max:20',
            'alamat_pembeli' => 'nullable|string|max:1000',
            'jumlah_beli' => 'required|integer|min:1',
        ]);

        $penjualan = DB::transaction(function () use ($data, $sepeda) {
            $sepeda = Sepeda::whereKey($sepeda->id_sepeda)->lockForUpdate()->firstOrFail();
            $stok = StokSepeda::where('id_sepeda', $sepeda->id_sepeda)->lockForUpdate()->first();

            if (!$stok || $stok->jumlah_stok < $data['jumlah_beli']) {
                throw ValidationException::withMessages([
                    'jumlah_beli' => 'Stok tidak mencukupi. Silakan tanyakan ketersediaan melalui WhatsApp.',
                ]);
            }

            $subtotal = $sepeda->harga_jual * $data['jumlah_beli'];
            $penjualan = Penjualan::create([
                'nama_pembeli' => $data['nama_pembeli'],
                'telepon_pembeli' => $data['telepon_pembeli'],
                'alamat_pembeli' => $data['alamat_pembeli'] ?? null,
                'total_bayar' => $subtotal,
                'status' => 'menunggu_pembayaran',
            ]);

            $stok->decrement('jumlah_stok', $data['jumlah_beli']);
            mutasi_stock::create([
                'id_sepeda' => $sepeda->id_sepeda,
                'jenis_mutasi' => 'keluar',
                'jumlah' => $data['jumlah_beli'],
                'keterangan' => 'Reservasi pesanan #TRX-' . str_pad($penjualan->id_penjualan, 5, '0', STR_PAD_LEFT),
            ]);

            DetailPenjualan::create([
                'id_penjualan' => $penjualan->id_penjualan,
                'id_sepeda' => $sepeda->id_sepeda,
                'jumlah_beli' => $data['jumlah_beli'],
                'subtotal' => $subtotal,
            ]);

            return $penjualan;
        });

        $nomorToko = preg_replace('/\\D+/', '', env('WHATSAPP_NOMOR_TOKO', '6281234567890'));
        $pesan = implode("\n", [
            'Halo, saya ingin menanyakan pesanan berikut:',
            'Nomor pesanan: #TRX-' . str_pad($penjualan->id_penjualan, 5, '0', STR_PAD_LEFT),
            'Nama: ' . $penjualan->nama_pembeli,
            'Sepeda: ' . $sepeda->merk_sepeda . ' ' . ($sepeda->tipe_sepeda ?? ''),
            'Jumlah: ' . $data['jumlah_beli'] . ' unit',
            'Total: Rp ' . number_format($penjualan->total_bayar, 0, ',', '.'),
        ]);

        return redirect()->away('https://wa.me/' . $nomorToko . '?text=' . rawurlencode($pesan));
    }

    public function store(Request $request)
    {
        // Validasi struktur input checkout/keranjang belanja
        $data = $request->validate([
            'nama_pembeli' => 'nullable|string|max:150',
            'telepon_pembeli' => 'nullable|string|max:20',
            'alamat_pembeli' => 'nullable|string',
            'items'        => 'required|array|min:1',
            'items.*.id_sepeda'  => 'required|exists:sepedas,id_sepeda',
            'items.*.jumlah_beli'=> 'required|integer|min:1',
            'discount_amount' => 'nullable|integer|min:0',
            'discount_description' => 'nullable|string|max:255',
        ]);

        $totalBayar = 0;
        $jumlahDiskon = (int) ($data['discount_amount'] ?? 0);
        $deskripsiDiskon = trim($data['discount_description'] ?? '');

        // Gunakan database transaction agar data tetap aman/konsisten bila terjadi kegagalan sistem ditengah jalan
        DB::transaction(function () use ($request, $jumlahDiskon, $deskripsiDiskon, &$totalBayar) {
            
            // 1. Buat data Penjualan utama
            $penjualan = Penjualan::create([
                'nama_pembeli' => $request->input('nama_pembeli') ?: 'Pembeli Umum',
                'telepon_pembeli' => $request->input('telepon_pembeli'),
                'alamat_pembeli' => $request->input('alamat_pembeli'),
                'total_bayar'  => 0,
                'discount_amount' => $jumlahDiskon,
                'discount_description' => $jumlahDiskon > 0 ? $deskripsiDiskon : null,
                'status' => 'lunas',
            ]);

            foreach ($request->items as $item) {
                $sepeda = Sepeda::findOrFail($item['id_sepeda']);
                $subtotal = $sepeda->harga_jual * $item['jumlah_beli'];
                $totalBayar += $subtotal;

                // 2. Kurangi stok fisik sepeda di database
                $stok = StokSepeda::where('id_sepeda', $sepeda->id_sepeda)->first();
                if ($stok->jumlah_stok < $item['jumlah_beli']) {
                    throw new \Exception("Stok sepeda {$sepeda->merk_sepeda} tidak mencukupi.");
                }
                $stok->decrement('jumlah_stok', $item['jumlah_beli']);

                mutasi_stock::create([
                    'id_sepeda' => $sepeda->id_sepeda,
                    'jenis_mutasi' => 'keluar',
                    'jumlah' => $item['jumlah_beli'],
                    'keterangan' => 'Penjualan #TRX-' . str_pad($penjualan->id_penjualan, 5, '0', STR_PAD_LEFT),
                ]);

                // 3. Masukkan ke item detail penjualan
                DetailPenjualan::create([
                    'id_penjualan' => $penjualan->id_penjualan,
                    'id_sepeda'    => $sepeda->id_sepeda,
                    'jumlah_beli'  => $item['jumlah_beli'],
                    'subtotal'     => $subtotal
                ]);
            }

            if ($jumlahDiskon > $totalBayar) {
                throw ValidationException::withMessages([
                    'discount_amount' => 'Potongan harga tidak boleh melebihi subtotal transaksi.',
                ]);
            }

            if ($jumlahDiskon > 0 && $deskripsiDiskon === '') {
                throw ValidationException::withMessages([
                    'discount_description' => 'Deskripsi potongan wajib diisi jika menggunakan diskon.',
                ]);
            }

            // 4. Update total_bayar sesungguhnya di data utama
            $penjualan->update(['total_bayar' => $totalBayar - $jumlahDiskon]);
        });

        return redirect()->route('laporan.penjualan')->with('success', 'Transaksi penjualan berhasil diproses.');
    }

    public function edit(Penjualan $penjualan)
    {
        abort_unless($penjualan->status === 'menunggu_pembayaran', 404);

        $penjualan->load('detailPenjualan.sepeda');

        return view('penjualan.edit', compact('penjualan'));
    }

    public function update(Request $request, Penjualan $penjualan)
    {
        $data = $request->validate([
            'discount_amount' => 'required|integer|min:0',
            'discount_description' => 'nullable|string|max:255',
        ]);
        $deskripsiDiskon = trim($data['discount_description'] ?? '');

        DB::transaction(function () use ($penjualan, $data, $deskripsiDiskon) {
            $penjualan = Penjualan::whereKey($penjualan->id_penjualan)
                ->lockForUpdate()
                ->firstOrFail();

            if ($penjualan->status !== 'menunggu_pembayaran') {
                throw ValidationException::withMessages([
                    'status' => 'Potongan hanya dapat diubah selama pesanan menunggu pembayaran.',
                ]);
            }

            $subtotal = $penjualan->detailPenjualan()->sum('subtotal');
            $jumlahDiskon = (int) $data['discount_amount'];

            if ($jumlahDiskon > $subtotal) {
                throw ValidationException::withMessages([
                    'discount_amount' => 'Potongan harga tidak boleh melebihi subtotal transaksi.',
                ]);
            }

            if ($jumlahDiskon > 0 && $deskripsiDiskon === '') {
                throw ValidationException::withMessages([
                    'discount_description' => 'Deskripsi potongan wajib diisi jika menggunakan diskon.',
                ]);
            }

            $penjualan->update([
                'discount_amount' => $jumlahDiskon,
                'discount_description' => $jumlahDiskon > 0 ? $deskripsiDiskon : null,
                'total_bayar' => $subtotal - $jumlahDiskon,
            ]);
        });

        return redirect()->route('penjualan.index')->with('success', 'Potongan pesanan berhasil diperbarui.');
    }

    public function konfirmasiPembayaran(Penjualan $penjualan)
    {
        DB::transaction(function () use ($penjualan) {
            $penjualan = Penjualan::whereKey($penjualan->id_penjualan)->lockForUpdate()->firstOrFail();

            if ($penjualan->status !== 'menunggu_pembayaran') {
                throw ValidationException::withMessages(['status' => 'Hanya pesanan yang menunggu pembayaran yang dapat dikonfirmasi.']);
            }

            $penjualan->update(['status' => 'lunas', 'tanggal_transaksi' => now()]);
        });

        return redirect()->route('penjualan.index')->with('success', 'Pembayaran dikonfirmasi dan stok diperbarui.');
    }

    public function batalkanPesanan(Penjualan $penjualan)
    {
        DB::transaction(function () use ($penjualan) {
            $penjualan = Penjualan::whereKey($penjualan->id_penjualan)->lockForUpdate()->firstOrFail();

            if ($penjualan->status !== 'menunggu_pembayaran') {
                throw ValidationException::withMessages(['status' => 'Hanya pesanan yang menunggu pembayaran yang dapat dibatalkan.']);
            }

            foreach ($penjualan->detailPenjualan as $detail) {
                if (!$detail->id_sepeda) {
                    continue;
                }

                $stok = StokSepeda::where('id_sepeda', $detail->id_sepeda)->lockForUpdate()->first();
                if (!$stok) {
                    continue;
                }

                $stok->increment('jumlah_stok', $detail->jumlah_beli);
                mutasi_stock::create([
                    'id_sepeda' => $detail->id_sepeda,
                    'jenis_mutasi' => 'masuk',
                    'jumlah' => $detail->jumlah_beli,
                    'keterangan' => 'Pembatalan pesanan #TRX-' . str_pad($penjualan->id_penjualan, 5, '0', STR_PAD_LEFT),
                ]);
            }

            $penjualan->update(['status' => 'dibatalkan']);
        });

        return redirect()->route('penjualan.index')->with('success', 'Pesanan berhasil dibatalkan.');
    }

    public function show($id)
    {
        $penjualan = Penjualan::with('detailPenjualan.sepeda')->findOrFail($id);
        return view('penjualan.show', compact('penjualan'));
    }
}
