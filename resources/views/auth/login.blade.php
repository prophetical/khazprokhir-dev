<x-guest-layout>

    {{-- Greeting --}}
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight mb-1">Selamat Datang 👋</h1>
        <p class="text-sm text-gray-400 font-medium">Silakan masuk ke akun Anda untuk melanjutkan</p>
    </div>

    {{-- Session Status --}}
    <x-auth-session-status class="mb-5 p-3 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-600 text-xs font-medium" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Username / Email --}}
        <div class="space-y-1.5">
            <label for="username" class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                Username
            </label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-300 group-focus-within:text-indigo-400 transition-colors">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <input id="username" name="username" type="text" value="{{ old('username') }}" required autofocus
                    class="block w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 transition-all"
                    placeholder="Masukkan username Anda" />
            </div>
            <x-input-error :messages="$errors->get('username')" class="mt-1 text-red-500 text-xs font-medium" />
        </div>

        {{-- Password --}}
        <div class="space-y-1.5">
            <label for="password" class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                Kata Sandi
            </label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-300 group-focus-within:text-indigo-400 transition-colors">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <input id="password" name="password" type="password" required
                    class="block w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 transition-all"
                    placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-500 text-xs font-medium" />
        </div>

        {{-- Remember Me & Forgot Password --}}
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer group">
                <input id="remember_me" type="checkbox" name="remember"
                    class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                <span class="text-sm text-gray-500 group-hover:text-gray-700 transition-colors select-none">Ingat Saya</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   class="text-sm font-semibold text-indigo-500 hover:text-indigo-700 transition-colors">
                    Lupa kata sandi?
                </a>
            @endif
        </div>

        {{-- Submit Button --}}
        <div class="pt-1">
            <button type="submit"
                class="w-full flex items-center justify-center gap-2 px-6 py-3.5 text-white font-black text-sm uppercase tracking-widest rounded-xl shadow-lg shadow-indigo-200 hover:shadow-xl hover:shadow-indigo-300/60 hover:-translate-y-0.5 active:translate-y-0 active:shadow-md transition-all duration-200"
                style="background: linear-gradient(135deg, #2563eb 0%, #7c3aed 60%, #db2777 100%);">
                <span>Masuk</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </button>
        </div>

        {{-- Register Link --}}
        @if (Route::has('register'))
            <p class="text-center text-xs text-gray-400 pt-1">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-indigo-500 font-bold hover:text-indigo-700 transition-colors">Daftar Sekarang</a>
            </p>
        @endif
    </form>

</x-guest-layout>
