@extends('layouts.customer')

@section('title', 'Pesanan Berhasil')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
    {{-- Success Icon --}}
    <div class="mx-auto w-20 h-20 rounded-full bg-green-100 flex items-center justify-center mb-6">
        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
    </div>

    <h1 class="text-3xl font-bold text-stone-800 mb-2">Terima Kasih!</h1>
    <p class="text-stone-600 mb-8">Pesanan Anda telah berhasil dibuat.</p>

    {{-- Order Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6 text-left mb-8">
        <div class="flex justify-between items-center mb-4">
            <div>
                <p class="text-sm text-stone-500">No. Pesanan</p>
                <p class="text-lg font-bold text-stone-800">{{ $pesanan->no_pesanan }}</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $pesanan->status_badge_class }}">
                {{ ucfirst($pesanan->status) }}
            </span>
        </div>

        <hr class="border-stone-200 my-4">

        <div class="space-y-3">
            @foreach($pesanan->detail as $item)
            <div class="flex items-center space-x-3">
                <img src="{{ asset('storage/' . $item->produk->gambar_utama) }}" alt="" class="w-12 h-12 rounded-lg object-cover"
                     onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%2748%27 height=%2748%27%3E%3Crect width=%2748%27 height=%2748%27 fill=%27%23f0f0f0%27/%3E%3Ctext x=%2724%27 y=%2728%27 text-anchor=%27middle%27 font-size=%278%27 fill=%27%23999%27%3ENo Image%3C/text%3E%3C/svg%3E'">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-stone-800 truncate">{{ $item->produk->nama_produk }}</p>
                    <p class="text-xs text-stone-500">{{ $item->quantity }} x Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                </div>
                <span class="text-sm font-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
            </div>
            @endforeach
        </div>

        <hr class="border-stone-200 my-4">

        <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-stone-600">Subtotal</span>
                <span class="font-medium">Rp {{ number_format($pesanan->subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-stone-600">Ongkos Kirim</span>
                <span class="font-medium">Rp {{ number_format($pesanan->ongkir, 0, ',', '.') }}</span>
            </div>
            @if($pesanan->diskon > 0)
            <div class="flex justify-between">
                <span class="text-stone-600">Diskon</span>
                <span class="font-medium text-green-600">- Rp {{ number_format($pesanan->diskon, 0, ',', '.') }}</span>
            </div>
            @endif
            <hr class="border-stone-200">
            <div class="flex justify-between text-base font-bold">
                <span>Total</span>
                <span class="text-amber-600">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="flex flex-col sm:flex-row justify-center gap-3">
        <a href="{{ route('customer.orders.show', $pesanan->no_pesanan) }}"
           class="bg-amber-600 text-white px-6 py-3 rounded-lg hover:bg-amber-700 transition font-semibold">
            Lihat Pesanan
        </a>
        <a href="{{ url('/products') }}"
           class="bg-stone-100 text-stone-700 px-6 py-3 rounded-lg hover:bg-stone-200 transition font-medium">
            Lanjut Belanja
        </a>
    </div>
</div>
@endsection
