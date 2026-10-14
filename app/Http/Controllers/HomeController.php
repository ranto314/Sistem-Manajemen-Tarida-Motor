<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Service;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil semua data produk (beserta nama kategorinya) dan jasa
        $products = Product::with('category')->get();
        $services = Service::all();

        return view('beranda', compact('products', 'services'));
    }
}