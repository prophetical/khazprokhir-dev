{{-- Partial: thead untuk Cutpack Input (Khazai Style) --}}
<thead class="sticky top-[27px] z-20">
    {{-- Row 1: Group Headers --}}
    <tr class="bg-amber-500 text-white text-[8px] font-black uppercase tracking-widest text-center">
        <th rowspan="2" class="px-2 border border-slate-700/50 w-8 shadow-sm">#</th>
        <th rowspan="2" class="px-1 border border-slate-700/50 w-6 shadow-sm">Slot</th>
        
        {{-- Inschiet Group --}}
        <th colspan="3" class="px-2 py-1.5 border border-slate-700/50 bg-amber-500">Inschiet (Lembar)</th>
        
        {{-- Total Group --}}
        <th colspan="3" class="px-2 py-1.5 border border-slate-700/50 bg-slate-700">Total Per Pack</th>
        
        {{-- Pengganti Group --}}
        <th colspan="3" class="px-2 py-1.5 border border-slate-700/50 bg-indigo-600">Terpakai (Pengganti)</th>
    </tr>

    {{-- Row 2: Detailed Headers --}}
    <tr class="bg-amber-500 text-white text-[8px] font-black uppercase tracking-[0.05em] text-center">
        {{-- Inschiet Sub --}}
        <th class="px-1 py-1 border border-slate-700/50 min-w-[38px]">Seri 1</th>
        <th class="px-1 py-1 border border-slate-700/50 min-w-[38px]">Seri 2</th>
        <th class="px-1 py-1 border border-slate-700/50 min-w-[42px]">Camp</th>
        
        {{-- Total Sub --}}
        <th class="px-1 py-1 border border-slate-700/50 min-w-[40px] bg-slate-800 text-amber-400">Seri 1</th>
        <th class="px-1 py-1 border border-slate-700/50 min-w-[40px] bg-slate-800 text-amber-400">Seri 2</th>
        <th class="px-1 py-1 border border-slate-700/50 min-w-[40px] bg-slate-800 text-indigo-300">Camp</th>
        
        {{-- Pengganti Sub --}}
        <th class="px-1 py-1 border border-slate-700/50 min-w-[40px] bg-indigo-800">Pack</th>
        <th class="px-2 py-1 border border-slate-700/50 min-w-[75px] bg-indigo-950 text-indigo-300">Seri</th>
        <th class="px-1 py-1 border border-slate-700/50 min-w-[45px] bg-indigo-800">Bilyet</th>
    </tr>
</thead>