@extends('layouts.customer')

@section('title', 'Wishlist')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        @include('customer.partials.sidebar')

        <div class="lg:col-span-3">
            <h1 class="text-2xl font-bold text-stone-800 mb-6">Wishlist Saya</h1>

            @if($wishlists->isEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-8 text-center">
                    <svg class="mx-auto h-16 w-16 text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <p class="mt-4 text-stone-500">Wishlist Anda kosong.</p>
                    <a href="{{ url('/products') }}" class="mt-2 inline-block text-amber-600 hover:text-amber-700 font-medium">Jelajahi Produk</a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($wishlists as $item)
                    @if($item->produk)
                    <div class="bg-white rounded-xl shadow-sm border border-stone-200 overflow-hidden group">
                        <div class="relative">
                            <img src="{{ asset('storage/' . $item->produk->gambar_utama) }}" alt="{{ $item->produk->nama_produk }}"
                                 class="w-full h-48 object-cover"
                                 onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%27300%27 height=%27200%27%3E%3Crect width=%27300%27 height=%27200%27 fill=%27%23f0f0f0%27/%3E%3Ctext x=%27150%27 y=%27105%27 text-anchor=%27middle%27 font-size=%2714%27 fill=%27%23999%27%3ENo Image%3C/text%3E%3C/svg%3E'">
                            <form action="{{ route('customer.wishlist.destroy', $item->id) }}" method="POST" class="absolute top-2 right-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 bg-white rounded-full shadow flex items-center justify-center text-red-500 hover:text-red-700 transition">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                        <div class="p-4">
                            <h3 class="font-medium text-stone-800 truncate">{{ $item->produk->nama_produk }}</h3>
                            <p class="text-amber-600 font-bold mt-1">Rp {{ number_format($item->produk->harga, 0, ',', '.') }}</p>
                            @if($item->produk->stok > 0)
                                <form action="{{ route('cart.add') }}" method="POST" class="mt-3">
                                    @csrf
                                    <input type="hidden" name="produk_id" value="{{ $item->produk->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="w-full bg-amber-600 text-white py-2 rounded-lg hover:bg-amber-700 transition text-sm font-medium">
                                        Tambah ke Keranjang
                                    </button>
                                </form>
                            @else
                                <p class="mt-3 text-center text-sm text-red-500 font-medium">Stok Habis</p>
                            @endif
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
