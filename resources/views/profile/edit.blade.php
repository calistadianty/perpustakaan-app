@extends('user.layout')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto px-6 space-y-8">
        
        <!-- Header -->
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Pengaturan Profil</h2>
            <p class="text-slate-500">Kelola informasi akun dan keamanan Anda.</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Left Column: Profile Card -->
            <div class="md:col-span-1 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 text-center">
                    <div class="relative w-32 h-32 mx-auto mb-4">
                        @if($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover rounded-full border-4 border-blue-50 shadow-md">
                        @else
                            <div class="w-full h-full bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-4xl font-bold border-4 border-blue-50">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                        <label for="avatar-upload" class="absolute bottom-0 right-0 bg-blue-600 text-white p-2 rounded-full cursor-pointer hover:bg-blue-700 transition shadow-lg" title="Ganti Foto">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </label>
                    </div>
                    <h3 class="font-bold text-xl text-slate-900">{{ $user->name }}</h3>
                    <p class="text-slate-500 text-sm">{{ $user->email }}</p>
                    <p class="mt-2 inline-flex items-center px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-bold uppercase tracking-wide">
                        {{ $user->role }}
                    </p>
                </div>

                <div class="bg-blue-900 rounded-2xl shadow-sm p-6 text-white relative overflow-hidden">
                    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#FFFFFF 1px, transparent 1px); background-size: 20px 20px;"></div>
                    <div class="relative z-10">
                        <h4 class="font-bold text-lg mb-2">Member Rumah Baca</h4>
                        <p class="text-blue-200 text-sm mb-4">Bergabung sejak {{ $user->created_at->format('d M Y') }}</p>
                        <hr class="border-blue-800 mb-4">
                        <div class="flex justify-between text-sm">
                            <span>Status</span>
                            <span class="font-bold text-green-400">Aktif</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Edit Forms -->
            <div class="md:col-span-2 space-y-8">
                
                <!-- Update Info -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <!-- Update Password -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
                    @include('profile.partials.update-password-form')
                </div>

                <!-- Delete Account -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
