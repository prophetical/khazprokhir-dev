<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Pengemasan HCS') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @php
                    $pecahan = request('pecahan');
                    $borderColor = 'border-indigo-500'; // default
                    if ($pecahan === 'S') $borderColor = 'border-lime-500';
                    elseif ($pecahan === 'T') $borderColor = 'border-gray-500';
                    elseif ($pecahan === 'U') $borderColor = 'border-amber-600';
                    elseif ($pecahan === 'V') $borderColor = 'border-purple-500';
                    elseif ($pecahan === 'W') $borderColor = 'border-green-500';
                    elseif ($pecahan === 'X') $borderColor = 'border-blue-500';
                    elseif ($pecahan === 'Y') $borderColor = 'border-red-500';
                @endphp
                <div class="p-6 text-gray-900 border-t-4 {{ $borderColor }}">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Riwayat Pengemasan</h2>
                        <form method="GET" action="{{ route('pengemasan.data') }}" class="flex flex-wrap gap-3 items-center w-full md:w-auto">
                            @if(request('sort'))
                                <input type="hidden" name="sort" value="{{ request('sort') }}">
                                <input type="hidden" name="direction" value="{{ request('direction') }}">
                            @endif
                            
                            <!-- Filter Pecahan -->
                            <select name="pecahan" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm sm:text-sm px-3 py-2 w-full md:w-auto">
                                <option value="">Semua Pecahan</option>
                                <option value="S" {{ request('pecahan') == 'S' ? 'selected' : '' }}>S</option>
                                <option value="T" {{ request('pecahan') == 'T' ? 'selected' : '' }}>T</option>
                                <option value="U" {{ request('pecahan') == 'U' ? 'selected' : '' }}>U</option>
                                <option value="V" {{ request('pecahan') == 'V' ? 'selected' : '' }}>V</option>
                                <option value="W" {{ request('pecahan') == 'W' ? 'selected' : '' }}>W</option>
                                <option value="X" {{ request('pecahan') == 'X' ? 'selected' : '' }}>X</option>
                                <option value="Y" {{ request('pecahan') == 'Y' ? 'selected' : '' }}>Y</option>
                            </select>

                            <!-- Cari Data Umum -->
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Data" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm sm:text-sm px-3 py-2 w-full md:w-auto min-w-[200px]">
                            
                            <!-- Cari No Dus Spesifik -->
                            <div class="relative w-full md:w-auto">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-1">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                </span>
                                <input type="number" name="search_dus" value="{{ request('search_dus') }}" placeholder="Cari No Dus" class="pl-9 border-indigo-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm sm:text-sm px-3 py-2 w-full bg-indigo-50 placeholder-indigo-300 text-indigo-900 min-w-[180px]">
                            </div>

                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 w-full md:w-auto justify-center">
                                Cari
                            </button>
                            
                            @if(request('search') || request('pecahan') || request('search_dus'))
                                <a href="{{ route('pengemasan.data') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 w-full md:w-auto justify-center">Reset</a>
                            @endif
                        </form>
                    </div>

                    @php
                        $direction = request('direction') === 'asc' ? 'desc' : 'asc';
                        function sortIcon($column) {
                            if (request('sort') === $column) {
                                return request('direction') === 'asc' ? '↑' : '↓';
                            }
                            return '';
                        }
                    @endphp

                    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider bg-gray-50 border-b border-gray-200">
                                        <a href="{{ route('pengemasan.data', array_merge(request()->query(), ['sort' => 'tanggal_pengemasan', 'direction' => $direction])) }}" class="hover:text-indigo-600 flex items-center">
                                            Tanggal {{ sortIcon('tanggal_pengemasan') }}
                                        </a>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider bg-gray-50 border-b border-gray-200">Identitas</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider bg-gray-50 border-b border-gray-200">Pack Awal-Akhir</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider bg-gray-50 border-b border-gray-200">
                                        <a href="{{ route('pengemasan.data', array_merge(request()->query(), ['sort' => 'jumlah_dus', 'direction' => $direction])) }}" class="hover:text-indigo-600 flex items-center">
                                            Jml Dus {{ sortIcon('jumlah_dus') }}
                                        </a>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider bg-gray-50 border-b border-gray-200">No Dus</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider bg-gray-50 border-b border-gray-200">
                                        <a href="{{ route('pengemasan.data', array_merge(request()->query(), ['sort' => 'petugas', 'direction' => $direction])) }}" class="hover:text-indigo-600 flex items-center">
                                            Petugas {{ sortIcon('petugas') }}
                                        </a>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider bg-gray-50 border-b border-gray-200">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($pengemasans as $p)
                                    <tr class="hover:bg-indigo-50 transition-colors duration-200 group">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">
                                            {{ $p->tanggal_pengemasan->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-900">{{ $p->pecahan }} - {{ $p->batch }} - {{ $p->seri }}</div>
                                            <div class="text-xs text-gray-500">{{ $p->tahun_anggaran }} / {{ $p->tahun_emisi }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            <div class="mb-1">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $p->pack_awal }} - {{ $p->pack_akhir }}
                                                </span>
                                            </div>
                                            <div class="text-xs text-gray-500 font-bold ml-1">({{ $p->jumlah_pack }} pack)</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-purple-100 text-purple-800 font-black">
                                                {{ $p->jumlah_dus }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-bold text-center">
                                            {{ $p->dus_awal }} - {{ $p->dus_akhir }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 flex items-center space-x-2 mt-2">
                                            <div class="h-6 w-6 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600">
                                                {{ substr($p->user->name ?? '?', 0, 1) }}
                                            </div>
                                            <span>{{ $p->user->name ?? '-' }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('pengemasan.show', $p) }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-4 py-2 rounded-sm transition-colors duration-200 shadow-sm">
                                                Detail
                                                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                            </svg>
                                            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada pengemasan</h3>
                                            <p class="mt-1 text-sm text-gray-500">Mulai pengemasan dari menu Pengemasan HCS.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-8 border-t border-gray-100 pt-4">
                        {{ $pengemasans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
