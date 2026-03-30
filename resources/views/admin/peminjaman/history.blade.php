@extends('admin.layout')

@section('page-title', 'Riwayat Peminjaman')

@section('content')
<div class="space-y-6">
    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-2xl font-bold text-gray-900">Riwayat Peminjaman</h3>
            <p class="text-gray-600 mt-1">Data peminjaman yang telah selesai (dikembalikan/ditolak)</p>
        </div>
    </div>

    <!-- Search Section -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form method="GET" action="{{ route('admin.peminjaman.history') }}">
            <div class="flex gap-2">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Cari nama pembaca atau judul buku..."
                       class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2 font-medium shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Cari
                </button>
            </div>
        </form>
    </div>

    <!-- HISTORY LOANS (Dikembalikan, Ditolak) -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex items-center gap-2">
            <span class="w-2 h-6 bg-gray-400 rounded-full"></span>
            <h3 class="font-bold text-gray-700 text-lg">Riwayat Peminjaman (Selesai)</h3>
        </div>
        
        @if($historyPeminjaman->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 text-gray-500 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Peminjam</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Buku</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-bold uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($historyPeminjaman as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3">
                            <span class="font-medium text-gray-900">{{ $item->user->name }}</span>
                        </td>
                        <td class="px-6 py-3 text-gray-600">{{ $item->book->judul }}</td>
                        <td class="px-6 py-3 text-gray-500 text-sm">
                            {{ $item->tanggal_pinjam->format('d/m/Y') }} 
                            <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            {{ $item->tanggal_kembali ? $item->tanggal_kembali->format('d/m/Y') : ($item->tanggal_batas_kembali ? $item->tanggal_batas_kembali->format('d/m/Y') : '-') }}
                        </td>
                        <td class="px-6 py-3">
                            @if($item->status == 'dikembalikan')
                                <span class="px-2 py-0.5 bg-green-100 text-green-800 rounded text-xs font-bold">Dikembalikan</span>
                            @else
                                <span class="px-2 py-0.5 bg-red-100 text-red-800 rounded text-xs font-bold">Ditolak</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-center">
                            @if($item->status == 'dikembalikan')
                                <button onclick="document.getElementById('receipt-modal-{{ $item->id }}').classList.remove('hidden')" class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 border border-blue-100 text-blue-700 text-xs font-bold rounded-lg hover:bg-blue-100 transition shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    Lihat Bukti
                                </button>

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
                                                <!-- Receipt Header -->
                                                <div class="text-center mb-6 border-b-2 border-dashed border-gray-100 pb-6">
                                                    <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-600 text-white rounded-xl mb-3">
                                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                                    </div>
                                                    <h1 class="text-xl font-bold text-gray-900 tracking-tight">RUMAH BACA</h1>
                                                    <p class="text-gray-400 text-xs uppercase tracking-widest mt-1">Bukti Peminjaman</p>
                                                </div>
                                                <!-- Details -->
                                                <div class="space-y-3 text-sm">
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-500 text-xs">Kode Peminjaman</span>
                                                        <span class="font-mono font-bold text-gray-900 bg-gray-100 px-2 py-0.5 rounded">#{{ str_pad($item->id, 6, '0', STR_PAD_LEFT) }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-500 text-xs">Nama Peminjam</span>
                                                        <span class="font-bold text-gray-900">{{ $item->user->name }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-500 text-xs">Tanggal Pinjam</span>
                                                        <span class="font-bold text-gray-900">{{ $item->tanggal_pinjam->format('d M Y') }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-500 text-xs">Tanggal Kembali</span>
                                                        <span class="font-bold text-green-600">{{ $item->tanggal_kembali ? $item->tanggal_kembali->format('d M Y') : '-' }}</span>
                                                    </div>
                                                    <div class="pt-3 border-t border-dashed border-gray-100 mt-3">
                                                        <p class="text-xs text-gray-500 mb-1">Judul Buku</p>
                                                        <p class="font-bold text-gray-900 leading-tight">{{ $item->book->judul }}</p>
                                                    </div>
                                                    <!-- Status -->
                                                    <div class="pt-4 text-center">
                                                        <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide border bg-green-50 text-green-700 border-green-200">
                                                            Dikembalikan
                                                        </span>
                                                    </div>
                                                </div>
                                                <!-- Footer -->
                                                <div class="mt-6 pt-4 border-t border-gray-100 text-center">
                                                    <p class="text-[10px] text-gray-400">Arsip Peminjaman. Terima kasih.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t">
            {{ $historyPeminjaman->appends(['search' => request('search'), 'user_id' => request('user_id')])->links() }}
        </div>
        @else
        <div class="text-center py-8">
            <p class="text-gray-400 text-sm">Belum ada riwayat peminjaman.</p>
        </div>
        @endif
    </div>

</div>
@endsection
