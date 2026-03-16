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

    <!-- Scripts -->
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

    <!-- Local AlpineJS -->
    <script defer src="{{ asset('vendor/alpinejs/alpine.min.js') }}"></script>

    <!-- Theme Management -->
    <script>
        (function () {
            const theme = localStorage.getItem('theme') || 'light-mode';
            document.documentElement.classList.add(theme);
            document.addEventListener('DOMContentLoaded', () => {
                document.body.classList.add(theme);
            });
        })();
    </script>

    <style>
        :root {
            --theme-bg-main: #f3f4f6;
            --theme-bg-card: #ffffff;
            --theme-bg-sidebar: linear-gradient(180deg, #1e40af 0%, #7c3aed 100%);
            --theme-bg-header: linear-gradient(135deg, rgba(30, 64, 175, 0.95) 0%, rgba(124, 58, 237, 0.95) 55%, rgba(219, 39, 119, 0.95) 100%);
            --theme-text-main: #111827;
            --theme-text-muted: #6b7280;
            --theme-border-main: #e5e7eb;
            --theme-input-bg: #ffffff;
            --theme-hover-bg: #f9fafb;
        }

        .dark-mode {
            --theme-bg-main: #1E293B;
            /* Main page background */
            --theme-bg-card: #243047;
            /* Content background */
            --theme-bg-sidebar: rgba(15, 23, 42, 0.75);
            /* Sidebar background matching header */
            --theme-bg-header: rgba(15, 23, 42, 0.75);
            /* Header background translucent */
            --theme-text-main: #F1F5F9;
            /* Primary text */
            --theme-text-muted: #CBD5F5;
            /* Secondary text */
            --theme-border-main: #3B4B65;
            /* Border color */
            --theme-input-bg: #2f3646ff;
            /* Darker input background for better contrast */
            --theme-hover-bg: #333a44ff;
            /* Hover color */
            --theme-neutral-bg: #2A3A55;
            /* Neutral UI background */
        }

        body.light-mode {
            background-color: var(--theme-bg-main);
            color: var(--theme-text-main);
        }

        body.dark-mode {
            background-color: var(--theme-bg-main) !important;
            color: var(--theme-text-main) !important;
        }

        /* Universal Adaptations */
        body.dark-mode .bg-gray-100 {
            background-color: var(--theme-bg-main) !important;
        }

        body.dark-mode .bg-white {
            background-color: var(--theme-bg-card) !important;
        }

        body.dark-mode .bg-gray-50 {
            background-color: var(--theme-neutral-bg) !important;
        }

        /* Typography & Borders */
        body.dark-mode .text-gray-900,
        body.dark-mode .text-gray-800 {
            color: var(--theme-text-main) !important;
        }

        body.dark-mode .text-gray-700,
        body.dark-mode .text-gray-600 {
            color: var(--theme-text-muted) !important;
        }

        body.dark-mode .text-gray-400,
        body.dark-mode .text-gray-500 {
            color: #818cf8 !important;
        }

        /* Indigo-400 for better secondary visibility */
        body.dark-mode .border-gray-100,
        body.dark-mode .border-gray-200,
        body.dark-mode .border-gray-300 {
            border-color: var(--theme-border-main) !important;
        }

        /* Sidebar & Header */
        #top-header {
            background: var(--theme-bg-header);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        nav.flex.flex-col.shrink-0 {
            background: var(--theme-bg-sidebar);
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }

        body.dark-mode #top-header {
            background: var(--theme-bg-header) !important;
            border-bottom: 1px solid var(--theme-border-main) !important;
        }

        body.dark-mode nav.flex.flex-col.shrink-0 {
            background: var(--theme-bg-sidebar) !important;
            border-right: 1px solid var(--theme-border-main) !important;
            backdrop-blur: 24px !important;
        }

        body.dark-mode nav.flex.flex-col.shrink-0 .hover\:bg-white\/10:hover {
            background-color: var(--theme-hover-bg) !important;
        }

        body.dark-mode nav.flex.flex-col.shrink-0 .bg-white\/20 {
            background-color: rgba(99, 102, 241, 0.15) !important;
            border-left: 4px solid #6366f1;
        }

        /* Table Refinements */
        body.dark-mode table {
            border-color: var(--theme-border-main) !important;
        }

        body.dark-mode .divide-y>*+* {
            border-color: var(--theme-border-main) !important;
        }

        body.dark-mode .divide-gray-200>*+* {
            border-color: var(--theme-border-main) !important;
        }

        /* Table Header Refinement (Clean & Solid) */
        body.dark-mode thead {
            background-color: #1a2434 !important;
        }

        body.dark-mode thead * {
            border-color: transparent !important;
            border-width: 0 !important;
            background-color: transparent !important;
        }

        body.dark-mode thead th.sticky {
            background-color: #1a2434 !important;
        }

        /* Table Body & Footer Unification (Perfectly Consistent) */
        body.dark-mode tbody td {
            background-color: #243047 !important;
            border-color: var(--theme-border-main) !important;
        }

        body.dark-mode tbody td.sticky {
            background-color: #243047 !important;
        }

        body.dark-mode tfoot {
            background-color: #2f3646ff !important;
        }

        body.dark-mode tfoot td {
            background-color: #2f3646ff !important;
            border-color: var(--theme-border-main) !important;
        }

        body.dark-mode tfoot td.sticky {
            background-color: #2f3646ff !important;
        }

        body.dark-mode tr:hover td {
            background-color: var(--theme-hover-bg) !important;
        }

        /* Unifying Table Highlights & Containers in Dark Mode (ONLY) */
        body.dark-mode .bg-gray-50\/50 {
            background-color: transparent !important;
        }

        /* Preserving denomination labels by ensuring unification rules don't hit span elements */

        /* Form Fields - Aggressive Overrides for Visibility */
        body.dark-mode input,
        body.dark-mode select,
        body.dark-mode textarea,
        body.dark-mode input[type="date"],
        body.dark-mode .bg-white.rounded-xl {
            background-color: #2f3646ff !important;
            /* Force high-contrast dark background */
            border: 1.5px solid #6366f1 !important;
            /* Distinct indigo border line */
            color: #ffffff !important;
        }

        body.dark-mode input:focus,
        body.dark-mode select:focus {
            border-color: #818cf8 !important;
            outline: 2px solid rgba(99, 102, 241, 0.4) !important;
        }

        /* Global Shadow Removal (requested by user) */

        /* HCS Sorting Report Specific Styles */
        @keyframes bounce-subtle {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-3px); }
        }
        .animate-bounce-subtle {
            animation: bounce-subtle 2s infinite ease-in-out;
        }

        body.dark-mode #main-wrapper {
            background-color: #0f172a !important; /* slate-900 */
        }
        
        body.dark-mode .bg-white,
        body.dark-mode .bg-slate-50,
        body.dark-mode .bg-gray-50 {
            background-color: #1e293b !important; /* slate-800 */
            border-color: #334155 !important; /* slate-700 */
        }

        body.dark-mode .bg-slate-100,
        body.dark-mode .bg-slate-200 {
            background-color: #0f172a !important; /* slate-900 */
            border-color: #1e293b !important;
        }

        body.dark-mode .from-white,
        body.dark-mode .to-slate-50\/50,
        body.dark-mode .from-slate-50\/50 {
            background-image: none !important;
            background-color: #1e293b !important;
        }

        body.dark-mode .shadow-xl,
        body.dark-mode .shadow-2xl,
        body.dark-mode .shadow-sm {
            box-shadow: none !important;
        }

        .pack-sorted-other-rikyet {
            background-color: #dbeafe;
            border-color: #bfdbfe;
            color: #1e40af;
            background-image: repeating-linear-gradient(45deg, rgba(30, 64, 175, 0.1), rgba(30, 64, 175, 0.1) 2px, transparent 2px, transparent 4px);
        }
        body.dark-mode .pack-sorted-other-rikyet {
            background-color: rgba(30, 64, 175, 0.3) !important;
            border-color: rgba(100, 116, 139, 0.3) !important;
            color: #93c5fd !important;
            background-image: repeating-linear-gradient(45deg, rgba(147, 197, 253, 0.1), rgba(147, 197, 253, 0.1) 2px, transparent 2px, transparent 4px) !important;
        }

        .pack-sorted-other-cutpack {
            background-color: #dcfce7;
            border-color: #bbf7d0;
            color: #166534;
            background-image: repeating-linear-gradient(-45deg, rgba(22, 101, 52, 0.1), rgba(22, 101, 52, 0.1) 2px, transparent 2px, transparent 4px);
        }
        body.dark-mode .pack-sorted-other-cutpack {
            background-color: rgba(22, 101, 52, 0.3) !important;
            border-color: rgba(100, 116, 139, 0.3) !important;
            color: #6ee7b7 !important;
            background-image: repeating-linear-gradient(-45deg, rgba(110, 231, 183, 0.1), rgba(110, 231, 183, 0.1) 2px, transparent 2px, transparent 4px) !important;
        }

        body.dark-mode .bg-rose-50 {
            background-color: rgba(159, 18, 57, 0.2) !important; /* rose-900/20 */
            border-color: rgba(225, 29, 72, 0.4) !important; /* rose-600/40 */
            color: #fecdd3 !important; /* rose-200 */
        }

        /* Global dark mode header color (applied only if no explicit text color is set) */
        body.dark-mode h3:not([class*="text-"]), 
        body.dark-mode h2:not([class*="text-"]),
        body.dark-mode h4:not([class*="text-"]) {
            color: #ffffff !important;
        }

        /* Force black text to stay black even in dark mode (useful for colored warning boxes) */
        body.dark-mode .text-black,
        body.dark-mode .!text-black {
            color: #000000 !important;
        }

        /* Detail Pengemasan HCS Specific Styles */
        body.dark-mode .bg-gradient-to-br[class*="from-"][class*="-50"].to-white {
            background-image: none !important;
            background-color: rgba(30, 41, 59, 0.5) !important; /* slate-800/50 */
            border-color: rgba(71, 85, 105, 0.3) !important; /* slate-600/30 */
        }

        /* Restore top accent borders that were overridden by global bg-white rule */
        body.dark-mode .border-t-8.border-lime-500, body.dark-mode .border-2.border-lime-500 { border-color: #84cc16 !important; }
        body.dark-mode .border-t-8.border-gray-500, body.dark-mode .border-2.border-gray-500 { border-color: #6b7280 !important; }
        body.dark-mode .border-t-8.border-amber-600, body.dark-mode .border-2.border-amber-600 { border-color: #d97706 !important; }
        body.dark-mode .border-t-8.border-purple-500, body.dark-mode .border-2.border-purple-500 { border-color: #a855f7 !important; }
        body.dark-mode .border-t-8.border-green-500, body.dark-mode .border-2.border-green-500 { border-color: #22c55e !important; }
        body.dark-mode .border-t-8.border-blue-500, body.dark-mode .border-2.border-blue-500 { border-color: #3b82f6 !important; }
        body.dark-mode .border-t-8.border-red-500, body.dark-mode .border-2.border-red-500 { border-color: #ef4444 !important; }

        /* Detail Pack label adjustments */
        body.dark-mode .bg-white.px-2.py-0.5.rounded.border,
        body.dark-mode .bg-white.shadow-sm.rounded-2xl.border-t-8 {
            background-color: #243047 !important; /* theme-bg-card */
        }

        /* HCTS Summary Card Denomination Tints in Dark Mode */
        body.dark-mode .bg-lime-50 { background-color: rgba(132, 204, 22, 0.1) !important; border-color: rgba(132, 204, 22, 0.2) !important; }
        body.dark-mode .bg-gray-50 { background-color: rgba(156, 163, 175, 0.1) !important; border-color: rgba(156, 163, 175, 0.2) !important; }
        body.dark-mode .bg-amber-50 { background-color: rgba(251, 191, 36, 0.1) !important; border-color: rgba(251, 191, 36, 0.2) !important; }
        body.dark-mode .bg-purple-50 { background-color: rgba(168, 85, 247, 0.1) !important; border-color: rgba(168, 85, 247, 0.2) !important; }
        body.dark-mode .bg-green-50 { background-color: rgba(34, 197, 94, 0.1) !important; border-color: rgba(34, 197, 94, 0.2) !important; }
        body.dark-mode .bg-blue-50 { background-color: rgba(59, 130, 246, 0.1) !important; border-color: rgba(59, 130, 246, 0.2) !important; }
        body.dark-mode .bg-red-50 { background-color: rgba(239, 68, 68, 0.1) !important; border-color: rgba(239, 68, 68, 0.2) !important; }

        /* HCTS Filter Cards Dark Mode Backgrounds */
        body.dark-mode .bg-gray-50\/50 {
            background-color: rgba(30, 41, 59, 0.4) !important;
            border-color: rgba(255, 255, 255, 0.05) !important;
        }
        body.dark-mode .bg-white\/50 {
            background-color: rgba(15, 23, 42, 0.4) !important;
            border-color: rgba(255, 255, 255, 0.05) !important;
        }

        /* HCTS Reset Button in Filter Card */
        body.dark-mode .bg-white.border-gray-100.rounded-2xl.text-gray-400 {
            background-color: rgba(30, 41, 59, 0.6) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        /* HCTS Inventory Dark Mode Overrides */
        body.dark-mode .bg-white\/70.backdrop-blur-md {
            background-color: rgba(15, 23, 42, 0.6) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }
        body.dark-mode .bg-gray-50.border-gray-100.rounded-xl {
            background-color: rgba(30, 41, 59, 0.8) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: #ffffff !important;
        }
        body.dark-mode .bg-emerald-50.text-emerald-600 {
            background-color: rgba(16, 185, 129, 0.1) !important;
            border-color: rgba(16, 185, 129, 0.2) !important;
            color: #34d399 !important;
        }

        /* HCTS Submission Footer & Buttons in Dark Mode */
        body.dark-mode .bg-gray-50\/30.backdrop-blur-sm {
            background-color: rgba(15, 23, 42, 0.8) !important;
            border-top-color: rgba(255, 255, 255, 0.05) !important;
        }
        body.dark-mode .bg-gray-100.text-gray-400.cursor-not-allowed.opacity-50 {
            background-color: rgba(30, 41, 59, 0.5) !important;
            color: rgba(148, 163, 184, 0.3) !important;
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
        }
    </style>
    @stack('css')
</head>

<body class="font-sans antialiased">
    <div class="flex h-screen bg-gray-100 overflow-hidden">
        <!-- Sidebar -->
        @include('layouts.navigation')

        <!-- Main Content Container -->
        <div id="main-scroll-container" class="flex-1 flex flex-col overflow-y-auto overflow-x-hidden relative min-w-0">
            <!-- Top Header -->
            <nav id="top-header"
                style="background: var(--theme-bg-header);"
                class="px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between shrink-0 sticky top-0 z-50 w-full transition-all duration-500 ease-in-out border-b border-white/10 backdrop-blur-xl">
                <div class="flex items-center gap-4">
                    <button class="text-white/70 hover:text-white focus:outline-none md:hidden transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    @if(isset($backUrl) && $backUrl)
                        <a href="{{ $backUrl }}"
                            class="group flex items-center justify-center w-10 h-10 bg-white/10 hover:bg-white text-white hover:text-indigo-600 rounded-xl transition-all duration-300 border border-white/20 hover:border-white">
                            <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>
                    @endif

                    @isset($header)
                        <div
                            class="flex-1 [&_h2]:text-white [&_h2]:font-extrabold [&_h2]:tracking-tight [&_h2]:drop-shadow">
                            {{ $header }}
                        </div>
                    @else
                        <div class="flex-1"></div>
                    @endisset
                </div>

                <!-- Realtime Jam & Tanggal -->
                <div class="hidden xl:flex items-center ml-auto mr-4 text-white/80 bg-white/5 dark:bg-slate-800/40 border border-white/10 dark:border-white/5 shadow-inner rounded-2xl px-4 py-1.5 hover:bg-white/10 transition-colors duration-300 group"
                    x-data="{ 
                    time: '', 
                    date: '',
                    init() {
                        this.updateClock();
                        setInterval(() => this.updateClock(), 1000);
                    },
                    updateClock() {
                        const now = new Date();
                        const optionsDate = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', timeZone: 'Asia/Jakarta' };
                        const optionsTime = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false, timeZone: 'Asia/Jakarta' };
                        this.date = now.toLocaleDateString('id-ID', optionsDate);
                        this.time = now.toLocaleTimeString('id-ID', optionsTime).replace(/\./g, ':');
                    }
                }">
                    <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform duration-500">
                        <svg class="w-4 h-4 text-pink-300 drop-shadow-[0_0_8px_rgba(244,114,182,0.5)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex flex-col text-right justify-center">
                        <span class="text-[8px] font-black uppercase tracking-[0.2em] text-white/40 mb-0.5" x-text="date"></span>
                        <div class="flex items-baseline gap-1">
                            <span class="text-sm font-black tracking-tighter text-white leading-none"
                                x-text="time"></span>
                            <span class="text-[8px] font-black text-rose-400 uppercase tracking-widest">WIB</span>
                        </div>
                    </div>
                </div>

                <!-- Theme Toggle -->
                <div class="flex items-center mr-4" x-data="{ 
                    darkMode: false,
                    toggleTheme() {
                        this.darkMode = !this.darkMode;
                        const theme = this.darkMode ? 'dark-mode' : 'light-mode';
                        const oldTheme = this.darkMode ? 'light-mode' : 'dark-mode';
                        
                        document.documentElement.classList.remove(oldTheme);
                        document.documentElement.classList.add(theme);
                        document.body.classList.remove(oldTheme);
                        document.body.classList.add(theme);
                        
                        localStorage.setItem('theme', theme);
                    }
                }" x-init="darkMode = document.documentElement.classList.contains('dark-mode')">
                    <button @click="toggleTheme()"
                        class="p-2 border border-white/20 text-white/90 bg-white/10 hover:bg-white/20 focus:outline-none transition rounded-lg"
                        title="Toggle Theme">
                        <template x-if="!darkMode">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                        </template>
                        <template x-if="darkMode">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 3v1m0 16v1m9-9h1M4 9h1m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </template>
                    </button>
                </div>

                <!-- User Profile Dropdown -->
                <div class="flex items-center ml-auto">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="flex items-center gap-3 px-3 py-1.5 border border-white/10 dark:border-white/5 text-sm font-bold rounded-2xl text-white bg-white/5 hover:bg-white/10 focus:outline-none transition-all duration-300 group">
                                <div class="hidden sm:block text-[11px] uppercase tracking-widest opacity-80 group-hover:opacity-100 transition-opacity">{{ Auth::user()->name }}</div>
                                <div
                                    class="h-8 w-8 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-black border border-white/20 shadow-lg group-hover:scale-105 transition-transform duration-300">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <svg class="w-4 h-4 text-white/40 group-hover:text-white/80 transition-colors" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="flex-1 min-w-0">
                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('scripts')



    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Global Session Flash Messages Handler
            @if(session('success'))
                Swal.fire({
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    confirmButtonColor: '#4f46e5'
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    title: 'Oops...',
                    text: "{{ session('error') }}",
                    icon: 'error',
                    confirmButtonColor: '#4f46e5'
                });
            @endif
                const scrollContainer = document.getElementById('main-scroll-container');
            const header = document.getElementById('top-header');

            if (scrollContainer && header) {
                let isShrunk = false;

                scrollContainer.addEventListener('scroll', () => {
                    // Menggunakan sistem threshold/hysteresis untuk mencegah jitter:
                    // Selisih h-20 (80px) dan h-14 (56px) adalah 24px. Jarak antara batas atas dan batas bawah harus > 24px.
                    if (scrollContainer.scrollTop > 40 && !isShrunk) {
                        header.classList.add('shadow-xl', 'h-14', 'border-white/5');
                        header.classList.remove('h-20', 'border-white/10');
                        isShrunk = true;
                    } else if (scrollContainer.scrollTop <= 10 && isShrunk) {
                        header.classList.remove('shadow-xl', 'h-14', 'border-white/5');
                        header.classList.add('h-20', 'border-white/10');
                        isShrunk = false;
                    }
                });
            }

            // Global Delete Confirmation Handler
            document.addEventListener('submit', (e) => {
                const form = e.target;

                if (form.classList.contains('delete-confirm') || form.classList.contains('delete-confirm-double')) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e11d48', // rose-600
                        cancelButtonColor: '#4b5563', // gray-600
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        borderRadius: '1.5rem',
                        customClass: {
                            popup: 'rounded-[2rem] border-0 shadow-2xl p-8',
                            confirmButton: 'rounded-xl font-bold uppercase tracking-widest text-[10px] px-8 py-3.5',
                            cancelButton: 'rounded-xl font-bold uppercase tracking-widest text-[10px] px-8 py-3.5'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>