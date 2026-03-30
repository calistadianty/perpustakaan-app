@extends('admin.layout')

@section('page-title', 'Manajemen Peminjaman')

@section('content')
<div class="space-y-6">
    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-2xl font-bold text-gray-900">Daftar Peminjaman</h3>
            <p class="text-gray-600 mt-1">Kelola data peminjaman buku perpustakaan</p>
        </div>
    </div>



    <!-- ACTIVE LOANS (Pending, Disetujui, Dipinjam) -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-blue-100">
        <div class="bg-blue-50 px-6 py-4 border-b border-blue-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="w-2 h-6 bg-blue-600 rounded-full"></span>
                <h3 class="font-bold text-gray-900 text-lg">Peminjaman Aktif</h3>
            </div>
            <!-- Search for Active Loans -->
            <form method="GET" action="{{ route('admin.peminjaman.index') }}" class="w-full sm:w-auto">
                <div class="relative">
                    <input type="text" name="search_active" value="{{ request('search_active') }}" placeholder="Cari Peminjaman Aktif..." class="w-full sm:w-64 pl-10 pr-4 py-2 border border-blue-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    @if(request('search_return'))
                        <input type="hidden" name="search_return" value="{{ request('search_return') }}">
                    @endif
                    @if(request('return_page'))
                        <input type="hidden" name="return_page" value="{{ request('return_page') }}">
                    @endif
                </div>
            </form>
        </div>
        
        @if($activePeminjaman->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 text-gray-600 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Peminjam</th>
                        <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Buku & Tanggal</th>
                        <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Alamat & Keterangan</th>
                        <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-sm font-bold uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($activePeminjaman as $item)
                    @php
                        $isOverdue = $item->status == 'dipinjam' && $item->tanggal_batas_kembali && $item->tanggal_batas_kembali->isPast();
                        $overdueDays = $isOverdue ? (int) now()->diffInDays($item->tanggal_batas_kembali) : 0;
                    @endphp
                    <tr class="{{ $isOverdue ? 'bg-red-50 hover:bg-red-100/70' : 'hover:bg-blue-50/50 bg-white' }} transition">
                        <td class="px-6 py-4 align-top">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-700 font-bold">
                                    {{ strtoupper(substr($item->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">{{ $item->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $item->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 align-top">
                            <p class="font-bold text-gray-900 mb-1">{{ $item->book->judul }}</p>
                            <div class="space-y-1">
                                <p class="text-xs text-gray-600 flex items-center gap-1">
                                     <span class="w-4 inline-block text-center"><svg class="w-4 h-4 inline text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></span> 
                                    Pinjam: <span class="font-medium">{{ $item->tanggal_pinjam->format('d/m/Y') }}</span>
                                </p>
                                <p class="text-xs {{ $isOverdue ? 'text-red-600 font-bold' : 'text-red-600' }} flex items-center gap-1">
                                     <span class="w-4 inline-block text-center"><svg class="w-4 h-4 inline text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>
                                    Batas: <span class="font-medium">{{ $item->tanggal_batas_kembali ? $item->tanggal_batas_kembali->format('d/m/Y') : '-' }}</span>
                                </p>
                                @if($isOverdue)
                                    <p class="text-xs text-red-500 font-bold mt-1 flex items-center gap-1">
                                         <span class="w-4 inline-block text-center"><svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg></span>
                                        Terlambat {{ $overdueDays }} hari!
                                    </p>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 align-top max-w-xs">
                            <div class="text-sm text-gray-700 mb-1">
                                <span class="font-semibold text-gray-500 text-xs block uppercase">Alamat:</span>
                                {{ $item->user->alamat ?? '-' }}
                            </div>
                            @if($item->keterangan)
                            <div class="text-xs text-gray-500 italic mt-1 bg-gray-50 p-2 rounded border border-gray-100">
                                "{{ $item->keterangan }}"
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 align-top">
                            @if($item->status == 'pending')
                                <span class="inline-flex items-center px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold border border-yellow-200 shadow-sm animate-pulse">Pending</span>
                            @elseif($item->status == 'dipinjam')
                                @if($isOverdue)
                                    <span class="inline-flex items-center px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-bold border border-red-300 shadow-sm">
                                        <span class="w-2 h-2 bg-red-500 rounded-full mr-1.5"></span>
                                        TERLAMBAT
                                    </span>
                                    <p class="text-[10px] text-red-600 mt-1 font-bold">{{ $overdueDays }} hari melewati batas</p>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-bold border border-purple-200 shadow-sm">Dipinjam</span>
                                    <p class="text-[10px] text-purple-600 mt-1 font-medium">Sedang Dibawa</p>
                                @endif
                            @endif
                        </td>
                        <td class="px-6 py-4 align-top text-center">
                            <div class="flex flex-col gap-2">
                                
                                @if($item->status == 'pending')
                                    <form action="{{ route('admin.peminjaman.approve', $item->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button class="w-full px-4 py-2 bg-green-600 text-white rounded-lg text-xs font-bold hover:bg-green-700 transition shadow-sm flex items-center justify-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Terima
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.peminjaman.reject', $item->id) }}" method="POST" onsubmit="return confirm('Tolak peminjaman ini?')">
                                        @csrf @method('PATCH')
                                        <button class="w-full px-4 py-2 bg-red-100 text-red-600 rounded-lg text-xs font-bold hover:bg-red-200 transition shadow-sm flex items-center justify-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Tolak
                                        </button>
                                    </form>

                                @endif



                                <!-- View Proof Button REMOVED as per request -->
                                <!-- 
                                <button onclick="document.getElementById('receipt-modal-{{ $item->id }}').classList.remove('hidden')" class="w-full px-4 py-2 bg-blue-50 text-blue-700 rounded-lg text-xs font-bold hover:bg-blue-100 transition border border-blue-200 flex items-center justify-center gap-1 mt-2">
                                    <span>📄</span> Lihat Bukti
                                </button>
                                -->

                                <!-- Receipt Modal -->
                                <div id="receipt-modal-{{ $item->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" style="text-align: left;">
                                    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                                        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="document.getElementById('receipt-modal-{{ $item->id }}').classList.add('hidden')"></div>
                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                                        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm w-full relative">
                                            <button onclick="document.getElementById('receipt-modal-{{ $item->id }}').classList.add('hidden')" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                            <div class="p-8">
                                                <div class="text-center mb-6 border-b-2 border-dashed border-gray-100 pb-6">
                                                    <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-600 text-white rounded-xl mb-3">
                                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                                    </div>
                                                    <h1 class="text-xl font-bold text-gray-900 tracking-tight">RUMAH BACA</h1>
                                                    <p class="text-gray-400 text-xs uppercase tracking-widest mt-1">Bukti Peminjaman</p>
                                                </div>
                                                <div class="space-y-3 text-sm">
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-500 text-xs">Peminjam</span>
                                                        <span class="font-bold text-gray-900">{{ $item->user->name }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-500 text-xs">Tanggal Pinjam</span>
                                                        <span class="font-bold text-gray-900">{{ $item->tanggal_pinjam->format('d/m/Y') }}</span>
                                                    </div>
                                                    <div class="pt-3 border-t border-dashed border-gray-100 mt-3">
                                                        <p class="text-xs text-gray-500 mb-1">Judul Buku</p>
                                                        <p class="font-bold text-gray-900 leading-tight">{{ $item->book->judul }}</p>
                                                    </div>
                                                    <div class="pt-4 text-center">
                                                        <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide border bg-gray-50 text-gray-700 border-gray-200">
                                                            {{ ucfirst($item->status) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t">
            {{ $activePeminjaman->appends(['return_page' => request('return_page'), 'search_active' => request('search_active'), 'search_return' => request('search_return')])->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <p class="text-gray-500">Tidak ada peminjaman aktif.</p>
        </div>
        @endif
    </div>

    <!-- RETURN REQUESTS -->
    <div class="mt-12 bg-white rounded-xl shadow-sm overflow-hidden border border-yellow-100">
        <div class="bg-yellow-50 px-6 py-4 border-b border-yellow-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="w-2 h-6 bg-yellow-500 rounded-full"></span>
                <h3 class="font-bold text-gray-900 text-lg">Pengajuan Pengembalian</h3>
            </div>
            <!-- Search for Return Requests -->
            <form method="GET" action="{{ route('admin.peminjaman.index') }}" class="w-full sm:w-auto">
                <div class="relative">
                    <input type="text" name="search_return" value="{{ request('search_return') }}" placeholder="Cari Pengajuan..." class="w-full sm:w-64 pl-10 pr-4 py-2 border border-yellow-200 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    @if(request('search_active'))
                        <input type="hidden" name="search_active" value="{{ request('search_active') }}">
                    @endif
                    @if(request('active_page'))
                        <input type="hidden" name="active_page" value="{{ request('active_page') }}">
                    @endif
                </div>
            </form>
        </div>
        
        @if($returnRequests->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 text-gray-600 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Peminjam</th>
                        <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Buku & Tanggal</th>
                        <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-sm font-bold uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($returnRequests as $item)
                    <tr class="hover:bg-yellow-50/50 bg-white transition">
                        <td class="px-6 py-4 align-top">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center text-yellow-700 font-bold">
                                    {{ strtoupper(substr($item->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">{{ $item->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $item->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 align-top">
                            <p class="font-bold text-gray-900 mb-1">{{ $item->book->judul }}</p>
                            <div class="space-y-1">
                                <p class="text-xs text-gray-600 flex items-center gap-1">
                                     <span class="w-4 inline-block text-center"><svg class="w-4 h-4 inline text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></span> 
                                    Pinjam: <span class="font-medium">{{ $item->tanggal_pinjam->format('d/m/Y') }}</span>
                                </p>
                                <p class="text-xs text-gray-600 flex items-center gap-1">
                                     <span class="w-4 inline-block text-center"><svg class="w-4 h-4 inline text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>
                                    Batas: <span class="font-medium">{{ $item->tanggal_batas_kembali ? $item->tanggal_batas_kembali->format('d/m/Y') : '-' }}</span>
                                </p>
                                <p class="text-xs text-blue-600 font-bold flex items-center gap-1 bg-blue-50 py-0.5 px-2 mt-1 rounded inline-flex">
                                     <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                    Dikembalikan: {{ $item->updated_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-4 align-top">
                            <span class="inline-flex items-center px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold border border-yellow-200 shadow-sm animate-pulse">Menunggu Konfirmasi</span>
                        </td>
                        <td class="px-6 py-4 align-top text-center">
                            <div class="flex flex-col gap-2">
                                <form action="{{ route('admin.peminjaman.approve-kembali', $item->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button class="w-full px-4 py-2 bg-green-600 text-white rounded-lg text-xs font-bold hover:bg-green-700 transition shadow-sm flex items-center justify-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Setujui
                                    </button>
                                </form>
                                <form action="{{ route('admin.peminjaman.reject-kembali', $item->id) }}" method="POST" onsubmit="return confirm('Tolak pengembalian ini?')">
                                    @csrf @method('PATCH')
                                    <button class="w-full px-4 py-2 bg-red-100 text-red-600 rounded-lg text-xs font-bold hover:bg-red-200 transition shadow-sm flex items-center justify-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg> Tolak
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t">
            {{ $returnRequests->appends(['active_page' => request('active_page'), 'search_active' => request('search_active'), 'search_return' => request('search_return')])->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <p class="text-gray-500">Tidak ada pengajuan pengembalian.</p>
        </div>
        @endif
    </div>

</div>
@endsection