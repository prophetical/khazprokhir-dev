<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penerimaan HCS - {{ \Carbon\Carbon::now()->format('d/m/Y') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; margin: 0; background: white; }
            .print-container { width: 100%; border: none; shadow: none; }
            /* Force background colors in print */
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            @page { margin: 1.5cm; }
        }
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; background-color: #f9fafb; }
        .table-tight th, .table-tight td { padding: 8px 12px; }
    </style>
</head>
<body class="p-4 md:p-10">
    <div class="print-container max-w-5xl mx-auto bg-white p-8 border border-gray-100 shadow-sm rounded-2xl min-h-screen">
        
        <!-- Action Toolbar (Hidden on Print) -->
        <div class="no-print flex justify-between items-center mb-8 pb-6 border-b border-gray-100">
            <a href="{{ route('reports.index', ['type' => $type, 'start_date' => $startDate, 'end_date' => $endDate, 'gilir' => $gilir]) }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 flex items-center transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali ke Dashboard
            </a>
            <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-indigo-100 transition-all flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                Cetak Laporan / Simpan PDF
            </button>
        </div>

        <!-- Header Section -->
        <header class="flex justify-between items-start mb-10">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 mb-1 tracking-tight">LAPORAN PENERIMAAN HCS</h1>
                <p class="text-gray-500 font-medium">Khazprokhir Management System</p>
                <div class="mt-4 flex items-center space-x-4 text-sm">
                    <div class="bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100 flex items-center">
                        <span class="text-gray-400 mr-2">Periode:</span>
                        <span class="font-bold text-gray-700">{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} – {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</span>
                    </div>
                    @if($gilir)
                    <div class="bg-indigo-50 px-3 py-1.5 rounded-lg border border-indigo-100 flex items-center">
                        <span class="text-indigo-400 mr-2">Shift:</span>
                        <span class="font-bold text-indigo-700 uppercase">{{ $gilir }}</span>
                    </div>
                    @endif
                </div>
            </div>
            <div class="text-right">
                <div class="mb-2">
                    <span class="inline-block bg-indigo-600 text-white p-2 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 2v-6m-9 9h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    </span>
                </div>
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Dicetak Pada</p>
                <p class="text-sm font-bold text-gray-700">{{ \Carbon\Carbon::now()->format('d F Y, H:i') }}</p>
            </div>
        </header>

        <!-- Global Summary Grid -->
        <div class="mb-10">
            <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Ringkasan All-Time (Global)</h2>
            <div class="grid grid-cols-4 lg:grid-cols-4 gap-4">
                @php
                    $pecahanMeta = [
                        'S' => ['color' => 'bg-stone-600',   'label' => 'Rp1.000'],
                        'T' => ['color' => 'bg-slate-500',   'label' => 'Rp2.000'],
                        'U' => ['color' => 'bg-orange-500',  'label' => 'Rp5.000'],
                        'V' => ['color' => 'bg-purple-600',  'label' => 'Rp10.000'],
                        'W' => ['color' => 'bg-green-600',   'label' => 'Rp20.000'],
                        'X' => ['color' => 'bg-blue-600',    'label' => 'Rp50.000'],
                        'Y' => ['color' => 'bg-red-600',     'label' => 'Rp100.000'],
                    ];
                @endphp
                @foreach($globalTotalsPerPecahan as $key => $total)
                <div class="border border-gray-100 rounded-xl p-3 flex items-center space-x-3">
                    <div class="{{ $pecahanMeta[$key]['color'] }} w-8 h-8 rounded-lg flex items-center justify-center text-white font-bold text-sm">
                        {{ $key }}
                    </div>
                    <div>
                        <p class="text-[8px] text-gray-400 uppercase font-bold">{{ $pecahanMeta[$key]['label'] }}</p>
                        <p class="text-sm font-bold text-gray-800">{{ number_format($total, 0, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach
                <div class="bg-gray-900 rounded-xl p-3 flex items-center space-x-3">
                    <div class="bg-white/20 w-8 h-8 rounded-lg flex items-center justify-center text-white font-bold text-[8px]">ALL</div>
                    <div>
                        <p class="text-[8px] text-gray-400 uppercase font-bold">Grand Total</p>
                        <p class="text-sm font-bold text-white">{{ number_format($globalGrandTotal, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Report Table -->
        <div class="mb-10">
            <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Data Rincian Penerimaan</h2>
            <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <table class="w-full text-left border-collapse table-tight">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-[10px] font-bold text-gray-500 uppercase">Tanggal</th>
                            <th class="text-[10px] font-bold text-gray-500 uppercase">No Bon</th>
                            <th class="text-[10px] font-bold text-gray-500 uppercase text-center">Pec</th>
                            <th class="text-[10px] font-bold text-gray-500 uppercase text-right">Jumlah</th>
                            <th class="text-[10px] font-bold text-gray-500 uppercase">Gilir</th>
                            <th class="text-[10px] font-bold text-gray-500 uppercase">Mesin</th>
                            <th class="text-[10px] font-bold text-gray-500 uppercase">Supplier</th>
                            <th class="text-[10px] font-bold text-gray-500 uppercase">Batch/Seri</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($data as $row)
                        <tr>
                            <td class="text-xs font-medium text-gray-600">{{ \Carbon\Carbon::parse($row->tanggal_penerimaan)->format('d/m/Y') }}</td>
                            <td class="text-xs font-bold text-gray-900">{{ $row->nomor_bon }}</td>
                            <td class="text-center">
                                <span class="{{ $pecahanMeta[$row->pecahan]['color'] }} text-white text-[9px] font-bold px-1.5 py-0.5 rounded">
                                    {{ $row->pecahan }}
                                </span>
                            </td>
                            <td class="text-xs font-bold text-gray-900 text-right">{{ number_format($row->jumlah, 0, ',', '.') }}</td>
                            <td class="text-[10px] font-medium text-gray-500 uppercase">{{ $row->gilir }}</td>
                            <td class="text-xs font-medium text-gray-600">{{ $row->mesin }}</td>
                            <td class="text-xs font-medium text-gray-600">{{ $row->supplier }}</td>
                            <td class="text-xs font-medium text-gray-600 italic">{{ $row->batch }} / {{ $row->seri }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-10 text-gray-400 text-sm italic">Tidak ada data ditemukan untuk periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-gray-50 border-t border-gray-200">
                        <tr>
                            <td colspan="3" class="text-[10px] font-bold text-gray-700 uppercase p-3">Total Baris Ini</td>
                            <td class="text-xs font-extrabold text-gray-900 text-right p-3">{{ number_format($data->sum('jumlah'), 0, ',', '.') }}</td>
                            <td colspan="4"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Footer / Approval -->
        <footer class="mt-20 pt-10 border-t border-dashed border-gray-200 grid grid-cols-2 gap-20">
            <div class="text-center">
                <p class="text-[10px] text-gray-400 uppercase font-bold mb-16">Petugas Operasional</p>
                <div class="w-40 h-px bg-gray-200 mx-auto mb-2"></div>
                <p class="text-sm font-bold text-gray-800">( ........................................ )</p>
            </div>
            <div class="text-center">
                <p class="text-[10px] text-gray-400 uppercase font-bold mb-16">Supervisor / Manager</p>
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
