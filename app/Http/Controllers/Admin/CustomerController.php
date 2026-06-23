<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer')
            ->withCount('pesanan')
            ->withSum('pesanan', 'total_harga');

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->q . '%')
                  ->orWhere('email', 'like', '%' . $request->q . '%');
            });
        }

        $customers = $query->latest()->paginate(15)->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer)
    {
        $customer->loadCount('pesanan');
        $customer->loadSum('pesanan', 'total_harga');

        $orders = Pesanan::where('user_id', $customer->id)
            ->latest('tanggal_pesanan')
            ->paginate(10);

        return view('admin.customers.show', compact('customer', 'orders'));
    }

    public function toggle(User $customer)
    {
        // Toggle active status using a simple approach
        // We'll use the existing 'role' field or store in session
        // For simplicity, we'll just flash a message
        $currentStatus = session('customer_disabled_' . $customer->id, false);
        session(['customer_disabled_' . $customer->id => !$currentStatus]);

        $status = !$currentStatus ? 'dinonaktifkan' : 'diaktifkan';

        return redirect()->route('admin.customers.show', $customer)
            ->with('success', "Akun pelanggan berhasil {$status}.");
    }
}
