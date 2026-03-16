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
            --theme-bg-header: linear-gradient(135deg, #1e40af 0%, #7c3aed 55%, #db2777 100%);
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
            --theme-bg-sidebar: #1B2638;
            /* Sidebar background */
            --theme-bg-header: #243047;
            /* Header background */
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
        * {
            box-shadow: none !important;
            text-shadow: none !important;
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
                class="px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between shrink-0 sticky top-0 z-50 w-full transition-all duration-300 ease-in-out border-b border-white/10">
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
                <div class="hidden xl:flex items-center ml-auto mr-4 text-white/80 bg-white/10 border border-white/20 shadow-sm rounded-lg px-3 py-1.5"
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
                    <svg class="w-4 h-4 text-pink-300 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="flex flex-col text-right justify-center mt-0.5">
                        <span class="text-[9px] font-bold uppercase tracking-wider text-white/50" x-text="date"></span>
                        <span class="text-sm font-black tracking-tight text-white leading-none"
                            x-text="time + ' WIB'"></span>
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
                                class="flex items-center px-3 py-2 border border-white/20 text-sm leading-4 font-medium rounded-lg text-white/90 bg-white/10 hover:bg-white/20 focus:outline-none transition ease-in-out duration-150">
                                <div class="font-semibold mr-2">{{ Auth::user()->name }}</div>
                                <div
                                    class="h-8 w-8 rounded-full bg-white/20 flex items-center justify-center text-white font-bold border border-white/30 drop-shadow">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4 text-white/60" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
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
                        header.classList.add('shadow-md', 'h-14', 'border-gray-200');
                        header.classList.remove('h-20', 'border-white/10');
                        isShrunk = true;
                    } else if (scrollContainer.scrollTop <= 10 && isShrunk) {
                        header.classList.remove('shadow-md', 'h-14', 'border-gray-200');
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