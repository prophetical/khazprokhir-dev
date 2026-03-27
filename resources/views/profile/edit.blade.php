<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-xl text-gray-800 dark:text-white leading-tight tracking-tighter">
            {{ __('Pengaturan Akun') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

            <!-- Compact Profile Header -->
            <div
                class="relative overflow-hidden bg-white dark:bg-gray-900/50 backdrop-blur-xl rounded-[2rem] p-6 shadow-2xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-white/5 transition-all duration-500 group">
                <!-- Decoration -->
                <div
                    class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl group-hover:bg-indigo-500/20 transition-all duration-700">
                </div>

                <div class="relative flex flex-col md:flex-row items-center gap-6">
                    <div class="relative">
                        <div
                            class="w-20 h-20 rounded-2xl bg-gradient-to-br from-indigo-600 to-purple-600 p-1 shadow-xl shadow-indigo-200 dark:shadow-indigo-900/20 rotate-3 group-hover:rotate-0 transition-all duration-500">
                            <div
                                class="w-full h-full bg-white dark:bg-gray-900 rounded-xl flex items-center justify-center overflow-hidden border-2 border-white dark:border-gray-800">
                                <span
                                    class="text-2xl font-black bg-gradient-to-br from-indigo-600 to-purple-600 bg-clip-text text-transparent uppercase">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </span>
                            </div>
                        </div>
                        <div
                            class="absolute -bottom-1 -right-1 w-7 h-7 bg-indigo-500 rounded-xl flex items-center justify-center text-white shadow-lg border-2 border-white dark:border-gray-900 group-hover:scale-110 transition-transform duration-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </div>

                    <div class="text-center md:text-left flex-1">
                        <div
                            class="inline-flex items-center px-2.5 py-1 rounded-full bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-[9px] font-black uppercase tracking-widest mb-2 border border-indigo-100 dark:border-indigo-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 mr-2 animate-pulse"></span>
                            {{ auth()->user()->role ?? 'Pengguna Aktif' }}
                        </div>
                        <h1 class="text-xl font-black text-gray-900 dark:text-white tracking-tighter mb-0.5">
                            {{ auth()->user()->name }}</h1>
                        <p
                            class="text-gray-500 dark:text-gray-400 font-medium text-xs flex items-center justify-center md:justify-start gap-2">
                            <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                            </svg>
                            {{ auth()->user()->email }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Profile Information Card -->
                <div
                    class="bg-white dark:bg-gray-900/50 backdrop-blur-xl rounded-[2rem] shadow-2xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-white/5 p-6 transition-all duration-500 hover:shadow-indigo-500/5">
                    <div class="h-full">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Password Card -->
                <div
                    class="bg-white dark:bg-gray-900/50 backdrop-blur-xl rounded-[2rem] shadow-2xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-white/5 p-6 transition-all duration-500 hover:shadow-indigo-500/5">
                    <div class="h-full">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            <!-- Danger Zone - Delete User -->
            @if(request()->has('danger'))
                <div
                    class="bg-rose-50/50 dark:bg-rose-950/20 backdrop-blur-xl rounded-[2rem] border border-rose-100 dark:border-rose-900/50 p-6">
                    @include('profile.partials.delete-user-form')
                </div>
            @endif
        </div>
    </div>
</x-app-layout>