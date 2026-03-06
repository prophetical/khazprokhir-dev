<nav x-data="{ 
        sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
        mobileOpen: false
    }" 
    x-init="$watch('sidebarCollapsed', value => localStorage.setItem('sidebarCollapsed', value))"
    :class="sidebarCollapsed ? 'w-20' : 'w-64'"
    class="bg-white border-r border-gray-200 min-h-screen flex flex-col shrink-0 shadow-sm z-40 transition-all duration-300 ease-in-out relative">
    
    <!-- Toggle Button (Desktop) -->
    <button @click="sidebarCollapsed = !sidebarCollapsed" 
            class="absolute -right-3 top-20 bg-white border border-gray-200 rounded-full p-1 shadow-sm hover:bg-gray-50 focus:outline-none z-50 hidden md:block">
        <svg :class="sidebarCollapsed ? 'rotate-180' : ''" class="w-4 h-4 text-gray-600 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
    </button>

    <!-- Primary Navigation Menu Header -->
    <div class="px-4 pt-6 pb-6 border-b border-gray-100 flex items-center" :class="sidebarCollapsed ? 'justify-center' : 'px-6'">
        <!-- Logo -->
        <a href="{{ route('dashboard') }}" class="flex items-center">
            <x-application-logo class="block h-10 w-auto fill-current text-indigo-600" />
            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="ml-3 font-bold text-gray-800 text-xl tracking-wider whitespace-nowrap">KHAZPRO</span>
        </a>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 mt-6 space-y-1 px-3 overflow-y-auto overflow-x-hidden">
        
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" 
           class="flex items-center py-3 px-3 rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}"
           title="{{ __('Dashboard') }}">
            <div class="shrink-0 w-8 flex justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </div>
            <span x-show="!sidebarCollapsed" x-transition class="ml-3 whitespace-nowrap overflow-hidden">{{ __('Dashboard') }}</span>
        </a>

        <!-- HCS Receiving -->
        <a href="{{ route('hcs-receiving.index') }}" 
           class="flex items-center py-3 px-3 rounded-lg transition-all duration-200 {{ request()->routeIs('hcs-receiving.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}"
           title="{{ __('Penerimaan HCS') }}">
            <div class="shrink-0 w-8 flex justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
            </div>
            <span x-show="!sidebarCollapsed" x-transition class="ml-3 whitespace-nowrap overflow-hidden">{{ __('Penerimaan HCS') }}</span>
        </a>

        <!-- Batch Tracking -->
        <a href="{{ route('batch-tracking.index') }}" 
           class="flex items-center py-3 px-3 rounded-lg transition-all duration-200 {{ request()->routeIs('batch-tracking.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}"
           title="{{ __('Batch Tracking') }}">
            <div class="shrink-0 w-8 flex justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <span x-show="!sidebarCollapsed" x-transition class="ml-3 whitespace-nowrap overflow-hidden">{{ __('Batch Tracking') }}</span>
        </a>

        <!-- Reports -->
        <a href="{{ route('reports.index') }}" 
           class="flex items-center py-3 px-3 rounded-lg transition-all duration-200 {{ request()->routeIs('reports.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}"
           title="{{ __('Laporan Penerimaan HCS') }}">
            <div class="shrink-0 w-8 flex justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 2v-6m-9 9h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <span x-show="!sidebarCollapsed" x-transition class="ml-3 whitespace-nowrap overflow-hidden">{{ __('Laporan Penerimaan HCS') }}</span>
        </a>
    </div>

    <!-- Bottom Attribution (Optional, only show if not collapsed) -->
    <div x-show="!sidebarCollapsed" class="p-4 text-center">
        <span class="text-xs text-gray-400">© {{ date('Y') }} Khazprokhir</span>
    </div>

</nav>
