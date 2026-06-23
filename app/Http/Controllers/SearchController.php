<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q', '');

        $produks = Produk::where('is_active', true)
            ->where(function ($query) use ($q) {
                $query->where('nama_produk', 'like', "%{$q}%")
                    ->orWhere('deskripsi', 'like', "%{$q}%");
            })
            ->with('kategori')
            ->paginate(12)
            ->withQueryString();

        return view('public.search', compact('produks', 'q'));
    }
}
