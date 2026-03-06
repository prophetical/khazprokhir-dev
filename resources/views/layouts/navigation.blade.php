<nav x-data="{ open: false }" class="bg-white border-r border-gray-200 w-64 min-h-screen flex flex-col shrink-0 shadow-sm z-20">
    <!-- Primary Navigation Menu -->
    <div class="px-4 sm:px-6 lg:px-8 pt-6 pb-4 border-b border-gray-100">
        <!-- Logo -->
        <div class="flex items-center justify-center shrink-0">
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center">
                <x-application-logo class="block h-12 w-auto fill-current text-indigo-600 mb-2" />
                <span class="font-bold text-gray-800 text-lg tracking-wider">KHAZPRO</span>
            </a>
        </div>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 mt-6 space-y-2 px-4 overflow-y-auto">
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="block w-full py-3 px-4 rounded-md transition-colors {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            {{ __('Dashboard') }}
        </x-nav-link>
        <x-nav-link :href="route('hcs-receiving.index')" :active="request()->routeIs('hcs-receiving.*')" class="block w-full py-3 px-4 rounded-md transition-colors {{ request()->routeIs('hcs-receiving.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            {{ __('HCS Receiving') }}
        </x-nav-link>
        <x-nav-link :href="route('batch-tracking.index')" :active="request()->routeIs('batch-tracking.*')" class="block w-full py-3 px-4 rounded-md transition-colors {{ request()->routeIs('batch-tracking.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            {{ __('Batch Tracking') }}
        </x-nav-link>
        <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')" class="block w-full py-3 px-4 rounded-md transition-colors {{ request()->routeIs('reports.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            {{ __('Reports') }}
        </x-nav-link>
    </div>

</nav>
