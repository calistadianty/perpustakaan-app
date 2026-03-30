@extends('petugas.layout')

@section('page-title', 'Detail Peminjaman')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <div class="mb-6">
        <a href="{{ route('petugas.peminjaman.index') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-blue-600 mb-4 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">Detail Peminjaman</h1>
            
            <div class="flex gap-2">
                @if($peminjaman->status == 'dipinjam')
                    <form action="{{ route('petugas.peminjaman.update', $peminjaman) }}" method="POST" onsubmit="return confirm('Konfirmasi pengembalian buku?')">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="kembali">
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-green-700 transition shadow-sm">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Tandai Kembali
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        <!-- Info Peminjaman -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
            <div>
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Informasi Peminjam</h3>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-700 font-bold text-lg">
                        {{ substr($peminjaman->user->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-bold text-gray-900">{{ $peminjaman->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $peminjaman->user->email }}</p>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Informasi Buku</h3>
                <div class="flex gap-4">
                    @if($peminjaman->book->cover)
                        <img src="{{ Storage::url($peminjaman->book->cover) }}" class="w-16 h-24 object-cover rounded-lg bg-gray-100">
                    @else
                        <div class="w-16 h-24 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 text-xs text-center p-1">No Cover</div>
                    @endif
                    <div>
                        <p class="font-bold text-gray-900">{{ $peminjaman->book->judul }}</p>
                        <p class="text-sm text-gray-500">{{ $peminjaman->book->penulis }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $peminjaman->book->penerbit }} ({{ $peminjaman->book->tahun_terbit }})</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status & Dates -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
            <div>
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Status Peminjaman</h3>
                @if($peminjaman->status == 'dipinjam')
                    <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-xl flex items-center gap-3">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div>
                            <p class="font-bold">Sedang Dipinjam</p>
                            <p class="text-xs opacity-80">Jatuh tempo pada {{ $peminjaman->tanggal_kembali_seharusnya }}</p>
                        </div>
                    </div>
                @elseif($peminjaman->status == 'kembali')
                    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl flex items-center gap-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div>
                            <p class="font-bold">Sudah Dikembalikan</p>
                            <p class="text-xs opacity-80">Dikembalikan pada {{ $peminjaman->tanggal_kembali->format('d M Y') }}</p>
                        </div>
                    </div>
                @else
                    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl flex items-center gap-3">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <div>
                            <p class="font-bold">Terlambat</p>
                            <p class="text-xs opacity-80">Belum dikembalikan</p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="pt-6 border-t border-gray-100 grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-500 mb-1">Tanggal Pinjam</p>
                    <p class="font-semibold text-gray-900">{{ $peminjaman->tanggal_pinjam->format('d F Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Dikembalikan</p>
                    <p class="font-semibold text-gray-900">{{ $peminjaman->tanggal_kembali ? $peminjaman->tanggal_kembali->format('d F Y') : '-' }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-xs text-gray-500 mb-1">Petugas yang melayani</p>
                    <p class="font-semibold text-gray-900">{{ $peminjaman->petugas ? $peminjaman->petugas->name : '-' }}</p>
                </div>
                @if($peminjaman->keterangan)
                    <div class="col-span-2">
                         <p class="text-xs text-gray-500 mb-1">Keterangan</p>
                         <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded-lg">{{ $peminjaman->keterangan }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
