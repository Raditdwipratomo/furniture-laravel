<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\DetailKeranjang;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Pengiriman;
use App\Models\Pembayaran;
use App\Models\Alamat;
use App\Models\Kupon;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $keranjang = Keranjang::with('detailKeranjang.produk')
            ->where('user_id', auth()->id())
            ->first();

        if (!$keranjang || $keranjang->detailKeranjang->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        $items = $keranjang->detailKeranjang;
        $subtotal = $items->sum(function ($item) {
            return $item->produk->harga * $item->quantity;
        });
        $totalBerat = $items->sum(function ($item) {
            return $item->produk->berat * $item->quantity;
        });

        $alamat = Alamat::where('user_id', auth()->id())->get();

        $midtransClientKey = Setting::get('midtrans_client_key', config('services.midtrans.client_key', ''));

        return view('checkout.index', compact('items', 'subtotal', 'totalBerat', 'alamat', 'midtransClientKey'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'alamat_id' => 'required|exists:alamats,id',
            'kurir' => 'required|string',
            'service' => 'required|string',
            'ongkir' => 'required|integer|min:0',
            'coupon_code' => 'nullable|string',
            'catatan' => 'nullable|string|max:500',
        ]);

        $alamat = Alamat::where('id', $request->alamat_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $keranjang = Keranjang::with('detailKeranjang.produk')
            ->where('user_id', auth()->id())
            ->first();

        if (!$keranjang || $keranjang->detailKeranjang->isEmpty()) {
            return response()->json(['message' => 'Keranjang kosong.'], 422);
        }

        $items = $keranjang->detailKeranjang;

        try {
            $result = DB::transaction(function () use ($items, $alamat, $request) {
                // 1. Validate stock for all items
                foreach ($items as $item) {
                    if (!$item->produk->is_active) {
                        throw new \Exception("Produk {$item->produk->nama_produk} tidak tersedia.");
                    }
                    if ($item->produk->stok < $item->quantity) {
                        throw new \Exception("Stok {$item->produk->nama_produk} tidak mencukupi.");
                    }
                }

                // 2. Calculate subtotal
                $subtotal = $items->sum(function ($item) {
                    return $item->produk->harga * $item->quantity;
                });

                // 3. Calculate discount
                $diskon = 0;
                $kupon = null;
                if ($request->coupon_code) {
                    $kupon = Kupon::where('kode', $request->coupon_code)->first();
                    if ($kupon && $kupon->isValid()) {
                        if ($kupon->min_order > 0 && $subtotal < $kupon->min_order) {
                            throw new \Exception("Minimal order untuk kupon ini adalah Rp " . number_format($kupon->min_order, 0, ',', '.'));
                        }
                        $diskon = $kupon->calculateDiscount($subtotal);
                    }
                }

                $ongkir = (int) $request->ongkir;
                $totalHarga = $subtotal - $diskon + $ongkir;

                // 4. Create pesanan
                $pesanan = Pesanan::create([
                    'user_id' => auth()->id(),
                    'no_pesanan' => Pesanan::generateOrderNumber(),
                    'tanggal_pesanan' => now(),
                    'total_harga' => $totalHarga,
                    'subtotal' => $subtotal,
                    'ongkir' => $ongkir,
                    'diskon' => $diskon,
                    'status' => 'pending',
                    'alamat_pengiriman' => $alamat->full_address,
                    'catatan' => $request->catatan,
                ]);

                // 5. Create detail_pesanan and decrement stock atomically
                foreach ($items as $item) {
                    DetailPesanan::create([
                        'pesanan_id' => $pesanan->id,
                        'produk_id' => $item->produk_id,
                        'quantity' => $item->quantity,
                        'harga' => $item->produk->harga,
                        'subtotal' => $item->produk->harga * $item->quantity,
                    ]);

                    // Decrement stock atomically
                    $affected = DB::table('produks')
                        ->where('id', $item->produk_id)
                        ->where('stok', '>=', $item->quantity)
                        ->decrement('stok', $item->quantity);

                    if ($affected === 0) {
                        throw new \Exception("Stok {$item->produk->nama_produk} tidak mencukupi.");
                    }
                }

                // 6. Create pengiriman
                Pengiriman::create([
                    'pesanan_id' => $pesanan->id,
                    'kurir' => $request->kurir,
                    'ongkir' => $ongkir,
                    'status_pengiriman' => 'menunggu_pengiriman',
                ]);

                // 7. Create pembayaran with pending status
                Pembayaran::create([
                    'pesanan_id' => $pesanan->id,
                    'metode_pembayaran' => 'midtrans',
                    'jumlah' => $totalHarga,
                    'status_pembayaran' => 'pending',
                ]);

                // 8. Increment coupon used_count
                if ($kupon) {
                    $kupon->increment('used_count');
                }

                // 9. Clear cart
                $items->each(function ($item) {
                    $item->delete();
                });

                session(['cart_count' => 0]);

                return $pesanan;
            });

            // Generate snap token
            $pembayaran = Pembayaran::where('pesanan_id', $result->id)->first();

            // Configure Midtrans from DB settings (with fallback to config)
            \Midtrans\Config::$serverKey = Setting::get('midtrans_server_key', config('services.midtrans.server_key', ''));
            \Midtrans\Config::$isProduction = filter_var(Setting::get('midtrans_is_production', config('services.midtrans.is_production', false)), FILTER_VALIDATE_BOOLEAN);
            \Midtrans\Config::$isSanitized = filter_var(Setting::get('midtrans_is_sanitized', config('services.midtrans.is_sanitized', true)), FILTER_VALIDATE_BOOLEAN);
            \Midtrans\Config::$is3ds = filter_var(Setting::get('midtrans_is_3ds', config('services.midtrans.is_3ds', true)), FILTER_VALIDATE_BOOLEAN);

            $params = [
                'transaction_details' => [
                    'order_id' => $result->no_pesanan,
                    'gross_amount' => $result->total_harga,
                ],
                'customer_details' => [
                    'first_name' => auth()->user()->nama,
                    'email' => auth()->user()->email,
                    'phone' => auth()->user()->no_hp,
                ],
                'item_details' => $result->detail->map(function ($item) {
                    return [
                        'id' => $item->produk_id,
                        'price' => $item->harga,
                        'quantity' => $item->quantity,
                        'name' => substr($item->produk->nama_produk, 0, 50),
                    ];
                })->toArray(),
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);

            $pembayaran->update(['snap_token' => $snapToken]);

            return response()->json([
                'snap_token' => $snapToken,
                'no_pesanan' => $result->no_pesanan,
                'redirect_url' => route('checkout.success', $result->no_pesanan),
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function success($no_pesanan)
    {
        $pesanan = Pesanan::with(['detail.produk', 'pengiriman', 'pembayaran'])
            ->where('user_id', auth()->id())
            ->where('no_pesanan', $no_pesanan)
            ->firstOrFail();

        return view('checkout.success', compact('pesanan'));
    }
}
