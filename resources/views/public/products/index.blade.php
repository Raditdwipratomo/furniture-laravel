@extends('layouts.customer')

@section('title', 'Katalog Produk - FurniturKu')

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-stone-500 mb-8">
        <a href="{{ url('/') }}" class="hover:text-accent transition">Beranda</a>
        <span>/</span>
        <span class="text-stone-800 font-medium">Produk</span>
    </nav>

    <div class="flex flex-col lg:flex-row gap-8">
        {{-- Sidebar --}}
        <aside class="w-full lg:w-64 flex-shrink-0">
            <form method="GET" action="{{ url('/products') }}" id="filter-form">
                {{-- Categories --}}
                <div class="bg-white rounded-lg shadow p-5 mb-6">
                    <h3 class="font-semibold text-stone-800 mb-4">Kategori</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ url('/products') }}"
                                class="flex justify-between text-sm {{ !request('kategori') ? 'text-accent font-medium' : 'text-stone-600 hover:text-accent' }} transition">
                                <span>Semua Kategori</span>
                            </a>
                        </li>
                        @foreach($kategoris as $kategori)
                        <li>
                            <a href="{{ url('/products?kategori=' . $kategori->id) }}"
                                class="flex justify-between text-sm {{ request('kategori') == $kategori->id ? 'text-accent font-medium' : 'text-stone-600 hover:text-accent' }} transition">
                                <span>{{ $kategori->nama_kategori }}</span>
                                <span class="text-stone-400">({{ $kategori->produks_count }})</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Price Range --}}
                <div class="bg-white rounded-lg shadow p-5 mb-6">
                    <h3 class="font-semibold text-stone-800 mb-4">Rentang Harga</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs text-stone-500">Harga Minimum</label>
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Rp 0"
                                class="w-full mt-1 px-3 py-2 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-accent">
                        </div>
                        <div>
                            <label class="text-xs text-stone-500">Harga Maksimum</label>
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Rp 99.999.999"
                                class="w-full mt-1 px-3 py-2 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-accent">
                        </div>
                    </div>
                </div>

                {{-- In Stock --}}
                <div class="bg-white rounded-lg shadow p-5 mb-6">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="in_stock" value="1" {{ request('in_stock') ? 'checked' : '' }}
                            class="w-4 h-4 text-accent border-stone-300 rounded focus:ring-accent">
                        <span class="text-sm text-stone-700">Hanya Stok Tersedia</span>
                    </label>
                </div>

                {{-- Preserve sort --}}
                @if(request('sort'))
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                @endif

                <button type="submit" class="w-full bg-accent hover:bg-accent-700 text-white py-2.5 rounded-lg text-sm font-medium transition">
                    Terapkan Filter
                </button>
                <a href="{{ url('/products') }}" class="block text-center mt-2 text-sm text-stone-500 hover:text-accent transition">
                    Reset Filter
                </a>
            </form>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1">
            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <p class="text-stone-600">
                    <span class="font-semibold text-stone-800">{{ $produks->total() }}</span> produk ditemukan
                </p>
                <div class="flex items-center gap-3">
                    <label class="text-sm text-stone-500 whitespace-nowrap">Urutkan:</label>
                    <select onchange="window.location.href=this.value"
                        class="border border-stone-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-accent">
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga: Rendah - Tinggi</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga: Tinggi - Rendah</option>
                    </select>
                </div>
            </div>

            {{-- Product Grid --}}
            @if($produks->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($produks as $produk)
                    <x-product-card :produk="$produk" />
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $produks->links() }}
            </div>
            @else
            <div class="bg-white rounded-lg shadow p-16 text-center">
                <svg class="w-20 h-20 text-stone-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <h3 class="text-lg font-semibold text-stone-700 mb-2">Produk tidak ditemukan</h3>
                <p class="text-stone-500 mb-4">Coba ubah filter atau kata kunci pencarian Anda</p>
                <a href="{{ url('/products') }}" class="inline-block bg-accent hover:bg-accent-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition">
                    Lihat Semua Produk
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

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

@endsection
