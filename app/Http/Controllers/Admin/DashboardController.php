<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\User;
use App\Models\DetailPesanan;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Pesanan::count();
        $totalRevenue = Pesanan::where('status', '!=', 'dibatalkan')->sum('total_harga');
        $totalProducts = Produk::count();
        $totalCustomers = User::where('role', 'customer')->count();

        $recentOrders = Pesanan::with('user')
            ->latest('tanggal_pesanan')
            ->take(10)
            ->get();

        $lowStockProducts = Produk::where('stok', '<', 5)
            ->where('is_active', true)
            ->orderBy('stok')
            ->take(10)
            ->get();

        // Revenue for last 30 days
        $revenueData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $revenue = Pesanan::whereDate('tanggal_pesanan', $date)
                ->where('status', '!=', 'dibatalkan')
                ->sum('total_harga');
            $revenueData[] = [
                'date' => now()->subDays($i)->format('d M'),
                'revenue' => $revenue,
            ];
        }

        // Top 5 best selling products
        $topProducts = DetailPesanan::select('produk_id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('produk_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->with('produk')
            ->get();

        return view('admin.dashboard.index', compact(
            'totalOrders',
            'totalRevenue',
            'totalProducts',
            'totalCustomers',
            'recentOrders',
            'lowStockProducts',
            'revenueData',
            'topProducts'
        ));
    }
}
