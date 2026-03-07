<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Penyortiran HCS') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 border-l-4 border-indigo-600 pl-2 mb-6">Filter Laporan</h3>
                    
                    <form method="GET" action="{{ route('hcs-sorting-reports.index') }}" class="mb-6 grid grid-cols-1 md:grid-cols-5 gap-4 items-end bg-gray-50 p-4 rounded-md border border-gray-100">
                        <div>
                            <x-input-label for="tanggal_dari" :value="__('Tanggal Dari')" />
                            <x-text-input id="tanggal_dari" class="block mt-1 w-full text-sm" type="date" name="tanggal_dari" :value="request('tanggal_dari')" />
                        </div>
                        <div>
                            <x-input-label for="tanggal_sampai" :value="__('Tanggal Sampai')" />
                            <x-text-input id="tanggal_sampai" class="block mt-1 w-full text-sm" type="date" name="tanggal_sampai" :value="request('tanggal_sampai')" />
                        </div>
                        <div>
                            <x-input-label for="batch" :value="__('Batch')" />
                            <x-text-input id="batch" class="block mt-1 w-full text-sm" type="text" name="batch" :value="request('batch')" placeholder="Cari Batch" />
                        </div>
                        <div>
                            <x-input-label for="seri" :value="__('Seri')" />
                            <x-text-input id="seri" class="block mt-1 w-full text-sm" type="text" name="seri" :value="request('seri')" placeholder="Cari Seri" />
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Filter
                            </button>
                            <a href="{{ route('hcs-sorting-reports.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 text-center">
                                Reset
                            </a>
                        </div>
                    </form>

                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gilir</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batch</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Seri</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pch</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Pack Terpilih</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-right">Jml Pack</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-right">Total Bilyet</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Petugas</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($reports as $report)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">{{ $report->tanggal_sortir->format('d/m/Y') }}</td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">{{ $report->gilir }}</td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $report->batch }}</td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">{{ $report->seri }}</td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">{{ $report->pecahan }}</td>
                                        <td class="px-3 py-4 text-sm text-gray-500 text-center max-w-xs truncate" title="{{ implode(', ', $report->packs_selected) }}">
                                            @php
                                                // Create a shortened representation of the selected packs array for display
                                                $arr = $report->packs_selected;
                                                sort($arr);
                                                $displayStr = "";
                                                if(count($arr) <= 5) {
                                                    $displayStr = implode(', ', $arr);
                                                } else {
                                                    $displayStr = $arr[0] . ', ' . $arr[1] . ', ..., ' . $arr[count($arr)-1];
                                                }
                                            @endphp
                                            {{ $displayStr }}
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-medium">{{ number_format($report->jumlah_pack, 0, ',', '.') }}</td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-green-700 text-right font-medium">{{ number_format($report->jumlah_bilyet, 0, ',', '.') }}</td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $report->petugas_1 }} 
                                            @if($report->petugas_2)
                                                <br><span class="text-xs text-gray-400">&amp; {{ $report->petugas_2 }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Tidak ada data laporan penyortiran.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $reports->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
