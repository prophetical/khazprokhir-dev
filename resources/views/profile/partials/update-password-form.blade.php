<section>
    <header>
        <h2 class="text-lg font-bold text-gray-800 uppercase tracking-wider flex items-center">
            <div class="w-1.5 h-5 bg-indigo-500 rounded-full mr-3"></div>
            {{ __('Perbarui Kata Sandi') }}
        </h2>

        <p class="mt-1 text-sm text-gray-500 font-medium">
            {{ __('Pastikan akun Anda menggunakan kata sandi yang panjang dan acak untuk menjaga keamanan.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-8 space-y-6">
        @csrf
        @method('put')

        <div class="space-y-2">
            <x-input-label for="update_password_current_password" class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">
                {{ __('Kata Sandi Saat Ini') }}
            </x-input-label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2-2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <x-text-input id="update_password_current_password" name="current_password" type="password" class="block w-full pl-11 border-gray-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 font-bold text-gray-700" autocomplete="current-password" />
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div class="space-y-2">
            <x-input-label for="update_password_password" class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">
                {{ __('Kata Sandi Baru') }}
            </x-input-label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <x-text-input id="update_password_password" name="password" type="password" class="block w-full pl-11 border-gray-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 font-bold text-gray-700" autocomplete="new-password" />
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div class="space-y-2">
            <x-input-label for="update_password_password_confirmation" class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">
                {{ __('Konfirmasi Kata Sandi') }}
            </x-input-label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="block w-full pl-11 border-gray-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 font-bold text-gray-700" autocomplete="new-password" />
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
            <button type="submit" 
                    class="relative group overflow-hidden py-3 px-8 bg-indigo-600 rounded-xl shadow-lg shadow-indigo-100 text-[10px] font-black uppercase tracking-[0.2em] transition-all duration-300 active:scale-95 text-white">
                <div class="absolute inset-0 bg-white/10 translate-y-full transition-transform duration-300 group-hover:translate-y-0"></div>
                <span class="relative z-10 flex items-center justify-center">
                    {{ __('Perbarui Password') }}
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </span>
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-xs font-black text-green-600 uppercase tracking-widest"
                >
                    <svg class="w-4 h-4 inline-block mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('Berhasil Diperbarui.') }}
                </p>
            @endif
        </div>
    </form>
</section>

