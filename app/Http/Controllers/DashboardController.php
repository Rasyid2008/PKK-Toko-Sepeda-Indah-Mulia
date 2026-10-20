<?php

namespace App\Http\Controllers;

use App\Models\Sepeda;
use App\Models\Penjualan;
use App\Models\StokSepeda;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ringkasan Metrik Utama
        $totalSepeda = Sepeda::count();
        $totalPelanggan = Penjualan::where('status', 'lunas')
            ->where('nama_pembeli', '<>', 'Pembeli Umum')
            ->whereNotNull('nama_pembeli')
            ->distinct()
            ->count('nama_pembeli');

        $totalStok = (int) StokSepeda::sum('jumlah_stok');

        // Pendapatan Hari Ini (menggunakan rentang hari yang lengkap agar data tidak terpotong)
        $pendapatanHariIni = (float) Penjualan::where('status', 'lunas')->whereBetween('tanggal_transaksi', [
            Carbon::today()->startOfDay(),
            Carbon::today()->endOfDay(),
        ])->sum('total_bayar');

        // 2. Alert Stok Menipis (Kurang dari 5 unit)
        $stokMenipis = StokSepeda::with('sepeda')
            ->where('jumlah_stok', '<=', 5)
            ->get();

        // 3. Data Grafik Transaksi (7 Hari Terakhir)
        $grafikData = Penjualan::selectRaw('DATE(tanggal_transaksi) as tanggal, SUM(total_bayar) as total')
            ->where('status', 'lunas')
            ->where('tanggal_transaksi', '>=', Carbon::now()->subDays(6))
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'ASC')
            ->get();

        return view('dashboard', compact(
            'totalSepeda',
            'totalPelanggan',
            'totalStok',
            'pendapatanHariIni',
            'stokMenipis',
            'grafikData'
        ));
    }
}
