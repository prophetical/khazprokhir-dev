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
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex" style="background: #f0f4ff;">

            {{-- ===== LEFT PANEL: Branding ===== --}}
            <div class="hidden lg:flex lg:w-1/2 flex-col items-center justify-center relative overflow-hidden p-16"
                 style="background: linear-gradient(135deg, #1e40af 0%, #7c3aed 55%, #db2777 100%);">

                {{-- Decorative blobs --}}
                <div class="absolute top-0 right-0 w-80 h-80 rounded-full opacity-20 blur-3xl" style="background: radial-gradient(circle, #fff 0%, transparent 70%); transform: translate(30%, -30%);"></div>
                <div class="absolute bottom-0 left-0 w-96 h-96 rounded-full opacity-20 blur-3xl" style="background: radial-gradient(circle, #fff 0%, transparent 70%); transform: translate(-30%, 30%);"></div>

                {{-- Grid dots pattern --}}
                <div class="absolute inset-0 opacity-10"
                     style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 32px 32px;">
                </div>

                <div class="relative z-10 text-center text-white">
                    {{-- Logo --}}
                    <div class="flex justify-center mb-8">
                        <div class="w-20 h-20 bg-white/15 rounded-3xl flex items-center justify-center border border-white/20 shadow-2xl backdrop-blur-sm">
                            <x-application-logo class="w-12 h-12 fill-current text-white drop-shadow" />
                        </div>
                    </div>

                    <h1 class="text-4xl font-extrabold tracking-tight mb-3 drop-shadow-lg">Khazprokhir</h1>
                    <p class="text-white/70 text-lg font-medium mb-10">Management System</p>


                </div>
            </div>

            {{-- ===== RIGHT PANEL: Login Form ===== --}}
            <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
                <div class="w-full max-w-md">

                    {{-- Mobile logo (shown only on small screens) --}}
                    <div class="flex justify-center mb-8 lg:hidden">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg"
                             style="background: linear-gradient(135deg, #1e40af, #7c3aed);">
                            <x-application-logo class="w-9 h-9 fill-current text-white" />
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl shadow-xl shadow-indigo-100/60 border border-gray-100 px-8 py-10">
                        {{ $slot }}
                    </div>


                </div>
            </div>

        </div>
    </body>
</html>
