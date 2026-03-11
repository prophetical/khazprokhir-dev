<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Harian Operasional - {{ \Carbon\Carbon::parse($tanggal_laporan)->locale('id')->isoFormat('D MMMM YYYY') }}</title>
    <script src="{{ asset('vendor/tailwindcss/tailwindcss.min.js') }}"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { padding: 0 !important; margin: 0 !important; background: white; }
            .print-container { width: 100% !important; max-width: none !important; border: none !important; shadow: none !important; padding: 0.2cm !important; border-radius: 0 !important; }
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            @page { margin: 0.5cm; }
        }
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; background-color: #f9fafb; }
        .table-tight th, .table-tight td { padding: 8px 10px; border: 1px solid #e5e7eb; }
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
    <div class="print-container max-w-7xl mx-auto bg-white p-8 border border-gray-100 shadow-sm rounded-2xl min-h-screen relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 rounded-bl-[80px] opacity-[0.08] pointer-events-none" style="background: linear-gradient(135deg, #1e40af 0%, #7c3aed 55%, #db2877 100%);"></div>
        
        <div class="no-print flex justify-between items-center mb-8 pb-6 border-b border-gray-100">
            <a href="{{ route('laporan-harian.index', request()->all()) }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 flex items-center transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali ke Dashboard
            </a>
            <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-indigo-100 transition-all flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                Cetak Laporan / Simpan PDF
            </button>
        </div>

        <header class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 mb-1 tracking-tight text-indigo-900">LAPORAN HARIAN OPERASIONAL</h1>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-4">Khazprokhir Management System</p>
                
                <div class="flex flex-wrap items-center gap-3 text-xs mb-4">
                    <div class="bg-indigo-50 px-3 py-1.5 rounded-lg border border-indigo-100 flex items-center">
                        <span class="text-indigo-400 mr-2 uppercase font-bold text-[10px]">Tanggal:</span>
                        <span class="font-bold text-indigo-700 underline decoration-indigo-200 underline-offset-4">{{ \Carbon\Carbon::parse($tanggal_laporan)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
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
                <p class="text-xs font-bold text-indigo-600">Laporan Persediaan HCS {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }}</p>
            </div>
        </header>

        <div class="mb-10">
            <table class="w-full text-left border-collapse table-tight">
                <thead>
                    <tr class="bg-gray-100 text-[10px] font-black uppercase text-gray-600">
                        <th rowspan="2" class="text-center bg-gray-200">Pecahan</th>
                        <th colspan="3" class="text-center bg-indigo-50 text-indigo-700">Persediaan</th>
                        <th rowspan="2" class="text-center bg-indigo-100 text-indigo-900">Total Persediaan<br>(Bilyet)</th>
                        <th colspan="3" class="text-center bg-pink-50 text-pink-700">Penyerahan HCS</th>
                        <th colspan="3" class="text-center bg-green-50 text-green-700">Target & Pencapaian</th>
                        <th rowspan="2" class="text-center bg-gray-50">Akumulasi<br>Terima HCS</th>
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
                </thead>
                <tbody class="text-xs font-medium text-gray-700 divide-y divide-gray-200">
                    @foreach($reportData as $row)
                        <tr class="hover:bg-gray-50">
                            <td class="text-center bg-gray-50 p-1">
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded shadow-sm text-[10px] font-black border {{ $colorMap[$row['pecahan']] ?? 'bg-gray-700 text-white' }}">
                                    {{ $row['pecahan'] }}
                                </span>
                            </td>
                            <td class="text-right">{{ $row['siap_kemas_bilyet'] == 0 ? '-' : number_format($row['siap_kemas_bilyet'], 0, ',', '.') }}</td>
                            <td class="text-right">{{ $row['siap_kirim_bilyet'] == 0 ? '-' : number_format($row['siap_kirim_bilyet'], 0, ',', '.') }}</td>
                            <td class="text-right">{{ $row['siap_kirim_dus'] == 0 ? '-' : number_format($row['siap_kirim_dus'], 0, ',', '.') }}</td>
                            <td class="text-right font-black text-indigo-700 bg-indigo-50/30">{{ $row['total_persediaan_bilyet'] == 0 ? '-' : number_format($row['total_persediaan_bilyet'], 0, ',', '.') }}</td>
                            <td class="text-right">{{ $row['penyerahan_hari_ini_bilyet'] == 0 ? '-' : number_format($row['penyerahan_hari_ini_bilyet'], 0, ',', '.') }}</td>
                            <td class="text-right">{{ $row['penyerahan_hari_ini_dus'] == 0 ? '-' : number_format($row['penyerahan_hari_ini_dus'], 2, ',', '.') }}</td>
                            <td class="text-right font-bold text-pink-700 bg-pink-50/30">{{ $row['akumulasi_penyerahan'] == 0 ? '-' : number_format($row['akumulasi_penyerahan'], 0, ',', '.') }}</td>
                            <td class="text-right">{{ $row['target'] == 0 ? '-' : number_format($row['target'], 0, ',', '.') }}</td>
                            <td class="text-right">{{ $row['sisa_target'] == 0 ? '-' : number_format($row['sisa_target'], 0, ',', '.') }}</td>
                            <td class="text-center font-black {{ $row['persentase_target'] >= 80 ? 'text-green-600' : ($row['persentase_target'] >= 50 ? 'text-amber-600' : 'text-rose-600') }}">
                                {{ $row['persentase_target'] == 0 ? '-' : number_format($row['persentase_target'], 1, ',', '.') . '%' }}
                            </td>
                            <td class="text-right font-bold bg-gray-50">{{ $row['akumulasi_penerimaan_hcs'] == 0 ? '-' : number_format($row['akumulasi_penerimaan_hcs'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-900 text-white text-xs font-black uppercase">
                    <tr>
                        <td class="text-center py-3">TOTAL</td>
                        <td class="text-right">{{ $totals['siap_kemas_bilyet'] == 0 ? '-' : number_format($totals['siap_kemas_bilyet'], 0, ',', '.') }}</td>
                        <td class="text-right">{{ $totals['siap_kirim_bilyet'] == 0 ? '-' : number_format($totals['siap_kirim_bilyet'], 0, ',', '.') }}</td>
                        <td class="text-right">
                            @php $siapKirimTotalDus = $totals['siap_kirim_bilyet'] / 20000; @endphp
                            {{ $siapKirimTotalDus == 0 ? '-' : number_format($siapKirimTotalDus, 0, ',', '.') }}
                        </td>
                        <td class="text-right text-indigo-300">{{ $totals['total_persediaan_bilyet'] == 0 ? '-' : number_format($totals['total_persediaan_bilyet'], 0, ',', '.') }}</td>
                        <td class="text-right">{{ $totals['penyerahan_hari_ini_bilyet'] == 0 ? '-' : number_format($totals['penyerahan_hari_ini_bilyet'], 0, ',', '.') }}</td>
                        <td class="text-right">
                            @php $penyerahanTotalDus = $totals['penyerahan_hari_ini_bilyet'] / 20000; @endphp
                            {{ $penyerahanTotalDus == 0 ? '-' : number_format($penyerahanTotalDus, 2, ',', '.') }}
                        </td>
                        <td class="text-right text-pink-300">{{ $totals['akumulasi_penyerahan_bilyet'] == 0 ? '-' : number_format($totals['akumulasi_penyerahan_bilyet'], 0, ',', '.') }}</td>
                        <td class="text-right">{{ $totals['target'] == 0 ? '-' : number_format($totals['target'], 0, ',', '.') }}</td>
                        <td class="text-right">{{ $totals['sisa_target'] == 0 ? '-' : number_format($totals['sisa_target'], 0, ',', '.') }}</td>
                        <td class="text-center">
                            @php
                                $totalPct = $totals['target'] > 0 ? ($totals['akumulasi_penyerahan_bilyet'] / $totals['target']) * 100 : 0;
                            @endphp
                            {{ $totalPct == 0 ? '-' : number_format($totalPct, 1, ',', '.') . '%' }}
                        </td>
                        <td class="text-right">{{ $totals['akumulasi_penerimaan_hcs'] == 0 ? '-' : number_format($totals['akumulasi_penerimaan_hcs'], 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

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
        window.onload = function() {
            setTimeout(() => { window.print(); }, 500);
        };
    </script>
    @endif
</body>
</html>
