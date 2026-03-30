@extends('layouts.admin')

@section('page-title', 'Detail Peminjaman')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('admin.peminjaman.index') }}" 
           class="inline-flex items-center gap-2 text-[#E6521F] hover:text-[#1a2a6c] mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar Peminjaman
        </a>
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-2xl font-bold text-[#E6521F]">Detail Peminjaman</h3>
                <p class="text-gray-600 mt-1">ID Peminjaman: #{{ $peminjaman->id }}</p>
            </div>
            
            <!-- Status Badge -->
            @if($peminjaman->status == 'dipinjam')
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-100 text-yellow-700 rounded-xl text-sm font-medium">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    Dipinjam
                </span>
            @else
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-green-100 text-green-700 rounded-xl text-sm font-medium">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Dikembalikan
                </span>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Informasi Pembaca -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h4 class="text-lg font-semibold text-[#E6521F] mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Informasi Pembaca
                </h4>
                <div class="flex items-start gap-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-[#9ECCFA] to-[#6ba3d9] rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold text-xl">
                            {{ strtoupper(substr($peminjaman->user->name, 0, 1)) }}
                        </span>
                    </div>
                    <div class="flex-1">
                        <h5 class="font-semibold text-gray-900 text-lg">{{ $peminjaman->user->name }}</h5>
                        <div class="mt-2 space-y-1 text-sm text-gray-600">
                            <p class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                {{ $peminjaman->user->email }}
                            </p>
                            @if($peminjaman->user->alamat)
                            <p class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $peminjaman->user->alamat }}
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Buku -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h4 class="text-lg font-semibold text-[#E6521F] mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Informasi Buku
                </h4>
                <div class="flex gap-4">
                    @if($peminjaman->book->cover)
                    <img src="{{ asset('storage/' . $peminjaman->book->cover) }}" 
                         alt="{{ $peminjaman->book->judul }}"
                         class="w-24 h-32 object-cover rounded-lg shadow-sm flex-shrink-0">
                    @else
                    <div class="w-24 h-32 bg-gradient-to-br from-[#9ECCFA] to-[#6ba3d9] rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    @endif
                    <div class="flex-1">
                        <h5 class="font-semibold text-gray-900 text-lg mb-2">{{ $peminjaman->book->judul }}</h5>
                        <div class="space-y-1 text-sm text-gray-600">
                            <p><span class="font-medium">Penulis:</span> {{ $peminjaman->book->penulis }}</p>
                            <p><span class="font-medium">Penerbit:</span> {{ $peminjaman->book->penerbit }}</p>
                            <p><span class="font-medium">Tahun Terbit:</span> {{ $peminjaman->book->tahun_terbit }}</p>
                            <p><span class="font-medium">Kategori:</span> 
                                <span class="inline-block px-2 py-1 bg-orange-100 text-orange-800 rounded text-xs">
                                    {{ $peminjaman->book->kategori->nama ?? '-' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Keterangan -->
            @if($peminjaman->keterangan)
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h4 class="text-lg font-semibold text-[#E6521F] mb-3">Keterangan</h4>
                <p class="text-gray-700">{{ $peminjaman->keterangan }}</p>
            </div>
            @endif

        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            
            <!-- Timeline -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h4 class="text-lg font-semibold text-[#E6521F] mb-4">Timeline</h4>
                <div class="space-y-4">
                    
                    <!-- Tanggal Pinjam -->
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Tanggal Pinjam</p>
                            <p class="text-sm text-gray-600">{{ $peminjaman->tanggal_pinjam->format('d F Y') }}</p>
                        </div>
                    </div>

                    <!-- Tanggal Kembali -->
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-10 h-10 {{ $peminjaman->tanggal_kembali ? 'bg-green-100' : 'bg-gray-100' }} rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 {{ $peminjaman->tanggal_kembali ? 'text-green-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Tanggal Kembali</p>
                            <p class="text-sm text-gray-600">
                                {{ $peminjaman->tanggal_kembali ? $peminjaman->tanggal_kembali->format('d F Y') : 'Belum dikembalikan' }}
                            </p>
                        </div>
                    </div>

                    <!-- Petugas -->
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-10 h-10 {{ $peminjaman->petugas ? 'bg-purple-100' : 'bg-gray-100' }} rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 {{ $peminjaman->petugas ? 'text-purple-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Diproses oleh</p>
                            <p class="text-sm text-gray-600">
                                {{ $peminjaman->petugas ? $peminjaman->petugas->name : 'Belum diproses' }}
                            </p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Metadata -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h4 class="text-lg font-semibold text-[#E6521F] mb-4">Metadata</h4>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500">Dibuat pada</p>
                        <p class="font-medium text-gray-900">{{ $peminjaman->created_at->format('d F Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Terakhir diupdate</p>
                        <p class="font-medium text-gray-900">{{ $peminjaman->updated_at->format('d F Y, H:i') }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection