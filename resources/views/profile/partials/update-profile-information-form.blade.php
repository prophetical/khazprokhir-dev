<section>
    <header class="mb-8">
        <h2 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter flex items-center">
            <span class="w-2 h-6 bg-indigo-500 rounded-full mr-3 shadow-[0_0_15px_rgba(99,102,241,0.5)]"></span>
            {{ __('Informasi Profil') }}
        </h2>

        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 font-medium">
            {{ __("Perbarui informasi profil akun dan alamat email Anda.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div class="space-y-2">
            <x-input-label for="username" class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest px-1">
                {{ __('Username') }}
            </x-input-label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 dark:text-gray-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <x-text-input id="username" name="username" type="text" class="block w-full pl-11 border-gray-100 dark:border-white/5 bg-gray-50/50 dark:bg-white/5 font-bold text-gray-400 dark:text-gray-600 cursor-not-allowed rounded-2xl" :value="$user->username" disabled />
            </div>
            <p class="px-1 text-[10px] font-bold text-gray-400 dark:text-gray-600 italic">
                {{ __('Username tidak dapat diubah.') }}
            </p>
        </div>

        <div class="space-y-2">
            <x-input-label for="np" class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest px-1">
                {{ __('NP (Nomor Pokok)') }}
            </x-input-label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 dark:text-gray-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </div>
                <x-text-input id="np" name="np" type="text" class="block w-full pl-11 border-gray-100 dark:border-white/5 bg-gray-50/50 dark:bg-white/5 font-bold text-gray-400 dark:text-gray-600 cursor-not-allowed rounded-2xl" :value="$user->np" disabled />
            </div>
            <p class="px-1 text-[10px] font-bold text-gray-400 dark:text-gray-600 italic">
                {{ __('NP (Nomor Pokok) tidak dapat diubah.') }}
            </p>
        </div>

        <div class="space-y-2">
            <x-input-label for="name" class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest px-1">
                {{ __('Nama Lengkap') }}
            </x-input-label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 dark:text-gray-500 group-focus-within:text-indigo-500 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <x-text-input id="name" name="name" type="text" class="block w-full pl-11 border-gray-100 dark:border-white/5 bg-gray-50/50 dark:bg-white/5 focus:bg-white dark:focus:bg-gray-800 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 font-bold text-gray-700 dark:text-gray-300 transition-all duration-300" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="space-y-2">
            <x-input-label for="email" class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest px-1">
                {{ __('Alamat Email') }}
            </x-input-label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 dark:text-gray-500 group-focus-within:text-indigo-500 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/></svg>
                </div>
                <x-text-input id="email" name="email" type="email" class="block w-full pl-11 border-gray-100 dark:border-white/5 bg-gray-50/50 dark:bg-white/5 focus:bg-white dark:focus:bg-gray-800 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 font-bold text-gray-700 dark:text-gray-300 transition-all duration-300" :value="old('email', $user->email)" required autocomplete="username" />
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-4 p-5 bg-amber-50 dark:bg-amber-900/10 rounded-2xl border border-amber-100 dark:border-amber-900/50 leading-relaxed shadow-sm">
                    <p class="text-xs text-amber-700 dark:text-amber-500 font-bold flex items-center mb-3">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                        {{ __('Alamat email Anda belum terverifikasi.') }}
                    </p>

                    <button form="send-verification" class="text-[10px] bg-white dark:bg-gray-800 border border-amber-200 dark:border-amber-900 shadow-sm px-4 py-2 rounded-xl text-amber-700 dark:text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-900/20 transition-all font-black uppercase tracking-widest">
                        {{ __('Kirim Ulang Verifikasi') }}
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-3 font-bold text-[10px] text-green-600 dark:text-green-500 flex items-center uppercase tracking-widest">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            {{ __('Tautan baru telah dikirim.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-6 mt-6 border-t border-gray-50 dark:border-white/5">
            <button type="submit" 
                    class="relative group overflow-hidden py-3.5 px-8 bg-indigo-600 rounded-2xl shadow-xl shadow-indigo-200 dark:shadow-none text-[11px] font-black uppercase tracking-[0.2em] transition-all duration-300 active:scale-95 text-white">
                <div class="absolute inset-0 bg-white/20 translate-y-full transition-transform duration-300 group-hover:translate-y-0 text-center flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                </div>
                <span class="relative z-10 flex items-center justify-center group-hover:opacity-0 transition-opacity duration-300 uppercase">
                    {{ __('Simpan Profil') }}
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </span>
            </button>

            @if (session('status') === 'profile-updated')
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="flex items-center gap-2 px-4 py-2 bg-green-50 dark:bg-green-500/10 rounded-xl border border-green-100 dark:border-green-500/20">
                    <svg class="w-4 h-4 text-green-600 dark:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span class="text-[10px] font-black text-green-700 dark:text-green-500 uppercase tracking-widest">{{ __('Berhasil') }}</span>
                </div>
            @endif
        </div>
    </form>
</section>

