@extends('layouts.customer')

@section('title', 'Edit Alamat')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        @include('customer.partials.sidebar')

        <div class="lg:col-span-3">
            <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                <h1 class="text-xl font-bold text-stone-800 mb-6">Edit Alamat</h1>

                <form action="{{ route('customer.addresses.update', $alamat->id) }}" method="POST" x-data="addressForm()" x-init="init()">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-stone-700 mb-1">Label Alamat</label>
                            <select name="label" class="w-full border border-stone-300 rounded-lg px-3 py-2 focus:ring-amber-500 focus:border-amber-500 @error('label') border-red-500 @enderror">
                                <option value="Rumah" {{ old('label', $alamat->label) === 'Rumah' ? 'selected' : '' }}>Rumah</option>
                                <option value="Kantor" {{ old('label', $alamat->label) === 'Kantor' ? 'selected' : '' }}>Kantor</option>
                                <option value="Apartemen" {{ old('label', $alamat->label) === 'Apartemen' ? 'selected' : '' }}>Apartemen</option>
                                <option value="Lainnya" {{ old('label', $alamat->label) === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('label')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-stone-700 mb-1">Nama Penerima</label>
                            <input type="text" name="nama_penerima" value="{{ old('nama_penerima', $alamat->nama_penerima) }}"
                                   class="w-full border border-stone-300 rounded-lg px-3 py-2 focus:ring-amber-500 focus:border-amber-500 @error('nama_penerima') border-red-500 @enderror">
                            @error('nama_penerima')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-stone-700 mb-1">No. Handphone</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp', $alamat->no_hp) }}"
                                   class="w-full border border-stone-300 rounded-lg px-3 py-2 focus:ring-amber-500 focus:border-amber-500 @error('no_hp') border-red-500 @enderror">
                            @error('no_hp')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-stone-700 mb-1">Provinsi</label>
                            <select name="provinsi" x-model="selectedProvince" @change="loadCities()"
                                    class="w-full border border-stone-300 rounded-lg px-3 py-2 focus:ring-amber-500 focus:border-amber-500 @error('provinsi') border-red-500 @enderror">
                                <option value="">Pilih Provinsi</option>
                                <template x-for="p in provinces" :key="p.id">
                                    <option :value="p.name" x-text="p.name"
                                        :selected="p.name === selectedProvince"></option>
                                </template>
                            </select>
                            @error('provinsi')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-stone-700 mb-1">Kota/Kabupaten</label>
                            <select name="kota" x-model="selectedCityName" @change="onCityChange()"
                                    class="w-full border border-stone-300 rounded-lg px-3 py-2 focus:ring-amber-500 focus:border-amber-500 @error('kota') border-red-500 @enderror">
                                <option value="">Pilih Kota/Kabupaten</option>
                                <template x-for="c in cities" :key="c.id">
                                    <option :value="c.name" x-text="c.name"
                                        :selected="c.name === selectedCityName"></option>
                                </template>
                            </select>
                            <input type="hidden" name="city_id" x-model="selectedCityId">
                            @error('kota')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            @error('city_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-stone-700 mb-1">Kecamatan</label>
                            <input type="text" name="kecamatan" value="{{ old('kecamatan', $alamat->kecamatan) }}"
                                   class="w-full border border-stone-300 rounded-lg px-3 py-2 focus:ring-amber-500 focus:border-amber-500 @error('kecamatan') border-red-500 @enderror">
                            @error('kecamatan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-stone-700 mb-1">Kode Pos</label>
                            <input type="text" name="kode_pos" value="{{ old('kode_pos', $alamat->kode_pos) }}"
                                   class="w-full border border-stone-300 rounded-lg px-3 py-2 focus:ring-amber-500 focus:border-amber-500 @error('kode_pos') border-red-500 @enderror">
                            @error('kode_pos')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-stone-700 mb-1">Alamat Lengkap</label>
                        <textarea name="alamat_lengkap" rows="3"
                                  class="w-full border border-stone-300 rounded-lg px-3 py-2 focus:ring-amber-500 focus:border-amber-500 @error('alamat_lengkap') border-red-500 @enderror">{{ old('alamat_lengkap', $alamat->alamat_lengkap) }}</textarea>
                        @error('alamat_lengkap')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="is_default" value="1" {{ old('is_default', $alamat->is_default) ? 'checked' : '' }}
                                   class="rounded border-stone-300 text-amber-600 focus:ring-amber-500">
                            <span class="text-sm text-stone-700">Jadikan alamat default</span>
                        </label>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <a href="{{ route('customer.addresses.index') }}" class="px-6 py-2.5 rounded-lg border border-stone-300 text-stone-700 hover:bg-stone-50 transition font-medium">
                            Batal
                        </a>
                        <button type="submit" class="bg-amber-600 text-white px-6 py-2.5 rounded-lg hover:bg-amber-700 transition font-medium">
                            Update Alamat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function addressForm() {
    return {
        provinces: [],
        cities: [],
        selectedProvince: '{{ old('provinsi', $alamat->provinsi) }}',
        selectedCityName: '{{ old('kota', $alamat->kota) }}',
        selectedCityId: '{{ old('city_id', $alamat->city_id) }}',

        async init() {
            try {
                const r = await fetch('{{ route("api.provinces") }}');
                this.provinces = await r.json();
                // If editing, load cities for the existing province
                if (this.selectedProvince) {
                    await this.loadCities();
                }
            } catch (e) {
                console.error('Gagal memuat provinsi:', e);
            }
        },

        async loadCities() {
            this.cities = [];
            if (!this.selectedProvince) return;

            const province = this.provinces.find(p => p.name === this.selectedProvince);
            if (!province) return;

            try {
                const r = await fetch('/api/cities/' + province.id);
                this.cities = await r.json();
            } catch (e) {
                console.error('Gagal memuat kota:', e);
            }
        },

        onCityChange() {
            const city = this.cities.find(c => c.name === this.selectedCityName);
            this.selectedCityId = city ? city.id : '';
        }
    }
}
</script>
@endpush
