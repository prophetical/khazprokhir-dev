<thead>
    {{-- BARIS 0: Judul Pack (Unified inside thead) --}}
    <tr class="bg-slate-900 border-b border-slate-700 h-[36px]">
        <th colspan="10" class="px-3 py-0 h-[36px] text-white text-[10px] font-black uppercase tracking-[0.2em] text-center shadow-lg bg-slate-900 border-x border-slate-700">
            {{ $title ?? 'PACK' }}
        </th>
    </tr>
    
    {{-- BARIS 1: Judul Utama --}}
    <tr class="bg-slate-800 text-white text-[9px] font-black uppercase tracking-[0.1em] text-center border-b border-slate-700 h-[36px]">
        <th rowspan="2" class="px-2 py-0 h-[72px] border-r border-slate-700 w-10 bg-slate-800 shadow-sm border-l border-slate-700 text-amber-500">No<br>Pack</th>
        <th colspan="3" class="px-2 py-0 h-[36px] border-r border-slate-700 bg-amber-600 shadow-inner">Inschiet Bilyet</th>
        <th colspan="3" class="px-2 py-0 h-[36px] border-r border-slate-700 bg-slate-900 text-amber-400">Total Rusak</th>
        <th colspan="3" class="px-2 py-0 h-[36px] border-slate-700 bg-indigo-700 shadow-inner border-r border-slate-700">Keterangan Pengganti</th>
    </tr>

    {{-- BARIS 2: Detil Kolom --}}
    <tr class="bg-slate-700 text-white text-[8px] font-bold uppercase text-center border-b border-slate-600 shadow-xl h-[36px]">
        <th class="px-1 py-0 h-[36px] border-r border-slate-600 bg-amber-500 w-11 shadow-sm">S1</th>
        <th class="px-1 py-0 h-[36px] border-r border-slate-600 bg-amber-500 w-11 shadow-sm">S2</th>
        <th class="px-1 py-0 h-[36px] border-r-2 border-slate-600 bg-amber-500 w-12 shadow-sm">Cmpr</th>
        
        <th class="px-1 py-0 h-[36px] border-r border-slate-600 bg-slate-800 text-amber-300 w-10">S1</th>
        <th class="px-1 py-0 h-[36px] border-r border-slate-600 bg-slate-800 text-amber-300 w-10">S2</th>
        <th class="px-1 py-0 h-[36px] border-r-2 border-slate-600 bg-slate-800 text-amber-300 w-12 text-blue-300">Cmpr</th>
        
        <th class="px-1 py-0 h-[36px] border-r border-slate-600 bg-indigo-600 w-12 leading-tight">Pack</th>
        <th class="px-1 py-0 h-[36px] border-r border-slate-600 bg-indigo-800 w-18">Seri PGT</th>
        <th class="px-1 py-0 h-[36px] bg-indigo-600 w-12 leading-tight border-r border-slate-600 border-b-2 border-indigo-400">Blyt</th>
    </tr>
</thead>
