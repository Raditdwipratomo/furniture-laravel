@extends('layouts.admin')

@section('title', 'Detail Pesanan')
@section('page_title', 'Detail Pesanan ' . $order->no_pesanan)

@section('content')
<div class="space-y-6" x-data="{ showStatusModal: false, showCancelModal: false }">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.orders.index') }}" class="text-gray-500 hover:text-gray-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div class="flex items-center gap-3">
            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $order->status_badge_class }}">{{ ucfirst($order->status) }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Order Items --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Item Pesanan</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500 border-b">
                                <th class="pb-3 font-medium">Produk</th>
                                <th class="pb-3 font-medium text-center">Qty</th>
                                <th class="pb-3 font-medium text-right">Harga</th>
                                <th class="pb-3 font-medium text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($order->detail as $detail)
                            <tr>
                                <td class="py-3">
                                    <p class="font-medium text-gray-900">{{ $detail->produk->nama_produk ?? 'Produk dihapus' }}</p>
                                    <p class="text-xs text-gray-500">{{ $detail->produk->sku ?? '-' }}</p>
                                </td>
                                <td class="py-3 text-center text-gray-600">{{ $detail->quantity }}</td>
                                <td class="py-3 text-right text-gray-600">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                <td class="py-3 text-right font-medium text-gray-900">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="border-t-2">
                            <tr>
                                <td colspan="3" class="pt-3 text-right text-gray-600">Subtotal</td>
                                <td class="pt-3 text-right font-medium">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="pt-2 text-right text-gray-600">Ongkir</td>
                                <td class="pt-2 text-right">Rp {{ number_format($order->ongkir, 0, ',', '.') }}</td>
                            </tr>
                            @if($order->diskon > 0)
                            <tr>
                                <td colspan="3" class="pt-2 text-right text-red-600">Diskon</td>
                                <td class="pt-2 text-right text-red-600">- Rp {{ number_format($order->diskon, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            <tr class="border-t">
                                <td colspan="3" class="pt-3 text-right font-semibold text-gray-800">Total</td>
                                <td class="pt-3 text-right font-bold text-lg text-gray-900">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Payment Info --}}
            @if($order->pembayaran)
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Pembayaran</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Metode</p>
                        <p class="font-medium">{{ $order->pembayaran->metode_pembayaran ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Jumlah</p>
                        <p class="font-medium">Rp {{ number_format($order->pembayaran->jumlah, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Status</p>
                        <p class="font-medium">
                            @php
                                $payBadge = match($order->pembayaran->status_pembayaran) {
                                    'berhasil' => 'bg-green-100 text-green-800',
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'gagal' => 'bg-red-100 text-red-800',
                                    'dibatalkan' => 'bg-red-100 text-red-800',
                                    'refund' => 'bg-orange-100 text-orange-800',
                                    default => 'bg-gray-100 text-gray-800',
                                };
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $payBadge }}">{{ ucfirst($order->pembayaran->status_pembayaran) }}</span>
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-500">Transaction ID</p>
                        <p class="font-medium text-xs">{{ $order->pembayaran->transaction_id ?? '-' }}</p>
                    </div>
                    @if($order->pembayaran->paid_at)
                    <div>
                        <p class="text-gray-500">Dibayar pada</p>
                        <p class="font-medium">{{ \Carbon\Carbon::parse($order->pembayaran->paid_at)->format('d M Y, H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Shipping Info --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Pengiriman</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Alamat</p>
                        <p class="font-medium">{{ $order->alamat_pengiriman ?? '-' }}</p>
                    </div>
                    @if($order->pengiriman)
                    <div>
                        <p class="text-gray-500">Kurir</p>
                        <p class="font-medium">{{ $order->pengiriman->kurir ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">No. Resi</p>
                        <p class="font-medium">{{ $order->pengiriman->no_resi ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Status Pengiriman</p>
                        <p class="font-medium">{{ $order->pengiriman->status_pengiriman ?? '-' }}</p>
                    </div>
                    @endif
                </div>
                @if($order->catatan)
                <div class="mt-4 pt-4 border-t">
                    <p class="text-gray-500 text-sm">Catatan</p>
                    <p class="text-sm mt-1">{{ $order->catatan }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Customer Info --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Pelanggan</h3>
                <div class="space-y-2 text-sm">
                    <p class="font-medium text-gray-900">{{ $order->user->nama ?? '-' }}</p>
                    <p class="text-gray-500">{{ $order->user->email ?? '-' }}</p>
                    <p class="text-gray-500">{{ $order->user->no_hp ?? '-' }}</p>
                </div>
            </div>

            {{-- Order Info --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Info Pesanan</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">No. Pesanan</span>
                        <span class="font-medium">{{ $order->no_pesanan }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Tanggal</span>
                        <span class="font-medium">{{ \Carbon\Carbon::parse($order->tanggal_pesanan)->format('d M Y, H:i') }}</span>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="bg-white rounded-xl shadow-sm p-6 space-y-3">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Aksi</h3>
                <button @click="showStatusModal = true" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm">
                    Ubah Status
                </button>
                @if(!in_array($order->status, ['selesai', 'dibatalkan']))
                <button @click="showCancelModal = true" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium text-sm">
                    Batalkan Pesanan
                </button>
                @endif
            </div>
        </div>
    </div>

    {{-- Status Update Modal --}}
    <div x-show="showStatusModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 p-6" @click.away="showStatusModal = false">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Ubah Status Pesanan</h3>
            <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status Baru</label>
                        <select name="status" x-ref="statusSelect" @change="$refs.resiField.style.display = $event.target.value === 'dikirim' ? 'block' : 'none'"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach(['pending','dibayar','diproses','dikirim','selesai','dibatalkan'] as $s)
                                <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div x-ref="resiField" style="display: none;">
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. Resi</label>
                        <input type="text" name="no_resi" value="{{ $order->pengiriman->no_resi ?? '' }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <label class="block text-sm font-medium text-gray-700 mb-1 mt-3">Kurir</label>
                        <input type="text" name="kurir" value="{{ $order->pengiriman->kurir ?? '' }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
                <div class="mt-6 flex gap-3">
                    <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">Simpan</button>
                    <button type="button" @click="showStatusModal = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium">Batal</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Cancel Modal --}}
    <div x-show="showCancelModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 p-6" @click.away="showCancelModal = false">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Batalkan Pesanan</h3>
            <form action="{{ route('admin.orders.destroy', $order) }}" method="POST">
                @csrf
                @method('DELETE')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alasan Pembatalan</label>
                    <textarea name="reason" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Masukkan alasan pembatalan..."></textarea>
                </div>
                <div class="mt-6 flex gap-3">
                    <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium">Batalkan Pesanan</button>
                    <button type="button" @click="showCancelModal = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium">Kembali</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
