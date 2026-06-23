<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembayaran::with('pesanan.user');

        if ($request->filled('status')) {
            $query->where('status_pembayaran', $request->status);
        }

        $pembayarans = $query->latest()->paginate(15)->withQueryString();

        return view('admin.payments.index', compact('pembayarans'));
    }

    public function show(Pembayaran $payment)
    {
        $payment->load('pesanan.user');

        return view('admin.payments.show', compact('payment'));
    }
}
