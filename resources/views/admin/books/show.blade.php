@extends('admin.layout')

@section('page-title', 'Detail Buku')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

    <div class="mb-2">
        <a href="{{ route('admin.books.index') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-indigo-600 transition mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Buku
        </a>
        <h1 class="text-2xl font-bold text-gray-900">{{ $book->judul }}</h1>
        <p class="text-gray-500">{{ $book->penulis }}</p>
    </div>

    {{-- Book Detail Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="grid md:grid-cols-3">
            <!-- Cover -->
            <div class="bg-gray-50 p-8 flex items-center justify-center border-r border-gray-100">
                @if($book->cover)
                    <img src="{{ Storage::url($book->cover) }}" class="w-full rounded-xl shadow-lg transform hover:scale-105 transition duration-500">
                @else
                    <div class="w-48 h-64 bg-gray-200 rounded-xl flex items-center justify-center text-gray-400">
                        No Cover
                    </div>
                @endif
            </div>

            <!-- Info -->
            <div class="md:col-span-2 p-8">
                <div class="grid grid-cols-2 gap-6 mb-8">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Penerbit</p>
                        <p class="font-semibold text-gray-900">{{ $book->penerbit ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Tahun Terbit</p>
                        <p class="font-semibold text-gray-900">{{ $book->tahun_terbit ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Stok</p>
                        <p class="font-bold {{ $book->stok > 0 ? 'text-green-600' : 'text-red-500' }}">
                            {{ $book->stok }} Buku
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Kategori</p>
                        <div class="flex flex-wrap gap-1 mt-1">
                            @forelse($book->categories as $category)
                                <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded text-xs font-bold">{{ $category->nama }}</span>
                            @empty
                                <span class="text-gray-500">-</span>
                            @endforelse
                        </div>
                    </div>
                    <div class="col-span-2">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Rating</p>
                        <div class="flex items-center gap-2 mt-1">
                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <span class="font-bold text-gray-900">{{ number_format($averageRating, 1) }}</span>
                            <span class="text-gray-400 text-sm">({{ $totalReviews }} ulasan)</span>
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Deskripsi</p>
                    <p class="text-gray-600 leading-relaxed">
                        {{ $book->deskripsi ?? 'Tidak ada deskripsi.' }}
                    </p>
                </div>

                <div class="pt-6 border-t border-gray-100 flex gap-3">
                    <a href="{{ route('admin.books.edit', $book) }}" 
                       class="inline-block px-4 py-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition rounded-lg font-medium border border-indigo-200">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg> Edit Buku
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Reviews Section --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-yellow-100 rounded-lg flex items-center justify-center text-yellow-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg></div>
                <div>
                    <h2 class="font-bold text-gray-900">Ulasan Pembaca</h2>
                    <p class="text-xs text-gray-400">{{ $totalReviews }} ulasan</p>
                </div>
            </div>
        </div>

        <div class="divide-y divide-gray-100">
            @forelse($book->reviews as $review)
                <div class="px-6 py-5 flex items-start gap-4">
                    {{-- Avatar --}}
                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center font-bold text-indigo-700 flex-shrink-0">
                        {{ strtoupper(substr($review->user->name ?? '?', 0, 1)) }}
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3 mb-1">
                            <span class="font-semibold text-gray-900">{{ $review->user->name ?? 'User Dihapus' }}</span>
                            <div class="flex items-center gap-1 bg-yellow-50 px-2 py-0.5 rounded-full">
                                <svg class="w-3.5 h-3.5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <span class="text-xs font-bold text-yellow-700">{{ $review->rating }}/5</span>
                            </div>
                            <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            {{ $review->comment ?? '-' }}
                        </p>
                    </div>

                    {{-- Delete --}}
                    <form action="{{ route('admin.books.reviews.destroy', [$book->id, $review->id]) }}" method="POST" class="flex-shrink-0">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="confirmDeleteReview(this)" title="Hapus Ulasan"
                            class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            @empty
                <div class="px-6 py-12 text-center">
                    <svg class="w-8 h-8 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    <p class="text-gray-400">Belum ada ulasan untuk buku ini</p>
                </div>
            @endforelse
        </div>
    </div>

</div>

<script>
    function confirmDeleteReview(button) {
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
