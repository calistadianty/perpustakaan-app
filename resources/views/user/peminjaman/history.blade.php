@extends('user.layout')

@section('title', 'Riwayat Peminjaman')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Riwayat Peminjaman</h1>
            <p class="text-gray-600 mt-2">Arsip peminjaman buku yang telah selesai.</p>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex border-b border-gray-200 mb-8">
        <a href="{{ route('peminjaman.index') }}" class="px-6 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none transition-colors">
            Status Peminjaman
        </a>
        <a href="{{ route('peminjaman.history') }}" class="px-6 py-3 text-sm font-medium text-blue-600 border-b-2 border-blue-600 focus:outline-none">
            Riwayat Peminjaman
        </a>
    </div>

    <!-- Riwayat Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @if($historyPeminjaman->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 text-gray-600 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Buku</th>
                        <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Tanggal Pinjam</th>
                        <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Tanggal Kembali</th>
                        <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($historyPeminjaman as $history)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                @if($history->book->cover)
                                    <img src="{{ Storage::url($history->book->cover) }}" class="w-12 h-16 object-cover rounded shadow-sm" alt="Cover">
                                @else
                                    <div class="w-12 h-16 bg-gray-200 rounded flex items-center justify-center text-xs text-gray-500">No Cover</div>
                                @endif
                                <div>
                                    <p class="font-bold text-gray-900 line-clamp-1">{{ $history->book->judul }}</p>
                                    <p class="text-xs text-gray-500">{{ $history->book->penulis }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $history->tanggal_pinjam->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $history->tanggal_kembali ? $history->tanggal_kembali->format('d M Y') : ($history->tanggal_batas_kembali ? $history->tanggal_batas_kembali->format('d M Y') : '-') }}
                        </td>
                        <td class="px-6 py-4">
                            @if($history->status == 'dikembalikan')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                    Dikembalikan
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                    Ditolak
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-2">
                                @if($history->status == 'dikembalikan')
                                    <a href="{{ route('catalog.show', $history->book->id) }}#review-section" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-bold rounded-lg hover:bg-gray-50 hover:border-blue-300 hover:text-blue-600 transition shadow-sm w-full justify-center">
                                        <span class="text-yellow-400">★</span> Ulas Buku
                                    </a>
                                @endif

                                <!-- View Proof Button (only for dikembalikan) -->
                                @if($history->status == 'dikembalikan')
                                <button onclick="document.getElementById('receipt-modal-{{ $history->id }}').classList.remove('hidden')" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-50 border border-blue-100 text-blue-700 text-sm font-bold rounded-lg hover:bg-blue-100 transition shadow-sm w-full justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    Lihat Bukti
                                </button>
                                @endif
                            </div>

                            @if($history->status == 'dikembalikan')
                            <!-- Receipt Modal -->
                            <div id="receipt-modal-{{ $history->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                                    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" aria-hidden="true" onclick="document.getElementById('receipt-modal-{{ $history->id }}').classList.add('hidden')"></div>
                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                    
                                    <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm w-full relative">
                                        
                                        <!-- Close Button -->
                                        <button onclick="document.getElementById('receipt-modal-{{ $history->id }}').classList.add('hidden')" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
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
                                                    <span class="font-mono font-bold text-gray-900 bg-gray-100 px-2 py-0.5 rounded">#{{ str_pad($history->id, 6, '0', STR_PAD_LEFT) }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-gray-500 text-xs">Nama Peminjam</span>
                                                    <span class="font-bold text-gray-900">{{ $history->user->name }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-gray-500 text-xs">Tanggal Pinjam</span>
                                                    <span class="font-bold text-gray-900">{{ $history->tanggal_pinjam->format('d M Y') }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-gray-500 text-xs">Tanggal Kembali</span>
                                                    <span class="font-bold text-green-600">{{ $history->tanggal_kembali ? $history->tanggal_kembali->format('d M Y') : '-' }}</span>
                                                </div>
                                                
                                                <div class="pt-3 border-t border-dashed border-gray-100 mt-3">
                                                    <p class="text-xs text-gray-500 mb-1">Judul Buku</p>
                                                    <p class="font-bold text-blue-900 leading-tight">{{ $history->book->judul }}</p>
                                                </div>
                                                
                                                <!-- Status -->
                                                <div class="pt-4 text-center">
                                                    <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide border
                                                        {{ $history->status == 'dikembalikan' ? 'bg-green-50 text-green-700 border-green-200' : '' }}
                                                        {{ $history->status == 'ditolak' ? 'bg-red-50 text-red-700 border-red-200' : '' }}
                                                    ">
                                                        {{ ucfirst($history->status) }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Footer -->
                                            <div class="mt-6 pt-4 border-t border-gray-100 flex flex-col gap-3">
                                                <a href="{{ route('peminjaman.export-receipt', $history->id) }}" target="_blank" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-50 text-blue-700 font-bold text-sm rounded-xl hover:bg-blue-100 transition shadow-sm border border-blue-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    Simpan PDF / Cetak
                                                </a>
                                                <p class="text-[10px] text-gray-400 text-center">Arsip Peminjaman. Terima kasih.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $historyPeminjaman->links() }}
        </div>
        @else
        <div class="p-12 text-center">
            <p class="text-gray-500">Belum ada riwayat peminjaman.</p>
        </div>
        @endif
    </div>
</div>
@endsection
