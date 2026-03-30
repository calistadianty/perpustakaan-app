@extends('user.layout')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">
    
    <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-6">
        <div>
            <h2 class="text-3xl font-bold text-slate-900 flex items-center gap-2"><svg class="w-8 h-8 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg> Katalog Buku</h2>
            <p class="text-slate-600 mt-2">Temukan buku favorit Anda dari koleksi kami</p>
        </div>
        
        <form action="{{ route('catalog.index') }}" method="GET" class="relative w-full md:w-96">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari judul atau penulis..." 
                   class="w-full pl-12 pr-4 py-3 rounded-xl border border-slate-200 focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 shadow-sm transition-all focus:outline-none">
            <button type="submit" class="absolute left-4 top-3.5 text-slate-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </button>
        </form>
    </div>

    <!-- Categories -->
    <div class="flex flex-wrap gap-2 mb-10">
        <a href="{{ route('catalog.index') }}" class="px-4 py-2 rounded-full text-sm font-semibold {{ !$category ? 'bg-blue-900 text-white shadow-md' : 'bg-white text-slate-600 hover:bg-blue-50 border border-slate-200' }} transition-all">
            Semua
        </a>
        @foreach(\App\Models\Category::all() as $cat)
            <a href="{{ route('catalog.index', ['category' => $cat->nama]) }}" 
               class="px-4 py-2 rounded-full text-sm font-semibold {{ $category == $cat->nama ? 'bg-blue-900 text-white shadow-md' : 'bg-white text-slate-600 hover:bg-blue-50 border border-slate-200' }} transition-all">
                {{ $cat->nama }}
            </a>
        @endforeach
    </div>

    <!-- Grid -->
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
        @forelse($books as $book)
        <div class="bg-white p-5 rounded-2xl shadow-sm hover:shadow-md transition-all group flex flex-col h-full">
            <div class="relative mb-4">
                @if($book->cover)
                    <img src="{{ Storage::url($book->cover) }}" class="rounded-xl h-64 w-full object-cover shadow-sm group-hover:shadow-md transition-all">
                @else
                    <div class="h-64 w-full bg-slate-50 rounded-xl flex items-center justify-center text-slate-300">No Cover</div>
                @endif
                <div class="absolute top-3 right-3 bg-white/90 backdrop-blur px-2 py-1 rounded-lg text-xs font-bold text-slate-900 shadow-sm flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg> {{ number_format($book->reviews()->avg('rating') ?? 0, 1) }}
                </div>
            </div>
         
            <h4 class="font-bold text-slate-900 mb-1 line-clamp-1 text-lg group-hover:text-blue-600 transition">{{ $book->judul }}</h4>
            <p class="text-sm text-slate-500 mb-4">{{ $book->penulis }}</p>

            <div class="mt-auto space-y-3">
                <div class="flex justify-between items-center text-xs font-medium">
                    <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded-md">{{ $book->categories->isNotEmpty() ? $book->categories->pluck('nama')->join(', ') : 'Umum' }}</span>
                    <span class="{{ $book->stok > 0 ? 'text-emerald-600' : 'text-red-500' }}">
                        {{ $book->stok }} Stok
                    </span>
                </div>
                
                <a href="{{ route('catalog.show', $book) }}" class="block w-full text-center bg-blue-900 text-white py-2.5 rounded-xl font-semibold hover:bg-blue-800 transition shadow-md hover:shadow-lg hover:-translate-y-0.5">
                    Lihat Detail
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-20">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <h3 class="text-xl font-bold text-gray-800">Buku tidak ditemukan</h3>
            <p class="text-gray-500">Coba kata kunci lain atau reset filter.</p>
            <a href="{{ route('catalog.index') }}" class="inline-block mt-4 text-blue-600 hover:underline">Reset Filter</a>
        </div>
        @endforelse
    </div>

    <div class="mt-12">
        {{ $books->withQueryString()->links() }}
    </div>
</div>
@endsection
