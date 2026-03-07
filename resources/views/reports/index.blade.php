<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Penerimaan HCS') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Summary Cards per Pecahan (Two Rows) -->
            <div class="space-y-4 mb-8">
                @php
                    $pecahanMeta = [
                        'S' => ['color' => 'bg-stone-600',   'label' => 'Rp1.000'],
                        'T' => ['color' => 'bg-slate-500',   'label' => 'Rp2.000'],
                        'U' => ['color' => 'bg-orange-500',  'label' => 'Rp5.000'],
                        'V' => ['color' => 'bg-purple-600',  'label' => 'Rp10.000'],
                        'W' => ['color' => 'bg-green-600',   'label' => 'Rp20.000'],
                        'X' => ['color' => 'bg-blue-600',    'label' => 'Rp50.000'],
                        'Y' => ['color' => 'bg-red-600',     'label' => 'Rp100.000'],
                    ];

                    $row1 = ['S', 'T', 'U', 'V'];
                    $row2 = ['W', 'X', 'Y'];
                @endphp

                <!-- Row 1: S, T, U, V -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($row1 as $key)
                    @php $meta = $pecahanMeta[$key]; @endphp
                    <a href="{{ route('reports.index', ['pecahan' => $key]) }}" class="bg-white overflow-hidden shadow-sm rounded-xl border {{ isset($pecahan) && $pecahan == $key ? 'border-indigo-500 ring-2 ring-indigo-200' : 'border-gray-100' }} p-4 flex flex-col items-center group hover:shadow-md transition-all">
                        <div class="{{ $meta['color'] }} w-10 h-10 rounded-lg flex items-center justify-center text-white font-bold text-lg mb-2 shadow-sm group-hover:scale-110 transition-transform">
                            {{ $key }}
                        </div>
                        <div class="text-[10px] text-gray-400 font-medium uppercase tracking-wider mb-1">{{ $meta['label'] }}</div>
                        <div class="text-xl font-bold text-gray-800">
                            {{ number_format($globalTotalsPerPecahan[$key] ?? 0, 0, ',', '.') }}
                        </div>
                    </a>
                    @endforeach
                </div>

                <!-- Row 2: W, X, Y, TOTAL -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($row2 as $key)
                    @php $meta = $pecahanMeta[$key]; @endphp
                    <a href="{{ route('reports.index', ['pecahan' => $key]) }}" class="bg-white overflow-hidden shadow-sm rounded-xl border {{ isset($pecahan) && $pecahan == $key ? 'border-indigo-500 ring-2 ring-indigo-200' : 'border-gray-100' }} p-4 flex flex-col items-center group hover:shadow-md transition-all">
                        <div class="{{ $meta['color'] }} w-10 h-10 rounded-lg flex items-center justify-center text-white font-bold text-lg mb-2 shadow-sm group-hover:scale-110 transition-transform">
                            {{ $key }}
                        </div>
                        <div class="text-[10px] text-gray-400 font-medium uppercase tracking-wider mb-1">{{ $meta['label'] }}</div>
                        <div class="text-xl font-bold text-gray-800">
                            {{ number_format($globalTotalsPerPecahan[$key] ?? 0, 0, ',', '.') }}
                        </div>
                    </a>
                    @endforeach

                    <!-- Grand Total Card -->
                    <a href="{{ route('reports.index') }}" class="bg-indigo-600 overflow-hidden shadow-lg rounded-xl p-4 flex flex-col items-center group hover:bg-indigo-700 transition-colors {{ !isset($pecahan) && !isset($startDate) && !isset($gilir) ? 'ring-4 ring-indigo-200' : '' }}">
                        <div class="bg-white/20 w-10 h-10 rounded-lg flex items-center justify-center text-white font-bold text-xs mb-2 shadow-sm group-hover:scale-110 transition-transform">
                            ALL
                        </div>
                        <div class="text-[10px] text-indigo-100 font-medium uppercase tracking-wider mb-1">Total Penerimaan</div>
                        <div class="text-xl font-bold text-white">
                            {{ number_format($globalGrandTotal, 0, ',', '.') }}
                        </div>
                    </a>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl mb-6 border border-gray-100">
                <div class="p-6">
                    <form action="{{ route('reports.index') }}" method="GET">
                        @if(isset($pecahan))
                            <input type="hidden" name="pecahan" value="{{ $pecahan }}">
                        @endif
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6 items-end">
                            
                            <!-- Date Range -->
                            <div class="md:col-span-2 lg:col-span-2 grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="start_date" value="Dari Tanggal" class="mb-2 text-gray-500 font-medium" />
                                    <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full border-gray-200 rounded-lg shadow-sm px-3 py-2 text-sm bg-gray-50/50 hover:bg-white focus:bg-white transition-all" value="{{ $startDate }}" />
                                </div>
                                <div>
                                    <x-input-label for="end_date" value="Sampai Tanggal" class="mb-2 text-gray-500 font-medium" />
                                    <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block w-full border-gray-200 rounded-lg shadow-sm px-3 py-2 text-sm bg-gray-50/50 hover:bg-white focus:bg-white transition-all" value="{{ $endDate }}" />
                                </div>
                            </div>

                            <!-- Gilir Filter -->
                            <div class="col-span-1">
                                <x-input-label for="gilir" value="Gilir (Shift)" class="mb-2 text-gray-500 font-medium" />
                                <div class="relative group">
                                    <select id="gilir" name="gilir" class="appearance-none mt-1 block w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm pl-3 pr-10 py-2.5 text-sm transition-all bg-gray-50/50 hover:bg-white cursor-pointer">
                                        <option value="">Semua Gilir</option>
                                        <option value="Gilir 1" {{ $gilir == 'Gilir 1' ? 'selected' : '' }}>Gilir 1</option>
                                        <option value="Gilir 2" {{ $gilir == 'Gilir 2' ? 'selected' : '' }}>Gilir 2</option>
                                        <option value="Gilir 3" {{ $gilir == 'Gilir 3' ? 'selected' : '' }}>Gilir 3</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400 group-hover:text-indigo-500 transition-colors">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="col-span-1 flex flex-col gap-2">
                                <div class="grid grid-cols-2 gap-2">
                                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-200 shadow-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                        Filter
                                    </button>
                                    <a href="{{ route('reports.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-gray-100 border border-gray-200 rounded-lg font-bold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 transition duration-200 shadow-sm" title="Reset Semua Filter">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                        Reset
                                    </a>
                                </div>
                                
                                <div class="grid grid-cols-3 gap-2">
                                    <a href="{{ route('reports.export', ['start_date' => $startDate, 'end_date' => $endDate, 'gilir' => $gilir, 'pecahan' => $pecahan ?? '']) }}" 
                                       class="inline-flex items-center justify-center p-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-colors" title="Export Excel (CSV)">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    </a>
                                    <a href="{{ route('reports.print', ['start_date' => $startDate, 'end_date' => $endDate, 'gilir' => $gilir, 'pecahan' => $pecahan ?? '']) }}" 
                                       target="_blank"
                                       class="inline-flex items-center justify-center p-2 bg-rose-500 text-white rounded-lg hover:bg-rose-600 transition-colors" title="Export PDF">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                    </a>
                                    <a href="{{ route('reports.print', ['start_date' => $startDate, 'end_date' => $endDate, 'gilir' => $gilir, 'pecahan' => $pecahan ?? '', 'autoprint' => 1]) }}" 
                                       target="_blank"
                                       class="inline-flex items-center justify-center p-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition-colors" title="Cetak Laporan">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 00-2 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Report Data Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-6 text-gray-900">
                    
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center">
                            <span class="bg-indigo-100 text-indigo-700 p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 2v-6m-9 9h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </span>
                            Laporan Penerimaan Harian
                            @if(isset($pecahan))
                                <span class="ml-2 text-indigo-500">(Pecahan {{ $pecahan }})</span>
                            @endif
                        </h3>
                        <div class="flex items-center text-sm font-medium text-gray-500 bg-gray-50 px-4 py-2 rounded-full border border-gray-100">
                            <svg class="w-4 h-4 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            @if($startDate && $endDate)
                                {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} — {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
                            @else
                                Semua Tanggal
                            @endif
                            @if($gilir)
                                <span class="mx-2 text-gray-300">|</span>
                                <span class="text-indigo-600 font-bold uppercase">{{ $gilir }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No Bon</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pecahan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gilir</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mesin</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batch/Seri</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Operator</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($data as $row)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $row->nomor_bon }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $row->pecahan }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($row->jumlah, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $row->gilir }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $row->mesin }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $row->supplier }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $row->batch }} / {{ $row->seri }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $row->user->name ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">Tidak ada data ditemukan untuk filter ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $data->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
