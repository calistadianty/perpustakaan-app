@extends('user.layout')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">
    <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-6">
        <div>
            <h2 class="text-3xl font-bold text-slate-900 flex items-center gap-2"><svg class="w-8 h-8 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg> Koleksi Pribadi</h2>
            <p class="text-slate-600 mt-2">Daftar buku favorit yang telah Anda simpan.</p>
        </div>
        
        <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 text-blue-900 font-bold hover:underline">
            <span>Jelajahi Katalog</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
        </a>
    </div>

    @if($koleksi->isEmpty())
        <div class="bg-white rounded-[2rem] p-12 text-center border border-slate-100 shadow-sm max-w-2xl mx-auto mt-12">
            <div class="w-20 h-20 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">Belum Ada Koleksi</h3>
            <p class="text-slate-500 mb-8">Anda belum menyimpan buku apapun ke dalam koleksi pribadi.</p>
            <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 bg-blue-900 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-800 transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5">
                Mulai Menjelajah
            </a>
        </div>
    @else
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($koleksi as $item)
            <div class="bg-white p-5 rounded-2xl shadow-sm hover:shadow-md transition-all group flex flex-col h-full relative" id="koleksi-item-{{ $item->book->id }}">
                
                <!-- Remove Button -->
                <button onclick="toggleBookmark({{ $item->book->id }})" class="absolute top-3 right-3 z-10 w-8 h-8 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-red-500 shadow-sm hover:bg-red-50 transition-colors" title="Hapus dari koleksi">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                </button>

                <div class="relative mb-4">
                    @if($item->book->cover)
                        <img src="{{ Storage::url($item->book->cover) }}" class="rounded-xl h-64 w-full object-cover shadow-sm group-hover:shadow-md transition-all">
                    @else
                        <div class="h-64 w-full bg-slate-50 rounded-xl flex items-center justify-center text-slate-300">No Cover</div>
                    @endif
                    <div class="absolute top-3 right-3 bg-white/90 backdrop-blur px-2 py-1 rounded-lg text-xs font-bold text-slate-900 shadow-sm flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg> {{ number_format($item->book->reviews()->avg('rating') ?? 0, 1) }}
                    </div>
                </div>
                
                <h4 class="font-bold text-slate-900 mb-1 line-clamp-1 text-lg group-hover:text-blue-600 transition">{{ $item->book->judul }}</h4>
                <p class="text-sm text-slate-500 mb-4">{{ $item->book->penulis }}</p>

                <div class="mt-auto space-y-3">
                    <div class="flex justify-between items-center text-xs font-medium">
                        <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded-md">{{ $item->book->category->nama ?? 'Umum' }}</span>
                        <span class="{{ $item->book->stok > 0 ? 'text-emerald-600' : 'text-red-500' }}">
                            {{ $item->book->stok }} Stok
                        </span>
                    </div>
                    
                    @if($item->book->stok > 0)
                        <a href="{{ route('catalog.show', $item->book->id) }}" class="block w-full py-2.5 rounded-xl bg-blue-900 text-white font-semibold hover:bg-blue-800 transition shadow-md hover:shadow-lg hover:-translate-y-0.5 text-center">
                            Detail Buku
                        </a>
                    @else
                        <button disabled class="w-full py-2.5 rounded-xl bg-slate-100 text-slate-400 font-semibold cursor-not-allowed">
                            Stok Habis
                        </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

<!-- AJAX Script for Removing Collection -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    async function toggleBookmark(bookId) {
        try {
            const response = await fetch('{{ route('koleksi.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ book_id: bookId })
            });

            const data = await response.json();

            if (data.status === 'removed') {
                Swal.fire({
                    icon: 'success',
                    title: 'Dihapus',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
                
                // Remove animation
                const item = document.getElementById(`koleksi-item-${bookId}`);
                if(item) {
                    item.style.opacity = '0';
                    item.style.transform = 'scale(0.9)';
                    setTimeout(() => {
                        item.remove();
                        // Reload if empty to show empty state
                        if(document.querySelectorAll('[id^="koleksi-item-"]').length === 0) location.reload();
                    }, 300);
                }
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Terjadi kesalahan sistem.',
            });
        }
    }
</script>
@endsection
