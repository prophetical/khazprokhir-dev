<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Khazprokhir - {{ $seri->seri }} - {{ $seri->batch }}</title>
    <!-- Tailwind Lokal (Tanpa CDN) -->
    <script src="{{ asset('vendor/tailwindcss/tailwindcss.min.js') }}"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { padding: 0 !important; margin: 0 !important; background: white; }
            .print-container {
                width: 100% !important;
                max-width: none !important;
                border: none !important;
                box-shadow: none !important;
                padding: 0.5cm !important;
                border-radius: 0 !important;
            }
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            @page { size: A3 landscape; margin: 0.5cm; }
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: #f9fafb;
        }

        .table-rekap th, .table-rekap td {
            padding: 4px 6px;
            font-size: 8px;
            line-height: 1.2;
        }

        .group-total-cell {
            background-color: #fff1f2 !important;
            color: #e11d48 !important;
            font-weight: 900 !important;
        }
    </style>
</head>

<body class="p-6 md:p-10">
    <div class="print-container max-w-[420mm] mx-auto bg-white p-10 border border-gray-100 shadow-sm rounded-3xl min-h-screen relative overflow-hidden">
        
        {{-- Dekorasi Estetik (Sesuai Reports/Print) --}}
        <div class="absolute top-0 right-0 w-60 h-60 rounded-bl-[120px] opacity-[0.05] pointer-events-none" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);"></div>
        <div class="absolute bottom-0 left-0 w-60 h-60 rounded-tr-[120px] opacity-[0.03] pointer-events-none" style="background: linear-gradient(315deg, #4f46e5 0%, #7c3aed 100%);"></div>

        <!-- Bilah Alat Aksi (no-print) -->
        <div class="no-print flex justify-between items-center mb-8 pb-6 border-b border-gray-100">
            <button onclick="window.close()" class="text-sm font-bold text-gray-400 hover:text-rose-500 flex items-center transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Tutup Halaman
            </button>
            <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-2xl font-black text-sm shadow-xl shadow-indigo-100 transition-all flex items-center transform active:scale-95">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                CETAK REKAP / SIMPAN PDF
            </button>
        </div>

        <!-- Header Section -->
        <header class="flex justify-between items-start mb-10">
            <div class="flex-1">
                <h1 class="text-3xl font-black text-gray-900 mb-2 tracking-tight">LAPORAN HASIL REKAP KHAZPROKHIR</h1>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-[0.3em] mb-6">Konsolidasi Data Produksi: Khazai | Cutpack | Rikyet</p>
                
                <div class="flex flex-wrap gap-4">
                    <div class="bg-slate-50 border border-slate-100 px-5 py-3 rounded-2xl flex flex-col min-w-[120px]">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Batch</span>
                        <span class="text-lg font-black text-slate-700 tracking-wider">{{ $seri->batch }}</span>
                    </div>
                    <div class="bg-slate-50 border border-slate-100 px-5 py-3 rounded-2xl flex flex-col min-w-[150px]">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Nomor Seri</span>
                        <span class="text-lg font-black text-slate-700 tracking-wider">{{ $seri->seri }}</span>
                    </div>
                    <div class="bg-indigo-50 border border-indigo-100 px-5 py-3 rounded-2xl flex flex-col items-center">
                        <span class="text-[9px] font-black text-indigo-400 uppercase tracking-widest mb-1">Pecahan</span>
                        <span class="w-8 h-8 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-black text-sm shadow-sm">{{ $seri->pecahan }}</span>
                    </div>
                    <div class="bg-slate-50 border border-slate-100 px-5 py-3 rounded-2xl flex flex-col min-w-[100px]">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">TA / TE</span>
                        <span class="text-lg font-black text-slate-700">{{ $seri->tahun_anggaran }} / {{ $seri->tahun_emisi }}</span>
                    </div>
                </div>
            </div>
            <div class="text-right flex flex-col items-end">
                <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mb-3">
                    <svg class="w-8 h-8 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Dicetak Pada</p>
                <p class="text-xs font-black text-gray-700">{{ now()->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }}</p>
            </div>
        </header>

        <!-- Grid 5 Kolom (Data 100 Pack) -->
        <div class="grid grid-cols-5 gap-3">
            @php
                $columns = [
                    ['start' => 1,  'end' => 20],
                    ['start' => 21, 'end' => 40],
                    ['start' => 41, 'end' => 60],
                    ['start' => 61, 'end' => 80],
                    ['start' => 81, 'end' => 100],
                ];

                function getPrintRowSpan($pack, $tableStart, $tableEnd) {
                    $groupStart = floor(($pack - 1) / 4) * 4 + 1;
                    if ($pack == $tableStart || $pack % 4 == 1) {
                        $groupEnd = $groupStart + 3;
                        $spanEnd = min($groupEnd, $tableEnd);
                        return $spanEnd - $pack + 1;
                    }
                    return 0;
                }
            @endphp

            @foreach($columns as $col)
            <div class="border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                <table class="w-full text-center border-collapse table-rekap">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th rowspan="2" class="border-r border-gray-200 text-gray-500 bg-gray-100/50">NO</th>
                            <th colspan="3" class="text-indigo-600 border-b border-gray-200 py-1 uppercase font-black tracking-tight">RUSAK BILYET</th>
                            <th rowspan="2" class="border-l border-gray-200 text-rose-500 bg-gray-100/50 uppercase leading-none">TOTAL<br>CAMP</th>
                        </tr>
                        <tr class="text-[7px]">
                            <th class="border-r border-gray-200 font-bold text-gray-400">S1</th>
                            <th class="border-r border-gray-200 font-bold text-gray-400">S2</th>
                            <th class="font-bold text-gray-400">CP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @for($p = $col['start']; $p <= $col['end']; $p++)
                        @php
                            $d = $grid[$p];
                            $span = getPrintRowSpan($p, $col['start'], $col['end']);
                            $gIdx = floor(($p - 1) / 4);
                        @endphp
                        <tr>
                            <td class="bg-gray-50/50 font-black text-gray-400 border-r border-gray-100 italic">{{ $p }}</td>
                            <td class="border-r border-gray-100 font-bold {{ $d['s1'] > 0 ? 'text-gray-700' : 'text-gray-200' }}">{{ $d['s1'] > 0 ? number_format($d['s1'], 0, ',', '.') : '-' }}</td>
                            <td class="border-r border-gray-100 font-bold {{ $d['s2'] > 0 ? 'text-gray-700' : 'text-gray-200' }}">{{ $d['s2'] > 0 ? number_format($d['s2'], 0, ',', '.') : '-' }}</td>
                            <td class="border-r border-gray-100 font-bold {{ $d['camp'] > 0 ? 'text-gray-700' : 'text-gray-200' }}">{{ $d['camp'] > 0 ? number_format($d['camp'], 0, ',', '.') : '-' }}</td>
                            
                            @if($span > 0)
                            <td rowspan="{{ $span }}" class="group-total-cell border-l border-gray-200 align-middle">
                                {{ number_format($groupTotals[$gIdx], 0, ',', '.') }}
                            </td>
                            @endif
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
            @endforeach
        </div>

        <!-- Footer / Approval (Sesuai Reports/Print) -->
        <footer class="mt-16 pt-10 border-t border-dashed border-gray-200 grid grid-cols-2 gap-40 max-w-4xl mx-auto">
            <div class="text-center">
                <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mb-20">Kepala Seksi</p>
                <div class="w-48 h-px bg-gray-300 mx-auto mb-2"></div>
                <p class="text-xs font-black text-gray-800">( ........................................ )</p>
            </div>
            <div class="text-center">
                <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mb-20">Penanggung Jawab</p>
                <div class="w-48 h-px bg-gray-300 mx-auto mb-2"></div>
                <p class="text-xs font-black text-gray-800">( ........................................ )</p>
            </div>
        </footer>

    </div>

    <!-- Pemicu cetak otomatis -->
    <script>
        window.onload = function () {
            setTimeout(() => { 
                // Jika ingin otomatis cetak saat tab dibuka:
                // window.print(); 
            }, 500);
        };
    </script>
</body>
</html>
