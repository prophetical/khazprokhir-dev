<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Penyortiran HCS') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 border-l-4 border-indigo-600 pl-4 mb-8">Laporan
                        Penyortiran HCS</h3>

                    @php
                        $themeClasses = [
                            'S' => ['bg' => 'bg-lime-500', 'border' => 'border-lime-500', 'ring' => 'focus:ring-lime-500', 'focus' => 'focus:border-lime-500', 'btn' => 'bg-lime-500', 'text' => 'text-white'],
                            'T' => ['bg' => 'bg-gray-400', 'border' => 'border-gray-400', 'ring' => 'focus:ring-gray-400', 'focus' => 'focus:border-gray-400', 'btn' => 'bg-gray-400', 'text' => 'text-white'],
                            'U' => ['bg' => 'bg-amber-400', 'border' => 'border-amber-400', 'ring' => 'focus:ring-amber-400', 'focus' => 'focus:border-amber-400', 'btn' => 'bg-amber-400', 'text' => 'text-white'],
                            'V' => ['bg' => 'bg-purple-500', 'border' => 'border-purple-500', 'ring' => 'focus:ring-purple-500', 'focus' => 'focus:border-purple-500', 'btn' => 'bg-purple-500', 'text' => 'text-white'],
                            'W' => ['bg' => 'bg-green-500', 'border' => 'border-green-500', 'ring' => 'focus:ring-green-500', 'focus' => 'focus:border-green-500', 'btn' => 'bg-green-500', 'text' => 'text-white'],
                            'X' => ['bg' => 'bg-blue-500', 'border' => 'border-blue-500', 'ring' => 'focus:ring-blue-500', 'focus' => 'focus:border-blue-500', 'btn' => 'bg-blue-500', 'text' => 'text-white'],
                            'Y' => ['bg' => 'bg-red-500', 'border' => 'border-red-500', 'ring' => 'focus:ring-red-500', 'focus' => 'focus:border-red-500', 'btn' => 'bg-red-500', 'text' => 'text-white'],
                        ];
                        $selectedPecahan = request('pecahan', '');
                    @endphp

                    <div x-data="{ 
                        selectedPecahan: '{{ $selectedPecahan }}',
                        themes: {{ json_encode($themeClasses) }},
                        get currentTheme() { return this.themes[this.selectedPecahan] || null }
                    }" class="bg-white overflow-hidden shadow-sm rounded-xl mb-6 border-t-4 transition-all duration-500"
                        :class="currentTheme ? currentTheme.border : 'border-gray-100'">
                        <div class="p-6">
                            <form method="GET" action="{{ route('hcs-sorting-reports.index') }}">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 items-end">
                                    <div class="lg:col-span-2 grid grid-cols-2 gap-4">
                                        <div>
                                            <label
                                                class="block text-[8px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Dari
                                                Tanggal</label>
                                            <input id="tanggal_dari" name="tanggal_dari" type="date"
                                                class="block w-full border-gray-200 rounded-lg shadow-sm px-3 py-3 text-sm text-center transition-all duration-300"
                                                :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'"
                                                value="{{ request('tanggal_dari') }}" />
                                        </div>
                                        <div>
                                            <label
                                                class="block text-[8px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Sampai
                                                Tanggal</label>
                                            <input id="tanggal_sampai" name="tanggal_sampai" type="date"
                                                class="block w-full border-gray-200 rounded-lg shadow-sm px-3 py-3 text-sm text-center transition-all duration-300"
                                                :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'"
                                                value="{{ request('tanggal_sampai') }}" />
                                        </div>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Pecahan</label>
                                        <select id="pecahan" name="pecahan" x-model="selectedPecahan"
                                            class="block w-full border-gray-200 rounded-lg shadow-sm text-sm py-3 text-center transition-all duration-300 font-bold"
                                            :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'">
                                            <option value="">Semua</option>
                                            @foreach(['S', 'T', 'U', 'V', 'W', 'X', 'Y'] as $p)
                                                <option value="{{ $p }}">{{ $p }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Gilir</label>
                                        <select id="gilir" name="gilir"
                                            class="block w-full border-gray-200 rounded-lg shadow-sm text-sm py-3 text-center transition-all duration-300"
                                            :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'">
                                            <option value="">Semua</option>
                                            <option value="Gilir 1" {{ request('gilir') == 'Gilir 1' ? 'selected' : '' }}>
                                                Gilir 1</option>
                                            <option value="Gilir 2" {{ request('gilir') == 'Gilir 2' ? 'selected' : '' }}>
                                                Gilir 2</option>
                                            <option value="Gilir 3" {{ request('gilir') == 'Gilir 3' ? 'selected' : '' }}>
                                                Gilir 3</option>
                                        </select>
                                    </div>

                                    <div class="lg:col-span-1">
                                        <label
                                            class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Batch
                                            / Seri</label>
                                        <div class="grid grid-cols-2 gap-2">
                                            <input id="batch" name="batch" type="text" placeholder="Batch"
                                                class="block w-full border-gray-200 rounded-lg shadow-sm px-2 py-3 text-sm text-center transition-all duration-300"
                                                :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'"
                                                value="{{ request('batch') }}" />
                                            <input id="seri" name="seri" type="text" placeholder="Seri"
                                                class="block w-full border-gray-200 rounded-lg shadow-sm px-2 py-3 text-sm text-center transition-all duration-300"
                                                :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'"
                                                value="{{ request('seri') }}" />
                                        </div>
                                    </div>

                                    <div class="col-span-1 flex flex-col gap-2">
                                        <div class="flex gap-2 h-full">
                                            <button type="submit"
                                                class="flex-1 inline-flex justify-center items-center px-4 py-3 rounded-lg font-bold text-xs uppercase tracking-widest transition-all shadow-md active:scale-95"
                                                :class="currentTheme ? (currentTheme.btn + ' ' + currentTheme.text + ' brightness-95 hover:brightness-105') : 'bg-gray-800 text-white'">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                </svg>
                                            </button>
                                            <a href="{{ route('hcs-sorting-reports.index') }}"
                                                class="flex-1 inline-flex justify-center items-center px-4 py-3 bg-gray-100 border border-gray-200 rounded-lg font-bold text-xs text-gray-400 uppercase tracking-widest shadow-sm hover:bg-gray-200 transition-all text-center">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pemisah antara Form Pencarian dan Tombol Export -->
                                <div
                                    class="mt-6 pt-4 border-t border-gray-100 flex flex-wrap gap-2 justify-end lg:justify-end">
                                    <a href="{{ route('hcs-sorting-reports.export', request()->all()) }}"
                                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg hover:bg-emerald-100 transition-colors shadow-sm"
                                        title="Export Excel (CSV)">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Excel
                                    </a>
                                    <a href="{{ route('hcs-sorting-reports.print', request()->all()) }}" target="_blank"
                                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium bg-rose-50 text-rose-700 border border-rose-200 rounded-lg hover:bg-rose-100 transition-colors shadow-sm"
                                        title="Export PDF / Print">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        PDF
                                    </a>
                                    <a href="{{ route('hcs-sorting-reports.print', array_merge(request()->all(), ['autoprint' => 1])) }}"
                                        target="_blank"
                                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium bg-gray-50 text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-100 transition-colors shadow-sm"
                                        title="Cetak Langsung">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 00-2 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                        Print
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto rounded-lg border border-gray-200 mt-4">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Gilir</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Batch</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Seri</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                        Emisi</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                        TA</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                        Pec</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Supplier</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pack</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-right">
                                        Jumlah Pack</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-right">
                                        Total Bilyet</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Petugas</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-slate-900 dark:divide-slate-800">
                                @php
                                    $colorMap = [
                                        'S' => 'bg-lime-500 border-lime-600 text-white',
                                        'T' => 'bg-gray-400 border-gray-500 text-white',
                                        'U' => 'bg-amber-400 border-amber-500 text-white',
                                        'V' => 'bg-purple-500 border-purple-600 text-white',
                                        'W' => 'bg-green-500 border-green-600 text-white',
                                        'X' => 'bg-blue-500 border-blue-600 text-white',
                                        'Y' => 'bg-red-500 border-red-600 text-white',
                                    ];
                                @endphp
                                @forelse ($reports as $report)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors">
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                            {{ $report->tanggal_sortir->locale('id')->isoFormat('D MMMM YYYY') }}</td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">{{ $report->gilir }}
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200 font-medium">
                                            {{ $report->batch }}</td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">{{ $report->seri }}
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-center font-bold text-gray-700 dark:text-gray-400">
                                            {{ $report->emisi }}</td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-center font-bold text-gray-700 dark:text-gray-400">
                                            {{ $report->tahun_anggaran }}</td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-center">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $colorMap[$report->pecahan] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ $report->pecahan }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                            {{ $report->supplier }}</td>
                                        <td class="px-3 py-4 text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate"
                                            title="{{ is_array($report->packs_selected) ? implode(', ', $report->packs_selected) : '' }}">
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
                                        <td
                                            class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200 text-right font-medium">
                                            {{ number_format($report->jumlah_pack, 0, ',', '.') }}</td>
                                        <td
                                            class="px-3 py-4 whitespace-nowrap text-sm text-green-700 dark:text-emerald-400 text-right font-medium">
                                            {{ number_format($report->jumlah_bilyet, 0, ',', '.') }}</td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $report->petugas_1 }}
                                            @if($report->petugas_2)
                                                <br><span class="text-xs text-gray-400">&amp; {{ $report->petugas_2 }}</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm font-medium">
                                            @if(auth()->user()->role === 'sortir' || auth()->user()->role === 'admin')
                                                <div class="flex items-center justify-center gap-3">
                                                    @if($report->status_kunci_pengemasan)
                                                        <span
                                                            class="text-gray-400 cursor-not-allowed flex items-center justify-center"
                                                            title="Data Terkunci (Sudah Masuk Pengemasan)">
                                                            <svg class="w-5 h-5 pointer-events-none" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                            </svg>
                                                        </span>
                                                    @else
                                                        <a href="{{ route('hcs-sorting-reports.edit', $report->id) }}"
                                                            class="text-indigo-600 hover:text-indigo-900 flex items-center justify-center"
                                                            title="Edit">
                                                            <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                                stroke="currentColor" class="w-5 h-5 pointer-events-none">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                            </svg>
                                                        </a>
                                                        <form id="delete-form-{{ $report->id }}"
                                                            action="{{ route('hcs-sorting-reports.destroy', $report->id) }}"
                                                            method="POST" class="m-0 p-0 flex items-center justify-center">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" onclick="deleteReport({{ $report->id }})"
                                                                class="text-red-600 hover:text-red-900 focus:outline-none flex items-center justify-center"
                                                                title="Hapus">
                                                                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                                    stroke="currentColor" class="w-5 h-5 pointer-events-none">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12"
                                            class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">Tidak ada
                                            data penyortiran yang sesuai.</td>
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
        // Fungsi global untuk konfirmasi penghapusan SweetAlert
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