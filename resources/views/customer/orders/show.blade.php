@extends('layouts.customer')

@section('title', 'Detail Pesanan ' . $pesanan->no_pesanan)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        @include('customer.partials.sidebar')

        <div class="lg:col-span-3 space-y-6">
            {{-- Order Header --}}
            <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4">
                    <div>
                        <h1 class="text-2xl font-bold text-stone-800">{{ $pesanan->no_pesanan }}</h1>
                        <p class="text-sm text-stone-500">{{ \Carbon\Carbon::parse($pesanan->tanggal_pesanan)->format('d M Y, H:i') }}</p>
                    </div>
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold mt-2 sm:mt-0 {{ $pesanan->status_badge_class }}">
                        {{ ucfirst($pesanan->status) }}
                    </span>
                </div>

                @if($pesanan->status === 'pending')
                <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p class="text-sm text-yellow-800">
                        <span class="font-semibold">Menunggu Pembayaran</span> - Silakan lakukan pembayaran untuk memproses pesanan Anda.
                    </p>
                    @if($pesanan->pembayaran && $pesanan->pembayaran->snap_token)
                    <button onclick="payOrder()" class="mt-3 bg-amber-600 text-white px-4 py-2 rounded-lg hover:bg-amber-700 transition text-sm font-medium">
                        Bayar Sekarang
                    </button>
                    @endif
                </div>
                @endif
            </div>

            {{-- Order Items --}}
            <div class="bg-white rounded-xl shadow-sm border border-stone-200 overflow-hidden">
                <div class="p-5 border-b border-stone-200">
                    <h2 class="text-lg font-semibold text-stone-800">Item Pesanan</h2>
                </div>
                <div class="divide-y divide-stone-100">
                    @foreach($pesanan->detail as $item)
                    <div class="p-5 flex items-center space-x-4">
                        <img src="{{ asset('storage/' . $item->produk->gambar_utama) }}" alt="{{ $item->produk->nama_produk }}"
                             class="w-16 h-16 rounded-lg object-cover"
                             onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%2764%27 height=%2764%27%3E%3Crect width=%2764%27 height=%2764%27 fill=%27%23f0f0f0%27/%3E%3Ctext x=%2732%27 y=%2736%27 text-anchor=%27middle%27 font-size=%2710%27 fill=%27%23999%27%3ENo Image%3C/text%3E%3C/svg%3E'">
                        <div class="flex-1 min-w-0">
                            <h4 class="font-medium text-stone-800">{{ $item->produk->nama_produk }}</h4>
                            <p class="text-sm text-stone-500">{{ $item->quantity }} x Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-stone-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    {{-- Review button per item (if delivered and not reviewed) --}}
                    @if($pesanan->status === 'selesai' && !in_array($item->produk_id, $reviewedProducts))
                    <div class="px-5 pb-5" x-data="{ showReview: false, rating: 5, komentar: '' }">
                        <button @click="showReview = !showReview" class="text-sm text-amber-600 hover:text-amber-700 font-medium">
                            <span x-show="!showReview">Tulis Review</span>
                            <span x-show="showReview">Tutup</span>
                        </button>
                        <div x-show="showReview" x-cloak class="mt-3 bg-stone-50 rounded-lg p-4">
                            <form action="{{ route('customer.reviews.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="produk_id" value="{{ $item->produk_id }}">
                                <input type="hidden" name="pesanan_id" value="{{ $pesanan->id }}">

                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-stone-700 mb-1">Rating</label>
                                    <div class="flex space-x-1">
                                        @for($i = 1; $i <= 5; $i++)
                                        <button type="button" @click="rating = {{ $i }}" class="text-2xl transition"
                                                :class="rating >= {{ $i }} ? 'text-amber-400' : 'text-stone-300'">
                                            ★
                                        </button>
                                        @endfor
                                    </div>
                                    <input type="hidden" name="rating" :value="rating">
                                </div>

                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-stone-700 mb-1">Komentar</label>
                                    <textarea name="komentar" x-model="komentar" rows="3"
                                              class="w-full border border-stone-300 rounded-lg px-3 py-2 text-sm focus:ring-amber-500 focus:border-amber-500"
                                              placeholder="Tulis review Anda..."></textarea>
                                </div>

                                <button type="submit" class="bg-amber-600 text-white px-4 py-2 rounded-lg hover:bg-amber-700 transition text-sm font-medium">
                                    Kirim Review
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>

            {{-- Order Summary --}}
            <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                <h2 class="text-lg font-semibold text-stone-800 mb-4">Ringkasan Pesanan</h2>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-stone-600">Subtotal</span>
                        <span class="font-medium">Rp {{ number_format($pesanan->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-stone-600">Ongkos Kirim</span>
                        <span class="font-medium">Rp {{ number_format($pesanan->ongkir, 0, ',', '.') }}</span>
                    </div>
                    @if($pesanan->diskon > 0)
                    <div class="flex justify-between text-sm">
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

            {{-- Shipping Info --}}
            @if($pesanan->pengiriman)
            <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                <h2 class="text-lg font-semibold text-stone-800 mb-4">Informasi Pengiriman</h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-stone-600">Kurir</span>
                        <span class="font-medium">{{ strtoupper($pesanan->pengiriman->kurir) }}</span>
                    </div>
                    @if($pesanan->pengiriman->no_resi)
                    <div class="flex justify-between">
                        <span class="text-stone-600">No. Resi</span>
                        <span class="font-medium">{{ $pesanan->pengiriman->no_resi }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-stone-600">Status</span>
                        <span class="font-medium">{{ ucfirst($pesanan->pengiriman->status_pengiriman == "menunggu_pengiriman" ? "Menunggu Pengiriman" : $pesanan->pengiriman->status_pengiriman) }}</span>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-stone-200">
                    <p class="text-sm text-stone-600 mb-1">Alamat Pengiriman</p>
                    <p class="text-sm text-stone-800">{{ $pesanan->alamat_pengiriman }}</p>
                </div>
            </div>
            @endif

            {{-- Payment Status --}}
            @if($pesanan->pembayaran)
            <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                <h2 class="text-lg font-semibold text-stone-800 mb-4">Status Pembayaran</h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-stone-600">Metode</span>
                        <span class="font-medium">{{ ucfirst($pesanan->pembayaran->metode_pembayaran == "bank_transfer" ? "Bank Transfer" : $pesanan->pembayaran->metode_pembayaran) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-stone-600">Status</span>
                        @if($pesanan->pembayaran->status_pembayaran === 'berhasil')
                            <span class="text-green-600 font-medium">Lunas</span>
                        @elseif($pesanan->pembayaran->status_pembayaran === 'pending')
                            <span class="text-yellow-600 font-medium">Menunggu Pembayaran</span>
                        @else
                            <span class="text-red-600 font-medium">Gagal</span>
                        @endif
                    </div>
                    @if($pesanan->pembayaran->paid_at)
                    <div class="flex justify-between">
                        <span class="text-stone-600">Dibayar pada</span>
                        <span class="font-medium">{{ \Carbon\Carbon::parse($pesanan->pembayaran->paid_at)->format('d M Y, H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Cancel Button --}}
            @if($pesanan->status === 'pending')
            <div class="flex justify-end">
                <form action="{{ route('customer.orders.cancel', $pesanan->no_pesanan) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                    @csrf
                    <button type="submit" class="bg-red-600 text-white px-6 py-2.5 rounded-lg hover:bg-red-700 transition font-medium">
                        Batalkan Pesanan
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>

@if($pesanan->pembayaran && $pesanan->pembayaran->snap_token && $pesanan->status === 'pending')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $midtransClientKey }}"></script>
<script>
function payOrder() {
    window.snap.pay('{{ $pesanan->pembayaran->snap_token }}', {
        onSuccess: function(result) {
            fetch('{{ route("payment.handle") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    order_id: result.order_id,
                    transaction_status: result.transaction_status,
                    transaction_id: result.transaction_id,
                    payment_type: result.payment_type
                })
            }).then(() => {
                window.location.reload();
            });
        },
        onPending: function(result) {
            fetch('{{ route("payment.handle") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    order_id: result.order_id,
                    transaction_status: result.transaction_status,
                    transaction_id: result.transaction_id,
                    payment_type: result.payment_type
                })
            }).then(() => {
                window.location.reload();
            });
        },
        onError: function(result) {
            alert('Pembayaran gagal. Silakan coba lagi.');
        }
    });
}
</script>
@endif
@endsection
