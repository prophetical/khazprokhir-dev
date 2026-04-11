<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Penyablonan Dus') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @php
                $themeClasses = [
                    'S' => '#84cc16',
                    'T' => '#6b7280',
                    'U' => '#f59e0b',
                    'V' => '#a855f7',
                    'W' => '#10b981',
                    'X' => '#3b82f6',
                    'Y' => '#ef4444',
                ];
            @endphp

            <!-- Top Section: Real-time Stock -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <div class="md:col-span-1 bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-50 rounded-full transition-transform group-hover:scale-150 duration-500"></div>
                    <div class="relative z-10">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Persediaan</p>
                        <h4 class="text-3xl font-black text-gray-900 flex items-baseline">
                            {{ number_format($sisaStok, 0, ',', '.') }}
                            <span class="ml-2 text-sm font-medium text-gray-400">Pcs</span>
                        </h4>
                        <div class="mt-2 flex items-center">
                            @if($sisaStok <= 1000)
                                <span class="flex h-2 w-2 rounded-full bg-red-500 mr-2 animate-pulse"></span>
                                <span class="text-[10px] font-bold text-red-500 uppercase">Stok Menipis!</span>
                            @else
                                <span class="flex h-2 w-2 rounded-full bg-emerald-500 mr-2"></span>
                                <span class="text-[10px] font-bold text-emerald-500 uppercase">Stok Tersedia</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-3 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <form action="{{ route('penyablonan.laporan') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Cari Tanggal</label>
                            <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                                class="block w-full border-gray-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Filter Gilir</label>
                            <select name="gilir" class="block w-full border-gray-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">Semua Gilir</option>
                                <option value="Gilir 1" {{ request('gilir') == 'Gilir 1' ? 'selected' : '' }}>Gilir 1</option>
                                <option value="Gilir 2" {{ request('gilir') == 'Gilir 2' ? 'selected' : '' }}>Gilir 2</option>
                                <option value="Gilir 3" {{ request('gilir') == 'Gilir 3' ? 'selected' : '' }}>Gilir 3</option>
                            </select>
                        </div>
                        <div class="flex items-end gap-2 md:col-span-2">
                            <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-[10px] uppercase tracking-widest py-3 px-4 rounded-lg shadow-sm transition-all flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Filter Data
                            </button>
                            <a href="{{ route('penyablonan.laporan.print', request()->all()) }}" target="_blank" class="flex-1 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-100 font-bold text-[10px] uppercase tracking-widest py-3 px-4 rounded-lg transition-all flex items-center justify-center shadow-sm">
                                <svg class="w-3.5 h-3.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                Cetak PDF
                            </a>
                            <a href="{{ route('penyablonan.laporan') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-500 font-bold text-[10px] uppercase tracking-widest py-3 px-4 rounded-lg transition-all flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table Data Laporan -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-black text-gray-800 tracking-tight">Data Aktivitas Penyablonan</h3>
                        <div class="text-[10px] font-bold text-gray-400 bg-gray-50 px-3 py-1.5 rounded-full border border-gray-100 uppercase tracking-tighter italic">
                            Sisa Persediaan Blanko (Belum Tersablon): <span class="text-indigo-600 ml-1">{{ number_format($sisaStok, 0, ',', '.') }} Pcs</span>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 uppercase tracking-widest">
                                <tr>
                                    <th class="px-4 py-4 text-left text-[10px] font-bold text-gray-400">Tanggal</th>
                                    <th class="px-4 py-4 text-center text-[10px] font-bold text-gray-400">Gilir</th>
                                    <th class="px-4 py-4 text-center text-[10px] font-bold text-gray-400">Pecahan</th>
                                    <th class="px-4 py-4 text-center text-[10px] font-bold text-gray-400">Emisi</th>
                                    <th class="px-4 py-4 text-center text-[10px] font-bold text-gray-400">TA</th>
                                    <th class="px-4 py-4 text-center text-[10px] font-bold text-gray-400">No Dus Awal</th>
                                    <th class="px-4 py-4 text-center text-[10px] font-bold text-gray-400">No Dus Akhir</th>
                                    <th class="px-4 py-4 text-right text-[10px] font-bold text-gray-400 pr-6">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100 italic transition-all duration-300">
                                @forelse($data as $row)
                                <tr class="hover:bg-indigo-50/20 group transition-colors">
                                    <td class="px-4 py-4 whitespace-nowrap text-xs font-bold text-gray-800">{{ $row->tanggal->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap text-center">
                                        <span class="text-[10px] font-bold text-gray-500 uppercase">{{ $row->gilir }}</span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-xs font-black text-white shadow-sm ring-2 ring-white ring-offset-1" style="background-color: {{ $themeClasses[$row->pecahan] }}">
                                            {{ $row->pecahan }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-center text-sm font-bold text-gray-700">{{ $row->te }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap text-center text-sm font-bold text-gray-700">{{ $row->ta }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap text-center">
                                        <div class="text-xs font-black text-indigo-600 bg-indigo-50 py-1 px-3 rounded-md inline-block">
                                            {{ $row->no_awal }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-center">
                                        <div class="text-xs font-black text-gray-600 bg-gray-50 py-1 px-3 rounded-md inline-block">
                                            {{ $row->no_akhir }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-right pr-6 font-black text-gray-900 group-hover:text-indigo-600 transition-colors">
                                        {{ number_format($row->jumlah, 0, ',', '.') }} Dus
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-sm text-gray-400 italic">Data tidak ditemukan. Silakan gunakan filter lain.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $data->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
