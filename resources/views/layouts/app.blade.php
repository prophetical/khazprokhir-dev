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
    </head>
    <body class="font-sans antialiased">
        <div class="flex h-screen bg-gray-100 overflow-hidden">
            <!-- Sidebar -->
            @include('layouts.navigation')

            <!-- Main Content Container -->
            <div id="main-scroll-container" class="flex-1 flex flex-col overflow-y-auto w-full relative">
                <!-- Top Header -->
                <nav id="top-header" class="bg-white px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between shrink-0 sticky top-0 z-50 w-full transition-all duration-300 ease-in-out">
                    <div class="flex items-center">
                        <button class="text-gray-500 hover:text-gray-700 focus:outline-none md:hidden mr-4">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <!-- Page Heading Title (if present) -->
                        @isset($header)
                            <div class="flex-1">
                                {{ $header }}
                            </div>
                        @else
                            <div class="flex-1"></div>
                        @endisset
                    </div>

                    <!-- User Profile Dropdown -->
                    <div class="flex items-center ml-auto">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:text-indigo-600 focus:outline-none transition ease-in-out duration-150">
                                    <div class="font-semibold mr-2">{{ Auth::user()->name }}</div>
                                    <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold border border-indigo-200">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
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
                <main class="flex-1">
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

                if(scrollContainer && header) {
                    scrollContainer.addEventListener('scroll', () => {
                        if (scrollContainer.scrollTop > 10) {
                            header.classList.add('shadow-md', 'h-14', 'border-b', 'border-gray-200');
                            header.classList.remove('h-20');
                        } else {
                            header.classList.remove('shadow-md', 'h-14', 'border-b', 'border-gray-200');
                            header.classList.add('h-20');
                        }
                    });
                }
            });
        </script>
    </body>
</html>
