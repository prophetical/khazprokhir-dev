<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Penyortiran HCS') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-900 uppercase">Total Jumlah Pack Siap Sortir</h3>
                    </div>

                    @php
                        $colorMap = [
                            'S' => 'bg-yellow-200 border-yellow-400 text-yellow-900',   // 1k Kuning Kehijauan
                            'T' => 'bg-gray-200 border-gray-400 text-gray-900',       // 2k Abu-abu
                            'U' => 'bg-amber-100 border-amber-300 text-amber-900',    // 5k Cokelat Kekuningan
                            'V' => 'bg-purple-100 border-purple-300 text-purple-900',   // 10k Ungu
                            'W' => 'bg-green-100 border-green-300 text-green-900',    // 20k Hijau
                            'X' => 'bg-blue-100 border-blue-300 text-blue-900',      // 50k Biru
                            'Y' => 'bg-red-100 border-red-300 text-red-900',       // 100k Merah
                        ];
                    @endphp

                    <!-- SUMMARY CARDS -->
                    <div class="mb-8 space-y-4">
                        <!-- Row 1: S, T, U, V -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            @foreach(['S', 'T', 'U', 'V'] as $p)
                                <div class="{{ $colorMap[$p] }} border p-4 rounded-lg shadow-sm">
                                    <div class="text-sm font-semibold opacity-80 mb-1">Pecahan {{ $p }}</div>
                                    <div class="text-2xl font-bold">{{ number_format($summaries[$p] ?? 0, 0, ',', '.') }} <span class="text-sm font-normal opacity-70">Pack</span></div>
                                    <div class="text-sm font-medium mt-1 pt-1 border-t border-black/10">
                                        {{ number_format(($summaries[$p] ?? 0) * 45000, 0, ',', '.') }} Bilyet
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!-- Row 2: W, X, Y & Total -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            @foreach(['W', 'X', 'Y'] as $p)
                                <div class="{{ $colorMap[$p] }} border p-4 rounded-lg shadow-sm">
                                    <div class="text-sm font-semibold opacity-80 mb-1">Pecahan {{ $p }}</div>
                                    <div class="text-2xl font-bold">{{ number_format($summaries[$p] ?? 0, 0, ',', '.') }} <span class="text-sm font-normal opacity-70">Pack</span></div>
                                    <div class="text-sm font-medium mt-1 pt-1 border-t border-black/10">
                                        {{ number_format(($summaries[$p] ?? 0) * 45000, 0, ',', '.') }} Bilyet
                                    </div>
                                </div>
                            @endforeach
                            <!-- TOTAL CARD -->
                            <div class="bg-gray-900 text-white border-gray-800 border p-4 rounded-lg shadow-md">
                                <div class="text-sm font-semibold text-gray-300 mb-1">Total Keseluruhan</div>
                                <div class="text-2xl font-bold">{{ number_format($totalAllPacks, 0, ',', '.') }} <span class="text-sm font-normal text-gray-400">Pack</span></div>
                                <div class="text-sm font-medium mt-1 pt-1 border-t border-gray-700">
                                    {{ number_format($totalAllPacks * 45000, 0, ',', '.') }} Bilyet
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <!-- SEARCH & FILTER FORM -->
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <form method="GET" action="{{ route('hcs-sorting.index') }}" class="flex flex-col md:flex-row gap-4 items-end">
                            <div class="w-full md:w-1/4">
                                <x-input-label for="pecahan" :value="__('Pecahan')" />
                                <select name="pecahan" id="pecahan" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Semua Pecahan</option>
                                    @foreach(['S', 'T', 'U', 'V', 'W', 'X', 'Y'] as $p)
                                        <option value="{{ $p }}" {{ request('pecahan') == $p ? 'selected' : '' }}>{{ $p }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-full md:w-1/4">
                                <x-input-label for="batch" :value="__('Batch')" />
                                <x-text-input id="batch" class="block mt-1 w-full" type="text" name="batch" :value="request('batch')" placeholder="Cari Batch" />
                            </div>
                            <div class="w-full md:w-1/4">
                                <x-input-label for="seri" :value="__('Seri')" />
                                <x-text-input id="seri" class="block mt-1 w-full" type="text" name="seri" :value="request('seri')" placeholder="Cari Seri" />
                            </div>
                            <div class="w-full md:w-1/4 flex gap-2">
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Cari
                                </button>
                                <a href="{{ route('hcs-sorting.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 text-center">
                                    Reset
                                </a>
                            </div>
                        </form>
                    </div>

                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900 border-l-4 border-indigo-600 pl-2">Daftar Data Siap Sortir</h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pecahan
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Batch
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Seri
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total Pack Siap Sortir
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($AvailableGroups as $group)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $group->pecahan }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $group->batch }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $group->seri }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $group->total_pack }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('hcs-sorting.create', ['pecahan' => $group->pecahan, 'batch' => $group->batch, 'seri' => $group->seri]) }}" 
                                               class="inline-flex items-center px-3 py-1.5 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                Pilih & Sortir
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Tidak ada data HCS yang siap disortir.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $AvailableGroups->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
