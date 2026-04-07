@for ($p = $start; $p <= $end; $p++)
    {{-- BARIS 1 (SLOT 1) ── --}}
    @for ($s = 1; $s <= 4; $s++)
        <tr class="hover:bg-amber-50/50 dark:hover:bg-slate-700/50 group/row {{ ($p % 2 == 0) ? 'bg-slate-50/50 dark:bg-slate-800/20' : 'bg-white dark:bg-slate-800/60' }} transition-colors">
            
            {{-- HIDDEN INPUTS --}}
            @if ($s === 1)
                <input type="hidden" name="packs[{{$p}}][nomor_pack]" value="{{$p}}">
            @endif
            <input type="hidden" name="packs[{{$p}}][slots][{{$s}}][slot]" value="{{$s}}">
            
            {{-- NO PACK (STICKY LEFT IF NEEDED - BUT CURRENTLY NORMAL) --}}
            @if ($s === 1)
                <td rowspan="4" class="px-1 py-0 text-center border-r border-b border-slate-300 dark:border-slate-600 bg-slate-800 text-white font-black text-[12px] align-middle shadow-[2px_0_4px_rgba(0,0,0,0.1)]">
                    {{ $p }}
                </td>
            @endif
            
            {{-- INSCHIET (S1, S2, CMPR) --}}
            <td class="p-0 border border-gray-100 dark:border-slate-700">
                <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, ''); calcTotal({{$p}}, 'seri1')" 
                    name="packs[{{$p}}][slots][{{$s}}][rusak_seri_1]" 
                    value="{{ $gridData[$p]['slots'][$s]['rusak_seri_1'] ?? '' }}" 
                    class="calc-seri1-pack-{{$p}} w-full h-6 px-1 text-center bg-transparent border-none focus:ring-1 focus:ring-amber-500 rounded font-bold text-gray-900 dark:text-white placeholder-gray-300 dark:placeholder-slate-600 text-[10px]"
                    placeholder="-" autocomplete="off">
            </td>
            <td class="p-0 border border-gray-100 dark:border-slate-700">
                <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, ''); calcTotal({{$p}}, 'seri2')" 
                    name="packs[{{$p}}][slots][{{$s}}][rusak_seri_2]" 
                    value="{{ $gridData[$p]['slots'][$s]['rusak_seri_2'] ?? '' }}" 
                    class="calc-seri2-pack-{{$p}} w-full h-6 px-1 text-center bg-transparent border-none focus:ring-1 focus:ring-amber-500 rounded font-bold text-gray-900 dark:text-white placeholder-gray-300 dark:placeholder-slate-600 text-[10px]"
                    placeholder="-" autocomplete="off">
            </td>
            <td class="p-0 border-r-2 border border-gray-100 dark:border-slate-700">
                <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, ''); calcTotal({{$p}}, 'campuran')" 
                    name="packs[{{$p}}][slots][{{$s}}][rusak_campuran]" 
                    value="{{ $gridData[$p]['slots'][$s]['rusak_campuran'] ?? '' }}" 
                    class="calc-campuran-pack-{{$p}} w-full h-6 px-1 text-center bg-transparent border-none focus:ring-1 focus:ring-amber-500 rounded font-bold text-gray-900 dark:text-white placeholder-gray-300 dark:placeholder-slate-600 text-[10px]"
                    placeholder="-" autocomplete="off">
            </td>
            
            {{-- TOTAL RUSAK (rowSpan 4 per pack) --}}
            @if ($s === 1)
                <td rowspan="4" class="p-0 border-r border-b border-slate-300 dark:border-slate-700 bg-amber-50/30 dark:bg-amber-900/10 align-middle">
                    <input type="text" readonly id="total-seri1-pack-{{$p}}" 
                        name="packs[{{$p}}][total_rusak_seri_1]" 
                        value="{{ $gridData[$p]['total_rusak_seri_1'] ?? '' }}" 
                        class="w-full text-center bg-transparent border-none text-amber-700 dark:text-amber-500 font-extrabold text-[10px] p-0 pointer-events-none"
                        placeholder="0" tabindex="-1">
                </td>
                <td rowspan="4" class="p-0 border-r border-b border-slate-300 dark:border-slate-700 bg-amber-50/30 dark:bg-amber-900/10 align-middle">
                    <input type="text" readonly id="total-seri2-pack-{{$p}}" 
                        name="packs[{{$p}}][total_rusak_seri_2]" 
                        value="{{ $gridData[$p]['total_rusak_seri_2'] ?? '' }}" 
                        class="w-full text-center bg-transparent border-none text-amber-700 dark:text-amber-500 font-extrabold text-[10px] p-0 pointer-events-none"
                        placeholder="0" tabindex="-1">
                </td>
                <td rowspan="4" class="p-0 border-r-2 border-b border-slate-300 dark:border-slate-700 bg-amber-50/30 dark:bg-amber-900/10 align-middle">
                    <input type="text" readonly id="total-campuran-pack-{{$p}}" 
                        name="packs[{{$p}}][total_rusak_campuran]" 
                        value="{{ $gridData[$p]['total_rusak_campuran'] ?? '' }}" 
                        class="w-full text-center bg-transparent border-none text-amber-700 dark:text-amber-500 font-extrabold text-[10px] p-0 pointer-events-none"
                        placeholder="0" tabindex="-1">
                </td>
            @endif

            {{-- KETERANGAN (Pack, Seri, Blyt) --}}
            <td class="p-0 border-r border-b border-gray-100 dark:border-slate-700 bg-indigo-50/20 dark:bg-indigo-900/5">
                <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                    name="packs[{{$p}}][slots][{{$s}}][nomor_pack_pengganti]" 
                    value="{{ $gridData[$p]['slots'][$s]['nomor_pack_pengganti'] ?? '' }}" 
                    class="w-full h-6 px-1 text-center bg-transparent border-none ring-1 ring-transparent focus:ring-indigo-500 rounded font-bold text-indigo-900 dark:text-indigo-300 placeholder-indigo-300/50 dark:placeholder-indigo-800/50 text-[10px]"
                    placeholder="-" autocomplete="off">
            </td>
            
            @if ($s === 1)
                <td rowspan="4" class="p-0.5 border-r border-b border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 align-middle">
                    <input type="text" oninput="maskSeri(this)"
                        name="packs[{{$p}}][seri_pengganti]" 
                        value="{{ $gridData[$p]['seri_pengganti'] ?? '' }}" 
                        class="w-full h-11 px-1 text-center bg-transparent border-none ring-1 ring-indigo-200 dark:ring-indigo-800 focus:ring-2 focus:ring-indigo-500 rounded font-black text-[10px] tracking-tighter uppercase text-indigo-900 dark:text-indigo-300 placeholder-indigo-200 shadow-sm transition-all"
                        placeholder="XX-XX9" autocomplete="off" maxlength="6">
                </td>
            @endif

            <td class="p-0 border-b border-gray-100 dark:border-slate-700 bg-indigo-50/20 dark:bg-indigo-900/5">
                <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                    name="packs[{{$p}}][slots][{{$s}}][nomor_bilyet_pengganti]" 
                    value="{{ $gridData[$p]['slots'][$s]['nomor_bilyet_pengganti'] ?? '' }}" 
                    class="w-full h-6 px-1 text-center bg-transparent border-none ring-1 ring-transparent focus:ring-indigo-500 rounded font-bold text-indigo-900 dark:text-indigo-300 placeholder-indigo-300/50 dark:placeholder-indigo-800/50 text-[10px]"
                    placeholder="-" autocomplete="off">
            </td>
        </tr>
    @endfor
@endfor
