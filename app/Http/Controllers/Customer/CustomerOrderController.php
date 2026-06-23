<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Setting;
use Illuminate\Http\Request;

class CustomerOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Pesanan::where('user_id', auth()->id())
            ->with('detail.produk');

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $pesanans = $query->orderBy('tanggal_pesanan', 'desc')->paginate(10);

        return view('customer.orders.index', compact('pesanans'));
    }

    public function show($no_pesanan)
    {
        $pesanan = Pesanan::with(['detail.produk', 'pengiriman', 'pembayaran'])
            ->where('user_id', auth()->id())
            ->where('no_pesanan', $no_pesanan)
            ->firstOrFail();

        $reviewedProducts = \App\Models\Review::where('user_id', auth()->id())
            ->where('pesanan_id', $pesanan->id)
            ->pluck('produk_id')
            ->toArray();

        $midtransClientKey = Setting::get('midtrans_client_key', config('services.midtrans.client_key', ''));

        return view('customer.orders.show', compact('pesanan', 'reviewedProducts', 'midtransClientKey'));
    }

    public function cancel($no_pesanan)
    {
        $pesanan = Pesanan::where('user_id', auth()->id())
            ->where('no_pesanan', $no_pesanan)
            ->firstOrFail();

        if ($pesanan->status !== 'pending') {
            return back()->with('error', 'Pesanan hanya bisa dibatalkan saat status masih pending.');
        }

        $pesanan->update(['status' => 'dibatalkan']);

        return redirect()->route('customer.orders.show', $no_pesanan)
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }
}
