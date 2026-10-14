<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PosController extends Controller
{
    // Menampilkan halaman kasir interaktif dengan gabungan Produk & Jasa
    public function index()
    {
        $categories = Category::withCount('products')->get();
        $products = Product::with('category')->orderBy('nama_barang')->get();
        $services = Service::orderBy('nama_jasa')->get();

        return view('kasir', compact('categories', 'products', 'services'));
    }

    // Memproses transaksi kasir gabungan Produk & Jasa
    public function store(Request $request)
    {
        $request->validate([
            'metode_pembayaran' => 'required|in:Cash,QRIS,Transfer Bank BRI',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required',
            'items.*.type' => 'nullable|string|in:product,service',
            'items.*.qty' => 'required|integer|min:1',
            'bayar' => 'required|numeric|min:0',
            'nama_pelanggan' => 'nullable|string|max:100',
            'nomor_polisi' => 'nullable|string|max:20',
            'catatan' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $totalHarga = 0;
            $itemsToProcess = [];

            foreach ($request->items as $item) {
                $itemType = $item['type'] ?? 'product';
                $qty = intval($item['qty']);

                if ($itemType === 'service') {
                    // Item berupa Jasa Layanan / Pasang
                    $service = Service::find($item['id']);
                    if (!$service) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => 'Layanan jasa dengan ID ' . $item['id'] . ' tidak ditemukan!'
                        ], 404);
                    }

                    $subtotal = $service->harga_jasa * $qty;
                    $totalHarga += $subtotal;

                    $itemsToProcess[] = [
                        'type' => 'service',
                        'model' => $service,
                        'qty' => $qty,
                        'subtotal' => $subtotal,
                    ];
                } else {
                    // Item berupa Produk Barang / Sparepart
                    $product = Product::lockForUpdate()->find($item['id']);
                    if (!$product) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => 'Barang dengan ID ' . $item['id'] . ' tidak ditemukan!'
                        ], 404);
                    }
                    
                    if ($product->stok < $qty) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => 'Stok untuk barang "' . $product->nama_barang . '" tidak mencukupi! Sisa stok: ' . $product->stok
                        ], 422);
                    }

                    $subtotal = $product->harga_jual * $qty;
                    $totalHarga += $subtotal;

                    $itemsToProcess[] = [
                        'type' => 'product',
                        'model' => $product,
                        'qty' => $qty,
                        'subtotal' => $subtotal,
                    ];
                }
            }

            $bayar = floatval($request->bayar);

            // Validasi uang bayar jika Cash
            if ($request->metode_pembayaran === 'Cash' && $bayar < $totalHarga) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Uang pembayaran tunai kurang dari total belanja (Kurang: Rp ' . number_format($totalHarga - $bayar, 0, ',', '.') . ')'
                ], 422);
            }

            // Jika QRIS atau Bank BRI, bayar sama dengan total belanja jika tidak diinput
            if (in_array($request->metode_pembayaran, ['QRIS', 'Transfer Bank BRI'])) {
                $bayar = $totalHarga;
            }

            $kembalian = max(0, $bayar - $totalHarga);

            // Generate Kode Transaksi Unik: TRD-YYYYMMDD-XXXX
            $today = Carbon::today()->format('Ymd');
            $countToday = Transaction::whereDate('created_at', Carbon::today())->count() + 1;
            $kodeTransaksi = 'TRD-' . $today . '-' . str_pad($countToday, 4, '0', STR_PAD_LEFT);

            // Buat transaksi
            $transaction = Transaction::create([
                'kode_transaksi' => $kodeTransaksi,
                'user_id' => Auth::id(),
                'nama_pelanggan' => $request->nama_pelanggan ?: 'Pelanggan Umum',
                'nomor_polisi' => $request->nomor_polisi ? strtoupper(trim($request->nomor_polisi)) : '-',
                'metode_pembayaran' => $request->metode_pembayaran,
                'total_harga' => $totalHarga,
                'bayar' => $bayar,
                'kembalian' => $kembalian,
                'catatan' => $request->catatan,
            ]);

            // Buat detail transaksi & kurangi stok hanya jika berupa barang
            foreach ($itemsToProcess as $proc) {
                $qty = $proc['qty'];

                if ($proc['type'] === 'service') {
                    $service = $proc['model'];
                    TransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => null,
                        'service_id' => $service->id,
                        'kode_barang' => $service->kode_jasa ?: ('JS-' . $service->id),
                        'nama_item' => $service->nama_jasa . ' (Jasa)',
                        'satuan' => 'Jasa',
                        'jumlah' => $qty,
                        'harga_satuan' => $service->harga_jasa,
                        'subtotal' => $proc['subtotal'],
                    ]);
                    // Jasa tidak memotong stok inventori
                } else {
                    $product = $proc['model'];
                    TransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $product->id,
                        'service_id' => null,
                        'kode_barang' => $product->kode_barang,
                        'nama_item' => $product->nama_barang,
                        'satuan' => $product->satuan,
                        'jumlah' => $qty,
                        'harga_satuan' => $product->harga_jual,
                        'subtotal' => $proc['subtotal'],
                    ]);

                    // Kurangi stok produk
                    $product->decrement('stok', $qty);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil diproses!',
                'transaction_id' => $transaction->id,
                'kode_transaksi' => $transaction->kode_transaksi,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    // Tampilan struk thermal printer (58mm/80mm) siap print
    public function printReceipt($id)
    {
        $transaction = Transaction::with(['details', 'user'])->findOrFail($id);
        return view('struk_pembayaran', compact('transaction'));
    }
}