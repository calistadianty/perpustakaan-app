{{-- PENJELASAN BLADE & LAYOUT:
     - @extends('admin.layout') berarti file ini mewarisi / menggunakan template dasar dari layout admin pusat.
     - @section('page-title') mengirimkan teks ke elemen <title> di layout pusat (variabel dinamis).
     - @section('content') membungkus semua tag HTML di bawahnya untuk disuntikkan ke dalam tata letak utama.
--}}
@extends('admin.layout')

@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-8">
    
    <!-- Welcome -->
    <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
        <p class="text-gray-500">Kelola perpustakaan Rumah Baca dari panel admin ini.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Total Buku -->
        <div class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Buku</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($totalBooks) }}</p>
                </div>
                <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
            <a href="{{ route('admin.books.index') }}" class="inline-block mt-4 text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                Lihat Semua →
            </a>
        </div>

        <!-- Total Stok -->
        <div class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Stok</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($totalStock) }}</p>
                </div>
                <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                    </svg>
                </div>
            </div>
            <p class="mt-4 text-sm text-gray-400">Buku tersedia</p>
        </div>

        <!-- Total Pembaca -->
        <div class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Pembaca</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($totalUsers) }}</p>
                </div>
                <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
            <a href="{{ route('admin.pembaca.index') }}" class="inline-block mt-4 text-sm text-indigo-600 hover:text-indigo-800 font-medium">
    Lihat Semua →
</a>
        </div>

        <!-- Total Staff -->
        <div class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Staff</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($totalStaff) }}</p>
                </div>
                <div class="w-14 h-14 bg-purple-50 rounded-2xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
            <a href="{{ route('admin.petugas.index') }}?role=petugas" class="inline-block mt-4 text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                Lihat Semua →
            </a>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Aksi Cepat</h3>
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('admin.books.create') }}" 
               class="flex items-center gap-2 bg-indigo-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-indigo-700 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Buku
            </a>
            <a href="{{ route('admin.petugas.create') }}?role=petugas" 
               class="flex items-center gap-2 bg-white border-2 border-gray-200 text-gray-700 px-6 py-3 rounded-xl font-semibold hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
                Tambah Staff
            </a>
        </div>
    </div>

</div>
@endsection