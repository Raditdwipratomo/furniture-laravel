@extends('layouts.admin')

@section('title', 'Edit Kupon')
@section('page_title', 'Edit Kupon')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="kode" class="block text-sm font-medium text-gray-700 mb-1">Kode Kupon <span class="text-red-500">*</span></label>
                        <input type="text" name="kode" id="kode" value="{{ old('kode', $coupon->kode) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 uppercase @error('kode') border-red-500 @enderror">
                        @error('kode') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="tipe" class="block text-sm font-medium text-gray-700 mb-1">Tipe <span class="text-red-500">*</span></label>
                        <select name="tipe" id="tipe" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('tipe') border-red-500 @enderror">
                            <option value="fixed" {{ old('tipe', $coupon->tipe) === 'fixed' ? 'selected' : '' }}>Tetap (Rp)</option>
                            <option value="percent" {{ old('tipe', $coupon->tipe) === 'percent' ? 'selected' : '' }}>Persen (%)</option>
                        </select>
                        @error('tipe') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="nilai" class="block text-sm font-medium text-gray-700 mb-1">Nilai <span class="text-red-500">*</span></label>
                        <input type="number" name="nilai" id="nilai" value="{{ old('nilai', $coupon->nilai) }}" min="0" step="0.01"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('nilai') border-red-500 @enderror">
                        @error('nilai') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="min_order" class="block text-sm font-medium text-gray-700 mb-1">Min. Order (Rp)</label>
                        <input type="number" name="min_order" id="min_order" value="{{ old('min_order', $coupon->min_order) }}" min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('min_order') border-red-500 @enderror">
                        @error('min_order') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="max_uses" class="block text-sm font-medium text-gray-700 mb-1">Maks. Penggunaan <span class="text-xs text-gray-500">(0 = unlimited)</span></label>
                    <input type="number" name="max_uses" id="max_uses" value="{{ old('max_uses', $coupon->max_uses) }}" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('max_uses') border-red-500 @enderror">
                    @error('max_uses') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="valid_from" class="block text-sm font-medium text-gray-700 mb-1">Berlaku Dari <span class="text-red-500">*</span></label>
                        <input type="date" name="valid_from" id="valid_from" value="{{ old('valid_from', $coupon->valid_from?->format('Y-m-d')) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('valid_from') border-red-500 @enderror">
                        @error('valid_from') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="valid_until" class="block text-sm font-medium text-gray-700 mb-1">Berlaku Sampai <span class="text-red-500">*</span></label>
                        <input type="date" name="valid_until" id="valid_until" value="{{ old('valid_until', $coupon->valid_until?->format('Y-m-d')) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('valid_until') border-red-500 @enderror">
                        @error('valid_until') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}
                        class="w-4 h-4 text-indigo-600 rounded focus:ring-indigo-500">
                    <span class="text-sm text-gray-700">Aktif</span>
                </label>
            </div>

            <div class="mt-6 flex items-center gap-4">
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">Perbarui Kupon</button>
                <a href="{{ route('admin.coupons.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
