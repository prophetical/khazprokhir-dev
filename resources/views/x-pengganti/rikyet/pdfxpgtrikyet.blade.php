<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Rikyet — {{ $seri->seri }} | X Pengganti</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 7.5pt;
            color: #1e293b;
            background: #fff;
        }

        /* ── Header ── */
        .doc-header {
            padding: 12px 16px 8px;
            border-bottom: 2.5px solid #0d9488;
            margin-bottom: 8px;
        }

        .doc-title {
            font-size: 13pt;
            font-weight: 900;
            color: #0d9488;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .doc-subtitle {
            font-size: 8pt;
            color: #64748b;
            margin-top: 2px;
            font-weight: 600;
        }

        .meta-grid {
            display: grid;
            grid-template-columns: repeat(5, auto);
            gap: 0 24px;
            margin-top: 8px;
        }

        .meta-item .label {
            font-size: 6pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #94a3b8;
        }

        .meta-item .value {
            font-size: 9pt;
            font-weight: 900;
            color: #0f172a;
        }

        .pecahan-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 22px;
            height: 22px;
            border-radius: 5px;
            background: #0d9488;
            color: white;
            font-weight: 900;
            font-size: 9pt;
        }

        .export-info {
            text-align: right;
            font-size: 6.5pt;
            color: #94a3b8;
            font-style: italic;
            margin-top: 4px;
        }

        /* ── Tabel ── */
        .table-wrap {
            padding: 0 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        thead th {
            background: #0f766e;
            color: white;
            font-weight: 900;
            text-align: center;
            padding: 3px 2px;
            border: 0.5pt solid #0d9488;
            font-size: 6.5pt;
            white-space: nowrap;
        }

        thead th.sub {
            background: #0d9488;
            font-size: 6pt;
        }

        thead th.total-col {
            background: #115e59;
        }

        thead th.keterangan-col {
            background: #4a1d96;
            font-size: 6pt;
        }

        tbody tr {
            page-break-inside: avoid;
        }

        tbody tr:nth-child(8n+1),
        tbody tr:nth-child(8n+2),
        tbody tr:nth-child(8n+3),
        tbody tr:nth-child(8n+4) {
            background: #f0fdfa;
        }

        tbody tr:nth-child(8n+5),
        tbody tr:nth-child(8n+6),
        tbody tr:nth-child(8n+7),
        tbody tr:nth-child(8n) {
            background: #ffffff;
        }

        tbody td {
            padding: 2px 3px;
            border: 0.4pt solid #cbd5e1;
            text-align: center;
            vertical-align: middle;
            font-size: 7pt;
        }

        tbody td.pack-no {
            background: #0d9488;
            color: white;
            font-weight: 900;
            font-size: 8pt;
            width: 18pt;
        }

        tbody td.total-cell {
            background: #ccfbf1;
            font-weight: 900;
            color: #0f766e;
        }

        tbody td.ket-cell {
            background: #ede9fe;
            font-size: 6.5pt;
            font-weight: 700;
            color: #4a1d96;
        }

        tbody td.empty {
            color: #cbd5e1;
        }

        /* Border atas tiap grup Pack baru */
        tr.pack-first-row td {
            border-top: 1.5pt solid #0d9488 !important;
        }

        /* ── Footer ── */
        .pdf-footer {
            margin-top: 10px;
            padding: 8px 16px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .sign-block {
            text-align: center;
        }

        .sign-block .role {
            font-size: 7pt;
            font-weight: 900;
            text-transform: uppercase;
            color: #0f766e;
        }

        .sign-block .space {
            height: 36px;
        }

        .sign-block .name {
            font-size: 7pt;
            font-weight: 900;
            border-top: 1px solid #0f172a;
            padding-top: 3px;
            min-width: 100px;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            @page {
                size: A3 landscape;
                margin: 10mm;
            }
        }
    </style>
</head>

<body>

    {{-- ── Document Header ── --}}
    <div class="doc-header">
        <div style="display:flex; justify-content:space-between; align-items:flex-start;">
            <div>
                <div class="doc-title">Rekapitulasi Rusak Bilyet (Brood) — Seksi Rikyet</div>
                <div class="doc-subtitle">Form Input Rikyet · Modul X Pengganti · 1 Brood = 1.000 Bilyet</div>
                <div class="meta-grid" style="margin-top:8px;">
                    <div class="meta-item">
                        <div class="label">Seri</div>
                        <div class="value">{{ $seri->seri }}</div>
                    </div>
                    <div class="meta-item">
                        <div class="label">Pecahan</div>
                        <div class="value"><span class="pecahan-badge">{{ $seri->pecahan }}</span></div>
                    </div>
                    <div class="meta-item">
                        <div class="label">Batch</div>
                        <div class="value">{{ $seri->batch }}</div>
                    </div>
                    <div class="meta-item">
                        <div class="label">Tahun Emisi</div>
                        <div class="value">{{ $seri->tahun_emisi }}</div>
                    </div>
                    <div class="meta-item">
                        <div class="label">Tahun Anggaran</div>
                        <div class="value">{{ $seri->tahun_anggaran }}</div>
                    </div>
                </div>
            </div>
            <div class="export-info">
                Tanggal Ekspor:<br>
                <strong>{{ $exportedAt }}</strong>
            </div>
        </div>
    </div>

    {{-- ── Tabel Utama ── --}}
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th rowspan="2" style="width:18pt;">No<br>Pack</th>
                    <th colspan="3">Jumlah Rusak (Brood)</th>
                    <th colspan="3" class="total-col">Total Jumlah (Brood)</th>
                    <th rowspan="2" class="keterangan-col" style="width:40pt;">Seri<br>Pengganti</th>
                </tr>
                <tr>
                    <th class="sub">Seri 1<br><span style="font-weight:500;font-size:5.5pt;">(A1–U1)</span></th>
                    <th class="sub">Seri 2<br><span style="font-weight:500;font-size:5.5pt;">(A2–U2)</span></th>
                    <th class="sub">Campuran<br><span style="font-weight:500;font-size:5.5pt;">(V1,W1,Y1,Z1&V2)</span>
                    </th>
                    <th class="sub total-col">Seri 1</th>
                    <th class="sub total-col">Seri 2</th>
                    <th class="sub total-col">Campuran</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $grandS1 = 0;
                    $grandS2 = 0;
                    $grandCamp = 0;
                @endphp
                @for($pack = 1; $pack <= 100; $pack++)
                    @php
                        $packData = $gridData[$pack];
                        $hasAny = false;
                        foreach ($packData['slots'] as $slt) {
                            if ($slt['rusak_seri_1'] !== '' || $slt['rusak_seri_2'] !== '' || $slt['rusak_campuran'] !== '' || $slt['seri_pengganti'] !== '') {
                                $hasAny = true;
                                break;
                            }
                        }
                        $tS1 = $packData['total_rusak_seri_1'] !== '' ? (int) $packData['total_rusak_seri_1'] : null;
                        $tS2 = $packData['total_rusak_seri_2'] !== '' ? (int) $packData['total_rusak_seri_2'] : null;
                        $tCamp = $packData['total_rusak_campuran'] !== '' ? (int) $packData['total_rusak_campuran'] : null;
                        if ($tS1)
                            $grandS1 += $tS1;
                        if ($tS2)
                            $grandS2 += $tS2;
                        if ($tCamp)
                            $grandCamp += $tCamp;
                    @endphp

                    @for($slot = 1; $slot <= 4; $slot++)
                        @php
                            $slotData = $packData['slots'][$slot];
                            $v1 = $slotData['rusak_seri_1'] !== '' ? $slotData['rusak_seri_1'] : null;
                            $v2 = $slotData['rusak_seri_2'] !== '' ? $slotData['rusak_seri_2'] : null;
                            $vc = $slotData['rusak_campuran'] !== '' ? $slotData['rusak_campuran'] : null;
                            $vsp = $slotData['seri_pengganti'] !== '' ? $slotData['seri_pengganti'] : null;
                        @endphp
                        <tr class="{{ $slot === 1 ? 'pack-first-row' : '' }}">
                            @if($slot === 1)
                                <td rowspan="4" class="pack-no">{{ $pack }}</td>
                            @endif
                            <td class="{{ $v1 === null ? 'empty' : '' }}">{{ $v1 ?? '–' }}</td>
                            <td class="{{ $v2 === null ? 'empty' : '' }}">{{ $v2 ?? '–' }}</td>
                            <td class="{{ $vc === null ? 'empty' : '' }}">{{ $vc ?? '–' }}</td>
                            @if($slot === 1)
                                <td rowspan="4" class="total-cell">{{ $tS1 ?? '–' }}</td>
                                <td rowspan="4" class="total-cell">{{ $tS2 ?? '–' }}</td>
                                <td rowspan="4" class="total-cell">{{ $tCamp ?? '–' }}</td>
                            @endif
                            <td class="ket-cell {{ $vsp === null ? 'empty' : '' }}">{{ $vsp ?? '–' }}</td>
                        </tr>
                    @endfor
                @endfor

                {{-- Grand Total Row --}}
                <tr style="background:#f0fdfa; font-weight:900; border-top:2pt solid #0d9488;">
                    <td colspan="4"
                        style="text-align:right; padding-right:8px; font-weight:900; font-size:7.5pt; color:#0f766e;">
                        GRAND TOTAL</td>
                    <td class="total-cell" style="font-size:8pt;">
                        {{ $grandS1 > 0 ? number_format($grandS1, 0, ',', '.') : '–' }}
                    </td>
                    <td class="total-cell" style="font-size:8pt;">
                        {{ $grandS2 > 0 ? number_format($grandS2, 0, ',', '.') : '–' }}
                    </td>
                    <td class="total-cell" style="font-size:8pt;">
                        {{ $grandCamp > 0 ? number_format($grandCamp, 0, ',', '.') : '–' }}
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- ── Footer TTD ── --}}
    <div class="pdf-footer">
        <div style="font-size:7pt; color:#64748b; max-width:220px;">
            <p style="font-weight:700;">Catatan:</p>
            <p>Satuan dalam <strong>Brood</strong> (1 Brood = 1.000 Bilyet). Data dicetak dari Sistem Informasi X
                Pengganti.</p>
        </div>
        <div style="display:flex; gap:40px;">
            <div class="sign-block">
                <div class="role">Petugas Rikyet</div>
                <div class="space"></div>
                <div class="name">( _________________ )</div>
            </div>
            <div class="sign-block">
                <div class="role">Mengetahui</div>
                <div class="space"></div>
                <div class="name">( _________________ )</div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('load', () => window.print());
    </script>
</body>

</html>