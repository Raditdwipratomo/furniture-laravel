<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Kategori;
use App\Models\Produk;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $kategoris = Kategori::withCount('produks')
            ->take(6)
            ->get();

        $featuredProducts = Produk::where('is_featured', true)
            ->where('is_active', true)
            ->with('kategori')
            ->take(8)
            ->get();

        $newestProducts = Produk::where('is_active', true)
            ->with('kategori')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        return view('public.home', compact('banners', 'kategoris', 'featuredProducts', 'newestProducts'));
    }
}
