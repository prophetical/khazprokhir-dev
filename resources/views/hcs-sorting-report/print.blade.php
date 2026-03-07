<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penyortiran HCS - {{ \Carbon\Carbon::now()->format('d/m/Y') }}</title>
    <!-- Tailwind Local -->
    <script src="{{ asset('vendor/tailwindcss/tailwindcss.min.js') }}"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { padding: 0 !important; margin: 0 !important; background: white; }
            .print-container { width: 100% !important; max-width: none !important; border: none !important; shadow: none !important; padding: 0.2cm !important; border-radius: 0 !important; }
            /* Force background colors in print */
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            @page { margin: 0.2cm; }
        }
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; background-color: #f9fafb; }
        .table-tight th, .table-tight td { padding: 6px 10px; }
    </style>
</head>
<body class="p-4 md:p-10">
    <div class="print-container max-w-5xl mx-auto bg-white p-8 border border-gray-100 shadow-sm rounded-2xl min-h-screen">
        
        <!-- Action Toolbar (Hidden on Print) -->
        <div class="no-print flex justify-between items-center mb-8 pb-6 border-b border-gray-100">
            <a href="{{ route('hcs-sorting-reports.index', request()->all()) }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 flex items-center transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali ke Dashboard Laporan
            </a>
            <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-indigo-100 transition-all flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                Cetak Laporan / Simpan PDF
            </button>
        </div>

        <!-- Header Section -->
        <header class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 mb-1 tracking-tight">LAPORAN PENYORTIRAN HCS</h1>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-4">Khazprokhir Management System</p>
                
                <!-- Filter Context -->
                <div class="flex items-center space-x-3 text-xs mb-4">
                    <div class="bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100 flex items-center">
                        <span class="text-gray-400 mr-2">Periode:</span>
                        <span class="font-bold text-gray-700">
                            @if(request('tanggal_dari') && request('tanggal_sampai'))
                                {{ \Carbon\Carbon::parse(request('tanggal_dari'))->format('d/m/Y') }} – {{ \Carbon\Carbon::parse(request('tanggal_sampai'))->format('d/m/Y') }}
                            @else
                                Semua Tanggal
                            @endif
                        </span>
                    </div>
                    @if(request('gilir'))
                    <div class="bg-indigo-50 px-3 py-1.5 rounded-lg border border-indigo-100 flex items-center">
                        <span class="text-indigo-400 mr-2">Shift:</span>
                        <span class="font-bold text-indigo-700 uppercase">{{ request('gilir') }}</span>
                    </div>
                    @endif
                    @if(request('batch'))
                    <div class="bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-100 flex items-center">
                        <span class="text-emerald-400 mr-2">Batch:</span>
                        <span class="font-bold text-emerald-700 uppercase">{{ request('batch') }}</span>
                    </div>
                    @endif
                    @if(request('seri'))
                    <div class="bg-amber-50 px-3 py-1.5 rounded-lg border border-amber-100 flex items-center">
                        <span class="text-amber-400 mr-2">Seri:</span>
                        <span class="font-bold text-amber-700 uppercase">{{ request('seri') }}</span>
                    </div>
                    @endif
                </div>
            </div>
            <div class="text-right">
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Dicetak Pada</p>
                <p class="text-xs font-bold text-gray-700">{{ \Carbon\Carbon::now()->format('d F Y, H:i') }}</p>
            </div>
        </header>

        <!-- Detailed Report Table -->
        <div class="mb-10">
            <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Data Rincian Penyortiran</h2>
            <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <table class="w-full text-left border-collapse table-tight">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-[10px] font-bold text-gray-500 uppercase">Tanggal</th>
                            <th class="text-[10px] font-bold text-gray-500 uppercase">Gilir</th>
                            <th class="text-[10px] font-bold text-gray-500 uppercase">Batch</th>
                            <th class="text-[10px] font-bold text-gray-500 uppercase">Seri</th>
                            <th class="text-[10px] font-bold text-gray-500 uppercase text-center">Emisi</th>
                            <th class="text-[10px] font-bold text-gray-500 uppercase text-center">TA</th>
                            <th class="text-[10px] font-bold text-gray-500 uppercase text-center">Pch</th>
                            <th class="text-[10px] font-bold text-gray-500 uppercase">Supplier</th>
                            <th class="text-[10px] font-bold text-gray-500 uppercase">Pack Terpilih</th>
                            <th class="text-[10px] font-bold text-gray-500 uppercase text-right">Jml Pack</th>
                            <th class="text-[10px] font-bold text-gray-500 uppercase text-right">Total Bilyet</th>
                            <th class="text-[10px] font-bold text-gray-500 uppercase">Petugas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @php
                            $colorMap = [
                                'S' => 'bg-lime-500',
                                'T' => 'bg-gray-500',
                                'U' => 'bg-amber-500',
                                'V' => 'bg-purple-500',
                                'W' => 'bg-green-500',
                                'X' => 'bg-blue-500',
                                'Y' => 'bg-red-500',
                            ];
                        @endphp
                        @forelse($reports as $row)
                        <tr>
                            <td class="text-[10px] font-medium text-gray-600">{{ $row->tanggal_sortir->locale('id')->isoFormat('D MMMM YYYY') }}</td>
                            <td class="text-[10px] font-medium text-gray-900">{{ $row->gilir }}</td>
                            <td class="text-[10px] font-bold text-gray-900">{{ $row->batch }}</td>
                            <td class="text-[10px] font-medium text-gray-900">{{ $row->seri }}</td>
                            <td class="text-[10px] font-bold text-gray-700 text-center">{{ $row->emisi }}</td>
                            <td class="text-[10px] font-bold text-gray-700 text-center">{{ $row->tahun_anggaran }}</td>
                            <td class="text-center">
                                <span class="{{ $colorMap[$row->pecahan] ?? 'bg-gray-500' }} text-white text-[9px] font-bold px-1.5 py-0.5 rounded">
                                    {{ $row->pecahan }}
                                </span>
                            </td>
                            <td class="text-[10px] font-medium text-gray-600">{{ $row->supplier }}</td>
                            <td class="text-[10px] font-medium text-gray-600">
                                @php
                                    $arr = is_array($row->packs_selected) ? $row->packs_selected : [];
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
                            <td class="text-[10px] font-bold text-gray-900 text-right">{{ number_format($row->jumlah_pack, 0, ',', '.') }}</td>
                            <td class="text-[10px] font-bold text-green-700 text-right">{{ number_format($row->jumlah_bilyet, 0, ',', '.') }}</td>
                            <td class="text-[10px] font-medium text-gray-600">
                                {{ $row->petugas_1 }} 
                                @if($row->petugas_2)
                                    <br><span class="text-[8px] mt-0.5 text-gray-400">&amp; {{ $row->petugas_2 }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center py-10 text-gray-400 text-sm italic">Tidak ada data ditemukan untuk periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-gray-50 border-t border-gray-200">
                        <tr>
                            <td colspan="8" class="text-[10px] font-bold text-gray-700 uppercase p-3 text-right">Total Filter Ini</td>
                            <td class="text-[10px] font-extrabold text-gray-900 text-right p-3">{{ number_format($reports->sum('jumlah_pack'), 0, ',', '.') }}</td>
                            <td class="text-[10px] font-extrabold text-green-700 text-right p-3">{{ number_format($reports->sum('jumlah_bilyet'), 0, ',', '.') }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Footer / Approval -->
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

    <!-- Auto-trigger print if requested via query param -->
    @if(request()->has('autoprint'))
    <script>
        window.onload = function() {
            setTimeout(() => { window.print(); }, 500);
        };
    </script>
    @endif
</body>
</html>
