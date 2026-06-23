@extends('layouts.customer')

@section('title', 'Login')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-[#78350f]">Selamat Datang Kembali</h2>
            <p class="mt-2 text-gray-600">Login ke akun Furnico Anda</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-8">
            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    @foreach($errors->all() as $error)
                        <p class="text-red-600 text-sm">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#d97706] focus:border-transparent transition-colors"
                           placeholder="nama@email.com">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" id="password" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#d97706] focus:border-transparent transition-colors"
                           placeholder="Masukkan password">
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-[#d97706] border-gray-300 rounded focus:ring-[#d97706]">
                        <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-[#d97706] text-white py-3 px-4 rounded-lg hover:bg-amber-700 transition-colors font-semibold">
                    Login
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-600">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-[#d97706] hover:text-amber-700 font-medium">Daftar sekarang</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
