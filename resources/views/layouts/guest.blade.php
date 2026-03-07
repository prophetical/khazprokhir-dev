<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}{{ isset($header) ? ' | ' . trim(strip_tags($header)) : '' }}</title>

        <!-- Fonts Local -->
        <link href="{{ asset('css/fonts.css') }}" rel="stylesheet" />

        <!-- Tailwind Local -->
        <script src="{{ asset('vendor/tailwindcss/tailwindcss.min.js') }}"></script>
        
        <!-- Local AlpineJS -->
        <script defer src="{{ asset('vendor/alpinejs/alpine.min.js') }}"></script>
    <body class="font-sans text-white antialiased selection:bg-indigo-500/30">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-[#0f172a] via-[#1e1b4b] to-[#0f172a] relative overflow-hidden">
            
            {{-- Floating Decorative Elements --}}
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-indigo-600/20 rounded-full blur-[120px] animate-pulse"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-purple-600/20 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s"></div>

            <div class="z-10 transition-all duration-700 hover:scale-105">
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-indigo-400 drop-shadow-[0_0_15px_rgba(129,140,248,0.5)]" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 z-10">
                <div class="mx-4 sm:mx-0 px-8 py-8 bg-white/5 backdrop-blur-2xl border border-white/10 shadow-[0_25px_50px_-12px_rgba(0,0,0,0.5)] rounded-3xl relative overflow-hidden group">
                    {{-- Glass inner glow --}}
                    <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent pointer-events-none"></div>
                    
                    {{ $slot }}
                </div>
                
                <p class="mt-6 text-center text-xs font-bold text-gray-500 uppercase tracking-[0.3em] opacity-50">
                    Khazprokhir &middot; v2.0
                </p>
            </div>
        </div>
    </body>
</html>
