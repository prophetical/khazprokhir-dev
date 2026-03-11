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

    <div class="py-12" x-data="{ search: '' }">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <!-- Filter & Action Row -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl mb-6 p-6 border border-gray-100">
                <div class="flex flex-col lg:flex-row justify-between items-end gap-6">
                    <form action="{{ route('laporan-harian.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end flex-grow">
                        <div>
                            <label for="tanggal_laporan" class="block text-sm font-semibold text-gray-700 mb-2 uppercase tracking-wider">Tanggal Laporan</label>
                            <input type="date" name="tanggal_laporan" id="tanggal_laporan" value="{{ $tanggalLaporan }}"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 py-2.5 text-center font-bold">
                        </div>

                        <div>
                            <label for="tahun_anggaran" class="block text-sm font-semibold text-gray-700 mb-2 uppercase tracking-wider">Tahun Anggaran</label>
                            <select name="tahun_anggaran" id="tahun_anggaran"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 py-2.5 text-center font-bold">
                                @foreach($tahunAnggaranOptions as $year)
                                    <option value="{{ $year }}" {{ $tahunAnggaran == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="tahun_emisi" class="block text-sm font-semibold text-gray-700 mb-2 uppercase tracking-wider">Tahun Emisi</label>
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
                        <a href="{{ route('laporan-harian.print', request()->all()) }}" target="_blank" class="inline-flex items-center px-4 py-2.5 bg-rose-50 text-rose-700 border border-rose-200 rounded-lg hover:bg-rose-100 transition shadow-sm font-bold text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                            PDF
                        </a>
                        <a href="{{ route('laporan-harian.print', array_merge(request()->all(), ['autoprint' => 1])) }}" target="_blank" class="inline-flex items-center px-4 py-2.5 bg-gray-50 text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-100 transition shadow-sm font-bold text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                            Print
                        </a>
                    </div>
                </div>
            </div>

            <!-- Summary Cards --> <!--
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-indigo-600 rounded-2xl p-6 shadow-xl shadow-indigo-100 relative overflow-hidden group hover:scale-[1.02] transition duration-300">
                    <div class="absolute -right-4 -bottom-4 bg-white/10 w-24 h-24 rounded-full group-hover:scale-150 transition duration-500"></div>
                    <p class="text-indigo-100 text-xs font-black uppercase tracking-widest mb-1">Total Persediaan</p>
                    <p class="text-white text-2xl font-black mb-1">{{ $totals['total_persediaan_bilyet'] == 0 ? '-' : number_format($totals['total_persediaan_bilyet'], 0, ',', '.') }}</p>
                    <p class="text-indigo-100/70 text-[10px] font-bold">Bilyet (Siap Kemas + Kirim)</p>
                </div>

                <div class="bg-pink-600 rounded-2xl p-6 shadow-xl shadow-pink-100 relative overflow-hidden group hover:scale-[1.02] transition duration-300">
                    <div class="absolute -right-4 -bottom-4 bg-white/10 w-24 h-24 rounded-full group-hover:scale-150 transition duration-500"></div>
                    <p class="text-pink-100 text-xs font-black uppercase tracking-widest mb-1">Penyerahan Hari Ini</p>
                    <p class="text-white text-2xl font-black mb-1">{{ $totals['penyerahan_hari_ini_bilyet'] == 0 ? '-' : number_format($totals['penyerahan_hari_ini_bilyet'], 0, ',', '.') }}</p>
                    <p class="text-pink-100/70 text-[10px] font-bold">
                        @php $penyerahanTotalDus = $totals['penyerahan_hari_ini_bilyet'] / 20000; @endphp
                        {{ $penyerahanTotalDus == 0 ? '-' : number_format($penyerahanTotalDus, 2, ',', '.')}}
                    </p>
                </div>

                <div class="bg-emerald-600 rounded-2xl p-6 shadow-xl shadow-emerald-100 relative overflow-hidden group hover:scale-[1.02] transition duration-300">
                    <div class="absolute -right-4 -bottom-4 bg-white/10 w-24 h-24 rounded-full group-hover:scale-150 transition duration-500"></div>
                    <p class="text-emerald-100 text-xs font-black uppercase tracking-widest mb-1">Akumulasi Penyerahan</p>
                    <p class="text-white text-2xl font-black mb-1">{{ $totals['akumulasi_penyerahan_bilyet'] == 0 ? '-' : number_format($totals['akumulasi_penyerahan_bilyet'], 0, ',', '.') }}</p>
                    <p class="text-emerald-100/70 text-[10px] font-bold">Progress Penyerahan S/D Hari Ini</p>
                </div>

                <div class="bg-purple-600 rounded-2xl p-6 shadow-xl shadow-purple-100 relative overflow-hidden group hover:scale-[1.02] transition duration-300">
                    <div class="absolute -right-4 -bottom-4 bg-white/10 w-24 h-24 rounded-full group-hover:scale-150 transition duration-500"></div>
                    <p class="text-purple-100 text-xs font-black uppercase tracking-widest mb-1">Achievement Rate</p>
                    <p class="text-white text-2xl font-black mb-1">
                        @php $totalPct = $totals['target'] > 0 ? ($totals['akumulasi_penyerahan_bilyet'] / $totals['target']) * 100 : 0; @endphp
                        {{ number_format($totalPct, 1, ',', '.') }}%
                    </p>
                    <div class="w-full bg-white/20 h-1.5 rounded-full mt-2">
                        <div class="bg-white h-full rounded-full" style="width: {{ $totalPct }}%"></div>
                    </div>
                </div>
            </div>
            -->
            <!-- Table Section -->
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl border border-gray-100">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-lg font-black text-indigo-600 tracking-tight flex items-center">
                        <span class="w-2 h-6 bg-indigo-600 rounded-full mr-3"></span>
                        Laporan Persediaan HCS {{ \Carbon\Carbon::parse($tanggalLaporan)->locale('id')->isoFormat('dddd, D MMMM Y') }} TA {{ $tahunAnggaran }}
                    </h3>
                    <div class="relative w-64 group">
                        <input type="text" x-model="search" placeholder="Cari Pecahan..." 
                            class="w-full pl-10 pr-4 py-2 rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all group-hover:border-indigo-300 shadow-sm">
                        <div class="absolute left-3 top-2.5 text-gray-400 group-hover:text-indigo-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100/80">
                            <tr class="text-[10px] font-black uppercase text-gray-500 tracking-widest divide-x divide-gray-200">
                                <th rowspan="2" class="px-4 py-4 text-center sticky left-0 bg-gray-100 z-10 w-20">Pecahan</th>
                                <th colspan="3" class="px-4 py-2 text-center text-indigo-600 bg-indigo-50/50">Persediaan</th>
                                <th rowspan="2" class="px-4 py-4 text-center text-indigo-900 bg-indigo-50/80">Total Persediaan<br>(Bilyet)</th>
                                <th colspan="3" class="px-4 py-2 text-center text-pink-600 bg-pink-50/50">Penyerahan HCS</th>
                                <th colspan="3" class="px-4 py-2 text-center text-emerald-600 bg-emerald-50/50">Target & Pencapaian</th>
                                <th rowspan="2" class="px-4 py-4 text-center">Akumulasi<br>Terima HCS</th>
                            </tr>
                            <tr class="text-[9px] font-bold uppercase text-gray-400 divide-x divide-gray-200">
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

            <!-- Secondary Table Section: Monitoring & Production -->
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl border border-gray-100 mt-8">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-lg font-black text-gray-800 tracking-tight flex items-center text-green-600">
                        <span class="w-2 h-6 bg-green-600 rounded-full mr-3"></span>
                        Monitoring Target & Produksi HCS {{ \Carbon\Carbon::parse($tanggalLaporan)->locale('id')->isoFormat('dddd, D MMMM Y') }} TA {{ $tahunAnggaran }}
                    </h3>
                </div>
                
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
                                    <span class="text-[9px] font-bold text-emerald-500 italic">s/d {{ \Carbon\Carbon::parse($tanggalLaporan)->locale('id')->isoFormat('D MMMM Y') }}</span>
                                </th>
                                <th colspan="3" class="px-4 py-2 text-center bg-purple-50/50">
                                    <span class="block text-purple-700 text-[10px] font-black uppercase tracking-tight">Penerimaan HCS</span>
                                    <span class="text-[9px] font-bold text-purple-500 italic">s/d {{ \Carbon\Carbon::parse($tanggalLaporan)->locale('id')->isoFormat('D MMMM Y') }}</span>
                                </th>
                            </tr>
                            <tr class="text-[9px] font-bold uppercase text-gray-400 divide-x divide-gray-200">
                                <th class="px-3 py-2 text-center">Target<br>(Bilyet)</th>
                                <th class="px-3 py-2 text-center">Realisasi<br>(Bilyet)</th>
                                <th class="px-3 py-2 text-center">Sisa<br>(Bilyet)</th>
                                <th class="px-3 py-2 text-center">Sisa<br>(Dus)</th>
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
                                    <td class="px-4 py-4 text-right text-xs font-black text-blue-700">{{ $row['sisa_target_bilyet'] <= 0 ? '-' : number_format($row['sisa_target_bilyet'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-4 text-right text-[10px] font-black text-gray-400 italic">{{ $row['sisa_target_doos'] <= 0 ? '-' : number_format($row['sisa_target_doos'], 0, ',', '.')}}</td>
                                    
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
                                <td class="px-4 py-4 text-right text-blue-300">{{ $secondaryTotals['sisa_target_bilyet'] <= 0 ? '-' : number_format($secondaryTotals['sisa_target_bilyet'], 0, ',', '.') }}</td>
                                <td class="px-4 py-4 text-right text-gray-400 font-normal italic">{{ $secondaryTotals['sisa_target_doos'] <= 0 ? '-' : number_format($secondaryTotals['sisa_target_doos'], 0, ',', '.')}}</td>
                                
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
    </div>

</x-app-layout>
