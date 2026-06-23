@extends('layouts.customer')

@section('title', 'Alamat Saya')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        @include('customer.partials.sidebar')

        <div class="lg:col-span-3">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-stone-800">Alamat Saya</h1>
                <a href="{{ route('customer.addresses.create') }}" class="bg-amber-600 text-white px-4 py-2 rounded-lg hover:bg-amber-700 transition font-medium text-sm">
                    + Tambah Alamat
                </a>
            </div>

            @if($alamat->isEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-8 text-center">
                    <svg class="mx-auto h-16 w-16 text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    </svg>
                    <p class="mt-4 text-stone-500">Anda belum memiliki alamat tersimpan.</p>
                    <a href="{{ route('customer.addresses.create') }}" class="mt-4 inline-block text-amber-600 hover:text-amber-700 font-medium">Tambah Alamat Pertama</a>
                </div>
            @else
                <div class="grid grid-cols-1 gap-4">
                    @foreach($alamat as $addr)
                    <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-5">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-2">
                                    <span class="font-semibold text-stone-800">{{ $addr->label }}</span>
                                    @if($addr->is_default)
                                        <span class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full font-medium">Default</span>
                                    @endif
                                </div>
                                <p class="text-sm text-stone-700 font-medium">{{ $addr->nama_penerima }} - {{ $addr->no_hp }}</p>
                                <p class="text-sm text-stone-500 mt-1">{{ $addr->full_address }}</p>
                            </div>
                            <div class="flex items-center space-x-2 ml-4">
                                @if(!$addr->is_default)
                                <form action="{{ route('customer.addresses.update', $addr->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="label" value="{{ $addr->label }}">
                                    <input type="hidden" name="nama_penerima" value="{{ $addr->nama_penerima }}">
                                    <input type="hidden" name="no_hp" value="{{ $addr->no_hp }}">
                                    <input type="hidden" name="provinsi" value="{{ $addr->provinsi }}">
                                    <input type="hidden" name="kota" value="{{ $addr->kota }}">
                                    <input type="hidden" name="kecamatan" value="{{ $addr->kecamatan }}">
                                    <input type="hidden" name="kode_pos" value="{{ $addr->kode_pos }}">
                                    <input type="hidden" name="alamat_lengkap" value="{{ $addr->alamat_lengkap }}">
                                    <input type="hidden" name="is_default" value="1">
                                    <button type="submit" class="text-xs text-amber-600 hover:text-amber-700 font-medium" title="Set Default">Default</button>
                                </form>
                                @endif
                                <a href="{{ route('customer.addresses.edit', $addr->id) }}" class="text-xs text-blue-600 hover:text-blue-700 font-medium">Edit</a>
                                <form action="{{ route('customer.addresses.destroy', $addr->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus alamat ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-medium">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
