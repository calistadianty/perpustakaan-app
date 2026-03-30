@extends('user.layout')

@section('title', 'Peminjaman Saya')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Peminjaman Saya</h1>
            <p class="text-gray-600 mt-2">Kelola peminjaman buku perpustakaan Anda</p>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex border-b border-gray-200 mb-8">
        <a href="{{ route('peminjaman.index') }}" class="px-6 py-3 text-sm font-medium text-blue-600 border-b-2 border-blue-600 focus:outline-none">
            Status Peminjaman
        </a>
        <a href="{{ route('peminjaman.history') }}" class="px-6 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none transition-colors">
            Riwayat Peminjaman
        </a>
    </div>

    <!-- Peminjaman Aktif Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @if($activePeminjaman->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 text-gray-600 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Buku</th>
                        <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Tanggal Pinjam</th>
                        <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Batas Kembali</th>
                        <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($activePeminjaman as $pinjam)
                    @php
                        $isOverdue = $pinjam->status == 'dipinjam' && $pinjam->tanggal_batas_kembali && $pinjam->tanggal_batas_kembali->isPast();
                        $overdueDays = $isOverdue ? (int) now()->diffInDays($pinjam->tanggal_batas_kembali) : 0;
                    @endphp
                    <tr class="{{ $isOverdue ? 'bg-red-50 hover:bg-red-100/70' : 'hover:bg-blue-50/30' }} transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                @if($pinjam->book->cover)
                                    <img src="{{ Storage::url($pinjam->book->cover) }}" class="w-12 h-16 object-cover rounded shadow-sm" alt="Cover">
                                @else
                                    <div class="w-12 h-16 bg-gray-200 rounded flex items-center justify-center text-xs text-gray-500">No Cover</div>
                                @endif
                                <div>
                                    <p class="font-bold text-gray-900 line-clamp-1">{{ $pinjam->book->judul }}</p>
                                    <p class="text-xs text-gray-500">{{ $pinjam->book->penulis }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $pinjam->tanggal_pinjam->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="{{ $isOverdue ? 'text-red-600 font-bold' : 'text-gray-600' }}">
                                {{ $pinjam->tanggal_batas_kembali ? $pinjam->tanggal_batas_kembali->format('d M Y') : '-' }}
                            </span>
                            @if($isOverdue)
                                <p class="text-xs text-red-500 font-bold mt-1 flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg> Terlambat {{ $overdueDays }} hari</p>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($pinjam->status == 'pending')
                                <span class="inline-flex items-center px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold border border-yellow-200 shadow-sm">
                                    <span class="w-2 h-2 bg-yellow-400 rounded-full mr-1 animate-pulse"></span>
                                    Menunggu
                                </span>
                            @elseif($pinjam->status == 'dipinjam')
                                @if($isOverdue)
                                    <span class="inline-flex items-center px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-bold border border-red-300 shadow-sm">
                                        <span class="w-2 h-2 bg-red-500 rounded-full mr-1.5"></span>
                                        TERLAMBAT
                                    </span>
                                    <p class="text-[10px] text-red-600 mt-1 font-bold">{{ $overdueDays }} hari melewati batas</p>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold border border-green-200 shadow-sm">
                                        Sedang Dipinjam
                                    </span>
                                @endif
                            @elseif($pinjam->status == 'menunggu_pengembalian')
                                <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold border border-blue-200 shadow-sm">
                                    <span class="w-2 h-2 bg-blue-500 rounded-full mr-1.5 animate-pulse"></span>
                                    Menunggu Pengembalian
                                </span>
                            @endif
                        </td>
                         <td class="px-6 py-4 text-sm text-gray-500">
                            <div class="flex flex-col gap-2">
                            @if($pinjam->status == 'dipinjam')
                                <button onclick="document.getElementById('return-modal-{{ $pinjam->id }}').classList.remove('hidden')" class="inline-flex items-center gap-1.5 px-4 py-2 bg-yellow-50 border border-yellow-200 text-yellow-700 text-xs font-bold rounded-lg hover:bg-yellow-100 transition shadow-sm w-max mb-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                                    Kembalikan
                                </button>
                                
                                <!-- Return Modal -->
                                <div id="return-modal-{{ $pinjam->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                                        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" aria-hidden="true" onclick="document.getElementById('return-modal-{{ $pinjam->id }}').classList.add('hidden')"></div>
                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                        
                                        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full relative">
                                            <form action="{{ route('peminjaman.ajukan-kembali', $pinjam->id) }}" method="POST">
                                                @csrf
                                                <div class="p-8">
                                                    <h3 class="text-xl font-bold text-gray-900 mb-4">Pengajuan Pengembalian Buku</h3>
                                                    <p class="text-sm text-gray-600 mb-6">Anda akan mengajukan pengembalian untuk buku berikut:</p>
                                                    <div class="bg-gray-50 p-4 rounded-xl space-y-3 text-sm text-gray-700 mb-6">
                                                        <div class="flex justify-between border-b pb-2">
                                                            <span class="text-gray-500">Judul:</span>
                                                            <span class="font-bold text-right ml-4">{{ $pinjam->book->judul }}</span>
                                                        </div>
                                                        <div class="flex justify-between border-b pb-2">
                                                            <span class="text-gray-500">Penulis:</span>
                                                            <span class="font-bold text-right ml-4">{{ $pinjam->book->penulis }}</span>
                                                        </div>
                                                        <div class="flex justify-between border-b pb-2">
                                                            <span class="text-gray-500">Penerbit:</span>
                                                            <span class="font-bold text-right ml-4">{{ $pinjam->book->penerbit }}</span>
                                                        </div>
                                                        <div class="flex justify-between border-b pb-2">
                                                            <span class="text-gray-500">Tanggal Pinjam:</span>
                                                            <span class="font-bold text-right">{{ $pinjam->tanggal_pinjam->format('d M Y') }}</span>
                                                        </div>
                                                        <div class="flex justify-between pt-1">
                                                            <span class="text-gray-500">Batas Kembali:</span>
                                                            <span class="font-bold text-red-600 text-right">{{ $pinjam->tanggal_batas_kembali ? $pinjam->tanggal_batas_kembali->format('d M Y') : '-' }}</span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="flex justify-end gap-3 mt-6">
                                                        <button type="button" onclick="document.getElementById('return-modal-{{ $pinjam->id }}').classList.add('hidden')" class="px-5 py-2.5 text-sm font-bold text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-xl transition cursor-pointer">Batal</button>
                                                        <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white text-sm font-bold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-600/30 cursor-pointer">Ajukan Pengembalian</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($pinjam->status == 'dipinjam' || $pinjam->status == 'dikembalikan' || $pinjam->status == 'menunggu_pengembalian')
                            <button onclick="document.getElementById('receipt-modal-{{ $pinjam->id }}').classList.remove('hidden')" class="inline-flex items-center gap-1 text-xs font-bold text-blue-700 hover:text-blue-900 hover:underline">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Lihat Bukti
                            </button>

                            <!-- Receipt Modal -->
                            <div id="receipt-modal-{{ $pinjam->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                                    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" aria-hidden="true" onclick="document.getElementById('receipt-modal-{{ $pinjam->id }}').classList.add('hidden')"></div>
                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                    
                                    <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm w-full relative">
                                        
                                        <!-- Close Button -->
                                        <button onclick="document.getElementById('receipt-modal-{{ $pinjam->id }}').classList.add('hidden')" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>

                                        <div class="p-8">
                                            <!-- Receipt Header -->
                                            <div class="text-center mb-6 border-b-2 border-dashed border-gray-100 pb-6">
                                                <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-900 text-white rounded-xl mb-3">
                                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                                </div>
                                                <h1 class="text-xl font-bold text-gray-900 tracking-tight">RUMAH BACA</h1>
                                                <p class="text-gray-400 text-xs uppercase tracking-widest mt-1">Bukti Peminjaman</p>
                                            </div>

                                            <!-- Details -->
                                            <div class="space-y-3 text-sm">
                                                <div class="flex justify-between">
                                                    <span class="text-gray-500 text-xs">Kode Peminjaman</span>
                                                    <span class="font-mono font-bold text-gray-900 bg-gray-100 px-2 py-0.5 rounded">#{{ str_pad($pinjam->id, 6, '0', STR_PAD_LEFT) }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-gray-500 text-xs">Nama Peminjam</span>
                                                    <span class="font-bold text-gray-900">{{ $pinjam->user->name }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-gray-500 text-xs">Tanggal Pinjam</span>
                                                    <span class="font-bold text-gray-900">{{ $pinjam->tanggal_pinjam->format('d M Y') }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-gray-500 text-xs">Batas Kembali</span>
                                                    <span class="font-bold text-red-600">{{ $pinjam->tanggal_batas_kembali->format('d M Y') }}</span>
                                                </div>
                                                
                                                <div class="pt-3 border-t border-dashed border-gray-100 mt-3">
                                                    <p class="text-xs text-gray-500 mb-1">Judul Buku</p>
                                                    <p class="font-bold text-blue-900 leading-tight">{{ $pinjam->book->judul }}</p>
                                                </div>
                                                
                                                <!-- Status -->
                                                <div class="pt-4 text-center">
                                                    <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide border
                                                        {{ $pinjam->status == 'dipinjam' ? 'bg-purple-50 text-purple-700 border-purple-200' : '' }}
                                                        {{ $pinjam->status == 'dikembalikan' ? 'bg-green-50 text-green-700 border-green-200' : '' }}
                                                    ">
                                                        {{ ucfirst($pinjam->status) }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Footer -->
                                            <div class="mt-6 pt-4 border-t border-gray-100 flex flex-col gap-3">
                                                <a href="{{ route('peminjaman.export-receipt', $pinjam->id) }}" target="_blank" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-50 text-blue-700 font-bold text-sm rounded-xl hover:bg-blue-100 transition shadow-sm border border-blue-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    Simpan PDF / Cetak
                                                </a>
                                                <p class="text-[10px] text-gray-400 text-center">Tunjukkan bukti ini kepada petugas.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @else
                            <span class="text-gray-300 text-xs">-</span>
                            @endif
                            </div>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $activePeminjaman->links() }}
        </div>
        @else
        <div class="p-12 text-center">
            <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-4"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg></div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Belum ada peminjaman aktif</h3>
            <p class="text-gray-500 mb-6">Mulai jelajahi katalog buku kami dan pinjam buku favoritmu!</p>
            <a href="{{ route('catalog.index') }}" class="inline-flex items-center px-6 py-3 bg-blue-900 text-white font-bold rounded-xl hover:bg-blue-800 transition shadow-lg shadow-blue-900/20">
                Jelajahi Katalog
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
