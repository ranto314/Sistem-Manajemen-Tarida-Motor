<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalKategori = Category::count();
        $totalBarang = Product::count();
        $totalJasa = Service::count();
        $totalStok = Product::sum('stok');
        $totalTransaksi = Transaction::count();
        $totalPendapatan = Transaction::sum('total_harga');
        
        // Transaksi Hari Ini
        $transaksiHariIni = Transaction::whereDate('created_at', Carbon::today())->count();
        $pendapatanHariIni = Transaction::whereDate('created_at', Carbon::today())->sum('total_harga');

        // Barang dengan stok menipis (di bawah 5 pcs)
        $barangStokMenipis = Product::where('stok', '<', 5)->with('category')->get();
        $jumlahStokMenipis = $barangStokMenipis->count();

        // 7 Transaksi Terakhir
        $transaksiTerakhir = Transaction::with(['details', 'user'])->latest()->take(7)->get();

        // Semua kategori dengan jumlah produk dan total stok
        $kategoriList = Category::withCount('products')->withSum('products', 'stok')->get();

        // Statistik Metode Pembayaran (Cash, QRIS, BRI)
        $metodeStats = Transaction::selectRaw('metode_pembayaran, COUNT(*) as count, SUM(total_harga) as total')
            ->groupBy('metode_pembayaran')
            ->get();

        return view('dashboard', compact(
            'totalKategori',
            'totalBarang',
            'totalJasa',
            'totalStok',
            'totalTransaksi',
            'totalPendapatan',
            'transaksiHariIni',
            'pendapatanHariIni',
            'barangStokMenipis',
            'jumlahStokMenipis',
            'transaksiTerakhir',
            'kategoriList',
            'metodeStats'
        ));
    }
}