<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $wishlists = Wishlist::where('user_id', auth()->id())
            ->with('produk')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer.wishlist.index', compact('wishlists'));
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
        ]);

        $wishlist = Wishlist::where('user_id', auth()->id())
            ->where('produk_id', $request->produk_id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            return response()->json([
                'status' => 'removed',
                'message' => 'Produk dihapus dari wishlist.',
            ]);
        } else {
            Wishlist::create([
                'user_id' => auth()->id(),
                'produk_id' => $request->produk_id,
            ]);
            return response()->json([
                'status' => 'added',
                'message' => 'Produk ditambahkan ke wishlist.',
            ]);
        }
    }

    public function destroy($id)
    {
        $wishlist = Wishlist::where('user_id', auth()->id())->findOrFail($id);
        $wishlist->delete();

        return redirect()->route('customer.wishlist.index')
            ->with('success', 'Produk berhasil dihapus dari wishlist.');
    }
}
