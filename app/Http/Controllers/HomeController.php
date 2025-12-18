<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::whereDoesntHave('transaction', function($q){
                $q->where('status', '!=', 'canceled');
            })
            ->latest()
            ->take(8)
            ->get();

        // ✅ daftar kategori versi label yang kamu mau tampilkan
        $categories = [
            'Fashion Wanita',
            'Fashion Pria',
            'Beauty & Skincare',
            'Sepatu & Sneakers',
            'Tas & Aksesoris',
            'Elektronik & Gadget',
            'Buku & Alat Kuliah',
            'Hobi (kamera, musik, game)',
            'Peralatan Rumah / Kost',
            'Bayi & Anak',
            'Olahraga',
            'Kesehatan',
        ];

        return view('home', compact('products', 'categories'));
    }
}
