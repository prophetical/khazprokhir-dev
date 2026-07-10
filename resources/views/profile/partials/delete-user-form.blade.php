<section class="space-y-6">
    <header>
        <h2 class="text-lg font-bold text-red-600 uppercase tracking-wider flex items-center">
            <div class="w-1.5 h-5 bg-red-500 rounded-full mr-3 text-red-600"></div>
            {{ __('Hapus Akun') }}
        </h2>

        <p class="mt-1 text-sm text-gray-500 font-medium leading-relaxed">
            {{ __('Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Sebelum menghapus akun, harap unduh data atau informasi apa pun yang ingin Anda simpan.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="relative group overflow-hidden py-3 px-8 bg-red-600 rounded-xl shadow-lg shadow-red-100 text-[10px] font-black uppercase tracking-[0.2em] transition-all duration-300 active:scale-95 text-white"
    >
        <div class="absolute inset-0 bg-white/10 translate-y-full transition-transform duration-300 group-hover:translate-y-0"></div>
        <span class="relative z-10 flex items-center justify-center">
            {{ __('Hapus Akun secara Permanen') }}
        </span>
    </x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8">
            @csrf
            @method('delete')

            <h2 class="text-xl font-black text-gray-800 uppercase tracking-wider mb-4">
                {{ __('Konfirmasi Penghapusan Akun') }}
            </h2>

            <div class="p-4 bg-red-50 border border-red-100 rounded-xl mb-6">
                <p class="text-sm text-red-700 font-bold leading-relaxed">
                    {{ __('Apakah Anda yakin ingin menghapus akun Anda secara permanen? Semua data akan hilang selamanya.') }}
                </p>
            </div>

            <p class="text-sm text-gray-600 font-medium mb-6 leading-relaxed">
                {{ __('Silakan masukkan kata sandi Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun Anda secara permanen.') }}
            </p>

            <div class="space-y-2 mb-8">
                <x-input-label for="password" value="{{ __('Kata Sandi') }}" class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1" />

                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-red-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <x-text-input
                        id="password"
                        name="password"
                        type="password"
                        class="block w-full pl-11 border-gray-200 rounded-xl focus:ring-4 focus:ring-red-500/10 font-bold text-gray-700"
                        placeholder="{{ __('Masukkan Kata Sandi') }}"
                    />
                </div>

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" 
                        class="py-3 px-6 bg-gray-100 text-gray-500 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-200 transition-all">
                    {{ __('Batal') }}
                </button>

                <button type="submit" 
                        class="py-3 px-6 bg-red-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-700 shadow-lg shadow-red-100 transition-all active:scale-95">
                    {{ __('Hapus Sekarang') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>

