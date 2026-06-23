<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::where('is_active', true)->with('kategori');

        // Filter by kategori
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('harga', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('harga', '<=', $request->max_price);
        }

        // Filter in stock
        if ($request->boolean('in_stock')) {
            $query->where('stok', '>', 0);
        }

        // Sort
        switch ($request->input('sort', 'newest')) {
            case 'price_asc':
                $query->orderBy('harga', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('harga', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $produks = $query->paginate(12)->withQueryString();
        $kategoris = Kategori::withCount('produks')->get();

        return view('public.products.index', compact('produks', 'kategoris'));
    }

    public function show($id)
    {
        $produk = Produk::with(['kategori', 'reviews' => function ($q) {
            $q->where('is_approved', true)->with('user');
        }])->findOrFail($id);

        $relatedProducts = Produk::where('kategori_id', $produk->kategori_id)
            ->where('id', '!=', $produk->id)
            ->where('is_active', true)
            ->with('kategori')
            ->take(4)
            ->get();

        $avgRating = $produk->reviews->avg('rating') ?? 0;
        $avgRating = round($avgRating, 1);

        return view('public.products.show', compact('produk', 'relatedProducts', 'avgRating'));
    }
}
