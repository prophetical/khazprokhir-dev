{{--
Partial: tbody Khazai — FLAT GRID (1 baris per pack, tanpa slot).
Kolom 'jumlah_rusak_vell' bersifat READ-ONLY (diisi otomatis oleh MappingAggregatorService).
Kolom 'seri_pengganti' dapat diisi manual.
--}}
@for($pack = $start; $pack <= $end; $pack++)
    @php
        $packData = $gridData[$pack] ?? [
            'seri_pengganti'    => '',
            'jumlah_rusak_vell' => 0,
        ];
        $isEven    = $pack % 2 === 0;
        $rowBg     = $isEven ? 'bg-slate-50/60 dark:bg-slate-700/30' : 'bg-white dark:bg-slate-800';
        $vell      = (int) ($packData['jumlah_rusak_vell'] ?? 0);
        $hasRusak  = $vell > 0;
    @endphp

    <tr class="{{ $rowBg }} hover:bg-violet-50/40 dark:hover:bg-violet-900/10 transition-colors pack-row-first"
        data-pack="{{ $pack }}">

        {{-- NO PACK --}}
        <td class="px-1 py-0 text-center font-black text-white border border-gray-200 dark:border-slate-600 w-8 align-middle bg-violet-700 dark:bg-violet-800">
            <input type="hidden" name="packs[{{ $pack - 1 }}][nomor_pack]" value="{{ $pack }}">
            <span class="text-[11px]">{{ $pack }}</span>
        </td>

        {{-- JUMLAH RUSAK VELL (Read-Only — dari MappingAggregatorService) --}}
        <td class="px-2 py-1 text-center border-x border-gray-100 dark:border-slate-700/50 align-middle">
            @if($hasRusak)
                <span class="inline-flex items-center justify-center min-w-[28px] px-1.5 py-0.5
                    rounded-md bg-violet-100 dark:bg-violet-900/40
                    text-violet-700 dark:text-violet-300 text-[11px] font-black">
                    {{ $vell }}
                </span>
            @else
                <span class="text-gray-300 dark:text-slate-600 text-[10px] font-medium">–</span>
            @endif
        </td>

        {{-- SERI PENGGANTI (Editable) --}}
        <td class="px-1 py-1 border-x border-gray-100 dark:border-slate-700/50 align-middle">
            <input type="text" maxlength="6"
                name="packs[{{ $pack - 1 }}][seri_pengganti]"
                value="{{ $packData['seri_pengganti'] }}"
                class="seri-pengganti-input w-full h-6 px-1 text-center text-[10px] font-black uppercase tracking-tighter
                    bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600
                    rounded-md focus:border-purple-400 focus:ring-1 focus:ring-purple-200"
                oninput="formatSeriPengganti(this)"
                onkeydown="handleSeriKeydown(event, this)"
                data-pack="{{ $pack }}">
        </td>
    </tr>
@endfor
