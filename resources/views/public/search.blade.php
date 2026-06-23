@extends('layouts.customer')

@section('title', 'Pencarian: ' . $q . ' - FurniturKu')

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-stone-500 mb-8">
        <a href="{{ url('/') }}" class="hover:text-accent transition">Beranda</a>
        <span>/</span>
        <span class="text-stone-800 font-medium">Pencarian</span>
    </nav>

    {{-- Search Input --}}
    <div class="max-w-2xl mx-auto mb-10">
        <form action="{{ url('/search') }}" method="GET" class="relative">
            <input type="text" name="q" value="{{ $q }}" placeholder="Cari produk furnitur..."
                class="w-full pl-12 pr-4 py-4 border-2 border-stone-300 rounded-xl text-lg focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent shadow-sm"
                autofocus>
            <svg class="w-6 h-6 absolute left-4 top-1/2 -translate-y-1/2 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 bg-accent hover:bg-accent-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition">
                Cari
            </button>
        </form>
    </div>

    {{-- Results Header --}}
    @if($q)
    <div class="mb-6">
        <p class="text-stone-600">
            Ditemukan <span class="font-semibold text-stone-800">{{ $produks->total() }}</span> hasil untuk
            "<span class="font-semibold text-stone-800">{{ $q }}</span>"
        </p>
    </div>
    @endif

    {{-- Results Grid --}}
    @if($produks->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($produks as $produk)
            <x-product-card :produk="$produk" />
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $produks->links() }}
    </div>
    @elseif($q)
    {{-- Empty State --}}
    <div class="bg-white rounded-xl shadow p-16 text-center max-w-lg mx-auto">
        <svg class="w-24 h-24 text-stone-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <h3 class="text-xl font-semibold text-stone-700 mb-2">Tidak ada hasil ditemukan</h3>
        <p class="text-stone-500 mb-6">Coba gunakan kata kunci lain atau jelajahi produk kami</p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ url('/products') }}" class="inline-block bg-accent hover:bg-accent-700 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition">
                Lihat Semua Produk
            </a>
            <a href="{{ url('/') }}" class="inline-block bg-stone-100 hover:bg-stone-200 text-stone-700 px-6 py-2.5 rounded-lg text-sm font-medium transition">
                Kembali ke Beranda
            </a>
        </div>
    </div>
    @endif
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
