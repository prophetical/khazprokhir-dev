@for($pack = $start; $pack <= $end; $pack++)
    @php
        $packData = $gridData[$pack] ?? [
            'seri_pengganti' => '',
            'slots' => [
                1 => ['jumlah_rusak_vell'=>'', 'nomor_pack_pengganti'=>'', 'nomor_vell_pengganti'=>''],
                2 => ['jumlah_rusak_vell'=>'', 'nomor_pack_pengganti'=>'', 'nomor_vell_pengganti'=>''],
                3 => ['jumlah_rusak_vell'=>'', 'nomor_pack_pengganti'=>'', 'nomor_vell_pengganti'=>''],
                4 => ['jumlah_rusak_vell'=>'', 'nomor_pack_pengganti'=>'', 'nomor_vell_pengganti'=>'']
            ]
        ];
        $isEven = $pack % 2 === 0;
        $rowBg = $isEven ? 'bg-slate-50/60 dark:bg-slate-700/30' : 'bg-white dark:bg-slate-800';
    @endphp

    {{-- SLOT 1 --}}
    <tr class="pack-row-first {{ $rowBg }} hover:bg-violet-50/40 dark:hover:bg-violet-900/10 transition-colors" data-pack="{{ $pack }}">
        <td rowspan="4" class="px-1 py-0 text-center font-black text-white border border-gray-200 dark:border-slate-600 w-8 align-middle bg-violet-700 dark:bg-violet-800">
            <input type="hidden" name="packs[{{ $pack - 1 }}][nomor_pack]" value="{{ $pack }}">
            <span class="text-[11px]">{{ $pack }}</span>
        </td>
        <td class="px-1 py-1 text-center border-x border-gray-100 dark:border-slate-700/50 align-middle">
            <span class="inline-flex items-center justify-center w-4 h-4 rounded-md bg-violet-100 dark:bg-violet-900/40 text-violet-600 dark:text-violet-400 font-black text-[9px]">1</span>
        </td>
        <td class="px-1 py-1 border-x border-gray-100 dark:border-slate-700/50 align-middle">
            <input type="text" inputmode="numeric"
                name="packs[{{ $pack - 1 }}][slots][0][jumlah_rusak_vell]"
                value="{{ $packData['slots'][1]['jumlah_rusak_vell'] }}"
                class="khazai-input rusak-input w-full h-6 px-1 text-center text-[10px] font-bold bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-md focus:border-violet-400 focus:ring-1 focus:ring-violet-200"
                data-pack="{{ $pack }}" data-slot="1" data-col="rusak"
                oninput="this.value = this.value.replace(/[^0-9]/g, ''); onlyDigits(this); calcPack({{ $pack }})">
            <input type="hidden" name="packs[{{ $pack - 1 }}][slots][0][slot]" value="1">
        </td>
        <td rowspan="4" class="px-1 py-0 text-center border-x border-gray-200 dark:border-slate-600 bg-violet-50 dark:bg-violet-900/10 align-middle">
            <div id="jumlah-{{ $pack }}" class="text-[13px] font-bold text-gray-300 dark:text-slate-600">–</div>
        </td>
        <td class="px-1 py-1 border-x border-gray-100 dark:border-slate-700/50 align-middle">
            <input type="text" inputmode="numeric"
                name="packs[{{ $pack - 1 }}][slots][0][nomor_pack_pengganti]"
                value="{{ $packData['slots'][1]['nomor_pack_pengganti'] }}"
                class="khazai-input w-full h-6 px-1 text-center text-[10px] font-bold bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-md focus:border-purple-400 focus:ring-1 focus:ring-purple-200"
                data-pack="{{ $pack }}" data-slot="1" data-col="nopack"
                oninput="this.value = this.value.replace(/[^0-9]/g, ''); onlyDigits(this)">
        </td>
        <td class="px-1 py-1 border-x border-gray-100 dark:border-slate-700/50 align-middle">
            <input type="text" inputmode="numeric"
                name="packs[{{ $pack - 1 }}][slots][0][nomor_vell_pengganti]"
                value="{{ $packData['slots'][1]['nomor_vell_pengganti'] }}"
                class="khazai-input w-full h-6 px-1 text-center text-[10px] font-bold bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-md focus:border-purple-400 focus:ring-1 focus:ring-purple-200"
                data-pack="{{ $pack }}" data-slot="1" data-col="novell"
                oninput="this.value = this.value.replace(/[^0-9]/g, ''); onlyDigits(this)">
        </td>
        <td rowspan="4" class="px-1 py-0 border-x border-gray-200 dark:border-slate-600 align-middle bg-purple-50/30 dark:bg-purple-900/5">
            <input type="text" maxlength="6"
                name="packs[{{ $pack - 1 }}][seri_pengganti]"
                value="{{ $packData['seri_pengganti'] }}"
                class="seri-pengganti-input w-full h-6 px-1 text-center text-[10px] font-black uppercase tracking-tighter bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-md focus:border-purple-400 focus:ring-1 focus:ring-purple-200"
                oninput="formatSeriPengganti(this)"
                onkeydown="handleSeriKeydown(event, this)">
        </td>
    </tr>

    @for($slot = 2; $slot <= 4; $slot++)
        <tr class="{{ $rowBg }} hover:bg-violet-50/40 dark:hover:bg-violet-900/10 transition-colors" data-pack="{{ $pack }}">
            <td class="px-1 py-1 text-center border-x border-gray-100 dark:border-slate-700/50 align-middle">
                <span class="inline-flex items-center justify-center w-4 h-4 rounded-md bg-gray-100 dark:bg-slate-700 text-gray-400 dark:text-slate-500 font-black text-[9px]">{{ $slot }}</span>
            </td>
            <td class="px-1 py-1 border-x border-gray-100 dark:border-slate-700/50 align-middle">
                <input type="text" inputmode="numeric"
                    name="packs[{{ $pack - 1 }}][slots][{{ $slot - 1 }}][jumlah_rusak_vell]"
                    value="{{ $packData['slots'][$slot]['jumlah_rusak_vell'] }}"
                    class="khazai-input rusak-input w-full h-6 px-1 text-center text-[10px] font-bold bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-md focus:border-violet-400 focus:ring-1 focus:ring-violet-200"
                    data-pack="{{ $pack }}" data-slot="{{ $slot }}" data-col="rusak"
                    oninput="this.value = this.value.replace(/[^0-9]/g, ''); onlyDigits(this); calcPack({{ $pack }})">
                <input type="hidden" name="packs[{{ $pack - 1 }}][slots][{{ $slot - 1 }}][slot]" value="{{ $slot }}">
            </td>
            <td class="px-1 py-1 border-x border-gray-100 dark:border-slate-700/50 align-middle">
                <input type="text" inputmode="numeric"
                    name="packs[{{ $pack - 1 }}][slots][{{ $slot - 1 }}][nomor_pack_pengganti]"
                    value="{{ $packData['slots'][$slot]['nomor_pack_pengganti'] }}"
                    class="khazai-input w-full h-6 px-1 text-center text-[10px] font-bold bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-md focus:border-purple-400 focus:ring-1 focus:ring-purple-200"
                    data-pack="{{ $pack }}" data-slot="{{ $slot }}" data-col="nopack"
                    oninput="this.value = this.value.replace(/[^0-9]/g, ''); onlyDigits(this)">
            </td>
            <td class="px-1 py-1 border-x border-gray-100 dark:border-slate-700/50 align-middle">
                <input type="text" inputmode="numeric"
                    name="packs[{{ $pack - 1 }}][slots][{{ $slot - 1 }}][nomor_vell_pengganti]"
                    value="{{ $packData['slots'][$slot]['nomor_vell_pengganti'] }}"
                    class="khazai-input w-full h-6 px-1 text-center text-[10px] font-bold bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-md focus:border-purple-400 focus:ring-1 focus:ring-purple-200"
                    data-pack="{{ $pack }}" data-slot="{{ $slot }}" data-col="novell"
                    oninput="this.value = this.value.replace(/[^0-9]/g, ''); onlyDigits(this)">
            </td>
        </tr>
    @endfor
@endfor
