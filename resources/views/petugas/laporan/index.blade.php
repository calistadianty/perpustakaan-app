@extends('petugas.layout')

@section('page-title', 'Laporan Perpustakaan')

@section('content')
<div class="space-y-8">

    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2"><svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg> Laporan Perpustakaan</h1>
        <p class="text-gray-500 mt-1">Ringkasan data dan generate laporan perpustakaan</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-6 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Total Buku</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalBuku }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Total Anggota</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalAnggota }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Total Peminjaman</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalPeminjaman }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Sedang Dipinjam</p>
            <p class="text-2xl font-bold text-blue-600 mt-1">{{ $sedangDipinjam }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Dikembalikan</p>
            <p class="text-2xl font-bold text-green-600 mt-1">{{ $dikembalikan }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border {{ $terlambat > 0 ? 'border-red-200 bg-red-50' : 'border-gray-100' }}">
            <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Terlambat</p>
            <p class="text-2xl font-bold {{ $terlambat > 0 ? 'text-red-600' : 'text-gray-900' }} mt-1">{{ $terlambat }}</p>
        </div>
    </div>

    <!-- Laporan Instan (Ekspor Langsung) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-purple-50 px-6 py-4 border-b border-purple-100">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg> Ekspor Laporan Langsung ke PDF
            </h3>
            <p class="text-xs text-gray-500 mt-1">Klik tombol untuk mengunduh laporan PDF secara langsung</p>
        </div>
        <div class="p-6 grid md:grid-cols-3 gap-4">

            {{-- Laporan Anggota --}}
            <div class="border border-gray-200 rounded-xl p-5 flex flex-col gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                    <div>
                        <p class="font-semibold text-gray-800">Laporan Anggota</p>
                        <p class="text-xs text-gray-500">Daftar pembaca & riwayat pinjam</p>
                    </div>
                </div>
                <a href="{{ route('petugas.laporan.anggota') }}"
                   class="w-full bg-blue-600 text-white text-sm font-semibold py-2 rounded-lg hover:bg-blue-700 transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Unduh PDF
                </a>
            </div>

            {{-- Buku Terpopuler --}}
            <div class="border border-gray-200 rounded-xl p-5 flex flex-col gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center text-purple-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg></div>
                    <div>
                        <p class="font-semibold text-gray-800">Buku Terpopuler</p>
                        <p class="text-xs text-gray-500">Ranking buku paling sering dipinjam</p>
                    </div>
                </div>
                <a href="{{ route('petugas.laporan.buku-populer') }}"
                   class="w-full bg-purple-600 text-white text-sm font-semibold py-2 rounded-lg hover:bg-purple-700 transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Unduh PDF
                </a>
            </div>

            {{-- Keterlambatan --}}
            <div class="border border-gray-200 rounded-xl p-5 flex flex-col gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center text-red-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg></div>
                    <div>
                        <p class="font-semibold text-gray-800">Keterlambatan</p>
                        <p class="text-xs text-gray-500">Daftar pinjaman yang terlambat</p>
                    </div>
                </div>
                <a href="{{ route('petugas.laporan.keterlambatan') }}"
                   class="w-full bg-red-600 text-white text-sm font-semibold py-2 rounded-lg hover:bg-red-700 transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Unduh PDF
                </a>
            </div>

        </div>
    </div>

    <!-- Filter & Generate Laporan Peminjaman -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-blue-50 px-6 py-4 border-b border-blue-100">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg> Generate Laporan Peminjaman (dengan Filter)
            </h3>
            <p class="text-xs text-gray-500 mt-1">Filter data lalu klik Generate untuk melihat preview, kemudian simpan sebagai PDF</p>
        </div>
        <form action="{{ route('petugas.laporan.index') }}" method="GET" class="p-6">
            <div class="grid md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                    <input type="date" name="dari" value="{{ request('dari') }}" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                    <input type="date" name="sampai" value="{{ request('sampai') }}" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        <option value="semua" {{ request('status') == 'semua' ? 'selected' : '' }}>Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="menunggu_pengembalian" {{ request('status') == 'menunggu_pengembalian' ? 'selected' : '' }}>Menunggu Pengembalian</option>
                        <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div>
                    <button type="submit" name="generate" value="1" class="w-full bg-blue-600 text-white py-2.5 rounded-lg font-semibold hover:bg-blue-700 transition flex items-center justify-center gap-2 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Generate Laporan
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Preview Table -->
    @if($filtered)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-gray-900">Hasil Laporan Peminjaman</h3>
                <p class="text-xs text-gray-500 mt-0.5">Ditemukan <span class="font-bold text-blue-600">{{ $peminjaman->count() }}</span> data peminjaman</p>
            </div>
            <a href="{{ route('petugas.laporan.peminjaman', request()->only(['dari', 'sampai', 'status'])) }}"
               class="inline-flex items-center gap-2 bg-red-600 text-white px-5 py-2.5 rounded-lg font-semibold hover:bg-red-700 transition text-sm shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Simpan sebagai PDF
            </a>
        </div>

        @if($peminjaman->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 text-gray-600 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Peminjam</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Judul Buku</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Tgl Pinjam</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Batas Kembali</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Tgl Kembali</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-bold uppercase tracking-wider">Terlambat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($peminjaman as $i => $item)
                    @php
                        $isOverdue = $item->status == 'dipinjam' && $item->tanggal_batas_kembali && $item->tanggal_batas_kembali->isPast();
                        $hariTerlambat = $isOverdue ? now()->diffInDays($item->tanggal_batas_kembali) : 0;
                    @endphp
                    <tr class="{{ $isOverdue ? 'bg-red-50' : 'hover:bg-gray-50' }}">
                        <td class="px-6 py-3 text-sm text-gray-600">{{ $i + 1 }}</td>
                        <td class="px-6 py-3 text-sm font-medium text-gray-900">{{ $item->user->name ?? '-' }}</td>
                        <td class="px-6 py-3 text-sm text-gray-700">{{ $item->book->judul ?? '-' }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ $item->tanggal_pinjam->format('d/m/Y') }}</td>
                        <td class="px-6 py-3 text-sm {{ $isOverdue ? 'text-red-600 font-bold' : 'text-gray-600' }}">
                            {{ $item->tanggal_batas_kembali ? $item->tanggal_batas_kembali->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ $item->tanggal_kembali ? $item->tanggal_kembali->format('d/m/Y') : '-' }}</td>
                        <td class="px-6 py-3">
                            @if($isOverdue)
                                <span class="inline-flex items-center px-2.5 py-0.5 bg-red-100 text-red-800 rounded-full text-xs font-bold border border-red-200">Terlambat</span>
                            @elseif($item->status == 'pending')
                                <span class="inline-flex items-center px-2.5 py-0.5 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold border border-yellow-200">Pending</span>
                            @elseif($item->status == 'dipinjam')
                                <span class="inline-flex items-center px-2.5 py-0.5 bg-purple-100 text-purple-800 rounded-full text-xs font-bold border border-purple-200">Dipinjam</span>
                            @elseif($item->status == 'menunggu_pengembalian')
                                <span class="inline-flex items-center px-2.5 py-0.5 bg-orange-100 text-orange-800 rounded-full text-xs font-bold border border-orange-200">Menunggu Pengembalian</span>
                            @elseif($item->status == 'dikembalikan')
                                <span class="inline-flex items-center px-2.5 py-0.5 bg-green-100 text-green-800 rounded-full text-xs font-bold border border-green-200">Dikembalikan</span>
                            @elseif($item->status == 'ditolak')
                                <span class="inline-flex items-center px-2.5 py-0.5 bg-red-100 text-red-700 rounded-full text-xs font-bold border border-red-200">Ditolak</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-center">
                            @if($hariTerlambat > 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 bg-red-100 text-red-800 rounded-full text-xs font-bold border border-red-200">{{ $hariTerlambat }} hari</span>
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Ringkasan -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            <div class="flex flex-wrap gap-4 text-xs">
                <span class="text-gray-500">Ringkasan:</span>
                <span class="font-bold text-yellow-700">Pending: {{ $peminjaman->where('status', 'pending')->count() }}</span>
                <span class="font-bold text-purple-700">Dipinjam: {{ $peminjaman->where('status', 'dipinjam')->count() }}</span>
                <span class="font-bold text-orange-700">Konfirmasi: {{ $peminjaman->where('status', 'menunggu_pengembalian')->count() }}</span>
                <span class="font-bold text-green-700">Dikembalikan: {{ $peminjaman->where('status', 'dikembalikan')->count() }}</span>
                <span class="font-bold text-red-700">Ditolak: {{ $peminjaman->where('status', 'ditolak')->count() }}</span>
            </div>
        </div>
        @else
        <div class="p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">Data Tidak Ditemukan</h3>
            <p class="text-gray-500 text-sm">Tidak ada data peminjaman dengan filter yang dipilih.</p>
        </div>
        @endif
    </div>
    @endif

</div>
@endsection
