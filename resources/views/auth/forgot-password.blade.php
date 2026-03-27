<x-guest-layout>
    {{-- Original Forgot Password Code (Commented Out per request)
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password
        reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
    --}}

    <div class="py-2 text-center">
        <div class="flex justify-center mb-6">
            <div class="relative">
                <div
                    class="w-20 h-20 bg-indigo-50 rounded-[1.5rem] flex items-center justify-center border border-indigo-100 shadow-inner">
                    <span class="text-3xl font-black text-indigo-300">404</span>
                </div>
                <div
                    class="absolute -top-1 -right-1 w-7 h-7 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg border-2 border-white">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2-2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
            </div>
        </div>

        <h2 class="text-xl font-black text-gray-900 uppercase tracking-tighter mb-4">
            {{ __('Akses Terbatas') }}
        </h2>

        <div class="px-3 py-4 bg-amber-50 rounded-2xl border border-amber-100 shadow-sm mb-6">
            <p class="text-[11px] text-amber-800 font-bold leading-relaxed px-4">
                {{ __('Untuk reset password, silakan hubungi administrator Khazprokhir di ekstensi:') }}
                <br>
                <span class="text-lg text-indigo-600 mt-1 block tracking-widest font-black">3482 / 3480</span>
            </p>
        </div>

        <div class="flex flex-col gap-4">
            <a href="{{ route('login') }}"
                class="relative group overflow-hidden py-3 px-8 bg-indigo-600 rounded-2xl shadow-lg shadow-indigo-100 text-[10px] font-black uppercase tracking-[0.2em] transition-all duration-300 active:scale-95 text-white">
                <span class="relative z-10 flex items-center justify-center">
                    {{ __('Kembali ke Login') }}
                </span>
            </a>
        </div>
    </div>
</x-guest-layout>