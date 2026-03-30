<section>
    <header>
        <h2 class="text-xl font-bold text-slate-900">
            {{ __('Informasi Profil') }}
        </h2>

        <p class="mt-1 text-sm text-slate-600">
            {{ __("Perbarui informasi profil akun dan alamat email Anda.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Hidden Avatar Input (Triggered by label in edit.blade.php) -->
        <input type="file" id="avatar-upload" name="avatar" class="hidden" accept="image/*" onchange="previewImage(this)">

        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full bg-slate-50 border-slate-200 focus:ring-blue-500 focus:border-blue-500 rounded-xl" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" name="username" type="text" class="mt-1 block w-full bg-slate-50 border-slate-200 focus:ring-blue-500 focus:border-blue-500 rounded-xl" :value="old('username', $user->username)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('username')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full bg-slate-50 border-slate-200 focus:ring-blue-500 focus:border-blue-500 rounded-xl" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="alamat" :value="__('Alamat')" />
            <textarea id="alamat" name="alamat" class="mt-1 block w-full bg-slate-50 border-slate-200 focus:ring-blue-500 focus:border-blue-500 rounded-xl shadow-sm" rows="3">{{ old('alamat', $user->alamat) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('alamat')" />
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="bg-blue-900 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-blue-800 transition shadow-lg shadow-blue-900/20">
                {{ __('Simpan Perubahan') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 font-bold flex items-center gap-1"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ __('Disimpan.') }}
                </p>
            @endif
        </div>
    </form>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                // Submit form automatically or just show preview? 
                // Let's just update the image src if possible, but the image is in parent view.
                // Simpler: Just submit or let user click save?
                // For now, let user click save to actually upload.
                
                // Optional: Preview logic
                var reader = new FileReader();
                reader.onload = function(e) {
                    // Try to find the image in the parent document and update it
                    // This is a bit hacky since it's in a different file/scope, but standard JS works
                    const img = document.querySelector('.md\\:col-span-1 img'); // Target the profile image
                    const div = document.querySelector('.md\\:col-span-1 .bg-blue-100'); // Target the placeholder div
                    
                    if(img) {
                        img.onload = () => URL.revokeObjectURL(img.src); // memory management
                        img.src = e.target.result;
                    } 
                    // If there was no image (placeholder), we might need to swap div for img. 
                    // Complex to do with simple JS here without full component reload. 
                    // Let's keep it simple: Preview works best if image tag exists.
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</section>
