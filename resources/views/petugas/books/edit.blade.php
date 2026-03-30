@extends('petugas.layout')

@section('page-title', 'Edit Buku')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <div class="mb-6">
        <a href="{{ route('petugas.books.index') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-blue-600 mb-4 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Buku
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Edit Buku</h1>
        <p class="text-gray-500">Perbarui informasi buku ini.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <form action="{{ route('petugas.books.update', $book) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid md:grid-cols-2 gap-6">
                <!-- Judul -->
                <div>
                    <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">Judul Buku *</label>
                    <input type="text" name="judul" id="judul" value="{{ old('judul', $book->judul) }}" required
                        class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-100 transition @error('judul') border-red-500 @enderror">
                    @error('judul') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Penulis -->
                <div>
                    <label for="penulis" class="block text-sm font-medium text-gray-700 mb-2">Penulis *</label>
                    <input type="text" name="penulis" id="penulis" value="{{ old('penulis', $book->penulis) }}" required
                        class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-100 transition @error('penulis') border-red-500 @enderror">
                    @error('penulis') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Penerbit -->
                <div>
                    <label for="penerbit" class="block text-sm font-medium text-gray-700 mb-2">Penerbit</label>
                    <input type="text" name="penerbit" id="penerbit" value="{{ old('penerbit', $book->penerbit) }}"
                        class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-100 transition @error('penerbit') border-red-500 @enderror">
                </div>

                <!-- Tahun Terbit -->
                <div>
                    <label for="tahun_terbit" class="block text-sm font-medium text-gray-700 mb-2">Tahun Terbit</label>
                    <input type="number" name="tahun_terbit" id="tahun_terbit" value="{{ old('tahun_terbit', $book->tahun_terbit) }}" min="1900" max="{{ date('Y') }}"
                        class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-100 transition @error('tahun_terbit') border-red-500 @enderror">
                </div>

                <!-- Stok -->
                <div>
                    <label for="stok" class="block text-sm font-medium text-gray-700 mb-2">Stok *</label>
                    <input type="number" name="stok" id="stok" value="{{ old('stok', $book->stok) }}" min="0" required
                        class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-100 transition @error('stok') border-red-500 @enderror">
                </div>
            </div>

            <!-- Kategori -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Kategori</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 p-4 bg-gray-50 rounded-xl border border-gray-100">
                    @foreach(\App\Models\Category::all() as $category)
                    <label class="inline-flex items-center p-3 bg-white rounded-lg border border-gray-200 cursor-pointer hover:border-blue-300 hover:shadow-sm transition">
                        <input type="checkbox" name="category_id[]" value="{{ $category->id }}" 
                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                            {{ (is_array(old('category_id')) && in_array($category->id, old('category_id'))) || $book->categories->contains($category->id) ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-600">{{ $category->nama }}</span>
                    </label>
                    @endforeach
                </div>
                @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Cover -->
            <div>
                <label for="cover" class="block text-sm font-medium text-gray-700 mb-2">Cover Buku</label>
                
                @if($book->cover)
                    <div class="mb-3">
                        <img src="{{ Storage::url($book->cover) }}" alt="Current Cover" class="h-32 rounded-lg border border-gray-200">
                        <p class="text-xs text-gray-500 mt-1">Cover saat ini</p>
                    </div>
                @endif

                <input type="file" name="cover" id="cover" accept="image/*"
                    class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:outline-none transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:font-medium @error('cover') border-red-500 @enderror">
                <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah cover.</p>
                @error('cover') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Deskripsi -->
            <div>
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" rows="4" 
                    class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-100 transition @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi', $book->deskripsi) }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-4 pt-6 mt-6 border-t border-gray-100">
                <a href="{{ route('petugas.books.index') }}" class="text-gray-500 hover:text-gray-700 font-medium">
                    Batal
                </a>
                <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-blue-600/20 hover:bg-blue-700 hover:-translate-y-0.5 transition-all">
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
