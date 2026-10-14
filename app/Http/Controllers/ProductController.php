<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    // Menampilkan semua produk lintas kategori dengan filter & search
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('kategori')) {
            $query->where('category_id', $request->kategori);
        }

        if ($request->filled('cari')) {
            $keyword = $request->cari;
            $query->where(function($q) use ($keyword) {
                $q->where('nama_barang', 'like', "%{$keyword}%")
                  ->orWhere('kode_barang', 'like', "%{$keyword}%")
                  ->orWhere('tipe_kendaraan', 'like', "%{$keyword}%");
            });
        }

        if ($request->filter_stok == 'menipis') {
            $query->where('stok', '<', 5);
        } elseif ($request->filter_stok == 'habis') {
            $query->where('stok', '<=', 0);
        }

        $products = $query->orderBy('nama_barang')->get();
        $categories = Category::withCount('products')->get();

        return view('admin_produk', compact('products', 'categories'));
    }

    // Menampilkan produk khusus per kategori dengan fitur CRUD, Cetak, dan Cari
    public function showByCategory(Request $request, $id)
    {
        $category = Category::withCount('products')
            ->withSum('products', 'stok')
            ->findOrFail($id);

        $query = Product::where('category_id', $id);

        if ($request->filled('cari')) {
            $keyword = $request->cari;
            $query->where(function($q) use ($keyword) {
                $q->where('nama_barang', 'like', "%{$keyword}%")
                  ->orWhere('kode_barang', 'like', "%{$keyword}%")
                  ->orWhere('tipe_kendaraan', 'like', "%{$keyword}%");
            });
        }

        $products = $query->orderBy('nama_barang')->get();
        $categories = Category::all();

        return view('admin_kategori_produk', compact('category', 'products', 'categories'));
    }

    // Menyimpan produk baru (Create)
    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|string|unique:products,kode_barang|max:50',
            'nama_barang' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'satuan' => 'required|string|max:30',
            'harga_beli' => 'nullable|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'tipe_kendaraan' => 'nullable|string|max:255',
            'tahun' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string|max:500',
        ]);

        Product::create([
            'kode_barang' => strtoupper(trim($request->kode_barang)),
            'nama_barang' => $request->nama_barang,
            'category_id' => $request->category_id,
            'satuan' => $request->satuan ?: 'Pcs',
            'harga_beli' => $request->harga_beli ?: 0,
            'harga_jual' => $request->harga_jual,
            'stok' => $request->stok,
            'tipe_kendaraan' => $request->tipe_kendaraan,
            'tahun' => $request->tahun,
            'keterangan' => $request->keterangan,
        ]);

        return back()->with('success', 'Barang "' . $request->nama_barang . '" berhasil ditambahkan!');
    }

    // Ambil data untuk form edit
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('edit_produk', compact('product', 'categories'));
    }

    // Menyimpan pembaruan produk (Update)
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'kode_barang' => 'required|string|max:50|unique:products,kode_barang,' . $id,
            'nama_barang' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'satuan' => 'required|string|max:30',
            'harga_beli' => 'nullable|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'tipe_kendaraan' => 'nullable|string|max:255',
            'tahun' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $product->update([
            'kode_barang' => strtoupper(trim($request->kode_barang)),
            'nama_barang' => $request->nama_barang,
            'category_id' => $request->category_id,
            'satuan' => $request->satuan ?: 'Pcs',
            'harga_beli' => $request->harga_beli ?: 0,
            'harga_jual' => $request->harga_jual,
            'stok' => $request->stok,
            'tipe_kendaraan' => $request->tipe_kendaraan,
            'tahun' => $request->tahun,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('produk.byCategory', $product->category_id)
            ->with('success', 'Barang "' . $product->nama_barang . '" berhasil diperbarui!');
    }

    // Menghapus produk (Delete)
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $nama = $product->nama_barang;
        $catId = $product->category_id;
        $product->delete();

        return back()->with('success', 'Barang "' . $nama . '" berhasil dihapus!');
    }

    // Halaman Cetak Laporan Khusus Per Kategori atau Seluruh Produk
    public function printProducts(Request $request, $id = null)
    {
        if ($id) {
            $category = Category::findOrFail($id);
            $products = Product::where('category_id', $id)->orderBy('nama_barang')->get();
            $title = 'Laporan Stok & Inventori: Kategori ' . $category->nama_kategori;
        } else {
            $category = null;
            $products = Product::with('category')->orderBy('category_id')->orderBy('nama_barang')->get();
            $title = 'Laporan Stok & Inventori Seluruh Produk Bengkel';
        }

        return view('print_produk', compact('category', 'products', 'title'));
    }
}