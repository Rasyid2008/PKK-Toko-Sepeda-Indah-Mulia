<?php

namespace App\Http\Controllers;

use App\Models\StokSepeda;
use App\Models\mutasi_stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokController extends Controller
{
    public function index()
    {
        $stoks = StokSepeda::with('sepeda.kategori')->get();
        return view('stok.index', compact('stoks'));
    }

    public function laporan(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai', now()->startOfMonth()->toDateString());
        $tanggalSelesai = $request->input('tanggal_selesai', now()->toDateString());

        $stoks = StokSepeda::with('sepeda.kategori')->get();
        $mutasis = mutasi_stock::with('sepeda.kategori')
            ->whereBetween('created_at', [
                $tanggalMulai . ' 00:00:00',
                $tanggalSelesai . ' 23:59:59',
            ])
            ->latest()
            ->get();

        return view('laporan.stok', [
            'stoks' => $stoks,
            'mutasis' => $mutasis,
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
            'total_stok' => $stoks->sum('jumlah_stok'),
            'total_masuk' => $mutasis->where('jenis_mutasi', 'masuk')->sum('jumlah'),
            'total_keluar' => $mutasis->where('jenis_mutasi', 'keluar')->sum('jumlah'),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jumlah_stok' => 'required|integer|min:0'
        ]);

        DB::transaction(function () use ($request, $id) {
            $stok = StokSepeda::findOrFail($id);
            $jumlahLama = $stok->jumlah_stok;
            $jumlahBaru = (int) $request->jumlah_stok;
            $selisih = $jumlahBaru - $jumlahLama;

            $stok->update(['jumlah_stok' => $jumlahBaru]);

            if ($selisih !== 0) {
                mutasi_stock::create([
                    'id_sepeda' => $stok->id_sepeda,
                    'jenis_mutasi' => $selisih > 0 ? 'masuk' : 'keluar',
                    'jumlah' => abs($selisih),
                    'keterangan' => 'Penyesuaian stok fisik',
                ]);
            }
        });

        return redirect()->route('stok.index')->with('success', 'Jumlah kuantiti stok fisik berhasil diperbarui.');
    }
}
