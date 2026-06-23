@extends('layouts.admin')

@section('title', 'Kupon')
@section('page_title', 'Manajemen Kupon')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <p class="text-gray-500 text-sm">Kelola kupon diskon untuk pelanggan</p>
        <a href="{{ route('admin.coupons.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Kupon
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left font-medium text-gray-500">Kode</th>
                        <th class="px-6 py-4 text-left font-medium text-gray-500">Tipe</th>
                        <th class="px-6 py-4 text-left font-medium text-gray-500">Nilai</th>
                        <th class="px-6 py-4 text-left font-medium text-gray-500">Min. Order</th>
                        <th class="px-6 py-4 text-center font-medium text-gray-500">Penggunaan</th>
                        <th class="px-6 py-4 text-left font-medium text-gray-500">Berlaku</th>
                        <th class="px-6 py-4 text-center font-medium text-gray-500">Status</th>
                        <th class="px-6 py-4 text-left font-medium text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($kupons as $kupon)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-mono font-bold text-indigo-600">{{ $kupon->kode }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $kupon->tipe === 'percent' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ $kupon->tipe === 'percent' ? 'Persen' : 'Tetap' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-medium">
                            {{ $kupon->tipe === 'percent' ? $kupon->nilai . '%' : 'Rp ' . number_format($kupon->nilai, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $kupon->min_order ? 'Rp ' . number_format($kupon->min_order, 0, ',', '.') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-sm">{{ $kupon->used_count }} / {{ $kupon->max_uses ?: '∞' }}</span>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-600">
                            {{ $kupon->valid_from->format('d M Y') }} - {{ $kupon->valid_until->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($kupon->is_active)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Aktif</span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.coupons.edit', $kupon) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Edit</a>
                                <form action="{{ route('admin.coupons.destroy', $kupon) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kupon ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">Belum ada kupon</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($kupons->hasPages())
        <div class="px-6 py-4 border-t">
            {{ $kupons->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
