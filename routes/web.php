<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\SepedaController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;

// 1. Rute Publik (Landing Page Depan)
Route::get('/', function () {
    $sepedas = \App\Models\Sepeda::with(['stok' => function ($query) {
        $query->where('jumlah_stok', '>', 0);
    }])->whereHas('stok', function ($query) {
        $query->where('jumlah_stok', '>', 0);
    })->take(4)->get();
    return view('welcome', compact('sepedas'));
});

Route::get('/pesan/{sepeda}', [PenjualanController::class, 'formPesanan'])->name('pesanan.create');
Route::post('/pesan/{sepeda}', [PenjualanController::class, 'simpanPesanan'])->name('pesanan.store');

// 2. Rute Autentikasi (Login & Logout)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 3. KELOMPOK RUTE ADMIN (Hanya Bisa Diakses Jika Sudah Login)
Route::middleware(['auth'])->group(function () {
    
    // Halaman Dashboard Utama Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Manajemen Data Sepeda (CRUD)
    Route::resource('sepeda', SepedaController::class);

    // Transaksi Kasir & Laporan Penjualan
    Route::get('/laporan/penjualan', [PenjualanController::class, 'laporan'])->name('laporan.penjualan');
    Route::resource('penjualan', PenjualanController::class);
    Route::patch('/penjualan/{penjualan}/konfirmasi', [PenjualanController::class, 'konfirmasiPembayaran'])->name('penjualan.konfirmasi');
    Route::patch('/penjualan/{penjualan}/batalkan', [PenjualanController::class, 'batalkanPesanan'])->name('penjualan.batalkan');

    // Kategori & Kontrol Stok
    Route::resource('kategori', KategoriController::class)->only(['index', 'store', 'destroy']);
    Route::resource('stok', StokController::class)->only(['index', 'update']);
    Route::get('/laporan/stok', [StokController::class, 'laporan'])->name('laporan.stok');
    Route::get('/mutasi-stok', [StokController::class, 'laporan'])->name('mutasi-stok.index');
    
});
