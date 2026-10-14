<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ServiceController;

// Halaman awal langsung arahkan ke Dashboard (jika sudah login) atau Login (jika belum)
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Autentikasi Login & Logout (Wajib Login)
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// SEMUA RUTE DI BAWAH INI DILINDUNGI MIDDLEWARE AUTH (WAJIB LOGIN)
Route::middleware('auth')->group(function () {

    // Dashboard Utama dengan Animasi & Statistik
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Kategori Produk (CRUD Lengkap)
    Route::get('/kategori', [CategoryController::class, 'index'])->name('kategori.index');
    Route::post('/kategori', [CategoryController::class, 'store'])->name('kategori.store');
    Route::put('/kategori/{id}', [CategoryController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{id}', [CategoryController::class, 'destroy'])->name('kategori.destroy');

    // Manajemen Produk (CRUD Lengkap, Cetak, dan Cari)
    Route::get('/produk', [ProductController::class, 'index'])->name('produk.index');
    Route::post('/produk', [ProductController::class, 'store'])->name('produk.store');
    Route::get('/produk/edit/{id}', [ProductController::class, 'edit'])->name('produk.edit');
    Route::put('/produk/{id}', [ProductController::class, 'update'])->name('produk.update');
    Route::delete('/produk/{id}', [ProductController::class, 'destroy'])->name('produk.destroy');
    
    // Produk Per Kategori (Dengan Fitur CRUD, Cari, dan Cetak Per Kategori)
    Route::get('/produk/kategori/{id}', [ProductController::class, 'showByCategory'])->name('produk.byCategory');
    Route::get('/produk/cetak/{id?}', [ProductController::class, 'printProducts'])->name('produk.print');

    // Manajemen Layanan & Jasa Servis (CRUD, Pemasangan, Cetak Tarif)
    Route::get('/jasa', [ServiceController::class, 'index'])->name('jasa.index');
    Route::post('/jasa', [ServiceController::class, 'store'])->name('jasa.store');
    Route::put('/jasa/{id}', [ServiceController::class, 'update'])->name('jasa.update');
    Route::delete('/jasa/{id}', [ServiceController::class, 'destroy'])->name('jasa.destroy');
    Route::get('/jasa/cetak', [ServiceController::class, 'print'])->name('jasa.print');

    // Sistem Pembayaran Kasir (POS, Hitung Kembalian, QRIS, BRI, Cetak Struk)
    Route::get('/kasir', [PosController::class, 'index'])->name('kasir.index');
    Route::post('/kasir/transaksi', [PosController::class, 'store'])->name('kasir.store');
    Route::get('/kasir/struk/{id}', [PosController::class, 'printReceipt'])->name('kasir.receipt');

    // Riwayat Transaksi & Laporan
    Route::get('/transaksi', [TransactionController::class, 'index'])->name('transaksi.index');
    Route::get('/transaksi/{id}', [TransactionController::class, 'show'])->name('transaksi.show');
    Route::get('/transaksi/cetak-struk/{id}', [TransactionController::class, 'printReceipt'])->name('transaksi.receipt');
    Route::get('/transaksi/laporan/cetak', [TransactionController::class, 'printReport'])->name('transaksi.report');

    // Profil Admin Bengkel
    Route::get('/admin/profil', [AuthController::class, 'profile'])->name('profil');
    Route::post('/admin/profil', [AuthController::class, 'updateProfile'])->name('profil.update');
});