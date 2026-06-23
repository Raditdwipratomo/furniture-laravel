@extends('layouts.admin')

@section('title', 'Detail Pembayaran')
@section('page_title', 'Detail Pembayaran')

@section('content')
<div class="space-y-6" x-data="{ showPayload: false }">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.payments.index') }}" class="text-gray-500 hover:text-gray-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Pembayaran</h3>
            <div class="space-y-4 text-sm">
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-500">No. Pesanan</span>
                    <a href="{{ route('admin.orders.show', $payment->pesanan) }}" class="font-medium text-indigo-600 hover:text-indigo-800">
                        {{ $payment->pesanan->no_pesanan ?? '-' }}
                    </a>
                </div>
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-500">Pelanggan</span>
                    <span class="font-medium">{{ $payment->pesanan->user->nama ?? '-' }}</span>
                </div>
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-500">Metode Pembayaran</span>
                    <span class="font-medium">{{ $payment->metode_pembayaran ?? '-' }}</span>
                </div>
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-500">Jumlah</span>
                    <span class="font-bold text-lg">Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-500">Status</span>
                    @php
                        $payBadge = match($payment->status_pembayaran) {
                            'berhasil' => 'bg-green-100 text-green-800',
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'gagal' => 'bg-red-100 text-red-800',
                            'dibatalkan' => 'bg-red-100 text-red-800',
                            'refund' => 'bg-orange-100 text-orange-800',
                            default => 'bg-gray-100 text-gray-800',
                        };
                    @endphp
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $payBadge }}">
                        {{ ucfirst($payment->status_pembayaran) }}
                    </span>
                </div>
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-500">Transaction ID</span>
                    <span class="font-medium text-xs">{{ $payment->transaction_id ?? '-' }}</span>
                </div>
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-500">Snap Token</span>
                    <span class="font-medium text-xs">{{ $payment->snap_token ?? '-' }}</span>
                </div>
                @if($payment->paid_at)
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-500">Dibayar pada</span>
                    <span class="font-medium">{{ \Carbon\Carbon::parse($payment->paid_at)->format('d M Y, H:i') }}</span>
                </div>
                @endif
                <div class="flex justify-between py-2">
                    <span class="text-gray-500">Dibuat</span>
                    <span class="font-medium">{{ $payment->created_at->format('d M Y, H:i') }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Payload Data</h3>
                <button @click="showPayload = !showPayload" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                    <span x-show="!showPayload">Tampilkan</span>
                    <span x-show="showPayload" style="display:none;">Sembunyikan</span>
                </button>
            </div>
            <div x-show="showPayload" style="display:none;">
                <pre class="bg-gray-900 text-green-400 p-4 rounded-lg overflow-x-auto text-xs leading-relaxed">{{ json_encode($payment->payload, JSON_PRETTY_PRINT) }}</pre>
            </div>
            <div x-show="!showPayload">
                <p class="text-gray-500 text-sm">Klik "Tampilkan" untuk melihat data payload JSON mentah.</p>
            </div>
        </div>
    </div>
</div>
@endsection
