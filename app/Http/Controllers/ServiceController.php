<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;

class ServiceController extends Controller
{
    // Menampilkan daftar layanan dan jasa servis bengkel
    public function index(Request $request)
    {
        $query = Service::query();

        if ($request->filled('cari')) {
            $keyword = $request->cari;
            $query->where(function($q) use ($keyword) {
                $q->where('nama_jasa', 'like', "%{$keyword}%")
                  ->orWhere('kode_jasa', 'like', "%{$keyword}%")
                  ->orWhere('kategori_jasa', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori_jasa', $request->kategori);
        }

        $services = $query->orderBy('nama_jasa')->get();
        $categories = Service::select('kategori_jasa')->distinct()->whereNotNull('kategori_jasa')->pluck('kategori_jasa');

        return view('admin_jasa', compact('services', 'categories'));
    }

    // Menyimpan jasa baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_jasa' => 'required|string|max:255',
            'harga_jasa' => 'required|numeric|min:0',
            'kategori_jasa' => 'nullable|string|max:100',
            'kode_jasa' => 'nullable|string|max:50|unique:services,kode_jasa',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $kode = $request->kode_jasa ? strtoupper(trim($request->kode_jasa)) : 'JS-' . rand(100, 999);

        Service::create([
            'kode_jasa' => $kode,
            'nama_jasa' => $request->nama_jasa,
            'kategori_jasa' => $request->kategori_jasa ?: 'Umum',
            'harga_jasa' => $request->harga_jasa,
            'keterangan' => $request->keterangan,
        ]);

        return back()->with('success', 'Layanan jasa "' . $request->nama_jasa . '" berhasil ditambahkan!');
    }

    // Memperbarui data jasa
    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $request->validate([
            'nama_jasa' => 'required|string|max:255',
            'harga_jasa' => 'required|numeric|min:0',
            'kategori_jasa' => 'nullable|string|max:100',
            'kode_jasa' => 'nullable|string|max:50|unique:services,kode_jasa,' . $id,
            'keterangan' => 'nullable|string|max:500',
        ]);

        $service->update([
            'kode_jasa' => $request->kode_jasa ? strtoupper(trim($request->kode_jasa)) : $service->kode_jasa,
            'nama_jasa' => $request->nama_jasa,
            'kategori_jasa' => $request->kategori_jasa ?: 'Umum',
            'harga_jasa' => $request->harga_jasa,
            'keterangan' => $request->keterangan,
        ]);

        return back()->with('success', 'Data jasa "' . $service->nama_jasa . '" berhasil diperbarui!');
    }

    // Menghapus jasa
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $nama = $service->nama_jasa;
        $service->delete();

        return back()->with('success', 'Layanan jasa "' . $nama . '" berhasil dihapus!');
    }

    // Cetak daftar tarif jasa
    public function print(Request $request)
    {
        $services = Service::orderBy('kategori_jasa')->orderBy('nama_jasa')->get();
        return view('print_jasa', compact('services'));
    }
}
