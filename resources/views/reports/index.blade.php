<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Penerimaan HCS') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">

            <!-- Kartu Ringkasan per Pecahan (Dua Baris) -->
            <div class="space-y-4 mb-8">
                @php
                    $themeClasses = [
                        'S' => ['bg' => 'bg-lime-500', 'border' => 'border-lime-500', 'ring' => 'focus:ring-lime-500', 'focus' => 'focus:border-lime-500', 'btn' => 'bg-lime-500', 'text' => 'text-gray-900', 'label' => 'TOTAL PENERIMAAN'],
                        'T' => ['bg' => 'bg-gray-400', 'border' => 'border-gray-400', 'ring' => 'focus:ring-gray-400', 'focus' => 'focus:border-gray-400', 'btn' => 'bg-gray-400', 'text' => 'text-white', 'label' => 'TOTAL PENERIMAAN'],
                        'U' => ['bg' => 'bg-amber-400', 'border' => 'border-amber-400', 'ring' => 'focus:ring-amber-400', 'focus' => 'focus:border-amber-400', 'btn' => 'bg-amber-400', 'text' => 'text-gray-900', 'label' => 'TOTAL PENERIMAAN'],
                        'V' => ['bg' => 'bg-purple-500', 'border' => 'border-purple-500', 'ring' => 'focus:ring-purple-500', 'focus' => 'focus:border-purple-500', 'btn' => 'bg-purple-500', 'text' => 'text-white', 'label' => 'TOTAL PENERIMAAN'],
                        'W' => ['bg' => 'bg-green-500', 'border' => 'border-green-500', 'ring' => 'focus:ring-green-500', 'focus' => 'focus:border-green-500', 'btn' => 'bg-green-500', 'text' => 'text-white', 'label' => 'TOTAL PENERIMAAN'],
                        'X' => ['bg' => 'bg-blue-500', 'border' => 'border-blue-500', 'ring' => 'focus:ring-blue-500', 'focus' => 'focus:border-blue-500', 'btn' => 'bg-blue-500', 'text' => 'text-white', 'label' => 'TOTAL PENERIMAAN'],
                        'Y' => ['bg' => 'bg-red-500', 'border' => 'border-red-500', 'ring' => 'focus:ring-red-500', 'focus' => 'focus:border-red-500', 'btn' => 'bg-red-500', 'text' => 'text-white', 'label' => 'TOTAL PENERIMAAN'],
                    ];

                    $row1 = ['S', 'T', 'U', 'V'];
                    $row2 = ['W', 'X', 'Y'];
                    $selectedPecahan = $pecahan ?? '';
                @endphp

                <!-- Baris 1: S, T, U, V -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($row1 as $key)
                        @php $meta = $themeClasses[$key]; @endphp
                        <a href="{{ route('reports.index', ['pecahan' => $key]) }}"
                            class="bg-white overflow-hidden shadow-sm rounded-xl border {{ isset($pecahan) && $pecahan == $key ? 'ring-2 ring-offset-1' : 'border-gray-100' }} p-4 flex flex-col items-center group hover:shadow-md transition-all"
                            style="{{ isset($pecahan) && $pecahan == $key ? 'border-color: transparent;' : '' }}"
                            id="card-{{ $key }}">
                            <div
                                class="{{ $meta['bg'] }} w-10 h-10 rounded-lg flex items-center justify-center text-white font-bold text-lg mb-2 shadow-sm group-hover:scale-110 transition-transform {{ $meta['text'] }}">
                                {{ $key }}
                            </div>
                            <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">
                                {{ $meta['label'] }}</div>
                            <div class="text-xl font-black text-gray-900 transition-colors duration-500"
                                :class="selectedPecahan == '{{ $key }}' ? '{{ $meta['text'] }}' : ''">
                                {{ number_format($globalTotalsPerPecahan[$key] ?? 0, 0, ',', '.') }}
                            </div>
                        </a>
                        <style>
                            #card-{{ $key }}.ring-2 {
                                --tw-ring-color:
                                    {{ str_contains($meta['bg'], 'lime') ? '#84cc16' : (str_contains($meta['bg'], 'gray') ? '#9ca3af' : (str_contains($meta['bg'], 'amber') ? '#fbbf24' : (str_contains($meta['bg'], 'purple') ? '#a855f7' : (str_contains($meta['bg'], 'green') ? '#22c55e' : (str_contains($meta['bg'], 'blue') ? '#3b82f6' : '#ef4444'))))) }}
                                ;
                            }
                        </style>
                    @endforeach
                </div>

                <!-- Baris 2: W, X, Y, TOTAL -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($row2 as $key)
                        @php $meta = $themeClasses[$key]; @endphp
                        <a href="{{ route('reports.index', ['pecahan' => $key]) }}"
                            class="bg-white overflow-hidden shadow-sm rounded-xl border {{ isset($pecahan) && $pecahan == $key ? 'ring-2 ring-offset-1' : 'border-gray-100' }} p-4 flex flex-col items-center group hover:shadow-md transition-all"
                            style="{{ isset($pecahan) && $pecahan == $key ? 'border-color: transparent;' : '' }}"
                            id="card-{{ $key }}">
                            <div
                                class="{{ $meta['bg'] }} w-10 h-10 rounded-lg flex items-center justify-center font-bold text-lg mb-2 shadow-sm group-hover:scale-110 transition-transform {{ $meta['text'] }}">
                                {{ $key }}
                            </div>
                            <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">
                                {{ $meta['label'] }}</div>
                            <div class="text-xl font-black text-gray-900 transition-colors duration-500"
                                :class="selectedPecahan == '{{ $key }}' ? '{{ $meta['text'] }}' : ''">
                                {{ number_format($globalTotalsPerPecahan[$key] ?? 0, 0, ',', '.') }}
                            </div>
                        </a>
                        <style>
                            #card-{{ $key }}.ring-2 {
                                --tw-ring-color:
                                    {{ str_contains($meta['bg'], 'lime') ? '#84cc16' : (str_contains($meta['bg'], 'gray') ? '#9ca3af' : (str_contains($meta['bg'], 'amber') ? '#fbbf24' : (str_contains($meta['bg'], 'purple') ? '#a855f7' : (str_contains($meta['bg'], 'green') ? '#22c55e' : (str_contains($meta['bg'], 'blue') ? '#3b82f6' : '#ef4444'))))) }}
                                ;
                            }
                        </style>
                    @endforeach

                    <!-- Grand Total Card -->
                    <a href="{{ route('reports.index') }}"
                        class="bg-indigo-600 overflow-hidden shadow-lg rounded-xl p-4 flex flex-col items-center group hover:bg-indigo-700 transition-colors {{ !isset($pecahan) && !isset($startDate) && !isset($gilir) ? 'ring-4 ring-indigo-200' : '' }}">
                        <div
                            class="bg-white/20 w-10 h-10 rounded-lg flex items-center justify-center text-white font-bold text-xs mb-2 shadow-sm group-hover:scale-110 transition-transform">
                            ALL
                        </div>
                        <div class="text-[10px] text-indigo-100 font-medium uppercase tracking-wider mb-1">Total
                            Penerimaan</div>
                        <div class="text-xl font-bold text-white">
                            {{ number_format($globalGrandTotal, 0, ',', '.') }}
                        </div>
                    </a>
                </div>
            </div>



            <!-- Tabel Data Laporan -->
            <div class="bg-white dark:bg-slate-900 overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 dark:border-slate-800">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center">
                            Laporan Penerimaan Harian
                            @if(isset($pecahan))
                                <span class="ml-2 text-indigo-500">Pecahan {{ $pecahan }}</span>
                            @endif
                        </h3>

                        <div
                            class="flex items-center text-sm font-medium text-gray-500 bg-gray-50 px-4 py-2 rounded-full border border-gray-100">
                            <svg class="w-4 h-4 mr-2 text-indigo-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            @if($startDate && $endDate)
                                {{ \Carbon\Carbon::parse($startDate)->locale('id')->isoFormat('D MMMM YYYY') }} —
                                {{ \Carbon\Carbon::parse($endDate)->locale('id')->isoFormat('D MMMM YYYY') }}
                            @else
                                Semua Tanggal
                            @endif
                            @if($gilir)
                                <span class="mx-2 text-gray-300">|</span>
                                <span class="text-indigo-600 font-bold uppercase">{{ $gilir }}</span>
                            @endif
                        </div>
                    </div>
                    <!-- Filters Section -->
                    <div x-data="{ 
                selectedPecahan: '{{ $selectedPecahan }}',
                themes: {{ json_encode($themeClasses) }},
                get currentTheme() { return this.themes[this.selectedPecahan] || null }
            }" class="bg-white overflow-hidden shadow-sm rounded-xl mb-6 border-t-4 transition-all duration-500"
                        :class="currentTheme ? currentTheme.border : 'border-gray-100'">
                        <div class="p-6">
                            <form action="{{ route('reports.index') }}" method="GET">
                                @if(isset($pecahan))
                                    <input type="hidden" name="pecahan" value="{{ $pecahan }}">
                                @endif
                                <div
                                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6 items-end">

                                    <!-- Rentang Tanggal -->
                                    <div class="md:col-span-2 lg:col-span-1 xl:col-span-2 grid grid-cols-2 gap-4">
                                        <div>
                                            <label
                                                class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Dari
                                                Tanggal</label>
                                            <input id="start_date" name="start_date" type="date"
                                                class="mt-1 block w-full border-gray-200 rounded-lg shadow-sm px-3 py-3 text-sm text-center transition-all duration-300"
                                                :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'"
                                                value="{{ $startDate }}" />
                                        </div>
                                        <div>
                                            <label
                                                class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Sampai
                                                Tanggal</label>
                                            <input id="end_date" name="end_date" type="date"
                                                class="mt-1 block w-full border-gray-200 rounded-lg shadow-sm px-3 py-3 text-sm text-center transition-all duration-300"
                                                :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'"
                                                value="{{ $endDate }}" />
                                        </div>
                                    </div>

                                    <!-- Filter TA -->
                                    <div class="col-span-1">
                                        <label
                                            class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Tahun
                                            Anggaran</label>
                                        <select id="tahun_anggaran" name="tahun_anggaran"
                                            class="block w-full border-gray-200 rounded-lg shadow-sm text-sm py-3 text-center transition-all duration-300 font-bold"
                                            :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'">
                                            <option value="">Semua TA</option>
                                            @foreach($availableYears as $year)
                                                <option value="{{ $year }}" {{ $tahunAnggaran == $year ? 'selected' : '' }}>
                                                    {{ $year }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Filter TE -->
                                    <div class="col-span-1">
                                        <label
                                            class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Tahun
                                            Emisi</label>
                                        <select id="tahun_emisi" name="tahun_emisi"
                                            class="block w-full border-gray-200 rounded-lg shadow-sm text-sm py-3 text-center transition-all duration-300 font-bold"
                                            :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'">
                                            <option value="">Semua TE</option>
                                            @foreach($availableEmissions as $emisi)
                                                <option value="{{ $emisi }}" {{ $tahunEmisi == $emisi ? 'selected' : '' }}>
                                                    {{ $emisi }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Filter Gilir -->
                                    <div class="col-span-1">
                                        <label
                                            class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Gilir</label>
                                        <select id="gilir" name="gilir"
                                            class="block w-full border-gray-200 rounded-lg shadow-sm text-sm py-3 text-center transition-all duration-300"
                                            :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'">
                                            <option value="">Semua Gilir</option>
                                            <option value="Gilir 1" {{ $gilir == 'Gilir 1' ? 'selected' : '' }}>Gilir 1
                                            </option>
                                            <option value="Gilir 2" {{ $gilir == 'Gilir 2' ? 'selected' : '' }}>Gilir 2
                                            </option>
                                            <option value="Gilir 3" {{ $gilir == 'Gilir 3' ? 'selected' : '' }}>Gilir 3
                                            </option>
                                        </select>
                                    </div>

                                    <!-- Tombol Aksi -->
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
                                            <a href="{{ route('reports.index', array_filter(['pecahan' => $pecahan ?? null])) }}"
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
                                    @php
                                        $exportParams = array_filter([
                                            'start_date' => $startDate,
                                            'end_date' => $endDate,
                                            'gilir' => $gilir,
                                            'pecahan' => $pecahan ?? null,
                                            'tahun_anggaran' => $tahunAnggaran,
                                            'tahun_emisi' => $tahunEmisi
                                        ]);
                                    @endphp
                                    <a href="{{ route('reports.export', $exportParams) }}"
                                        class="btn-report btn-excel"
                                        title="Export Excel (CSV)">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Excel
                                    </a>
                                    <a href="{{ route('reports.print', $exportParams) }}" target="_blank"
                                        class="btn-report btn-pdf"
                                        title="Export PDF / Print">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        PDF
                                    </a>
                                    <a href="{{ route('reports.print', array_merge($exportParams, ['autoprint' => 1])) }}"
                                        target="_blank"
                                        class="btn-report btn-print"
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
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-800 border dark:border-slate-800">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                        No Bon</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">
                                        Pch</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">
                                        Emisi</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">
                                        TA</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">
                                        Jumlah</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">
                                        Gilir</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">
                                        Mesin</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">
                                        Supplier</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                        Batch / Seri</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                        Operator</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-slate-900 divide-y divide-gray-200 dark:divide-slate-800">
                                @forelse($data->groupBy('tanggal_penerimaan') as $date => $group)
                                    <!-- Date Header Row -->
                                    <tr class="bg-gray-50/80 dark:bg-slate-800/80 border-t border-gray-200 dark:border-slate-700">
                                        <td colspan="10"
                                            class="px-3 py-2 text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                                            <div class="flex items-center">
                                                <svg class="w-3.5 h-3.5 mr-2 text-indigo-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                Penerimaan:
                                                {{ \Carbon\Carbon::parse($date)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                                            </div>
                                        </td>
                                    </tr>
                                    @foreach($group as $row)
                                        <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/50 transition-colors">
                                            <td class="px-3 py-3 whitespace-nowrap text-xs font-bold text-gray-800 dark:text-gray-200">
                                                {{ $row->nomor_bon }}</td>
                                            <td class="px-3 py-3 whitespace-nowrap text-xs text-center">
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black {{ $themeClasses[$row->pecahan]['bg'] }} {{ $themeClasses[$row->pecahan]['text'] }} shadow-sm">
                                                    {{ $row->pecahan }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-3 whitespace-nowrap text-xs text-center font-bold text-gray-700 dark:text-gray-300">
                                                {{ $row->emisi }}</td>
                                            <td class="px-3 py-3 whitespace-nowrap text-xs text-center font-bold text-gray-700 dark:text-gray-300">
                                                {{ $row->tahun_anggaran }}</td>
                                            <td class="px-3 py-3 whitespace-nowrap text-xs text-right font-black text-gray-900 dark:text-gray-100">
                                                {{ number_format($row->jumlah, 0, ',', '.') }}</td>
                                            <td
                                                class="px-3 py-3 whitespace-nowrap text-[10px] text-center font-bold text-gray-500 dark:text-gray-400 uppercase">
                                                {{ $row->gilir }}</td>
                                            <td
                                                class="px-3 py-3 whitespace-nowrap text-[10px] text-center font-bold text-gray-500 dark:text-gray-400 uppercase">
                                                {{ $row->mesin }}</td>
                                            <td class="px-3 py-3 whitespace-nowrap text-xs text-center">
                                                <span
                                                    class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $row->supplier === 'Cutpack' ? 'bg-green-100 text-green-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' }}">
                                                    {{ $row->supplier }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-3 whitespace-nowrap text-xs text-gray-600 dark:text-gray-400 font-medium italic">
                                                {{ $row->batch }} / {{ $row->seri }}</td>
                                            <td
                                                class="px-3 py-3 whitespace-nowrap text-[10px] text-gray-500 dark:text-gray-400 font-bold uppercase">
                                                {{ $row->user->name ?? '-' }}</td>
                                            <td class="px-3 py-3 whitespace-nowrap text-center">
                                                @php $barcodeData = $row->barcode(); @endphp
                                                @if($barcodeData)
                                                    <a href="{{ route('hcs-receiving.history', $barcodeData->barcode_token) }}" 
                                                        class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-600 rounded-lg font-black text-[9px] uppercase tracking-tighter hover:bg-indigo-600 hover:text-white transition-all shadow-sm border border-indigo-100"
                                                        title="Lihat Riwayat Audit">
                                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        History
                                                    </a>
                                                @else
                                                    <span class="text-[9px] font-bold text-gray-300 uppercase tracking-widest italic">Manual</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="10"
                                            class="px-6 py-10 whitespace-nowrap text-sm text-center text-gray-500 italic">
                                            Tidak ada data ditemukan untuk filter ini.</td>
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