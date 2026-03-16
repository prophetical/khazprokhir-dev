@php
    // colorMap moved to partials.report-tables
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Laporan Harian Operasional') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ 
        search: '', 
        showHcs: true, 
        showMonitoring: true, 
        showHcts: true, 
        showAnnual: true, 
        showMonthly: true 
    }">
        <div class="w-full mx-auto sm:px-6 lg:px-8 overflow-hidden">
            <!-- Filter & Action Row -->
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl mb-8 border border-gray-100 p-1">
                <div class="bg-gray-50/50 rounded-[1.25rem] p-6">
                    <div class="flex flex-col 2xl:flex-row justify-between items-end gap-8">
                        <form action="{{ route('laporan-harian.index') }}" method="GET"
                            class="w-full 2xl:w-auto flex-grow">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                                <!-- Tanggal Laporan -->
                                <div class="relative">
                                    <label for="tanggal_laporan"
                                        class="flex items-center gap-2 text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">
                                        Tanggal Laporan
                                    </label>
                                    <input type="date" name="tanggal_laporan" id="tanggal_laporan"
                                        value="{{ $tanggalLaporan }}"
                                        class="w-full rounded-xl border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 py-3 text-center font-black text-gray-700 bg-white">
                                </div>

                                <!-- Tahun Anggaran -->
                                <div class="relative">
                                    <label for="tahun_anggaran"
                                        class="flex items-center gap-2 text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">
                                        Tahun Anggaran
                                    </label>
                                    <select name="tahun_anggaran" id="tahun_anggaran"
                                        class="w-full rounded-xl border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 py-3 text-center font-black text-gray-700 bg-white">
                                        @foreach($tahunAnggaranOptions as $year)
                                            <option value="{{ $year }}" {{ $tahunAnggaran == $year ? 'selected' : '' }}>
                                                {{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Tahun Emisi -->
                                <div class="relative">
                                    <label for="tahun_emisi"
                                        class="flex items-center gap-2 text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">
                                        Tahun Emisi
                                    </label>
                                    <div class="flex gap-3">
                                        <select name="tahun_emisi" id="tahun_emisi"
                                            class="flex-grow rounded-xl border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 py-3 text-center font-black text-gray-700 bg-white">
                                            @foreach($tahunEmisiOptions as $emisi)
                                                <option value="{{ $emisi }}" {{ $tahunEmisi == $emisi ? 'selected' : '' }}>
                                                    {{ $emisi }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit"
                                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-black px-6 rounded-xl shadow-lg shadow-indigo-100 transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center shrink-0">
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
                        <div class="flex flex-col gap-2 w-full 2xl:w-auto">
                            <span
                                class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0 sm:mb-2 px-1 hidden 2xl:block">&nbsp;</span>
                            <div
                                class="flex flex-wrap gap-3 shrink-0 items-center justify-center sm:justify-end 2xl:justify-start">
                                <!-- Excel -->
                                <a href="{{ route('laporan-harian.export', request()->all()) }}"
                                    class="inline-flex items-center px-6 py-3 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl hover:bg-emerald-100 transition-all duration-200 font-black text-sm shadow-sm hover:shadow-md hover:-translate-y-0.5 active:translate-y-0">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Excel
                                </a>

                                <!-- PDF -->
                                <a :href="'{{ route('laporan-harian.print') }}?' + new URLSearchParams({
                                    tanggal_laporan: '{{ $tanggalLaporan }}',
                                    tahun_anggaran: '{{ $tahunAnggaran }}',
                                    tahun_emisi: '{{ $tahunEmisi }}',
                                    autoprint: 1,
                                    showHcs: showHcs,
                                    showMonitoring: showMonitoring,
                                    showHcts: showHcts,
                                    showAnnual: showAnnual,
                                    showMonthly: showMonthly
                                }).toString()" target="_blank"
                                    class="inline-flex items-center px-6 py-3 bg-rose-50 text-rose-700 border border-rose-200 rounded-xl hover:bg-rose-100 transition-all duration-200 font-black text-sm shadow-sm hover:shadow-md hover:-translate-y-0.5 active:translate-y-0">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    PDF
                                </a>

                                <!-- Print -->
                                <a :href="'{{ route('laporan-harian.print') }}?' + new URLSearchParams({
                                    tanggal_laporan: '{{ $tanggalLaporan }}',
                                    tahun_anggaran: '{{ $tahunAnggaran }}',
                                    tahun_emisi: '{{ $tahunEmisi }}',
                                    showHcs: showHcs,
                                    showMonitoring: showMonitoring,
                                    showHcts: showHcts,
                                    showAnnual: showAnnual,
                                    showMonthly: showMonthly
                                }).toString()"
                                    class="inline-flex items-center px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-black rounded-xl transition-all duration-200 shadow-lg shadow-indigo-100 hover:shadow-indigo-200 hover:-translate-y-0.5 active:translate-y-0 group">
                                    <svg class="w-4 h-4 mr-2 group-hover:rotate-12 transition-transform" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                    </svg>
                                    Print
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Sections -->
            <div class="space-y-8 mt-8">
                @include('laporan-harian.partials.report-tables')
            </div>

        </div>
    </div>

</x-app-layout>