@extends('layouts.admin')

@section('title', 'Edit Produk')
@section('page_title', 'Edit Produk')

@section('content')
<div class="max-w-4xl" x-data="editProductForm()">
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Info --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800">Informasi Produk</h3>

                    <div>
                        <label for="nama_produk" class="block text-sm font-medium text-gray-700 mb-1">Nama Produk <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_produk" id="nama_produk" value="{{ old('nama_produk', $product->nama_produk) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('nama_produk') border-red-500 @enderror">
                        @error('nama_produk') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="sku" class="block text-sm font-medium text-gray-700 mb-1">SKU <span class="text-red-500">*</span></label>
                            <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('sku') border-red-500 @enderror">
                            @error('sku') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="kategori_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                            <select name="kategori_id" id="kategori_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('kategori_id') border-red-500 @enderror">
                                <option value="">Pilih Kategori</option>
                                @foreach($kategoris as $kat)
                                    <option value="{{ $kat->id }}" {{ old('kategori_id', $product->kategori_id) == $kat->id ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                                @endforeach
                            </select>
                            @error('kategori_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" rows="4"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi', $product->deskripsi) }}</textarea>
                        @error('deskripsi') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Images --}}
                <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800">Gambar Produk</h3>

                    @if($product->gambar && count($product->gambar) > 0)
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-2">Gambar saat ini:</p>
                        <div class="grid grid-cols-4 gap-3">
                            @foreach($product->gambar as $img)
                            <div class="relative">
                                <img src="{{ asset('storage/' . $img) }}" class="w-full h-24 object-cover rounded-lg border">
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <input type="file" name="gambar[]" id="gambar" accept="image/*" multiple
                        @change="previewImages($event)"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <p class="text-xs text-gray-500">Upload gambar baru untuk mengganti gambar lama</p>
                    @error('gambar.*') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror

                    <div x-show="previews.length > 0" class="grid grid-cols-4 gap-3 mt-3">
                        <template x-for="(preview, index) in previews" :key="index">
                            <div class="relative group">
                                <img :src="preview" class="w-full h-24 object-cover rounded-lg border">
                                <button type="button" @click="removeImage(index)" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity">&times;</button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800">Harga & Stok</h3>

                    <div>
                        <label for="harga" class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="harga" id="harga" value="{{ old('harga', $product->harga) }}" min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('harga') border-red-500 @enderror">
                        @error('harga') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="stok" class="block text-sm font-medium text-gray-700 mb-1">Stok <span class="text-red-500">*</span></label>
                        <input type="number" name="stok" id="stok" value="{{ old('stok', $product->stok) }}" min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('stok') border-red-500 @enderror">
                        @error('stok') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="berat" class="block text-sm font-medium text-gray-700 mb-1">Berat (gram) <span class="text-red-500">*</span></label>
                        <input type="number" name="berat" id="berat" value="{{ old('berat', $product->berat) }}" min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('berat') border-red-500 @enderror">
                        @error('berat') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800">Status</h3>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                            class="w-4 h-4 text-indigo-600 rounded focus:ring-indigo-500">
                        <span class="text-sm text-gray-700">Produk Featured</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                            class="w-4 h-4 text-indigo-600 rounded focus:ring-indigo-500">
                        <span class="text-sm text-gray-700">Aktif</span>
                    </label>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="flex-1 px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">
                        Perbarui Produk
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function editProductForm() {
    return {
        previews: [],
        previewImages(event) {
            this.previews = [];
            const files = event.target.files;
            for (let i = 0; i < files.length; i++) {
                this.previews.push(URL.createObjectURL(files[i]));
            }
        },
        removeImage(index) {
            this.previews.splice(index, 1);
            const input = document.getElementById('gambar');
            const dt = new DataTransfer();
            for (let i = 0; i < input.files.length; i++) {
                if (i !== index) dt.items.add(input.files[i]);
            }
            input.files = dt.files;
        }
    }
}
</script>
@endpush
@endsection
