@extends('layouts.customer')

@section('title', 'Edit Profil')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        @include('customer.partials.sidebar')

        <div class="lg:col-span-3 space-y-6">
            {{-- Profile Info --}}
            <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                <h2 class="text-lg font-semibold text-stone-800 mb-4">Informasi Profil</h2>

                <form action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-stone-700 mb-1">Nama</label>
                            <input type="text" name="nama" value="{{ old('nama', auth()->user()->nama) }}"
                                   class="w-full border border-stone-300 rounded-lg px-3 py-2 focus:ring-amber-500 focus:border-amber-500 @error('nama') border-red-500 @enderror">
                            @error('nama')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-stone-700 mb-1">Email</label>
                            <input type="email" value="{{ auth()->user()->email }}" disabled
                                   class="w-full border border-stone-200 rounded-lg px-3 py-2 bg-stone-50 text-stone-500">
                            <p class="text-xs text-stone-500 mt-1">Email tidak dapat diubah.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-stone-700 mb-1">No. Handphone</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp', auth()->user()->no_hp) }}"
                                   class="w-full border border-stone-300 rounded-lg px-3 py-2 focus:ring-amber-500 focus:border-amber-500 @error('no_hp') border-red-500 @enderror">
                            @error('no_hp')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-stone-700 mb-1">Avatar</label>
                            <input type="file" name="avatar" accept="image/*"
                                   class="w-full border border-stone-300 rounded-lg px-3 py-2 focus:ring-amber-500 focus:border-amber-500">
                            @if(auth()->user()->avatar)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="" class="w-16 h-16 rounded-full object-cover">
                                </div>
                            @endif
                            @error('avatar')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="bg-amber-600 text-white px-6 py-2.5 rounded-lg hover:bg-amber-700 transition font-medium">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            {{-- Change Password --}}
            <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
                <h2 class="text-lg font-semibold text-stone-800 mb-4">Ubah Password</h2>

                <form action="{{ route('customer.profile.update-password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-stone-700 mb-1">Password Saat Ini</label>
                            <input type="password" name="current_password"
                                   class="w-full border border-stone-300 rounded-lg px-3 py-2 focus:ring-amber-500 focus:border-amber-500 @error('current_password') border-red-500 @enderror">
                            @error('current_password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-stone-700 mb-1">Password Baru</label>
                            <input type="password" name="password"
                                   class="w-full border border-stone-300 rounded-lg px-3 py-2 focus:ring-amber-500 focus:border-amber-500 @error('password') border-red-500 @enderror">
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-stone-700 mb-1">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation"
                                   class="w-full border border-stone-300 rounded-lg px-3 py-2 focus:ring-amber-500 focus:border-amber-500">
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="bg-amber-600 text-white px-6 py-2.5 rounded-lg hover:bg-amber-700 transition font-medium">
                            Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
