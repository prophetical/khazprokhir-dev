@for($pack = $start; $pack <= $end; $pack++)
    @php
        $packData = $gridData[$pack] ?? [
            'seri_pengganti' => '',
            'total_rusak_seri_1' => '',
            'total_rusak_seri_2' => '',
            'total_rusak_campuran' => '',
            'slots' => [
                1 => ['rusak_seri_1' => '', 'rusak_seri_2' => '', 'rusak_campuran' => '', 'nomor_pack_pengganti' => '', 'nomor_bilyet_pengganti' => ''],
                2 => ['rusak_seri_1' => '', 'rusak_seri_2' => '', 'rusak_campuran' => '', 'nomor_pack_pengganti' => '', 'nomor_bilyet_pengganti' => ''],
                3 => ['rusak_seri_1' => '', 'rusak_seri_2' => '', 'rusak_campuran' => '', 'nomor_pack_pengganti' => '', 'nomor_bilyet_pengganti' => ''],
                4 => ['rusak_seri_1' => '', 'rusak_seri_2' => '', 'rusak_campuran' => '', 'nomor_pack_pengganti' => '', 'nomor_bilyet_pengganti' => ''],
            ]
        ];
        $isEven = $pack % 2 === 0;
        $rowBg = $isEven ? 'bg-slate-50/60 dark:bg-slate-700/30' : 'bg-white dark:bg-slate-800';
    @endphp

    {{-- SLOT 1 --}}
    <tr class="pack-row-first {{ $rowBg }} hover:bg-indigo-50/40 dark:hover:bg-indigo-900/10 transition-colors" data-pack="{{ $pack }}">
        {{-- NO PACK --}}
        <td rowspan="4" class="p-0 text-center font-black text-white border border-gray-200 dark:border-slate-600 w-8 align-middle bg-indigo-700 dark:bg-indigo-800">
            <input type="hidden" name="packs[{{ $pack }}][nomor_pack]" value="{{ $pack }}">
            <span class="text-[11px]">{{ $pack }}</span>
        </td>

        {{-- SLOT --}}
        <td class="p-0 text-center border-x border-gray-100 dark:border-slate-700/50 align-middle">
            <span class="inline-flex items-center justify-center w-4 h-4 rounded-md bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 font-black text-[9px]">1</span>
        </td>

        {{-- INSCHIET INPUTS --}}
        <td class="p-1 border-x border-gray-100 dark:border-slate-700/50 align-middle">
            <input type="text" inputmode="numeric" oninput="onlyDigits(this); calcTotal({{ $pack }}, 'seri1')"
                name="packs[{{ $pack }}][slots][1][rusak_seri_1]"
                value="{{ $packData['slots'][1]['rusak_seri_1'] }}"
                class="khazai-input calc-seri1-pack-{{ $pack }} w-full h-6 px-1 text-center text-[10px] font-bold bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-md"
                data-pack="{{ $pack }}" data-slot="1" data-col="seri1" autocomplete="off">
            <input type="hidden" name="packs[{{ $pack }}][slots][1][slot]" value="1">
        </td>
        <td class="p-1 border-x border-gray-100 dark:border-slate-700/50 align-middle">
            <input type="text" inputmode="numeric" oninput="onlyDigits(this); calcTotal({{ $pack }}, 'seri2')"
                name="packs[{{ $pack }}][slots][1][rusak_seri_2]"
                value="{{ $packData['slots'][1]['rusak_seri_2'] }}"
                class="khazai-input calc-seri2-pack-{{ $pack }} w-full h-6 px-1 text-center text-[10px] font-bold bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-md"
                data-pack="{{ $pack }}" data-slot="1" data-col="seri2" autocomplete="off">
        </td>
        <td class="p-1 border-x border-gray-100 dark:border-slate-700/50 align-middle">
            <input type="text" inputmode="numeric" oninput="onlyDigits(this); calcTotal({{ $pack }}, 'campuran')"
                name="packs[{{ $pack }}][slots][1][rusak_campuran]"
                value="{{ $packData['slots'][1]['rusak_campuran'] }}"
                class="khazai-input calc-campuran-pack-{{ $pack }} w-full h-6 px-1 text-center text-[10px] font-bold bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-md"
                data-pack="{{ $pack }}" data-slot="1" data-col="campuran" autocomplete="off">
        </td>

        {{-- TOTALS (Rowspan 4) --}}
        <td rowspan="4" class="p-0 border-x border-gray-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/40 align-middle">
            <input type="text" readonly id="total-seri1-pack-{{ $pack }}" name="packs[{{ $pack }}][total_rusak_seri_1]"
                value="{{ $packData['total_rusak_seri_1'] }}"
                class="w-full text-center bg-transparent border-none font-bold text-[11px] p-0 text-gray-300 dark:text-slate-700 pointer-events-none" tabindex="-1">
        </td>
        <td rowspan="4" class="p-0 border-x border-gray-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/40 align-middle">
            <input type="text" readonly id="total-seri2-pack-{{ $pack }}" name="packs[{{ $pack }}][total_rusak_seri_2]"
                value="{{ $packData['total_rusak_seri_2'] }}"
                class="w-full text-center bg-transparent border-none font-bold text-[11px] p-0 text-gray-300 dark:text-slate-700 pointer-events-none" tabindex="-1">
        </td>
        <td rowspan="4" class="p-0 border-x border-gray-200 dark:border-slate-600 bg-indigo-50/30 dark:bg-indigo-900/10 align-middle">
            <input type="text" readonly id="total-campuran-pack-{{ $pack }}" name="packs[{{ $pack }}][total_rusak_campuran]"
                value="{{ $packData['total_rusak_campuran'] }}"
                class="w-full text-center bg-transparent border-none font-bold text-[11px] p-0 text-gray-300 dark:text-slate-700 pointer-events-none" tabindex="-1">
        </td>

        {{-- REPLACEMENT INPUTS --}}
        <td class="p-1 border-x border-gray-100 dark:border-slate-700/50 align-middle">
            <input type="text" inputmode="numeric" oninput="onlyDigits(this)"
                name="packs[{{ $pack }}][slots][1][nomor_pack_pengganti]"
                value="{{ $packData['slots'][1]['nomor_pack_pengganti'] }}"
                class="khazai-input w-full h-6 px-1 text-center text-[10px] font-bold bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-md"
                data-pack="{{ $pack }}" data-slot="1" data-col="pgt_pack" autocomplete="off">
        </td>

        <td rowspan="4" class="p-1 border-x border-gray-200 dark:border-slate-600 align-middle bg-indigo-50/20 dark:bg-indigo-900/5">
            <input type="text" maxlength="6" oninput="maskSeri(this)"
                name="packs[{{ $pack }}][seri_pengganti]"
                value="{{ $packData['seri_pengganti'] }}"
                class="w-full h-6 px-1 text-center text-[10px] font-black uppercase tracking-tighter bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-md focus:border-indigo-400 focus:ring-1 focus:ring-indigo-200"
                autocomplete="off">
        </td>

        <td class="p-1 border-x border-gray-100 dark:border-slate-700/50 align-middle">
            <input type="text" inputmode="numeric" oninput="onlyDigits(this)"
                name="packs[{{ $pack }}][slots][1][nomor_bilyet_pengganti]"
                value="{{ $packData['slots'][1]['nomor_bilyet_pengganti'] }}"
                class="khazai-input w-full h-6 px-1 text-center text-[10px] font-bold bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-md"
                data-pack="{{ $pack }}" data-slot="1" data-col="pgt_bilyet" autocomplete="off">
        </td>
    </tr>

    {{-- SLOTS 2-4 --}}
    @for($slot = 2; $slot <= 4; $slot++)
        <tr class="{{ $rowBg }} hover:bg-indigo-50/40 dark:hover:bg-indigo-900/10 transition-colors" data-pack="{{ $pack }}">
            <td class="p-0 text-center border-x border-gray-100 dark:border-slate-700/50 align-middle">
                <span class="inline-flex items-center justify-center w-4 h-4 rounded-md bg-gray-100 dark:bg-slate-700 text-gray-400 dark:text-slate-500 font-black text-[9px]">{{ $slot }}</span>
            </td>

            <td class="p-1 border-x border-gray-100 dark:border-slate-700/50 align-middle">
                <input type="text" inputmode="numeric" oninput="onlyDigits(this); calcTotal({{ $pack }}, 'seri1')"
                    name="packs[{{ $pack }}][slots][{{ $slot }}][rusak_seri_1]"
                    value="{{ $packData['slots'][$slot]['rusak_seri_1'] }}"
                    class="khazai-input calc-seri1-pack-{{ $pack }} w-full h-6 px-1 text-center text-[10px] font-bold bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-md"
                    data-pack="{{ $pack }}" data-slot="{{ $slot }}" data-col="seri1" autocomplete="off">
                <input type="hidden" name="packs[{{ $pack }}][slots][{{ $slot }}][slot]" value="{{ $slot }}">
            </td>
            <td class="p-1 border-x border-gray-100 dark:border-slate-700/50 align-middle">
                <input type="text" inputmode="numeric" oninput="onlyDigits(this); calcTotal({{ $pack }}, 'seri2')"
                    name="packs[{{ $pack }}][slots][{{ $slot }}][rusak_seri_2]"
                    value="{{ $packData['slots'][$slot]['rusak_seri_2'] }}"
                    class="khazai-input calc-seri2-pack-{{ $pack }} w-full h-6 px-1 text-center text-[10px] font-bold bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-md"
                    data-pack="{{ $pack }}" data-slot="{{ $slot }}" data-col="seri2" autocomplete="off">
            </td>
            <td class="p-1 border-x border-gray-100 dark:border-slate-700/50 align-middle">
                <input type="text" inputmode="numeric" oninput="onlyDigits(this); calcTotal({{ $pack }}, 'campuran')"
                    name="packs[{{ $pack }}][slots][{{ $slot }}][rusak_campuran]"
                    value="{{ $packData['slots'][$slot]['rusak_campuran'] }}"
                    class="khazai-input calc-campuran-pack-{{ $pack }} w-full h-6 px-1 text-center text-[10px] font-bold bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-md"
                    data-pack="{{ $pack }}" data-slot="{{ $slot }}" data-col="campuran" autocomplete="off">
            </td>

            <td class="p-1 border-x border-gray-100 dark:border-slate-700/50 align-middle">
                <input type="text" inputmode="numeric" oninput="onlyDigits(this)"
                    name="packs[{{ $pack }}][slots][{{ $slot }}][nomor_pack_pengganti]"
                    value="{{ $packData['slots'][$slot]['nomor_pack_pengganti'] }}"
                    class="khazai-input w-full h-6 px-1 text-center text-[10px] font-bold bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-md"
                    data-pack="{{ $pack }}" data-slot="{{ $slot }}" data-col="pgt_pack" autocomplete="off">
            </td>
            <td class="p-1 border-x border-gray-100 dark:border-slate-700/50 align-middle">
                <input type="text" inputmode="numeric" oninput="onlyDigits(this)"
                    name="packs[{{ $pack }}][slots][{{ $slot }}][nomor_bilyet_pengganti]"
                    value="{{ $packData['slots'][$slot]['nomor_bilyet_pengganti'] }}"
                    class="khazai-input w-full h-6 px-1 text-center text-[10px] font-bold bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-md"
                    data-pack="{{ $pack }}" data-slot="{{ $slot }}" data-col="pgt_bilyet" autocomplete="off">
            </td>
        </tr>
    @endfor
@endfor