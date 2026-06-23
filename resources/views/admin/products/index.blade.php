@extends('layouts.admin')

@section('title', 'Produk')
@section('page_title', 'Manajemen Produk')

@section('content')
<div class="space-y-6">
    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm p-4 w-full">
        <form method="GET" action="{{ route('admin.products.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 w-full justify-center items-center">
            
            <div>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari produk atau SKU..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <select name="kategori_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kat)
                        <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="stok" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Semua Stok</option>
                    <option value="available" {{ request('stok') === 'available' ? 'selected' : '' }}>Tersedia (≥5)</option>
                    <option value="low" {{ request('stok') === 'low' ? 'selected' : '' }}>Stok Rendah (<5)</option>
                    <option value="out" {{ request('stok') === 'out' ? 'selected' : '' }}>Habis (0)</option>
                </select>
            </div>
            <div class="gap-5 flex col-span-2">
                <div class="gap-3 flex flex-row"> 
                    <button type="submit" class="px-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">Filter</button>
                    <a href="{{ route('admin.products.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">Reset</a>
                </div>
                   <div>
        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Produk
        </a>
    </div>
            </div>
            
        </form>
    
    </div>

  

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Gambar</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Nama Produk</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Kategori</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Harga</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Stok</th>
                        <th class="px-4 py-3 text-center font-medium text-gray-500">Featured</th>
                        <th class="px-4 py-3 text-center font-medium text-gray-500">Aktif</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($produks as $produk)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            @if($produk->gambar && count($produk->gambar) > 0)
                                <img src="{{ asset('storage/' . $produk->gambar[0]) }}" alt="{{ $produk->nama_produk }}" class="w-12 h-12 object-cover rounded-lg">
                            @else
                                <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-900">{{ $produk->nama_produk }}</p>
                            <p class="text-xs text-gray-500">{{ $produk->sku }}</p>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $produk->kategori->nama_kategori ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-600">Rp {{ number_format($produk->harga, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">
                            @if($produk->stok == 0)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Habis</span>
                            @elseif($produk->stok < 5)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">{{ $produk->stok }}</span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">{{ $produk->stok }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($produk->is_featured)
                                <span class="text-yellow-500">&#9733;</span>
                            @else
                                <span class="text-gray-300">&#9733;</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($produk->is_active)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Aktif</span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.products.edit', $produk) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Edit</a>
                                <form action="{{ route('admin.products.destroy', $produk) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">Belum ada produk</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($produks->hasPages())
        <div class="px-6 py-4 border-t">
            {{ $produks->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
