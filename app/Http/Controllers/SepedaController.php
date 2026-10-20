<?php

namespace App\Http\Controllers;

use App\Models\Sepeda;
use App\Models\Kategori;
use App\Models\StokSepeda;
use App\Models\mutasi_stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class SepedaController extends Controller
{
    public function index()
    {
        // Menu: Data Sepeda
        $sepedas = Sepeda::with('kategori', 'stok')->latest()->get();
        return view('sepeda.index', compact('sepedas'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('sepeda.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_kategori' => 'nullable|exists:kategoris,id_kategori',
            'merk_sepeda' => 'required|string|max:100',
            'tipe_sepeda' => 'nullable|string|max:100',
            'harga_jual'  => 'required|numeric|min:0',
            'stok_awal'   => 'required|integer|min:0',
            'gambar'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('sepeda', 'public');
            if (!$data['gambar']) {
                throw new \RuntimeException('Gambar sepeda gagal disimpan.');
            }
        }

        try {
            DB::transaction(function () use ($data) {
                $sepeda = Sepeda::create([
                    'id_kategori' => $data['id_kategori'],
                    'merk_sepeda' => $data['merk_sepeda'],
                    'tipe_sepeda' => $data['tipe_sepeda'] ?? null,
                    'harga_jual'  => $data['harga_jual'],
                    'gambar'      => $data['gambar'] ?? null,
                ]);

                // Sekaligus mendaftarkan slot stok untuk sepeda baru ini
                $stok = StokSepeda::create([
                    'id_sepeda'   => $sepeda->id_sepeda,
                    'jumlah_stok' => $data['stok_awal']
                ]);

                if ($stok->jumlah_stok > 0) {
                    mutasi_stock::create([
                        'id_sepeda' => $sepeda->id_sepeda,
                        'jenis_mutasi' => 'masuk',
                        'jumlah' => $stok->jumlah_stok,
                        'keterangan' => 'Stok awal sepeda baru',
                    ]);
                }
            });
        } catch (Throwable $exception) {
            if (!empty($data['gambar'])) {
                Storage::disk('public')->delete($data['gambar']);
            }
            throw $exception;
        }

        return redirect()->route('sepeda.index')->with('success', 'Data sepeda dan stok awal berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $sepeda = Sepeda::findOrFail($id);
        $kategoris = Kategori::all();
        return view('sepeda.edit', compact('sepeda', 'kategoris'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'id_kategori' => 'nullable|exists:kategoris,id_kategori',
            'merk_sepeda' => 'required|string|max:100',
            'tipe_sepeda' => 'nullable|string|max:100',
            'harga_jual'  => 'required|numeric|min:0',
            'gambar'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $sepeda = Sepeda::findOrFail($id);
        $gambarLama = $sepeda->gambar;
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('sepeda', 'public');
            if (!$data['gambar']) {
                throw new \RuntimeException('Gambar sepeda gagal disimpan.');
            }
        }

        try {
            $sepeda->update($data);
        } catch (Throwable $exception) {
            if (!empty($data['gambar'])) {
                Storage::disk('public')->delete($data['gambar']);
            }
            throw $exception;
        }

        if (!empty($data['gambar']) && $gambarLama) {
            Storage::disk('public')->delete($gambarLama);
        }

        return redirect()->route('sepeda.index')->with('success', 'Data sepeda berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $sepeda = Sepeda::findOrFail($id);
        $sepeda->delete(); // Karena foreign key diset cascade pada stok, data stok otomatis terhapus

        return redirect()->route('sepeda.index')->with('success', 'Data sepeda berhasil dihapus.');
    }
}
