@extends('layouts.admin')

@section('title', 'Tambah Banner')
@section('page_title', 'Tambah Banner Baru')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-6">
                <div>
                    <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">Judul Banner <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" id="judul" value="{{ old('judul') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('judul') border-red-500 @enderror"
                        placeholder="Masukkan judul banner">
                    @error('judul') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="gambar" class="block text-sm font-medium text-gray-700 mb-1">Gambar Banner <span class="text-red-500">*</span></label>
                    <input type="file" name="gambar" id="gambar" accept="image/*"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('gambar') border-red-500 @enderror">
                    @error('gambar') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    <p class="text-xs text-gray-500 mt-1">Rekomendasi ukuran: 1200x400px</p>
                </div>

                <div>
                    <label for="url" class="block text-sm font-medium text-gray-700 mb-1">URL Link</label>
                    <input type="url" name="url" id="url" value="{{ old('url') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('url') border-red-500 @enderror"
                        placeholder="https://...">
                    @error('url') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('sort_order') border-red-500 @enderror">
                    @error('sort_order') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                        class="w-4 h-4 text-indigo-600 rounded focus:ring-indigo-500">
                    <span class="text-sm text-gray-700">Aktif</span>
                </label>
            </div>

            <div class="mt-6 flex items-center gap-4">
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">Simpan Banner</button>
                <a href="{{ route('admin.banners.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
