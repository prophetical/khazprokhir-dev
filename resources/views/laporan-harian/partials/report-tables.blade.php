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

<!-- Table Section: HCS -->
<div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl border border-gray-100 p-1">
    <div class="bg-gray-50/50 rounded-[1.25rem] p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-black text-indigo-600 tracking-tight flex items-center">
                <span class="w-2 h-6 bg-indigo-600 rounded-full mr-3"></span>
                Laporan Persediaan HCS
                {{ \Carbon\Carbon::parse($tanggalLaporan)->locale('id')->isoFormat('dddd, D MMMM Y') }} TA
                {{ $tahunAnggaran }}
            </h3>
            <div class="flex items-center gap-4">
                <button @click="showHcs = !showHcs"
                    class="p-2 hover:bg-indigo-100 rounded-lg transition-colors text-indigo-600">
                    <svg class="w-6 h-6 transition-transform duration-200" :class="{ 'rotate-180': !showHcs }"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>
                </button>
            </div>
        </div>
        <div x-show="showHcs" x-transition>
            <div class="overflow-x-auto rounded-xl border border-gray-100 bg-white">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100/80">
                        <tr
                            class="text-[10px] font-black uppercase text-gray-500 tracking-widest divide-x divide-gray-200">
                            <th rowspan="2" class="px-4 py-4 text-center sticky left-0 bg-gray-100 z-10 w-20">Pecahan
                            </th>
                            <th colspan="3" class="px-4 py-2 text-center text-indigo-600 bg-indigo-50/50">Persediaan
                            </th>
                            <th rowspan="2" class="px-4 py-4 text-center text-indigo-900 bg-indigo-50/80">Total
                                Persediaan</th>
                            <th colspan="3" class="px-4 py-2 text-center text-pink-600 bg-pink-50/50">Penyerahan HCS
                            </th>
                            <th colspan="3" class="px-4 py-2 text-center text-emerald-600 bg-emerald-50/50">Target &
                                Pencapaian</th>
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
                            <th class="x-3 py-1 text-center bg-gray-100 italic">b</th>
                            <th class="x-3 py-1 text-center bg-gray-100 italic">c</th>
                            <th class="x-3 py-1 text-center bg-gray-100 italic">d=c/20.000</th>
                            <th class="x-3 py-1 text-center bg-gray-100 italic">e=b+c</th>
                            <th class="x-3 py-1 text-center bg-gray-100 italic">f</th>
                            <th class="x-3 py-1 text-center bg-gray-100 italic">g=f/20.000</th>
                            <th class="x-3 py-1 text-center bg-gray-100 italic">h</th>
                            <th class="x-3 py-1 text-center bg-gray-100 italic">i</th>
                            <th class="x-3 py-1 text-center bg-gray-100 italic">j=i-h</th>
                            <th class="x-3 py-1 text-center bg-gray-100 italic">k=i/j*100%</th>
                            <th class="x-3 py-1 text-center bg-gray-100 italic">l</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($reportData as $row)
                            <tr x-show="search === '' || '{{ $row['pecahan'] }}'.toLowerCase().includes(search.toLowerCase())"
                                class="hover:bg-indigo-50/40 transition duration-150 group">
                                <td
                                    class="px-4 py-4 text-center sticky left-0 bg-white group-hover:bg-indigo-50/40 z-10 border-r border-gray-100">
                                    <span
                                        class="inline-flex items-center justify-center h-7 w-7 rounded-lg shadow-sm font-black text-xs border {{ $colorMap[$row['pecahan']] ?? 'bg-gray-900 text-white' }}">
                                        {{ $row['pecahan'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-right text-xs font-bold text-gray-600 border-r border-gray-100">
                                    {{ $row['siap_kemas_bilyet'] == 0 ? '-' : number_format($row['siap_kemas_bilyet'], 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-right text-xs font-bold text-gray-600 border-r border-gray-100">
                                    {{ $row['siap_kirim_bilyet'] == 0 ? '-' : number_format($row['siap_kirim_bilyet'], 0, ',', '.') }}
                                </td>
                                <td
                                    class="px-4 py-4 text-right text-[10px] font-black text-gray-400 border-r border-gray-100 italic">
                                    {{ $row['siap_kirim_dus'] == 0 ? '-' : number_format($row['siap_kirim_dus'], 0, ',', '.')}}
                                </td>
                                <td
                                    class="px-4 py-4 text-right text-xs font-black text-indigo-700 bg-indigo-50/10 border-r border-gray-100">
                                    {{ $row['total_persediaan_bilyet'] == 0 ? '-' : number_format($row['total_persediaan_bilyet'], 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-right text-xs font-bold text-gray-600 border-r border-gray-100">
                                    {{ $row['penyerahan_hari_ini_bilyet'] == 0 ? '-' : number_format($row['penyerahan_hari_ini_bilyet'], 0, ',', '.') }}
                                </td>
                                <td
                                    class="px-4 py-4 text-right text-[10px] font-black text-pink-600 border-r border-gray-100 italic">
                                    {{ $row['penyerahan_hari_ini_dus'] == 0 ? '-' : number_format($row['penyerahan_hari_ini_dus'], 0, ',', '.')}}
                                </td>
                                <td
                                    class="px-4 py-4 text-right text-xs font-black text-pink-700 bg-pink-50/10 border-r border-gray-100">
                                    {{ $row['akumulasi_penyerahan'] == 0 ? '-' : number_format($row['akumulasi_penyerahan'], 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-right text-xs font-bold text-gray-600 border-r border-gray-100">
                                    {{ $row['target'] == 0 ? '-' : number_format($row['target'], 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-right text-xs font-bold text-gray-600 border-r border-gray-100">
                                    {{ $row['sisa_target'] == 0 ? '-' : number_format($row['sisa_target'], 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-center border-r border-gray-100">
                                    <div class="space-y-1">
                                        <div class="flex justify-between items-center px-1">
                                            <span
                                                class="text-[10px] font-black {{ $row['persentase_target'] >= 80 ? 'text-emerald-600' : ($row['persentase_target'] >= 50 ? 'text-amber-600' : 'text-rose-600') }}">
                                                {{ number_format($row['persentase_target'], 2, ',', '.') }}%
                                            </span>
                                        </div>
                                        <div class="w-full bg-gray-100 h-1.5 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full {{ $row['persentase_target'] >= 80 ? 'bg-emerald-500' : ($row['persentase_target'] >= 50 ? 'bg-amber-500' : 'bg-rose-500') }}"
                                                style="width: {{ min(100, $row['persentase_target']) }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-right text-xs font-black text-gray-800 bg-gray-50/30">
                                    {{ $row['akumulasi_penerimaan_hcs'] == 0 ? '-' : number_format($row['akumulasi_penerimaan_hcs'], 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    @php
                        $totalPct = $totals['target'] > 0 ? ($totals['akumulasi_penyerahan_bilyet'] / $totals['target']) * 100 : 0;
                    @endphp
                    <tfoot class="bg-gray-900 text-white text-xs font-black uppercase">
                        <tr class="divide-x divide-gray-700">
                            <td class="px-4 py-6 text-center uppercase tracking-widest sticky left-0 bg-gray-900 z-10">
                                TOTAL</td>
                            <td class="px-4 py-6 text-right">
                                {{ $totals['siap_kemas_bilyet'] == 0 ? '-' : number_format($totals['siap_kemas_bilyet'], 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-6 text-right">
                                {{ $totals['siap_kirim_bilyet'] == 0 ? '-' : number_format($totals['siap_kirim_bilyet'], 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-6 text-right text-[10px] text-gray-400 italic">
                                @php $siapKirimTotalDus = $totals['siap_kirim_bilyet'] / 20000; @endphp
                                {{ $siapKirimTotalDus == 0 ? '-' : number_format($siapKirimTotalDus, 0, ',', '.')}}
                            </td>
                            <td class="px-4 py-6 text-right text-indigo-300">
                                {{ $totals['total_persediaan_bilyet'] == 0 ? '-' : number_format($totals['total_persediaan_bilyet'], 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-6 text-right">
                                {{ $totals['penyerahan_hari_ini_bilyet'] == 0 ? '-' : number_format($totals['penyerahan_hari_ini_bilyet'], 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-6 text-right text-[10px] text-pink-300 italic">
                                @php $penyerahanTotalDus = $totals['penyerahan_hari_ini_bilyet'] / 20000; @endphp
                                {{ $penyerahanTotalDus == 0 ? '-' : number_format($penyerahanTotalDus, 0, ',', '.')}}
                            </td>
                            <td class="px-4 py-6 text-right text-pink-300">
                                {{ $totals['akumulasi_penyerahan_bilyet'] == 0 ? '-' : number_format($totals['akumulasi_penyerahan_bilyet'], 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-6 text-right">
                                {{ $totals['target'] == 0 ? '-' : number_format($totals['target'], 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-6 text-right">
                                {{ $totals['sisa_target'] == 0 ? '-' : number_format($totals['sisa_target'], 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-6 text-center text-xs">
                                <div class="flex items-center gap-2">
                                    {{ $totalPct == 0 ? '-' : number_format($totalPct, 2, ',', '.') . '%' }}
                                    <div class="flex-grow bg-white/10 h-1.5 rounded-full overflow-hidden max-w-[60px]">
                                        <div class="bg-indigo-400 h-full rounded-full"
                                            style="width: {{ min(100, $totalPct) }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-6 text-right text-amber-300">
                                {{ $totals['akumulasi_penerimaan_hcs'] == 0 ? '-' : number_format($totals['akumulasi_penerimaan_hcs'], 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Monitoring & Production Section -->
<div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl border border-gray-100 mt-8 p-1">
    <div class="bg-gray-50/50 rounded-[1.25rem] p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-black text-green-600 tracking-tight flex items-center">
                <span class="w-2 h-6 bg-green-600 rounded-full mr-3"></span>
                Monitoring Target & Produksi HCS
                {{ \Carbon\Carbon::parse($tanggalLaporan)->locale('id')->isoFormat('dddd, D MMMM Y') }} TA
                {{ $tahunAnggaran }}
            </h3>
            <button @click="showMonitoring = !showMonitoring"
                class="p-2 hover:bg-green-100 rounded-lg transition-colors text-green-600">
                <svg class="w-6 h-6 transition-transform duration-200" :class="{ 'rotate-180': !showMonitoring }"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                </svg>
            </button>
        </div>
        <div x-show="showMonitoring" x-transition>
            <div class="overflow-x-auto rounded-xl border border-gray-100 bg-white">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100/80">
                        <tr
                            class="text-[10px] font-black uppercase text-gray-500 tracking-widest divide-x divide-gray-200">
                            <th rowspan="2" class="px-4 py-4 text-center sticky left-0 bg-gray-100 z-10 w-20">Pecahan
                            </th>
                            <th colspan="4" class="px-4 py-2 text-center text-blue-600 bg-blue-50/50">Target & Realisasi
                                Pengemasan {{ \Carbon\Carbon::parse($tanggalLaporan)->locale('id')->isoFormat('MMMM') }}
                            </th>
                            <th class="px-4 py-2 text-center bg-amber-50/50">
                                <span
                                    class="block text-amber-700 text-[10px] font-black uppercase tracking-tight">Target
                                    Produksi</span>
                                <span class="text-[9px] font-bold text-amber-500 italic">{{ $sisaHariKerja }} Hari
                                    Kerja</span>
                            </th>
                            <th colspan="4" class="px-4 py-2 text-center bg-emerald-50/50">
                                <span
                                    class="block text-emerald-700 text-[10px] font-black uppercase tracking-tight">Pengemasan</span>
                                <span
                                    class="text-[9px] font-bold text-emerald-500 italic">{{ \Carbon\Carbon::parse($tanggalLaporan)->subDay()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                            </th>
                            <th colspan="3" class="px-4 py-2 text-center bg-purple-50/50">
                                <span
                                    class="block text-purple-700 text-[10px] font-black uppercase tracking-tight">Penerimaan
                                    HCS</span>
                                <span
                                    class="text-[9px] font-bold text-purple-500 italic">{{ \Carbon\Carbon::parse($tanggalLaporan)->subDay()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                            </th>
                        </tr>
                        <tr class="text-[9px] font-bold text-gray-400 divide-x divide-gray-200">
                            <th class="px-3 py-2 text-center">Target<br>(Bilyet)</th>
                            <th class="px-3 py-2 text-center">Realisasi<br>(Bilyet)</th>
                            <th class="px-3 py-2 text-center">Sisa Target<br>(Bilyet)</th>
                            <th class="px-3 py-2 text-center">Sisa Target<br>(Dus)</th>
                            <th class="px-3 py-2 text-center bg-amber-100/50 text-amber-900">Target / Hari<br>(Bilyet)
                            </th>
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
                            <th class="x-3 py-1 text-center bg-gray-100 italic">b</th>
                            <th class="x-3 py-1 text-center bg-gray-100 italic">c</th>
                            <th class="x-3 py-1 text-center bg-gray-100 italic">d=b-c</th>
                            <th class="x-3 py-1 text-center bg-gray-100 italic">e=d/20.000</th>
                            <th class="x-3 py-1 text-center bg-gray-100 italic">f=d/{{ $sisaHariKerja }}</th>
                            <th class="x-3 py-1 text-center bg-gray-100 italic">g</th>
                            <th class="x-3 py-1 text-center bg-gray-100 italic">h</th>
                            <th class="x-3 py-1 text-center bg-gray-100 italic">i</th>
                            <th class="x-3 py-1 text-center bg-gray-100 italic">j=g+h+i</th>
                            <th class="x-3 py-1 text-center bg-gray-100 italic">k</th>
                            <th class="x-3 py-1 text-center bg-gray-100 italic">l</th>
                            <th class="x-3 py-1 text-center bg-gray-100 italic">m=k+l</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($secondaryData as $row)
                            <tr class="hover:bg-gray-50 transition duration-150 group divide-x divide-gray-100">
                                <td class="px-4 py-4 text-center sticky left-0 bg-white group-hover:bg-gray-50 z-10">
                                    <span
                                        class="inline-flex items-center justify-center h-7 w-7 rounded-lg shadow-sm font-black text-xs border {{ $colorMap[$row['pecahan']] ?? 'bg-gray-900 text-white' }}">
                                        {{ $row['pecahan'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-right text-xs font-bold text-gray-600">
                                    {{ $row['target_penyerahan_bulan'] == 0 ? '-' : number_format($row['target_penyerahan_bulan'], 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-right text-xs font-bold text-gray-600">
                                    {{ $row['penyerahan_bulan'] == 0 ? '-' : number_format($row['penyerahan_bulan'], 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-right text-xs font-black">
                                    @if($row['sisa_target_bilyet'] < 0)
                                        <span
                                            class="text-green-600">+{{ number_format(abs($row['sisa_target_bilyet']), 0, ',', '.') }}</span>
                                    @elseif($row['sisa_target_bilyet'] > 0)
                                        <span
                                            class="text-blue-700">{{ number_format($row['sisa_target_bilyet'], 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-right text-[10px] font-black italic">
                                    @if($row['sisa_target_doos'] < 0)
                                        <span
                                            class="text-green-600">+{{ number_format(abs($row['sisa_target_doos']), 0, ',', '.') }}</span>
                                    @elseif($row['sisa_target_doos'] > 0)
                                        <span
                                            class="text-gray-400">{{ number_format($row['sisa_target_doos'], 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-gray-300">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-right text-xs font-black text-amber-900 bg-amber-50/50">
                                    {{ $row['target_produksi_harian'] <= 0 ? '-' : number_format($row['target_produksi_harian'], 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-right text-[10px] font-bold text-gray-500">
                                    {{ $row['kemas_g1'] == 0 ? '-' : number_format($row['kemas_g1'], 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-right text-[10px] font-bold text-gray-500">
                                    {{ $row['kemas_g2'] == 0 ? '-' : number_format($row['kemas_g2'], 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-right text-[10px] font-bold text-gray-500">
                                    {{ $row['kemas_g3'] == 0 ? '-' : number_format($row['kemas_g3'], 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-right text-xs font-black text-emerald-700 bg-emerald-50/20">
                                    {{ $row['total_kemas'] == 0 ? '-' : number_format($row['total_kemas'], 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-right text-[10px] font-bold text-gray-500">
                                    {{ $row['hcs_rikyet'] == 0 ? '-' : number_format($row['hcs_rikyet'], 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-right text-[10px] font-bold text-gray-500">
                                    {{ $row['hcs_cutpack'] == 0 ? '-' : number_format($row['hcs_cutpack'], 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-right text-xs font-black text-purple-700 bg-purple-50/20">
                                    {{ $row['total_hcs'] == 0 ? '-' : number_format($row['total_hcs'], 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-800 text-white font-black text-xs divide-x divide-gray-700">
                        <tr class="divide-x divide-gray-700">
                            <td class="px-4 py-4 text-center uppercase sticky left-0 bg-gray-800 z-10 tracking-widest">
                                TOTAL</td>
                            <td class="px-4 py-4 text-right">
                                {{ $secondaryTotals['target_penyerahan_bulan'] == 0 ? '-' : number_format($secondaryTotals['target_penyerahan_bulan'], 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-4 text-right">
                                {{ $secondaryTotals['penyerahan_bulan'] == 0 ? '-' : number_format($secondaryTotals['penyerahan_bulan'], 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-4 text-right">
                                @if($secondaryTotals['sisa_target_bilyet'] < 0)
                                    <span
                                        class="text-green-400">+{{ number_format(abs($secondaryTotals['sisa_target_bilyet']), 0, ',', '.') }}</span>
                                @elseif($secondaryTotals['sisa_target_bilyet'] > 0)
                                    <span
                                        class="text-blue-300">{{ number_format($secondaryTotals['sisa_target_bilyet'], 0, ',', '.') }}</span>
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-right italic font-normal">
                                @if($secondaryTotals['sisa_target_doos'] < 0)
                                    <span
                                        class="text-green-500 text-[10px]">+{{ number_format(abs($secondaryTotals['sisa_target_doos']), 0, ',', '.') }}</span>
                                @elseif($secondaryTotals['sisa_target_doos'] > 0)
                                    <span
                                        class="text-gray-400 text-[10px]">{{ number_format($secondaryTotals['sisa_target_doos'], 0, ',', '.') }}</span>
                                @else
                                    <span class="text-gray-600">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-right text-amber-400">
                                {{ $secondaryTotals['target_produksi_harian'] <= 0 ? '-' : number_format($secondaryTotals['target_produksi_harian'], 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-4 text-right">
                                {{ $secondaryTotals['kemas_g1'] == 0 ? '-' : number_format($secondaryTotals['kemas_g1'], 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-4 text-right">
                                {{ $secondaryTotals['kemas_g2'] == 0 ? '-' : number_format($secondaryTotals['kemas_g2'], 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-4 text-right">
                                {{ $secondaryTotals['kemas_g3'] == 0 ? '-' : number_format($secondaryTotals['kemas_g3'], 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-4 text-right text-emerald-400">
                                {{ $secondaryTotals['total_kemas'] == 0 ? '-' : number_format($secondaryTotals['total_kemas'], 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-4 text-right">
                                {{ $secondaryTotals['hcs_rikyet'] == 0 ? '-' : number_format($secondaryTotals['hcs_rikyet'], 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-4 text-right">
                                {{ $secondaryTotals['hcs_cutpack'] == 0 ? '-' : number_format($secondaryTotals['hcs_cutpack'], 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-4 text-right text-purple-400">
                                {{ $secondaryTotals['total_hcs'] == 0 ? '-' : number_format($secondaryTotals['total_hcs'], 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- HCTS Inventory Section -->
<div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl border border-gray-100 mt-8 p-1">
    <div class="bg-gray-50/50 rounded-[1.25rem] p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-black text-fuchsia-600 tracking-tight flex items-center">
                <span class="w-2 h-6 bg-fuchsia-600 rounded-full mr-3"></span>
                Laporan Persediaan HCTS
                {{ \Carbon\Carbon::parse($tanggalLaporan)->locale('id')->isoFormat('dddd, D MMMM Y') }} TA
                {{ $tahunAnggaran }}
            </h3>
            <button @click="showHcts = !showHcts"
                class="p-2 hover:bg-fuchsia-100 rounded-lg transition-colors text-fuchsia-600">
                <svg class="w-6 h-6 transition-transform duration-200" :class="{ 'rotate-180': !showHcts }" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                </svg>
            </button>
        </div>
        <div x-show="showHcts" x-transition>
            <div class="overflow-x-auto rounded-xl border border-gray-100 bg-white">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100/80">
                        <tr
                            class="text-[10px] font-black uppercase text-gray-500 tracking-widest divide-x divide-gray-200">
                            <th class="px-4 py-4 text-center sticky left-0 bg-gray-100 z-10 w-20">Pecahan</th>
                            <th class="px-4 py-4 text-center">Penerimaan<br><span
                                    class="text-[9px] font-bold italic">{{ \Carbon\Carbon::parse($tanggalLaporan)->subDay()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                            </th>
                            <th class="px-4 py-4 text-center text-emerald-600 bg-emerald-50/30">Penyerahan<br><span
                                    class="text-[9px] font-bold italic">{{ \Carbon\Carbon::parse($tanggalLaporan)->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                            </th>
                            <th class="px-4 py-4 text-center">Akumulasi<br>Penerimaan</th>
                            <th class="px-4 py-4 text-center">Akumulasi<br>Penyerahan</th>
                            <th class="px-4 py-4 text-center text-indigo-600 bg-indigo-50/50">Persediaan<br>HCTS</th>
                            <th class="px-4 py-4 text-center text-amber-600 bg-amber-50/30">Container<br>Siap Hitung
                            </th>
                        </tr>
                        <tr class="text-[9px] lowercase text-gray-400 divide-x divide-gray-200 bg-gray-50/50">
                            <th class="px-3 py-1 text-center bg-gray-100 italic">a</th>
                            <th class="x-3 py-1 text-center bg-gray-100 italic">b</th>
                            <th class="x-3 py-1 text-center bg-gray-100 italic">c</th>
                            <th class="x-3 py-1 text-center bg-gray-100 italic">d</th>
                            <th class="x-3 py-1 text-center bg-gray-100 italic">e</th>
                            <th class="x-3 py-1 text-center bg-gray-100 italic">f=d-e</th>
                            <th class="x-3 py-1 text-center bg-gray-100 italic">g=f/3.000.000</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach(['S', 'T', 'U', 'V', 'W', 'X', 'Y'] as $pec)
                            @php $data = $hctsInventoryData[$pec] ?? ['penerimaan_h1' => 0, 'penyerahan_hari_ini' => 0, 'akumulasi_penerimaan' => 0, 'akumulasi_penyerahan' => 0, 'persediaan' => 0, 'ct_siap_hitung' => 0]; @endphp
                            <tr class="hover:bg-gray-50 transition duration-150 divide-x divide-gray-100">
                                <td class="px-4 py-4 text-center sticky left-0 bg-white group-hover:bg-gray-50 z-10">
                                    <span
                                        class="inline-flex items-center justify-center h-7 w-7 rounded-lg shadow-sm font-black text-xs border {{ $colorMap[$pec] ?? 'bg-gray-900 text-white' }}">
                                        {{ $pec }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-right text-xs font-bold text-gray-600">
                                    {{ $data['penerimaan_h1'] == 0 ? '-' : number_format($data['penerimaan_h1'], 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-right text-xs font-black text-emerald-700 bg-emerald-50/10">
                                    {{ $data['penyerahan_hari_ini'] == 0 ? '-' : number_format($data['penyerahan_hari_ini'], 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-right text-xs font-bold text-gray-600">
                                    {{ $data['akumulasi_penerimaan'] == 0 ? '-' : number_format($data['akumulasi_penerimaan'], 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-right text-xs font-bold text-gray-600">
                                    {{ $data['akumulasi_penyerahan'] == 0 ? '-' : number_format($data['akumulasi_penyerahan'], 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-right text-xs font-black text-indigo-700 bg-indigo-50/20">
                                    {{ $data['persediaan'] == 0 ? '-' : number_format($data['persediaan'], 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <span
                                            class="text-xs font-black text-amber-700">{{ $data['ct_siap_hitung'] == 0 ? '-' : $data['ct_siap_hitung'] }}</span>
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
                            <td class="px-4 py-4 text-center uppercase tracking-widest sticky left-0 bg-gray-900 z-10">
                                TOTAL</td>
                            <td class="px-4 py-4 text-right">
                                {{ $totalPenerimaanH1 == 0 ? '-' : number_format($totalPenerimaanH1, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-4 text-right text-emerald-400">
                                {{ $totalPenyerahanHariIni == 0 ? '-' : number_format($totalPenyerahanHariIni, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-4 text-right">
                                {{ $totalAkumulasiTerima == 0 ? '-' : number_format($totalAkumulasiTerima, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-4 text-right">
                                {{ $totalAkumulasiSerah == 0 ? '-' : number_format($totalAkumulasiSerah, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-4 text-right text-indigo-300">
                                {{ $totalPersediaan == 0 ? '-' : number_format($totalPersediaan, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-4 text-center text-amber-400">
                                {{ $totalCT == 0 ? '-' : $totalCT . ' CT' }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Annual Target Achievement Section -->
<div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl border border-gray-100 mt-8 p-1">
    <div class="bg-gray-50/50 rounded-[1.25rem] p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-black text-yellow-600 tracking-tight flex items-center">
                <span class="w-2 h-6 bg-yellow-600 rounded-full mr-3"></span>
                Laporan Pencapaian Target Pengemasan Tahunan TA {{ $tahunAnggaran }}
            </h3>
            <button @click="showAnnual = !showAnnual"
                class="p-2 hover:bg-yellow-100 rounded-lg transition-colors text-yellow-600">
                <svg class="w-6 h-6 transition-transform duration-200" :class="{ 'rotate-180': !showAnnual }"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                </svg>
            </button>
        </div>
        <div x-show="showAnnual" x-transition>
            <div class="overflow-x-auto rounded-xl border border-gray-100 bg-white">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100/80">
                        <tr
                            class="text-[10px] font-black uppercase text-gray-500 tracking-widest divide-x divide-gray-200">
                            <th rowspan="2" class="px-4 py-4 text-center sticky left-0 bg-gray-100 z-10 w-20">PEC</th>
                            <th colspan="2" class="px-4 py-2 text-center text-indigo-600 bg-indigo-50/30">Target TA
                                {{ $tahunAnggaran }}
                            </th>
                            <th colspan="2" class="px-4 py-2 text-center text-emerald-600 bg-emerald-50/30">Akumulasi
                                Pengemasan</th>
                            <th colspan="2" class="px-4 py-2 text-center text-rose-600 bg-rose-50/30">Sisa / Over</th>
                            <th rowspan="2" class="px-4 py-4 text-center text-amber-600 bg-amber-50/30">% Pencapaian
                            </th>
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
                            <th class="x-3 py-1 text-center bg-gray-100 italic">b</th>
                            <th class="x-3 py-1 text-center bg-gray-100 italic">c=b/20.000</th>
                            <th class="x-3 py-1 text-center bg-gray-100 italic">d</th>
                            <th class="x-3 py-1 text-center bg-gray-100 italic">e=d/20.000</th>
                            <th class="x-3 py-1 text-center bg-gray-100 italic">f=b-d</th>
                            <th class="x-3 py-1 text-center bg-gray-100 italic">g=f/20.000</th>
                            <th class="x-3 py-1 text-center bg-gray-100 italic">h=d/b*100%</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($targetAchievementData['data'] as $row)
                            <tr class="hover:bg-gray-50 transition duration-150 divide-x divide-gray-100">
                                <td class="px-4 py-4 text-center sticky left-0 bg-white group-hover:bg-gray-50 z-10">
                                    <span
                                        class="inline-flex items-center justify-center h-7 w-7 rounded-lg shadow-sm font-black text-xs border {{ $colorMap[$row['pecahan']] ?? 'bg-gray-900 text-white' }}">
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
                                <td
                                    class="px-4 py-4 text-right text-xs font-black {{ $row['sisa_bilyet'] < 0 ? 'text-emerald-600' : ($row['sisa_bilyet'] > 0 ? 'text-rose-600' : 'text-gray-400') }}">
                                    @if($row['sisa_bilyet'] < 0) +{{ number_format(abs($row['sisa_bilyet']), 0, ',', '.') }}
                                    @elseif($row['sisa_bilyet'] > 0) {{ number_format($row['sisa_bilyet'], 0, ',', '.') }}
                                    @else - @endif
                                </td>
                                <td
                                    class="px-4 py-4 text-right text-xs font-black {{ $row['sisa_dus'] < 0 ? 'text-emerald-600' : ($row['sisa_dus'] > 0 ? 'text-rose-600' : 'text-gray-400') }}">
                                    @if($row['sisa_dus'] < 0) +{{ number_format(abs($row['sisa_dus']), 0, ',', '.') }}
                                    @elseif($row['sisa_dus'] > 0) {{ number_format($row['sisa_dus'], 0, ',', '.') }} @else -
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <div class="flex flex-col items-center gap-1">
                                        <span
                                            class="text-xs font-black {{ $row['persen'] >= 100 ? 'text-emerald-600' : ($row['persen'] >= 50 ? 'text-amber-600' : 'text-rose-600') }}">
                                            {{ number_format($row['persen'], 2, ',', '.') }}%
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-900 text-white font-black text-xs divide-x divide-gray-700">
                        <tr class="divide-x divide-gray-700">
                            <td class="px-4 py-6 text-center uppercase tracking-widest sticky left-0 bg-gray-900 z-10">
                                TOTAL</td>
                            <td class="px-4 py-6 text-right">
                                {{ $targetAchievementData['totals']['target_bilyet'] == 0 ? '-' : number_format($targetAchievementData['totals']['target_bilyet'], 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-6 text-right text-indigo-300">
                                {{ $targetAchievementData['totals']['target_dus'] == 0 ? '-' : number_format($targetAchievementData['totals']['target_dus'], 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-6 text-right">
                                {{ $targetAchievementData['totals']['akumulasi_bilyet'] == 0 ? '-' : number_format($targetAchievementData['totals']['akumulasi_bilyet'], 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-6 text-right text-emerald-400">
                                {{ $targetAchievementData['totals']['akumulasi_dus'] == 0 ? '-' : number_format($targetAchievementData['totals']['akumulasi_dus'], 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-6 text-right">
                                {{ $targetAchievementData['totals']['sisa_bilyet'] == 0 ? '-' : number_format($targetAchievementData['totals']['sisa_bilyet'], 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-6 text-right">
                                {{ $targetAchievementData['totals']['sisa_dus'] == 0 ? '-' : number_format($targetAchievementData['totals']['sisa_dus'], 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-6 text-center text-amber-400">
                                @php $totalAnnualPct = $targetAchievementData['totals']['target_bilyet'] > 0 ? ($targetAchievementData['totals']['akumulasi_bilyet'] / $targetAchievementData['totals']['target_bilyet']) * 100 : 0; @endphp
                                {{ number_format($totalAnnualPct, 2, ',', '.') }}%
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Monthly Target Achievement Section -->
<div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl border border-gray-100 mt-8 p-1">
    <div class="bg-gray-50/50 rounded-[1.25rem] p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-black text-rose-600 tracking-tight flex items-center">
                <span class="w-2 h-6 bg-rose-600 rounded-full mr-3"></span>
                Laporan Pencapaian Target Pengemasan Bulan
                {{ \Carbon\Carbon::parse($tanggalLaporan)->locale('id')->isoFormat('MMMM Y') }} TA {{ $tahunAnggaran }}
            </h3>
            <button @click="showMonthly = !showMonthly"
                class="p-2 hover:bg-rose-100 rounded-lg transition-colors text-rose-600">
                <svg class="w-6 h-6 transition-transform duration-200" :class="{ 'rotate-180': !showMonthly }"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                </svg>
            </button>
        </div>
        <div x-show="showMonthly" x-transition>
            <div class="overflow-x-auto rounded-xl border border-gray-100 bg-white">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100/80">
                        <tr
                            class="text-[10px] font-black uppercase text-gray-500 tracking-widest divide-x divide-gray-200">
                            <th rowspan="2" class="px-4 py-4 text-center sticky left-0 bg-gray-100 z-10 w-20">PEC</th>
                            <th colspan="2" class="px-4 py-2 text-center text-indigo-600 bg-indigo-50/30">Target
                                Pengemasan {{ \Carbon\Carbon::parse($tanggalLaporan)->locale('id')->isoFormat('MMMM') }}
                            </th>
                            <th colspan="2" class="px-4 py-2 text-center text-emerald-600 bg-emerald-50/30">Akumulasi
                                Pengemasan {{ \Carbon\Carbon::parse($tanggalLaporan)->locale('id')->isoFormat('MMMM') }}
                            </th>
                            <th colspan="2" class="px-4 py-2 text-center text-rose-600 bg-rose-50/30">Sisa / Over</th>
                            <th rowspan="2" class="px-4 py-4 text-center text-amber-600 bg-amber-50/30">% Pencapaian
                            </th>
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
                            <th class="x-3 py-1 text-center bg-gray-100 italic">b</th>
                            <th class="x-3 py-1 text-center bg-gray-100 italic">c=b/20.000</th>
                            <th class="x-3 py-1 text-center bg-gray-100 italic">d</th>
                            <th class="x-3 py-1 text-center bg-gray-100 italic">e=d/20.000</th>
                            <th class="x-3 py-1 text-center bg-gray-100 italic">f=b-d</th>
                            <th class="x-3 py-1 text-center bg-gray-100 italic">g=f/20.000</th>
                            <th class="x-3 py-1 text-center bg-gray-100 italic">h=d/b*100%</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($monthlyTargetAchievementData['data'] as $row)
                            <tr class="hover:bg-gray-50 transition duration-150 divide-x divide-gray-100">
                                <td class="px-4 py-4 text-center sticky left-0 bg-white group-hover:bg-gray-50 z-10">
                                    <span
                                        class="inline-flex items-center justify-center h-7 w-7 rounded-lg shadow-sm font-black text-xs border {{ $colorMap[$row['pecahan']] ?? 'bg-gray-900 text-white' }}">
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
                                <td
                                    class="px-4 py-4 text-right text-xs font-black {{ $row['sisa_bilyet'] < 0 ? 'text-emerald-600' : ($row['sisa_bilyet'] > 0 ? 'text-rose-600' : 'text-gray-400') }}">
                                    @if($row['sisa_bilyet'] < 0) +{{ number_format(abs($row['sisa_bilyet']), 0, ',', '.') }}
                                    @elseif($row['sisa_bilyet'] > 0) {{ number_format($row['sisa_bilyet'], 0, ',', '.') }}
                                    @else - @endif
                                </td>
                                <td
                                    class="px-4 py-4 text-right text-xs font-black {{ $row['sisa_dus'] < 0 ? 'text-emerald-600' : ($row['sisa_dus'] > 0 ? 'text-rose-600' : 'text-gray-400') }}">
                                    @if($row['sisa_dus'] < 0) +{{ number_format(abs($row['sisa_dus']), 2, ',', '.') }}
                                    @elseif($row['sisa_dus'] > 0) {{ number_format($row['sisa_dus'], 2, ',', '.') }} @else -
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <div class="flex flex-col items-center gap-1">
                                        <span
                                            class="text-xs font-black {{ $row['persen'] >= 100 ? 'text-emerald-600' : ($row['persen'] >= 50 ? 'text-amber-600' : 'text-rose-600') }}">
                                            {{ number_format($row['persen'], 2, ',', '.') }}%
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-900 text-white font-black text-xs divide-x divide-gray-700">
                        <tr class="divide-x divide-gray-700">
                            <td class="px-4 py-6 text-center uppercase tracking-widest sticky left-0 bg-gray-900 z-10">
                                TOTAL</td>
                            <td class="px-4 py-6 text-right">
                                {{ $monthlyTargetAchievementData['totals']['target_bilyet'] == 0 ? '-' : number_format($monthlyTargetAchievementData['totals']['target_bilyet'], 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-6 text-right text-indigo-300">
                                {{ $monthlyTargetAchievementData['totals']['target_dus'] == 0 ? '-' : number_format($monthlyTargetAchievementData['totals']['target_dus'], 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-6 text-right">
                                {{ $monthlyTargetAchievementData['totals']['akumulasi_bilyet'] == 0 ? '-' : number_format($monthlyTargetAchievementData['totals']['akumulasi_bilyet'], 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-6 text-right text-emerald-400">
                                {{ $monthlyTargetAchievementData['totals']['akumulasi_dus'] == 0 ? '-' : number_format($monthlyTargetAchievementData['totals']['akumulasi_dus'], 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-6 text-right">
                                {{ $monthlyTargetAchievementData['totals']['sisa_bilyet'] == 0 ? '-' : number_format($monthlyTargetAchievementData['totals']['sisa_bilyet'], 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-6 text-right">
                                {{ $monthlyTargetAchievementData['totals']['sisa_dus'] == 0 ? '-' : number_format($monthlyTargetAchievementData['totals']['sisa_dus'], 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-6 text-center text-amber-400">
                                @php $totalMonthlyPct = $monthlyTargetAchievementData['totals']['target_bilyet'] > 0 ? ($monthlyTargetAchievementData['totals']['akumulasi_bilyet'] / $monthlyTargetAchievementData['totals']['target_bilyet']) * 100 : 0; @endphp
                                {{ number_format($totalMonthlyPct, 2, ',', '.') }}%
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>