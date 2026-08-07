<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Harian Operasional -
        {{ \Carbon\Carbon::parse($tanggal_laporan)->locale('id')->isoFormat('D MMMM YYYY') }}
    </title>
    <script src="{{ asset('vendor/tailwindcss/tailwindcss.min.js') }}"></script>
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                padding: 0 !important;
                margin: 0 !important;
                background: white;
            }

            .print-container {
                width: 100% !important;
                max-width: none !important;
                border: none !important;
                shadow: none !important;
                padding: 0.2cm !important;
                border-radius: 0 !important;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            @page {
                margin: 0.5cm;
            }
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: #f9fafb;
        }

        .table-tight th,
        .table-tight td {
            padding: 8px 10px;
            border: 1px solid #e5e7eb;
        }
    </style>
    @php
        $colorMap = [
            'S' => 'bg-emerald-500 border-emerald-600 text-white',
            'T' => 'bg-gray-400 border-gray-500 text-white',
            'U' => 'bg-amber-400 border-amber-500 text-white',
            'V' => 'bg-purple-500 border-purple-600 text-white',
            'W' => 'bg-green-500 border-green-600 text-white',
            'X' => 'bg-blue-500 border-blue-600 text-white',
            'Y' => 'bg-red-500 border-red-600 text-white',
        ];
    @endphp

<body class="p-4 md:p-10">
    <div
        class="print-container max-w-7xl mx-auto bg-white p-8 border border-gray-100 shadow-sm rounded-2xl min-h-screen relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 rounded-bl-[80px] opacity-[0.08] pointer-events-none"
            style="background: linear-gradient(135deg, #1e40af 0%, #7c3aed 55%, #db2877 100%);"></div>

        <div class="no-print flex justify-between items-center mb-8 pb-6 border-b border-gray-100">
            <a href="{{ route('laporan-harian.index', request()->all()) }}"
                class="text-sm font-medium text-gray-500 hover:text-indigo-600 flex items-center transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Dashboard
            </a>
            <button onclick="window.print()"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-indigo-100 transition-all flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak Laporan / Simpan PDF
            </button>
        </div>

        <header class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 mb-1 uppercase tracking-tight text-indigo-900">LAPORAN
                    HARIAN OPERASIONAL -
                    {{ \Carbon\Carbon::parse($tanggal_laporan)->locale('id')->isoFormat('D MMMM YYYY') }}
                </h1>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-4">Khazprokhir Management
                    System</p>

                <div class="flex flex-wrap items-center gap-3 text-xs mb-4">
                    <div class="bg-indigo-50 px-3 py-1.5 rounded-lg border border-indigo-100 flex items-center">
                        <span class="text-indigo-400 mr-2 uppercase font-bold text-[10px]">Tanggal:</span>
                        <span
                            class="font-bold text-indigo-700 underline decoration-indigo-200 underline-offset-4">{{ \Carbon\Carbon::parse($tanggal_laporan)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
                    </div>
                    @if($tahun_anggaran)
                        <div class="bg-purple-50 px-3 py-1.5 rounded-lg border border-purple-100 flex items-center">
                            <span class="text-purple-400 mr-2 uppercase font-bold text-[10px]">TA:</span>
                            <span class="font-bold text-purple-700">{{ $tahun_anggaran }}</span>
                        </div>
                    @endif
                    @if($tahun_emisi)
                        <div class="bg-pink-50 px-3 py-1.5 rounded-lg border border-pink-100 flex items-center">
                            <span class="text-pink-400 mr-2 uppercase font-bold text-[10px]">Emisi:</span>
                            <span class="font-bold text-pink-700">{{ $tahun_emisi }}</span>
                        </div>
                    @endif
                </div>
            </div>
            <div class="text-right">
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Waktu Cetak</p>
                <p class="text-xs font-bold text-indigo-600">
                    {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }}
                </p>
            </div>
        </header>

        @if(isset($verifikasi) && $verifikasi && in_array(auth()->user()->role, ['tasil', 'admin']))
            @include('laporan-harian.partials.verifikasi-status')
        @endif

        @if(request('showHcs', 'true') === 'true')
            <div class="mb-10">
                <h3 class="text-lg font-black text-indigo-900 mb-4 flex items-center">
                    <span class="w-1.5 h-5 bg-indigo-600 rounded-full mr-2"></span>
                    Laporan Persediaan HCS
                </h3>
                <table class="w-full text-left border-collapse table-tight">
                    <thead>
                        <tr class="bg-gray-100 text-[10px] font-black uppercase text-gray-600">
                            <th rowspan="2" class="text-center bg-gray-200">Pecahan</th>
                            @if(!auth()->user()->role)
                                <th rowspan="2" class="text-center bg-gray-50">Akumulasi<br>Terima HCS</th>
                            @endif
                            <th colspan="3" class="text-center bg-indigo-50 text-indigo-700">Persediaan</th>
                            <th rowspan="2" class="text-center bg-indigo-100 text-indigo-900">Total Persediaan<br>(Bilyet)
                            </th>
                            <th colspan="3" class="text-center bg-pink-50 text-pink-700">Penyerahan HCS</th>
                            <th colspan="3" class="text-center bg-green-50 text-green-700">Target & Pencapaian</th>
                            @if(auth()->user()->role)
                                <th rowspan="2" class="text-center bg-gray-50">Akumulasi<br>Terima HCS</th>
                            @endif
                        </tr>
                        <tr class="bg-gray-50 text-[9px] font-bold uppercase text-gray-500">
                            <th class="text-center">Siap Kemas (Bilyet)</th>
                            <th class="text-center">Siap Kirim (Bilyet)</th>
                            <th class="text-center">Siap Kirim (Dus)</th>

                            <th class="text-center">Hari Ini (Bilyet)</th>
                            <th class="text-center">Hari Ini (Dus)</th>
                            <th class="text-center">Akumulasi (Bilyet)</th>

                            <th class="text-center">Target</th>
                            <th class="text-center">Sisa</th>
                            <th class="text-center">%</th>
                        </tr>
                        <tr class="text-[8px] lowercase text-gray-400 bg-gray-50/50">
                            @if(!auth()->user()->role)
                                <th class="text-center italic">a</th>
                                <th class="text-center italic">b</th>
                                <th class="text-center italic">c</th>
                                <th class="text-center italic">d</th>
                                <th class="text-center italic">e=d/20.000</th>
                                <th class="text-center italic">f=c+d</th>
                                <th class="text-center italic">g</th>
                                <th class="text-center italic">h=g/20.000</th>
                                <th class="text-center italic">i</th>
                                <th class="text-center italic">j</th>
                                <th class="text-center italic">k=j-i</th>
                                <th class="text-center italic">l=i/j*100%</th>
                            @else
                                <th class="text-center italic">a</th>
                                <th class="text-center italic">b</th>
                                <th class="text-center italic">c</th>
                                <th class="text-center italic">d=c/20.000</th>
                                <th class="text-center italic">e=b+c</th>
                                <th class="text-center italic">f</th>
                                <th class="text-center italic">g=f/20.000</th>
                                <th class="text-center italic">h</th>
                                <th class="text-center italic">i</th>
                                <th class="text-center italic">j=i-h</th>
                                <th class="text-center italic">k=h/i*100%</th>
                                <th class="text-center italic">l</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="text-xs font-medium text-gray-700 divide-y divide-gray-200">
                        @foreach($reportData as $row)
                            <tr class="hover:bg-gray-50">
                                <td class="text-center bg-gray-50 p-1">
                                    <span
                                        class="inline-flex items-center justify-center w-6 h-6 rounded shadow-sm text-[10px] font-black border {{ $colorMap[$row['pecahan']] ?? 'bg-gray-700 text-white' }}">
                                        {{ $row['pecahan'] }}
                                    </span>
                                </td>
                                @if(!auth()->user()->role)
                                    <td class="text-right font-bold bg-gray-50">
                                        {{ $row['akumulasi_penerimaan_hcs'] == 0 ? '-' : number_format($row['akumulasi_penerimaan_hcs'], 0, ',', '.') }}
                                    </td>
                                @endif
                                <td class="text-right">
                                    {{ $row['siap_kemas_bilyet'] == 0 ? '-' : number_format($row['siap_kemas_bilyet'], 0, ',', '.') }}
                                </td>
                                <td class="text-right">
                                    {{ $row['siap_kirim_bilyet'] == 0 ? '-' : number_format($row['siap_kirim_bilyet'], 0, ',', '.') }}
                                </td>
                                <td class="text-right">
                                    {{ $row['siap_kirim_dus'] == 0 ? '-' : number_format($row['siap_kirim_dus'], 0, ',', '.') }}
                                </td>
                                <td class="text-right font-black text-indigo-700 bg-indigo-50/30">
                                    {{ $row['total_persediaan_bilyet'] == 0 ? '-' : number_format($row['total_persediaan_bilyet'], 0, ',', '.') }}
                                </td>
                                <td class="text-right">
                                    {{ $row['penyerahan_hari_ini_bilyet'] == 0 ? '-' : number_format($row['penyerahan_hari_ini_bilyet'], 0, ',', '.') }}
                                </td>
                                <td class="text-right">
                                    {{ $row['penyerahan_hari_ini_dus'] == 0 ? '-' : number_format($row['penyerahan_hari_ini_dus'], 2, ',', '.') }}
                                </td>
                                <td class="text-right font-bold text-pink-700 bg-pink-50/30">
                                    {{ $row['akumulasi_penyerahan'] == 0 ? '-' : number_format($row['akumulasi_penyerahan'], 0, ',', '.') }}
                                </td>
                                <td class="text-right">
                                    {{ $row['target'] == 0 ? '-' : number_format($row['target'], 0, ',', '.') }}
                                </td>
                                <td class="text-right">
                                    {{ $row['sisa_target'] == 0 ? '-' : number_format($row['sisa_target'], 0, ',', '.') }}
                                </td>
                                <td
                                    class="text-center font-black {{ $row['persentase_target'] >= 80 ? 'text-green-600' : ($row['persentase_target'] >= 50 ? 'text-amber-600' : 'text-rose-600') }}">
                                    {{ $row['persentase_target'] == 0 ? '-' : number_format($row['persentase_target'], 1, ',', '.') . '%' }}
                                </td>
                                @if(auth()->user()->role)
                                    <td class="text-right font-bold bg-gray-50">
                                        {{ $row['akumulasi_penerimaan_hcs'] == 0 ? '-' : number_format($row['akumulasi_penerimaan_hcs'], 0, ',', '.') }}
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-900 text-white text-xs font-black uppercase">
                        <tr>
                            <td class="text-center py-3">TOTAL</td>
                            @if(!auth()->user()->role)
                                <td class="text-right">
                                    {{ $totals['akumulasi_penerimaan_hcs'] == 0 ? '-' : number_format($totals['akumulasi_penerimaan_hcs'], 0, ',', '.') }}
                                </td>
                            @endif
                            <td class="text-right">
                                {{ $totals['siap_kemas_bilyet'] == 0 ? '-' : number_format($totals['siap_kemas_bilyet'], 0, ',', '.') }}
                            </td>
                            <td class="text-right">
                                {{ $totals['siap_kirim_bilyet'] == 0 ? '-' : number_format($totals['siap_kirim_bilyet'], 0, ',', '.') }}
                            </td>
                            <td class="text-right">
                                @php $siapKirimTotalDus = $totals['siap_kirim_bilyet'] / 20000; @endphp
                                {{ $siapKirimTotalDus == 0 ? '-' : number_format($siapKirimTotalDus, 0, ',', '.') }}
                            </td>
                            <td class="text-right text-indigo-300">
                                {{ $totals['total_persediaan_bilyet'] == 0 ? '-' : number_format($totals['total_persediaan_bilyet'], 0, ',', '.') }}
                            </td>
                            <td class="text-right">
                                {{ $totals['penyerahan_hari_ini_bilyet'] == 0 ? '-' : number_format($totals['penyerahan_hari_ini_bilyet'], 0, ',', '.') }}
                            </td>
                            <td class="text-right">
                                @php $penyerahanTotalDus = $totals['penyerahan_hari_ini_bilyet'] / 20000; @endphp
                                {{ $penyerahanTotalDus == 0 ? '-' : number_format($penyerahanTotalDus, 2, ',', '.') }}
                            </td>
                            <td class="text-right text-pink-300">
                                {{ $totals['akumulasi_penyerahan_bilyet'] == 0 ? '-' : number_format($totals['akumulasi_penyerahan_bilyet'], 0, ',', '.') }}
                            </td>
                            <td class="text-right">
                                {{ $totals['target'] == 0 ? '-' : number_format($totals['target'], 0, ',', '.') }}
                            </td>
                            <td class="text-right">
                                {{ $totals['sisa_target'] == 0 ? '-' : number_format($totals['sisa_target'], 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                @php
                                    $totalPct = $totals['target'] > 0 ? ($totals['akumulasi_penyerahan_bilyet'] / $totals['target']) * 100 : 0;
                                @endphp
                                {{ $totalPct == 0 ? '-' : number_format($totalPct, 1, ',', '.') . '%' }}
                            </td>
                            @if(auth()->user()->role)
                                <td class="text-right">
                                    {{ $totals['akumulasi_penerimaan_hcs'] == 0 ? '-' : number_format($totals['akumulasi_penerimaan_hcs'], 0, ',', '.') }}
                                </td>
                            @endif
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif

        @if(request('showMonitoring', 'true') === 'true' && auth()->user()->role)
            <div class="mb-10">
                <h3 class="text-lg font-black text-green-700 mb-4 flex items-center">
                    <span class="w-1.5 h-5 bg-green-600 rounded-full mr-2"></span>
                    Monitoring Target & Produksi HCS
                </h3>
                <table class="w-full text-left border-collapse table-tight">
                    <thead>
                        <tr class="bg-gray-100 text-[10px] font-black uppercase text-gray-600">
                            <th rowspan="2" class="text-center bg-gray-200">Pecahan</th>
                            <th colspan="4" class="text-center bg-indigo-50 text-indigo-700">Target & Realisasi Bulan</th>
                            <th rowspan="2" class="text-center bg-amber-50 text-amber-700">Target Harian<br><span
                                    class="text-[9px] font-bold text-amber-500 italic">{{ $sisaHariKerja }} Hari
                                    Kerja</span></th>
                            <th colspan="4" class="text-center bg-emerald-50 text-emerald-700">Produksi Pengemasan
                                (Bilyet)<br><span
                                    class="text-[9px] font-bold text-emerald-500 italic">{{ \Carbon\Carbon::parse($tanggal_laporan)->subDay()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                            </th>
                            <th colspan="3" class="text-center bg-purple-50 text-purple-700">Penerimaan HCS
                                (Bilyet)<br><span
                                    class="text-[9px] font-bold text-purple-500 italic">{{ \Carbon\Carbon::parse($tanggal_laporan)->subDay()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                            </th>
                        </tr>
                        <tr class="bg-gray-50 text-[9px] font-bold uppercase text-gray-500">
                            <th class="text-center">Target (Bilyet)</th>
                            <th class="text-center">Realisasi (Bilyet)</th>
                            <th class="text-center">Sisa (Bilyet)</th>
                            <th class="text-center">Sisa (Dus)</th>
                            <th class="text-center">G1</th>
                            <th class="text-center">G2</th>
                            <th class="text-center">G3</th>
                            <th class="text-center bg-emerald-100/30 font-black">Total</th>
                            <th class="text-center">Rikyet</th>
                            <th class="text-center">Cutpack</th>
                            <th class="text-center bg-purple-100/30 font-black">Total</th>
                        </tr>
                        <tr class="text-[8px] lowercase text-gray-400 bg-gray-50/50">
                            <th class="text-center italic">a</th>
                            <th class="text-center italic">b</th>
                            <th class="text-center italic">c</th>
                            <th class="text-center italic">d=b-c</th>
                            <th class="text-center italic">e=d/20.000</th>
                            <th class="text-center italic">f=d/{{ $sisaHariKerja ?? '?' }}</th>
                            <th class="text-center italic">g</th>
                            <th class="text-center italic">h</th>
                            <th class="text-center italic">i</th>
                            <th class="text-center italic">j=g+h+i</th>
                            <th class="text-center italic">k</th>
                            <th class="text-center italic">l</th>
                            <th class="text-center italic">m=k+l</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs font-medium text-gray-700 divide-y divide-gray-200">
                        @foreach($secondaryData as $row)
                            <tr>
                                <td class="text-center bg-gray-50 p-1">
                                    <span
                                        class="inline-flex items-center justify-center w-6 h-6 rounded shadow-sm text-[10px] font-black border {{ $colorMap[$row['pecahan']] ?? 'bg-gray-700 text-white' }}">
                                        {{ $row['pecahan'] }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    {{ $row['target_penyerahan_bulan'] == 0 ? '-' : number_format($row['target_penyerahan_bulan'], 0, ',', '.') }}
                                </td>
                                <td class="text-right">
                                    {{ $row['penyerahan_bulan'] == 0 ? '-' : number_format($row['penyerahan_bulan'], 0, ',', '.') }}
                                </td>
                                <td
                                    class="text-right font-black {{ $row['sisa_target_bilyet'] < 0 ? 'text-green-600' : 'text-blue-700' }}">
                                    {{ $row['sisa_target_bilyet'] == 0 ? '-' : ($row['sisa_target_bilyet'] < 0 ? '+' : '') . number_format(abs($row['sisa_target_bilyet']), 0, ',', '.') }}
                                </td>
                                <td class="text-right font-bold text-indigo-600 bg-indigo-50/20 italic">
                                    {{ $row['sisa_target_doos'] == 0 ? '-' : ($row['sisa_target_doos'] < 0 ? '+' : '') . number_format(abs($row['sisa_target_doos']), 0, ',', '.') }}
                                </td>
                                <td class="text-right font-bold text-amber-600 bg-amber-50/20">
                                    {{ $row['target_produksi_harian'] == 0 ? '-' : number_format($row['target_produksi_harian'], 0, ',', '.') }}
                                </td>
                                <td class="text-right">
                                    {{ $row['kemas_g1'] == 0 ? '-' : number_format($row['kemas_g1'], 0, ',', '.') }}
                                </td>
                                <td class="text-right">
                                    {{ $row['kemas_g2'] == 0 ? '-' : number_format($row['kemas_g2'], 0, ',', '.') }}
                                </td>
                                <td class="text-right">
                                    {{ $row['kemas_g3'] == 0 ? '-' : number_format($row['kemas_g3'], 0, ',', '.') }}
                                </td>
                                <td class="text-right font-black text-emerald-700 bg-emerald-50/20">
                                    {{ $row['total_kemas'] == 0 ? '-' : number_format($row['total_kemas'], 0, ',', '.') }}
                                </td>
                                <td class="text-right">
                                    {{ $row['hcs_rikyet'] == 0 ? '-' : number_format($row['hcs_rikyet'], 0, ',', '.') }}
                                </td>
                                <td class="text-right">
                                    {{ $row['hcs_cutpack'] == 0 ? '-' : number_format($row['hcs_cutpack'], 0, ',', '.') }}
                                </td>
                                <td class="text-right font-black text-purple-700 bg-purple-50/20">
                                    {{ $row['total_hcs'] == 0 ? '-' : number_format($row['total_hcs'], 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-900 text-white text-[10px] font-black uppercase">
                        <tr>
                            <td class="text-center py-3">TOTAL</td>
                            <td class="text-right">
                                {{ $secondaryTotals['target_penyerahan_bulan'] == 0 ? '-' : number_format($secondaryTotals['target_penyerahan_bulan'], 0, ',', '.') }}
                            </td>
                            <td class="text-right">
                                {{ $secondaryTotals['penyerahan_bulan'] == 0 ? '-' : number_format($secondaryTotals['penyerahan_bulan'], 0, ',', '.') }}
                            </td>
                            <td
                                class="text-right {{ $secondaryTotals['sisa_target_bilyet'] < 0 ? 'text-green-400' : 'text-blue-300' }}">
                                {{ $secondaryTotals['sisa_target_bilyet'] == 0 ? '-' : ($secondaryTotals['sisa_target_bilyet'] < 0 ? '+' : '') . number_format(abs($secondaryTotals['sisa_target_bilyet']), 0, ',', '.') }}
                            </td>
                            <td class="text-right text-indigo-300">
                                {{ $secondaryTotals['sisa_target_doos'] == 0 ? '-' : ($secondaryTotals['sisa_target_doos'] < 0 ? '+' : '') . number_format(abs($secondaryTotals['sisa_target_doos']), 0, ',', '.') }}
                            </td>
                            <td class="text-right text-amber-300">
                                {{ $secondaryTotals['target_produksi_harian'] == 0 ? '-' : number_format($secondaryTotals['target_produksi_harian'], 0, ',', '.') }}
                            </td>
                            <td class="text-right">
                                {{ $secondaryTotals['kemas_g1'] == 0 ? '-' : number_format($secondaryTotals['kemas_g1'], 0, ',', '.') }}
                            </td>
                            <td class="text-right">
                                {{ $secondaryTotals['kemas_g2'] == 0 ? '-' : number_format($secondaryTotals['kemas_g2'], 0, ',', '.') }}
                            </td>
                            <td class="text-right">
                                {{ $secondaryTotals['kemas_g3'] == 0 ? '-' : number_format($secondaryTotals['kemas_g3'], 0, ',', '.') }}
                            </td>
                            <td class="text-right text-emerald-300">
                                {{ $secondaryTotals['total_kemas'] == 0 ? '-' : number_format($secondaryTotals['total_kemas'], 0, ',', '.') }}
                            </td>
                            <td class="text-right">
                                {{ $secondaryTotals['hcs_rikyet'] == 0 ? '-' : number_format($secondaryTotals['hcs_rikyet'], 0, ',', '.') }}
                            </td>
                            <td class="text-right">
                                {{ $secondaryTotals['hcs_cutpack'] == 0 ? '-' : number_format($secondaryTotals['hcs_cutpack'], 0, ',', '.') }}
                            </td>
                            <td class="text-right text-purple-300">
                                {{ $secondaryTotals['total_hcs'] == 0 ? '-' : number_format($secondaryTotals['total_hcs'], 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif

        @if(request('showHcts', 'true') === 'true')
            <div class="mb-10">
                <h3 class="text-lg font-black text-orange-700 mb-4 flex items-center">
                    <span class="w-1.5 h-5 bg-orange-600 rounded-full mr-2"></span>
                    Laporan Persediaan HCTS
                </h3>
                <table class="w-full text-left border-collapse table-tight">
                    <thead>
                        <tr class="bg-gray-100 text-[10px] font-black uppercase text-gray-600">
                            <th class="text-center bg-gray-200">Pecahan</th>
                            @if(auth()->user()->role)
                                <th class="text-center">Penerimaan<br><span
                                        class="text-[8px] italic font-normal text-gray-400">{{ \Carbon\Carbon::parse($tanggal_laporan)->subDay()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                                </th>
                                <th class="text-center bg-emerald-50 text-emerald-700">Penyerahan<br><span
                                        class="text-[8px] italic font-normal text-emerald-400">{{ \Carbon\Carbon::parse($tanggal_laporan)->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                                </th>
                            @endif
                            <th class="text-center">Akumulasi Penerimaan (Bilyet)</th>
                            <th class="text-center">Akumulasi Penyerahan (Bilyet)</th>
                            <th class="text-center bg-indigo-50 text-indigo-700">Persediaan HCTS (Bilyet)</th>
                            <th class="text-center bg-amber-50 text-amber-700">Container Siap Hitung</th>
                        </tr>
                        <tr class="text-[8px] lowercase text-gray-400 bg-gray-50/50">
                            @if(!auth()->user()->role)
                                <th class="text-center italic">a</th>
                                <th class="text-center italic">b</th>
                                <th class="text-center italic">c</th>
                                <th class="text-center italic">d=b-c</th>
                                <th class="text-center italic">e=d/300.000</th>
                            @else
                                <th class="text-center italic">a</th>
                                <th class="text-center italic">b</th>
                                <th class="text-center italic">c</th>
                                <th class="text-center italic">d</th>
                                <th class="text-center italic">e</th>
                                <th class="text-center italic">f=d-e</th>
                                <th class="text-center italic">g=f/300.000</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="text-xs font-medium text-gray-700 divide-y divide-gray-200">
                        @foreach(['S', 'T', 'U', 'V', 'W', 'X', 'Y'] as $pec)
                            @php $data = $hctsInventoryData[$pec] ?? ['penerimaan_h1' => 0, 'penyerahan_hari_ini' => 0, 'akumulasi_penerimaan' => 0, 'akumulasi_penyerahan' => 0, 'persediaan' => 0, 'ct_siap_hitung' => 0]; @endphp
                            <tr>
                                <td class="text-center bg-gray-50 p-1">
                                    <span
                                        class="inline-flex items-center justify-center w-6 h-6 rounded shadow-sm text-[10px] font-black border {{ $colorMap[$pec] ?? 'bg-gray-700 text-white' }}">
                                        {{ $pec }}
                                    </span>
                                </td>
                                @if(auth()->user()->role)
                                    <td class="text-right">
                                        {{ $data['penerimaan_h1'] == 0 ? '-' : number_format($data['penerimaan_h1'], 0, ',', '.') }}
                                    </td>
                                    <td class="text-right font-black text-emerald-700 bg-emerald-50/10">
                                        {{ $data['penyerahan_hari_ini'] == 0 ? '-' : number_format($data['penyerahan_hari_ini'], 0, ',', '.') }}
                                    </td>
                                @endif
                                <td class="text-right">
                                    {{ $data['akumulasi_penerimaan'] == 0 ? '-' : number_format($data['akumulasi_penerimaan'], 0, ',', '.') }}
                                </td>
                                <td class="text-right">
                                    {{ $data['akumulasi_penyerahan'] == 0 ? '-' : number_format($data['akumulasi_penyerahan'], 0, ',', '.') }}
                                </td>
                                <td class="text-right font-black text-indigo-700 bg-indigo-50/20">
                                    {{ $data['persediaan'] == 0 ? '-' : number_format($data['persediaan'], 0, ',', '.') }}
                                </td>
                                <td class="text-center font-black text-amber-700">
                                    {{ $data['ct_siap_hitung'] == 0 ? '-' : $data['ct_siap_hitung'] . ' CT' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-900 text-white text-[10px] font-black uppercase">
                        @php
                            $totalPenerimaanH1 = collect($hctsInventoryData)->sum('penerimaan_h1');
                            $totalPenyerahanHariIni = collect($hctsInventoryData)->sum('penyerahan_hari_ini');
                            $totalAkumulasiTerima = collect($hctsInventoryData)->sum('akumulasi_penerimaan');
                            $totalAkumulasiSerah = collect($hctsInventoryData)->sum('akumulasi_penyerahan');
                            $totalPersediaan = collect($hctsInventoryData)->sum('persediaan');
                            $totalCT = collect($hctsInventoryData)->sum('ct_siap_hitung');
                        @endphp
                        <tr>
                            <td class="text-center py-3">TOTAL</td>
                            @if(auth()->user()->role)
                                <td class="text-right">
                                    {{ $totalPenerimaanH1 == 0 ? '-' : number_format($totalPenerimaanH1, 0, ',', '.') }}
                                </td>
                                <td class="text-right text-emerald-400">
                                    {{ $totalPenyerahanHariIni == 0 ? '-' : number_format($totalPenyerahanHariIni, 0, ',', '.') }}
                                </td>
                            @endif
                            <td class="text-right">
                                {{ $totalAkumulasiTerima == 0 ? '-' : number_format($totalAkumulasiTerima, 0, ',', '.') }}
                            </td>
                            <td class="text-right">
                                {{ $totalAkumulasiSerah == 0 ? '-' : number_format($totalAkumulasiSerah, 0, ',', '.') }}
                            </td>
                            <td class="text-right text-indigo-300">
                                {{ $totalPersediaan == 0 ? '-' : number_format($totalPersediaan, 0, ',', '.') }}
                            </td>
                            <td class="text-center text-amber-400">{{ $totalCT == 0 ? '-' : $totalCT . ' CT' }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif

        @if(request('showAnnual', 'true') === 'true' && auth()->user()->role)
            <div class="mb-10">
                <h3 class="text-lg font-black text-yellow-700 mb-4 flex items-center">
                    <span class="w-1.5 h-5 bg-yellow-600 rounded-full mr-2"></span>
                    Laporan Pencapaian Target Pengemasan Tahunan
                </h3>
                <table class="w-full text-left border-collapse table-tight">
                    <thead>
                        <tr class="bg-gray-100 text-[10px] font-black uppercase text-gray-600">
                            <th rowspan="2" class="text-center bg-gray-200">PEC</th>
                            <th colspan="2" class="text-center bg-indigo-50 text-indigo-700">Target TA {{ $tahun_anggaran }}
                            </th>
                            <th colspan="2" class="text-center bg-emerald-50 text-emerald-700">Akumulasi Pengemasan</th>
                            <th colspan="2" class="text-center bg-rose-50 text-rose-700">Sisa / Over</th>
                            <th rowspan="2" class="text-center bg-amber-50 text-amber-700">% Pencapaian</th>
                        </tr>
                        <tr class="bg-gray-50 text-[9px] font-bold uppercase text-gray-500">
                            <th class="text-center">Bilyet</th>
                            <th class="text-center">Dus</th>
                            <th class="text-center">Bilyet</th>
                            <th class="text-center">Dus</th>
                            <th class="text-center">Bilyet</th>
                            <th class="text-center">Dus</th>
                        </tr>
                        <tr class="text-[8px] lowercase text-gray-400 bg-gray-50/50">
                            <th class="text-center italic">a</th>
                            <th class="text-center italic">b</th>
                            <th class="text-center italic">c=b/20.000</th>
                            <th class="text-center italic">d</th>
                            <th class="text-center italic">e=d/20.000</th>
                            <th class="text-center italic">f=b-d</th>
                            <th class="text-center italic">g=f/20.000</th>
                            <th class="text-center italic">h=d/b*100%</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs font-medium text-gray-700 divide-y divide-gray-200">
                        @foreach($targetAchievementData['data'] as $row)
                            <tr>
                                <td class="text-center bg-gray-50 p-1">
                                    <span
                                        class="inline-flex items-center justify-center w-6 h-6 rounded shadow-sm text-[10px] font-black border {{ $colorMap[$row['pecahan']] ?? 'bg-gray-700 text-white' }}">
                                        {{ $row['pecahan'] }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    {{ $row['target_bilyet'] == 0 ? '-' : number_format($row['target_bilyet'], 0, ',', '.') }}
                                </td>
                                <td class="text-right font-bold text-indigo-700 bg-indigo-50/10">
                                    {{ $row['target_dus'] == 0 ? '-' : number_format($row['target_dus'], 0, ',', '.') }}
                                </td>
                                <td class="text-right">
                                    {{ $row['akumulasi_bilyet'] == 0 ? '-' : number_format($row['akumulasi_bilyet'], 0, ',', '.') }}
                                </td>
                                <td class="text-right font-bold text-emerald-700 bg-emerald-50/10">
                                    {{ $row['akumulasi_dus'] == 0 ? '-' : number_format($row['akumulasi_dus'], 0, ',', '.') }}
                                </td>
                                <td
                                    class="text-right font-black {{ $row['sisa_bilyet'] < 0 ? 'text-emerald-600' : ($row['sisa_bilyet'] > 0 ? 'text-rose-600' : 'text-gray-400') }}">
                                    {{ $row['sisa_bilyet'] == 0 ? '-' : ($row['sisa_bilyet'] < 0 ? '+' : '') . number_format(abs($row['sisa_bilyet']), 0, ',', '.') }}
                                </td>
                                <td
                                    class="text-right font-black {{ $row['sisa_dus'] < 0 ? 'text-emerald-600' : ($row['sisa_dus'] > 0 ? 'text-rose-600' : 'text-gray-400') }}">
                                    {{ $row['sisa_dus'] == 0 ? '-' : ($row['sisa_dus'] < 0 ? '+' : '') . number_format(abs($row['sisa_dus']), 0, ',', '.') }}
                                </td>
                                <td
                                    class="text-center font-black {{ $row['persen'] >= 100 ? 'text-green-600' : ($row['persen'] >= 50 ? 'text-amber-600' : 'text-rose-600') }}">
                                    {{ number_format($row['persen'], 1, ',', '.') }}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-900 text-white text-[10px] font-black uppercase">
                        <tr>
                            <td class="text-center py-3">TOTAL</td>
                            <td class="text-right">
                                {{ number_format($targetAchievementData['totals']['target_bilyet'], 0, ',', '.') }}
                            </td>
                            <td class="text-right text-indigo-300">
                                {{ number_format($targetAchievementData['totals']['target_dus'], 0, ',', '.') }}
                            </td>
                            <td class="text-right">
                                {{ number_format($targetAchievementData['totals']['akumulasi_bilyet'], 0, ',', '.') }}
                            </td>
                            <td class="text-right text-emerald-300">
                                {{ number_format($targetAchievementData['totals']['akumulasi_dus'], 0, ',', '.') }}
                            </td>
                            <td
                                class="text-right {{ $targetAchievementData['totals']['sisa_bilyet'] < 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                                {{ ($targetAchievementData['totals']['sisa_bilyet'] < 0 ? '+' : '') . number_format(abs($targetAchievementData['totals']['sisa_bilyet']), 0, ',', '.') }}
                            </td>
                            <td
                                class="text-right {{ $targetAchievementData['totals']['sisa_dus'] < 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                                {{ ($targetAchievementData['totals']['sisa_dus'] < 0 ? '+' : '') . number_format(abs($targetAchievementData['totals']['sisa_dus']), 0, ',', '.') }}
                            </td>
                            <td class="text-center text-amber-400">
                                @php $totalPct = $targetAchievementData['totals']['target_bilyet'] > 0 ? ($targetAchievementData['totals']['akumulasi_bilyet'] / $targetAchievementData['totals']['target_bilyet']) * 100 : 0; @endphp
                                {{ number_format($totalPct, 1, ',', '.') }}%
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif

        @if(request('showMonthly', 'true') === 'true' && auth()->user()->role)
            <div class="mb-10">
                <h3 class="text-lg font-black text-rose-700 mb-4 flex items-center">
                    <span class="w-1.5 h-5 bg-rose-600 rounded-full mr-2"></span>
                    Laporan Pencapaian Target Pengemasan Bulanan
                    ({{ \Carbon\Carbon::parse($tanggal_laporan)->locale('id')->isoFormat('MMMM Y') }})
                </h3>
                <table class="w-full text-left border-collapse table-tight">
                    <thead>
                        <tr class="bg-gray-100 text-[10px] font-black uppercase text-gray-600">
                            <th rowspan="2" class="text-center bg-gray-200">PEC</th>
                            <th colspan="2" class="text-center bg-indigo-50 text-indigo-700">Target Bulan
                                {{ \Carbon\Carbon::parse($tanggal_laporan)->locale('id')->isoFormat('MMMM') }}
                            </th>
                            <th colspan="2" class="text-center bg-emerald-50 text-emerald-700">Akumulasi Bulan
                                {{ \Carbon\Carbon::parse($tanggal_laporan)->locale('id')->isoFormat('MMMM') }}
                            </th>
                            <th colspan="2" class="text-center bg-rose-50 text-rose-700">Sisa / Over</th>
                            <th rowspan="2" class="text-center bg-amber-50 text-amber-700">% Pencapaian</th>
                        </tr>
                        <tr class="bg-gray-50 text-[9px] font-bold uppercase text-gray-500">
                            <th class="text-center">Bilyet</th>
                            <th class="text-center">Dus</th>
                            <th class="text-center">Bilyet</th>
                            <th class="text-center">Dus</th>
                            <th class="text-center">Bilyet</th>
                            <th class="text-center">Dus</th>
                        </tr>
                        <tr class="text-[8px] lowercase text-gray-400 bg-gray-50/50">
                            <th class="text-center italic">a</th>
                            <th class="text-center italic">b</th>
                            <th class="text-center italic">c=b/20.000</th>
                            <th class="text-center italic">d</th>
                            <th class="text-center italic">e=d/20.000</th>
                            <th class="text-center italic">f=b-d</th>
                            <th class="text-center italic">g=f/20.000</th>
                            <th class="text-center italic">h=d/b*100%</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs font-medium text-gray-700 divide-y divide-gray-200">
                        @foreach($monthlyTargetAchievementData['data'] as $row)
                            <tr>
                                <td class="text-center bg-gray-50 p-1">
                                    <span
                                        class="inline-flex items-center justify-center w-6 h-6 rounded shadow-sm text-[10px] font-black border {{ $colorMap[$row['pecahan']] ?? 'bg-gray-700 text-white' }}">
                                        {{ $row['pecahan'] }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    {{ $row['target_bilyet'] == 0 ? '-' : number_format($row['target_bilyet'], 0, ',', '.') }}
                                </td>
                                <td class="text-right font-bold text-indigo-700 bg-indigo-50/10">
                                    {{ $row['target_dus'] == 0 ? '-' : number_format($row['target_dus'], 0, ',', '.') }}
                                </td>
                                <td class="text-right">
                                    {{ $row['akumulasi_bilyet'] == 0 ? '-' : number_format($row['akumulasi_bilyet'], 0, ',', '.') }}
                                </td>
                                <td class="text-right font-bold text-emerald-700 bg-emerald-50/10">
                                    {{ $row['akumulasi_dus'] == 0 ? '-' : number_format($row['akumulasi_dus'], 0, ',', '.') }}
                                </td>
                                <td
                                    class="text-right font-black {{ $row['sisa_bilyet'] < 0 ? 'text-emerald-600' : ($row['sisa_bilyet'] > 0 ? 'text-rose-600' : 'text-gray-400') }}">
                                    {{ $row['sisa_bilyet'] == 0 ? '-' : ($row['sisa_bilyet'] < 0 ? '+' : '') . number_format(abs($row['sisa_bilyet']), 0, ',', '.') }}
                                </td>
                                <td
                                    class="text-right font-black {{ $row['sisa_dus'] < 0 ? 'text-emerald-600' : ($row['sisa_dus'] > 0 ? 'text-rose-600' : 'text-gray-400') }}">
                                    {{ $row['sisa_dus'] == 0 ? '-' : ($row['sisa_dus'] < 0 ? '+' : '') . number_format(abs($row['sisa_dus']), 0, ',', '.') }}
                                </td>
                                <td
                                    class="text-center font-black {{ $row['persen'] >= 100 ? 'text-green-600' : ($row['persen'] >= 50 ? 'text-amber-600' : 'text-rose-600') }}">
                                    {{ number_format($row['persen'], 1, ',', '.') }}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-900 text-white text-[10px] font-black uppercase">
                        <tr>
                            <td class="text-center py-3">TOTAL</td>
                            <td class="text-right">
                                {{ number_format($monthlyTargetAchievementData['totals']['target_bilyet'], 0, ',', '.') }}
                            </td>
                            <td class="text-right text-indigo-300">
                                {{ number_format($monthlyTargetAchievementData['totals']['target_dus'], 0, ',', '.') }}
                            </td>
                            <td class="text-right">
                                {{ number_format($monthlyTargetAchievementData['totals']['akumulasi_bilyet'], 0, ',', '.') }}
                            </td>
                            <td class="text-right text-emerald-300">
                                {{ number_format($monthlyTargetAchievementData['totals']['akumulasi_dus'], 0, ',', '.') }}
                            </td>
                            <td
                                class="text-right {{ $monthlyTargetAchievementData['totals']['sisa_bilyet'] < 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                                {{ ($monthlyTargetAchievementData['totals']['sisa_bilyet'] < 0 ? '+' : '') . number_format(abs($monthlyTargetAchievementData['totals']['sisa_bilyet']), 0, ',', '.') }}
                            </td>
                            <td
                                class="text-right {{ $monthlyTargetAchievementData['totals']['sisa_dus'] < 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                                {{ ($monthlyTargetAchievementData['totals']['sisa_dus'] < 0 ? '+' : '') . number_format(abs($monthlyTargetAchievementData['totals']['sisa_dus']), 0, ',', '.') }}
                            </td>
                            <td class="text-center text-amber-400">
                                @php $totalPct = $monthlyTargetAchievementData['totals']['target_bilyet'] > 0 ? ($monthlyTargetAchievementData['totals']['akumulasi_bilyet'] / $monthlyTargetAchievementData['totals']['target_bilyet']) * 100 : 0; @endphp
                                {{ number_format($totalPct, 1, ',', '.') }}%
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif

        <footer class="mt-20 pt-10 border-t border-dashed border-gray-200 grid grid-cols-2 gap-20">
            <div class="text-center">
                <p class="text-[10px] text-gray-400 uppercase font-bold mb-16">Penanggung Jawab</p>
                <div class="w-40 h-px bg-gray-200 mx-auto mb-2"></div>
                <p class="text-sm font-bold text-gray-800">( ........................................ )</p>
            </div>
            <div class="text-center">
                <p class="text-[10px] text-gray-400 uppercase font-bold mb-16">Kepala Seksi</p>
                <div class="w-40 h-px bg-gray-200 mx-auto mb-2"></div>
                <p class="text-sm font-bold text-gray-800">( ........................................ )</p>
            </div>
        </footer>
    </div>

    @if(request()->has('autoprint'))
        <script>
            window.onload = function () {
                setTimeout(() => { window.print(); }, 500);
            };
        </script>
    @endif
</body>

</html>