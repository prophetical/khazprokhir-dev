<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Production Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Stat 1 -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 flex flex-col items-center">
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wide">HCS Received Today</div>
                        <div class="mt-2 text-3xl font-extrabold text-indigo-600">{{ $totalHcsToday }}</div>
                    </div>
                </div>
                
                <!-- Stat 2 -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 flex flex-col items-center">
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wide">Bilyet Processed</div>
                        <div class="mt-2 text-3xl font-extrabold text-blue-600">{{ number_format($totalBilyetToday, 0, ',', '.') }}</div>
                    </div>
                </div>

                <!-- Stat 3 -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 flex flex-col items-center">
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wide">Packs Processed</div>
                        <div class="mt-2 text-3xl font-extrabold text-green-600">{{ number_format($totalPacksToday, 0, ',', '.') }}</div>
                    </div>
                </div>
                
                <!-- Stat 4 -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 flex flex-col items-center">
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wide">Pack Usage</div>
                        <div class="mt-2 text-3xl font-extrabold text-purple-600">{{ $packUsagePercentage }}%</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Supplier Distribution Chart (Simple CSS implementation) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Supplier Distribution</h3>
                        
                        @php
                            $totalSuppliers = $cutpackCount + $rikyetCount;
                            $cutpackPct = $totalSuppliers > 0 ? ($cutpackCount / $totalSuppliers) * 100 : 0;
                            $rikyetPct = $totalSuppliers > 0 ? ($rikyetCount / $totalSuppliers) * 100 : 0;
                        @endphp

                        @if ($totalSuppliers > 0)
                            <div class="space-y-4">
                                <div>
                                    <div class="flex justify-between items-end mb-1">
                                        <span class="text-sm font-semibold text-blue-600">Cutpack ({{ $cutpackCount }} packs)</span>
                                        <span class="text-sm font-semibold text-blue-600">{{ round($cutpackPct, 1) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-4">
                                        <div class="bg-blue-500 h-4 rounded-full" style="width: {{ $cutpackPct }}%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between items-end mb-1">
                                        <span class="text-sm font-semibold text-green-600">Rikyet ({{ $rikyetCount }} packs)</span>
                                        <span class="text-sm font-semibold text-green-600">{{ round($rikyetPct, 1) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-4">
                                        <div class="bg-green-500 h-4 rounded-full" style="width: {{ $rikyetPct }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center text-gray-500 py-8">
                                Belum ada data supplier hari ini.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Activity or Information -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">System Status</h3>
                        <p class="text-gray-600 mb-4">Khazprokhir Inventory and Production Management System berjalan normal.</p>
                        
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">
                                        Role anda: <strong>{{ ucfirst(auth()->user()->role) }}</strong>. 
                                        Pastikan data yang diinput sesuai dengan bilyet fisik.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
