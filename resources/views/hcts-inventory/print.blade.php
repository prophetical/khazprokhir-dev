<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Persediaan HCTS - {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</title>
    <script src="{{ asset('vendor/tailwindcss/tailwindcss.min.js') }}"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { padding: 0 !important; margin: 0 !important; background: white; }
            .print-container { width: 100% !important; max-width: none !important; border: none !important; shadow: none !important; padding: 0.2cm !important; border-radius: 0 !important; }
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            @page { margin: 0.2cm; }
        }
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; background-color: #f9fafb; }
        .table-tight th, .table-tight td { padding: 8px 12px; }
    </style>
</head>
<body class="p-4 md:p-10">
    <div class="print-container max-w-5xl mx-auto bg-white p-8 border border-gray-100 shadow-sm rounded-2xl min-h-screen relative overflow-hidden">
        {{-- Dekorasi pojok kanan atas --}}
        <div class="absolute top-0 right-0 w-40 h-40 rounded-bl-[80px] opacity-[0.08] pointer-events-none" style="background: linear-gradient(135deg, #1e40af 0%, #7c3aed 55%, #db2877 100%);"></div>
        <div class="absolute top-0 right-0 w-20 h-20 rounded-bl-[40px] opacity-[0.13] pointer-events-none" style="background: linear-gradient(135deg, #1e40af 0%, #7c3aed 55%, #db2877 100%);"></div>
        
        <!-- Action Toolbar -->
        <div class="no-print flex justify-between items-center mb-8 pb-6 border-b border-gray-100">
            <button onclick="window.history.back()" class="text-sm font-medium text-gray-500 hover:text-rose-600 flex items-center transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali
            </button>
            <button onclick="window.print()" class="bg-rose-600 hover:bg-rose-700 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-rose-100 transition-all flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                Cetak Laporan / Simpan PDF
            </button>
        </div>

        <header class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 mb-1 tracking-tight uppercase">Laporan Persediaan HCTS</h1>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-4">Khazprokhir Management System</p>
                
                <div class="flex flex-wrap items-center gap-3 text-xs mb-4">
                    @if($ta)
                    <div class="bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100 flex items-center">
                        <span class="text-gray-400 mr-2 uppercase font-black text-[9px]">TA:</span>
                        <span class="font-bold text-gray-700">{{ $ta }}</span>
                    </div>
                    @endif
                    @if($te)
                    <div class="bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100 flex items-center">
                        <span class="text-gray-400 mr-2 uppercase font-black text-[9px]">TE:</span>
                        <span class="font-bold text-gray-700">{{ $te }}</span>
                    </div>
                    @endif
                </div>
            </div>
            <div class="text-right">
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Dicetak Pada</p>
                <p class="text-xs font-bold text-gray-700">{{ \Carbon\Carbon::now()->format('d F Y, H:i') }}</p>
            </div>
        </header>

        <!-- Summary Cards Print Grid -->
        <div class="grid grid-cols-4 gap-4 mb-8">
            @php
                $denomThemes = [
                    'S' => ['bg' => 'bg-lime-500', 'text' => 'text-lime-700', 'border' => 'border-lime-200'],
                    'T' => ['bg' => 'bg-gray-400', 'text' => 'text-gray-600', 'border' => 'border-gray-200'],
                    'U' => ['bg' => 'bg-amber-400', 'text' => 'text-amber-700', 'border' => 'border-amber-200'],
                    'V' => ['bg' => 'bg-purple-500', 'text' => 'text-purple-700', 'border' => 'border-purple-200'],
                    'W' => ['bg' => 'bg-green-500', 'text' => 'text-green-700', 'border' => 'border-green-200'],
                    'X' => ['bg' => 'bg-blue-500', 'text' => 'text-blue-700', 'border' => 'border-blue-200'],
                    'Y' => ['bg' => 'bg-red-500', 'text' => 'text-red-700', 'border' => 'border-red-200'],
                ];
            @endphp

            @foreach($cardStats as $denom => $stock)
                <div class="border {{ $denomThemes[$denom]['border'] }} p-3 rounded-2xl text-center">
                    <p class="text-[8px] font-black {{ $denomThemes[$denom]['text'] }} uppercase tracking-widest mb-1">{{ $denom }}</p>
                    <p class="text-xs font-black text-gray-900">{{ number_format($stock, 0, ',', '.') }}</p>
                </div>
            @endforeach

            <!-- Total Card -->
            <div class="bg-gray-900 p-3 rounded-2xl text-center">
                <p class="text-[8px] font-black text-rose-400 uppercase tracking-widest mb-1">TOTAL</p>
                <p class="text-xs font-black text-white">{{ number_format($totalInventory, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm mb-10">
            <table class="w-full text-left border-collapse table-tight">
                <thead class="bg-gray-900 text-white">
                    <tr>
                        <th class="text-[9px] font-black uppercase tracking-widest px-4 py-3 text-center">Pecahan</th>
                        <th class="text-[9px] font-black uppercase tracking-widest px-4 py-3 text-center">TA</th>
                        <th class="text-[9px] font-black uppercase tracking-widest px-4 py-3 text-center">TE</th>
                        <th class="text-[9px] font-black uppercase tracking-widest px-4 py-3 text-right">Received</th>
                        <th class="text-[9px] font-black uppercase tracking-widest px-4 py-3 text-right">Submitted</th>
                        <th class="text-[9px] font-black uppercase tracking-widest px-4 py-3 text-right">Stock</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($inventory as $row)
                        <tr>
                            <td class="text-center px-4 py-3">
                                @php
                                    $pchClasses = [
                                        'S' => 'bg-lime-500 text-white',
                                        'T' => 'bg-gray-400 text-white',
                                        'U' => 'bg-amber-400 text-white',
                                        'V' => 'bg-purple-500 text-white',
                                        'W' => 'bg-green-500 text-white',
                                        'X' => 'bg-blue-500 text-white',
                                        'Y' => 'bg-red-500 text-white',
                                    ];
                                    $currentClass = $pchClasses[$row->pecahan] ?? 'bg-gray-500 text-white';
                                @endphp
                                <span class="px-2 py-0.5 rounded text-[9px] font-black {{ $currentClass }} shadow-sm">
                                    {{ $row->pecahan }}
                                </span>
                            </td>
                            <td class="text-[10px] font-bold text-gray-600 px-4 py-3 text-center">{{ $row->tahun_anggaran }}</td>
                            <td class="text-[10px] font-bold text-gray-600 px-4 py-3 text-center">{{ $row->tahun_emisi }}</td>
                            <td class="text-[10px] font-bold text-gray-600 px-4 py-3 text-right">{{ number_format($row->total_received, 0, ',', '.') }}</td>
                            <td class="text-[10px] font-bold text-gray-600 px-4 py-3 text-right">{{ number_format($row->total_submitted, 0, ',', '.') }}</td>
                            <td class="text-[10px] font-black text-rose-600 px-4 py-3 text-right">{{ number_format($row->stock, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <footer class="mt-20 pt-10 border-t border-dashed border-gray-200 grid grid-cols-2 gap-20">
            <div class="text-center">
                <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mb-20">Penanggung Jawab</p>
                <div class="w-48 h-px bg-gray-200 mx-auto mb-2"></div>
                <p class="text-xs font-bold text-gray-900">( ........................................ )</p>
            </div>
            <div class="text-center">
                <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mb-20">Kepala Seksi</p>
                <div class="w-48 h-px bg-gray-200 mx-auto mb-2"></div>
                <p class="text-xs font-bold text-gray-900">( ........................................ )</p>
            </div>
        </footer>
    </div>
</body>
</html>
