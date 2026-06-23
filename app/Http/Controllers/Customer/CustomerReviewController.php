<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class CustomerReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $reviews = Review::where('user_id', auth()->id())
            ->with(['produk', 'pesanan'])
            ->orderBy('tanggal_review', 'desc')
            ->paginate(10);

        return view('customer.reviews.index', compact('reviews'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'pesanan_id' => 'required|exists:pesanans,id',
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'required|string|max:1000',
        ]);

        $pesanan = Pesanan::where('id', $request->pesanan_id)
            ->where('user_id', auth()->id())
            ->where('status', 'selesai')
            ->firstOrFail();

        // Check if user already reviewed this product for this order
        $existing = Review::where('user_id', auth()->id())
            ->where('produk_id', $request->produk_id)
            ->where('pesanan_id', $request->pesanan_id)
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah memberikan review untuk produk ini.');
        }

        // Check if product is in the order
        $hasProduct = $pesanan->detail()->where('produk_id', $request->produk_id)->exists();
        if (!$hasProduct) {
            return back()->with('error', 'Produk tidak ada dalam pesanan ini.');
        }

        Review::create([
            'user_id' => auth()->id(),
            'produk_id' => $request->produk_id,
            'pesanan_id' => $request->pesanan_id,
            'rating' => $request->rating,
            'komentar' => $request->komentar,
            'is_approved' => false,
            'tanggal_review' => now(),
        ]);

        return back()->with('success', 'Review berhasil ditambahkan. Menunggu persetujuan admin.');
    }
}
