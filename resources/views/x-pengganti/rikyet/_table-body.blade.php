{{--
Partial: tbody untuk tabel rikyet.
FIX #2: No Pack cell — pakai inline style warna agar dijamin visible.
FIX #3: Border antar BARIS dalam satu pack (border-bottom tipis),
Border antar PACK (border-top tebal hijau teal).
$start, $end, $gridData wajib disertakan.
--}}

@for($pack = $start; $pack <= $end; $pack++)
    @php
        $packData = $gridData[$pack] ?? [
            'seri_pengganti' => '',
            'total_rusak_seri_1' => '',
            'total_rusak_seri_2' => '',
            'total_rusak_campuran' => '',
            'slots' => [
                1 => ['rusak_seri_1' => '', 'rusak_seri_2' => '', 'rusak_campuran' => '', 'seri_pengganti' => ''],
                2 => ['rusak_seri_1' => '', 'rusak_seri_2' => '', 'rusak_campuran' => '', 'seri_pengganti' => ''],
                3 => ['rusak_seri_1' => '', 'rusak_seri_2' => '', 'rusak_campuran' => '', 'seri_pengganti' => ''],
                4 => ['rusak_seri_1' => '', 'rusak_seri_2' => '', 'rusak_campuran' => '', 'seri_pengganti' => ''],
            ]
        ];
        // Selang-seling background pack untuk keterbacaan visual
        $isEven = $pack % 2 === 0;
        $rowBgStyle = $isEven ? 'background-color:#f8fafc;' : 'background-color:#ffffff;';
        // Dark mode handled via class
        $rowBgClass = $isEven ? 'dark:bg-slate-700/30' : 'dark:bg-slate-800';
    @endphp

    {{-- SLOT 1 — baris pertama pack: border tebal di atas sebagai pemisah pack --}}
    <tr class="{{ $rowBgClass }}" style="{{ $rowBgStyle }} border-top:2.5px solid #0d9488;" data-pack="{{ $pack }}">

        {{-- NO PACK — rowspan=4, warna dijamin putih di atas teal --}}
        <td rowspan="4"
            style="width:22px; text-align:center; vertical-align:middle; font-size:10px; font-weight:900; color:#ffffff; background-color:#0d9488; border:1px solid rgba(255,255,255,0.3); padding:2px 1px;">
            <input type="hidden" name="packs[{{ $pack }}][nomor_pack]" value="{{ $pack }}">
            {{ $pack }}
        </td>

        {{-- Rusak Seri 1 — Slot 1 --}}
        <td style="padding:1px 2px; border:1px solid #e2e8f0; border-bottom:1px dashed #e2e8f0; vertical-align:middle;">
            <input type="text" inputmode="numeric" name="packs[{{ $pack }}][slots][1][rusak_seri_1]"
                value="{{ $packData['slots'][1]['rusak_seri_1'] }}" class="rikyet-input calc-seri1-pack-{{ $pack }}"
                style="width:100%; height:22px; text-align:center; font-size:10px; font-weight:700; background:transparent; border:1px solid transparent; border-radius:3px; outline:none;"
                data-pack="{{ $pack }}" data-slot="1" data-col="seri1"
                oninput="this.value=this.value.replace(/[^0-9]/g,''); calcTotal({{ $pack }},'seri1')"
                onfocus="this.style.borderColor='#0d9488'; this.style.backgroundColor='#f0fdfa';"
                onblur="this.style.borderColor='transparent'; this.style.backgroundColor='transparent';">
            <input type="hidden" name="packs[{{ $pack }}][slots][1][slot]" value="1">
        </td>

        {{-- Rusak Seri 2 — Slot 1 --}}
        <td style="padding:1px 2px; border:1px solid #e2e8f0; border-bottom:1px dashed #e2e8f0; vertical-align:middle;">
            <input type="text" inputmode="numeric" name="packs[{{ $pack }}][slots][1][rusak_seri_2]"
                value="{{ $packData['slots'][1]['rusak_seri_2'] }}" class="rikyet-input calc-seri2-pack-{{ $pack }}"
                style="width:100%; height:22px; text-align:center; font-size:10px; font-weight:700; background:transparent; border:1px solid transparent; border-radius:3px; outline:none;"
                data-pack="{{ $pack }}" data-slot="1" data-col="seri2"
                oninput="this.value=this.value.replace(/[^0-9]/g,''); calcTotal({{ $pack }},'seri2')"
                onfocus="this.style.borderColor='#0d9488'; this.style.backgroundColor='#f0fdfa';"
                onblur="this.style.borderColor='transparent'; this.style.backgroundColor='transparent';">
        </td>

        {{-- Rusak Campuran — Slot 1 --}}
        <td style="padding:1px 2px; border:1px solid #e2e8f0; border-bottom:1px dashed #e2e8f0; vertical-align:middle;">
            <input type="text" inputmode="numeric" name="packs[{{ $pack }}][slots][1][rusak_campuran]"
                value="{{ $packData['slots'][1]['rusak_campuran'] }}" class="rikyet-input calc-campuran-pack-{{ $pack }}"
                style="width:100%; height:22px; text-align:center; font-size:10px; font-weight:700; background:transparent; border:1px solid transparent; border-radius:3px; outline:none;"
                data-pack="{{ $pack }}" data-slot="1" data-col="campuran"
                oninput="this.value=this.value.replace(/[^0-9]/g,''); calcTotal({{ $pack }},'campuran')"
                onfocus="this.style.borderColor='#0891b2'; this.style.backgroundColor='#ecfeff';"
                onblur="this.style.borderColor='transparent'; this.style.backgroundColor='transparent';">
        </td>

        {{-- TOTAL SERI 1 — rowspan=4, read-only --}}
        <td rowspan="4"
            style="padding:2px 1px; text-align:center; vertical-align:middle; border:1px solid #e2e8f0; background-color:#f0fdfa;">
            <input type="text" readonly id="total-seri1-pack-{{ $pack }}" name="packs[{{ $pack }}][total_rusak_seri_1]"
                value="{{ $packData['total_rusak_seri_1'] !== '' ? $packData['total_rusak_seri_1'] : '' }}"
                style="width:100%; text-align:center; font-size:10px; font-weight:900; color:#0f766e; background:transparent; border:none; cursor:default; outline:none;"
                tabindex="-1">
        </td>

        {{-- TOTAL SERI 2 — rowspan=4, read-only --}}
        <td rowspan="4"
            style="padding:2px 1px; text-align:center; vertical-align:middle; border:1px solid #e2e8f0; background-color:#f0fdfa;">
            <input type="text" readonly id="total-seri2-pack-{{ $pack }}" name="packs[{{ $pack }}][total_rusak_seri_2]"
                value="{{ $packData['total_rusak_seri_2'] !== '' ? $packData['total_rusak_seri_2'] : '' }}"
                style="width:100%; text-align:center; font-size:10px; font-weight:900; color:#0f766e; background:transparent; border:none; cursor:default; outline:none;"
                tabindex="-1">
        </td>

        {{-- TOTAL CAMPURAN — rowspan=4, read-only --}}
        <td rowspan="4"
            style="padding:2px 1px; text-align:center; vertical-align:middle; border:1px solid #e2e8f0; background-color:#ecfeff;">
            <input type="text" readonly id="total-campuran-pack-{{ $pack }}" name="packs[{{ $pack }}][total_rusak_campuran]"
                value="{{ $packData['total_rusak_campuran'] !== '' ? $packData['total_rusak_campuran'] : '' }}"
                style="width:100%; text-align:center; font-size:10px; font-weight:900; color:#0e7490; background:transparent; border:none; cursor:default; outline:none;"
                tabindex="-1">
        </td>

        {{-- SERI PENGGANTI — Slot 1 (per-baris, BUKAN rowspan) --}}
        <td
            style="padding:1px 2px; border:1px solid #e2e8f0; border-bottom:1px dashed #e2e8f0; vertical-align:middle; background-color:#faf5ff;">
            <input type="text" maxlength="6" name="packs[{{ $pack }}][slots][1][seri_pengganti]"
                value="{{ $packData['slots'][1]['seri_pengganti'] }}" class="rikyet-input seri-pengganti-input"
                style="width:100%; height:22px; text-align:center; font-size:9px; font-weight:900; text-transform:uppercase; letter-spacing:-0.03em; background:#fff; border:1px solid #e5e7eb; border-radius:3px; outline:none;"
                data-pack="{{ $pack }}" data-slot="1" data-col="seri" oninput="formatSeriPengganti(this)"
                onkeydown="handleSeriKeydown(event, this)" placeholder="">
        </td>
    </tr>

    {{-- SLOT 2 - baris kedua: dashed separator atas --}}
    <tr class="{{ $rowBgClass }}" style="{{ $rowBgStyle }} border-top:1px dashed #cbd5e1;" data-pack="{{ $pack }}">
        <td
            style="padding:1px 2px; border:1px solid #e2e8f0; border-bottom:1px dashed #e2e8f0; border-top:1px dashed #cbd5e1; vertical-align:middle;">
            <input type="text" inputmode="numeric" name="packs[{{ $pack }}][slots][2][rusak_seri_1]"
                value="{{ $packData['slots'][2]['rusak_seri_1'] }}" class="rikyet-input calc-seri1-pack-{{ $pack }}"
                style="width:100%; height:22px; text-align:center; font-size:10px; font-weight:700; background:transparent; border:1px solid transparent; border-radius:3px; outline:none;"
                data-pack="{{ $pack }}" data-slot="2" data-col="seri1"
                oninput="this.value=this.value.replace(/[^0-9]/g,''); calcTotal({{ $pack }},'seri1')"
                onfocus="this.style.borderColor='#0d9488'; this.style.backgroundColor='#f0fdfa';"
                onblur="this.style.borderColor='transparent'; this.style.backgroundColor='transparent';">
            <input type="hidden" name="packs[{{ $pack }}][slots][2][slot]" value="2">
        </td>
        <td
            style="padding:1px 2px; border:1px solid #e2e8f0; border-bottom:1px dashed #e2e8f0; border-top:1px dashed #cbd5e1; vertical-align:middle;">
            <input type="text" inputmode="numeric" name="packs[{{ $pack }}][slots][2][rusak_seri_2]"
                value="{{ $packData['slots'][2]['rusak_seri_2'] }}" class="rikyet-input calc-seri2-pack-{{ $pack }}"
                style="width:100%; height:22px; text-align:center; font-size:10px; font-weight:700; background:transparent; border:1px solid transparent; border-radius:3px; outline:none;"
                data-pack="{{ $pack }}" data-slot="2" data-col="seri2"
                oninput="this.value=this.value.replace(/[^0-9]/g,''); calcTotal({{ $pack }},'seri2')"
                onfocus="this.style.borderColor='#0d9488'; this.style.backgroundColor='#f0fdfa';"
                onblur="this.style.borderColor='transparent'; this.style.backgroundColor='transparent';">
        </td>
        <td
            style="padding:1px 2px; border:1px solid #e2e8f0; border-bottom:1px dashed #e2e8f0; border-top:1px dashed #cbd5e1; vertical-align:middle;">
            <input type="text" inputmode="numeric" name="packs[{{ $pack }}][slots][2][rusak_campuran]"
                value="{{ $packData['slots'][2]['rusak_campuran'] }}" class="rikyet-input calc-campuran-pack-{{ $pack }}"
                style="width:100%; height:22px; text-align:center; font-size:10px; font-weight:700; background:transparent; border:1px solid transparent; border-radius:3px; outline:none;"
                data-pack="{{ $pack }}" data-slot="2" data-col="campuran"
                oninput="this.value=this.value.replace(/[^0-9]/g,''); calcTotal({{ $pack }},'campuran')"
                onfocus="this.style.borderColor='#0891b2'; this.style.backgroundColor='#ecfeff';"
                onblur="this.style.borderColor='transparent'; this.style.backgroundColor='transparent';">
        </td>
        <td
            style="padding:1px 2px; border:1px solid #e2e8f0; border-top:1px dashed #cbd5e1; vertical-align:middle; background-color:#faf5ff;">
            <input type="text" maxlength="6" name="packs[{{ $pack }}][slots][2][seri_pengganti]"
                value="{{ $packData['slots'][2]['seri_pengganti'] }}" class="rikyet-input seri-pengganti-input"
                style="width:100%; height:22px; text-align:center; font-size:9px; font-weight:900; text-transform:uppercase; letter-spacing:-0.03em; background:#fff; border:1px solid #e5e7eb; border-radius:3px; outline:none;"
                data-pack="{{ $pack }}" data-slot="2" data-col="seri" oninput="formatSeriPengganti(this)"
                onkeydown="handleSeriKeydown(event, this)" placeholder="">
        </td>
    </tr>

    {{-- SLOT 3 --}}
    <tr class="{{ $rowBgClass }}" style="{{ $rowBgStyle }} border-top:1px dashed #cbd5e1;" data-pack="{{ $pack }}">
        <td
            style="padding:1px 2px; border:1px solid #e2e8f0; border-bottom:1px dashed #e2e8f0; border-top:1px dashed #cbd5e1; vertical-align:middle;">
            <input type="text" inputmode="numeric" name="packs[{{ $pack }}][slots][3][rusak_seri_1]"
                value="{{ $packData['slots'][3]['rusak_seri_1'] }}" class="rikyet-input calc-seri1-pack-{{ $pack }}"
                style="width:100%; height:22px; text-align:center; font-size:10px; font-weight:700; background:transparent; border:1px solid transparent; border-radius:3px; outline:none;"
                data-pack="{{ $pack }}" data-slot="3" data-col="seri1"
                oninput="this.value=this.value.replace(/[^0-9]/g,''); calcTotal({{ $pack }},'seri1')"
                onfocus="this.style.borderColor='#0d9488'; this.style.backgroundColor='#f0fdfa';"
                onblur="this.style.borderColor='transparent'; this.style.backgroundColor='transparent';">
            <input type="hidden" name="packs[{{ $pack }}][slots][3][slot]" value="3">
        </td>
        <td
            style="padding:1px 2px; border:1px solid #e2e8f0; border-bottom:1px dashed #e2e8f0; border-top:1px dashed #cbd5e1; vertical-align:middle;">
            <input type="text" inputmode="numeric" name="packs[{{ $pack }}][slots][3][rusak_seri_2]"
                value="{{ $packData['slots'][3]['rusak_seri_2'] }}" class="rikyet-input calc-seri2-pack-{{ $pack }}"
                style="width:100%; height:22px; text-align:center; font-size:10px; font-weight:700; background:transparent; border:1px solid transparent; border-radius:3px; outline:none;"
                data-pack="{{ $pack }}" data-slot="3" data-col="seri2"
                oninput="this.value=this.value.replace(/[^0-9]/g,''); calcTotal({{ $pack }},'seri2')"
                onfocus="this.style.borderColor='#0d9488'; this.style.backgroundColor='#f0fdfa';"
                onblur="this.style.borderColor='transparent'; this.style.backgroundColor='transparent';">
        </td>
        <td
            style="padding:1px 2px; border:1px solid #e2e8f0; border-bottom:1px dashed #e2e8f0; border-top:1px dashed #cbd5e1; vertical-align:middle;">
            <input type="text" inputmode="numeric" name="packs[{{ $pack }}][slots][3][rusak_campuran]"
                value="{{ $packData['slots'][3]['rusak_campuran'] }}" class="rikyet-input calc-campuran-pack-{{ $pack }}"
                style="width:100%; height:22px; text-align:center; font-size:10px; font-weight:700; background:transparent; border:1px solid transparent; border-radius:3px; outline:none;"
                data-pack="{{ $pack }}" data-slot="3" data-col="campuran"
                oninput="this.value=this.value.replace(/[^0-9]/g,''); calcTotal({{ $pack }},'campuran')"
                onfocus="this.style.borderColor='#0891b2'; this.style.backgroundColor='#ecfeff';"
                onblur="this.style.borderColor='transparent'; this.style.backgroundColor='transparent';">
        </td>
        <td
            style="padding:1px 2px; border:1px solid #e2e8f0; border-top:1px dashed #cbd5e1; vertical-align:middle; background-color:#faf5ff;">
            <input type="text" maxlength="6" name="packs[{{ $pack }}][slots][3][seri_pengganti]"
                value="{{ $packData['slots'][3]['seri_pengganti'] }}" class="rikyet-input seri-pengganti-input"
                style="width:100%; height:22px; text-align:center; font-size:9px; font-weight:900; text-transform:uppercase; letter-spacing:-0.03em; background:#fff; border:1px solid #e5e7eb; border-radius:3px; outline:none;"
                data-pack="{{ $pack }}" data-slot="3" data-col="seri" oninput="formatSeriPengganti(this)"
                onkeydown="handleSeriKeydown(event, this)" placeholder="">
        </td>
    </tr>

    {{-- SLOT 4 — baris terakhir pack --}}
    <tr class="{{ $rowBgClass }}" style="{{ $rowBgStyle }} border-top:1px dashed #cbd5e1;" data-pack="{{ $pack }}">
        <td style="padding:1px 2px; border:1px solid #e2e8f0; border-top:1px dashed #cbd5e1; vertical-align:middle;">
            <input type="text" inputmode="numeric" name="packs[{{ $pack }}][slots][4][rusak_seri_1]"
                value="{{ $packData['slots'][4]['rusak_seri_1'] }}" class="rikyet-input calc-seri1-pack-{{ $pack }}"
                style="width:100%; height:22px; text-align:center; font-size:10px; font-weight:700; background:transparent; border:1px solid transparent; border-radius:3px; outline:none;"
                data-pack="{{ $pack }}" data-slot="4" data-col="seri1"
                oninput="this.value=this.value.replace(/[^0-9]/g,''); calcTotal({{ $pack }},'seri1')"
                onfocus="this.style.borderColor='#0d9488'; this.style.backgroundColor='#f0fdfa';"
                onblur="this.style.borderColor='transparent'; this.style.backgroundColor='transparent';">
            <input type="hidden" name="packs[{{ $pack }}][slots][4][slot]" value="4">
        </td>
        <td style="padding:1px 2px; border:1px solid #e2e8f0; border-top:1px dashed #cbd5e1; vertical-align:middle;">
            <input type="text" inputmode="numeric" name="packs[{{ $pack }}][slots][4][rusak_seri_2]"
                value="{{ $packData['slots'][4]['rusak_seri_2'] }}" class="rikyet-input calc-seri2-pack-{{ $pack }}"
                style="width:100%; height:22px; text-align:center; font-size:10px; font-weight:700; background:transparent; border:1px solid transparent; border-radius:3px; outline:none;"
                data-pack="{{ $pack }}" data-slot="4" data-col="seri2"
                oninput="this.value=this.value.replace(/[^0-9]/g,''); calcTotal({{ $pack }},'seri2')"
                onfocus="this.style.borderColor='#0d9488'; this.style.backgroundColor='#f0fdfa';"
                onblur="this.style.borderColor='transparent'; this.style.backgroundColor='transparent';">
        </td>
        <td style="padding:1px 2px; border:1px solid #e2e8f0; border-top:1px dashed #cbd5e1; vertical-align:middle;">
            <input type="text" inputmode="numeric" name="packs[{{ $pack }}][slots][4][rusak_campuran]"
                value="{{ $packData['slots'][4]['rusak_campuran'] }}" class="rikyet-input calc-campuran-pack-{{ $pack }}"
                style="width:100%; height:22px; text-align:center; font-size:10px; font-weight:700; background:transparent; border:1px solid transparent; border-radius:3px; outline:none;"
                data-pack="{{ $pack }}" data-slot="4" data-col="campuran"
                oninput="this.value=this.value.replace(/[^0-9]/g,''); calcTotal({{ $pack }},'campuran')"
                onfocus="this.style.borderColor='#0891b2'; this.style.backgroundColor='#ecfeff';"
                onblur="this.style.borderColor='transparent'; this.style.backgroundColor='transparent';">
        </td>
        <td
            style="padding:1px 2px; border:1px solid #e2e8f0; border-top:1px dashed #cbd5e1; vertical-align:middle; background-color:#faf5ff;">
            <input type="text" maxlength="6" name="packs[{{ $pack }}][slots][4][seri_pengganti]"
                value="{{ $packData['slots'][4]['seri_pengganti'] }}" class="rikyet-input seri-pengganti-input"
                style="width:100%; height:22px; text-align:center; font-size:9px; font-weight:900; text-transform:uppercase; letter-spacing:-0.03em; background:#fff; border:1px solid #e5e7eb; border-radius:3px; outline:none;"
                data-pack="{{ $pack }}" data-slot="4" data-col="seri" oninput="formatSeriPengganti(this)"
                onkeydown="handleSeriKeydown(event, this)" placeholder="">
        </td>
    </tr>
@endfor