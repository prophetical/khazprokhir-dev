<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Profil Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Profile Info Section -->
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl border border-gray-100 p-1">
                <div class="bg-gray-50/50 rounded-[1.25rem] p-6 sm:p-8">
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            <!-- Password Update Section -->
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl border border-gray-100 p-1">
                <div class="bg-gray-50/50 rounded-[1.25rem] p-6 sm:p-8">
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            <!-- Delete Account Section -->
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl border border-gray-100 p-1">
                <div class="bg-gray-50/50 rounded-[1.25rem] p-6 sm:p-8">
                    <div class="max-w-xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

