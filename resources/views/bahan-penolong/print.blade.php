<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Bahan Penolong - {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</title>
    <!-- Tailwind Local -->
    <script src="{{ asset('vendor/tailwindcss/tailwindcss.min.js') }}"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { padding: 0 !important; margin: 0 !important; background: white; }
            .print-container { width: 100% !important; max-width: none !important; border: none !important; shadow: none !important; padding: 0.2cm !important; border-radius: 0 !important; }
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            @page { margin: 0.2cm; }
        }
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; background-color: #f9fafb; color: #1e293b; }
        .table-tight th, .table-tight td { padding: 8px 12px; }
    </style>
</head>
<body class="p-4 md:p-10">
    <div class="print-container max-w-6xl mx-auto bg-white p-8 border border-gray-100 shadow-sm rounded-3xl min-h-screen relative overflow-hidden">
        {{-- Glassmorphism Decorations --}}
        <div class="absolute top-0 right-0 w-64 h-64 rounded-bl-full opacity-[0.03] pointer-events-none bg-gradient-to-br from-indigo-600 via-purple-600 to-rose-600"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 rounded-tr-full opacity-[0.03] pointer-events-none bg-gradient-to-tr from-indigo-600 via-purple-600 to-rose-600"></div>
        
        <!-- Action Toolbar -->
        <div class="no-print flex justify-between items-center mb-10 pb-6 border-b border-gray-100">
            <button onclick="window.history.back()" class="text-sm font-bold text-gray-400 hover:text-indigo-600 flex items-center transition-all group">
                <div class="w-8 h-8 rounded-xl bg-gray-50 flex items-center justify-center mr-2 group-hover:bg-indigo-50 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                </div>
                Kembali
            </button>
            <button onclick="window.print()" class="bg-slate-900 hover:bg-black text-white px-8 py-3 rounded-2xl font-black text-xs shadow-xl shadow-slate-200 transition-all flex items-center uppercase tracking-widest">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                Cetak Laporan
            </button>
        </div>

        <header class="flex justify-between items-start mb-12">
            <div>
                <h1 class="text-3xl font-black text-slate-900 mb-2 tracking-tight uppercase">Laporan Mutasi Bahan Penolong</h1>
                <div class="flex items-center gap-2 mb-6">
                    <span class="w-8 h-1 bg-indigo-500 rounded-full"></span>
                    <p class="text-[10px] text-indigo-500 font-black uppercase tracking-[0.2em]">Khazprokhir Inventory Management</p>
                </div>
                
                <div class="flex flex-wrap gap-3 text-xs">
                    @if(request('tanggal_awal') || request('tanggal_akhir'))
                    <div class="bg-slate-50 px-4 py-2 rounded-xl border border-slate-100 flex items-center shadow-sm">
                        <span class="text-slate-400 mr-2 uppercase font-black text-[9px] tracking-widest">Periode</span>
                        <span class="font-bold text-slate-700">
                            {{ request('tanggal_awal') ? \Carbon\Carbon::parse(request('tanggal_awal'))->translatedFormat('d M Y') : 'Awal' }} — 
                            {{ request('tanggal_akhir') ? \Carbon\Carbon::parse(request('tanggal_akhir'))->translatedFormat('d M Y') : 'Sekarang' }}
                        </span>
                    </div>
                    @endif

                    @if(request('tipe'))
                    <div class="bg-slate-50 px-4 py-2 rounded-xl border border-slate-100 flex items-center shadow-sm">
                        <span class="text-slate-400 mr-2 uppercase font-black text-[9px] tracking-widest">Tipe</span>
                        <span class="font-bold {{ request('tipe') == 'masuk' ? 'text-emerald-600' : 'text-rose-600' }} uppercase italic">
                            {{ request('tipe') }}
                        </span>
                    </div>
                    @endif

                    @if(request('search'))
                    <div class="bg-indigo-50 px-4 py-2 rounded-xl border border-indigo-100 flex items-center shadow-sm">
                        <span class="text-indigo-400 mr-2 uppercase font-black text-[9px] tracking-widest">Pencarian</span>
                        <span class="font-bold text-indigo-700">"{{ request('search') }}"</span>
                    </div>
                    @endif
                </div>
            </div>
            <div class="text-right">
                <p class="text-[10px] text-slate-400 uppercase tracking-widest font-black mb-1">Dicetak Pada</p>
                <div class="bg-slate-900 text-white px-4 py-2 rounded-xl inline-block shadow-lg shadow-slate-100">
                    <p class="text-xs font-bold leading-none">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                    <p class="text-[9px] font-medium text-slate-400 mt-1 uppercase">{{ \Carbon\Carbon::now()->format('H:i') }} WIB</p>
                </div>
            </div>
        </header>

        <div class="mb-12 relative">
            <div class="overflow-hidden border border-slate-200 rounded-2xl shadow-sm">
                <table class="w-full text-left border-collapse table-tight">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Waktu</th>
                            <th class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Material</th>
                            <th class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Kode</th>
                            <th class="text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">Tipe/Kat</th>
                            <th class="text-[10px] font-black text-slate-500 uppercase tracking-widest text-right">Jumlah</th>
                            <th class="text-[10px] font-black text-slate-500 uppercase tracking-widest text-right">Stok Akhir</th>
                            <th class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Petugas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($transactions as $t)
                        <tr class="text-[11px] hover:bg-slate-50/50 transition-colors">
                            <td class="border-r border-slate-50 italic text-slate-400">
                                <span class="font-bold text-slate-700 block not-italic">{{ $t->created_at->format('d/m/Y') }}</span>
                                {{ $t->created_at->format('H:i') }}
                            </td>
                            <td class="font-bold text-slate-800 uppercase border-r border-slate-50">
                                {{ $t->bahanPenolong->nama_bahan }}
                            </td>
                            <td class="font-mono text-[10px] text-slate-400 uppercase border-r border-slate-50">
                                {{ $t->bahanPenolong->kode_material ?? '-' }}
                            </td>
                            <td class="text-center border-r border-slate-50">
                                <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase {{ $t->tipe == 'masuk' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                    {{ $t->tipe }}
                                </span>
                                <span class="block text-[8px] text-slate-300 font-bold uppercase mt-0.5">{{ $t->kategori }}</span>
                            </td>
                            <td class="text-right font-black text-slate-900 border-r border-slate-50 bg-slate-50/30">
                                <span class="{{ $t->tipe == 'masuk' ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $t->tipe == 'masuk' ? '+' : '-' }}{{ number_format($t->jumlah) }}
                                </span>
                                <span class="text-[8px] text-slate-400 ml-1 italic font-normal">{{ $t->bahanPenolong->satuan }}</span>
                            </td>
                            <td class="text-right font-bold text-slate-500 bg-slate-50/50">
                                {{ number_format($t->stok_akhir) }}
                            </td>
                            <td class="uppercase text-[9px] font-bold text-slate-400 pl-4">
                                {{ $t->user->name ?? '-' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($transactions->isEmpty())
            <div class="py-20 text-center border border-dashed border-slate-200 rounded-2xl mt-4">
                <p class="text-slate-400 italic text-sm font-medium">Tidak ada data mutasi yang ditemukan.</p>
            </div>
            @endif
        </div>

        <footer class="mt-24 pt-12 border-t border-dashed border-slate-200 grid grid-cols-2 gap-24">
            <div class="text-center">
                <p class="text-[10px] text-slate-400 uppercase font-black mb-16 tracking-widest">Penanggung Jawab,</p>
                <div class="w-48 h-px bg-slate-200 mx-auto mb-3"></div>
                <p class="text-xs font-black text-slate-900 uppercase italic">
                    {{ Auth::user()->name }}
                </p>
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter mt-1">{{ Auth::user()->role }}</p>
            </div>
            <div class="text-center">
                <p class="text-[10px] text-slate-400 uppercase font-black mb-16 tracking-widest">Mengetahui,</p>
                <div class="w-48 h-px bg-slate-200 mx-auto mb-3"></div>
                <p class="text-xs font-black text-slate-900 uppercase">Kepala Bagian</p>
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter mt-1">Inventory Control</p>
            </div>
        </footer>

        <!-- Timestamp Label for Print Only -->
        <div class="hidden print:block absolute bottom-4 right-8">
            <p class="text-[7px] text-slate-300 font-mono tracking-widest">SYSTEM_PRINT_ID: {{ time() }}_BP_MUTASI</p>
        </div>
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
