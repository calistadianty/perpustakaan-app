@extends('user.layout')

@section('content')

<!-- HERO SECTION (Small) -->
<section class="bg-white border-b border-slate-200 reveal">
  <div class="max-w-7xl mx-auto px-6 py-12 flex justify-between items-center">
    <div>
      <h2 class="text-3xl font-black text-slate-900 tracking-tight">Selamat Datang, {{ auth()->user()->name }}! 👋</h2>
      <p class="text-slate-500 mt-2 text-lg">Temukan buku favoritmu dan mulai membaca hari ini.</p>
    </div>
  </div>
</section>

<!-- SEARCH & FILTER -->
<section class="bg-white py-8 reveal">
  <div class="max-w-7xl mx-auto px-6">
    <div class="flex flex-col md:flex-row gap-4 items-center">
      <div class="relative flex-1">
        <input type="text" placeholder="Cari judul buku, penulis..." 
        class="w-full px-6 py-4 pl-14 rounded-2xl border-0 bg-white ring-1 ring-slate-200 focus:ring-2 focus:ring-blue-500 focus:outline-none text-lg shadow-sm">
        <div class="absolute left-5 top-1/2 transform -translate-y-1/2 text-slate-400">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CATALOG BOOK -->
<section class="bg-slate-50 py-12 reveal">
  <div class="max-w-7xl mx-auto px-6">
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
      
      @foreach($books as $book)
      <div class="bg-white border border-slate-100 p-6 rounded-3xl hover:shadow-xl hover:shadow-blue-900/5 transition-all duration-300 group hover:-translate-y-1">
        <div class="relative mb-4">
            @if($book->cover)
                <img src="{{ asset('storage/' . $book->cover) }}" class="rounded-2xl h-56 w-full object-cover">
            @else
                <img src="https://via.placeholder.com/300x400" class="rounded-2xl h-56 w-full object-cover">
            @endif
          
          <div class="{{ $book->stok > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-red-50 text-red-700 border border-red-100' }} absolute top-3 right-3 px-3 py-1 rounded-full text-xs font-bold shadow-sm uppercase tracking-wide">
            {{ $book->stok > 0 ? 'Ada Stok' : 'Habis' }}
          </div>
        </div>
        
        <h4 class="font-bold text-lg mb-1 truncate text-slate-900">{{ $book->judul }}</h4>
        <p class="text-slate-500 text-sm mb-4 truncate">{{ $book->penulis }}</p>
        
        <div class="flex items-center justify-between mb-4">
          <div class="flex items-center gap-1 mb-4"><svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg><svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg><svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg><svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg><svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg></div>
          <span class="text-sm text-gray-500">5.0</span>
        </div>

        <div class="flex gap-2 mb-5">
          <span class="bg-blue-50 text-blue-700 px-2.5 py-1 rounded-lg text-xs font-medium">{{ $book->category->nama_kategori ?? 'Umum' }}</span>
          <span class="bg-slate-100 text-slate-600 px-2.5 py-1 rounded-lg text-xs font-medium">{{ $book->stok }} stok</span>
        </div>

          <button class="w-full bg-blue-900 text-white py-3 rounded-xl font-semibold hover:bg-blue-700 transition-colors shadow-md hover:shadow-lg flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg> Detail Buku
          </button>
      </div>
      @endforeach

    </div>
  </div>
</section>

@endsection
