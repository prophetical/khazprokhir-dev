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
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <!-- Filter & Action Row -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl mb-6 p-6 border border-gray-100">
                <div class="flex flex-col lg:flex-row justify-between items-end gap-6">
                    <form action="{{ route('laporan-harian.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end flex-grow">
                        <div>
                            <label for="tanggal_laporan" class="block text-sm font-semibold text-gray-700 mb-2 tracking-wider">Tanggal Laporan</label>
                            <input type="date" name="tanggal_laporan" id="tanggal_laporan" value="{{ $tanggalLaporan }}"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 py-2.5 text-center font-bold">
                        </div>

                        <div>
                            <label for="tahun_anggaran" class="block text-sm font-semibold text-gray-700 mb-2 tracking-wider">Tahun Anggaran</label>
                            <select name="tahun_anggaran" id="tahun_anggaran"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 py-2.5 text-center font-bold">
                                @foreach($tahunAnggaranOptions as $year)
                                    <option value="{{ $year }}" {{ $tahunAnggaran == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="tahun_emisi" class="block text-sm font-semibold text-gray-700 mb-2 tracking-wider">Tahun Emisi</label>
                            <div class="flex gap-2">
                                <select name="tahun_emisi" id="tahun_emisi"
                                    class="flex-grow rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 py-2.5 text-center font-bold">
                                    @foreach($tahunEmisiOptions as $emisi)
                                        <option value="{{ $emisi }}" {{ $tahunEmisi == $emisi ? 'selected' : '' }}>{{ $emisi }}</option>
                                    @endforeach
                                </select>
                                <button type="submit"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 rounded-lg shadow-lg shadow-indigo-200 transition duration-150 flex items-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-2 shrink-0 h-[46px] items-center">
                        <a href="{{ route('laporan-harian.export', request()->all()) }}" class="inline-flex items-center px-4 py-2.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg hover:bg-emerald-100 transition shadow-sm font-bold text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            Excel
                        </a>
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
                        }).toString()" 
                           target="_blank" 
                           class="inline-flex items-center px-4 py-2.5 bg-rose-50 text-rose-700 border border-rose-200 rounded-lg hover:bg-rose-100 transition shadow-sm font-bold text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                            PDF
                        </a>
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
                           class="inline-flex items-center px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-indigo-100 group">
                            <svg class="w-4 h-4 mr-2 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                            Print
                        </a>
                    </div>
                </div>
            </div>


            <!-- Table Section -->
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl border border-gray-100">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-lg font-black text-indigo-600 tracking-tight flex items-center">
                        <span class="w-2 h-6 bg-indigo-600 rounded-full mr-3"></span>
                        Laporan Persediaan HCS {{ \Carbon\Carbon::parse($tanggalLaporan)->locale('id')->isoFormat('dddd, D MMMM Y') }} TA {{ $tahunAnggaran }}
                    </h3>
                    <div class="flex items-center gap-4">
                        <div class="relative w-64 group">
                            <input type="text" x-model="search" placeholder="Cari Pecahan..." 
                                class="w-full pl-10 pr-4 py-2 rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all group-hover:border-indigo-300 shadow-sm">
                            <div class="absolute left-3 top-2.5 text-gray-400 group-hover:text-indigo-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            </div>
                        </div>
                        <button @click="showHcs = !showHcs" class="p-2 hover:bg-indigo-100 rounded-lg transition-colors text-indigo-600">
                            <svg class="w-6 h-6 transition-transform duration-200" :class="{ 'rotate-180': !showHcs }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div x-show="showHcs" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2">
                    <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100/80">
                            <tr class="text-[10px] font-black uppercase text-gray-500 tracking-widest divide-x divide-gray-200">
                                <th rowspan="2" class="px-4 py-4 text-center sticky left-0 bg-gray-100 z-10 w-20">Pecahan</th>
                                <th colspan="3" class="px-4 py-2 text-center text-indigo-600 bg-indigo-50/50">Persediaan</th>
                                <th rowspan="2" class="px-4 py-4 text-center text-indigo-900 bg-indigo-50/80">Total Persediaan</th>
                                <th colspan="3" class="px-4 py-2 text-center text-pink-600 bg-pink-50/50">Penyerahan HCS</th>
                                <th colspan="3" class="px-4 py-2 text-center text-emerald-600 bg-emerald-50/50">Target & Pencapaian</th>
                                <th rowspan="2" class="px-4 py-4 text-center">Akumulasi<br>Terima HCS</th>
                            </tr>
                            <tr class="text-[9px] font-bold text-gray-400 divide-x divide-gray-200">
                                <th class="px-3 py-2 text-center">Siap Kemas<br>(Bilyet)</th>
                                <th class="px-3 py-2 text-center">Siap Kirim<br>(Bilyet)</th>
                                <th class="px-3 py-2 text-center">Siap Kirim<br>(Dus)</th>
                                <th class="px-3 py-2 text-center">Hari Ini<br>(Bilyet)</th>
                                <th class="px-3 py-2 text-center">Hari Ini<br>(Dus)</th>
                                <th class="px-3 py-2 text-center">Akumulasi<br>(Bilyet)</th>
                                <th class="px-3 py-2 text-center w-32">Target</th>
                                <th class="px-3 py-2 text-center w-32">Sisa</th>
                                <th class="px-3 py-2 text-center w-24">%</th>
                            </tr>
                            <tr class="text-[9px] lowercase text-gray-400 divide-x divide-gray-200 bg-gray-50/50">
                                <th class="px-3 py-1 text-center bg-gray-100 italic">a</th>
                                <th class="px-3 py-1 text-center italic">b</th>
                                <th class="px-3 py-1 text-center italic">c</th>
                                <th class="px-3 py-1 text-center italic">d=c/20.000</th>
                                <th class="px-3 py-1 text-center italic">e=b+c</th>
                                <th class="px-3 py-1 text-center italic">f</th>
                                <th class="px-3 py-1 text-center italic">g=f/20.000</th>
                                <th class="px-3 py-1 text-center italic">h</th>
                                <th class="px-3 py-1 text-center italic">i</th>
                                <th class="px-3 py-1 text-center italic">j=i-h</th>
                                <th class="px-3 py-1 text-center italic">k=i/j*100%</th>
                                <th class="px-3 py-1 text-center italic">l</th>
                            </tr>
                        </thead>
                        
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($reportData as $row)
                                <tr x-show="search === '' || '{{ $row['pecahan'] }}'.toLowerCase().includes(search.toLowerCase())"
                                    class="hover:bg-indigo-50/40 transition duration-150 group">
                                    <td class="px-4 py-4 text-center sticky left-0 bg-white group-hover:bg-indigo-50/40 z-10 border-r border-gray-100">
                                        <span class="inline-flex items-center justify-center h-7 w-7 rounded-lg shadow-sm font-black text-xs border {{ (is_array($colorMap) && isset($colorMap[$row['pecahan']])) ? $colorMap[$row['pecahan']] : 'bg-gray-900 text-white' }}">
                                            {{ $row['pecahan'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-right text-xs font-bold text-gray-600 border-r border-gray-100">{{ $row['siap_kemas_bilyet'] == 0 ? '-' : number_format($row['siap_kemas_bilyet'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-4 text-right text-xs font-bold text-gray-600 border-r border-gray-100">{{ $row['siap_kirim_bilyet'] == 0 ? '-' : number_format($row['siap_kirim_bilyet'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-4 text-right text-[10px] font-black text-gray-400 border-r border-gray-100 italic">{{ $row['siap_kirim_dus'] == 0 ? '-' : number_format($row['siap_kirim_dus'], 0, ',', '.')}}</td>
                                    <td class="px-4 py-4 text-right text-xs font-black text-indigo-700 bg-indigo-50/10 border-r border-gray-100">{{ $row['total_persediaan_bilyet'] == 0 ? '-' : number_format($row['total_persediaan_bilyet'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-4 text-right text-xs font-bold text-gray-600 border-r border-gray-100">{{ $row['penyerahan_hari_ini_bilyet'] == 0 ? '-' : number_format($row['penyerahan_hari_ini_bilyet'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-4 text-right text-[10px] font-black text-pink-600 border-r border-gray-100 italic">{{ $row['penyerahan_hari_ini_dus'] == 0 ? '-' : number_format($row['penyerahan_hari_ini_dus'], 0, ',', '.')}}</td>
                                    <td class="px-4 py-4 text-right text-xs font-black text-pink-700 bg-pink-50/10 border-r border-gray-100">{{ $row['akumulasi_penyerahan'] == 0 ? '-' : number_format($row['akumulasi_penyerahan'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-4 text-right text-xs font-bold text-gray-600 border-r border-gray-100">{{ $row['target'] == 0 ? '-' : number_format($row['target'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-4 text-right text-xs font-bold text-gray-600 border-r border-gray-100">{{ $row['sisa_target'] == 0 ? '-' : number_format($row['sisa_target'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-4 text-center border-r border-gray-100">
                                        <div class="space-y-1">
                                            <div class="flex justify-between items-center px-1">
                                                <span class="text-[10px] font-black {{ $row['persentase_target'] >= 80 ? 'text-emerald-600' : ($row['persentase_target'] >= 50 ? 'text-amber-600' : 'text-rose-600') }}">
                                                    {{ number_format($row['persentase_target'], 2, ',', '.') }}%
                                                </span>
                                            </div>
                                            <div class="w-full bg-gray-100 h-1.5 rounded-full overflow-hidden">
                                                <div class="h-full rounded-full {{ $row['persentase_target'] >= 80 ? 'bg-emerald-500' : ($row['persentase_target'] >= 50 ? 'bg-amber-500' : 'bg-rose-500') }}" 
                                                     style="width: {{ min(100, $row['persentase_target']) }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-right text-xs font-black text-gray-800 bg-gray-50/30">{{ $row['akumulasi_penerimaan_hcs'] == 0 ? '-' : number_format($row['akumulasi_penerimaan_hcs'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        @php
                            $totalPct = $totals['target'] > 0 ? ($totals['akumulasi_penyerahan_bilyet'] / $totals['target']) * 100 : 0;
                        @endphp
                        <tfoot class="bg-gray-900 text-white text-xs font-black uppercase">
                            <tr class="divide-x divide-gray-700">
                                <td class="px-4 py-6 text-center uppercase tracking-widest sticky left-0 bg-gray-900 z-10">TOTAL</td>
                                <td class="px-4 py-6 text-right">{{ $totals['siap_kemas_bilyet'] == 0 ? '-' : number_format($totals['siap_kemas_bilyet'], 0, ',', '.') }}</td>
                                <td class="px-4 py-6 text-right">{{ $totals['siap_kirim_bilyet'] == 0 ? '-' : number_format($totals['siap_kirim_bilyet'], 0, ',', '.') }}</td>
                                <td class="px-4 py-6 text-right text-[10px] text-gray-400 italic">
                                    @php $siapKirimTotalDus = $totals['siap_kirim_bilyet'] / 20000; @endphp
                                    {{ $siapKirimTotalDus == 0 ? '-' : number_format($siapKirimTotalDus, 0, ',', '.')}}
                                </td>
                                <td class="px-4 py-6 text-right text-indigo-300">{{ $totals['total_persediaan_bilyet'] == 0 ? '-' : number_format($totals['total_persediaan_bilyet'], 0, ',', '.') }}</td>
                                <td class="px-4 py-6 text-right">{{ $totals['penyerahan_hari_ini_bilyet'] == 0 ? '-' : number_format($totals['penyerahan_hari_ini_bilyet'], 0, ',', '.') }}</td>
                                <td class="px-4 py-6 text-right text-[10px] text-pink-300 italic">
                                    @php $penyerahanTotalDus = $totals['penyerahan_hari_ini_bilyet'] / 20000; @endphp
                                    {{ $penyerahanTotalDus == 0 ? '-' : number_format($penyerahanTotalDus, 0, ',', '.')}}
                                </td>
                                <td class="px-4 py-6 text-right text-pink-300">{{ $totals['akumulasi_penyerahan_bilyet'] == 0 ? '-' : number_format($totals['akumulasi_penyerahan_bilyet'], 0, ',', '.') }}</td>
                                <td class="px-4 py-6 text-right">{{ $totals['target'] == 0 ? '-' : number_format($totals['target'], 0, ',', '.') }}</td>
                                <td class="px-4 py-6 text-right">{{ $totals['sisa_target'] == 0 ? '-' : number_format($totals['sisa_target'], 0, ',', '.') }}</td>
                                <td class="px-4 py-6 text-center text-xs">
                                    <div class="flex items-center gap-2">
                                        {{ $totalPct == 0 ? '-' : number_format($totalPct, 2, ',', '.') . '%' }}
                                        <div class="flex-grow bg-white/10 h-1.5 rounded-full overflow-hidden max-w-[60px]">
                                            <div class="bg-indigo-400 h-full rounded-full" style="width: {{ min(100, $totalPct) }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-6 text-right text-amber-300">{{ $totals['akumulasi_penerimaan_hcs'] == 0 ? '-' : number_format($totals['akumulasi_penerimaan_hcs'], 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                    </div>
                </div>
            </div>

            <!-- Secondary Table Section: Monitoring & Production -->
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl border border-gray-100 mt-8">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-lg font-black text-gray-800 tracking-tight flex items-center text-green-600">
                        <span class="w-2 h-6 bg-green-600 rounded-full mr-3"></span>
                        Monitoring Target & Produksi HCS {{ \Carbon\Carbon::parse($tanggalLaporan)->locale('id')->isoFormat('dddd, D MMMM Y') }} TA {{ $tahunAnggaran }}
                    </h3>
                    <button @click="showMonitoring = !showMonitoring" class="p-2 hover:bg-green-100 rounded-lg transition-colors text-green-600">
                        <svg class="w-6 h-6 transition-transform duration-200" :class="{ 'rotate-180': !showMonitoring }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                        </svg>
                    </button>
                </div>
                
                <div x-show="showMonitoring" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2">
                    <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100/80">
                            <tr class="text-[10px] font-black uppercase text-gray-500 tracking-widest divide-x divide-gray-200">
                                <th rowspan="2" class="px-4 py-4 text-center sticky left-0 bg-gray-100 z-10 w-20">Pecahan</th>
                                <th colspan="4" class="px-4 py-2 text-center text-blue-600 bg-blue-50/50">Target & Realisasi Pengemasan {{ \Carbon\Carbon::parse($tanggalLaporan)->locale('id')->isoFormat('MMMM') }}</th>
                                <th class="px-4 py-2 text-center bg-amber-50/50">
                                    <span class="block text-amber-700 text-[10px] font-black uppercase tracking-tight">Target Produksi</span>
                                    <span class="text-[9px] font-bold text-amber-500 italic">{{ $sisaHariKerja }} Hari Kerja</span>
                                </th>
                                <th colspan="4" class="px-4 py-2 text-center bg-emerald-50/50">
                                    <span class="block text-emerald-700 text-[10px] font-black uppercase tracking-tight">Pengemasan</span>
                                    <span class="text-[9px] font-bold text-emerald-500 italic">{{ \Carbon\Carbon::parse($tanggalLaporan)->subDay()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                                </th>
                                <th colspan="3" class="px-4 py-2 text-center bg-purple-50/50">
                                    <span class="block text-purple-700 text-[10px] font-black uppercase tracking-tight">Penerimaan HCS</span>
                                    <span class="text-[9px] font-bold text-purple-500 italic">{{ \Carbon\Carbon::parse($tanggalLaporan)->subDay()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                                </th>
                            </tr>
                            <tr class="text-[9px] font-bold text-gray-400 divide-x divide-gray-200">
                                <th class="px-3 py-2 text-center">Target<br>(Bilyet)</th>
                                <th class="px-3 py-2 text-center">Realisasi<br>(Bilyet)</th>
                                <th class="px-3 py-2 text-center">Sisa Target<br>(Bilyet)</th>
                                <th class="px-3 py-2 text-center">Sisa Target<br>(Dus)</th>
                                <th class="px-3 py-2 text-center bg-amber-100/50 text-amber-900">Target / Hari<br>(Bilyet)</th>
                                <th class="px-3 py-2 text-center">Gilir 1</th>
                                <th class="px-3 py-2 text-center">Gilir 2</th>
                                <th class="px-3 py-2 text-center">Gilir 3</th>
                                <th class="px-3 py-2 text-center font-black text-emerald-700">Total</th>
                                <th class="px-3 py-2 text-center">Rikyet</th>
                                <th class="px-3 py-2 text-center">Cutpack</th>
                                <th class="px-3 py-2 text-center font-black text-purple-700">Total</th>
                            </tr>
                            <tr class="text-[9px] lowercase text-gray-400 divide-x divide-gray-200 bg-gray-50/50">
                                <th class="px-3 py-1 text-center bg-gray-100 italic">a</th>
                                <th class="px-3 py-1 text-center italic">b</th>
                                <th class="px-3 py-1 text-center italic">c</th>
                                <th class="px-3 py-1 text-center italic">d=b-c</th>
                                <th class="px-3 py-1 text-center italic">e=d/20.000</th>
                                <th class="px-3 py-1 text-center italic">f=d/{{ $sisaHariKerja }}</th>
                                <th class="px-3 py-1 text-center italic">g</th>
                                <th class="px-3 py-1 text-center italic">h</th>
                                <th class="px-3 py-1 text-center italic">i</th>
                                <th class="px-3 py-1 text-center italic">j=g+h+i</th>
                                <th class="px-3 py-1 text-center italic">k</th>
                                <th class="px-3 py-1 text-center italic">l</th>
                                <th class="px-3 py-1 text-center italic">m=k+l</th>
                            </tr>
                        </thead>
                        
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($secondaryData as $row)
                                <tr class="hover:bg-gray-50 transition duration-150 group divide-x divide-gray-100">
                                    <td class="px-4 py-4 text-center sticky left-0 bg-white group-hover:bg-gray-50 z-10">
                                        <span class="inline-flex items-center justify-center h-7 w-7 rounded-lg shadow-sm font-black text-xs border {{ (is_array($colorMap) && isset($colorMap[$row['pecahan']])) ? $colorMap[$row['pecahan']] : 'bg-gray-900 text-white' }}">
                                            {{ $row['pecahan'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-right text-xs font-bold text-gray-600">{{ $row['target_penyerahan_bulan'] == 0 ? '-' : number_format($row['target_penyerahan_bulan'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-4 text-right text-xs font-bold text-gray-600">{{ $row['penyerahan_bulan'] == 0 ? '-' : number_format($row['penyerahan_bulan'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-4 text-right text-xs font-black">
                                        @if($row['sisa_target_bilyet'] < 0)
                                            <span class="text-green-600">+{{ number_format(abs($row['sisa_target_bilyet']), 0, ',', '.') }}</span>
                                        @elseif($row['sisa_target_bilyet'] > 0)
                                            <span class="text-blue-700">{{ number_format($row['sisa_target_bilyet'], 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-right text-[10px] font-black italic">
                                        @if($row['sisa_target_doos'] < 0)
                                            <span class="text-green-600">+{{ number_format(abs($row['sisa_target_doos']), 0, ',', '.') }}</span>
                                        @elseif($row['sisa_target_doos'] > 0)
                                            <span class="text-gray-400">{{ number_format($row['sisa_target_doos'], 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-gray-300">-</span>
                                        @endif
                                    </td>
                                    
                                    <td class="px-4 py-4 text-right text-xs font-black text-amber-900 bg-amber-50/50">{{ $row['target_produksi_harian'] <= 0 ? '-' : number_format($row['target_produksi_harian'], 0, ',', '.') }}</td>

                                    <td class="px-4 py-4 text-right text-[10px] font-bold text-gray-500">{{ $row['kemas_g1'] == 0 ? '-' : number_format($row['kemas_g1'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-4 text-right text-[10px] font-bold text-gray-500">{{ $row['kemas_g2'] == 0 ? '-' : number_format($row['kemas_g2'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-4 text-right text-[10px] font-bold text-gray-500">{{ $row['kemas_g3'] == 0 ? '-' : number_format($row['kemas_g3'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-4 text-right text-xs font-black text-emerald-700 bg-emerald-50/20">{{ $row['total_kemas'] == 0 ? '-' : number_format($row['total_kemas'], 0, ',', '.') }}</td>

                                    <td class="px-4 py-4 text-right text-[10px] font-bold text-gray-500">{{ $row['hcs_rikyet'] == 0 ? '-' : number_format($row['hcs_rikyet'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-4 text-right text-[10px] font-bold text-gray-500">{{ $row['hcs_cutpack'] == 0 ? '-' : number_format($row['hcs_cutpack'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-4 text-right text-xs font-black text-purple-700 bg-purple-50/20">{{ $row['total_hcs'] == 0 ? '-' : number_format($row['total_hcs'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>

                        <tfoot class="bg-gray-800 text-white font-black text-xs divide-x divide-gray-700">
                            <tr class="divide-x divide-gray-700">
                                <td class="px-4 py-4 text-center uppercase sticky left-0 bg-gray-800 z-10 tracking-widest">TOTAL</td>
                                <td class="px-4 py-4 text-right">{{ $secondaryTotals['target_penyerahan_bulan'] == 0 ? '-' : number_format($secondaryTotals['target_penyerahan_bulan'], 0, ',', '.') }}</td>
                                <td class="px-4 py-4 text-right">{{ $secondaryTotals['penyerahan_bulan'] == 0 ? '-' : number_format($secondaryTotals['penyerahan_bulan'], 0, ',', '.') }}</td>
                                <td class="px-4 py-4 text-right">
                                    @if($secondaryTotals['sisa_target_bilyet'] < 0)
                                        <span class="text-green-400">+{{ number_format(abs($secondaryTotals['sisa_target_bilyet']), 0, ',', '.') }}</span>
                                    @elseif($secondaryTotals['sisa_target_bilyet'] > 0)
                                        <span class="text-blue-300">{{ number_format($secondaryTotals['sisa_target_bilyet'], 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-gray-500">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-right italic font-normal">
                                    @if($secondaryTotals['sisa_target_doos'] < 0)
                                        <span class="text-green-500 text-[10px]">+{{ number_format(abs($secondaryTotals['sisa_target_doos']), 0, ',', '.') }}</span>
                                    @elseif($secondaryTotals['sisa_target_doos'] > 0)
                                        <span class="text-gray-400 text-[10px]">{{ number_format($secondaryTotals['sisa_target_doos'], 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-gray-600">-</span>
                                    @endif
                                </td>
                                
                                <td class="px-4 py-4 text-right text-amber-400">{{ $secondaryTotals['target_produksi_harian'] <= 0 ? '-' : number_format($secondaryTotals['target_produksi_harian'], 0, ',', '.') }}</td>

                                <td class="px-4 py-4 text-right">{{ $secondaryTotals['kemas_g1'] == 0 ? '-' : number_format($secondaryTotals['kemas_g1'], 0, ',', '.') }}</td>
                                <td class="px-4 py-4 text-right">{{ $secondaryTotals['kemas_g2'] == 0 ? '-' : number_format($secondaryTotals['kemas_g2'], 0, ',', '.') }}</td>
                                <td class="px-4 py-4 text-right">{{ $secondaryTotals['kemas_g3'] == 0 ? '-' : number_format($secondaryTotals['kemas_g3'], 0, ',', '.') }}</td>
                                <td class="px-4 py-4 text-right text-emerald-400">{{ $secondaryTotals['total_kemas'] == 0 ? '-' : number_format($secondaryTotals['total_kemas'], 0, ',', '.') }}</td>

                                <td class="px-4 py-4 text-right">{{ $secondaryTotals['hcs_rikyet'] == 0 ? '-' : number_format($secondaryTotals['hcs_rikyet'], 0, ',', '.') }}</td>
                                <td class="px-4 py-4 text-right">{{ $secondaryTotals['hcs_cutpack'] == 0 ? '-' : number_format($secondaryTotals['hcs_cutpack'], 0, ',', '.') }}</td>
                                <td class="px-4 py-4 text-right text-purple-400">{{ $secondaryTotals['total_hcs'] == 0 ? '-' : number_format($secondaryTotals['total_hcs'], 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                    </div>
                </div>
            </div>
            <!-- HCTS Inventory Report Section -->
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl border border-gray-100 mt-8">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-lg font-black text-orange-600 tracking-tight flex items-center">
                        <span class="w-2 h-6 bg-orange-600 rounded-full mr-3"></span>
                        Laporan Persediaan HCTS {{ \Carbon\Carbon::parse($tanggalLaporan)->locale('id')->isoFormat('dddd, D MMMM Y') }} TA {{ $tahunAnggaran }}
                    </h3>
                    <button @click="showHcts = !showHcts" class="p-2 hover:bg-orange-100 rounded-lg transition-colors text-orange-600">
                        <svg class="w-6 h-6 transition-transform duration-200" :class="{ 'rotate-180': !showHcts }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                        </svg>
                    </button>
                </div>
                
                <div x-show="showHcts" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2">
                    <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100/80">
                            <tr class="text-[10px] font-black uppercase text-gray-500 tracking-widest divide-x divide-gray-200">
                                <th class="px-4 py-4 text-center sticky left-0 bg-gray-100 z-10 w-20">Pecahan</th>
                                <th class="px-4 py-4 text-center">Penerimaan<br><span class="text-[9px] font-bold italic">{{ \Carbon\Carbon::parse($tanggalLaporan)->subDay()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span></th>
                                <th class="px-4 py-4 text-center text-emerald-600 bg-emerald-50/30">Penyerahan<br><span class="text-[9px] font-bold italic">{{ \Carbon\Carbon::parse($tanggalLaporan)->locale('id')->isoFormat('dddd, D MMMM Y') }}</span></th>
                                <th class="px-4 py-4 text-center">Akumulasi<br>Penerimaan</th>
                                <th class="px-4 py-4 text-center">Akumulasi<br>Penyerahan</th>
                                <th class="px-4 py-4 text-center text-indigo-600 bg-indigo-50/50">Persediaan<br>HCTS</th>
                                <th class="px-4 py-4 text-center text-amber-600 bg-amber-50/30">Container<br>Siap Hitung</th>
                            </tr>
                            <tr class="text-[9px] lowercase text-gray-400 divide-x divide-gray-200 bg-gray-50/50">
                                <th class="px-3 py-1 text-center bg-gray-100 italic">a</th>
                                <th class="px-3 py-1 text-center italic">b</th>
                                <th class="px-3 py-1 text-center italic">c</th>
                                <th class="px-3 py-1 text-center italic">d</th>
                                <th class="px-3 py-1 text-center italic">e</th>
                                <th class="px-3 py-1 text-center italic">f=d-e</th>
                                <th class="px-3 py-1 text-center italic">g=f/3.000.000</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach(['S', 'T', 'U', 'V', 'W', 'X', 'Y'] as $pec)
                                @php $data = $hctsInventoryData[$pec] ?? ['penerimaan_h1'=>0, 'penyerahan_hari_ini'=>0, 'akumulasi_penerimaan'=>0, 'akumulasi_penyerahan'=>0, 'persediaan'=>0, 'ct_siap_hitung'=>0]; @endphp
                                <tr class="hover:bg-gray-50 transition duration-150 divide-x divide-gray-100">
                                    <td class="px-4 py-4 text-center sticky left-0 bg-white group-hover:bg-gray-50 z-10">
                                        <span class="inline-flex items-center justify-center h-7 w-7 rounded-lg shadow-sm font-black text-xs border {{ (is_array($colorMap) && isset($colorMap[$pec])) ? $colorMap[$pec] : 'bg-gray-900 text-white' }}">
                                            {{ $pec }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-right text-xs font-bold text-gray-600">{{ $data['penerimaan_h1'] == 0 ? '-' : number_format($data['penerimaan_h1'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-4 text-right text-xs font-black text-emerald-700 bg-emerald-50/10">{{ $data['penyerahan_hari_ini'] == 0 ? '-' : number_format($data['penyerahan_hari_ini'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-4 text-right text-xs font-bold text-gray-600">{{ $data['akumulasi_penerimaan'] == 0 ? '-' : number_format($data['akumulasi_penerimaan'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-4 text-right text-xs font-bold text-gray-600">{{ $data['akumulasi_penyerahan'] == 0 ? '-' : number_format($data['akumulasi_penyerahan'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-4 text-right text-xs font-black text-indigo-700 bg-indigo-50/20">{{ $data['persediaan'] == 0 ? '-' : number_format($data['persediaan'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <span class="text-xs font-black text-amber-700">{{ $data['ct_siap_hitung'] == 0 ? '-' : $data['ct_siap_hitung'] }}</span>
                                            @if($data['ct_siap_hitung'] > 0)
                                                <span class="text-[8px] font-black text-amber-400 uppercase">CT</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-900 text-white font-black text-xs divide-x divide-gray-700">
                            @php
                                $totalPenerimaanH1 = collect($hctsInventoryData)->sum('penerimaan_h1');
                                $totalPenyerahanHariIni = collect($hctsInventoryData)->sum('penyerahan_hari_ini');
                                $totalAkumulasiTerima = collect($hctsInventoryData)->sum('akumulasi_penerimaan');
                                $totalAkumulasiSerah = collect($hctsInventoryData)->sum('akumulasi_penyerahan');
                                $totalPersediaan = collect($hctsInventoryData)->sum('persediaan');
                                $totalCT = collect($hctsInventoryData)->sum('ct_siap_hitung');
                            @endphp
                            <tr class="divide-x divide-gray-700">
                                <td class="px-4 py-4 text-center uppercase tracking-widest sticky left-0 bg-gray-900 z-10">TOTAL</td>
                                <td class="px-4 py-4 text-right">{{ $totalPenerimaanH1 == 0 ? '-' : number_format($totalPenerimaanH1, 0, ',', '.') }}</td>
                                <td class="px-4 py-4 text-right text-emerald-400">{{ $totalPenyerahanHariIni == 0 ? '-' : number_format($totalPenyerahanHariIni, 0, ',', '.') }}</td>
                                <td class="px-4 py-4 text-right">{{ $totalAkumulasiTerima == 0 ? '-' : number_format($totalAkumulasiTerima, 0, ',', '.') }}</td>
                                <td class="px-4 py-4 text-right">{{ $totalAkumulasiSerah == 0 ? '-' : number_format($totalAkumulasiSerah, 0, ',', '.') }}</td>
                                <td class="px-4 py-4 text-right text-indigo-300">{{ $totalPersediaan == 0 ? '-' : number_format($totalPersediaan, 0, ',', '.') }}</td>
                                <td class="px-4 py-4 text-center text-amber-400">{{ $totalCT == 0 ? '-' : $totalCT . ' CT' }}</td>
                            </tr>
                        </tfoot>
                    </table>
                    </div>
                </div>
            </div>

            <!-- Rekap Pencapaian Target Pengemasan Section -->
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl border border-gray-100 mt-8">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-lg font-black text-yellow-600 tracking-tight flex items-center">
                        <span class="w-2 h-6 bg-yellow-600 rounded-full mr-3"></span>
                        Laporan Pencapaian Target Pengemasan Tahunan {{ \Carbon\Carbon::parse($tanggalLaporan)->locale('id')->isoFormat('dddd, D MMMM Y') }} TA {{ $tahunAnggaran }}
                    </h3>
                    <button @click="showAnnual = !showAnnual" class="p-2 hover:bg-yellow-100 rounded-lg transition-colors text-yellow-600">
                        <svg class="w-6 h-6 transition-transform duration-200" :class="{ 'rotate-180': !showAnnual }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                        </svg>
                    </button>
                </div>
                
                <div x-show="showAnnual" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2">
                    <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100/80">
                            <tr class="text-[10px] font-black uppercase text-gray-500 tracking-widest divide-x divide-gray-200">
                                <th rowspan="2" class="px-4 py-4 text-center sticky left-0 bg-gray-100 z-10 w-20">PEC</th>
                                <th colspan="2" class="px-4 py-2 text-center text-indigo-600 bg-indigo-50/30">Target TA {{ $tahunAnggaran }}</th>
                                <th colspan="2" class="px-4 py-2 text-center text-emerald-600 bg-emerald-50/30">Akumulasi Pengemasan</th>
                                <th colspan="2" class="px-4 py-2 text-center text-rose-600 bg-rose-50/30">Sisa / Over</th>
                                <th rowspan="2" class="px-4 py-4 text-center text-amber-600 bg-amber-50/30">% Pencapaian</th>
                            </tr>
                            <tr class="text-[9px] font-bold text-gray-400 divide-x divide-gray-200">
                                <th class="px-3 py-2 text-center bg-indigo-50/10">Bilyet</th>
                                <th class="px-3 py-2 text-center bg-indigo-50/10">Dus</th>
                                <th class="px-3 py-2 text-center bg-emerald-50/10">Bilyet</th>
                                <th class="px-3 py-2 text-center bg-emerald-50/10">Dus</th>
                                <th class="px-3 py-2 text-center bg-rose-50/10">Bilyet</th>
                                <th class="px-3 py-2 text-center bg-rose-50/10">Dus</th>
                            </tr>
                            <tr class="text-[9px] lowercase text-gray-400 divide-x divide-gray-200 bg-gray-50/50">
                                <th class="px-3 py-1 text-center bg-gray-100 italic">a</th>
                                <th class="px-3 py-1 text-center italic">b</th>
                                <th class="px-3 py-1 text-center italic">c=b/20.000</th>
                                <th class="px-3 py-1 text-center italic">d</th>
                                <th class="px-3 py-1 text-center italic">e=d/20.000</th>
                                <th class="px-3 py-1 text-center italic">f=b-d</th>
                                <th class="px-3 py-1 text-center italic">g=f/20.000</th>
                                <th class="px-3 py-1 text-center italic">h=d/b*100%</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($targetAchievementData['data'] as $row)
                                <tr class="hover:bg-gray-50 transition duration-150 divide-x divide-gray-100">
                                    <td class="px-4 py-4 text-center sticky left-0 bg-white group-hover:bg-gray-50 z-10">
                                        <span class="inline-flex items-center justify-center h-7 w-7 rounded-lg shadow-sm font-black text-xs border {{ (is_array($colorMap) && isset($colorMap[$row['pecahan']])) ? $colorMap[$row['pecahan']] : 'bg-gray-900 text-white' }}">
                                            {{ $row['pecahan'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-right text-xs font-bold text-gray-600 italic">
                                        {{ $row['target_bilyet'] == 0 ? '-' : number_format($row['target_bilyet'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-4 text-right text-xs font-bold text-indigo-700 bg-indigo-50/10">
                                        {{ $row['target_dus'] == 0 ? '-' : number_format($row['target_dus'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-4 text-right text-xs font-bold text-gray-600 italic">
                                        {{ $row['akumulasi_bilyet'] == 0 ? '-' : number_format($row['akumulasi_bilyet'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-4 text-right text-xs font-bold text-emerald-700 bg-emerald-50/10">
                                        {{ $row['akumulasi_dus'] == 0 ? '-' : number_format($row['akumulasi_dus'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-4 text-right text-xs font-black {{ $row['sisa_bilyet'] < 0 ? 'text-emerald-600' : ($row['sisa_bilyet'] > 0 ? 'text-rose-600' : 'text-gray-400') }}">
                                        @if($row['sisa_bilyet'] < 0)
                                            +{{ number_format(abs($row['sisa_bilyet']), 0, ',', '.') }}
                                        @elseif($row['sisa_bilyet'] > 0)
                                            {{ number_format($row['sisa_bilyet'], 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-right text-xs font-black {{ $row['sisa_dus'] < 0 ? 'text-emerald-600' : ($row['sisa_dus'] > 0 ? 'text-rose-600' : 'text-gray-400') }}">
                                        @if($row['sisa_dus'] < 0)
                                            +{{ number_format(abs($row['sisa_dus']), 0, ',', '.') }}
                                        @elseif($row['sisa_dus'] > 0)
                                            {{ number_format($row['sisa_dus'], 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <div class="flex flex-col items-center gap-1">
                                            <span class="text-xs font-black {{ $row['persen'] >= 100 ? 'text-emerald-600' : ($row['persen'] >= 50 ? 'text-amber-600' : 'text-rose-600') }}">
                                                {{ number_format($row['persen'], 2, ',', '.') }}%
                                            </span>
                                            <div class="w-20 bg-gray-100 h-1 rounded-full overflow-hidden">
                                                <div class="h-full rounded-full {{ $row['persen'] >= 100 ? 'bg-emerald-500' : ($row['persen'] >= 50 ? 'bg-amber-500' : 'bg-rose-500') }}" 
                                                     style="width: {{ min(100, $row['persen']) }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-900 text-white font-black text-xs divide-x divide-gray-700">
                            @php
                                $totalTargetBilyet = $targetAchievementData['totals']['target_bilyet'];
                                $totalPct = $totalTargetBilyet > 0 ? ($targetAchievementData['totals']['akumulasi_bilyet'] / $totalTargetBilyet) * 100 : 0;
                            @endphp
                            <tr class="divide-x divide-gray-700">
                                <td class="px-4 py-6 text-center uppercase tracking-widest sticky left-0 bg-gray-900 z-10">TOTAL</td>
                                <td class="px-4 py-6 text-right">{{ $targetAchievementData['totals']['target_bilyet'] == 0 ? '-' : number_format($targetAchievementData['totals']['target_bilyet'], 0, ',', '.') }}</td>
                                <td class="px-4 py-6 text-right text-indigo-300">{{ $targetAchievementData['totals']['target_dus'] == 0 ? '-' : number_format($targetAchievementData['totals']['target_dus'], 0, ',', '.') }}</td>
                                <td class="px-4 py-6 text-right">{{ $targetAchievementData['totals']['akumulasi_bilyet'] == 0 ? '-' : number_format($targetAchievementData['totals']['akumulasi_bilyet'], 0, ',', '.') }}</td>
                                <td class="px-4 py-6 text-right text-emerald-400">{{ $targetAchievementData['totals']['akumulasi_dus'] == 0 ? '-' : number_format($targetAchievementData['totals']['akumulasi_dus'], 0, ',', '.') }}</td>
                                <td class="px-4 py-6 text-right {{ $targetAchievementData['totals']['sisa_bilyet'] < 0 ? 'text-emerald-400' : ($targetAchievementData['totals']['sisa_bilyet'] > 0 ? 'text-rose-400' : 'text-gray-500') }}">
                                    @if($targetAchievementData['totals']['sisa_bilyet'] < 0)
                                        +{{ number_format(abs($targetAchievementData['totals']['sisa_bilyet']), 0, ',', '.') }}
                                    @elseif($targetAchievementData['totals']['sisa_bilyet'] > 0)
                                        {{ number_format($targetAchievementData['totals']['sisa_bilyet'], 0, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-6 text-right {{ $targetAchievementData['totals']['sisa_dus'] < 0 ? 'text-emerald-400' : ($targetAchievementData['totals']['sisa_dus'] > 0 ? 'text-rose-400' : 'text-gray-500') }}">
                                    @if($targetAchievementData['totals']['sisa_dus'] < 0)
                                        +{{ number_format(abs($targetAchievementData['totals']['sisa_dus']), 0, ',', '.') }}
                                    @elseif($targetAchievementData['totals']['sisa_dus'] > 0)
                                        {{ number_format($targetAchievementData['totals']['sisa_dus'], 0, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-6 text-center text-amber-400">
                                    {{ number_format($totalPct, 2, ',', '.') }}%
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    </div>
                </div>
            </div>
            <!-- Laporan Target Pengemasan Bulanan Section -->
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl border border-gray-100 mt-8">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-lg font-black text-rose-600 tracking-tight flex items-center">
                        <span class="w-2 h-6 bg-rose-600 rounded-full mr-3"></span>
                        Laporan Pencapaian Target Pengemasan Bulan {{ \Carbon\Carbon::parse($tanggalLaporan)->locale('id')->isoFormat('MMMM Y') }} TA {{ $tahunAnggaran }}
                    </h3>
                    <button @click="showMonthly = !showMonthly" class="p-2 hover:bg-rose-100 rounded-lg transition-colors text-rose-600">
                        <svg class="w-6 h-6 transition-transform duration-200" :class="{ 'rotate-180': !showMonthly }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                        </svg>
                    </button>
                </div>
                
                <div x-show="showMonthly" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2">
                    <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100/80">
                            <tr class="text-[10px] font-black uppercase text-gray-500 tracking-widest divide-x divide-gray-200">
                                <th rowspan="2" class="px-4 py-4 text-center sticky left-0 bg-gray-100 z-10 w-20">PEC</th>
                                <th colspan="2" class="px-4 py-2 text-center text-indigo-600 bg-indigo-50/30">Target Pengemasan {{ \Carbon\Carbon::parse($tanggalLaporan)->locale('id')->isoFormat('MMMM') }}</th>
                                <th colspan="2" class="px-4 py-2 text-center text-emerald-600 bg-emerald-50/30">Akumulasi Pengemasan {{ \Carbon\Carbon::parse($tanggalLaporan)->locale('id')->isoFormat('MMMM') }}</th>
                                <th colspan="2" class="px-4 py-2 text-center text-rose-600 bg-rose-50/30">Sisa / Over</th>
                                <th rowspan="2" class="px-4 py-4 text-center text-amber-600 bg-amber-50/30">% Pencapaian</th>
                            </tr>
                            <tr class="text-[9px] font-bold text-gray-400 divide-x divide-gray-200">
                                <th class="px-3 py-2 text-center bg-indigo-50/10">Bilyet</th>
                                <th class="px-3 py-2 text-center bg-indigo-50/10">Dus</th>
                                <th class="px-3 py-2 text-center bg-emerald-50/10">Bilyet</th>
                                <th class="px-3 py-2 text-center bg-emerald-50/10">Dus</th>
                                <th class="px-3 py-2 text-center bg-rose-50/10">Bilyet</th>
                                <th class="px-3 py-2 text-center bg-rose-50/10">Dus</th>
                            </tr>
                            <tr class="text-[9px] lowercase text-gray-400 divide-x divide-gray-200 bg-gray-50/50">
                                <th class="px-3 py-1 text-center bg-gray-100 italic">a</th>
                                <th class="px-3 py-1 text-center italic">b</th>
                                <th class="px-3 py-1 text-center italic">c=b/20.000</th>
                                <th class="px-3 py-1 text-center italic">d</th>
                                <th class="px-3 py-1 text-center italic">e=d/20.000</th>
                                <th class="px-3 py-1 text-center italic">f=b-d</th>
                                <th class="px-3 py-1 text-center italic">g=f/20.000</th>
                                <th class="px-3 py-1 text-center italic">h=d/b*100%</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($monthlyTargetAchievementData['data'] as $row)
                                <tr class="hover:bg-gray-50 transition duration-150 divide-x divide-gray-100">
                                    <td class="px-4 py-4 text-center sticky left-0 bg-white group-hover:bg-gray-50 z-10">
                                        <span class="inline-flex items-center justify-center h-7 w-7 rounded-lg shadow-sm font-black text-xs border {{ (is_array($colorMap) && isset($colorMap[$row['pecahan']])) ? $colorMap[$row['pecahan']] : 'bg-gray-900 text-white' }}">
                                            {{ $row['pecahan'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-right text-xs font-bold text-gray-600 italic">
                                        {{ $row['target_bilyet'] == 0 ? '-' : number_format($row['target_bilyet'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-4 text-right text-xs font-bold text-indigo-700 bg-indigo-50/10">
                                        {{ $row['target_dus'] == 0 ? '-' : number_format($row['target_dus'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-4 text-right text-xs font-bold text-gray-600 italic">
                                        {{ $row['akumulasi_bilyet'] == 0 ? '-' : number_format($row['akumulasi_bilyet'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-4 text-right text-xs font-bold text-emerald-700 bg-emerald-50/10">
                                        {{ $row['akumulasi_dus'] == 0 ? '-' : number_format($row['akumulasi_dus'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-4 text-right text-xs font-black {{ $row['sisa_bilyet'] < 0 ? 'text-emerald-600' : ($row['sisa_bilyet'] > 0 ? 'text-rose-600' : 'text-gray-400') }}">
                                        @if($row['sisa_bilyet'] < 0)
                                            +{{ number_format(abs($row['sisa_bilyet']), 0, ',', '.') }}
                                        @elseif($row['sisa_bilyet'] > 0)
                                            {{ number_format($row['sisa_bilyet'], 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-right text-xs font-black {{ $row['sisa_dus'] < 0 ? 'text-emerald-600' : ($row['sisa_dus'] > 0 ? 'text-rose-600' : 'text-gray-400') }}">
                                        @if($row['sisa_dus'] < 0)
                                            +{{ number_format(abs($row['sisa_dus']), 2, ',', '.') }}
                                        @elseif($row['sisa_dus'] > 0)
                                            {{ number_format($row['sisa_dus'], 2, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <div class="flex flex-col items-center gap-1">
                                            <span class="text-xs font-black {{ $row['persen'] >= 100 ? 'text-emerald-600' : ($row['persen'] >= 50 ? 'text-amber-600' : 'text-rose-600') }}">
                                                {{ number_format($row['persen'], 2, ',', '.') }}%
                                            </span>
                                            <div class="w-20 bg-gray-100 h-1 rounded-full overflow-hidden">
                                                <div class="h-full rounded-full {{ $row['persen'] >= 100 ? 'bg-emerald-500' : ($row['persen'] >= 50 ? 'bg-amber-500' : 'bg-rose-500') }}" 
                                                     style="width: {{ min(100, $row['persen']) }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-900 text-white font-black text-xs divide-x divide-gray-700">
                            @php
                                $totalTargetBilyet = $monthlyTargetAchievementData['totals']['target_bilyet'];
                                $totalPct = $totalTargetBilyet > 0 ? ($monthlyTargetAchievementData['totals']['akumulasi_bilyet'] / $totalTargetBilyet) * 100 : 0;
                            @endphp
                            <tr class="divide-x divide-gray-700">
                                <td class="px-4 py-6 text-center uppercase tracking-widest sticky left-0 bg-gray-900 z-10">TOTAL</td>
                                <td class="px-4 py-6 text-right">{{ $monthlyTargetAchievementData['totals']['target_bilyet'] == 0 ? '-' : number_format($monthlyTargetAchievementData['totals']['target_bilyet'], 0, ',', '.') }}</td>
                                <td class="px-4 py-6 text-right text-indigo-300">{{ $monthlyTargetAchievementData['totals']['target_dus'] == 0 ? '-' : number_format($monthlyTargetAchievementData['totals']['target_dus'], 0, ',', '.') }}</td>
                                <td class="px-4 py-6 text-right">{{ $monthlyTargetAchievementData['totals']['akumulasi_bilyet'] == 0 ? '-' : number_format($monthlyTargetAchievementData['totals']['akumulasi_bilyet'], 0, ',', '.') }}</td>
                                <td class="px-4 py-6 text-right text-emerald-400">{{ $monthlyTargetAchievementData['totals']['akumulasi_dus'] == 0 ? '-' : number_format($monthlyTargetAchievementData['totals']['akumulasi_dus'], 0, ',', '.') }}</td>
                                <td class="px-4 py-6 text-right {{ $monthlyTargetAchievementData['totals']['sisa_bilyet'] < 0 ? 'text-emerald-400' : ($monthlyTargetAchievementData['totals']['sisa_bilyet'] > 0 ? 'text-rose-400' : 'text-gray-500') }}">
                                    @if($monthlyTargetAchievementData['totals']['sisa_bilyet'] < 0)
                                        +{{ number_format(abs($monthlyTargetAchievementData['totals']['sisa_bilyet']), 0, ',', '.') }}
                                    @elseif($monthlyTargetAchievementData['totals']['sisa_bilyet'] > 0)
                                        {{ number_format($monthlyTargetAchievementData['totals']['sisa_bilyet'], 0, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-6 text-right {{ $monthlyTargetAchievementData['totals']['sisa_dus'] < 0 ? 'text-emerald-400' : ($monthlyTargetAchievementData['totals']['sisa_dus'] > 0 ? 'text-rose-400' : 'text-gray-500') }}">
                                    @if($monthlyTargetAchievementData['totals']['sisa_dus'] < 0)
                                        +{{ number_format(abs($monthlyTargetAchievementData['totals']['sisa_dus']), 2, ',', '.') }}
                                    @elseif($monthlyTargetAchievementData['totals']['sisa_dus'] > 0)
                                        {{ number_format($monthlyTargetAchievementData['totals']['sisa_dus'], 2, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-6 text-center text-amber-400">
                                    {{ number_format($totalPct, 2, ',', '.') }}%
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
