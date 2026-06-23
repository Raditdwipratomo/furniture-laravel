@extends('layouts.customer')

@section('title', 'Pesanan Saya')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        @include('customer.partials.sidebar')

        <div class="lg:col-span-3">
            <h1 class="text-2xl font-bold text-stone-800 mb-6">Pesanan Saya</h1>

            {{-- Status Filter Tabs --}}
            <div class="flex flex-wrap gap-2 mb-6">
                @php
                    $statuses = ['all' => 'Semua', 'pending' => 'Pending', 'diproses' => 'Diproses', 'dikirim' => 'Dikirim', 'selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan'];
                    $currentStatus = request('status', 'all');
                @endphp
                @foreach($statuses as $key => $label)
                <a href="{{ route('customer.orders.index', ['status' => $key]) }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $currentStatus === $key ? 'bg-amber-600 text-white' : 'bg-white border border-stone-200 text-stone-600 hover:bg-stone-50' }}">
                    {{ $label }}
                </a>
                @endforeach
            </div>

            {{-- Orders --}}
            @if($pesanans->isEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-8 text-center">
                    <svg class="mx-auto h-16 w-16 text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <p class="mt-4 text-stone-500">Belum ada pesanan.</p>
                    <a href="{{ url('/products') }}" class="mt-2 inline-block text-amber-600 hover:text-amber-700 font-medium">Mulai Belanja</a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($pesanans as $pesanan)
                    <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-5">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4">
                            <div>
                                <p class="font-semibold text-stone-800">{{ $pesanan->no_pesanan }}</p>
                                <p class="text-xs text-stone-500">{{ \Carbon\Carbon::parse($pesanan->tanggal_pesanan)->format('d M Y, H:i') }}</p>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold mt-2 sm:mt-0 {{ $pesanan->status_badge_class }}">
                                {{ ucfirst($pesanan->status) }}
                            </span>
                        </div>

                        <div class="flex items-center space-x-3 mb-4">
                            @if($pesanan->detail->first())
                            <img src="{{ asset('storage/' . $pesanan->detail->first()->produk->gambar_utama) }}" alt="" class="w-12 h-12 rounded-lg object-cover"
                                 onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%2748%27 height=%2748%27%3E%3Crect width=%2748%27 height=%2748%27 fill=%27%23f0f0f0%27/%3E%3Ctext x=%2724%27 y=%2728%27 text-anchor=%27middle%27 font-size=%278%27 fill=%27%23999%27%3ENo Image%3C/text%3E%3C/svg%3E'">
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-stone-700 truncate">
                                    {{ $pesanan->detail->first()->produk->nama_produk ?? 'Produk tidak tersedia' }}
                                    @if($pesanan->detail->count() > 1)
                                        <span class="text-stone-500">+{{ $pesanan->detail->count() - 1 }} lainnya</span>
                                    @endif
                                </p>
                                <p class="text-xs text-stone-500">{{ $pesanan->detail->sum('quantity') }} item</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between border-t border-stone-100 pt-4">
                            <p class="font-bold text-stone-800">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</p>
                            <a href="{{ route('customer.orders.show', $pesanan->no_pesanan) }}"
                               class="bg-amber-600 text-white px-4 py-2 rounded-lg hover:bg-amber-700 transition text-sm font-medium">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $pesanans->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
