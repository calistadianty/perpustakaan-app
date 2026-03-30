<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Rumah Baca | Sistem Peminjaman Buku</title>

@vite(['resources/css/app.css', 'resources/js/app.js'])

<style>
.reveal {
  opacity: 0;
  transform: translateY(40px);
  transition: all 0.8s ease;
}
.reveal.active {
  opacity: 1;
  transform: translateY(0);
}

</style>

</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased">

<!-- NAVBAR -->
<!-- NAVBAR -->
<x-navbar class="reveal" />

<!-- HERO -->
<section class="relative min-h-[600px] flex items-center justify-center overflow-hidden reveal">
  <!-- Background Image with Overlay -->
  <div class="absolute inset-0 z-0">
    <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=1600&fit=crop" 
         class="w-full h-full object-cover object-center"
         alt="Library Background">
    <div class="absolute inset-0 bg-blue-900/80 backdrop-blur-[2px]"></div>
  </div>

  <!-- Content -->
  <div class="relative z-10 max-w-5xl mx-auto px-6 text-center text-white">
      
      <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md px-5 py-2.5 rounded-full text-sm font-bold text-white border border-white/20 shadow-sm mb-8 hover:bg-white/20 transition-colors cursor-default">
        @auth
            <span>👋 Halo, {{ auth()->user()->name }}</span>
        @else
            <span class="flex items-center gap-2"><span class="w-2 h-2 bg-blue-400 rounded-full animate-pulse"></span> Perpustakaan Masa Depan</span>
        @endauth
      </div>
      
      <h2 class="text-5xl lg:text-7xl font-black leading-tight tracking-tight mb-6 drop-shadow-lg">
        Jelajahi Dunia <br/>
        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-white">Lewat Membaca</span>
      </h2>

      <p class="text-blue-100 text-lg md:text-xl leading-relaxed max-w-2xl mx-auto font-medium mb-10 drop-shadow-md">
        Solusi digital untuk perpustakaan modern. Cari, pinjam, dan kembalikan buku dengan mudah.
      </p>

      <div class="flex flex-col sm:flex-row gap-5 justify-center items-center">
        @auth
             <a href="#katalog" class="group bg-white text-blue-900 px-8 py-4 rounded-2xl font-bold shadow-xl shadow-blue-900/20 hover:shadow-2xl hover:-translate-y-1 transition-all flex items-center justify-center gap-2 min-w-[200px]">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg> Mulai Menjelajah
              <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        @else
            <a href="{{ route('login') }}" class="group bg-white text-blue-900 px-8 py-4 rounded-2xl font-bold shadow-xl shadow-blue-900/20 hover:shadow-2xl hover:bg-blue-50 hover:-translate-y-1 transition-all flex items-center justify-center gap-2 min-w-[200px]">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg> Pinjam Sekarang
              <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
            </a>
            
            <a href="{{ route('register') }}" class="bg-transparent text-white border-2 border-white/30 px-8 py-4 rounded-2xl font-bold hover:bg-white/10 hover:border-white transition-all text-center min-w-[200px]">
              Daftar Akun
            </a>
        @endauth
      </div>

      <!-- Quick Stats (Centered) -->
      <div class="grid grid-cols-3 gap-8 pt-12 mt-8 border-t border-white/10 max-w-3xl mx-auto">
        <div>
          <div class="text-3xl font-black text-white">{{ \App\Models\Book::approved()->count() }}+</div>
          <div class="text-sm font-bold text-blue-200 uppercase tracking-wide">Koleksi Buku</div>
        </div>
        <div>
          <div class="text-3xl font-black text-white">{{ \App\Models\User::where('role', 'user')->count() }}+</div>
          <div class="text-sm font-bold text-blue-200 uppercase tracking-wide">Anggota</div>
        </div>
        <div>
          <div class="text-3xl font-black text-white">24/7</div>
          <div class="text-sm font-bold text-blue-200 uppercase tracking-wide">Akses Online</div>
        </div>
      </div>
  </div>
</section>

<!-- LANGKAH MUDAH SECTION -->
<section class="bg-white py-24 reveal">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center max-w-3xl mx-auto mb-20">
            <h3 class="text-sm font-bold text-blue-600 tracking-widest uppercase mb-3">Cara Kerja</h3>
            <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900">Pinjam Buku dalam 3 Langkah</h2>
        </div>

        <div class="grid md:grid-cols-3 gap-10">
            <!-- Step 1 -->
            <div class="bg-slate-50 p-10 rounded-[2.5rem] text-center hover:-translate-y-2 transition-transform duration-300 border border-slate-100 hover:border-blue-100 hover:shadow-xl hover:shadow-blue-900/5 group">
                <div class="w-20 h-20 bg-white text-blue-900 rounded-3xl flex items-center justify-center text-3xl font-black mx-auto mb-8 shadow-lg group-hover:bg-blue-900 group-hover:text-white transition-colors">1</div>
                <h4 class="text-2xl font-bold text-slate-900 mb-4">Pilih Buku</h4>
                <p class="text-slate-600 leading-relaxed">Cari buku favoritmu di katalog kami. Cek sinopsis dan rating sebelum meminjam.</p>
            </div>

            <!-- Step 2 -->
            <div class="bg-slate-50 p-10 rounded-[2.5rem] text-center hover:-translate-y-2 transition-transform duration-300 border border-slate-100 hover:border-blue-100 hover:shadow-xl hover:shadow-blue-900/5 group">
                <div class="w-20 h-20 bg-white text-blue-900 rounded-3xl flex items-center justify-center text-3xl font-black mx-auto mb-8 shadow-lg group-hover:bg-blue-900 group-hover:text-white transition-colors">2</div>
                <h4 class="text-2xl font-bold text-slate-900 mb-4">Ajukan Peminjaman</h4>
                <p class="text-slate-600 leading-relaxed">Ajukan peminjaman buku dan tunggu konfirmasi dari perpustakaan.</p>
            </div>

            <!-- Step 3 -->
            <div class="bg-slate-50 p-10 rounded-[2.5rem] text-center hover:-translate-y-2 transition-transform duration-300 border border-slate-100 hover:border-blue-100 hover:shadow-xl hover:shadow-blue-900/5 group">
                <div class="w-20 h-20 bg-white text-blue-900 rounded-3xl flex items-center justify-center text-3xl font-black mx-auto mb-8 shadow-lg group-hover:bg-blue-900 group-hover:text-white transition-colors">3</div>
                <h4 class="text-2xl font-bold text-slate-900 mb-4">Konfirmasi dan Ambil Buku</h4>
                <p class="text-slate-600 leading-relaxed">Setelah konfirmasi, datang ke perpustakaan dan ambil bukumu!</p>
            </div>
        </div>
    </div>
</section>

<!-- QUOTE OF THE DAY -->
<section class="bg-blue-900 py-24 relative overflow-hidden reveal">
    <!-- Background Patterns -->
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#FFFFFF 1px, transparent 1px); background-size: 30px 30px;"></div>
    
    <div class="max-w-4xl mx-auto px-6 text-center relative z-10">
        <div class="text-6xl text-blue-400 font-serif mb-6 opacity-50">"</div>
        <blockquote class="text-3xl md:text-5xl font-serif text-white leading-relaxed mb-10 tracking-wide">
            Membaca adalah alat paling dasar untuk meraih hidup yang baik.
        </blockquote>
        <div class="flex items-center justify-center gap-4">
            <div class="h-0.5 w-12 bg-blue-400/50"></div>
            <cite class="text-blue-200 not-italic font-medium tracking-widest uppercase text-sm">Joseph Addison</cite>
            <div class="h-0.5 w-12 bg-blue-400/50"></div>
        </div>
    </div>
</section>



<!-- ALL BOOKS CATALOG -->
<section id="katalog" class="bg-slate-50 py-24 reveal">
  <div class="max-w-7xl mx-auto px-6">
      
      <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6 border-b border-slate-200 pb-8">
        <div>
            <span class="text-blue-600 font-bold tracking-widest uppercase text-sm mb-2 block">Katalog Kami</span>
            <h3 class="text-4xl font-black text-slate-900">Temukan Buku Favoritmu</h3>
        </div>
        
        <!-- Search (Visual Only for now) -->
        <div class="relative w-full md:w-96">
            <input type="text" placeholder="Cari judul buku..." class="w-full pl-12 pr-4 py-4 rounded-xl border-0 bg-white shadow-sm ring-1 ring-slate-200 focus:ring-2 focus:ring-blue-500 focus:outline-none transition-shadow">
            <svg class="w-5 h-5 text-slate-400 absolute left-4 top-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
      </div>

      <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
          @foreach($books as $book)
          <div class="bg-white p-5 rounded-2xl shadow-sm hover:shadow-xl hover:shadow-blue-900/5 transition-all group border border-slate-100 hover:border-blue-100 hover:-translate-y-1">
             <div class="relative mb-5">
                 @if($book->cover)
                    <img src="{{ Storage::url($book->cover) }}" class="rounded-xl h-64 w-full object-cover shadow-sm">
                 @else
                    <div class="h-64 w-full bg-slate-100 rounded-xl flex items-center justify-center text-slate-400">No Cover</div>
                 @endif
             </div>
             
             <h4 class="font-bold text-slate-900 mb-1 line-clamp-1 text-lg" title="{{ $book->judul }}">{{ $book->judul }}</h4>
             <p class="text-sm text-slate-500 mb-4">{{ $book->penulis }}</p>

             <div class="flex gap-2 mb-5">
                <span class="{{ $book->stok > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-red-50 text-red-700 border border-red-100' }} px-2.5 py-1 rounded-md text-xs font-bold uppercase tracking-wide">
                    {{ $book->stok > 0 ? 'Ada Stok' : 'Habis' }}
                </span>
             </div>

                <a href="{{ route('catalog.show', $book->id) }}" class="block w-full text-center py-3 rounded-xl bg-blue-900 text-white font-semibold hover:bg-blue-800 transition-colors text-sm shadow-md hover:shadow-lg">
                    Detail Buku
                </a>
          </div>
          @endforeach
      </div>

      <div class="mt-16 text-center">
          <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 bg-white text-slate-800 border border-slate-200 px-8 py-4 rounded-full font-bold hover:bg-slate-50 hover:border-blue-300 transition-all shadow-sm hover:shadow-md">
              Lihat Selengkapnya ({{ \App\Models\Book::approved()->count() }})
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
          </a>
      </div>

  </div>
</section>

<!-- FOOTER -->
<footer class="bg-blue-900 text-blue-100 py-16 reveal">
  <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-4 gap-12">
    <div>
      <h4 class="font-bold text-white text-xl mb-6">Rumah Baca</h4>
      <p class="text-sm leading-relaxed opacity-80">Platform peminjaman buku modern untuk kemudahan akses literasi bagi semua.</p>
    </div>
    <div>
      <h5 class="font-bold text-white mb-6">Layanan</h5>
      <ul class="space-y-3 text-sm">
        <li><a href="#" class="hover:text-blue-400 transition-colors">Peminjaman Buku</a></li>
        <li><a href="#" class="hover:text-blue-400 transition-colors">Rating & Review</a></li>
        <li><a href="#" class="hover:text-blue-400 transition-colors">Katalog Online</a></li>
      </ul>
    </div>
    <div>
      <h5 class="font-bold text-white mb-6">Akun</h5>
      <ul class="space-y-3 text-sm">
        @auth
            <li><a href="{{ route('profile.edit') }}" class="hover:text-blue-400 transition-colors">Edit Profil</a></li>
            <li>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="hover:text-blue-400 transition-colors">Logout</button>
                </form>
            </li>
        @else
            <li><a href="{{ route('login') }}" class="hover:text-blue-400 transition-colors">Masuk</a></li>
            <li><a href="{{ route('register') }}" class="hover:text-blue-400 transition-colors">Daftar</a></li>
        @endauth
      </ul>
    </div>
    <div>
      <h5 class="font-bold text-white mb-6">Kontak</h5>
      <ul class="space-y-3 text-sm">
        <li class="flex items-center gap-3"><svg class="w-5 h-5 text-blue-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg> info@rumahbaca.com</li>
        <li class="flex items-center gap-3"><svg class="w-5 h-5 text-blue-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg> (021) 123-4567</li>
        <li class="flex items-center gap-3"><svg class="w-5 h-5 text-blue-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg> Jakarta, Indonesia</li>
      </ul>
    </div>
  </div>
  <div class="max-w-7xl mx-auto px-6 pt-8 mt-12 border-t border-blue-800 text-center text-sm opacity-60">
    <p>© {{ date('Y') }} Rumah Baca. Semua hak cipta dilindungi undang-undang.</p>
  </div>
</footer>

<!-- SCROLL ANIMATIONS -->
<script>
const reveal = document.querySelectorAll('.reveal');
function showOnScroll() {
  reveal.forEach(el => {
    const windowHeight = window.innerHeight;
    const elementTop = el.getBoundingClientRect().top;
    if (elementTop < windowHeight - 120) {
      el.classList.add('active');
    }
  });
}
window.addEventListener('scroll', showOnScroll);
showOnScroll();
</script>

</body>
</html>
