@extends('layouts.customer')

@section('title', 'Checkout')

@push('styles')
<style>
    .step-active { border-color: #d97706; color: #d97706; }
    .step-done { border-color: #16a34a; color: #16a34a; background-color: #f0fdf4; }
</style>
@endpush

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="checkoutApp()" x-init="init()">
    <h1 class="text-3xl font-bold text-stone-800 mb-8">Checkout</h1>

    {{-- Step indicator --}}
    <div class="flex items-center justify-center mb-8 space-x-2">
        <template x-for="(s, i) in steps" :key="i">
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center text-sm font-bold transition"
                     :class="currentStep > i ? 'step-done' : (currentStep === i ? 'step-active' : 'border-stone-300 text-stone-400')">
                    <span x-text="i + 1"></span>
                </div>
                <span class="ml-2 text-sm hidden sm:inline" :class="currentStep >= i ? 'text-stone-800 font-medium' : 'text-stone-400'" x-text="s"></span>
                <div x-show="i < steps.length - 1" class="w-8 sm:w-16 h-0.5 mx-2" :class="currentStep > i ? 'bg-green-500' : 'bg-stone-200'"></div>
            </div>
        </template>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            {{-- Step 1: Address --}}
            <div x-show="currentStep === 0" x-cloak>
                <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                    <h2 class="text-lg font-semibold text-stone-800 mb-4">Pilih Alamat Pengiriman</h2>

                    @if($alamat->isEmpty())
                        <p class="text-stone-500 mb-4">Anda belum memiliki alamat.</p>
                    @else
                        <div class="space-y-3">
                            @foreach($alamat as $addr)
                            <label class="block border rounded-lg p-4 cursor-pointer transition hover:border-amber-400"
                                   :class="selectedAddress === {{ $addr->id }} ? 'border-amber-500 bg-amber-50' : 'border-stone-200'">
                                <div class="flex items-start">
                                    <input type="radio" name="alamat_id" value="{{ $addr->id }}"
                                           x-model="selectedAddress"
                                           @change="onAddressChange()"
                                           class="mt-1 text-amber-600 focus:ring-amber-500">
                                    <div class="ml-3">
                                        <div class="flex items-center space-x-2">
                                            <span class="font-semibold text-stone-800">{{ $addr->label }}</span>
                                            @if($addr->is_default)
                                                <span class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">Default</span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-stone-600 mt-1">{{ $addr->nama_penerima }} - {{ $addr->no_hp }}</p>
                                        <p class="text-sm text-stone-500">{{ $addr->full_address }}</p>
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    @endif

                    <a href="{{ route('customer.addresses.create') }}" class="mt-4 inline-flex items-center text-sm text-amber-600 hover:text-amber-700 font-medium">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Alamat Baru
                    </a>

                    <div class="mt-6 flex justify-end">
                        <button @click="nextStep()" :disabled="!selectedAddress"
                                class="bg-amber-600 text-white px-6 py-2.5 rounded-lg hover:bg-amber-700 transition font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                            Lanjut
                        </button>
                    </div>
                </div>
            </div>

            {{-- Step 2: Shipping --}}
            <div x-show="currentStep === 1" x-cloak>
                <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                    <h2 class="text-lg font-semibold text-stone-800 mb-4">Metode Pengiriman</h2>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-stone-700 mb-1">Kurir</label>
                        <select x-model="selectedCourier" @change="loadShipping()"  class="w-full border border-stone-300 rounded-lg px-3 py-2 focus:ring-amber-500 focus:border-amber-500">
                            <option value="">Pilih Kurir</option>
                            <option value="jne">JNE</option>
                            <option value="tiki">TIKI</option>
                            <option value="pos">POS Indonesia</option>
                        </select>
                    </div>

                    {{-- Loading --}}
                    <div x-show="shippingLoading" class="text-center py-8">
                        <svg class="animate-spin h-8 w-8 text-amber-600 mx-auto" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <p class="text-sm text-stone-500 mt-2">Memuat opsi pengiriman...</p>
                    </div>

                    {{-- Shipping options --}}
                    <div x-show="!shippingLoading && shippingOptions.length > 0" class="space-y-3">
                        <template x-for="(opt, i) in shippingOptions" :key="i">
                            <label class="block border rounded-lg p-4 cursor-pointer transition hover:border-amber-400"
                                   :class="selectedShippingIndex === i ? 'border-amber-500 bg-amber-50' : 'border-stone-200'">
                                <div class="flex items-center">
                                    <input type="radio" name="shipping" :value="i" x-model="selectedShippingIndex" class="text-amber-600 focus:ring-amber-500">
                                    <div class="ml-3 flex-1">
                                        <div class="flex justify-between">
                                            <span class="font-medium text-stone-800" x-text="opt.service + ' - ' + opt.description"></span>
                                            <span class="font-semibold text-amber-600" x-text="'Rp ' + formatNumber(opt.cost)"></span>
                                        </div>
                                        <p class="text-xs text-stone-500 mt-1">Estimasi: <span x-text="opt.etd"></span></p>
                                    </div>
                                </div>
                            </label>
                        </template>
                    </div>

                    <div x-show="!shippingLoading && shippingOptions.length === 0 && selectedCourier" class="text-center py-8 text-stone-500">
                        <p>Tidak ada opsi pengiriman tersedia.</p>
                    </div>

                    <div class="mt-6 flex justify-between">
                        <button @click="currentStep = 0" class="px-6 py-2.5 rounded-lg border border-stone-300 text-stone-700 hover:bg-stone-50 transition font-medium">
                            Kembali
                        </button>
                        <button @click="nextStep()" :disabled="selectedShippingIndex === null"
                                class="bg-amber-600 text-white px-6 py-2.5 rounded-lg hover:bg-amber-700 transition font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                            Lanjut
                        </button>
                    </div>
                </div>
            </div>

            {{-- Step 3: Summary --}}
            <div x-show="currentStep === 2" x-cloak>
                <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                    <h2 class="text-lg font-semibold text-stone-800 mb-4">Ringkasan Pesanan</h2>

                    <div class="space-y-3 mb-6">
                        @foreach($items as $item)
                        <div class="flex items-center space-x-3 py-2 border-b border-stone-100">
                            <img src="{{ asset('storage/' . $item->produk->gambar_utama) }}" alt="" class="w-12 h-12 rounded-lg object-cover"
                                 onerror="this.src='https://via.placeholder.com/48x48?text=No+Image'">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-stone-800 truncate">{{ $item->produk->nama_produk }}</p>
                                <p class="text-xs text-stone-500">{{ $item->quantity }} x Rp {{ number_format($item->produk->harga, 0, ',', '.') }}</p>
                            </div>
                            <span class="text-sm font-semibold text-stone-800">Rp {{ number_format($item->produk->harga * $item->quantity, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>

                    {{-- Coupon --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-stone-700 mb-1">Kode Kupon</label>
                        <div class="flex space-x-2">
                            <input type="text" x-model="couponCode" placeholder="Masukkan kode kupon"
                                   class="flex-1 border border-stone-300 rounded-lg px-3 py-2 text-sm focus:ring-amber-500 focus:border-amber-500">
                            <button @click="validateCoupon()" class="bg-stone-700 text-white px-4 py-2 rounded-lg text-sm hover:bg-stone-800 transition font-medium">
                                Terapkan
                            </button>
                        </div>
                        <p x-show="couponMessage" class="text-sm mt-1" :class="couponValid ? 'text-green-600' : 'text-red-600'" x-text="couponMessage"></p>
                    </div>

                    {{-- Notes --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-stone-700 mb-1">Catatan (Opsional)</label>
                        <textarea x-model="catatan" rows="3" placeholder="Catatan untuk pesanan..."
                                  class="w-full border border-stone-300 rounded-lg px-3 py-2 text-sm focus:ring-amber-500 focus:border-amber-500"></textarea>
                    </div>

                    <div class="mt-6 flex justify-between">
                        <button @click="currentStep = 1" class="px-6 py-2.5 rounded-lg border border-stone-300 text-stone-700 hover:bg-stone-50 transition font-medium">
                            Kembali
                        </button>
                        <button @click="nextStep()"
                                class="bg-amber-600 text-white px-6 py-2.5 rounded-lg hover:bg-amber-700 transition font-medium">
                            Lanjut ke Pembayaran
                        </button>
                    </div>
                </div>
            </div>

            {{-- Step 4: Payment --}}
            <div x-show="currentStep === 3" x-cloak>
                <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                    <h2 class="text-lg font-semibold text-stone-800 mb-4">Pembayaran</h2>

                    <div class="bg-stone-50 rounded-lg p-4 mb-6 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-stone-600">Subtotal</span>
                            <span class="font-medium">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm" x-show="shippingCost > 0">
                            <span class="text-stone-600">Ongkos Kirim</span>
                            <span class="font-medium" x-text="'Rp ' + formatNumber(shippingCost)"></span>
                        </div>
                        <div class="flex justify-between text-sm" x-show="discount > 0">
                            <span class="text-stone-600">Diskon</span>
                            <span class="font-medium text-green-600" x-text="'- Rp ' + formatNumber(discount)"></span>
                        </div>
                        <hr class="border-stone-200">
                        <div class="flex justify-between text-base font-bold">
                            <span>Total</span>
                            <span class="text-amber-600" x-text="'Rp ' + formatNumber(grandTotal)"></span>
                        </div>
                    </div>

                    <div x-show="submitError" class="bg-red-50 border border-red-200 text-red-700 p-3 rounded-lg mb-4 text-sm" x-text="submitError"></div>

                    <div class="flex justify-between">
                        <button @click="currentStep = 2" class="px-6 py-2.5 rounded-lg border border-stone-300 text-stone-700 hover:bg-stone-50 transition font-medium">
                            Kembali
                        </button>
                        <button @click="submitOrder()" :disabled="submitting"
                                class="bg-amber-600 text-white px-8 py-3 rounded-lg hover:bg-amber-700 transition font-bold disabled:opacity-50">
                            <span x-show="!submitting">Bayar Sekarang</span>
                            <span x-show="submitting">Memproses...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Order summary sidebar --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6 sticky top-24">
                <h3 class="text-lg font-semibold text-stone-800 mb-4">Ringkasan</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-stone-600">Subtotal ({{ $itemCount ?? $items->sum('quantity') }} item)</span>
                        <span class="font-medium">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between" x-show="shippingCost > 0">
                        <span class="text-stone-600">Ongkir</span>
                        <span class="font-medium" x-text="'Rp ' + formatNumber(shippingCost)"></span>
                    </div>
                    <div class="flex justify-between" x-show="discount > 0">
                        <span class="text-stone-600">Diskon</span>
                        <span class="font-medium text-green-600" x-text="'- Rp ' + formatNumber(discount)"></span>
                    </div>
                    <hr class="border-stone-200">
                    <div class="flex justify-between text-base font-bold">
                        <span>Total</span>
                        <span class="text-amber-600" x-text="'Rp ' + formatNumber(grandTotal)"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $midtransClientKey }}"></script>
@push('scripts')
<script>
function checkoutApp() {
    return {
        currentStep: 0,
        steps: ['Alamat', 'Pengiriman', 'Ringkasan', 'Bayar'],
        selectedAddress: null,
        selectedCourier: '',
        selectedShippingIndex: null,
        shippingOptions: [],
        shippingLoading: false,
        couponCode: '',
        couponValid: false,
        couponMessage: '',
        discount: 0,
        shippingCost: 0,
        grandTotal: {{ $subtotal }},
        catatan: '',
        submitting: false,
        submitError: '',
        totalBerat: {{ $totalBerat }},
        subtotal: {{ $subtotal }},

        init() {
            // Auto-select default address
            @foreach($alamat as $addr)
                @if($addr->is_default)
                this.selectedAddress = {{ $addr->id }};
                @break
                @endif
            @endforeach
        },

        formatNumber(val) {
            return Number(val).toLocaleString('id-ID');
        },

        onAddressChange() {
            this.shippingOptions = [];
            this.selectedShippingIndex = null;
            this.selectedCourier = '';
            this.shippingCost = 0;
            this.grandTotal = this.subtotal - this.discount;
        },

        loadShipping() {
            if (!this.selectedCourier) return;
            this.shippingLoading = true;
            this.shippingOptions = [];
            this.selectedShippingIndex = null;

            // Get city_id from the selected address
            const addressId = this.selectedAddress;

            fetch('{{ route("api.shipping-cost") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    address_id: addressId,
                    weight: this.totalBerat,
                    courier: this.selectedCourier
                })
            })
            .then(r => {
                if (!r.ok) {
                    return r.json().then(err => { throw new Error(err.message || 'Gagal memuat ongkir'); });
                }
                return r.json();
            })
            .then(data => {
                this.shippingOptions = Array.isArray(data) ? data : [];
                this.shippingLoading = false;
            })
            .catch((e) => {
                this.shippingOptions = [];
                this.shippingLoading = false;
                alert(e.message || 'Gagal memuat opsi pengiriman.');
            });
        },

        validateCoupon() {
            if (!this.couponCode) return;

            fetch('{{ route("api.validate-coupon") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    code: this.couponCode,
                    subtotal: this.subtotal
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.valid) {
                    this.couponValid = true;
                    this.discount = data.discount;
                    this.couponMessage = data.message;
                } else {
                    this.couponValid = false;
                    this.discount = 0;
                    this.couponMessage = data.message;
                }
                this.updateGrandTotal();
            })
            .catch(() => {
                this.couponValid = false;
                this.couponMessage = 'Gagal memvalidasi kupon.';
            });
        },

        updateGrandTotal() {
            const idx = this.selectedShippingIndex;
            const ongkir = idx !== null && this.shippingOptions[idx] ? this.shippingOptions[idx].cost : 0;
            this.shippingCost = ongkir;
            this.grandTotal = this.subtotal + ongkir - this.discount;
        },

        nextStep() {
            if (this.currentStep === 1) {
                this.updateGrandTotal();
            }
            this.currentStep++;
        },

        submitOrder() {
            this.submitting = true;
            this.submitError = '';

            const idx = this.selectedShippingIndex;
            const shipping = this.shippingOptions[idx];

            fetch('{{ route("checkout.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    alamat_id: this.selectedAddress,
                    kurir: this.selectedCourier,
                    service: shipping ? shipping.service : '',
                    ongkir: shipping ? shipping.cost : 0,
                    coupon_code: this.couponValid ? this.couponCode : '',
                    catatan: this.catatan
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.snap_token) {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                    const handleUrl = '{{ route("payment.handle") }}';

                    function handlePaymentResult(result) {
                        return fetch(handleUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                order_id: result.order_id,
                                transaction_status: result.transaction_status,
                                transaction_id: result.transaction_id || null,
                                payment_type: result.payment_type || null
                            })
                        });
                    }

                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            handlePaymentResult(result).finally(function() {
                                window.location.href = data.redirect_url;
                            });
                        },
                        onPending: function(result) {
                            handlePaymentResult(result).finally(function() {
                                window.location.href = '/customer/orders/' + data.no_pesanan;
                            });
                        },
                        onError: function(result) {
                            handlePaymentResult(result).finally(function() {
                                window.location.href = '/customer/orders/' + data.no_pesanan;
                            });
                        },
                        onClose: function() {
                            window.location.href = '/customer/orders/' + data.no_pesanan;
                        }
                    });
                } else if (data.message) {
                    this.submitError = data.message;
                }
                this.submitting = false;
            })
            .catch(err => {
                this.submitting = false;
                this.submitError = 'Terjadi kesalahan. Silakan coba lagi.';
            });
        }
    }
}
</script>
@endpush
@endsection
