<nav x-data="{ 
        hcsOpen: {{ request()->routeIs('hcs-receiving.*', 'batch-tracking.*', 'reports.*', 'rekomendasi-penerimaan.*') ? 'true' : 'false' }},
        sortingOpen: {{ request()->routeIs('hcs-sorting.*', 'hcs-sorting-reports.*', 'rekomendasi-penyortiran.*') ? 'true' : 'false' }},
        penyerahanBiOpen: {{ request()->routeIs('penyerahan-bi.*') ? 'true' : 'false' }},
        laporanOpen: {{ request()->routeIs('laporan-harian.*') ? 'true' : 'false' }},
        hctsOpen: {{ request()->routeIs('hcts-receiving.*', 'hcts-inventory.*') ? 'true' : 'false' }},
        penyerahanHctsOpen: {{ request()->routeIs('hcts-submission.*') ? 'true' : 'false' }}
    }"
    :class="[
        sidebarCollapsed ? 'w-20' : 'w-64',
        mobileOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
    ]"
    class="fixed lg:relative h-full flex flex-col shrink-0 z-40 transition-all duration-300 ease-in-out border-none shadow-xl transform lg:translate-x-0"
    x-cloak>

    <!-- Toggle Button (Desktop) -->
    <button @click="sidebarCollapsed = !sidebarCollapsed"
        class="absolute -right-3 top-20 bg-white border border-gray-200 rounded-full p-1 hover:bg-gray-50 focus:outline-none z-50 hidden md:block">
        <svg :class="sidebarCollapsed ? 'rotate-180' : ''"
            class="w-4 h-4 text-indigo-600 transition-transform duration-300" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
    </button>

    <!-- Primary Navigation Menu Header -->
    <div class="px-4 pt-6 pb-6 border-b border-white/10 flex items-center"
        :class="sidebarCollapsed ? 'justify-center' : 'px-6'">
        <!-- Logo -->
        <a href="{{ route('dashboard') }}" class="flex items-center">
            <x-application-logo class="block h-8 w-auto fill-current text-white" />
            <div x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                class="ml-3 flex flex-col justify-center">
                <span
                    class="font-bold text-white text-lg tracking-wider whitespace-nowrap uppercase leading-none mb-0.5">KHAZPRO</span>
                <span class="text-[9px] text-indigo-300 font-medium tracking-wide whitespace-nowrap italic">Presisi
                    mengelola, data terpercaya</span>
            </div>
        </a>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 mt-6 space-y-1 px-3 overflow-y-auto overflow-x-hidden">

        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
            class="flex items-center py-2.5 px-3 rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white font-semibold shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white' }}"
            title="{{ __('Dashboard') }}">
            <div class="shrink-0 w-8 flex justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </div>
            <span x-show="!sidebarCollapsed" x-transition
                class="ml-3 text-sm font-medium whitespace-nowrap overflow-hidden">{{ __('Dashboard') }}</span>
        </a>

        <!-- Pesan -->
        <a href="{{ route('messages.index') }}"
            class="flex items-center py-2.5 px-3 rounded-lg transition-all duration-200 {{ request()->routeIs('messages.*') ? 'bg-white/20 text-white font-semibold shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white' }}"
            title="Pesan">
            <div class="shrink-0 w-8 flex justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
            </div>
            <span x-show="!sidebarCollapsed" x-transition
                class="ml-3 text-sm font-medium whitespace-nowrap overflow-hidden">Pesan</span>
        </a>

        <!-- Laporan Harian Group -->
        <div class="space-y-1">
            <button @click="laporanOpen = !laporanOpen; if(sidebarCollapsed) sidebarCollapsed = false;"
                class="w-full flex items-center justify-between py-2.5 px-3 rounded-lg transition-all duration-200 {{ request()->routeIs('laporan-harian.*') ? 'text-white font-semibold bg-white/20 shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white' }}"
                title="Laporan Harian">
                <div class="flex items-center">
                    <div class="shrink-0 w-8 flex justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <span x-show="!sidebarCollapsed" x-transition
                        class="ml-3 text-sm font-medium whitespace-nowrap overflow-hidden">Laporan Harian</span>
                </div>
                <svg x-show="!sidebarCollapsed" :class="laporanOpen ? 'rotate-180' : ''"
                    class="w-3 h-3 transition-transform duration-200" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Sub-menu Items -->
            <div x-show="laporanOpen && !sidebarCollapsed" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                class="pl-11 space-y-1">
                <a href="{{ route('laporan-harian.index') }}"
                    class="block py-2 text-[13px] transition-all duration-200 {{ request()->routeIs('laporan-harian.index') ? 'text-white font-bold' : 'text-white/60 hover:text-white' }}">
                    Laporan Harian
                </a>
                <a href="{{ route('laporan-harian.realtime') }}"
                    class="block py-2 text-[13px] transition-all duration-200 {{ request()->routeIs('laporan-harian.realtime') ? 'text-white font-bold' : 'text-white/60 hover:text-white' }}">
                    Laporan Harian Realtime
                </a>
            </div>
        </div>

        <!-- HCS Receiving Group -->
        <div class="space-y-1">
            <button @click="hcsOpen = !hcsOpen; if(sidebarCollapsed) sidebarCollapsed = false;"
                class="w-full flex items-center justify-between py-2.5 px-3 rounded-lg transition-all duration-200 {{ request()->routeIs('hcs-receiving.*', 'batch-tracking.*', 'reports.*', 'rekomendasi-penerimaan.*') ? 'text-white font-semibold bg-white/20 shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white' }}"
                title="Penerimaan HCS">
                <div class="flex items-center">
                    <div class="shrink-0 w-8 flex justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.25 13.5h3.86a2.25 2.25 0 012.012 1.244l.256.512a2.25 2.25 0 002.013 1.244h3.218a2.25 2.25 0 002.013-1.244l.256-.512a2.25 2.25 0 012.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18v-4.162c0-2.24-1.815-4.062-4.062-4.062h-11.376c-2.247 0-4.062 1.822-4.062 4.062zM15 7.5l-3 3m0 0l-3-3m3 3v-7.5" />
                        </svg>
                    </div>
                    <span x-show="!sidebarCollapsed" x-transition
                        class="ml-3 text-sm font-medium whitespace-nowrap overflow-hidden">Penerimaan HCS</span>
                </div>
                <svg x-show="!sidebarCollapsed" :class="hcsOpen ? 'rotate-180' : ''"
                    class="w-3 h-3 transition-transform duration-200" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Sub-menu Items -->
            <div x-show="hcsOpen && !sidebarCollapsed" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                class="pl-11 space-y-1">
                <a href="{{ route('hcs-receiving.create') }}"
                    class="block py-2 text-[13px] transition-all duration-200 {{ request()->routeIs('hcs-receiving.create') ? 'text-white font-bold' : 'text-white/60 hover:text-white' }}">
                    Input Penerimaan HCS
                </a>
                <a href="{{ route('hcs-receiving.index') }}"
                    class="block py-2 text-[13px] transition-all duration-200 {{ request()->routeIs('hcs-receiving.index') ? 'text-white font-bold' : 'text-white/60 hover:text-white' }}">
                    Detail Penerimaan HCS
                </a>
                <a href="{{ route('rekomendasi-penerimaan.index') }}"
                    class="block py-2 text-[13px] transition-all duration-200 {{ request()->routeIs('rekomendasi-penerimaan.*') ? 'text-white font-bold' : 'text-white/60 hover:text-white' }}">
                    Rekomendasi Penerimaan HCS
                </a>
                <a href="{{ route('batch-tracking.index') }}"
                    class="block py-2 text-[13px] transition-all duration-200 {{ request()->routeIs('batch-tracking.index') ? 'text-white font-bold' : 'text-white/60 hover:text-white' }}">
                    Batch Tracking
                </a>
                <a href="{{ route('reports.index') }}"
                    class="block py-2 text-[13px] transition-all duration-200 {{ request()->routeIs('reports.index') ? 'text-white font-bold' : 'text-white/60 hover:text-white' }}">
                    Laporan Penerimaan HCS
                </a>
            </div>
        </div>

        <!-- HCS Sorting Group -->
        <div class="space-y-1 mt-2">
            <button @click="sortingOpen = !sortingOpen; if(sidebarCollapsed) sidebarCollapsed = false;"
                class="w-full flex items-center justify-between py-2.5 px-3 rounded-lg transition-all duration-200 {{ request()->routeIs('hcs-sorting.*', 'hcs-sorting-reports.*', 'rekomendasi-penyortiran.*') ? 'text-white font-semibold bg-white/20 shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white' }}"
                title="Penyortiran HCS">
                <div class="flex items-center">
                    <div class="shrink-0 w-8 flex justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <!-- A different icon for sorting (e.g. server/stack) -->
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                    </div>
                    <span x-show="!sidebarCollapsed" x-transition
                        class="ml-3 text-sm font-medium whitespace-nowrap overflow-hidden">Penyortiran HCS</span>
                </div>
                <svg x-show="!sidebarCollapsed" :class="sortingOpen ? 'rotate-180' : ''"
                    class="w-3 h-3 transition-transform duration-200" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Sub-menu Items -->
            <div x-show="sortingOpen && !sidebarCollapsed" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                class="pl-11 space-y-1">
                <a href="{{ route('hcs-sorting.index') }}"
                    class="block py-2 text-[13px] transition-all duration-200 {{ request()->routeIs('hcs-sorting.index', 'hcs-sorting.create', 'hcs-sorting.edit') ? 'text-white font-bold' : 'text-white/60 hover:text-white' }}">
                    Input & Data Penyortiran HCS
                </a>
                <a href="{{ route('rekomendasi-penyortiran.index') }}"
                    class="block py-2 text-[13px] transition-all duration-200 {{ request()->routeIs('rekomendasi-penyortiran.*') ? 'text-white font-bold' : 'text-white/60 hover:text-white' }}">
                    Rekomendasi Penyortiran
                </a>
                <a href="{{ route('hcs-sorting-reports.index') }}"
                    class="block py-2 text-[13px] transition-all duration-200 {{ request()->routeIs('hcs-sorting-reports.*') ? 'text-white font-bold' : 'text-white/60 hover:text-white' }}">
                    Laporan Penyortiran
                </a>
            </div>
        </div>

        <!-- Pengemasan Group -->
        <div class="space-y-1 mt-2 mb-4"
            x-data="{ pengemasanOpen: {{ request()->routeIs('pengemasan.*') ? 'true' : 'false' }} }">
            <button @click="pengemasanOpen = !pengemasanOpen; if(sidebarCollapsed) sidebarCollapsed = false;"
                class="w-full flex items-center justify-between py-2.5 px-3 rounded-lg transition-all duration-200 {{ request()->routeIs('pengemasan.*') ? 'text-white font-semibold bg-white/20 shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white' }}"
                title="Pengemasan HCS">
                <div class="flex items-center">
                    <div class="shrink-0 w-8 flex justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <span x-show="!sidebarCollapsed" x-transition
                        class="ml-3 text-sm font-medium whitespace-nowrap overflow-hidden">Pengemasan HCS</span>
                </div>
                <svg x-show="!sidebarCollapsed" :class="pengemasanOpen ? 'rotate-180' : ''"
                    class="w-3 h-3 transition-transform duration-200" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Sub-menu Items -->
            <div x-show="pengemasanOpen && !sidebarCollapsed" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                class="pl-11 space-y-1">
                <a href="{{ route('pengemasan.index') }}"
                    class="block py-2 text-[13px] transition-all duration-200 {{ request()->routeIs('pengemasan.index', 'pengemasan.create') ? 'text-white font-bold' : 'text-white/60 hover:text-white' }}">
                    Input Pengemasan HCS
                </a>
                <a href="{{ route('pengemasan.data') }}"
                    class="block py-2 text-[13px] transition-all duration-200 {{ request()->routeIs('pengemasan.data', 'pengemasan.show') ? 'text-white font-bold' : 'text-white/60 hover:text-white' }}">
                    Data Pengemasan HCS
                </a>
            </div>
        </div>

        <!-- Penyerahan ke BI Group -->
        <div class="space-y-1 mt-2 mb-4">
            <button @click="penyerahanBiOpen = !penyerahanBiOpen; if(sidebarCollapsed) sidebarCollapsed = false;"
                class="w-full flex items-center justify-between py-2.5 px-3 rounded-lg transition-all duration-200 {{ request()->routeIs('penyerahan-bi.*') ? 'text-white font-semibold bg-white/20 shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white' }}"
                title="Penyerahan ke BI">
                <div class="flex items-center">
                    <div class="shrink-0 w-8 flex justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7.5 7.5h-.75A2.25 2.25 0 004.5 9.75v7.5a2.25 2.25 0 002.25 2.25h7.5a2.25 2.25 0 002.25-2.25v-7.5a2.25 2.25 0 00-2.25-2.25h-.75m0-3l-3-3m0 0l-3 3m3-3v11.25m6-2.25h.75a2.25 2.25 0 012.25 2.25v7.5a2.25 2.25 0 01-2.25 2.25h-7.5a2.25 2.25 0 01-2.25-2.25v-.75" />
                        </svg>
                    </div>
                    <span x-show="!sidebarCollapsed" x-transition
                        class="ml-3 text-sm font-medium whitespace-nowrap overflow-hidden">Penyerahan ke BI</span>
                </div>
                <svg x-show="!sidebarCollapsed" :class="penyerahanBiOpen ? 'rotate-180' : ''"
                    class="w-3 h-3 transition-transform duration-200" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Sub-menu Items -->
            <div x-show="penyerahanBiOpen && !sidebarCollapsed" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                class="pl-11 space-y-1">
                <a href="{{ route('penyerahan-bi.create') }}"
                    class="block py-2 text-[13px] transition-all duration-200 {{ request()->routeIs('penyerahan-bi.create') ? 'text-white font-bold' : 'text-white/60 hover:text-white' }}">
                    Input Penyerahan
                </a>
                <a href="{{ route('penyerahan-bi.index') }}"
                    class="block py-2 text-[13px] transition-all duration-200 {{ request()->routeIs('penyerahan-bi.index') ? 'text-white font-bold' : 'text-white/60 hover:text-white' }}">
                    Laporan Penyerahan
                </a>
            </div>
        </div>

        <!-- HCTS Receiving Group -->
        <div class="space-y-1">
            <button @click="hctsOpen = !hctsOpen; if(sidebarCollapsed) sidebarCollapsed = false;"
                class="w-full flex items-center justify-between py-2.5 px-3 rounded-lg transition-all duration-200 {{ request()->routeIs('hcts-receiving.*', 'hcts-inventory.*') ? 'text-white font-semibold bg-white/20 shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white' }}"
                title="Penerimaan HCTS">
                <div class="flex items-center">
                    <div class="shrink-0 w-8 flex justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                    </div>
                    <span x-show="!sidebarCollapsed" x-transition
                        class="ml-3 text-sm font-medium whitespace-nowrap overflow-hidden">Penerimaan HCTS</span>
                </div>
                <svg x-show="!sidebarCollapsed" :class="hctsOpen ? 'rotate-180' : ''"
                    class="w-3 h-3 transition-transform duration-200" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Sub-menu Items -->
            <div x-show="hctsOpen && !sidebarCollapsed" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                class="pl-11 space-y-1">
                <a href="{{ route('hcts-receiving.create') }}"
                    class="block py-2 text-[13px] transition-all duration-200 {{ request()->routeIs('hcts-receiving.create') ? 'text-white font-bold' : 'text-white/60 hover:text-white' }}">
                    Input Penerimaan HCTS
                </a>
                <a href="{{ route('hcts-receiving.index') }}"
                    class="block py-2 text-[13px] transition-all duration-200 {{ request()->routeIs('hcts-receiving.index') ? 'text-white font-bold' : 'text-white/60 hover:text-white' }}">
                    Detail Penerimaan HCTS
                </a>
                <a href="{{ route('hcts-receiving.summary') }}"
                    class="block py-2 text-[13px] transition-all duration-200 {{ request()->routeIs('hcts-receiving.summary') ? 'text-white font-bold' : 'text-white/60 hover:text-white' }}">
                    HCS – HCTS Summary
                </a>
                <a href="{{ route('hcts-inventory.index') }}"
                    class="block py-2 text-[13px] transition-all duration-200 {{ request()->routeIs('hcts-inventory.index') ? 'text-white font-bold' : 'text-white/60 hover:text-white' }}">
                    Persediaan HCTS
                </a>
            </div>
        </div>

        <!-- Penyerahan HCTS Group -->
        <div class="space-y-1 mt-2 mb-4">
            <button @click="penyerahanHctsOpen = !penyerahanHctsOpen; if(sidebarCollapsed) sidebarCollapsed = false;"
                class="w-full flex items-center justify-between py-2.5 px-3 rounded-lg transition-all duration-200 {{ request()->routeIs('hcts-submission.*') ? 'text-white font-semibold bg-white/20 shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white' }}"
                title="Penyerahan HCTS">
                <div class="flex items-center">
                    <div class="shrink-0 w-8 flex justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </div>
                    <span x-show="!sidebarCollapsed" x-transition
                        class="ml-3 text-sm font-medium whitespace-nowrap overflow-hidden">Penyerahan HCTS</span>
                </div>
                <svg x-show="!sidebarCollapsed" :class="penyerahanHctsOpen ? 'rotate-180' : ''"
                    class="w-3 h-3 transition-transform duration-200" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Sub-menu Items -->
            <div x-show="penyerahanHctsOpen && !sidebarCollapsed" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                class="pl-11 space-y-1">
                <a href="{{ route('hcts-submission.create') }}"
                    class="block py-2 text-[13px] transition-all duration-200 {{ request()->routeIs('hcts-submission.create') ? 'text-white font-bold' : 'text-white/60 hover:text-white' }}">
                    Input Penyerahan HCTS
                </a>
                <a href="{{ route('hcts-submission.index') }}"
                    class="block py-2 text-[13px] transition-all duration-200 {{ request()->routeIs('hcts-submission.index') ? 'text-white font-bold' : 'text-white/60 hover:text-white' }}">
                    Detail Penyerahan HCTS
                </a>
            </div>
        </div>


        @if(auth()->user()->role === 'admin')
            <div class="pt-4 pb-2">
                <div x-show="!sidebarCollapsed"
                    class="px-3 text-[10px] font-bold text-indigo-200 uppercase tracking-[0.2em] mb-2 opacity-50">Admin
                    Panel</div>
                <a href="{{ route('targets.index') }}"
                    class="flex items-center py-2.5 px-3 rounded-lg transition-all duration-200 {{ request()->routeIs('targets.*') ? 'bg-white/20 text-white font-semibold shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white' }}"
                    title="Manajemen Target">
                    <div class="shrink-0 w-8 flex justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <span x-show="!sidebarCollapsed" x-transition
                        class="ml-3 text-sm font-medium whitespace-nowrap overflow-hidden">Manajemen Target</span>
                </a>
            </div>
        @endif

    </div>

    <!-- Bottom Attribution -->
    <div x-show="!sidebarCollapsed" class="p-4 text-center">
        <span class="text-[10px] text-white/40 font-medium uppercase tracking-widest">© {{ date('Y') }}
            Khazprokhir</span>
    </div>

</nav>