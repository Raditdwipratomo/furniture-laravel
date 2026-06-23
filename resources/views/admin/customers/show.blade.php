@extends('layouts.admin')

@section('title', 'Detail Pelanggan')
@section('page_title', 'Detail Pelanggan')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.customers.index') }}" class="text-gray-500 hover:text-gray-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h2 class="text-lg font-semibold text-gray-800">{{ $customer->nama }}</h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Customer Profile --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="text-center mb-6">
                <div class="w-20 h-20 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center font-bold text-2xl mx-auto mb-3">
                    {{ strtoupper(substr($customer->nama, 0, 1)) }}
                </div>
                <h3 class="text-lg font-semibold text-gray-900">{{ $customer->nama }}</h3>
                <p class="text-sm text-gray-500">{{ $customer->email }}</p>
            </div>
            <div class="space-y-3 text-sm border-t pt-4">
                <div class="flex justify-between">
                    <span class="text-gray-500">No. HP</span>
                    <span class="font-medium">{{ $customer->no_hp ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Terdaftar</span>
                    <span class="font-medium">{{ $customer->created_at->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Total Pesanan</span>
                    <span class="font-medium">{{ $customer->pesanan_count }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Total Belanja</span>
                    <span class="font-medium">Rp {{ number_format($customer->pesanan_sum_total_harga ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t">
                <form action="{{ route('admin.customers.toggle', $customer) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full px-4 py-2 {{ session('customer_disabled_' . $customer->id) ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }} text-white rounded-lg transition-colors font-medium text-sm">
                        {{ session('customer_disabled_' . $customer->id) ? 'Aktifkan Akun' : 'Nonaktifkan Akun' }}
                    </button>
                </form>
            </div>
        </div>

        {{-- Order History --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">Riwayat Pesanan</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left font-medium text-gray-500">No. Pesanan</th>
                                <th class="px-6 py-3 text-left font-medium text-gray-500">Tanggal</th>
                                <th class="px-6 py-3 text-left font-medium text-gray-500">Total</th>
                                <th class="px-6 py-3 text-left font-medium text-gray-500">Status</th>
                                <th class="px-6 py-3 text-left font-medium text-gray-500">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($orders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 font-medium text-gray-900">{{ $order->no_pesanan }}</td>
                                <td class="px-6 py-3 text-gray-600">{{ \Carbon\Carbon::parse($order->tanggal_pesanan)->format('d M Y') }}</td>
                                <td class="px-6 py-3 text-gray-600">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                                <td class="px-6 py-3">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $order->status_badge_class }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-3">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Detail</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada pesanan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($orders->hasPages())
                <div class="px-6 py-4 border-t">
                    {{ $orders->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
