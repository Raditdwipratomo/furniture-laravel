<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $recentOrders = Pesanan::where('user_id', auth()->id())
            ->with('detail.produk')
            ->orderBy('tanggal_pesanan', 'desc')
            ->take(3)
            ->get();

        $totalOrders = Pesanan::where('user_id', auth()->id())->count();
        $totalSpent = Pesanan::where('user_id', auth()->id())
            ->where('status', '!=', 'dibatalkan')
            ->sum('total_harga');

        return view('customer.account.index', compact('recentOrders', 'totalOrders', 'totalSpent'));
    }
}
