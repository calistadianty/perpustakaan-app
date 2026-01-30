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

.gradient-text {
  background: linear-gradient(135deg, #0B1957 0%, #9ECCFA 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.book-card {
  background: linear-gradient(145deg, #ffffff 0%, #f8f9ff 100%);
  box-shadow: 0 10px 30px rgba(11, 25, 87, 0.1);
}

.availability-badge {
  background: linear-gradient(90deg, #10B981, #059669);
  color: white;
}

.borrowed-badge {
  background: linear-gradient(90deg, #EF4444, #DC2626);
  color: white;
}

.rating-stars {
  color: #FBBF24;
}
</style>

</head>
<body class="bg-[#F8F3EA] text-[#0B1957]">

<!-- NAVBAR -->
<header class="bg-white shadow-lg sticky top-0 z-50 reveal">
  <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
    <div class="flex items-center gap-3">
      <div class="w-10 h-10 bg-gradient-to-br from-[#0B1957] to-[#9ECCFA] rounded-xl flex items-center justify-center">
        <span class="text-white font-bold text-lg">📚</span>
      </div>
      <h1 class="font-bold text-xl gradient-text">Rumah Baca</h1>
    </div>

    <nav class="hidden md:flex gap-6 text-sm font-medium">
      <a href="#" class="hover:text-[#9ECCFA] transition-colors">Home</a>
      <a href="#" class="hover:text-[#9ECCFA] transition-colors">Katalog Buku</a>
      <a href="#" class="hover:text-[#9ECCFA] transition-colors">Pinjaman Saya</a>
      <a href="#" class="hover:text-[#9ECCFA] transition-colors">Riwayat</a>

      <a href="{{ route('login') }}" class="bg-gradient-to-r from-[#0B1957] to-[#2d4a8a] text-white px-6 py-2 rounded-full font-semibold hover:scale-105 transition-all">
        Masuk
      </a>
    </nav>
  </div>
</header>

<!-- HERO -->
<section class="bg-gradient-to-br from-[#9ECCFA] to-[#b8d5fa] reveal">
  <div class="max-w-7xl mx-auto px-6 py-20 grid lg:grid-cols-2 gap-12 items-center">
    
    <div class="space-y-6">
      <div class="inline-block bg-white/30 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-medium">
        📖 Sistem Peminjaman Modern
      </div>
      
      <h2 class="text-5xl lg:text-6xl font-extrabold leading-tight">
        Pinjam Buku  
        <span class="block gradient-text">Lebih Mudah</span>
      </h2>

      <p class="text-[#2d3b6a] text-lg leading-relaxed max-w-lg">
        Cek ketersediaan buku fisik, ajukan peminjaman online, dan berikan rating untuk buku favorit Anda.
      </p>

      <div class="flex flex-col sm:flex-row gap-4 pt-4">
        <a href="{{ route('login') }}" class="bg-gradient-to-r from-[#0B1957] to-[#2d4a8a] text-white px-8 py-4 rounded-2xl font-semibold shadow-xl hover:scale-105 transition-all text-center">
          🚀 Mulai Pinjam Buku
        </a>
        
        <a href="{{ route('register') }}" class="bg-white/80 text-[#0B1957] px-8 py-4 rounded-2xl font-semibold hover:bg-white hover:scale-105 transition-all text-center">
          📋 Registrasi
        </a>
      </div>

      <!-- Quick Stats -->
      <div class="grid grid-cols-3 gap-6 pt-8">
        <div class="text-center">
          <div class="text-2xl font-bold text-[#0B1957]">5,240</div>
          <div class="text-sm text-[#2d3b6a]">Buku Tersedia</div>
        </div>
        <div class="text-center">
          <div class="text-2xl font-bold text-[#0B1957]">1,850</div>
          <div class="text-sm text-[#2d3b6a]">Member Aktif</div>
        </div>
        <div class="text-center">
          <div class="text-2xl font-bold text-[#0B1957]">98%</div>
          <div class="text-sm text-[#2d3b6a]">Rating Kepuasan</div>
        </div>
      </div>
    </div>

    <div class="relative flex justify-center">
      <img src="https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=500&h=600&fit=crop" 
      class="rounded-3xl shadow-2xl object-cover h-96 w-80 border-4 border-white/50">
      
      <!-- Floating Status Card -->
      <div class="absolute -top-6 -left-6 bg-white p-4 rounded-2xl shadow-xl">
        <div class="flex items-center gap-3">
          <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
          <div class="text-sm">
            <div class="font-semibold text-green-600">Tersedia</div>
            <div class="text-xs text-gray-500">3 dari 5 buku</div>
          </div>
        </div>
      </div>

      <div class="absolute -bottom-6 -right-6 bg-white p-4 rounded-2xl shadow-xl">
        <div class="text-sm text-center">
          <div class="font-semibold">Rating</div>
          <div class="text-yellow-500 text-lg">⭐⭐⭐⭐⭐</div>
          <div class="text-xs text-gray-500">4.8/5</div>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- SEARCH & FILTER -->
<section class="bg-white py-12 reveal">
  <div class="max-w-6xl mx-auto px-6">
    <div class="flex flex-col md:flex-row gap-4 items-center">
      <!-- Search Bar -->
      <div class="relative flex-1">
        <input type="text" placeholder="Cari judul buku, penulis, atau ISBN..." 
        class="w-full px-6 py-4 pl-14 rounded-2xl border-2 border-gray-100 focus:border-[#9ECCFA] focus:outline-none text-lg shadow-lg">
        <div class="absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
        </div>
      </div>

      <!-- Availability Filter -->
      <div class="flex gap-2">
        <button class="availability-badge px-4 py-2 rounded-full text-sm font-semibold">
          ✅ Tersedia (234)
        </button>
        <button class="bg-gray-100 text-gray-600 px-4 py-2 rounded-full text-sm font-semibold">
          📚 Semua (1,240)
        </button>
      </div>
    </div>
  </div>
</section>

<!-- CATEGORY TABS -->
<section class="bg-[#F8F3EA] py-8 reveal">
  <div class="max-w-6xl mx-auto px-6 flex flex-wrap justify-center gap-4">
    <span class="bg-[#9ECCFA] text-[#0B1957] px-6 py-3 rounded-full text-sm font-semibold cursor-pointer">Semua Kategori</span>
    <span class="bg-white text-[#0B1957] px-6 py-3 rounded-full text-sm font-semibold hover:bg-[#9ECCFA] transition cursor-pointer">📖 Novel</span>
    <span class="bg-white text-[#0B1957] px-6 py-3 rounded-full text-sm font-semibold hover:bg-[#9ECCFA] transition cursor-pointer">🎓 Pelajaran</span>
    <span class="bg-white text-[#0B1957] px-6 py-3 rounded-full text-sm font-semibold hover:bg-[#9ECCFA] transition cursor-pointer">🎨 Komik</span>
    <span class="bg-white text-[#0B1957] px-6 py-3 rounded-full text-sm font-semibold hover:bg-[#9ECCFA] transition cursor-pointer">🔬 Ensiklopedia</span>
    <span class="bg-white text-[#0B1957] px-6 py-3 rounded-full text-sm font-semibold hover:bg-[#9ECCFA] transition cursor-pointer">💼 Bisnis</span>
  </div>
</section>

<!-- POPULAR BOOKS -->
<section class="bg-white py-20 reveal">
  <div class="max-w-7xl mx-auto px-6">

    <div class="text-center mb-16">
      <h3 class="text-4xl font-extrabold mb-4 text-[#0B1957]">
        🏆 Buku Paling Dipinjam
      </h3>
      <p class="text-gray-600 text-lg">Favorit member bulan ini</p>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">

      <!-- Book Card 1 -->
      <div class="book-card p-6 rounded-3xl hover:scale-105 transition-all duration-300 group">
        <div class="relative mb-4">
          <img src="https://covers.openlibrary.org/b/id/10521270-L.jpg" class="rounded-2xl h-56 w-full object-cover">
          <div class="availability-badge absolute top-3 right-3 px-3 py-1 rounded-full text-xs font-semibold">
            ✅ Tersedia
          </div>
        </div>
        
        <h4 class="font-bold text-lg mb-1">Laskar Pelangi</h4>
        <p class="text-gray-600 text-sm mb-3">Andrea Hirata</p>
        
        <div class="flex items-center justify-between mb-4">
          <div class="rating-stars">⭐⭐⭐⭐⭐</div>
          <span class="text-sm text-gray-500">4.8 (124 reviews)</span>
        </div>

        <div class="flex gap-2 mb-4">
          <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">Novel</span>
          <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">3 tersedia</span>
        </div>

        <button class="w-full bg-[#0B1957] text-white py-3 rounded-xl font-semibold hover:bg-[#2d4a8a] transition">
          📚 Pinjam Sekarang
        </button>
      </div>

      <!-- Book Card 2 -->
      <div class="book-card p-6 rounded-3xl hover:scale-105 transition-all duration-300 group">
        <div class="relative mb-4">
          <img src="https://covers.openlibrary.org/b/id/11153234-L.jpg" class="rounded-2xl h-56 w-full object-cover">
          <div class="availability-badge absolute top-3 right-3 px-3 py-1 rounded-full text-xs font-semibold">
            ✅ Tersedia
          </div>
        </div>
        
        <h4 class="font-bold text-lg mb-1">Negeri 5 Menara</h4>
        <p class="text-gray-600 text-sm mb-3">Ahmad Fuadi</p>
        
        <div class="flex items-center justify-between mb-4">
          <div class="rating-stars">⭐⭐⭐⭐⭐</div>
          <span class="text-sm text-gray-500">4.9 (89 reviews)</span>
        </div>

        <div class="flex gap-2 mb-4">
          <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">Novel</span>
          <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">2 tersedia</span>
        </div>

        <button class="w-full bg-[#0B1957] text-white py-3 rounded-xl font-semibold hover:bg-[#2d4a8a] transition">
          📚 Pinjam Sekarang
        </button>
      </div>

      <!-- Book Card 3 -->
      <div class="book-card p-6 rounded-3xl hover:scale-105 transition-all duration-300 group">
        <div class="relative mb-4">
          <img src="https://covers.openlibrary.org/b/id/8235116-L.jpg" class="rounded-2xl h-56 w-full object-cover">
          <div class="borrowed-badge absolute top-3 right-3 px-3 py-1 rounded-full text-xs font-semibold">
            ❌ Dipinjam
          </div>
        </div>
        
        <h4 class="font-bold text-lg mb-1">Bumi</h4>
        <p class="text-gray-600 text-sm mb-3">Tere Liye</p>
        
        <div class="flex items-center justify-between mb-4">
          <div class="rating-stars">⭐⭐⭐⭐⭐</div>
          <span class="text-sm text-gray-500">4.7 (156 reviews)</span>
        </div>

        <div class="flex gap-2 mb-4">
          <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-xs">Fantasy</span>
          <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">Semua dipinjam</span>
        </div>

        <button class="w-full bg-gray-400 text-white py-3 rounded-xl font-semibold cursor-not-allowed">
          ⏰ Reservasi
        </button>
      </div>

      <!-- Book Card 4 -->
      <div class="book-card p-6 rounded-3xl hover:scale-105 transition-all duration-300 group">
        <div class="relative mb-4">
          <img src="https://covers.openlibrary.org/b/id/12600070-L.jpg" class="rounded-2xl h-56 w-full object-cover">
          <div class="availability-badge absolute top-3 right-3 px-3 py-1 rounded-full text-xs font-semibold">
            ✅ Tersedia
          </div>
        </div>
        
        <h4 class="font-bold text-lg mb-1">Dilan 1990</h4>
        <p class="text-gray-600 text-sm mb-3">Pidi Baiq</p>
        
        <div class="flex items-center justify-between mb-4">
          <div class="rating-stars">⭐⭐⭐⭐</div>
          <span class="text-sm text-gray-500">4.6 (203 reviews)</span>
        </div>

        <div class="flex gap-2 mb-4">
          <span class="bg-pink-100 text-pink-800 px-2 py-1 rounded-full text-xs">Romance</span>
          <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">1 tersedia</span>
        </div>

        <button class="w-full bg-[#0B1957] text-white py-3 rounded-xl font-semibold hover:bg-[#2d4a8a] transition">
          📚 Pinjam Sekarang
        </button>
      </div>

    </div>
  </div>
</section>

<!-- FEATURES -->
<section class="bg-[#F8F3EA] py-20 reveal">
  <div class="max-w-7xl mx-auto px-6">
  <div class="text-center mb-16"> 
    <h3 class="text-3xl font-bold mb-4">Kenapa Pilih Rumah Baca?</h3>
 </div>    <div class="grid md:grid-cols-3 gap-10">
    <div class="bg-white p-8 rounded-3xl shadow-lg text-center hover:scale-105 transition">
        <div class="text-5xl mb-4">🔍</div> 
         <h4 class="font-bold text-xl mb-3">Cari & Pinjam Mudah</h4>       
          <p class="text-gray-600">Cek ketersediaan buku real-time dan pinjam langsung online.</p>     
         </div>      
         <div class="bg-white p-8 rounded-3xl shadow-lg text-center hover:scale-105 transition">        
            <div class="text-5xl mb-4">⭐</div>       
             <h4 class="font-bold text-xl mb-3">Rating & Review</h4>        
             <p class="text-gray-600">Lihat rating dari pembaca lain untuk memilih buku terbaik.</p>      
            </div>      
        <div class="bg-white p-8 rounded-3xl shadow-lg text-center hover:scale-105 transition">       
            <div class="text-5xl mb-4">📱</div>        
            <h4 class="font-bold text-xl mb-3">Bookmark</h4>        
            <p class="text-gray-600">Tandai buku favorit sebagai wishlist sebelum meminjam.</p>      
    </div>

    </div>
  </div>
</section>

<!-- CTA -->
<section class="bg-gradient-to-r from-[#0B1957] to-[#2d4a8a] text-white py-20 text-center reveal">
  <div class="max-w-4xl mx-auto px-6">
    <h3 class="text-4xl font-bold mb-4">
      🚀 Siap Meminjam Buku?
    </h3>

    <p class="text-[#9ECCFA] text-lg mb-8">
      Bergabunglah dengan ribuan member yang sudah merasakan kemudahan meminjam buku di Rumah Baca
    </p>

    <div class="flex flex-col sm:flex-row gap-4 justify-center">
      <a href="{{ route('register') }}" class="bg-white text-[#0B1957] px-8 py-4 rounded-2xl font-bold hover:scale-105 transition">
        📝 Daftar Gratis Sekarang
      </a>
      <a href="#" class="border-2 border-white text-white px-8 py-4 rounded-2xl font-semibold hover:bg-white hover:text-[#0B1957] transition">
        📚 Lihat Katalog Lengkap
      </a>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer class="bg-[#0B1957] text-[#9ECCFA] py-12 reveal">
  <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-4 gap-8">
    
    <div>
      <h4 class="font-bold text-white mb-4">Rumah Baca</h4>
      <p class="text-sm">Platform peminjaman buku modern untuk kemudahan akses literasi.</p>
    </div>

    <div>
      <h5 class="font-semibold text-white mb-3">Layanan</h5>
      <ul class="space-y-2 text-sm">
        <li><a href="#" class="hover:text-white transition">Peminjaman Buku</a></li>
        <li><a href="#" class="hover:text-white transition">Rating & Review</a></li>
        <li><a href="#" class="hover:text-white transition">Reservasi</a></li>
      </ul>
    </div>

    <div>
      <h5 class="font-semibold text-white mb-3">Akun</h5>
      <ul class="space-y-2 text-sm">
        <li><a href="#" class="hover:text-white transition">Pinjaman Saya</a></li>
        <li><a href="#" class="hover:text-white transition">Riwayat</a></li>
        <li><a href="#" class="hover:text-white transition">Wishlist</a></li>
      </ul>
    </div>

    <div>
      <h5 class="font-semibold text-white mb-3">Kontak</h5>
      <ul class="space-y-2 text-sm">
        <li>📧 info@rumahbaca.com</li>
        <li>📞 (021) 123-4567</li>
        <li>📍 Jakarta, Indonesia</li>
      </ul>
    </div>

  </div>

  <div class="max-w-7xl mx-auto px-6 pt-8 mt-8 border-t border-[#2d4a8a] text-center text-sm">
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
