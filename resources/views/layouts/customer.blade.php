<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name')) - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        accent: {
                            DEFAULT: '#d97706',
                            50: '#fffbeb',
                            100: '#fef3c7',
                            200: '#fde68a',
                            300: '#fcd34d',
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                            700: '#b45309',
                            800: '#92400e',
                            900: '#78350f',
                        },
                        walnut: {
                            DEFAULT: '#5C4033',
                        },
                    },
                },
            },
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body class="bg-stone-50 text-stone-800 min-h-screen flex flex-col">
    {{-- Navbar --}}
    <nav class="bg-white shadow-sm border-b border-stone-200 sticky top-0 z-50" x-data="{ mobileOpen: false, userMenu: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                {{-- Logo --}}
                <a href="{{ url('/') }}" class="text-2xl font-bold text-amber-600 tracking-tight">
                    {{ config('app.name') }}
                </a>

                {{-- Desktop Nav --}}
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ url('/') }}" class="text-stone-600 hover:text-amber-600 transition">Beranda</a>
                    <a href="{{ url('/products') }}" class="text-stone-600 hover:text-amber-600 transition">Produk</a>

                    @auth
                        {{-- Cart --}}
                        <a href="{{ route('cart.index') }}" class="relative text-stone-600 hover:text-amber-600 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                            </svg>
                            @php
                                $cartCount = session('cart_count', 0);
                            @endphp
                            @if($cartCount > 0)
                                <span class="absolute -top-2 -right-2 bg-amber-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">{{ $cartCount }}</span>
                            @endif
                        </a>

                        {{-- Wishlist --}}
                        <a href="{{ route('customer.wishlist.index') }}" class="text-stone-600 hover:text-amber-600 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </a>

                        {{-- User Dropdown --}}
                        <div class="relative">
                            <button @click="userMenu = !userMenu" class="flex items-center space-x-2 text-stone-600 hover:text-amber-600 transition">
                                @if(auth()->user()->avatar)
                                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-8 h-8 rounded-full object-cover" alt="Avatar">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center font-semibold">
                                        {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                                    </div>
                                @endif
                                <span class="text-sm font-medium">{{ auth()->user()->nama }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="userMenu" @click.outside="userMenu = false" x-cloak
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-stone-200 py-1 z-50">
                                <a href="{{ route('customer.account.index') }}" class="block px-4 py-2 text-sm text-stone-700 hover:bg-stone-50">Dashboard</a>
                                <a href="{{ route('customer.orders.index') }}" class="block px-4 py-2 text-sm text-stone-700 hover:bg-stone-50">Pesanan</a>
                                <a href="{{ route('customer.profile.edit') }}" class="block px-4 py-2 text-sm text-stone-700 hover:bg-stone-50">Profil</a>
                                <a href="{{ route('customer.addresses.index') }}" class="block px-4 py-2 text-sm text-stone-700 hover:bg-stone-50">Alamat</a>
                                <hr class="my-1 border-stone-200">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-stone-50">Keluar</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-stone-600 hover:text-amber-600 transition text-sm font-medium">Masuk</a>
                        <a href="{{ route('register') }}" class="bg-amber-600 text-white px-4 py-2 rounded-lg hover:bg-amber-700 transition text-sm font-medium">Daftar</a>
                    @endauth
                </div>

                {{-- Mobile toggle --}}
                <button @click="mobileOpen = !mobileOpen" class="md:hidden text-stone-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileOpen" x-cloak class="md:hidden border-t border-stone-200 bg-white">
            <div class="px-4 py-3 space-y-2">
                <a href="{{ url('/') }}" class="block py-2 text-stone-600 hover:text-amber-600">Beranda</a>
                <a href="{{ url('/products') }}" class="block py-2 text-stone-600 hover:text-amber-600">Produk</a>
                @auth
                    <a href="{{ route('cart.index') }}" class="block py-2 text-stone-600 hover:text-amber-600">Keranjang</a>
                    <a href="{{ route('customer.wishlist.index') }}" class="block py-2 text-stone-600 hover:text-amber-600">Wishlist</a>
                    <a href="{{ route('customer.account.index') }}" class="block py-2 text-stone-600 hover:text-amber-600">Akun</a>
                    <a href="{{ route('customer.orders.index') }}" class="block py-2 text-stone-600 hover:text-amber-600">Pesanan</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block py-2 text-red-600 hover:text-red-700">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block py-2 text-stone-600 hover:text-amber-600">Masuk</a>
                    <a href="{{ route('register') }}" class="block py-2 text-amber-600 font-medium">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4" x-data="{ show: true }" x-show="show">
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex justify-between items-center">
                <span>{{ session('success') }}</span>
                <button @click="show = false" class="text-green-600 hover:text-green-800">&times;</button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4" x-data="{ show: true }" x-show="show">
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex justify-between items-center">
                <span>{{ session('error') }}</span>
                <button @click="show = false" class="text-red-600 hover:text-red-800">&times;</button>
            </div>
        </div>
    @endif

    {{-- Main Content --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-stone-800 text-stone-300 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-white font-bold text-lg mb-3">{{ config('app.name') }}</h3>
                    <p class="text-sm text-stone-400">Toko furnitur berkualitas untuk rumah impian Anda.</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-3">Navigasi</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ url('/') }}" class="hover:text-amber-400 transition">Beranda</a></li>
                        <li><a href="{{ url('/products') }}" class="hover:text-amber-400 transition">Produk</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-3">Hubungi Kami</h4>
                    <p class="text-sm text-stone-400">Email: info@furnico.id</p>
                    <p class="text-sm text-stone-400">Telp: +62 812-3456-7890</p>
                </div>
            </div>
            <div class="border-t border-stone-700 mt-8 pt-6 text-center text-sm text-stone-500">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </div>
        </div>
    </footer>

    <script>
        // CSRF token for AJAX
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            window.fetchWithCsrf = function(url, options = {}) {
                options.headers = options.headers || {};
                options.headers['X-CSRF-TOKEN'] = csrfToken;
                options.headers['Accept'] = 'application/json';
                return fetch(url, options);
            };
        });
    </script>
    @stack('scripts')
</body>
</html>
