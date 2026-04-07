<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User Hak Akses') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-slate-900 overflow-hidden shadow-sm rounded-xl border-t-4 border-indigo-500 dark:border-indigo-600 border-x border-b dark:border-x-slate-800 dark:border-b-slate-800">
                <div class="p-8 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-8">
                        <div class="flex flex-col">
                            <h3 class="text-lg font-bold text-indigo-800 dark:text-indigo-400 tracking-tight">Modifikasi
                                Akun</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Perbarui data user
                                ({{ $user->name }}) dan konfigurasi
                                ulang hak akses.</p>
                        </div>
                        <a href="{{ route('users.index') }}"
                            class="text-sm text-gray-400 dark:text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 flex items-center gap-1 transition-colors group">
                            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali
                        </a>
                    </div>

                    @if($errors->any())
                        <div
                            class="mb-6 bg-red-50 dark:bg-rose-900/10 border-l-4 border-red-400 dark:border-rose-800 p-4 rounded-r-lg shadow-sm">
                            <ul class="text-red-700 dark:text-rose-400 text-sm space-y-1">
                                @foreach($errors->all() as $err)
                                    <li>• {{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('users.update', $user->id) }}">
                        @csrf
                        @method('PUT')

                        {{-- Bagian 1: Identitas --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div class="md:col-span-1">
                                <label
                                    class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Nama
                                    Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                    class="block w-full border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-lg shadow-sm text-sm py-2.5 px-3 focus:border-indigo-500 focus:ring-indigo-500 dark:text-gray-300 transition-all font-bold">
                            </div>
                            <div class="md:col-span-1">
                                <label
                                    class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Alamat
                                    Email</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                    class="block w-full border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-lg shadow-sm text-sm py-2.5 px-3 focus:border-indigo-500 focus:ring-indigo-500 dark:text-gray-300 transition-all font-bold font-mono">
                            </div>
                            <div class="md:col-span-1">
                                <label
                                    class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Username</label>
                                <input type="text" name="username" value="{{ old('username', $user->username) }}"
                                    required
                                    class="block w-full border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-lg shadow-sm text-sm py-2.5 px-3 focus:border-indigo-500 focus:ring-indigo-500 dark:text-gray-300 transition-all font-bold font-mono">
                            </div>
                            <div
                                class="md:col-span-1 border-l-4 border-amber-200 dark:border-amber-800 pl-4 bg-amber-50/30 dark:bg-amber-900/10 py-2 rounded-r-lg">
                                <label
                                    class="block text-[10px] font-bold text-amber-600 uppercase tracking-widest mb-2 italic flex items-center gap-2">
                                    <svg class="w-3 h-3 text-amber-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                    </svg>
                                    Reset Password (Opsional)
                                </label>
                                <input type="password" name="password" placeholder="Kosongkan jika tidak diubah"
                                    class="block w-full border-transparent bg-white/50 dark:bg-slate-800/50 rounded shadow-sm text-xs py-2 px-3 focus:border-amber-400 focus:ring-amber-400 dark:text-gray-300 transition-all font-bold">
                            </div>
                            <div class="md:col-span-1 pt-6">
                                <label
                                    class="block text-[10px] font-bold text-gray-300 uppercase tracking-widest mb-2">Konfirmasi
                                    Reset Password</label>
                                <input type="password" name="password_confirmation"
                                    class="block w-full border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-800 rounded shadow-sm text-xs py-2 px-3 focus:border-indigo-500 focus:ring-indigo-500 dark:text-gray-300 transition-all font-bold">
                            </div>
                        </div>

                        {{-- Bagian 2: Role --}}
                        <div class="mb-8 p-6 bg-indigo-50/50 dark:bg-indigo-950/10 rounded-xl border border-indigo-100/50 dark:border-indigo-900/30"
                            x-data="{ role: '{{ old('role', $user->role) }}' }">
                            <label
                                class="block text-[10px] font-bold text-indigo-400 uppercase tracking-[0.2em] mb-4">Ubah
                                Role</label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @foreach(['admin' => 'Administrator', 'supervisor' => 'Supervisor', 'sortir' => 'Staff Sortir', 'kemas' => 'Staff Pengemasan', 'khazverutas' => 'Staff Khazai Verutas'] as $val => $label)
                                    <label
                                        class="relative flex items-center p-4 cursor-pointer rounded-lg border-2 transition-all"
                                        :class="role === '{{ $val }}' ? 'bg-white dark:bg-slate-800 border-indigo-500 dark:border-indigo-600 shadow-md ring-2 ring-indigo-500/10' : 'bg-transparent border-gray-100 dark:border-slate-800 hover:border-indigo-200 dark:hover:border-indigo-900'">
                                        <input type="radio" name="role" value="{{ $val }}" @click="role = '{{ $val }}'"
                                            class="sr-only" {{ old('role', $user->role) === $val ? 'checked' : '' }}>
                                        <div class="flex flex-col">
                                            <span
                                                class="text-sm font-black uppercase tracking-widest transition-colors duration-300"
                                                :class="role === '{{ $val }}' ? 'text-indigo-700 dark:text-indigo-400' : 'text-gray-400 dark:text-gray-600'">{{ $val }}</span>
                                            <span
                                                class="text-[10px] text-gray-500 dark:text-gray-400 mt-1">{{ $label }}</span>
                                        </div>
                                        <div x-show="role === '{{ $val }}'"
                                            class="absolute right-4 top-1/2 -translate-y-1/2">
                                            <svg class="w-5 h-5 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd" />
                                                </path>
                                            </svg>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            <div x-show="role === 'admin'"
                                class="mt-4 p-3 bg-amber-50 dark:bg-amber-950/20 rounded-lg border border-amber-200 dark:border-amber-900/50 flex items-center gap-3">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <span
                                    class="text-[10px] text-amber-700 dark:text-amber-500 font-bold uppercase tracking-tight leading-none italic">
                                    Mengatur role sebagai Admin akan memberikan semua hak akses penuh kepada username
                                    ini.</span>
                            </div>
                        </div>

                </div>

                {{-- Footer Action --}}
                <div class="flex justify-end gap-3 pt-6 border-t border-gray-100 dark:border-slate-800">
                    <a href="{{ route('users.index') }}"
                        class="px-8 py-3 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg font-bold text-sm text-gray-400 dark:text-gray-500 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-slate-700 active:scale-95 transition-all">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-10 py-3 bg-indigo-600 border border-transparent rounded-lg font-bold text-sm text-white uppercase tracking-widest shadow-lg hover:bg-indigo-700 active:scale-95 transition-all duration-300">
                        Simpan Perubahan
                    </button>
                </div>

                </form>
            </div>
        </div>
    </div>
    </div>
</x-app-layout>