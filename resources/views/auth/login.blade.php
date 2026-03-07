<x-guest-layout>
    <div class="mb-6 text-center relative">
        <h1 class="text-2xl font-black text-white tracking-tight mb-1">
            Selamat Datang
        </h1>
        <p class="text-indigo-200/60 text-xs font-medium">Silakan masuk ke akun Anda</p>
        
        {{-- Decorative line --}}
        <div class="mt-4 flex justify-center">
            <div class="w-10 h-1 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full shadow-[0_0_10px_rgba(99,102,241,0.8)]"></div>
        </div>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-5 p-3 rounded-xl bg-indigo-500/20 border border-indigo-400/30 text-indigo-100 text-xs backdrop-blur-md" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Username (Email) -->
        <div class="space-y-2">
            <label for="email" class="block text-xs font-bold text-indigo-300 uppercase tracking-widest ml-1">
                Username
            </label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-indigo-400 text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                    class="block w-full pl-12 pr-4 py-3.5 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all backdrop-blur-sm shadow-inner"
                    placeholder="Masukkan username anda" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400 text-xs font-medium" />
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <div class="flex justify-between items-center px-1">
                <label for="password" class="block text-xs font-bold text-indigo-300 uppercase tracking-widest">
                    Kata Sandi
                </label>
            </div>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-indigo-400 text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <input id="password" name="password" type="password" required
                    class="block w-full pl-12 pr-4 py-3.5 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all backdrop-blur-sm shadow-inner"
                    placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400 text-xs font-medium" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between px-1">
            <label for="remember_me" class="inline-flex items-center group cursor-pointer">
                <div class="relative flex items-center">
                    <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded border-white/20 bg-white/5 text-indigo-600 focus:ring-indigo-500/50 transition-all cursor-pointer">
                </div>
                <span class="ms-2 text-sm text-indigo-200/70 group-hover:text-white transition-colors">Ingat Saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-medium text-indigo-400 hover:text-indigo-300 transition-colors" href="{{ route('password.request') }}">
                    Lupa kata sandi?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="pt-1">
            <button type="submit" class="w-full relative group overflow-hidden rounded-2xl">
                <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-purple-600 transition-all group-hover:scale-105"></div>
                <div class="absolute inset-0 bg-white/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative px-8 py-3.5 flex items-center justify-center gap-2">
                    <span class="text-white font-black uppercase tracking-[0.2em] text-xs">Masuk</span>
                    <svg class="w-4 h-4 text-white transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </div>
            </button>
        </div>

        {{-- Register Link --}}
        @if (Route::has('register'))
            <p class="text-center text-xs text-indigo-200/50 pt-2">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="text-indigo-400 font-bold hover:text-indigo-300 transition-colors">Daftar Sekarang</a>
            </p>
        @endif
    </form>
</x-guest-layout>
