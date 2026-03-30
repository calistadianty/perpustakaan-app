<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $book->judul }} - Rumah Baca</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900 flex flex-col min-h-screen">

    <!-- NAVBAR (Standard) -->
    <x-navbar />

    <main class="flex-grow py-12 px-6">
        <div class="max-w-5xl mx-auto">
            <!-- Back Button (Moved from Header) -->
            <div class="mb-6">
                <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-blue-900 transition font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Katalog
                </a>
            </div>

    <main class="flex-grow py-12 px-6">
        <div class="max-w-5xl mx-auto">
            
            <!-- BOOK DETAIL CARD -->
            {{-- PENJELASAN DESAIN TAILWIND: 
                 - bg-white: Latar belakang putih. 
                 - rounded-3xl: Tepian kartu sangat melengkung (modern look). 
                 - shadow-xl: Efek bayangan membuat kartu tampak melayang (timbul). --}}
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden mb-12">
                <div class="grid md:grid-cols-3">
                    <!-- Cover -->
                    <div class="bg-gray-100 p-8 flex items-center justify-center">
                        @if($book->cover)
                            <img src="{{ Storage::url($book->cover) }}" class="rounded-xl shadow-2xl w-48 md:w-full max-w-sm object-cover transform hover:scale-105 transition duration-500">
                        @else
                            <div class="w-48 h-72 bg-gray-200 rounded-xl flex items-center justify-center text-gray-400 font-bold">No Cover</div>
                        @endif
                    </div>

                    <!-- Info -->
                    <div class="md:col-span-2 p-8 md:p-12 space-y-6">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide">
                                    {{ $book->categories->isNotEmpty() ? $book->categories->pluck('nama')->join(', ') : 'Umum' }}
                                </span>
                                <div class="flex items-center text-yellow-400 gap-1 text-sm font-bold">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg> {{ number_format($averageRating, 1) }}
                                    <span class="text-slate-400 font-normal">({{ $totalReviews }} reviews)</span>
                                </div>
                            </div>
                            <h1 class="text-4xl font-extrabold text-slate-900 mb-2 leading-tight">{{ $book->judul }}</h1>
                            <p class="text-xl text-slate-500">{{ $book->penulis }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4 border-y border-gray-100 py-6">
                            <div>
                                <p class="text-xs text-slate-400 uppercase tracking-widest font-semibold">Tahun Terbit</p>
                                <p class="font-bold text-slate-900">{{ $book->tahun_terbit ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-widest font-semibold">Stok Buku</p>
                                <p class="font-bold {{ $book->stok > 0 ? 'text-green-600' : 'text-red-500' }}">
                                    {{ $book->stok }} Tersedia
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 uppercase tracking-widest font-semibold">Penerbit</p>
                                <p class="font-bold text-slate-900">{{ $book->penerbit ?? '-' }}</p>
                            </div>
                        </div>

                        <div>
                            <h3 class="font-bold text-slate-900 mb-2">Sinopsis</h3>
                            <p class="text-slate-600 leading-relaxed">
                                {{ $book->deskripsi ?? 'Tidak ada deskripsi untuk buku ini.' }}
                            </p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="pt-4 flex flex-col gap-4">
                            {{-- PENJELASAN BLADE: @auth mengecek apakah pengunjung web adalah pengguna yang sudah Login. Jika terpenuhi, tombol "Pinjam" akan muncul. --}}
                            @auth
                                <div class="flex gap-4">
                                    @if($book->stok > 0)
                                        <button onclick="document.getElementById('borrowModal').classList.remove('hidden')" class="flex-1 bg-blue-900 text-white py-4 rounded-xl font-bold text-lg hover:bg-blue-800 transition shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg> Pinjam Buku Ini
                                        </button>
                                    @else
                                        <button disabled class="flex-1 bg-slate-200 text-slate-500 py-4 rounded-xl font-bold text-lg cursor-not-allowed flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Stok Habis
                                        </button>
                                    @endif

                                    <!-- Bookmark Button -->
                                    @php
                                        $isCollected = \App\Models\Koleksi::where('user_id', auth()->id())->where('book_id', $book->id)->exists();
                                    @endphp
                                    <button onclick="toggleBookmark({{ $book->id }})" id="bookmark-btn" class="px-6 py-4 rounded-xl font-bold text-lg border-2 {{ $isCollected ? 'bg-indigo-50 border-indigo-200 text-indigo-700' : 'bg-white border-slate-200 text-slate-600' }} hover:bg-slate-50 transition-colors flex items-center gap-2">
                                        <svg id="bookmark-icon" class="w-6 h-6 {{ $isCollected ? 'fill-current text-indigo-600' : 'fill-none text-indigo-600' }} stroke-indigo-600" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                        </svg>
                                        <span id="bookmark-text">{{ $isCollected ? 'Tersimpan' : 'Simpan' }}</span>
                                    </button>
                                </div>
                            @else
                                <a href="{{ route('login') }}" class="flex-1 bg-blue-900 text-white py-4 rounded-xl font-bold text-lg hover:bg-blue-800 transition text-center shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg> Login untuk Meminjam
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>

            <!-- Borrow Modal -->
            <div id="borrowModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" aria-hidden="true" onclick="document.getElementById('borrowModal').classList.add('hidden')"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    
                    <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full">
                        <form action="{{ route('peminjaman.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="book_id" value="{{ $book->id }}">
                            
                            <!-- Header (Compact) -->
                            <div class="bg-blue-900 px-4 py-5 text-center">
                                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-800 mb-3 shadow-inner">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                                <h3 class="text-lg leading-6 font-bold text-white uppercase tracking-wider" id="modal-title">Konfirmasi Peminjaman</h3>
                                <p class="text-blue-200 text-xs mt-1">Lengkapi data di bawah ini</p>
                            </div>

                            <div class="px-5 py-5 space-y-4">
                                <!-- Readonly Name -->
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Peminjam</label>
                                    <div class="bg-slate-100 rounded-lg px-3 py-2 border border-slate-200 text-slate-700 font-semibold flex items-center gap-2 text-sm">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        {{ auth()->user()->name }}
                                    </div>
                                </div>

                                <!-- Address -->
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Alamat Lengkap</label>
                                    <textarea name="alamat" required rows="2" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-medium text-slate-900 text-sm" placeholder="Masukkan alamat lengkap...">{{ auth()->user()->alamat }}</textarea>
                                </div>

                                <!-- Dates Grid -->
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Tanggal Pinjam</label>
                                        <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-bold text-slate-800 text-sm" onchange="updateMinReturnDate()">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Rencana Kembali</label>
                                        <input type="date" name="tanggal_batas_kembali" id="tanggal_batas_kembali" required min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d', strtotime('+14 days')) }}" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-bold text-blue-700 bg-blue-50 text-sm">
                                        <p class="text-[10px] text-blue-600 mt-0.5 font-semibold">*Max 14 Hari</p>
                                    </div>
                                </div>

                                <!-- Notes -->
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Catatan (Opsional)</label>
                                    <input type="text" name="keterangan" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-medium text-sm" placeholder="Cth: Keperluan Skripsi">
                                </div>
                            </div>

                            <!-- Buttons (Grid for Symmetry) -->
                            <div class="px-5 pb-5 pt-1">
                                <div class="grid grid-cols-2 gap-3">
                                    <button type="button" onclick="document.getElementById('borrowModal').classList.add('hidden')" class="w-full py-2.5 rounded-lg border-2 border-slate-200 text-slate-600 font-bold hover:bg-slate-50 hover:border-slate-300 transition uppercase tracking-wide text-xs">
                                        Batal
                                    </button>
                                    <button type="submit" class="w-full py-2.5 rounded-lg bg-blue-900 text-white font-bold hover:bg-blue-800 transition shadow-md hover:shadow-lg uppercase tracking-wide text-xs">
                                        Ajukan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script>
                function updateMinReturnDate() {
                    const pinjamDate = document.getElementById('tanggal_pinjam').value;
                    const returnInput = document.getElementById('tanggal_batas_kembali');
                    
                    if (pinjamDate) {
                        returnInput.min = pinjamDate;
                        
                        // Calculate Max Date (14 days from start)
                        const date = new Date(pinjamDate);
                        date.setDate(date.getDate() + 14);
                        const maxDate = date.toISOString().split('T')[0];
                        
                        returnInput.max = maxDate;
                        
                        // Reset value if out of range
                        if (returnInput.value < pinjamDate || returnInput.value > maxDate) {
                            returnInput.value = '';
                        }
                    }
                }
            </script>
                        </div>
                    </div>
                </div>
            </div>

            <!-- REVIEWS SECTION -->
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Write Review -->
                <div class="md:col-span-1">
                    <div class="bg-white p-6 rounded-3xl shadow-sm sticky top-24">
                        <h3 class="text-xl font-bold mb-4 flex items-center gap-2"><svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg> Tulis Ulasan</h3>
                        @auth
                            @if($userHasReviewed)
                                <div class="bg-green-50 text-green-800 p-4 rounded-xl text-center">
                                    <p class="font-semibold">Terima kasih!</p>
                                    <p class="text-sm">Anda sudah memberikan ulasan untuk buku ini.</p>
                                </div>
                            @elseif(!$userCanReview)
                                <div class="bg-amber-50 text-amber-800 p-4 rounded-xl text-center border border-amber-200">
                                    <h4 class="font-bold flex items-center justify-center gap-2 mb-1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Belum Bisa Mengulas
                                    </h4>
                                    <p class="text-sm leading-relaxed">Anda harus meminjam dan mengembalikan buku ini terlebih dahulu sebelum dapat memberikan ulasan.</p>
                                </div>
                            @else
                                <form action="{{ route('catalog.rate', $book) }}" method="POST" class="space-y-4">
                                    @csrf
                                    <div class="flex items-center gap-4">
                                        <div class="flex flex-row-reverse justify-end gap-1 group">
                                            @foreach(range(5,1) as $rating)
                                                <input type="radio" id="star-{{ $rating }}" name="rating" value="{{ $rating }}" class="peer hidden" required onchange="document.getElementById('rating-label-new').innerText = this.value + '/5 Bintang'">
                                                <label for="star-{{ $rating }}" class="cursor-pointer text-3xl text-gray-300 transition-colors peer-checked:text-yellow-400 peer-hover:text-yellow-400 hover:text-yellow-400 peer-checked:group-[]:text-yellow-400" 
                                                    style="text-shadow: 0 1px 2px rgba(0,0,0,0.1);">
                                                    ★
                                                </label>
                                            @endforeach
                                            
                                            <!-- CSS styling for "siblings after" (which are visually before) -->
                                            <style>
                                                /* If a radio is checked, highlight all following labels */
                                                .group input:checked ~ label { color: #facc15; }
                                                /* If a label is hovered, highlight all following labels */
                                                .group label:hover ~ label { color: #facc15; }
                                            </style>
                                        </div>
                                        <span id="rating-label-new" class="text-sm font-bold text-slate-600">0/5 Bintang</span>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold mb-1">Komentar</label>
                                        <textarea name="comment" rows="4" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" placeholder="Bagaimana pendapatmu tentang buku ini?"></textarea>
                                    </div>
                                    <button type="submit" class="w-full bg-slate-900 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition shadow-md">
                                        Kirim Ulasan
                                    </button>
                                </form>
                            @endif
                        @else
                            <div class="bg-slate-50 p-6 rounded-xl text-center">
                                <p class="text-slate-600 mb-4">Silakan login untuk memberikan rating dan review.</p>
                                <a href="{{ route('login') }}" class="text-blue-700 font-bold hover:underline">Masuk disini</a>
                            </div>
                        @endauth
                    </div>
                </div>

                <!-- Review List -->
                <div class="md:col-span-2">
                    <h3 class="text-xl font-bold mb-6">Ulasan Pembaca ({{ $totalReviews }})</h3>
                    
                    <div class="space-y-4">
                        @forelse($book->reviews()->latest()->get() as $review)
                            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center font-bold text-slate-700">
                                            {{ substr($review->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900">{{ $review->user->name }}</p>
                                            <p class="text-xs text-slate-500">{{ $review->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="bg-yellow-50 px-3 py-1 rounded-full text-yellow-600 font-bold text-sm flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg> {{ $review->rating }}
                                        </div>
                                        @if(auth()->id() === $review->user_id)
                                            <div class="relative group">
                                                <button class="text-slate-400 hover:text-slate-600">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                                                </button>
                                                <!-- Dropdown Menu -->
                                                <div class="absolute right-0 mt-1 w-32 bg-white rounded-lg shadow-lg border border-slate-100 hidden group-hover:block z-10">
                                                    <button onclick="document.getElementById('edit-review-{{ $review->id }}').classList.toggle('hidden')" class="block w-full text-left px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-blue-600">
                                                        Edit
                                                    </button>
                                                    <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Hapus ulasan ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <p class="text-slate-600 pl-13 leading-relaxed">{{ $review->comment }}</p>

                                <!-- Edit Form (Hidden by default) -->
                                @if(auth()->id() === $review->user_id)
                                    <div id="edit-review-{{ $review->id }}" class="hidden mt-4 pt-4 border-t border-slate-100">
                                        <form action="{{ route('reviews.update', $review->id) }}" method="POST" class="space-y-3">
                                            @csrf
                                            @method('PATCH')
                                            <div>
                                                <label class="block text-xs font-bold text-slate-500 mb-1">Edit Rating</label>
                                                <div class="flex items-center gap-4">
                                                    <div class="flex flex-row-reverse justify-end gap-1 group-edit-{{ $review->id }}">
                                                        @foreach(range(5,1) as $rating)
                                                            <input type="radio" id="star-edit-{{ $review->id }}-{{ $rating }}" name="rating" value="{{ $rating }}" class="peer hidden" {{ $review->rating == $rating ? 'checked' : '' }} onchange="document.getElementById('rating-label-edit-{{ $review->id }}').innerText = this.value + '/5 Bintang'">
                                                            <label for="star-edit-{{ $review->id }}-{{ $rating }}" class="cursor-pointer text-2xl text-gray-300 transition-colors hover:text-yellow-400">
                                                                ★
                                                            </label>
                                                        @endforeach
                                                        
                                                        <style>
                                                            .group-edit-{{ $review->id }} input:checked ~ label { color: #facc15; }
                                                            .group-edit-{{ $review->id }} label:hover ~ label { color: #facc15; }
                                                        </style>
                                                    </div>
                                                    <span id="rating-label-edit-{{ $review->id }}" class="text-xs font-bold text-slate-600">{{ $review->rating }}/5 Bintang</span>
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-slate-500 mb-1">Edit Komentar</label>
                                                <textarea name="comment" rows="3" class="w-full rounded-lg border-slate-200 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">{{ $review->comment }}</textarea>
                                            </div>
                                            <div class="flex gap-2 justify-end">
                                                <button type="button" onclick="document.getElementById('edit-review-{{ $review->id }}').classList.add('hidden')" class="px-3 py-1.5 text-xs font-bold text-slate-500 hover:text-slate-700">Batal</button>
                                                <button type="submit" class="bg-blue-900 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-blue-800 transition">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-12 bg-white rounded-2xl border border-dashed border-gray-300">
                                <p class="text-gray-400">Belum ada ulasan. Jadilah yang pertama!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // PENJELASAN JAVASCRIPT: Fungsi async ini dijalankan saat tombol "Simpan" (Bookmark) diklik.
        // Kode ini menembak API/Controller Laravel secara diam-diam (AJAX / fetch api) 
        // sehingga data tersimpan tanpa perlu mereload / memuat ulang tampilan webnya.
        async function toggleBookmark(bookId) {
            const btn = document.getElementById('bookmark-btn');
            const icon = document.getElementById('bookmark-icon');
            const text = document.getElementById('bookmark-text');
            
            // Optimistic UI update
            const originalState = {
                class: btn.className,
                fill: icon.classList.contains('fill-current'),
                text: text.innerText
            };

            try {
                const response = await fetch('{{ route('koleksi.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ book_id: bookId })
                });

                const data = await response.json();

                if (data.status === 'added') {
                    btn.classList.remove('bg-white', 'border-slate-200', 'text-slate-600');
                    btn.classList.add('bg-indigo-50', 'border-indigo-200', 'text-indigo-700');
                    icon.classList.remove('fill-none');
                    icon.classList.add('fill-current');
                    text.innerText = 'Tersimpan';
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Tersimpan!',
                        text: 'Buku berhasil ditambahkan ke koleksi.',
                        timer: 1500,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                } else if (data.status === 'removed') {
                    btn.classList.add('bg-white', 'border-slate-200', 'text-slate-600');
                    btn.classList.remove('bg-indigo-50', 'border-indigo-200', 'text-indigo-700');
                    icon.classList.add('fill-none');
                    icon.classList.remove('fill-current');
                    text.innerText = 'Simpan';

                    Swal.fire({
                        icon: 'info',
                        title: 'Dihapus',
                        text: 'Buku dihapus dari koleksi.',
                        timer: 1500,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan saat memproses permintaan.',
                });
            }
        }
    </script>
    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#1e3a8a',
            });
        @endif

        @if(session('error_limit'))
            Swal.fire({
                icon: 'warning',
                title: 'Batas Peminjaman Penuh',
                text: '{{ session('error_limit') }}',
                confirmButtonColor: '#f59e0b',
                confirmButtonText: 'Baik, Saya Mengerti'
            });
        @endif

        @if(session('error_overdue'))
            Swal.fire({
                icon: 'error',
                title: 'Peminjaman Ditangguhkan',
                text: '{{ session('error_overdue') }}',
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Lihat Peminjaman Saya',
                allowOutsideClick: false
            }).then((result) => {
                if(result.isConfirmed) {
                    window.location.href = "{{ route('peminjaman.index') }}";
                }
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Perhatian!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#ef4444',
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                html: '<ul class="text-left">@foreach($errors->all() as $error)<li>• {{ $error }}</li>@endforeach</ul>',
                confirmButtonColor: '#ef4444',
            });
        @endif
    </script>
</body>
</html>
