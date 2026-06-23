@extends('layouts.admin')

@section('title', 'Pengaturan')
@section('page_title', 'Pengaturan Toko')

@section('content')
<div class="max-w-full">
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="space-y-6">
            {{-- Store Info --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Toko</h3>
                <div class="space-y-4">
                    <div>
                        <label for="store_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Toko</label>
                        <input type="text" name="store_name" id="store_name" value="{{ old('store_name', $settings['store_name'] ?? '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="tagline" class="block text-sm font-medium text-gray-700 mb-1">Tagline</label>
                        <input type="text" name="tagline" id="tagline" value="{{ old('tagline', $settings['tagline'] ?? '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Contoh: Furnitur berkualitas untuk rumah Anda">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-1">Email Kontak</label>
                            <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email', $settings['contact_email'] ?? '') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                            <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Payment Config --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Konfigurasi Pembayaran (Midtrans)</h3>
                <div class="space-y-4">
                    <div>
                        <label for="midtrans_server_key" class="block text-sm font-medium text-gray-700 mb-1">Server Key</label>
                        <input type="text" name="midtrans_server_key" id="midtrans_server_key" value="{{ old('midtrans_server_key', $settings['midtrans_server_key'] ?? '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-mono text-sm">
                    </div>
                    <div>
                        <label for="midtrans_client_key" class="block text-sm font-medium text-gray-700 mb-1">Client Key</label>
                        <input type="text" name="midtrans_client_key" id="midtrans_client_key" value="{{ old('midtrans_client_key', $settings['midtrans_client_key'] ?? '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-mono text-sm">
                    </div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="midtrans_is_production" value="1"
                            {{ ($settings['midtrans_is_production'] ?? '0') === '1' ? 'checked' : '' }}
                            class="w-4 h-4 text-indigo-600 rounded focus:ring-indigo-500">
                        <span class="text-sm text-gray-700">Mode Production</span>
                    </label>
                    <p class="text-xs text-gray-500">Centang jika menggunakan Midtrans production environment</p>
                </div>
            </div>

            {{-- Shipping Config --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Konfigurasi Pengiriman (RajaOngkir)</h3>
                <div class="space-y-4">
                    <div>
                        <label for="rajaongkir_api_key" class="block text-sm font-medium text-gray-700 mb-1">API Key</label>
                        <input type="text" name="rajaongkir_api_key" id="rajaongkir_api_key" value="{{ old('rajaongkir_api_key', $settings['rajaongkir_api_key'] ?? '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-mono text-sm">
                    </div>
                    <div>
                        <label for="store_city_id" class="block text-sm font-medium text-gray-700 mb-1">ID Kota Asal Toko</label>
                        <input type="text" name="store_city_id" id="store_city_id" value="{{ old('store_city_id', $settings['store_city_id'] ?? '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <p class="text-xs text-gray-500 mt-1">ID kota dari RajaOngkir untuk perhitungan ongkir</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-8 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">
                    Simpan Pengaturan
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
