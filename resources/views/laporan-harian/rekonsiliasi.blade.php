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

    $hariIndonesia = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    $bulanIndonesia = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    $startDateCarbon = \Carbon\Carbon::parse($start_date);
    $endDateCarbon = \Carbon\Carbon::parse($end_date);

    $startDateRange = $hariIndonesia[$startDateCarbon->dayOfWeek] . ', ' . $startDateCarbon->day . ' ' . $bulanIndonesia[$startDateCarbon->month - 1] . ' ' . $startDateCarbon->year;
    $endDateRange = $hariIndonesia[$endDateCarbon->dayOfWeek] . ', ' . $endDateCarbon->day . ' ' . $bulanIndonesia[$endDateCarbon->month - 1] . ' ' . $endDateCarbon->year;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Rekonsiliasi Data') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ 
        search: '', 
    }">
        <div class="w-full mx-auto sm:px-6 lg:px-8 overflow-hidden">
            <!-- Baris Filter & Aksi -->
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl mb-8 border border-gray-100 p-1">
                <div class="bg-gray-50/50 rounded-[1.25rem] p-6">
                    <div class="flex flex-col md:flex-row justify-between items-end gap-8">
                        <form action="{{ route('laporan-harian.rekonsiliasi') }}" method="GET"
                            class="w-full xl:w-auto flex-grow">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                                <!-- Start Date -->
                                <div class="relative">
                                    <label for="start_date"
                                        class="flex items-center gap-2 text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">
                                        Tanggal Mulai
                                    </label>
                                    <input type="date" name="start_date" id="start_date" value="{{ $start_date }}"
                                        class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 py-2 text-center font-black text-gray-700 bg-white">
                                </div>

                                <!-- End Date -->
                                <div class="relative">
                                    <label for="end_date"
                                        class="flex items-center gap-2 text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">
                                        Tanggal Akhir
                                    </label>
                                    <input type="date" name="end_date" id="end_date" value="{{ $end_date }}"
                                        class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 py-2 text-center font-black text-gray-700 bg-white">
                                </div>

                                <!-- Tahun Anggaran -->
                                <div class="relative">
                                    <label for="tahun_anggaran"
                                        class="flex items-center gap-2 text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">
                                        Tahun Anggaran
                                    </label>
                                    <div class="flex gap-3">
                                        <select name="tahun_anggaran" id="tahun_anggaran"
                                            class="flex-grow rounded-xl border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 py-2 text-center font-black text-gray-700 bg-white">
                                            @foreach($tahunAnggaranOptions as $year)
                                                <option value="{{ $year }}" {{ $tahun_anggaran == $year ? 'selected' : '' }}>
                                                    {{ $year }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit"
                                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-black px-6 rounded-xl transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- Action Buttons -->
                        <div class="flex flex-col gap-2 w-full md:w-auto">
                            <span
                                class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0 sm:mb-2 px-1 hidden 2xl:block">&nbsp;</span>
                            <div
                                class="flex flex-wrap gap-3 shrink-0 items-center justify-center sm:justify-end 2xl:justify-start">
                                <!-- Excel -->
                                <a href="{{ route('laporan-harian.rekonsiliasi-export', request()->all()) }}"
                                    class="btn-report btn-excel">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Excel
                                </a>

                                <!-- PDF -->
                                <a href="{{ route('laporan-harian.rekonsiliasi-print', request()->all()) }}"
                                    target="_blank" class="btn-report btn-pdf">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Sections -->
            <div class="space-y-8 mt-8">
                <!-- Data Rekonsiliasi Table -->
                <div
                    class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl border border-gray-100 transition-all duration-300">
                    <div
                        class="border-b border-gray-100 from-gray-50 to-white px-6 py-5 flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-gray-800 tracking-tight">Data Rekonsiliasi HCS</h3>
                                <p class="text-[11px] font-medium text-gray-500 mt-0.5">{{ $startDateRange }} s/d {{ $endDateRange }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 bg-gray-50/50 rounded-[1.25rem]">
                        <div class="overflow-x-auto rounded-xl border border-gray-100 bg-white shadow-sm">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100/80 dark:bg-slate-800/80">
                                    <tr
                                        class="text-[10px] font-black uppercase text-gray-500 tracking-widest divide-x divide-gray-200">
                                        <th rowspan="2" class="px-6 py-4 text-center align-middle">Pecahan</th>
                                        <th rowspan="2" class="px-6 py-4 text-center align-middle">Penerimaan HCS</th>
                                        <th rowspan="2" class="px-6 py-4 text-center align-middle">Pengemasan HCS</th>
                                        <th colspan="2"
                                            class="px-6 py-2 text-center align-middle border-b border-gray-200 text-indigo-600 dark:text-indigo-400">
                                            Range No. Dus Pengemasan</th>
                                        <th rowspan="2" class="px-6 py-4 text-center align-middle">Penyerahan HCS</th>
                                        <th colspan="2"
                                            class="px-6 py-2 text-center align-middle border-b border-gray-200 text-indigo-600 dark:text-indigo-400">
                                            Range No. Dus Penyerahan</th>
                                        <th rowspan="2" class="px-6 py-4 text-center align-middle">Akumulasi Target
                                            Pengemasan</th>
                                        <th rowspan="2" class="px-6 py-4 text-center align-middle">Akumulasi Target
                                            Penyerahan</th>
                                    </tr>
                                    <tr
                                        class="text-[9px] font-bold text-gray-400 dark:text-gray-500 divide-x divide-gray-200 dark:divide-slate-700 bg-indigo-50/50 dark:bg-indigo-900/30">
                                        <th class="px-6 py-2 text-center align-middle">No Awal</th>
                                        <th class="px-6 py-2 text-center align-middle">No Akhir</th>
                                        <th class="px-6 py-2 text-center align-middle">No Awal</th>
                                        <th class="px-6 py-2 text-center align-middle">No Akhir</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($rekonsiliasiData as $row)
                                        <tr
                                            class="hover:bg-indigo-50/40 transition duration-150 group divide-x divide-gray-100">
                                            <td
                                                class="px-6 py-4 text-center bg-white group-hover:bg-indigo-50/40 z-10 font-black">
                                                <div class="flex justify-center">
                                                    <span
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-sm font-black border shadow-sm {{ $colorMap[$row['pecahan']] ?? 'bg-gray-900 text-white' }}">
                                                        {{ $row['pecahan'] }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-center text-xs font-bold text-gray-600">
                                                {{ $row['penerimaan_hcs'] > 0 ? number_format($row['penerimaan_hcs'], 0, ',', '.') : '-' }}
                                            </td>
                                            <td class="px-6 py-4 text-center text-xs font-bold text-gray-600">
                                                {{ $row['pengemasan_hcs'] > 0 ? number_format($row['pengemasan_hcs'], 0, ',', '.') : '-' }}
                                            </td>
                                            <td class="px-6 py-4 text-center text-xs font-bold text-gray-600">
                                                {{ $row['min_dus_kemas'] }}
                                            </td>
                                            <td class="px-6 py-4 text-center text-xs font-bold text-gray-600">
                                                {{ $row['max_dus_kemas'] }}
                                            </td>
                                            <td class="px-6 py-4 text-center text-xs font-bold text-gray-600">
                                                {{ $row['penyerahan_hcs'] > 0 ? number_format($row['penyerahan_hcs'], 0, ',', '.') : '-' }}
                                            </td>
                                            <td class="px-6 py-4 text-center text-xs font-bold text-gray-600">
                                                {{ $row['min_dus_serah'] }}
                                            </td>
                                            <td class="px-6 py-4 text-center text-xs font-bold text-gray-600">
                                                {{ $row['max_dus_serah'] }}
                                            </td>
                                            <td class="px-6 py-4 text-center text-xs font-bold text-gray-600">
                                                {{ $row['target_pengemasan'] > 0 ? number_format($row['target_pengemasan'], 0, ',', '.') : '-' }}
                                            </td>
                                            <td class="px-6 py-4 text-center text-xs font-bold text-gray-600">
                                                {{ $row['target_penyerahan'] > 0 ? number_format($row['target_penyerahan'], 0, ',', '.') : '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-900 text-white text-xs font-black uppercase">
                                    <tr class="divide-x divide-gray-700">
                                        <td class="px-6 py-5 text-center uppercase tracking-widest">TOTAL</td>
                                        <td class="px-6 py-5 text-center text-amber-300">
                                            {{ $totals['penerimaan_hcs'] > 0 ? number_format($totals['penerimaan_hcs'], 0, ',', '.') : '-' }}
                                        </td>
                                        <td class="px-6 py-5 text-center text-emerald-300">
                                            {{ $totals['pengemasan_hcs'] > 0 ? number_format($totals['pengemasan_hcs'], 0, ',', '.') : '-' }}
                                        </td>
                                        <td class="px-6 py-5 text-center text-gray-400 font-bold">-</td>
                                        <td class="px-6 py-5 text-center text-gray-400 font-bold">-</td>
                                        <td class="px-6 py-5 text-center text-pink-300">
                                            {{ $totals['penyerahan_hcs'] > 0 ? number_format($totals['penyerahan_hcs'], 0, ',', '.') : '-' }}
                                        </td>
                                        <td class="px-6 py-5 text-center text-gray-400 font-bold">-</td>
                                        <td class="px-6 py-5 text-center text-gray-400 font-bold">-</td>
                                        <td class="px-6 py-5 text-center text-blue-300">
                                            {{ $totals['target_pengemasan'] > 0 ? number_format($totals['target_pengemasan'], 0, ',', '.') : '-' }}
                                        </td>
                                        <td class="px-6 py-5 text-center text-indigo-300">
                                            {{ $totals['target_penyerahan'] > 0 ? number_format($totals['target_penyerahan'], 0, ',', '.') : '-' }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</x-app-layout>