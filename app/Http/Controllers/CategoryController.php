<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')
            ->withSum('products', 'stok')
            ->get();
        return view('admin_kategori', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:categories,nama_kategori',
            'deskripsi' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:100',
        ]);

        Category::create([
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi,
            'icon' => $request->icon ?: 'fa-box',
        ]);

        return back()->with('success', 'Kategori baru berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:categories,nama_kategori,' . $id,
            'deskripsi' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:100',
        ]);

        $category->update([
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi,
            'icon' => $request->icon ?: $category->icon,
        ]);

        return back()->with('success', 'Data kategori berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $category = Category::withCount('products')->findOrFail($id);
        
        $category->delete();
        return back()->with('success', 'Kategori dan seluruh produk di dalamnya berhasil dihapus!');
    }
}
