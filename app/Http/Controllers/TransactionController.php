<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;

class TransactionController extends Controller
{
    // Menampilkan riwayat transaksi bengkel
    public function index(Request $request)
    {
        $query = Transaction::with(['details', 'user']);

        if ($request->filled('metode')) {
            $query->where('metode_pembayaran', $request->metode);
        }

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('created_at', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_selesai);
        }

        if ($request->filled('cari')) {
            $keyword = $request->cari;
            $query->where(function($q) use ($keyword) {
                $q->where('kode_transaksi', 'like', "%{$keyword}%")
                  ->orWhere('nama_pelanggan', 'like', "%{$keyword}%")
                  ->orWhere('nomor_polisi', 'like', "%{$keyword}%");
            });
        }

        $transactions = $query->latest()->get();

        $totalOmset = $transactions->sum('total_harga');
        $totalCount = $transactions->count();

        return view('admin_transaksi', compact('transactions', 'totalOmset', 'totalCount'));
    }

    // Detail satu transaksi
    public function show($id)
    {
        $transaction = Transaction::with(['details.product', 'user'])->findOrFail($id);
        return response()->json($transaction);
    }

    // Cetak struk ulang
    public function printReceipt($id)
    {
        $transaction = Transaction::with(['details', 'user'])->findOrFail($id);
        return view('struk_pembayaran', compact('transaction'));
    }

    // Cetak Laporan Rekap Penjualan Transaksi
    public function printReport(Request $request)
    {
        $query = Transaction::with(['details', 'user']);

        if ($request->filled('metode')) {
            $query->where('metode_pembayaran', $request->metode);
        }
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('created_at', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_selesai);
        }

        $transactions = $query->latest()->get();
        $totalOmset = $transactions->sum('total_harga');

        return view('print_transaksi', compact('transactions', 'totalOmset', 'request'));
    }
}
