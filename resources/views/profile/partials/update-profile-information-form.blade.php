<section>
    <header>
        <h2 class="text-lg font-bold text-gray-800 uppercase tracking-wider flex items-center">
            <div class="w-1.5 h-5 bg-indigo-500 rounded-full mr-3"></div>
            {{ __('Informasi Profil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-500 font-medium">
            {{ __("Perbarui informasi profil akun dan alamat email Anda.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-8 space-y-6">
        @csrf
        @method('patch')

        <div class="space-y-2">
            <x-input-label for="username" class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">
                {{ __('Username') }}
            </x-input-label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <x-text-input id="username" name="username" type="text" class="block w-full pl-11 border-gray-200 rounded-xl bg-gray-50/50 font-bold text-gray-400 cursor-not-allowed" :value="$user->username" disabled />
            </div>
            <p class="px-1 text-[10px] font-medium text-gray-400">
                {{ __('Username tidak dapat diubah.') }}
            </p>
        </div>

        <div class="space-y-2">
            <x-input-label for="name" class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">
                {{ __('Nama Lengkap') }}
            </x-input-label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <x-text-input id="name" name="name" type="text" class="block w-full pl-11 border-gray-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 font-bold text-gray-700" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="space-y-2">
            <x-input-label for="email" class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">
                {{ __('Alamat Email') }}
            </x-input-label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/></svg>
                </div>
                <x-text-input id="email" name="email" type="email" class="block w-full pl-11 border-gray-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 font-bold text-gray-700" :value="old('email', $user->email)" required autocomplete="username" />
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-4 p-4 bg-amber-50 rounded-xl border border-amber-100 leading-relaxed">
                    <p class="text-xs text-amber-700 font-bold flex items-center mb-2">
                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                        {{ __('Alamat email Anda belum terverifikasi.') }}
                    </p>

                    <button form="send-verification" class="text-xs bg-white border border-amber-200 px-3 py-1.5 rounded-lg text-amber-600 hover:bg-amber-100 transition-all font-black uppercase tracking-widest">
                        {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-3 font-bold text-xs text-green-600">
                            {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
            <button type="submit" 
                    class="relative group overflow-hidden py-3 px-8 bg-indigo-600 rounded-xl shadow-lg shadow-indigo-100 text-[10px] font-black uppercase tracking-[0.2em] transition-all duration-300 active:scale-95 text-white">
                <div class="absolute inset-0 bg-white/10 translate-y-full transition-transform duration-300 group-hover:translate-y-0"></div>
                <span class="relative z-10 flex items-center justify-center">
                    {{ __('Simpan Perubahan') }}
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </span>
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-xs font-black text-green-600 uppercase tracking-widest"
                >
                    <svg class="w-4 h-4 inline-block mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('Tersimpan.') }}
                </p>
            @endif
        </div>
    </form>
</section>

