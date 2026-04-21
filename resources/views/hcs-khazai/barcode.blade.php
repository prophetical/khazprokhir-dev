<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Barcode - {{ $registration->barcode_token }}</title>
    <style>
        @page {
            size: 80mm 50mm;
            margin: 0;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            margin: 0;
            padding: 5mm;
            text-align: center;
            font-size: 10px;
        }

        .container {
            border: 1px solid #000;
            padding: 5px;
            height: 35mm;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .header {
            font-weight: bold;
            font-size: 12px;
            border-bottom: 1px solid #000;
            margin-bottom: 5px;
            padding-bottom: 2px;
        }

        .barcode-svg {
            max-width: 100%;
            height: 15mm;
        }

        .details {
            display: grid;
            grid-template-cols: 1fr 1fr;
            text-align: left;
            font-size: 9px;
            margin-top: 5px;
        }

        .footer {
            font-size: 8px;
            border-top: 1px dashed #ccc;
            margin-top: 5px;
            padding-top: 2px;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="no-print"
        style="margin-bottom: 20px; display: flex; gap: 10px; justify-content: center; padding: 20px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
        <button onclick="window.print()"
            style="background: #4f46e5; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 800; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);">Cetak
            Barcode</button>
        <button
            onclick="if(window.opener) { window.close(); } else { window.location.href='{{ route('hcs-khazai-registration.index') }}'; }"
            style="background: white; color: #64748b; border: 1px solid #e2e8f0; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 800; font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Tutup</button>
    </div>

    <div class="container">
        <div class="header">BARCODE PENERIMAAN HCS</div>

        <svg id="barcode" class="barcode-svg"></svg>

        <div class="details">
            <div>NO BON : {{ $registration->nomor_bon }}</div>
            <div>TANGGAL : {{ $registration->tanggal_pembuatan->format('d/m/Y') }}</div>
            <div>BATCH/SERI : {{ $registration->batch }}/{{ $registration->seri }}</div>
            <div>PECAHAN : {{ $registration->pecahan }} ({{ number_format($registration->jumlah, 0, ',', '.') }})</div>
        </div>

        <div class="footer text-[8px]">
            Khazprokhir - {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

    <script src="{{ asset('js/vendor/JsBarcode.all.min.js') }}"></script>
    <script>
        JsBarcode("#barcode", "{{ $registration->barcode_token }}", {
            format: "CODE128",
            width: 2,
            height: 50,
            displayValue: true,
            fontSize: 14,
            font: "monospace"
        });

        // Auto print and close after a delay (optional)
        // window.onload = () => { window.print(); };
    </script>
</body>

</html>