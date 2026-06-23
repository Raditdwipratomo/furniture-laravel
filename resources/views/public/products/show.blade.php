@extends('layouts.customer')

@section('title', $produk->nama_produk . ' - FurniturKu')

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-stone-500 mb-8 flex-wrap">
        <a href="{{ url('/') }}" class="hover:text-accent transition">Beranda</a>
        <span>/</span>
        <a href="{{ url('/products') }}" class="hover:text-accent transition">Produk</a>
        <span>/</span>
        @if($produk->kategori)
            <a href="{{ url('/products?kategori=' . $produk->kategori_id) }}" class="hover:text-accent transition">{{ $produk->kategori->nama_kategori }}</a>
            <span>/</span>
        @endif
        <span class="text-stone-800 font-medium line-clamp-1">{{ $produk->nama_produk }}</span>
    </nav>

    {{-- Product Detail --}}
    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-12" x-data="{
        selectedIndex: 0,
        images: {{ json_encode($produk->gambar ?? []) }},
        qty: 1,
        maxQty: {{ $produk->stok }},
    }">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
            {{-- Image Gallery --}}
            <div class="p-6 lg:p-8">
                {{-- Main Image --}}
                <div class="relative mb-4 rounded-lg overflow-hidden bg-gray-100 aspect-square">
                    <template x-if="images.length > 0">
                        <img :src="'/storage/' + images[selectedIndex]" :alt="'{{ $produk->nama_produk }}'"
                            class="w-full h-full object-cover">
                    </template>
                    <template x-if="images.length === 0">
                        <div class="w-full h-full flex items-center justify-center bg-gray-200">
                            <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </template>
                </div>

                {{-- Thumbnails --}}
                <template x-if="images.length > 1">
                    <div class="flex gap-3 overflow-x-auto">
                        <template x-for="(img, idx) in images" :key="idx">
                            <button @click="selectedIndex = idx"
                                :class="selectedIndex === idx ? 'ring-2 ring-accent ring-offset-2' : 'ring-1 ring-stone-200'"
                                class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0 transition">
                                <img :src="'/storage/' + img" class="w-full h-full object-cover">
                            </button>
                        </template>
                    </div>
                </template>
            </div>

            {{-- Product Info --}}
            <div class="p-6 lg:p-8 flex flex-col">
                @if($produk->kategori)
                    <a href="{{ url('/products?kategori=' . $produk->kategori_id) }}"
                        class="text-sm text-accent hover:text-amber-700 font-medium mb-2 transition">
                        {{ $produk->kategori->nama_kategori }}
                    </a>
                @endif

                <h1 class="text-2xl lg:text-3xl font-bold text-stone-800 mb-3">{{ $produk->nama_produk }}</h1>

                {{-- SKU --}}
                <p class="text-sm text-stone-500 mb-4">SKU: <span class="text-stone-700">{{ $produk->sku ?? '-' }}</span></p>

                {{-- Rating --}}
                @if($avgRating > 0)
                <div class="flex items-center gap-2 mb-4">
                    <div class="flex items-center gap-0.5">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="text-lg {{ $i <= round($avgRating) ? 'text-amber-500' : 'text-stone-300' }}">★</span>
                        @endfor
                    </div>
                    <span class="text-sm text-stone-600">{{ number_format($avgRating, 1) }} ({{ $produk->reviews->count() }} ulasan)</span>
                </div>
                @else
                <div class="flex items-center gap-2 mb-4">
                    <div class="flex items-center gap-0.5">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="text-lg text-stone-300">★</span>
                        @endfor
                    </div>
                    <span class="text-sm text-stone-500">Belum ada ulasan</span>
                </div>
                @endif

                {{-- Price --}}
                <div class="mb-6">
                    <p class="text-3xl font-bold text-walnut">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
                </div>

                {{-- Stock Badge --}}
                <div class="mb-6">
                    @if($produk->stok > 0)
                        <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 px-3 py-1.5 rounded-full text-sm font-medium">
                            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                            Stok Tersedia ({{ $produk->stok }})
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-700 px-3 py-1.5 rounded-full text-sm font-medium">
                            <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                            Stok Habis
                        </span>
                    @endif
                </div>

                {{-- Quantity + Add to Cart --}}
                @if($produk->stok > 0)
                <div class="flex items-center gap-4 mb-4">
                    <div class="flex items-center border border-stone-300 rounded-lg">
                        <button @click="qty = Math.max(1, qty - 1)"
                            class="w-10 h-10 flex items-center justify-center text-stone-600 hover:bg-stone-100 transition rounded-l-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                            </svg>
                        </button>
                        <input type="number" x-model="qty" min="1" :max="maxQty"
                            class="w-14 h-10 text-center border-x border-stone-300 text-sm focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                        <button @click="qty = Math.min(maxQty, qty + 1)"
                            class="w-10 h-10 flex items-center justify-center text-stone-600 hover:bg-stone-100 transition rounded-r-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <form action="{{ url('/cart/add') }}" method="POST" class="flex gap-3 mb-6">
                    @csrf
                    <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                    <input type="hidden" name="quantity" x-model="qty">
                    <button type="submit"
                        class="flex-1 bg-accent hover:bg-accent-700 text-white py-3 rounded-lg font-semibold transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                        </svg>
                        Tambah ke Keranjang
                    </button>
                </form>
                @else
                <div class="mb-6">
                    <button disabled class="w-full bg-stone-300 text-stone-500 py-3 rounded-lg font-semibold cursor-not-allowed">
                        Stok Habis
                    </button>
                </div>
                @endif

                {{-- Wishlist --}}
                @auth
                <button onclick="toggleWishlistDetail({{ $produk->id }}, this)"
                    class="flex items-center gap-2 text-stone-600 hover:text-red-500 transition mb-6">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <span class="text-sm font-medium">Tambah ke Wishlist</span>
                </button>
                @endauth

                {{-- Description --}}
                @if($produk->deskripsi)
                <div class="mt-4 border-t border-stone-200 pt-6">
                    <h3 class="font-semibold text-stone-800 mb-3">Deskripsi Produk</h3>
                    <div class="text-stone-600 text-sm leading-relaxed whitespace-pre-line">{!! nl2br(e($produk->deskripsi)) !!}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Reviews Section --}}
    <div class="bg-white rounded-xl shadow-lg p-6 lg:p-8 mb-12">
        <h2 class="text-2xl font-bold text-stone-800 mb-6">Ulasan Pelanggan</h2>

        {{-- Average Rating Summary --}}
        <div class="flex items-center gap-6 mb-8 pb-6 border-b border-stone-200">
            <div class="text-center">
                <p class="text-5xl font-bold text-walnut">{{ number_format($avgRating, 1) }}</p>
                <div class="flex items-center gap-0.5 mt-2 justify-center">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="text-xl {{ $i <= round($avgRating) ? 'text-amber-500' : 'text-stone-300' }}">★</span>
                    @endfor
                </div>
                <p class="text-sm text-stone-500 mt-1">{{ $produk->reviews->count() }} ulasan</p>
            </div>
        </div>

        {{-- Review List --}}
        @if($produk->reviews->count() > 0)
            <div class="space-y-6">
                @foreach($produk->reviews as $review)
                <div class="border-b border-stone-100 pb-6 last:border-0 last:pb-0">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                            <span class="text-accent font-semibold text-sm">{{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}</span>
                        </div>
                        <div>
                            <p class="font-medium text-stone-800">{{ $review->user->name ?? 'Pengguna' }}</p>
                            <p class="text-xs text-stone-500">{{ \Carbon\Carbon::parse($review->tanggal_review)->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-0.5 mb-2 ml-13">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="text-sm {{ $i <= $review->rating ? 'text-amber-500' : 'text-stone-300' }}">★</span>
                        @endfor
                    </div>
                    <p class="text-stone-600 text-sm ml-13">{{ $review->komentar }}</p>
                </div>
                @endforeach
            </div>
        @else
            <p class="text-stone-500 text-center py-8">Belum ada ulasan untuk produk ini.</p>
        @endif
    </div>

    {{-- Related Products --}}
    @if($relatedProducts->count() > 0)
    <section>
        <h2 class="text-2xl font-bold text-stone-800 mb-6">Produk Terkait</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedProducts as $relProduk)
                <x-product-card :produk="$relProduk" />
            @endforeach
        </div>
    </section>
    @endif
</div>

@push('scripts')
<script>
function toggleWishlistDetail(produkId, btn) {
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
        const svg = btn.querySelector('svg');
        const text = btn.querySelector('span');
        if (data.added) {
            svg.setAttribute('fill', 'currentColor');
            svg.classList.add('text-red-500');
            svg.classList.remove('text-stone-600');
            text.textContent = 'Hapus dari Wishlist';
        } else {
            svg.setAttribute('fill', 'none');
            svg.classList.remove('text-red-500');
            svg.classList.add('text-stone-600');
            text.textContent = 'Tambah ke Wishlist';
        }
    });
}

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

@endsection
