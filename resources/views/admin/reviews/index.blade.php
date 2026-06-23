@extends('layouts.admin')

@section('title', 'Review')
@section('page_title', 'Moderasi Review')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-sm p-4">
        <form method="GET" action="{{ route('admin.reviews.index') }}" class="flex gap-4">
            <div class="flex-1">
                <select name="approved" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Semua Review</option>
                    <option value="yes" {{ request('approved') === 'yes' ? 'selected' : '' }}>Sudah Disetujui</option>
                    <option value="no" {{ request('approved') === 'no' ? 'selected' : '' }}>Belum Disetujui</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">Filter</button>
            <a href="{{ route('admin.reviews.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">Reset</a>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left font-medium text-gray-500">Produk</th>
                        <th class="px-6 py-4 text-left font-medium text-gray-500">Reviewer</th>
                        <th class="px-6 py-4 text-center font-medium text-gray-500">Rating</th>
                        <th class="px-6 py-4 text-left font-medium text-gray-500">Komentar</th>
                        <th class="px-6 py-4 text-left font-medium text-gray-500">Tanggal</th>
                        <th class="px-6 py-4 text-center font-medium text-gray-500">Status</th>
                        <th class="px-6 py-4 text-left font-medium text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($reviews as $review)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-900">{{ $review->produk->nama_produk ?? 'Produk dihapus' }}</p>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $review->user->nama ?? '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="{{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}">&#9733;</span>
                                @endfor
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600 max-w-xs">
                            <p class="truncate">{{ $review->komentar }}</p>
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-xs">{{ $review->tanggal_review ? \Carbon\Carbon::parse($review->tanggal_review)->format('d M Y') : $review->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($review->is_approved)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Disetujui</span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">Pending</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                @if(!$review->is_approved)
                                <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-800 font-medium">Setujui</button>
                                </form>
                                @endif
                                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus review ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">Belum ada review</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reviews->hasPages())
        <div class="px-6 py-4 border-t">
            {{ $reviews->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
