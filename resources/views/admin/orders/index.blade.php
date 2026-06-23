@extends('layouts.admin')

@section('title', 'Pesanan')
@section('page_title', 'Manajemen Pesanan')

@section('content')
<div class="space-y-6">
    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm p-4">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="md:col-span-2">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari no. pesanan atau nama pelanggan..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Semua Status</option>
                    @foreach(['pending','dibayar','diproses','dikirim','selesai','dibatalkan'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">Filter</button>
                <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">Reset</a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left font-medium text-gray-500">No. Pesanan</th>
                        <th class="px-6 py-4 text-left font-medium text-gray-500">Pelanggan</th>
                        <th class="px-6 py-4 text-left font-medium text-gray-500">Tanggal</th>
                        <th class="px-6 py-4 text-left font-medium text-gray-500">Total</th>
                        <th class="px-6 py-4 text-left font-medium text-gray-500">Status</th>
                        <th class="px-6 py-4 text-left font-medium text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($pesanans as $pesanan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $pesanan->no_pesanan }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $pesanan->user->nama ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ \Carbon\Carbon::parse($pesanan->tanggal_pesanan)->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-gray-600">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $pesanan->status_badge_class }}">
                                {{ ucfirst($pesanan->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.orders.show', $pesanan) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada pesanan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pesanans->hasPages())
        <div class="px-6 py-4 border-t">
            {{ $pesanans->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
