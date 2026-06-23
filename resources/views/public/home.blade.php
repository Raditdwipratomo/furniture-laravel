@extends('layouts.customer')

@section('title', 'Beranda - FurniturKu')

@section('content')

{{-- Hero Banner Slider --}}
@if($banners->count() > 0)
<section x-data="{
    current: 0,
    total: {{ $banners->count() }},
    autoplay() {
        setInterval(() => {
            this.current = (this.current + 1) % this.total;
        }, 5000);
    }
}" x-init="autoplay()" class="relative overflow-hidden bg-stone-900">
    @foreach($banners as $i => $banner)
    <div x-show="current === {{ $i }}" x-transition:enter="transition ease-out duration-700"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-500"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="relative h-[300px] md:h-[450px] lg:h-[550px]"
         {{ $i > 0 ? 'x-cloak' : '' }}>
        @if($banner->gambar)
            <img src="{{ asset('storage/' . $banner->gambar) }}" alt="{{ $banner->judul }}"
                class="w-full h-full object-cover">
        @else
            <div class="w-full h-full bg-gradient-to-r from-walnut to-stone-700"></div>
        @endif
        <div class="absolute inset-0 bg-black/40 flex items-center">
            <div class="container mx-auto px-4">
                <div class="max-w-xl text-white">
                    <h2 class="text-3xl md:text-5xl font-bold mb-4">{{ $banner->judul }}</h2>
                    @if($banner->url)
                        <a href="{{ $banner->url }}" class="inline-block bg-accent hover:bg-accent-700 text-white px-8 py-3 rounded-lg font-semibold transition">
                            Lihat Selengkapnya
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach

    {{-- Dots --}}
    @if($banners->count() > 1)
    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
        @foreach($banners as $i => $banner)
        <button @click="current = {{ $i }}"
            :class="current === {{ $i }} ? 'bg-white w-8' : 'bg-white/50 w-3'"
            class="h-3 rounded-full transition-all duration-300"></button>
        @endforeach
    </div>
    @endif
</section>
@else
{{-- Fallback Hero --}}
<section class="bg-gradient-to-r from-walnut to-stone-700 py-20">
    <div class="container mx-auto px-4 text-center text-white">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Koleksi Furnitur Terbaik</h1>
        <p class="text-lg text-stone-200 mb-8">Temukan furnitur impian Anda dengan harga terjangkau</p>
        <a href="{{ url('/products') }}" class="inline-block bg-accent hover:bg-accent-700 text-white px-8 py-3 rounded-lg font-semibold transition">
            Lihat Produk
        </a>
    </div>
</section>
@endif

{{-- Featured Categories --}}
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-stone-800">Kategori Populer</h2>
            <p class="text-stone-500 mt-2">Jelajahi koleksi kami berdasarkan kategori</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($kategoris as $kategori)
            <a href="{{ url('/products?kategori=' . $kategori->id) }}"
                class="bg-white rounded-xl shadow hover:shadow-lg p-6 text-center transition-all duration-300 hover:-translate-y-1 group">
                <div class="w-16 h-16 bg-amber-50 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-amber-100 transition">
                    <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-stone-800 text-sm">{{ $kategori->nama_kategori }}</h3>
                <p class="text-xs text-stone-500 mt-1">{{ $kategori->produks_count ?? $kategori->produk_count }} produk</p>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- Featured Products --}}
@if($featuredProducts->count() > 0)
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="text-3xl font-bold text-stone-800">Produk Unggulan</h2>
                <p class="text-stone-500 mt-2">Koleksi terbaik pilihan kami untuk Anda</p>
            </div>
            <a href="{{ url('/products') }}" class="hidden md:inline-flex items-center text-accent hover:text-amber-700 font-medium transition">
                Lihat Semua
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredProducts as $produk)
                <x-product-card :produk="$produk" />
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Value Propositions --}}
<section class="py-12 bg-stone-100">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="flex items-center gap-4 justify-center md:justify-start">
                <div class="w-14 h-14 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-7 h-7 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-stone-800">Gratis Ongkir</h3>
                    <p class="text-sm text-stone-500">Pengiriman gratis ke seluruh Indonesia</p>
                </div>
            </div>
            <div class="flex items-center gap-4 justify-center">
                <div class="w-14 h-14 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-7 h-7 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-stone-800">Garansi Kualitas</h3>
                    <p class="text-sm text-stone-500">Produk berkualitas dengan garansi resmi</p>
                </div>
            </div>
            <div class="flex items-center gap-4 justify-center md:justify-end">
                <div class="w-14 h-14 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-7 h-7 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-stone-800">Pembayaran Aman</h3>
                    <p class="text-sm text-stone-500">Transaksi aman dengan berbagai metode bayar</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- New Arrivals --}}
@if($newestProducts->count() > 0)
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="text-3xl font-bold text-stone-800">Produk Terbaru</h2>
                <p class="text-stone-500 mt-2">Koleksi terbaru yang baru saja hadir</p>
            </div>
            <a href="{{ url('/products?sort=newest') }}" class="hidden md:inline-flex items-center text-accent hover:text-amber-700 font-medium transition">
                Lihat Semua
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($newestProducts as $produk)
                <x-product-card :produk="$produk" />
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection

@push('scripts')
<script>
function toggleWishlist(produkId, btn) {
    fetch('/api/wishlist/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ produk_id: produkId }),
    })
    .then(res => res.json())
    .then(data => {
        if (data.added) {
            btn.querySelector('svg').setAttribute('fill', 'currentColor');
            btn.querySelector('svg').classList.add('text-red-500');
            btn.querySelector('svg').classList.remove('text-stone-400');
        } else {
            btn.querySelector('svg').setAttribute('fill', 'none');
            btn.querySelector('svg').classList.remove('text-red-500');
            btn.querySelector('svg').classList.add('text-stone-400');
        }
    });
}
</script>
@endpush
