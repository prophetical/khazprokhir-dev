<!DOCTYPE html>
<html lang="id">
@php App::setLocale('id') @endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ringkasan HCS-HCTS - {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</title>
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
            <button onclick="window.history.back()" class="text-sm font-medium text-gray-500 hover:text-indigo-600 flex items-center transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali
            </button>
            <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-indigo-100 transition-all flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                Cetak Laporan / Simpan PDF
            </button>
        </div>

        <header class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 mb-1 tracking-tight uppercase">Ringkasan Akumulasi HCS & HCTS</h1>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-4">Khazprokhir Management System</p>
                <div class="flex flex-wrap items-center gap-3 text-xs mb-4">
                    @if($search)
                    <div class="bg-indigo-50 px-3 py-1.5 rounded-lg border border-indigo-100 flex items-center">
                        <span class="text-indigo-400 mr-2 uppercase font-black text-[9px]">Cari:</span>
                        <span class="font-bold text-indigo-700">"{{ $search }}"</span>
                    </div>
                    @endif
                    @if(isset($taFilter) && $taFilter)
                    <div class="bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100 flex items-center">
                        <span class="text-blue-400 mr-2 uppercase font-black text-[9px]">TA:</span>
                        <span class="font-bold text-blue-700">{{ $taFilter }}</span>
                    </div>
                    @endif
                    @if(isset($teFilter) && $teFilter)
                    <div class="bg-cyan-50 px-3 py-1.5 rounded-lg border border-cyan-100 flex items-center">
                        <span class="text-cyan-400 mr-2 uppercase font-black text-[9px]">Emisi:</span>
                        <span class="font-bold text-cyan-700">{{ $teFilter }}</span>
                    </div>
                    @endif
                </div>
            <div class="text-right">
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Dicetak Pada</p>
                <p class="text-xs font-bold text-gray-700">{{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }}</p>
            </div>
        </header>

        <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm mb-10">
            <table class="w-full text-left border-collapse table-tight">
                <thead class="bg-gray-900 text-white">
                    <tr>
                        <th class="px-4 py-3 text-[9px] font-black uppercase tracking-widest">Batch / Seri</th>
                        <th class="px-4 py-3 text-center text-[9px] font-black uppercase tracking-widest">Pecahan</th>
                        <th class="px-4 py-3 text-center text-[9px] font-black uppercase tracking-widest">Emisi / TA</th>
                        <th class="px-4 py-3 text-right text-[9px] font-black uppercase tracking-widest text-emerald-400 bg-white/5">Total HCS</th>
                        <th class="px-4 py-3 text-right text-[9px] font-black uppercase tracking-widest text-rose-400 bg-white/5">Total HCTS</th>
                        <th class="px-4 py-3 text-center text-[9px] font-black uppercase tracking-widest text-rose-300 bg-rose-900/10">% HCTS</th>
                        <th class="px-4 py-3 text-right text-[9px] font-black uppercase tracking-widest">Sisa Kuota</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($groups as $group)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 text-[10px] font-bold text-gray-900 uppercase">{{ $group->batch }} / {{ $group->seri }}</td>
                        <td class="px-4 py-3 text-center">
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
                                $currentClass = $pchClasses[$group->pecahan] ?? 'bg-gray-500 text-white';
                            @endphp
                            <span class="px-2 py-0.5 rounded text-[9px] font-black {{ $currentClass }} shadow-sm">
                                {{ $group->pecahan }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center text-[10px] font-bold text-gray-400">{{ $group->emisi }} / {{ $group->tahun_anggaran }}</td>
                        <td class="px-4 py-3 text-right text-[10px] font-black text-emerald-600 border-x border-emerald-50 bg-emerald-50/10">{{ number_format($group->total_hcs, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right text-[10px] font-black text-rose-600 border-x border-rose-50 bg-rose-50/10">{{ number_format($group->total_hcts, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-center text-[10px] font-black text-rose-500 italic bg-rose-50/30">
                            {{ number_format(($group->total_hcts / ($group->total_hcs + $group->total_hcts ?: 1)) * 100, 1) }}%
                        </td>
                        <td class="px-4 py-3 text-right text-[10px] font-black text-gray-400 italic">
                            {{ number_format(4500000 - ($group->total_hcs + $group->total_hcts), 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-100 border-t-2 border-gray-900">
                    <tr>
                        <th colspan="3" class="px-4 py-4 text-[10px] font-black text-gray-900 uppercase tracking-widest text-right">Total Akumulasi Periode</th>
                        <th class="px-4 py-4 text-[11px] font-black text-emerald-700 text-right border-x border-white bg-emerald-50/50">{{ number_format($groups->sum('total_hcs'), 0, ',', '.') }}</th>
                        <th class="px-4 py-4 text-[11px] font-black text-rose-700 text-right border-x border-white bg-rose-50/50">{{ number_format($groups->sum('total_hcts'), 0, ',', '.') }}</th>
                        <th colspan="2" class="bg-gray-100 border-none"></th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <footer class="mt-20 pt-10 border-t border-dashed border-gray-200 grid grid-cols-3 gap-10">
            <div class="text-center">
                <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mb-16">Dibuat Oleh</p>
                <div class="w-40 h-px bg-gray-200 mx-auto mb-2"></div>
                <p class="text-xs font-bold text-gray-900">( ........................................ )</p>
                <p class="text-[8px] text-gray-400 font-bold uppercase mt-1">Seksi Kas Keliling</p>
            </div>
            <div class="text-center">
                <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mb-16">Penanggung Jawab</p>
                <div class="w-40 h-px bg-gray-200 mx-auto mb-2"></div>
                <p class="text-xs font-bold text-gray-900">( ........................................ )</p>
                <p class="text-[8px] text-gray-400 font-bold uppercase mt-1">Seksi Penyetoran</p>
            </div>
            <div class="text-center">
                <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mb-16">Kepala Seksi</p>
                <div class="w-40 h-px bg-gray-200 mx-auto mb-2"></div>
                <p class="text-xs font-bold text-gray-900">( ........................................ )</p>
                <p class="text-[8px] text-gray-400 font-bold uppercase mt-1">Jabatan Struktural</p>
            </div>
        </footer>
    </div>
</body>
</html>
