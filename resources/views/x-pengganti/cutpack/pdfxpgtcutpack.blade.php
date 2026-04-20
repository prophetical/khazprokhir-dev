<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lembar Pengganti Cutpack — {{ $seri->seri }}</title>
    <style>
        /* ── Base Styles ── */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 7.5pt;
            color: #1e293b;
            line-height: 1.2;
            background: white;
        }

        @page {
            size: legal landscape;
            margin: 0.5cm;
        }

        .w-full {
            width: 100%;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .font-black {
            font-weight: 900;
        }

        .uppercase {
            text-transform: uppercase;
        }

        /* ── Header ── */
        .doc-header {
            border-bottom: 2px solid #4f46e5;
            margin-bottom: 6px;
            padding-bottom: 4px;
            border-collapse: collapse;
        }

        .doc-title {
            font-size: 13pt;
            font-weight: 900;
            color: #0f172a;
        }

        .doc-meta {
            font-size: 8pt;
            color: #64748b;
        }

        /* ── Info Seri Section ── */
        .info-card {
            background-color: #eef2ff;
            border: 1.5px solid #a5b4fc;
            margin-bottom: 12px;
            padding: 6px 10px;
            border-radius: 6px;
        }

        .info-table th {
            text-align: left;
            font-size: 7pt;
            color: #4338ca;
            padding: 0 4px;
            font-weight: normal;
        }

        .info-table td {
            font-size: 10pt;
            font-weight: 900;
            color: #1e293b;
            padding: 0 4px;
        }

        .pecahan-badge {
            display: inline-block;
            width: 24px;
            height: 24px;
            line-height: 24px;
            text-align: center;
            border-radius: 6px;
            background: {{ $seri->pecahan_color_hex }};
            color: white;
            font-weight: 900;
            font-size: 10pt;
        }

        /* ── Cutpack Grid Table ── */
        .grid-container {
            width: 100%;
            overflow: hidden;
        }

        .data-table {
            border-collapse: collapse;
            width: 100%;
            table-layout: fixed;
        }

        .data-table thead th {
            background-color: #f1f5f9;
            color: #334155;
            border: 1pt solid #cbd5e1;
            padding: 3px 1px;
            font-size: 6.5pt;
            font-weight: normal;
            text-align: center;
        }

        .bg-pack {
            background-color: #1e293b !important;
            color: white !important;
            font-weight: 900;
        }

        .head-amber {
            background-color: #f59e0b !important;
            color: white !important;
            font-weight: 900;
        }

        .head-indigo {
            background-color: #4f46e5 !important;
            color: white !important;
            font-weight: 900;
        }

        .head-dark-amber {
            background-color: #78350f !important;
            color: #fde68a !important;
            font-weight: 900;
        }

        .head-dark-indigo {
            background-color: #312e81 !important;
            color: #e0e7ff !important;
            font-weight: 900;
        }

        .head-sub {
            background-color: #475569 !important;
            color: white !important;
            font-weight: 900;
        }

        .data-table tbody td {
            border: 0.5pt solid #cbd5e1;
            padding: 2px 1px;
            font-size: 8.5pt;
            font-weight: 900;
            text-align: center;
            vertical-align: middle;
        }

        .row-border-thick {
            border-bottom: 2pt solid #0f172a !important;
        }

        .cell-seri {
            background-color: #f8fafc;
        }

        .cell-total {
            background-color: #fffbeb;
            color: #b45309;
        }

        .cell-sp {
            background-color: #eef2ff;
            color: #3730a3;
        }

        /* ── Screen Only ── */
        .no-print {
            display: none;
        }

        @media screen {
            .no-print {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 20px;
                padding: 15px;
                background: #f1f5f9;
                border-bottom: 1px solid #e2e8f0;
                margin-bottom: 15px;
            }

            .print-btn {
                background: #4f46e5;
                color: white;
                border: none;
                padding: 10px 24px;
                border-radius: 8px;
                cursor: pointer;
                font-weight: 900;
                font-size: 11pt;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                transition: 0.2s;
            }

            .print-btn:hover {
                background: #4338ca;
                transform: translateY(-1px);
            }
        }
    </style>
</head>

<body onload="window.print()">

    <div class="no-print">
        <a href="{{ url()->previous() }}"
            style="color: #64748b; text-decoration: none; font-size: 11pt; font-weight: bold;">&larr; Kembali</a>
        <button class="print-btn" onclick="window.print()">Print PDF</button>
    </div>

    <!-- Header Section -->
    <table class="w-full doc-header">
        <tr>
            <td>
                <div class="font-black uppercase" style="font-size: 6.5pt; color: #4f46e5; letter-spacing: 0.1em;">
                    Khazprokhir System — X Pengganti (Cutpack)</div>
                <div class="doc-title">LEMBAR X PENGGANTI CUTPACK</div>
            </td>
            <td class="text-right">
                <div class="doc-meta">Tanggal Cetak: <b>{{ $exportDate }}</b></div>
                <div class="doc-meta">Berdasarkan Seri: <b>{{ $seri->seri }}</b></div>
            </td>
        </tr>
    </table>

    <!-- Info Seri Card -->
    <div class="info-card">
        <table class="w-full info-table">
            <tr>
                <th width="15%">PECAHAN</th>
                <th width="25%">NOMOR SERI</th>
                <th width="20%">BATCH / KELOMPOK</th>
                <th width="20%">TAHUN ANGGARAN</th>
                <th width="20%">TAHUN EMISI</th>
            </tr>
            <tr>
                <td style="font-size: 12pt;"><span class="pecahan-badge">{{ $seri->pecahan }}</span></td>
                <td style="font-size: 12pt;">{{ $seri->seri }}</td>
                <td style="font-size: 12pt;">{{ $seri->batch }}</td>
                <td style="font-size: 12pt;">{{ $seri->tahun_anggaran }}</td>
                <td style="font-size: 12pt;">{{ $seri->tahun_emisi }}</td>
            </tr>
        </table>
    </div>

    <!-- The 100 Pack Grid -->
    <div class="grid-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th rowspan="3" style="width: 32px;" class="bg-pack">NO.<br>PACK</th>
                    <th colspan="6" class="head-amber">INSCHIET LEMBAR BILYET</th>
                    <th colspan="3" class="head-indigo">KETERANGAN</th>
                </tr>
                <tr>
                    <th colspan="3" class="head-sub">JUMLAH RUSAK (Brood)</th>
                    <th colspan="3" class="head-sub" style="color: #fde68a !important;">TOTAL</th>
                    <th rowspan="2" class="head-indigo">NOMOR PACK<br>PENGGANTI</th>
                    <th rowspan="2" class="head-dark-indigo">SERI<br>PENGGANTI</th>
                    <th rowspan="2" class="head-indigo">NOMOR BILYET<br>PENGGANTI</th>
                </tr>
                <tr>
                    <th class="head-sub">SERI 1<br>(A1-U1)</th>
                    <th class="head-sub">SERI 2<br>(A2-U2)</th>
                    <th class="head-sub">CAMPURAN</th>
                    <th class="head-dark-amber">SERI 1<br>(A1-U1)</th>
                    <th class="head-dark-amber">SERI 2<br>(A2-U2)</th>
                    <th class="head-dark-amber">CAMPURAN<br>(V1,W1,Y1,Z1 DAN V2)</th>
                </tr>
            </thead>
            <tbody>
                @for ($p = 1; $p <= 100; $p++)
                    @for ($s = 1; $s <= 4; $s++)
                        @php
                            $isLastSlot = ($s == 4);
                            $rowStyle = $isLastSlot ? 'row-border-thick' : '';
                        @endphp
                        <tr>
                            @if ($s == 1)
                                <td rowspan="4" class="bg-pack row-border-thick" style="font-size: 10pt;">{{ $p }}</td>
                            @endif

                            <td class="cell-seri {{ $rowStyle }}">{{ $gridData[$p]['slots'][$s]['rusak_seri_1'] ?? '-' }}</td>
                            <td class="cell-seri {{ $rowStyle }}">{{ $gridData[$p]['slots'][$s]['rusak_seri_2'] ?? '-' }}</td>
                            <td class="cell-seri {{ $rowStyle }}">{{ $gridData[$p]['slots'][$s]['rusak_campuran'] ?? '-' }}</td>

                            @if ($s == 1)
                                <td rowspan="4" class="cell-total row-border-thick" style="font-size: 11pt;">
                                    {{ $gridData[$p]['total_rusak_seri_1'] ?? '-' }}</td>
                                <td rowspan="4" class="cell-total row-border-thick" style="font-size: 11pt;">
                                    {{ $gridData[$p]['total_rusak_seri_2'] ?? '-' }}</td>
                                <td rowspan="4" class="cell-total row-border-thick" style="font-size: 11pt;">
                                    {{ $gridData[$p]['total_rusak_campuran'] ?? '-' }}</td>
                            @endif

                            <td class="{{ $rowStyle }}" style="color: #4f46e5;">
                                {{ $gridData[$p]['slots'][$s]['nomor_pack_pengganti'] ?? '-' }}</td>

                            @if ($s == 1)
                                <td rowspan="4" class="cell-sp row-border-thick" style="font-size: 12pt; letter-spacing: 0.1em;">
                                    {{ $gridData[$p]['seri_pengganti'] ?? '' }}</td>
                            @endif

                            <td class="{{ $rowStyle }}" style="color: #4f46e5;">
                                {{ $gridData[$p]['slots'][$s]['nomor_bilyet_pengganti'] ?? '-' }}</td>
                        </tr>
                    @endfor
                @endfor
            </tbody>
        </table>
    </div>
</body>

</html>