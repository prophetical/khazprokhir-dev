<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penerimaan HCTS - {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</title>
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
        .table-tight th, .table-tight td { padding: 6px 10px; }
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
                <h1 class="text-2xl font-extrabold text-gray-900 mb-1 tracking-tight uppercase">Laporan Penerimaan HCTS</h1>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-4">Khazprokhir Management System</p>
                
                <div class="flex flex-wrap items-center gap-3 text-xs mb-4">
                    <div class="bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100 flex items-center">
                        <span class="text-gray-400 mr-2 uppercase font-black text-[9px]">Periode:</span>
                        <span class="font-bold text-gray-700">
                            {{ $startDate ? \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') : 'Awal' }} – {{ $endDate ? \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') : 'Sekarang' }}
                        </span>
                    </div>
                    @if($pecahanFilter)
                    <div class="bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-100 flex items-center">
                        <span class="text-emerald-400 mr-2 uppercase font-black text-[9px]">Pecahan:</span>
                        <span class="font-bold text-emerald-700 uppercase">{{ $pecahanFilter }}</span>
                    </div>
                    @endif
                    @if($gilirFilter)
                    <div class="bg-amber-50 px-3 py-1.5 rounded-lg border border-amber-100 flex items-center">
                        <span class="text-amber-500 mr-2 uppercase font-black text-[9px]">Gilir:</span>
                        <span class="font-bold text-amber-700 uppercase">{{ $gilirFilter }}</span>
                    </div>
                    @endif
                    @if($search)
                    <div class="bg-indigo-50 px-3 py-1.5 rounded-lg border border-indigo-100 flex items-center">
                        <span class="text-indigo-400 mr-2 uppercase font-black text-[9px]">Cari:</span>
                        <span class="font-bold text-indigo-700">"{{ $search }}"</span>
                    </div>
                    @endif
                </div>
            </div>
            <div class="text-right">
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Dicetak Pada</p>
                <p class="text-xs font-bold text-gray-700">{{ \Carbon\Carbon::now()->format('d F Y, H:i') }}</p>
            </div>
        </header>

        <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm mb-10">
            <table class="w-full text-left border-collapse table-tight">
                <thead class="bg-gray-900 text-white">
                    <tr>
                        <th class="text-[9px] font-black uppercase tracking-widest px-4 py-3">Tanggal</th>
                        <th class="text-[9px] font-black uppercase tracking-widest px-4 py-3">No. Bon</th>
                        <th class="text-[9px] font-black uppercase tracking-widest px-4 py-3 text-center">Pec</th>
                        <th class="text-[9px] font-black uppercase tracking-widest px-4 py-3 text-center">Gilir</th>
                        <th class="text-[9px] font-black uppercase tracking-widest px-4 py-3 text-right">Jumlah</th>
                        <th class="text-[9px] font-black uppercase tracking-widest px-4 py-3 text-center">Batch/Seri</th>
                        <th class="text-[9px] font-black uppercase tracking-widest px-4 py-3 text-center">TA/Ems</th>
                        <th class="text-[9px] font-black uppercase tracking-widest px-4 py-3">No. Segel</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($receivings as $row)
                        <tr>
                            <td class="text-[10px] font-bold text-gray-600 px-4 py-3">{{ \Carbon\Carbon::parse($row->tanggal_penerimaan)->format('d/m/Y') }}</td>
                            <td class="text-[10px] font-black text-gray-900 px-4 py-3 italic italic underline decoration-gray-100">{{ $row->nomor_bon }}</td>
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
                            <td class="text-center px-4 py-3 text-[10px] font-bold text-gray-500 uppercase italic">{{ $row->gilir }}</td>
                            <td class="text-[10px] font-black text-rose-600 text-right px-4 py-3">{{ number_format($row->jumlah, 0, ',', '.') }}</td>
                            <td class="text-[10px] font-bold text-gray-700 text-center px-4 py-3 uppercase">{{ $row->batch }} / {{ $row->seri }}</td>
                            <td class="text-[10px] font-bold text-gray-400 text-center px-4 py-3">{{ $row->tahun_anggaran }} / {{ $row->emisi }}</td>
                            <td class="text-[9px] font-mono font-bold text-indigo-600 px-4 py-3">{{ $row->nomor_segel }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 border-t-2 border-gray-900">
                    <tr>
                        <th colspan="3" class="px-4 py-4 text-[10px] font-black text-gray-900 uppercase tracking-widest text-right">Total Akumulasi HCTS</th>
                        <th class="px-4 py-4 text-sm font-black text-rose-600 text-right border-x border-gray-200">{{ number_format($receivings->sum('jumlah'), 0, ',', '.') }}</th>
                        <th colspan="3" class="bg-gray-50 border-none"></th>
                    </tr>
                </tfoot>
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
