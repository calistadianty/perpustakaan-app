<header class="bg-white shadow-lg sticky top-0 z-50 {{ isset($class) ? $class : '' }}">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <!-- Log & Brand -->
        <a href="{{ route('home') }}" class="flex items-center gap-3 hover:scale-105 transition-transform">
            <div class="w-10 h-10 bg-blue-900 rounded-xl flex items-center justify-center shadow-lg shadow-blue-900/20">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <h1 class="font-bold text-xl text-blue-900 tracking-tight">Rumah Baca</h1>
        </a>

        <!-- Navigation Menu -->
        <nav class="hidden md:flex gap-8 text-sm font-semibold items-center text-slate-600">
            <a href="{{ route('home') }}" class="hover:text-blue-700 transition-colors {{ request()->routeIs('home') ? 'text-blue-700' : '' }}">Home</a>
            
            @auth
                <a href="{{ route('peminjaman.index') }}" class="hover:text-blue-700 transition-colors {{ request()->routeIs('peminjaman.index') ? 'text-blue-700' : '' }}">Peminjaman</a>
            @endauth
            
            <!-- Buku Dropdown -->
            <div class="relative group">
                <button class="flex items-center gap-1 hover:text-blue-700 transition-colors focus:outline-none {{ request()->routeIs('catalog.*') || request()->routeIs('koleksi.*') ? 'text-blue-700' : '' }}">
                    <span>Buku</span>
                    <svg class="w-4 h-4 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div class="absolute left-0 mt-2 w-48 bg-white rounded-xl shadow-xl py-2 invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-all transform origin-top-right z-50">
                    <a href="{{ route('catalog.index') }}" class="block px-4 py-2 hover:bg-gray-50 text-sm text-slate-700">Katalog Buku</a>
                    @auth
                        <a href="{{ route('koleksi.index') }}" class="block px-4 py-2 hover:bg-gray-50 text-sm text-slate-700">Koleksi Pribadi</a>
                    @endauth
                </div>
            </div>

            <!-- Auth Controls -->
            @auth
            <div class="relative ml-4 group">
                <button class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 pl-1 pr-4 py-1 rounded-full transition-all">
                    @if(auth()->user()->avatar)
                        <img src="{{ Storage::url(auth()->user()->avatar) }}" class="w-8 h-8 rounded-full object-cover border border-white shadow-sm">
                    @else
                        <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <span class="font-semibold text-gray-900 text-sm">{{ Str::limit(auth()->user()->name, 10) }}</span>
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl py-2 invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-all transform origin-top-right z-50 border border-gray-100">
                    <div class="px-4 py-2 border-b border-gray-100">
                        <p class="text-xs text-slate-500">Halo,</p>
                        <p class="font-bold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-gray-50 text-sm text-slate-700">Edit Profil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-red-50 text-red-600 text-sm">Logout</button>
                    </form>
                </div>
            </div>
            @else
            <a href="{{ route('login') }}" class="ml-4 bg-blue-900 text-white px-6 py-2.5 rounded-full font-bold hover:bg-blue-800 transition-all shadow-lg hover:shadow-blue-900/30 hover:-translate-y-0.5">
                Masuk
            </a>
            @endauth
        </nav>
    </div>
</header>
