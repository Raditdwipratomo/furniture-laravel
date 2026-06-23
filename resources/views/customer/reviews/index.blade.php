@extends('layouts.customer')

@section('title', 'Review Saya')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        @include('customer.partials.sidebar')

        <div class="lg:col-span-3">
            <h1 class="text-2xl font-bold text-stone-800 mb-6">Review Saya</h1>

            @if($reviews->isEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-8 text-center">
                    <svg class="mx-auto h-16 w-16 text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                    <p class="mt-4 text-stone-500">Anda belum memberikan review.</p>
                    <a href="{{ route('customer.orders.index', ['status' => 'selesai']) }}" class="mt-2 inline-block text-amber-600 hover:text-amber-700 font-medium">Review Pesanan Selesai</a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($reviews as $review)
                    <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-5">
                        <div class="flex items-start space-x-4">
                            @if($review->produk)
                            <img src="{{ asset('storage/' . $review->produk->gambar_utama) }}" alt="" class="w-16 h-16 rounded-lg object-cover flex-shrink-0"
                                 onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%2764%27 height=%2764%27%3E%3Crect width=%2764%27 height=%2764%27 fill=%27%23f0f0f0%27/%3E%3Ctext x=%2732%27 y=%2736%27 text-anchor=%27middle%27 font-size=%2710%27 fill=%27%23999%27%3ENo Image%3C/text%3E%3C/svg%3E'">
                            @endif
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-medium text-stone-800">{{ $review->produk->nama_produk ?? 'Produk tidak tersedia' }}</h4>
                                    @if($review->is_approved)
                                        <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Disetujui</span>
                                    @else
                                        <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full">Menunggu</span>
                                    @endif
                                </div>

                                {{-- Stars --}}
                                <div class="flex space-x-0.5 mt-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-amber-400' : 'text-stone-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>

                                <p class="text-sm text-stone-600 mt-2">{{ $review->komentar }}</p>
                                <p class="text-xs text-stone-400 mt-2">
                                    {{ \Carbon\Carbon::parse($review->tanggal_review)->format('d M Y') }}
                                    @if($review->pesanan)
                                        | Pesanan: {{ $review->pesanan->no_pesanan }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $reviews->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
