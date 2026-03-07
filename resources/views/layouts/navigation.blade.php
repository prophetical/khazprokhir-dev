<nav x-data="{ 
        sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
        mobileOpen: false,
        hcsOpen: {{ request()->routeIs('hcs-receiving.*', 'batch-tracking.*', 'reports.*') ? 'true' : 'false' }},
        sortingOpen: {{ request()->routeIs('hcs-sorting.*', 'hcs-sorting-reports.*') ? 'true' : 'false' }}
    }" 
    x-init="$watch('sidebarCollapsed', value => localStorage.setItem('sidebarCollapsed', value))"
    :class="sidebarCollapsed ? 'w-20' : 'w-64'"
    class="min-h-screen flex flex-col shrink-0 z-40 transition-all duration-300 ease-in-out relative border-none shadow-xl"
    style="background: linear-gradient(180deg, #1e40af 0%, #7c3aed 100%);">
    
    <!-- Toggle Button (Desktop) -->
    <button @click="sidebarCollapsed = !sidebarCollapsed" 
            class="absolute -right-3 top-20 bg-white border border-gray-200 rounded-full p-1 shadow-md hover:bg-gray-50 focus:outline-none z-50 hidden md:block">
        <svg :class="sidebarCollapsed ? 'rotate-180' : ''" class="w-4 h-4 text-indigo-600 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
    </button>

    <!-- Primary Navigation Menu Header -->
    <div class="px-4 pt-6 pb-6 border-b border-white/10 flex items-center" :class="sidebarCollapsed ? 'justify-center' : 'px-6'">
        <!-- Logo -->
        <a href="{{ route('dashboard') }}" class="flex items-center">
            <x-application-logo class="block h-8 w-auto fill-current text-white" />
            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="ml-3 font-bold text-white text-lg tracking-wider whitespace-nowrap uppercase">KHAZPRO</span>
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </div>
            <span x-show="!sidebarCollapsed" x-transition class="ml-3 text-sm font-medium whitespace-nowrap overflow-hidden">{{ __('Dashboard') }}</span>
        </a>

        <!-- HCS Receiving Group -->
        <div class="space-y-1">
            <button @click="hcsOpen = !hcsOpen; if(sidebarCollapsed) sidebarCollapsed = false;"
               class="w-full flex items-center justify-between py-2.5 px-3 rounded-lg transition-all duration-200 {{ request()->routeIs('hcs-receiving.*', 'batch-tracking.*', 'reports.*') ? 'text-white font-semibold bg-white/10' : 'text-white/70 hover:bg-white/10 hover:text-white' }}"
               title="Penerimaan HCS">
                <div class="flex items-center">
                    <div class="shrink-0 w-8 flex justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <span x-show="!sidebarCollapsed" x-transition class="ml-3 text-sm font-medium whitespace-nowrap overflow-hidden">Penerimaan HCS</span>
                </div>
                <svg x-show="!sidebarCollapsed" :class="hcsOpen ? 'rotate-180' : ''" class="w-3 h-3 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Sub-menu Items -->
            <div x-show="hcsOpen && !sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="pl-11 space-y-1">
                <a href="{{ route('hcs-receiving.index') }}" 
                   class="block py-2 text-[13px] transition-all duration-200 {{ request()->routeIs('hcs-receiving.index') ? 'text-white font-bold' : 'text-white/60 hover:text-white' }}">
                    Data Receiving
                </a>
                <a href="{{ route('batch-tracking.index') }}" 
                   class="block py-2 text-[13px] transition-all duration-200 {{ request()->routeIs('batch-tracking.index') ? 'text-white font-bold' : 'text-white/60 hover:text-white' }}">
                    Batch Tracking
                </a>
                <a href="{{ route('reports.index') }}" 
                   class="block py-2 text-[13px] transition-all duration-200 {{ request()->routeIs('reports.index') ? 'text-white font-bold' : 'text-white/60 hover:text-white' }}">
                    Laporan Receiving
                </a>
            </div>
        </div>

        <!-- HCS Sorting Group -->
        <div class="space-y-1 mt-2">
            <button @click="sortingOpen = !sortingOpen; if(sidebarCollapsed) sidebarCollapsed = false;"
               class="w-full flex items-center justify-between py-2.5 px-3 rounded-lg transition-all duration-200 {{ request()->routeIs('hcs-sorting.*', 'hcs-sorting-reports.*') ? 'text-white font-semibold bg-white/10' : 'text-white/70 hover:bg-white/10 hover:text-white' }}"
               title="Penyortiran HCS">
                <div class="flex items-center">
                    <div class="shrink-0 w-8 flex justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <!-- A different icon for sorting (e.g. server/stack) -->
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                    </div>
                    <span x-show="!sidebarCollapsed" x-transition class="ml-3 text-sm font-medium whitespace-nowrap overflow-hidden">Penyortiran HCS</span>
                </div>
                <svg x-show="!sidebarCollapsed" :class="sortingOpen ? 'rotate-180' : ''" class="w-3 h-3 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Sub-menu Items -->
            <div x-show="sortingOpen && !sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="pl-11 space-y-1">
                <a href="{{ route('hcs-sorting.index') }}" 
                   class="block py-2 text-[13px] transition-all duration-200 {{ request()->routeIs('hcs-sorting.*') ? 'text-white font-bold' : 'text-white/60 hover:text-white' }}">
                    Data Penyortiran
                </a>
                <a href="{{ route('hcs-sorting-reports.index') }}" 
                   class="block py-2 text-[13px] transition-all duration-200 {{ request()->routeIs('hcs-sorting-reports.*') ? 'text-white font-bold' : 'text-white/60 hover:text-white' }}">
                    Laporan Penyortiran
                </a>
            </div>
        </div>

    </div>

    <!-- Bottom Attribution -->
    <div x-show="!sidebarCollapsed" class="p-4 text-center">
        <span class="text-[10px] text-white/40 font-medium uppercase tracking-widest">© {{ date('Y') }} Khazprokhir</span>
    </div>

</nav>
