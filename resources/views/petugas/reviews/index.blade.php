@extends('petugas.layout')

@section('page-title', 'Ulasan Buku')

@section('content')
<div class="space-y-6">

    {{-- Header & Search --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Ulasan Buku</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola semua ulasan dari pembaca</p>
        </div>
        <form method="GET" action="{{ route('petugas.reviews.index') }}" class="w-full sm:w-auto">
            <div class="relative">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari judul buku atau nama user..."
                    class="w-full sm:w-80 pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </form>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center text-yellow-600"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg></div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $reviews->total() }}</p>
                    <p class="text-xs text-gray-500">Total Ulasan</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center text-green-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg></div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Review::distinct('book_id')->count('book_id') }}</p>
                    <p class="text-xs text-gray-500">Buku Direview</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg></div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Review::distinct('user_id')->count('user_id') }}</p>
                    <p class="text-xs text-gray-500">User Aktif Review</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Buku</th>
                        <th class="px-6 py-4 font-semibold">User</th>
                        <th class="px-6 py-4 font-semibold text-center">Rating</th>
                        <th class="px-6 py-4 font-semibold">Komentar</th>
                        <th class="px-6 py-4 font-semibold">Tanggal</th>
                        <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($reviews as $review)
                        <tr class="hover:bg-gray-50 transition-colors">
                            {{-- Buku --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($review->book && $review->book->cover)
                                        <img src="{{ Storage::url($review->book->cover) }}" class="w-10 h-14 rounded-lg object-cover shadow-sm" alt="">
                                    @else
                                        <div class="w-10 h-14 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400 text-xs"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg></div>
                                    @endif
                                    <div class="max-w-[180px]">
                                        <p class="font-semibold text-gray-900 truncate">{{ $review->book->judul ?? 'Buku Dihapus' }}</p>
                                        <p class="text-xs text-gray-400 truncate">{{ $review->book->penulis ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- User --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-700 font-bold text-xs">
                                        {{ strtoupper(substr($review->user->name ?? '?', 0, 1)) }}
                                    </div>
                                    <span class="font-medium text-gray-700">{{ $review->user->name ?? 'User Dihapus' }}</span>
                                </div>
                            </td>

                            {{-- Rating --}}
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex items-center gap-1 bg-yellow-50 px-3 py-1 rounded-full">
                                    <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    <span class="font-bold text-yellow-700">{{ $review->rating }}/5</span>
                                </div>
                            </td>

                            {{-- Komentar --}}
                            <td class="px-6 py-4">
                                <p class="text-gray-600 max-w-[250px] truncate" title="{{ $review->comment }}">
                                    {{ $review->comment ?? '-' }}
                                </p>
                            </td>

                            {{-- Tanggal --}}
                            <td class="px-6 py-4">
                                <span class="text-gray-500 text-xs">{{ $review->created_at->format('d M Y') }}</span>
                                <br>
                                <span class="text-gray-400 text-xs">{{ $review->created_at->diffForHumans() }}</span>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('petugas.reviews.destroy', $review->id) }}" method="POST" class="inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete(this)" class="inline-flex items-center gap-1 bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                    <p class="text-gray-400 font-medium">Belum ada ulasan</p>
                                    @if($search)
                                        <p class="text-gray-400 text-sm">Tidak ditemukan ulasan untuk pencarian "{{ $search }}"</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($reviews->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $reviews->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

<script>
    function confirmDelete(button) {
        Swal.fire({
            title: 'Hapus Ulasan?',
            text: 'Ulasan ini akan dihapus secara permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('form').submit();
            }
        });
    }
</script>
@endsection
