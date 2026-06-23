@extends('layouts.admin')

@section('title', 'Pelanggan')
@section('page_title', 'Manajemen Pelanggan')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-sm p-4">
        <form method="GET" action="{{ route('admin.customers.index') }}" class="flex gap-4">
            <div class="flex-1">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau email pelanggan..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">Cari</button>
            <a href="{{ route('admin.customers.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">Reset</a>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left font-medium text-gray-500">#</th>
                        <th class="px-6 py-4 text-left font-medium text-gray-500">Nama</th>
                        <th class="px-6 py-4 text-left font-medium text-gray-500">Email</th>
                        <th class="px-6 py-4 text-left font-medium text-gray-500">No. HP</th>
                        <th class="px-6 py-4 text-center font-medium text-gray-500">Pesanan</th>
                        <th class="px-6 py-4 text-right font-medium text-gray-500">Total Belanja</th>
                        <th class="px-6 py-4 text-left font-medium text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($customers as $customer)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-gray-600">{{ $loop->iteration + ($customers->currentPage() - 1) * $customers->perPage() }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center font-bold text-sm">
                                    {{ strtoupper(substr($customer->nama, 0, 1)) }}
                                </div>
                                <span class="font-medium text-gray-900">{{ $customer->nama }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $customer->email }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $customer->no_hp ?? '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-700">
                                {{ $customer->pesanan_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-gray-600">Rp {{ number_format($customer->pesanan_sum_total_harga ?? 0, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.customers.show', $customer) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">Belum ada pelanggan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($customers->hasPages())
        <div class="px-6 py-4 border-t">
            {{ $customers->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
