@extends('admin.layout')

@section('page-title', 'Edit Akun')

@section('content')
<div class="space-y-6">
    
    <!-- Header -->
    <div>
        <a href="{{ route('admin.petugas.index') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-blue-600 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
        <h1 class="text-2xl font-bold text-blue-600">Edit Akun</h1>
        <p class="text-gray-500">Perbarui informasi akun {{ $user->name }}</p>
    </div>
    <!-- Form -->
    <div class="max-w-2x2 mx-auto">
        <form action="{{ route('admin.petugas.update', $user) }}" method="POST" class="bg-white rounded-2x2 shadow-sm p-8 space-y-6">
            @csrf
            @method('PUT')
        
        <div class="grid md:grid-cols-2 gap-6">
            <!-- Nama -->
            <div class="md:col-span-2">
                <label for="name" class="block text-sm font-medium text-blue-600 mb-2">Nama Lengkap *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                    class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-400 focus:outline-none transition @error('name') border-red-500 @enderror"
                    placeholder="Masukkan nama lengkap">
                @error('name')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Username -->
            <div>
                <label for="username" class="block text-sm font-medium text-blue-600 mb-2">Username *</label>
                <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" required
                    class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-400 focus:outline-none transition @error('username') border-red-500 @enderror"
                    placeholder="Username unik">
                @error('username')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-blue-600 mb-2">Email *</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                    class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-400 focus:outline-none transition @error('email') border-red-500 @enderror"
                    placeholder="email@example.com">
                @error('email')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>




            <!-- Alamat -->
            <div>
                <label for="alamat" class="block text-sm font-medium text-blue-600 mb-2">Alamat</label>
                <input type="text" name="alamat" id="alamat" value="{{ old('alamat', $user->alamat) }}"
                    class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-400 focus:outline-none transition @error('alamat') border-red-500 @enderror"
                    placeholder="Alamat lengkap (opsional)">
                @error('alamat')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-blue-600 mb-2">Password Baru</label>
                <input type="password" name="password" id="password"
                    class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-400 focus:outline-none transition @error('password') border-red-500 @enderror"
                    placeholder="Kosongkan jika tidak diubah">
                @error('password')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Konfirmasi Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-blue-600 mb-2">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-400 focus:outline-none transition"
                    placeholder="Ulangi password baru">
            </div>
        </div>

        <!-- Submit -->
        <div class="flex items-center gap-4 pt-4">
            <button type="submit" 
                class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-3 rounded-xl font-semibold hover:scale-105 transition-all">
                Perbarui Akun
            </button>
            <a href="{{ route('admin.petugas.index') }}?role={{ $user->role }}" class="text-gray-500 hover:text-blue-600 font-medium">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
