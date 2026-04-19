{{--
Partial: tbody Cutpack — FLAT (1 baris per pack, tanpa slot).
--}}
@for($pack = $start; $pack <= $end; $pack++)
    @php
        $packData = $gridData[$pack] ?? [
            'seri_pengganti'         => '',
            'total_rusak_seri_1'     => 0,
            'total_rusak_seri_2'     => 0,
            'total_rusak_campuran'   => 0,
            'nomor_pack_pengganti'   => '',
            'nomor_bilyet_pengganti' => '',
        ];
        $isEven = $pack % 2 === 0;
        $rowBg  = $isEven ? 'bg-slate-50/60 dark:bg-slate-700/30' : 'bg-white dark:bg-slate-800';

        $s1   = (int) ($packData['total_rusak_seri_1'] ?? 0);
        $s2   = (int) ($packData['total_rusak_seri_2'] ?? 0);
        $camp = (int) ($packData['total_rusak_campuran'] ?? 0);
    @endphp

    <tr class="pack-row-first {{ $rowBg }} hover:bg-indigo-50/40 dark:hover:bg-indigo-900/10 transition-colors" data-pack="{{ $pack }}">
        {{-- NO PACK --}}
        <td class="p-0 text-center font-black text-white border border-gray-200 dark:border-slate-600 w-8 align-middle bg-indigo-700 dark:bg-indigo-800">
            <input type="hidden" name="packs[{{ $pack - 1 }}][nomor_pack]" value="{{ $pack }}">
            <span class="text-[11px]">{{ $pack }}</span>
        </td>

        {{-- BILYET RUSAK (READ-ONLY dari MappingAggregatorService) --}}
        <td class="px-1 py-1 border border-gray-100 dark:border-slate-700/50 align-middle text-center bg-slate-50 dark:bg-slate-900/40">
            @if($s1 > 0)
                <span class="badg-s1 inline-flex items-center justify-center min-w-[24px] px-1 py-0.5 rounded
                    bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400 font-bold text-[10px]">
                    {{ $s1 }}
                </span>
            @else <span class="text-gray-300 dark:text-slate-600 text-[10px]">–</span> @endif
        </td>
        <td class="px-1 py-1 border border-gray-100 dark:border-slate-700/50 align-middle text-center bg-slate-50 dark:bg-slate-900/40">
            @if($s2 > 0)
                <span class="badg-s2 inline-flex items-center justify-center min-w-[24px] px-1 py-0.5 rounded
                    bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400 font-bold text-[10px]">
                    {{ $s2 }}
                </span>
            @else <span class="text-gray-300 dark:text-slate-600 text-[10px]">–</span> @endif
        </td>
        <td class="px-1 py-1 border border-gray-100 dark:border-slate-700/50 align-middle text-center bg-indigo-50/30 dark:bg-indigo-900/10">
            @if($camp > 0)
                <span class="badg-c inline-flex items-center justify-center min-w-[24px] px-1 py-0.5 rounded
                    bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-400 font-bold text-[10px]">
                    {{ $camp }}
                </span>
            @else <span class="text-gray-300 dark:text-slate-600 text-[10px]">–</span> @endif
        </td>

        {{-- KETERANGAN PENGGANTI (EDITABLE) --}}
        <td class="p-1 border border-gray-100 dark:border-slate-700/50 align-middle" style="display:none;">
            <input type="text" inputmode="numeric" oninput="onlyDigits(this)"
                name="packs[{{ $pack - 1 }}][nomor_pack_pengganti]"
                value="{{ $packData['nomor_pack_pengganti'] }}"
                class="w-full h-6 px-1 text-center text-[10px] font-bold bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-md"
                autocomplete="off">
        </td>
        <td class="p-1 border border-gray-200 dark:border-slate-600 align-middle bg-indigo-50/20 dark:bg-indigo-900/5" style="display:none;">
            <input type="text" maxlength="6" oninput="formatSeriPengganti(this)" onkeydown="handleSeriKeydown(event, this)"
                name="packs[{{ $pack - 1 }}][seri_pengganti]" data-pack="{{ $pack }}"
                value="{{ $packData['seri_pengganti'] }}"
                class="seri-pengganti-input w-full h-6 px-1 text-center text-[10px] font-black uppercase tracking-tighter bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-md focus:border-indigo-400 focus:ring-1 focus:ring-indigo-200"
                autocomplete="off">
        </td>
        <td class="p-1 border border-gray-100 dark:border-slate-700/50 align-middle" style="display:none;">
            <input type="text" inputmode="numeric" oninput="onlyDigits(this)"
                name="packs[{{ $pack - 1 }}][nomor_bilyet_pengganti]"
                value="{{ $packData['nomor_bilyet_pengganti'] }}"
                class="w-full h-6 px-1 text-center text-[10px] font-bold bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-md"
                autocomplete="off">
        </td>
    </tr>
@endfor