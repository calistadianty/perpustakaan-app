@extends('admin.layout')

@section('page-title', 'Tambah Buku')

@section('content')
<div class="space-y-6">

    
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('admin.books.index') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-700 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Tambah Buku Baru</h1>
        <p class="text-gray-500">Isi form berikut untuk menambahkan buku ke perpustakaan</p>
    </div>

    <!-- Form -->
     <div class="max-w-3x3">
    <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm p-8 space-y-6">
        @csrf

        <div class="grid md:grid-cols-2 gap-6">
            <!-- Judul -->
            <div class="md:col-span-2">
                <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">Judul Buku *</label>
                <input type="text" name="judul" id="judul" value="{{ old('judul') }}" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition @error('judul') border-red-500 @enderror"
                    placeholder="Masukkan judul buku">
                @error('judul')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Penulis -->
            <div>
                <label for="penulis" class="block text-sm font-medium text-gray-700 mb-2">Penulis *</label>
                <input type="text" name="penulis" id="penulis" value="{{ old('penulis') }}" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition @error('penulis') border-red-500 @enderror"
                    placeholder="Nama penulis">
                @error('penulis')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Penerbit -->
            <div>
                <label for="penerbit" class="block text-sm font-medium text-gray-700 mb-2">Penerbit</label>
                <input type="text" name="penerbit" id="penerbit" value="{{ old('penerbit') }}"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition @error('penerbit') border-red-500 @enderror"
                    placeholder="Nama penerbit">
                @error('penerbit')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tahun Terbit -->
            <div>
                <label for="tahun_terbit" class="block text-sm font-medium text-gray-700 mb-2">Tahun Terbit</label>
                <input type="number" name="tahun_terbit" id="tahun_terbit" value="{{ old('tahun_terbit') }}" min="1900" max="{{ date('Y') }}"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition @error('tahun_terbit') border-red-500 @enderror"
                    placeholder="{{ date('Y') }}">
                @error('tahun_terbit')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Stok -->
            <div>
                <label for="stok" class="block text-sm font-medium text-gray-700 mb-2">Stok *</label>
                <input type="number" name="stok" id="stok" value="{{ old('stok', 1) }}" min="0" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition @error('stok') border-red-500 @enderror"
                    placeholder="Jumlah stok">
                @error('stok')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kategori -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-3">Kategori</label>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($categories as $category)
                        <label class="flex items-center space-x-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition">
                            <input type="checkbox" name="category_id[]" value="{{ $category->id }}" 
                                {{ in_array($category->id, old('category_id', [])) ? 'checked' : '' }}
                                class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <span class="text-gray-700">{{ $category->nama }}</span>
                        </label>
                    @endforeach
                </div>
                @error('category_id')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Cover -->
            <div>
                <label for="cover" class="block text-sm font-medium text-gray-700 mb-2">Cover Buku</label>
                <input type="file" name="cover" id="cover" accept="image/*"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:text-indigo-700 file:font-medium @error('cover') border-red-500 @enderror">
                @error('cover')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Deskripsi -->
            <div class="md:col-span-2">
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" rows="4"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition resize-none @error('deskripsi') border-red-500 @enderror"
                    placeholder="Deskripsi singkat tentang buku">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Submit -->
        <div class="flex items-center gap-4 pt-4">
            <button type="submit" 
                class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-semibold hover:bg-indigo-700 transition-all">
                Simpan Buku
            </button>
            <a href="{{ route('admin.books.index') }}" class="text-gray-500 hover:text-gray-700 font-medium">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
