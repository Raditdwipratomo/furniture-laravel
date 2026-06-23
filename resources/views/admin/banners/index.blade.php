@extends('layouts.admin')

@section('title', 'Banner')
@section('page_title', 'Manajemen Banner')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <p class="text-gray-500 text-sm">Kelola banner untuk halaman utama</p>
        <a href="{{ route('admin.banners.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Banner
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left font-medium text-gray-500">Gambar</th>
                        <th class="px-6 py-4 text-left font-medium text-gray-500">Judul</th>
                        <th class="px-6 py-4 text-left font-medium text-gray-500">URL</th>
                        <th class="px-6 py-4 text-center font-medium text-gray-500">Urutan</th>
                        <th class="px-6 py-4 text-center font-medium text-gray-500">Status</th>
                        <th class="px-6 py-4 text-left font-medium text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($banners as $banner)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            @if($banner->gambar)
                                <img src="{{ asset('storage/' . $banner->gambar) }}" alt="{{ $banner->judul }}" class="w-32 h-16 object-cover rounded-lg">
                            @else
                                <div class="w-32 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $banner->judul }}</td>
                        <td class="px-6 py-4 text-gray-600 text-xs">{{ $banner->url ?? '-' }}</td>
                        <td class="px-6 py-4 text-center font-medium">{{ $banner->sort_order ?? 0 }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($banner->is_active)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Aktif</span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.banners.edit', $banner) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Edit</a>
                                <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus banner ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada banner</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($banners->hasPages())
        <div class="px-6 py-4 border-t">
            {{ $banners->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
