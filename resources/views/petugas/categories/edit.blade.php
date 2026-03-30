@extends('petugas.layout')
@section('page-title', 'Edit Kategori')
@section('content')
<div class="max-w-xl">
    <div class="mb-6">
        <a href="{{ route('petugas.categories.index') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-700 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Edit Kategori</h1>
    </div>
    <form action="{{ route('petugas.categories.update', $category) }}" method="POST" class="bg-white rounded-2xl shadow-sm p-8">
        @csrf
        @method('PUT')
        <div class="mb-6">
            <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori *</label>
            <input type="text" name="nama" id="nama" value="{{ old('nama', $category->nama) }}" required
                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 @error('nama') border-red-500 @enderror">
            @error('nama')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-semibold hover:bg-indigo-700 transition-all">
            Perbarui
        </button>
    </form>
</div>
@endsection
