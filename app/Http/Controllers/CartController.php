<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\DetailKeranjang;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            $keranjang = Keranjang::with('detailKeranjang.produk')
                ->where('user_id', auth()->id())
                ->first();
            
            $items = $keranjang ? $keranjang->detailKeranjang : collect();
        } else {
            // Guest cart from session
            $sessionCart = session('cart', []);
            $items = collect();
            
            foreach ($sessionCart as $produkId => $data) {
                $produk = Produk::find($produkId);
                if ($produk) {
                    $items->push((object) [
                        'id' => $produkId,
                        'produk' => $produk,
                        'quantity' => $data['quantity'],
                    ]);
                }
            }
        }

        $subtotal = $items->sum(function($item) {
            return $item->produk->harga * $item->quantity;
        });

        $itemCount = $items->sum('quantity');

        return view('cart.index', compact('items', 'subtotal', 'itemCount'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'quantity' => 'integer|min:1',
        ]);

        $produk = Produk::findOrFail($request->produk_id);
        $quantity = $request->get('quantity', 1);

        if ($produk->stok < $quantity) {
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        if (!$produk->is_active) {
            return back()->with('error', 'Produk tidak tersedia.');
        }

        DB::transaction(function () use ($produk, $quantity) {
            if (auth()->check()) {
                $keranjang = Keranjang::firstOrCreate(['user_id' => auth()->id()]);
                
                $detail = DetailKeranjang::where('keranjang_id', $keranjang->id)
                    ->where('produk_id', $produk->id)
                    ->first();

                if ($detail) {
                    $newQuantity = $detail->quantity + $quantity;
                    if ($produk->stok < $newQuantity) {
                        throw new \Exception('Stok tidak mencukupi.');
                    }
                    $detail->update(['quantity' => $newQuantity]);
                } else {
                    DetailKeranjang::create([
                        'keranjang_id' => $keranjang->id,
                        'produk_id' => $produk->id,
                        'quantity' => $quantity,
                    ]);
                }

                // Update session cart count
                $cartCount = DetailKeranjang::where('keranjang_id', $keranjang->id)->sum('quantity');
                session(['cart_count' => $cartCount]);
            } else {
                // Guest - use session
                $cart = session('cart', []);
                if (isset($cart[$produk->id])) {
                    $newQuantity = $cart[$produk->id]['quantity'] + $quantity;
                    if ($produk->stok < $newQuantity) {
                        throw new \Exception('Stok tidak mencukupi.');
                    }
                    $cart[$produk->id]['quantity'] = $newQuantity;
                } else {
                    $cart[$produk->id] = ['quantity' => $quantity];
                }
                session(['cart' => $cart]);
                
                $cartCount = collect($cart)->sum('quantity');
                session(['cart_count' => $cartCount]);
            }
        });

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($id, $request) {
            if (auth()->check()) {
                $detail = DetailKeranjang::whereHas('keranjang', function($q) {
                    $q->where('user_id', auth()->id());
                })->findOrFail($id);

                if ($detail->produk->stok < $request->quantity) {
                    throw new \Exception('Stok tidak mencukupi.');
                }

                $detail->update(['quantity' => $request->quantity]);

                $cartCount = DetailKeranjang::where('keranjang_id', $detail->keranjang_id)->sum('quantity');
                session(['cart_count' => $cartCount]);
            } else {
                $cart = session('cart', []);
                if (isset($cart[$id])) {
                    $produk = Produk::find($id);
                    if ($produk && $produk->stok >= $request->quantity) {
                        $cart[$id]['quantity'] = $request->quantity;
                        session(['cart' => $cart]);
                        
                        $cartCount = collect($cart)->sum('quantity');
                        session(['cart_count' => $cartCount]);
                    } else {
                        throw new \Exception('Stok tidak mencukupi.');
                    }
                }
            }
        });

        return back()->with('success', 'Keranjang berhasil diupdate.');
    }

    public function remove($id)
    {
        if (auth()->check()) {
            $detail = DetailKeranjang::whereHas('keranjang', function($q) {
                $q->where('user_id', auth()->id());
            })->findOrFail($id);

            $detail->delete();

            $cartCount = DetailKeranjang::where('keranjang_id', $detail->keranjang_id)->sum('quantity');
            session(['cart_count' => $cartCount]);
        } else {
            $cart = session('cart', []);
            unset($cart[$id]);
            session(['cart' => $cart]);
            
            $cartCount = collect($cart)->sum('quantity');
            session(['cart_count' => $cartCount]);
        }

        return back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }
}
