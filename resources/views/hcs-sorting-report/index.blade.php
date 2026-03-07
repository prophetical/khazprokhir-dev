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
                    
                    <form method="GET" action="{{ route('hcs-sorting-reports.index') }}" class="mb-6 grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 items-end bg-gray-50 p-4 rounded-md border border-gray-100">
                        <div>
                            <x-input-label for="tanggal_dari" :value="__('Tanggal Dari')" />
                            <x-text-input id="tanggal_dari" class="block mt-1 w-full text-sm" type="date" name="tanggal_dari" :value="request('tanggal_dari')" />
                        </div>
                        <div>
                            <x-input-label for="tanggal_sampai" :value="__('Tanggal Sampai')" />
                            <x-text-input id="tanggal_sampai" class="block mt-1 w-full text-sm" type="date" name="tanggal_sampai" :value="request('tanggal_sampai')" />
                        </div>
                        <div>
                            <x-input-label for="gilir" :value="__('Gilir')" />
                            <select id="gilir" name="gilir" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                                <option value="">Semua Gilir</option>
                                <option value="Gilir 1" {{ request('gilir') == 'Gilir 1' ? 'selected' : '' }}>Gilir 1</option>
                                <option value="Gilir 2" {{ request('gilir') == 'Gilir 2' ? 'selected' : '' }}>Gilir 2</option>
                                <option value="Gilir 3" {{ request('gilir') == 'Gilir 3' ? 'selected' : '' }}>Gilir 3</option>
                            </select>
                        </div>
                        <div>
                            <x-input-label for="batch" :value="__('Batch')" />
                            <x-text-input id="batch" class="block mt-1 w-full text-sm" type="text" name="batch" :value="request('batch')" placeholder="Cari Batch" />
                        </div>
                        <div>
                            <x-input-label for="seri" :value="__('Seri')" />
                            <x-text-input id="seri" class="block mt-1 w-full text-sm" type="text" name="seri" :value="request('seri')" placeholder="Cari Seri" />
                        </div>
                        <div class="col-span-1 lg:col-span-1 flex flex-col gap-2">
                                <div class="grid grid-cols-2 gap-2">
                                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-200 shadow-sm" title="Terapkan Filter">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                    </button>
                                    <a href="{{ route('hcs-sorting-reports.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-gray-100 border border-gray-200 rounded-lg font-bold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 transition duration-200 shadow-sm" title="Reset Semua Filter">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                    </a>
                                </div>
                                <div class="grid grid-cols-3 gap-2">
                                    <a href="{{ route('hcs-sorting-reports.export', ['tanggal_dari' => request('tanggal_dari'), 'tanggal_sampai' => request('tanggal_sampai'), 'gilir' => request('gilir'), 'batch' => request('batch'), 'seri' => request('seri')]) }}" 
                                       class="inline-flex items-center justify-center p-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-colors shadow-sm" title="Export Excel (CSV)">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    </a>
                                    <a href="{{ route('hcs-sorting-reports.print', ['tanggal_dari' => request('tanggal_dari'), 'tanggal_sampai' => request('tanggal_sampai'), 'gilir' => request('gilir'), 'batch' => request('batch'), 'seri' => request('seri')]) }}" 
                                       target="_blank"
                                       class="inline-flex items-center justify-center p-2 bg-rose-500 text-white rounded-lg hover:bg-rose-600 transition-colors shadow-sm" title="Export PDF">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                    </a>
                                    <a href="{{ route('hcs-sorting-reports.print', ['tanggal_dari' => request('tanggal_dari'), 'tanggal_sampai' => request('tanggal_sampai'), 'gilir' => request('gilir'), 'batch' => request('batch'), 'seri' => request('seri'), 'autoprint' => 1]) }}" 
                                       target="_blank"
                                       class="inline-flex items-center justify-center p-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition-colors shadow-sm" title="Cetak Laporan">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 00-2 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                                    </a>
                                </div>
                        </div>
                    </form>

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

                    <div class="overflow-x-auto rounded-lg border border-gray-200 mt-4">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gilir</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batch</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Seri</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Pch</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pack Terpilih</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-right">Jml Pack</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-right">Total Bilyet</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Petugas</th>
                                    <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php
                                    $colorMap = [
                                        'S' => 'bg-lime-200 border-lime-400 text-lime-800',
                                        'T' => 'bg-gray-200 border-gray-400 text-gray-800',
                                        'U' => 'bg-amber-100 border-amber-300 text-amber-800',
                                        'V' => 'bg-purple-100 border-purple-300 text-purple-800',
                                        'W' => 'bg-green-100 border-green-300 text-green-800',
                                        'X' => 'bg-blue-100 border-blue-300 text-blue-800',
                                        'Y' => 'bg-red-100 border-red-300 text-red-800',
                                    ];
                                @endphp
                                @forelse ($reports as $report)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">{{ $report->tanggal_sortir->format('d/m/Y') }}</td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">{{ $report->gilir }}</td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $report->batch }}</td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">{{ $report->seri }}</td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $colorMap[$report->pecahan] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ $report->pecahan }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">{{ $report->supplier }}</td>
                                        <td class="px-3 py-4 text-sm text-gray-500 max-w-xs truncate" title="{{ is_array($report->packs_selected) ? implode(', ', $report->packs_selected) : '' }}">
                                            @php
                                                $arr = is_array($report->packs_selected) ? $report->packs_selected : [];
                                                sort($arr, SORT_NUMERIC);
                                                $ranges = [];
                                                $i = 0;
                                                while ($i < count($arr)) {
                                                    $start = $arr[$i];
                                                    $end = $start;
                                                    while (isset($arr[$i + 1]) && $arr[$i + 1] == $end + 1) {
                                                        $end = $arr[$i + 1];
                                                        $i++;
                                                    }
                                                    $ranges[] = ($start == $end) ? $start : $start . '-' . $end;
                                                    $i++;
                                                }
                                                $displayStr = implode(' | ', $ranges);
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
                                        <td class="px-3 py-4 whitespace-nowrap text-sm font-medium">
                                            @if(auth()->user()->role === 'sortir' || auth()->user()->role === 'admin')
                                                <div class="flex items-center justify-center gap-3">
                                                    <a href="{{ route('hcs-sorting-reports.edit', $report->id) }}" class="text-indigo-600 hover:text-indigo-900 flex items-center justify-center" title="Edit">
                                                        <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 pointer-events-none">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                        </svg>
                                                    </a>
                                                    <form id="delete-form-{{ $report->id }}" action="{{ route('hcs-sorting-reports.destroy', $report->id) }}" method="POST" class="m-0 p-0 flex items-center justify-center">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" onclick="deleteReport({{ $report->id }})" class="text-red-600 hover:text-red-900 focus:outline-none flex items-center justify-center" title="Hapus">
                                                            <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 pointer-events-none">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">Tidak ada data penyortiran yang sesuai.</td>
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

    <!-- SweetAlert2 -->
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
    
    <script>
        // Global function for SweetAlert Delete Confirmation
        function deleteReport(id) {
            Swal.fire({
                title: 'Hapus Laporan?',
                text: 'Data penyortiran akan dihapus dan status pack akan dikembalikan menjadi siap sortir.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
</x-app-layout>
