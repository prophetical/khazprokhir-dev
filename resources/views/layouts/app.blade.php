<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}{{ isset($header) ? ' | ' . trim(strip_tags($header)) : '' }}</title>

    <!-- Font Lokal -->
    <link href="{{ asset('css/fonts.css') }}" rel="stylesheet" />

    <!-- Scripts -->
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <!-- Manajemen Tema -->
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
            --theme-bg-main: #0f172a;
            /* depth slate-900 */
            --theme-bg-card: #1e293b;
            /* surface slate-800 */
            --theme-bg-sidebar: rgba(15, 23, 42, 0.85);
            /* translucent slate-900 */
            --theme-bg-header: rgba(15, 23, 42, 0.85);
            /* transparan slate-900 */
            --theme-text-main: #f8fafc;
            /* slate-50 */
            --theme-text-muted: #94a3b8;
            /* slate-400 */
            --theme-border-main: #334155;
            /* slate-700 */
            --theme-input-bg: #1e293b;
            /* slate-800 */
            --theme-hover-bg: rgba(255, 255, 255, 0.05);
            --theme-neutral-bg: #0f172a;
            /* slate-900 */
        }

        body.light-mode {
            background-color: var(--theme-bg-main);
            color: var(--theme-text-main);
        }

        body.dark-mode {
            background-color: var(--theme-bg-main) !important;
            color: var(--theme-text-main) !important;
        }

        /* Adaptasi Universal */
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

        /* Indigo-400 untuk visibilitas sekunder yang lebih baik */
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
            backdrop-blur: 40px !important;
            -webkit-backdrop-blur: 40px !important;
        }

        body.dark-mode nav.flex.flex-col.shrink-0 .hover\:bg-white\/10:hover {
            background-color: var(--theme-hover-bg) !important;
        }

        /* Sorotan Sidebar Aktif Penting */
        body.dark-mode nav.flex.flex-col.shrink-0 [class*="bg-white/20"],
        body.dark-mode nav.flex.flex-col.shrink-0 .bg-white\/20 {
            background-color: rgba(99, 102, 241, 0.15) !important;
            border-left: 4px solid #6366f1 !important;
            color: #ffffff !important;
        }

        body.dark-mode nav.flex.flex-col.shrink-0 [class*="text-white/70"] {
            color: rgba(255, 255, 255, 0.6) !important;
        }

        /* Penyempurnaan Tabel */
        body.dark-mode table {
            border-color: var(--theme-border-main) !important;
        }

        body.dark-mode .divide-y>*+* {
            border-color: var(--theme-border-main) !important;
        }

        body.dark-mode .divide-gray-200>*+* {
            border-color: var(--theme-border-main) !important;
        }

        /* Penyempurnaan Header Tabel (Clean & Solid) */
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

        /* Penyatuan Body & Footer Tabel */
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

        /* Menyatukan Sorotan Tabel & Kontainer dalam Mode Gelap */
        body.dark-mode .bg-gray-50\/50 {
            background-color: transparent !important;
        }

        /* Mempertahankan label pecahan dan memastikan aturan penyeragaman tdk mengenai elemen span */

        /* Kolom Input Form - Penimpaan Agresif untuk Visibilitas */
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

        /* Global Dark Mode SweetAlert2 */
        body.dark-mode .swal2-popup {
            background-color: #1e293b !important;
            /* theme-bg-card */
            color: #f8fafc !important;
            /* theme-text-main */
        }

        body.dark-mode .swal2-title,
        body.dark-mode .swal2-html-container,
        body.dark-mode .swal2-content {
            color: #f8fafc !important;
            /* theme-text-main */
        }


        /* Gaya Terkhusus Laporan Sortir HCS */
        @keyframes bounce-subtle {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-3px);
            }
        }

        .animate-bounce-subtle {
            animation: bounce-subtle 2s infinite ease-in-out;
        }

        body.dark-mode #main-scroll-container {
            background-color: var(--theme-bg-main) !important;
        }

        body.dark-mode .bg-white,
        body.dark-mode .bg-slate-50,
        body.dark-mode .bg-gray-50 {
            background-color: var(--theme-bg-card) !important;
            border-color: var(--theme-border-main) !important;
        }

        body.dark-mode .bg-slate-100,
        body.dark-mode .bg-slate-200,
        body.dark-mode .bg-gray-100,
        body.dark-mode .bg-gray-200 {
            background-color: var(--theme-bg-main) !important;
            border-color: var(--theme-border-main) !important;
        }

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
            background-color: rgba(159, 18, 57, 0.2) !important;
            /* rose-900/20 */
            border-color: rgba(225, 29, 72, 0.4) !important;
            /* rose-600/40 */
            color: #fecdd3 !important;
            /* rose-200 */
        }

        /* Warna teks header dark mode global (hanya jika kelas warna teks tidak disetel eksplisit) */
        body.dark-mode h3:not([class*="text-"]),
        body.dark-mode h2:not([class*="text-"]),
        body.dark-mode h4:not([class*="text-"]) {
            color: #ffffff !important;
        }

        /* Paksa teks hitam agar tetap hitam meski di dark mode (berguna untuk kotak peringatan berwarna) */
        body.dark-mode .text-black,
        body.dark-mode . !text-black {
            color: #000000 !important;
        }

        /* Gaya Terkhusus untuk Detail Pengemasan HCS */
        body.dark-mode .bg-gradient-to-br[class*="from-"][class*="-50"].to-white {
            background-image: none !important;
            background-color: rgba(30, 41, 59, 0.5) !important;
            /* slate-800/50 */
            border-color: rgba(71, 85, 105, 0.3) !important;
            /* slate-600/30 */
        }

        /* Kembalikan border aksen atas yang tertumpuk oleh aturan global bg-white */
        body.dark-mode .border-t-8.border-lime-500,
        body.dark-mode .border-2.border-lime-500 {
            border-color: #84cc16 !important;
        }

        body.dark-mode .border-t-8.border-gray-500,
        body.dark-mode .border-2.border-gray-500 {
            border-color: #6b7280 !important;
        }

        body.dark-mode .border-t-8.border-amber-600,
        body.dark-mode .border-2.border-amber-600 {
            border-color: #d97706 !important;
        }

        body.dark-mode .border-t-8.border-purple-500,
        body.dark-mode .border-2.border-purple-500 {
            border-color: #a855f7 !important;
        }

        body.dark-mode .border-t-8.border-green-500,
        body.dark-mode .border-2.border-green-500 {
            border-color: #22c55e !important;
        }

        body.dark-mode .border-t-8.border-blue-500,
        body.dark-mode .border-2.border-blue-500 {
            border-color: #3b82f6 !important;
        }

        body.dark-mode .border-t-8.border-red-500,
        body.dark-mode .border-2.border-red-500 {
            border-color: #ef4444 !important;
        }

        /* Penyesuaian label Detail Pack */
        body.dark-mode .bg-white.px-2.py-0.5.rounded.border,
        body.dark-mode .bg-white.shadow-sm.rounded-2xl.border-t-8 {
            background-color: #243047 !important;
            /* theme-bg-card */
        }

        /* Nuansa Warna Pecahan Kartu Ringkasan HCTS di Dark Mode */
        body.dark-mode .bg-lime-50 {
            background-color: rgba(132, 204, 22, 0.1) !important;
            border-color: rgba(132, 204, 22, 0.2) !important;
        }

        body.dark-mode .bg-gray-50 {
            background-color: rgba(156, 163, 175, 0.1) !important;
            border-color: rgba(156, 163, 175, 0.2) !important;
        }

        body.dark-mode .bg-amber-50 {
            background-color: rgba(251, 191, 36, 0.1) !important;
            border-color: rgba(251, 191, 36, 0.2) !important;
        }

        body.dark-mode .bg-purple-50 {
            background-color: rgba(168, 85, 247, 0.1) !important;
            border-color: rgba(168, 85, 247, 0.2) !important;
        }

        body.dark-mode .bg-green-50 {
            background-color: rgba(34, 197, 94, 0.1) !important;
            border-color: rgba(34, 197, 94, 0.2) !important;
        }

        body.dark-mode .bg-blue-50 {
            background-color: rgba(59, 130, 246, 0.1) !important;
            border-color: rgba(59, 130, 246, 0.2) !important;
        }

        body.dark-mode .bg-red-50 {
            background-color: rgba(239, 68, 68, 0.1) !important;
            border-color: rgba(239, 68, 68, 0.2) !important;
        }

        /* Latar Belakang Kartu Filter HCTS di Dark Mode */
        body.dark-mode .bg-gray-50\/50 {
            background-color: rgba(30, 41, 59, 0.4) !important;
            border-color: rgba(255, 255, 255, 0.05) !important;
        }

        body.dark-mode .bg-white\/50 {
            background-color: rgba(15, 23, 42, 0.4) !important;
            border-color: rgba(255, 255, 255, 0.05) !important;
        }

        /* Tombol Reset HCTS di Kartu Filter */
        body.dark-mode .bg-white.border-gray-100.rounded-2xl.text-gray-400 {
            background-color: rgba(30, 41, 59, 0.6) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        /* Penyesuaian Inventori HCTS di Dark Mode */
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

        /* Footer & Tombol Penyerahan HCTS di Dark Mode */
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
    <div x-data="{ 
            sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
            mobileOpen: false 
         }" x-init="$watch('sidebarCollapsed', value => localStorage.setItem('sidebarCollapsed', value))"
        class="flex h-screen bg-gray-100 dark:bg-slate-900 overflow-hidden">

        <!-- Mobile Backdrop -->
        <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click="mobileOpen = false"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-30 lg:hidden">
        </div>

        <!-- Sidebar -->
        @if(!$fullScreen)
            @include('layouts.navigation')
        @endif

        <!-- Main Content Container -->
        <div id="main-scroll-container" class="flex-1 flex flex-col overflow-y-auto overflow-x-hidden relative min-w-0">
            <!-- Top Header -->
            @if(!$fullScreen)
                <nav id="top-header" style="background: var(--theme-bg-header);"
                    class="px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between shrink-0 sticky top-0 z-50 w-full transition-all duration-500 ease-in-out border-b border-white/10 backdrop-blur-xl">
@else
                {{-- Minimal Header for Full Screen Mode --}}
                <nav id="top-header" style="background: var(--theme-bg-header);"
                    class="px-4 sm:px-6 h-16 flex items-center justify-between shrink-0 sticky top-0 z-50 w-full border-b border-white/10 backdrop-blur-xl">
@endif
                <div class="flex items-center gap-4">
                    <button @click="mobileOpen = !mobileOpen"
                        class="text-white/70 hover:text-white focus:outline-none lg:hidden transition-colors">
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

                @if(!$fullScreen)
                    <!-- Realtime Jam & Tanggal -->
                    <div class="hidden lg:flex items-center ml-auto mr-4 text-white/80 bg-white/5 dark:bg-slate-800/40 border border-white/10 dark:border-white/5 shadow-inner rounded-2xl px-4 py-1.5 hover:bg-white/10 transition-colors duration-300 group"
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
                            this.time = now.toLocaleTimeString('id-ID', optionsTime).replace(/[\.]/g, ':');
                        }
                    }">
                        <div
                            class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform duration-500">
                            <svg class="w-4 h-4 text-pink-300 drop-shadow-[0_0_8px_rgba(244,114,182,0.5)]" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex flex-col text-right justify-center">
                            <span class="text-[8px] font-black uppercase tracking-[0.2em] text-white/40 mb-0.5"
                                x-text="date"></span>
                            <div class="flex items-baseline gap-1">
                                <span class="text-sm font-black tracking-tighter text-white leading-none"
                                    x-text="time"></span>
                                <span class="text-[8px] font-black text-rose-400 uppercase tracking-widest">WIB</span>
                            </div>
                        </div>
                    </div>
                @endif

                @if(!$fullScreen)
                    <!-- Tombol buat ganti tema -->
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
                            window.dispatchEvent(new CustomEvent('theme-changed', { detail: { theme } }));
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
                @endif

                @if(!$fullScreen)
                    <!-- Notifikasi Pengemasan -->
                    @if(in_array(auth()->user()->role, ['admin', 'kemas', 'sortir', 'supervisor']))
                        <div class="flex items-center mr-4 relative" x-data="hcsNotification()" x-init="init()"
                            @click.away="open = false">
                            <button @click="open = !open"
                                class="relative p-2 border border-white/20 text-white/90 bg-white/10 hover:bg-white/20 focus:outline-none transition rounded-lg"
                                title="Notifikasi Pengemasan">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>

                                <template x-if="count > 0">
                                    <span
                                        class="absolute -top-1.5 -right-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-rose-500 text-[9px] font-bold text-white shadow-sm ring-2 ring-indigo-900 dark:ring-slate-900 border border-white/10"
                                        x-text="count">
                                    </span>
                                </template>
                            </button>

                            <audio id="hcs-notify-sound" preload="auto" style="display:none;">
                                <source src="/audio/cihuy.mp3" type="audio/mpeg">
                            </audio>

                            <div x-show="open" x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                                class="absolute right-0 top-full mt-3 w-[85vw] sm:w-[550px] bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700/50 overflow-hidden z-50 backdrop-blur-xl"
                                style="display: none;">

                                <div
                                    class="bg-indigo-50 dark:bg-gray-800/80 px-4 py-3 border-b border-indigo-100 dark:border-gray-700">
                                    <h3 class="text-sm font-bold text-indigo-900 dark:text-white flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-indigo-600 dark:text-indigo-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        HCS Siap Dikemas
                                    </h3>
                                </div>

                                <div class="max-h-80 overflow-y-auto p-2">
                                    <template x-if="count === 0">
                                        <div class="p-6 text-center text-sm font-medium text-gray-500 dark:text-gray-400">
                                            Tidak ada data HCS siap dikemas.
                                        </div>
                                    </template>
                                    <template x-for="(item, index) in data" :key="index">
                                        <a :href="`/pengemasan/create?tahun_anggaran=${item.tahun_anggaran}&tahun_emisi=${item.emisi}&pecahan=${item.pecahan}&batch=${item.batch}&seri=${item.seri}&pack_awal=${item.pack_awal}&pack_akhir=${item.pack_akhir}&max_pack_akhir=${item.pack_akhir}`"
                                            class="block p-4 mb-2 rounded-xl transition-all border border-transparent hover:border-indigo-200 dark:hover:border-indigo-500/50 hover:bg-indigo-50/50 dark:hover:bg-indigo-900/60 group">
                                            <div class="flex justify-between items-start mb-2">
                                                <div class="flex flex-col">
                                                    <span
                                                        class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-[0.2em] mb-0.5"
                                                        x-text="`Pecahan ${item.pecahan}`"></span>
                                                    <span
                                                        class="text-xs font-bold text-gray-900 dark:text-white group-hover:text-indigo-700 dark:group-hover:text-indigo-300 transition-colors"
                                                        x-text="`TA/TE: ${item.tahun_anggaran}/${item.emisi}`"></span>
                                                </div>
                                                <span
                                                    class="text-[11px] font-black px-3 py-1 rounded-lg bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/30"
                                                    x-text="`${item.jumlah_pack} PACK`"></span>
                                            </div>
                                            <div class="grid grid-cols-2 gap-4 mb-3">
                                                <div class="flex flex-col">
                                                    <span
                                                        class="text-[9px] text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider">Batch</span>
                                                    <span
                                                        class="text-xs font-bold text-gray-800 dark:text-gray-200 group-hover:text-gray-900 dark:group-hover:text-white"
                                                        x-text="item.batch"></span>
                                                </div>
                                                <div class="flex flex-col">
                                                    <span
                                                        class="text-[9px] text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider">Seri</span>
                                                    <span
                                                        class="text-xs font-bold text-gray-800 dark:text-gray-200 group-hover:text-gray-900 dark:group-hover:text-white"
                                                        x-text="item.seri"></span>
                                                </div>
                                            </div>
                                            <div
                                                class="flex items-center justify-between bg-gray-50 dark:bg-gray-900/50 group-hover:bg-indigo-100 dark:group-hover:bg-indigo-900/80 p-2 rounded-lg border border-gray-100 dark:border-gray-700 transition-colors">
                                                <div
                                                    class="text-[11px] font-black text-gray-600 dark:text-gray-400 group-hover:text-indigo-700 dark:group-hover:text-indigo-300">
                                                    Range: <span
                                                        class="bg-white dark:bg-gray-800 px-2 py-0.5 rounded border border-gray-200 dark:border-gray-600 ml-1"
                                                        x-text="`${item.pack_awal} - ${item.pack_akhir}`"></span>
                                                </div>
                                                <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transform group-hover:translate-x-1 transition-all"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                        d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                                </svg>
                                            </div>
                                        </a>
                                    </template>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

                @if(!$fullScreen)
                    <!-- User Profile Dropdown -->
                    <div class="flex items-center ml-auto">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="flex items-center gap-3 px-3 py-1.5 border border-white/10 dark:border-white/5 text-sm font-bold rounded-2xl text-white bg-white/5 hover:bg-white/10 focus:outline-none transition-all duration-300 group">
                                    <div
                                        class="hidden sm:block text-[11px] uppercase tracking-widest opacity-80 group-hover:opacity-100 transition-opacity">
                                        {{ Auth::user()->name }}
                                    </div>
                                    <div
                                        class="h-8 w-8 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-black border border-white/20 shadow-lg group-hover:scale-105 transition-transform duration-300">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    <svg class="w-4 h-4 text-white/40 group-hover:text-white/80 transition-colors"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
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
                @endif
            </nav>

            <!-- Page Content -->
            <main class="flex-1 min-w-0">
                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('scripts')



    <script>
        function hcsNotification() {
            return {
                open: false,
                count: parseInt(localStorage.getItem('hcs_last_count')) || 0,
                data: [],
                initialized: false,

                init() {
                    this.fetchData();
                    setInterval(() => this.fetchData(), 3000);

                    // Unlock sound on any interaction
                    const unlock = () => {
                        const audio = document.getElementById('hcs-notify-sound');
                        if (audio) {
                            audio.play().then(() => {
                                audio.pause();
                                audio.currentTime = 0;
                            }).catch(() => { });
                        }
                        document.removeEventListener('click', unlock);
                        document.removeEventListener('keydown', unlock);
                    };
                    document.addEventListener('click', unlock);
                    document.addEventListener('keydown', unlock);
                },

                async fetchData() {
                    try {
                        const response = await fetch('/notifications/hcs-ready', {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        if (!response.ok) return;
                        const json = await response.json();

                        if (json.status === 'success') {
                            const newCount = parseInt(json.count);
                            const audio = document.getElementById('hcs-notify-sound');

                            // Putar jika jumlah benar-benar bertambah dibandingkan terakhir kali (bahkan dari sesi sebelumnya)
                            if (newCount > this.count) {
                                if (audio) {
                                    audio.currentTime = 0;
                                    audio.play().catch(e => console.warn('Audio playback failed:', e));
                                }
                            }

                            this.count = newCount;
                            this.data = json.data;
                            localStorage.setItem('hcs_last_count', newCount);
                            this.initialized = true;
                        }
                    } catch (error) {
                        console.error('Notification error:', error);
                    }
                }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Handler Pesan Flash Sesi Global
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

            // Handler Konfirmasi Hapus Global
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