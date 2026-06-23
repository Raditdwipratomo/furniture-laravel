@php
    $currentRoute = request()->route()->getName();
@endphp

<div class="lg:col-span-1">
    <div class="bg-white rounded-xl shadow-sm border border-stone-200 overflow-hidden sticky top-24">
        <div class="p-4 bg-stone-50 border-b border-stone-200 text-center">
            @if(auth()->user()->avatar)
                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="" class="w-16 h-16 rounded-full object-cover mx-auto mb-2">
            @else
                <div class="w-16 h-16 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-2xl font-bold mx-auto mb-2">
                    {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                </div>
            @endif
            <p class="font-semibold text-stone-800">{{ auth()->user()->nama }}</p>
            <p class="text-xs text-stone-500">{{ auth()->user()->email }}</p>
        </div>
        <nav class="p-2">
            <a href="{{ route('customer.account.index') }}"
               class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm transition {{ str_starts_with($currentRoute, 'customer.account') ? 'bg-amber-50 text-amber-700 font-medium' : 'text-stone-600 hover:bg-stone-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('customer.profile.edit') }}"
               class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm transition {{ str_starts_with($currentRoute, 'customer.profile') ? 'bg-amber-50 text-amber-700 font-medium' : 'text-stone-600 hover:bg-stone-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span>Profil</span>
            </a>
            <a href="{{ route('customer.addresses.index') }}"
               class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm transition {{ str_starts_with($currentRoute, 'customer.addresses') ? 'bg-amber-50 text-amber-700 font-medium' : 'text-stone-600 hover:bg-stone-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Alamat</span>
            </a>
            <a href="{{ route('customer.orders.index') }}"
               class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm transition {{ str_starts_with($currentRoute, 'customer.orders') ? 'bg-amber-50 text-amber-700 font-medium' : 'text-stone-600 hover:bg-stone-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <span>Pesanan</span>
            </a>
            <a href="{{ route('customer.wishlist.index') }}"
               class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm transition {{ str_starts_with($currentRoute, 'customer.wishlist') ? 'bg-amber-50 text-amber-700 font-medium' : 'text-stone-600 hover:bg-stone-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                <span>Wishlist</span>
            </a>
            <a href="{{ route('customer.reviews.index') }}"
               class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm transition {{ str_starts_with($currentRoute, 'customer.reviews') ? 'bg-amber-50 text-amber-700 font-medium' : 'text-stone-600 hover:bg-stone-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                <span>Review</span>
            </a>
        </nav>
    </div>
</div>
