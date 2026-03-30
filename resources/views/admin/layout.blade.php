<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Panel - {{ config('app.name', 'Rumah Baca') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .sidebar-link {
            transition: all 0.2s ease;
        }
        .sidebar-link:hover {
            background-color: #F3F4F6;
            color: #1F2937;
        }
        .sidebar-link.active {
            background-color: #EEF2FF;
            color: #4F46E5;
            border-left: 3px solid #4F46E5;
        }
        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        .submenu.open {
            max-height: 200px;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100 text-gray-900">
    <div class="min-h-screen flex">
        
        <!-- Sidebar -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 text-gray-600 transform transition-transform duration-300 lg:translate-x-0 -translate-x-full overflow-y-auto max-h-screen">
            
            <!-- Logo -->
            <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-100">
                <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <div>
                    <h1 class="font-bold text-lg text-gray-900">Rumah Baca</h1>
                    <p class="text-xs text-gray-500">Admin Panel</p>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="mt-6 px-4">
                <div class="space-y-2">
                    
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Dashboard
                    </a>

                    <!-- Manajemen Buku -->
<div x-data="{ 
    open: {{ request()->routeIs('admin.books.*') || request()->routeIs('admin.categories.*') || request()->routeIs('admin.book-approval.*') ? 'true' : 'false' }} 
}">

    <button @click="open = !open" 
        class="sidebar-link w-full flex items-center justify-between gap-3 px-4 py-3 rounded-lg text-sm font-medium
        {{ request()->routeIs('admin.books.*') || request()->routeIs('admin.categories.*') || request()->routeIs('admin.book-approval.*') ? 'active' : '' }}">

        <div class="flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13
                         C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13
                         C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13
                         C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            Manajemen Buku
        </div>

        <svg class="w-4 h-4 transition-transform"
             :class="{ 'rotate-180': open }"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <div x-show="open" x-collapse class="pl-12 mt-1 space-y-1">
        <a href="{{ route('admin.books.index') }}"
           class="block px-4 py-2 text-sm rounded-lg hover:bg-gray-50
           {{ request()->routeIs('admin.books.*') ? 'text-blue-600 font-bold' : 'text-gray-600' }}">
            Daftar Buku
        </a>

        <a href="{{ route('admin.categories.index') }}"
           class="block px-4 py-2 text-sm rounded-lg hover:bg-gray-50
           {{ request()->routeIs('admin.categories.*') ? 'text-blue-600 font-bold' : 'text-gray-600' }}">
            Kategori
        </a>


    </div>
</div>
  <!-- Manajemen Peminjaman -->
                    <div x-data="{ 
                        open: {{ request()->routeIs('admin.peminjaman.*') ? 'true' : 'false' }} 
                    }">

                        <button @click="open = !open" 
                            class="sidebar-link w-full flex items-center justify-between gap-3 px-4 py-3 rounded-lg text-sm font-medium
                            {{ request()->routeIs('admin.peminjaman.*') ? 'active' : '' }}">

                            <div class="flex items-center gap-3 whitespace-nowrap">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                </svg>
                                Peminjaman
                            </div>

                            <svg class="w-4 h-4 transition-transform flex-shrink-0"
                                 :class="{ 'rotate-180': open }"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="open" x-collapse class="pl-12 mt-1 space-y-1">
                            <a href="{{ route('admin.peminjaman.index') }}"
                               class="block px-4 py-2 text-sm rounded-lg hover:bg-gray-50
                               {{ request()->routeIs('admin.peminjaman.index') ? 'text-blue-600 font-bold' : 'text-gray-600' }}">
                                Sedang Dipinjam
                            </a>

                            <a href="{{ route('admin.peminjaman.history') }}"
                               class="block px-4 py-2 text-sm rounded-lg hover:bg-gray-50
                               {{ request()->routeIs('admin.peminjaman.history') ? 'text-blue-600 font-bold' : 'text-gray-600' }}">
                                Riwayat
                            </a>
                        </div>
                    </div>

                    <!-- Manajemen Akun -->
                    <div x-data="{ 
                        open: {{ request()->routeIs('admin.petugas.*') || request()->routeIs('admin.pembaca.*') ? 'true' : 'false' }} 
                    }">

                        <button @click="open = !open" 
                            class="sidebar-link w-full flex items-center justify-between gap-3 px-4 py-3 rounded-lg text-sm font-medium
                            {{ request()->routeIs('admin.petugas.*') || request()->routeIs('admin.pembaca.*') ? 'active' : '' }}">

                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1
                                             zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0"/>
                                </svg>
                                Manajemen Akun
                            </div>

                            <svg class="w-4 h-4 transition-transform"
                                 :class="{ 'rotate-180': open }"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="open" x-collapse class="pl-12 mt-1 space-y-1">
                            <a href="{{ route('admin.petugas.index') }}?role=petugas"
                                class="block px-4 py-2 text-sm rounded-lg hover:bg-gray-50
                                {{ request()->is('admin/petugas*') && request()->get('role') == 'petugas'
                                     ? 'text-blue-600 font-bold' : 'text-gray-600' }}">
                                Petugas
                            </a>

                            <a href="{{ route('admin.pembaca.index') }}"
                               class="block px-4 py-2 text-sm rounded-lg hover:bg-gray-100
                               {{ request()->routeIs('admin.pembaca.*') ? 'text-indigo-600' : 'text-gray-600' }}">
                                Pengguna
                            </a>
                        </div>
                    </div>

                    <!-- Laporan -->
                    <a href="{{ route('admin.laporan.index') }}" 
                       class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium
                       {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Laporan
                    </a>

                </div>
            </nav>
            <!-- User Info at Bottom -->
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200 bg-gray-50">
                <div class="flex items-center gap-3 px-2">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <span class="text-blue-700 font-bold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate text-gray-900">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-blue-600">Administrator</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-2 hover:bg-gray-200 rounded-lg transition" title="Logout">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Overlay for mobile -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 lg:hidden hidden" onclick="toggleSidebar()"></div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-h-screen lg:ml-64">
            
            <!-- Top Header -->
            <header class="bg-white shadow-sm sticky top-0 z-30">
                <div class="flex items-center justify-between px-6 py-4">
                    <!-- Mobile Menu Button -->
                    <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg hover:bg-gray-100">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <!-- Page Title -->
                    <h2 class="text-xl font-bold text-gray-900">
                        @yield('page-title', 'Dashboard')
                    </h2>

                    <!-- Right Side -->
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-500">{{ now()->format('d M Y') }}</span>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6">
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-800 rounded-xl">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-100 border border-red-300 text-red-800 rounded-xl">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t px-6 py-4">
                <p class="text-center text-sm text-gray-500">
                    © {{ date('Y') }} Rumah Baca. Admin Panel.
                </p>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#2563EB',
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#EF4444',
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                html: '<ul style="text-align:left">@foreach($errors->all() as $error)<li>• {{ $error }}</li>@endforeach</ul>',
                confirmButtonColor: '#EF4444',
            });
        @endif
    </script>
</body>
</html>