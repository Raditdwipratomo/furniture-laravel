@props(['produk'])

<div class="bg-white rounded-lg shadow hover:shadow-lg transition-all duration-300 overflow-hidden group">
    {{-- Image --}}
    <a href="{{ url('/products/' . $produk->id) }}" class="block relative overflow-hidden">
        @if($produk->gambar && is_array($produk->gambar) && count($produk->gambar) > 0)
            <img src="{{ asset('storage/' . $produk->gambar[0]) }}" alt="{{ $produk->nama_produk }}"
                class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-300">
        @else
            <div class="w-full h-56 bg-gray-200 flex items-center justify-center">
                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        @endif

        {{-- Wishlist Heart --}}
        @auth
        <button onclick="toggleWishlist({{ $produk->id }}, this)" class="absolute top-3 right-3 w-9 h-9 bg-white rounded-full shadow flex items-center justify-center hover:bg-red-50 transition z-10">
            <svg class="w-5 h-5 text-stone-400 hover:text-red-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
        </button>
        @endauth

        {{-- Stock badge --}}
        @if($produk->stok <= 0)
            <span class="absolute top-3 left-3 bg-red-500 text-white text-xs px-2 py-1 rounded-full font-medium">Habis</span>
        @endif
    </a>

    {{-- Info --}}
    <div class="p-4">
        @if($produk->kategori)
            <p class="text-xs text-stone-500 mb-1">{{ $produk->kategori->nama_kategori }}</p>
        @endif
        <a href="{{ url('/products/' . $produk->id) }}">
            <h3 class="font-semibold text-stone-800 mb-2 line-clamp-2 hover:text-accent transition">{{ $produk->nama_produk }}</h3>
        </a>

        {{-- Rating --}}
        @php $avg = $produk->avg_rating; @endphp
        @if($avg > 0)
            <div class="flex items-center gap-1 mb-2">
                @for($i = 1; $i <= 5; $i++)
                    <span class="text-sm {{ $i <= round($avg) ? 'text-amber-500' : 'text-stone-300' }}">★</span>
                @endfor
                <span class="text-xs text-stone-500 ml-1">({{ number_format($avg, 1) }})</span>
            </div>
        @endif

        <p class="text-lg font-bold text-walnut mb-3">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>

        {{-- Add to Cart --}}
        <form action="{{ url('/cart/add') }}" method="POST">
            @csrf
            <input type="hidden" name="produk_id" value="{{ $produk->id }}">
            <input type="hidden" name="quantity" value="1">
            <button type="submit"
                class="w-full bg-accent hover:bg-accent-700 text-white py-2 rounded-lg text-sm font-medium transition {{ $produk->stok <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                {{ $produk->stok <= 0 ? 'disabled' : '' }}>
                {{ $produk->stok > 0 ? 'Tambah ke Keranjang' : 'Stok Habis' }}
            </button>
        </form>
    </div>
</div>
