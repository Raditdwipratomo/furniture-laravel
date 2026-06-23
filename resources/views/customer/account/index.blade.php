@extends('layouts.customer')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        {{-- Sidebar --}}
        @include('customer.partials.sidebar')

        {{-- Main content --}}
        <div class="lg:col-span-3 space-y-6">
            {{-- Welcome --}}
            <div class="bg-gradient-to-r from-amber-500 to-amber-600 rounded-xl p-6 text-white">
                <h1 class="text-2xl font-bold">Halo, {{ auth()->user()->nama }}!</h1>
                <p class="text-amber-100 mt-1">Selamat datang kembali di {{ config('app.name') }}</p>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-5">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-stone-800">{{ $totalOrders }}</p>
                            <p class="text-xs text-stone-500">Total Pesanan</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-5">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-lg font-bold text-stone-800">Rp {{ number_format($totalSpent, 0, ',', '.') }}</p>
                            <p class="text-xs text-stone-500">Total Belanja</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-5">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-stone-800">{{ auth()->user()->wishlists()->count() }}</p>
                            <p class="text-xs text-stone-500">Wishlist</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Orders --}}
            <div class="bg-white rounded-xl shadow-sm border border-stone-200">
                <div class="flex justify-between items-center p-5 border-b border-stone-200">
                    <h2 class="text-lg font-semibold text-stone-800">Pesanan Terbaru</h2>
                    <a href="{{ route('customer.orders.index') }}" class="text-sm text-amber-600 hover:text-amber-700 font-medium">Lihat Semua</a>
                </div>
                @if($recentOrders->isEmpty())
                    <div class="p-8 text-center text-stone-500">
                        <p>Belum ada pesanan.</p>
                        <a href="{{ url('/products') }}" class="mt-2 inline-block text-amber-600 hover:text-amber-700 font-medium">Mulai Belanja</a>
                    </div>
                @else
                    <div class="divide-y divide-stone-100">
                        @foreach($recentOrders as $order)
                        <a href="{{ route('customer.orders.show', $order->no_pesanan) }}" class="flex items-center justify-between p-5 hover:bg-stone-50 transition">
                            <div class="flex items-center space-x-4">
                                @if($order->detail->first())
                                <img src="{{ asset('storage/' . $order->detail->first()->produk->gambar_utama) }}" alt="" class="w-12 h-12 rounded-lg object-cover"
                                     onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%2748%27 height=%2748%27%3E%3Crect width=%2748%27 height=%2748%27 fill=%27%23f0f0f0%27/%3E%3Ctext x=%2724%27 y=%2728%27 text-anchor=%27middle%27 font-size=%278%27 fill=%27%23999%27%3ENo Image%3C/text%3E%3C/svg%3E'">
                                @endif
                                <div>
                                    <p class="font-medium text-stone-800">{{ $order->no_pesanan }}</p>
                                    <p class="text-xs text-stone-500">{{ \Carbon\Carbon::parse($order->tanggal_pesanan)->format('d M Y') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-stone-800">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $order->status_badge_class }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
