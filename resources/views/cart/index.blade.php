@extends('layouts.customer')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-stone-800 mb-8">Keranjang Belanja</h1>

    @if($items->isEmpty())
        {{-- Empty cart --}}
        <div class="text-center py-16">
            <svg class="mx-auto h-24 w-24 text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
            </svg>
            <h3 class="mt-4 text-xl font-semibold text-stone-600">Keranjang Anda kosong</h3>
            <p class="mt-2 text-stone-500">Yuk mulai belanja furnitur impian Anda!</p>
            <a href="{{ url('/products') }}" class="mt-6 inline-block bg-amber-600 text-white px-6 py-3 rounded-lg hover:bg-amber-700 transition font-medium">
                Mulai Belanja
            </a>
        </div>
    @else
        {{-- Cart items --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Items table --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-stone-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-stone-50 border-b border-stone-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-stone-600 uppercase">Produk</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-stone-600 uppercase">Harga</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-stone-600 uppercase">Qty</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-stone-600 uppercase">Subtotal</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-stone-600 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-100">
                                @foreach($items as $item)
                                <tr x-data="{ qty: {{ $item->quantity }} }" class="hover:bg-stone-50 transition">
                                    <td class="px-4 py-4">
                                        <div class="flex items-center space-x-3">
                                            <img src="{{ asset('storage/' . ($item->produk->gambar && is_array($item->produk->gambar) && count($item->produk->gambar) > 0 ? $item->produk->gambar[0] : '')) }}"
                                                 alt="{{ $item->produk->nama_produk }}" 
                                                 class="w-16 h-16 rounded-lg object-cover flex-shrink-0"
                                                 onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%2764%27 height=%2764%27%3E%3Crect width=%2764%27 height=%2764%27 fill=%27%23f0f0f0%27/%3E%3Ctext x=%2732%27 y=%2736%27 text-anchor=%27middle%27 font-size=%2710%27 fill=%27%23999%27%3ENo Image%3C/text%3E%3C/svg%3E'">
                                            <div>
                                                <h4 class="font-medium text-stone-800 text-sm">{{ $item->produk->nama_produk }}</h4>
                                                <p class="text-xs text-stone-500">SKU: {{ $item->produk->sku }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-center text-sm text-stone-700">
                                        Rp {{ number_format($item->produk->harga, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-4">
                                        <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center justify-center space-x-1">
                                            @csrf
                                            @method('PUT')
                                            <button type="button" 
                                                    @click="qty = Math.max(1, qty - 1); $refs.qtyInput.value = qty; $refs.qtyForm.submit()"
                                                    class="w-8 h-8 rounded-lg bg-stone-100 hover:bg-stone-200 text-stone-600 flex items-center justify-center transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                            </button>
                                            <input type="number" name="quantity" x-ref="qtyInput" :value="qty" min="1" max="{{ $item->produk->stok }}"
                                                   @change="qty = $event.target.value; $refs.qtyForm.submit()"
                                                   class="w-12 h-8 text-center text-sm border border-stone-300 rounded-lg focus:ring-amber-500 focus:border-amber-500">
                                            <button type="button"
                                                    @click="qty = Math.min({{ $item->produk->stok }}, qty + 1); $refs.qtyInput.value = qty; $refs.qtyForm.submit()"
                                                    class="w-8 h-8 rounded-lg bg-stone-100 hover:bg-stone-200 text-stone-600 flex items-center justify-center transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                            </button>
                                            <input type="hidden" name="quantity" :value="qty">
                                            <button type="submit" x-ref="qtyForm" class="hidden"></button>
                                        </form>
                                    </td>
                                    <td class="px-4 py-4 text-right text-sm font-semibold text-stone-800">
                                        Rp {{ number_format($item->produk->harga * $item->quantity, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <form action="{{ route('cart.remove', $item->id) }}" method="POST" onsubmit="return confirm('Hapus item ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Summary --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6 sticky top-24">
                    <h3 class="text-lg font-semibold text-stone-800 mb-4">Ringkasan Belanja</h3>
                    <div class="space-y-3 border-b border-stone-200 pb-4 mb-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-stone-600">Jumlah Item</span>
                            <span class="font-medium">{{ $itemCount }} item</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-stone-600">Subtotal</span>
                            <span class="font-semibold text-stone-800">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="flex justify-between text-base font-bold text-stone-800 mb-6">
                        <span>Total</span>
                        <span class="text-amber-600">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    @auth
                        <a href="{{ route('checkout.index') }}" class="block w-full bg-amber-600 text-white text-center py-3 rounded-lg hover:bg-amber-700 transition font-semibold">
                            Lanjut ke Checkout
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="block w-full bg-amber-600 text-white text-center py-3 rounded-lg hover:bg-amber-700 transition font-semibold">
                            Login untuk Checkout
                        </a>
                    @endauth
                    <a href="{{ url('/products') }}" class="block w-full mt-3 bg-stone-100 text-stone-700 text-center py-3 rounded-lg hover:bg-stone-200 transition font-medium">
                        Lanjut Belanja
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
