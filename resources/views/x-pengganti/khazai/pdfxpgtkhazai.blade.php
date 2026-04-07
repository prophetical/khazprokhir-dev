<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lembar Pengganti — {{ $seri->seri }} | Khazprokhir</title>
    <style>
        /* ── Base Styles ── */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 7pt;
            color: #1e293b;
            line-height: 1.1;
            background: white;
        }

        /* ── Page Setup (Landscape A4) ── */
        @page {
            size: A4 landscape;
            margin: 0.4cm;
        }

        /* ── Utilities ── */
        .w-full { width: 100%; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-black { font-weight: 900; }
        .font-bold { font-weight: 700; }
        .uppercase { text-transform: uppercase; }
        
        /* ── Header ── */
        .doc-header {
            border-bottom: 2px solid #f59e0b;
            margin-bottom: 6px;
            padding-bottom: 2px;
        }
        .doc-title {
            font-size: 13pt;
            font-weight: 900;
            color: #0f172a;
        }
        .doc-meta {
            font-size: 6.5pt;
            color: #64748b;
        }

        /* ── Info Seri Section ── */
        .info-card {
            background-color: #fffbeb;
            border: 1px solid #fde68a;
            margin-bottom: 8px;
            padding: 3px 6px;
            border-radius: 4px;
        }
        .info-table th {
            text-align: left;
            font-size: 5.5pt;
            color: #b45309;
            padding: 0 3px;
            font-weight: normal; /* Normal font for labels */
        }
        .info-table td {
            font-size: 8pt;
            font-weight: 900;
            color: #1e293b;
            padding: 0 3px;
        }

        /* ── Master Columnar Layout ── */
        .column-container {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .column-cell {
            width: 25%;
            padding: 0 1.5px;
            vertical-align: top;
        }

        /* ── Data Table Styling ── */
        .data-table {
            border-collapse: collapse;
            width: 100%;
            table-layout: fixed;
        }
        .data-table thead th {
            background-color: #f59e0b;
            color: white;
            font-weight: normal; /* REDUCE BOLD: As requested */
            font-size: 5.5pt;    /* SMALLER TEXT: As requested */
            border: 0.4pt solid #d97706;
            padding: 1.5px 0.5px;
            text-align: center;
        }
        .data-table tbody td {
            border: 0.4pt solid #e2e8f0;
            padding: 1px 0.5px;
            font-size: 6.5pt;
            text-align: center;
        }
        
        .bg-pack { background-color: #334155 !important; color: white !important; font-weight: 900; width: 14pt; }
        .bg-jumlah { background-color: #fff7ed !important; color: #c2410c !important; font-weight: 900; width: 13pt; } /* SMALLER JUMLAH: As requested */
        .bg-alternate { background-color: #f8fafc; }
        
        /* ── Pack Boundary ── */
        .pack-row-start td {
            border-top: 1pt solid #334155 !important;
        }
        
        /* ── Footer ── */
        .doc-footer {
            margin-top: 8px;
            padding-top: 4px;
            border-top: 0.5pt solid #e2e8f0;
        }
        .signature-area {
            width: 110px;
            border-bottom: 0.6pt solid #1e293b;
            margin-bottom: 2px;
            height: 30px;
        }

        /* ── Screen Only ── */
        .no-print { display: none; }
        @media screen {
            .no-print { display: block; text-align: center; padding: 10px; background: #f1f5f9; border-bottom: 1px solid #e2e8f0; }
            .print-btn {
                background: #f59e0b; color: white; border: none; padding: 6px 14px; 
                border-radius: 4px; cursor: pointer; font-weight: 900; font-size: 10pt;
            }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <a href="{{ url()->previous() }}" style="margin-right: 15px; color: #64748b; text-decoration: none; font-size: 9pt;">← Kembali</a>
        <button class="print-btn" onclick="window.print()">CETAK PDF (4 KOLOM)</button>
    </div>

    <!-- Header Section -->
    <table class="w-full doc-header">
        <tr>
            <td>
                <div class="font-black uppercase" style="font-size: 5pt; color: #f59e0b; letter-spacing: 0.1em;">Khazprokhir System — X Pengganti</div>
                <div class="doc-title">LEMBAR PENGGANTI (100 PACK)</div>
            </td>
            <td class="text-right">
                <div class="doc-meta">Tanggal: <b>{{ $exportedAt }}</b></div>
                <div class="doc-meta">Seri: <b>{{ $seri->seri }}</b> | Batch: <b>{{ $seri->batch }}</b></div>
            </td>
        </tr>
    </table>

    <!-- Info Seri Card -->
    <div class="info-card">
        <table class="w-full info-table">
            <tr>
                <th width="10%">PECAHAN</th>
                <th width="20%">SERI</th>
                <th width="15%">BATCH</th>
                <th width="15%">TAHUN ANGGARAN</th>
                <th width="15%">TAHUN EMISI</th>
                <th class="text-right">TOTAL RUSAK</th>
            </tr>
            <tr>
                <td>{{ $seri->pecahan }}</td>
                <td>{{ $seri->seri }}</td>
                <td>{{ $seri->batch }}</td>
                <td>{{ $seri->tahun_anggaran }}</td>
                <td>{{ $seri->tahun_emisi }}</td>
                <td class="text-right font-black" style="font-size: 11pt; color: #b45309;">
                    {{ $packs->flatMap->details->sum('jumlah_rusak_vell') }} <small style="font-size: 6pt; font-weight: 400;">VELL</small>
                </td>
            </tr>
        </table>
    </div>

    <!-- 4-Column Layout Master Table -->
    <table class="column-container">
        <tr>
            @php
                $ranges = [
                    ['start' => 1,  'end' => 25],
                    ['start' => 26, 'end' => 50],
                    ['start' => 51, 'end' => 75],
                    ['start' => 76, 'end' => 100],
                ];
            @endphp

            @foreach($ranges as $range)
                <td class="column-cell">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th rowspan="2" class="bg-pack" style="width: 13pt;">PK</th>
                                <th colspan="4">INSCHIET VELL</th>
                                <th rowspan="2" style="width: 32pt;">SERI PGT</th>
                            </tr>
                            <tr>
                                <th style="width: 12pt;">RSK</th>
                                <th class="bg-jumlah" style="width: 13pt;">JML</th>
                                <th style="width: 15pt;">PGT-P</th>
                                <th style="width: 12pt;">PGT-V</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($pNum = $range['start']; $pNum <= $range['end']; $pNum++)
                                @php
                                    $packModel  = $packs->firstWhere('nomor_pack', $pNum);
                                    $details    = $packModel?->details ?? collect();
                                    $totalRusak = $details->sum('jumlah_rusak_vell');
                                @endphp

                                @for($slotValue = 1; $slotValue <= 4; $slotValue++)
                                    @php
                                        $detail = $details->firstWhere('slot', $slotValue);
                                        $pPGT = $detail?->nomor_pack_pengganti;
                                        $vPGT = $detail?->nomor_vell_pengganti;
                                    @endphp
                                    <tr class="{{ $slotValue === 1 ? 'pack-row-start' : '' }} {{ $slotValue % 2 === 0 ? 'bg-alternate' : '' }}">
                                        @if($slotValue === 1)
                                            <td rowspan="4" class="bg-pack font-black">{{ $pNum }}</td>
                                        @endif
                                        <td>{{ $detail?->jumlah_rusak_vell ?? '–' }}</td>
                                        @if($slotValue === 1)
                                            <td rowspan="4" class="bg-jumlah">{{ $totalRusak > 0 ? $totalRusak : '–' }}</td>
                                        @endif
                                        <td>{{ $pPGT ?? '–' }}</td>
                                        <td>{{ $vPGT ?? '–' }}</td>
                                        @if($slotValue === 1)
                                            <td rowspan="4" class="font-bold" style="font-size: 5pt; color: #0f172a; word-wrap: break-word;">
                                                {{ $packModel?->seri_pengganti ?? '–' }}
                                            </td>
                                        @endif
                                    </tr>
                                @endfor
                            @endfor
                        </tbody>
                    </table>
                </td>
            @endforeach
        </tr>
    </table>

    <!-- Generic Footer -->
    <table class="w-full doc-footer">
        <tr>
            <td>
                <div style="font-size: 5.5pt; color: #94a3b8;">
                    Khazprokhir System — Generasi otomatis: {{ $exportedAt }}<br>
                    Dokumen ini adalah data historis transaksi penggantian bilyet.
                </div>
            </td>
            <td class="text-right">
                <table style="float: right;">
                    <tr>
                        <td class="text-center">
                            <div style="font-size: 6pt; margin-bottom: 2px;">Petugas Khazai:</div>
                            <div class="signature-area"></div>
                            <div style="font-size: 5pt; font-weight: bold;">( ......................................... )</div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>
</html>