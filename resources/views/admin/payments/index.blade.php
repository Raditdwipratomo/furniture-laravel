@extends('layouts.admin')

@section('title', 'Pembayaran')
@section('page_title', 'Manajemen Pembayaran')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-sm p-4">
        <form method="GET" action="{{ route('admin.payments.index') }}" class="flex gap-4">
            <div class="flex-1">
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Semua Status</option>
                    @foreach(['pending','berhasil','gagal','dibatalkan','refund'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">Filter</button>
            <a href="{{ route('admin.payments.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">Reset</a>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left font-medium text-gray-500">No. Pesanan</th>
                        <th class="px-6 py-4 text-left font-medium text-gray-500">Pelanggan</th>
                        <th class="px-6 py-4 text-left font-medium text-gray-500">Metode</th>
                        <th class="px-6 py-4 text-right font-medium text-gray-500">Jumlah</th>
                        <th class="px-6 py-4 text-left font-medium text-gray-500">Status</th>
                        <th class="px-6 py-4 text-left font-medium text-gray-500">Tanggal</th>
                        <th class="px-6 py-4 text-left font-medium text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($pembayarans as $pembayaran)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $pembayaran->pesanan->no_pesanan ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $pembayaran->pesanan->user->nama ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $pembayaran->metode_pembayaran ?? '-' }}</td>
                        <td class="px-6 py-4 text-right text-gray-600">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            @php
                                $payBadge = match($pembayaran->status_pembayaran) {
                                    'berhasil' => 'bg-green-100 text-green-800',
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'gagal' => 'bg-red-100 text-red-800',
                                    'dibatalkan' => 'bg-red-100 text-red-800',
                                    'refund' => 'bg-orange-100 text-orange-800',
                                    default => 'bg-gray-100 text-gray-800',
                                };
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $payBadge }}">
                                {{ ucfirst($pembayaran->status_pembayaran) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $pembayaran->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.payments.show', $pembayaran) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">Belum ada pembayaran</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pembayarans->hasPages())
        <div class="px-6 py-4 border-t">
            {{ $pembayarans->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
