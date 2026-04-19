{{-- Partial: tbody Rikyet — FLAT (1 baris per pack, tanpa slot). --}}
@for($pack = $start; $pack <= $end; $pack++)
    @php
        $packData = $gridData[$pack] ?? [
            'seri_pengganti'       => '',
            'total_rusak_seri_1'   => 0,
            'total_rusak_seri_2'   => 0,
            'total_rusak_campuran' => 0,
        ];
        $isEven = $pack % 2 === 0;
        $rowBgStyle = $isEven ? 'background-color:#f8fafc;' : 'background-color:#ffffff;';
        $rowBgClass = $isEven ? 'dark:bg-slate-700/30' : 'dark:bg-slate-800';

        $s1   = (int) ($packData['total_rusak_seri_1'] ?? 0);
        $s2   = (int) ($packData['total_rusak_seri_2'] ?? 0);
        $camp = (int) ($packData['total_rusak_campuran'] ?? 0);
    @endphp

    <tr class="{{ $rowBgClass }}" style="{{ $rowBgStyle }} border-top:2.5px solid #0d9488;" data-pack="{{ $pack }}">
        {{-- NO PACK --}}
        <td style="width:32px; text-align:center; vertical-align:middle; font-size:10px; font-weight:900; color:#ffffff; background-color:#0d9488; border:1px solid rgba(255,255,255,0.3); padding:2px 1px;">
            <input type="hidden" name="packs[{{ $pack - 1 }}][nomor_pack]" value="{{ $pack }}">
            {{ $pack }}
        </td>

        {{-- BILYET/BROOD RUSAK (READ-ONLY dari MappingAggregatorService) --}}
        <td style="padding:4px 1px; text-align:center; vertical-align:middle; border:1px solid #e2e8f0; background-color:#f0fdfa;">
            @if($s1 > 0)
                <span class="badg-s1 inline-block" style="font-size:11px; font-weight:900; color:#0f766e;">{{ $s1 }}</span>
            @else <span style="font-size:11px; color:#94a3b8;">–</span> @endif
        </td>
        <td style="padding:4px 1px; text-align:center; vertical-align:middle; border:1px solid #e2e8f0; background-color:#f0fdfa;">
            @if($s2 > 0)
                <span class="badg-s2 inline-block" style="font-size:11px; font-weight:900; color:#0f766e;">{{ $s2 }}</span>
            @else <span style="font-size:11px; color:#94a3b8;">–</span> @endif
        </td>
        <td style="padding:4px 1px; text-align:center; vertical-align:middle; border:1px solid #e2e8f0; background-color:#ecfeff;">
            @if($camp > 0)
                <span class="badg-c inline-block" style="font-size:11px; font-weight:900; color:#0e7490;">{{ $camp }}</span>
            @else <span style="font-size:11px; color:#94a3b8;">–</span> @endif
        </td>

        {{-- SERI PENGGANTI (EDITABLE) --}}
        <td style="padding:4px 2px; border:1px solid #e2e8f0; vertical-align:middle; background-color:#faf5ff; display:none;">
            <input type="text" maxlength="6" name="packs[{{ $pack - 1 }}][seri_pengganti]"
                value="{{ $packData['seri_pengganti'] }}" class="rikyet-input seri-pengganti-input"
                style="width:100%; height:26px; text-align:center; font-size:10px; font-weight:900; text-transform:uppercase; letter-spacing:-0.03em; background:#fff; border:1px solid #e5e7eb; border-radius:3px; outline:none;"
                data-pack="{{ $pack }}" oninput="formatSeriPengganti(this)"
                onkeydown="handleSeriKeydown(event, this)">
        </td>
    </tr>
@endfor