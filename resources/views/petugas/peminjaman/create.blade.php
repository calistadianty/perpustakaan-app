@extends('petugas.layout')

@section('page-title', 'Tambah Peminjaman')

@section('content')
<div class="max-w-2xl mx-auto">
    
    <div class="mb-6">
        <a href="{{ route('petugas.peminjaman.index') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-blue-600 mb-4 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Catat Peminjaman Baru</h1>
        <p class="text-gray-500">Catat peminjaman buku oleh anggota.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <form action="{{ route('petugas.peminjaman.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Pembaca -->
            <div>
                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Peminjam (Anggota) *</label>
                <select name="user_id" id="user_id" required 
                    class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-100 transition">
                    <option value="">-- Pilih Peminjam --</option>
                    @foreach(\App\Models\User::where('role', 'user')->get() as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Buku -->
            <div>
                <label for="book_id" class="block text-sm font-medium text-gray-700 mb-2">Buku *</label>
                <select name="book_id" id="book_id" required 
                    class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-100 transition">
                    <option value="">-- Pilih Buku --</option>
                    @foreach(\App\Models\Book::where('stok', '>', 0)->approved()->get() as $book)
                        <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                            {{ $book->judul }} (Stok: {{ $book->stok }})
                        </option>
                    @endforeach
                </select>
                @error('book_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Tanggal Pinjam -->
            <div>
                <label for="tanggal_pinjam" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pinjam *</label>
                <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required
                    class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-100 transition">
                @error('tanggal_pinjam') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Keterangan -->
            <div>
                <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan (Opsional)</label>
                <textarea name="keterangan" id="keterangan" rows="3" 
                    class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-100 transition">{{ old('keterangan') }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-4 pt-6 mt-6 border-t border-gray-100">
                <a href="{{ route('petugas.peminjaman.index') }}" class="text-gray-500 hover:text-gray-700 font-medium">
                    Batal
                </a>
                <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-blue-600/20 hover:bg-blue-700 hover:-translate-y-0.5 transition-all">
                    Simpan Peminjaman
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
