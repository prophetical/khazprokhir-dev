<x-guest-layout>
    <div class="mb-10 text-center">
        <h2 class="text-2xl font-black text-gray-900 uppercase tracking-tighter flex items-center justify-center">
            <span class="w-1.5 h-6 bg-indigo-600 rounded-full mr-3 shadow-[0_0_15px_rgba(79,70,229,0.4)]"></span>
            {{ __('Daftar Akun Baru') }}
        </h2>
        <p class="mt-2 text-xs text-gray-500 font-medium italic">
            {{ __('Silhakan lengkapi form di bawah ini untuk bergabung.') }}
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div class="space-y-1.5">
            <x-input-label for="name" class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">
                {{ __('Nama Lengkap') }}
            </x-input-label>
            <div class="relative group">
                <div
                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <x-text-input id="name"
                    class="block w-full pl-11 border-gray-100 bg-gray-50/50 focus:bg-white rounded-2xl focus:ring-4 focus:ring-indigo-500/10 font-bold text-gray-700 transition-all duration-300"
                    type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- Email Address -->
        <div class="space-y-1.5">
            <x-input-label for="email" class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">
                {{ __('Alamat Email') }}
            </x-input-label>
            <div class="relative group">
                <div
                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                    </svg>
                </div>
                <x-text-input id="email"
                    class="block w-full pl-11 border-gray-100 bg-gray-50/50 focus:bg-white rounded-2xl focus:ring-4 focus:ring-indigo-500/10 font-bold text-gray-700 transition-all duration-300"
                    type="email" name="email" :value="old('email')" required autocomplete="username" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Username -->
        <div class="space-y-1.5">
            <x-input-label for="username" class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">
                {{ __('Username') }}
            </x-input-label>
            <div class="relative group">
                <div
                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <x-text-input id="username"
                    class="block w-full pl-11 border-gray-100 bg-gray-50/50 focus:bg-white rounded-2xl focus:ring-4 focus:ring-indigo-500/10 font-bold text-gray-700 transition-all duration-300"
                    type="text" name="username" :value="old('username')" required />
            </div>
            <x-input-error :messages="$errors->get('username')" class="mt-1" />
        </div>

        <!-- NP (Nomor Pokok) -->
        <div class="space-y-1.5">
            <x-input-label for="np" class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">
                {{ __('NP (Nomor Pokok)') }}
            </x-input-label>
            <div class="relative group">
                <div
                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </div>
                <x-text-input id="np"
                    class="block w-full pl-11 border-gray-100 bg-gray-50/50 focus:bg-white rounded-2xl focus:ring-4 focus:ring-indigo-500/10 font-bold text-gray-700 transition-all duration-300"
                    type="text" name="np" :value="old('np')" required />
            </div>
            <p class="px-1 text-[10px] text-gray-400 italic">{{ __('Hanya huruf dan angka, contoh: A12345 atau 12345') }}</p>
            <x-input-error :messages="$errors->get('np')" class="mt-1" />
        </div>

        <!-- Password -->
        <div class="space-y-1.5">
            <x-input-label for="password" class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">
                {{ __('Kata Sandi') }}
            </x-input-label>
            <div class="relative group">
                <div
                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <x-text-input id="password"
                    class="block w-full pl-11 border-gray-100 bg-gray-50/50 focus:bg-white rounded-2xl focus:ring-4 focus:ring-indigo-500/10 font-bold text-gray-700 transition-all duration-300"
                    type="password" name="password" required autocomplete="new-password" />
            </div>
            <p class="px-1 text-[10px] text-gray-400 italic">{{ __('Min. 8 karakter: kombinasi huruf besar, huruf kecil, angka, dan simbol (mis. !@#$&).') }}</p>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div class="space-y-1.5">
            <x-input-label for="password_confirmation"
                class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">
                {{ __('Konfirmasi Kata Sandi') }}
            </x-input-label>
            <div class="relative group">
                <div
                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <x-text-input id="password_confirmation"
                    class="block w-full pl-11 border-gray-100 bg-gray-50/50 focus:bg-white rounded-2xl focus:ring-4 focus:ring-indigo-500/10 font-bold text-gray-700 transition-all duration-300"
                    type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <div class="pt-4 flex flex-col gap-4">
            <button type="submit"
                class="relative group overflow-hidden py-3.5 px-8 bg-indigo-600 rounded-2xl shadow-xl shadow-indigo-200 text-[11px] font-black uppercase tracking-[0.2em] transition-all duration-300 active:scale-95 text-white w-full">
                <div
                    class="absolute inset-0 bg-white/20 translate-y-full transition-transform duration-300 group-hover:translate-y-0 text-center flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <span
                    class="relative z-10 flex items-center justify-center group-hover:opacity-0 transition-opacity duration-300 uppercase">
                    {{ __('Daftar Sekarang') }}
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </span>
            </button>

            <div class="text-center">
                <a class="text-[10px] font-black text-gray-400 hover:text-indigo-600 uppercase tracking-widest transition-colors duration-300 flex items-center justify-center gap-1.5"
                    href="{{ route('login') }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 7l-5 5m0 0l5 5m-5-5h12" />
                    </svg>
                    {{ __('Sudah punya akun?') }}
                </a>
            </div>
        </div>
    </form>
</x-guest-layout>