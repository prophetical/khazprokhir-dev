<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penyablonan Dus - {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY') }}</title>
    <!-- Tailwind Lokal -->
    <script src="{{ asset('vendor/tailwindcss/tailwindcss.min.js') }}"></script>
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                padding: 0 !important;
                margin: 0 !important;
                background: white;
            }

            .print-container {
                width: 100% !important;
                max-width: none !important;
                border: none !important;
                shadow: none !important;
                padding: 0.2cm !important;
                border-radius: 0 !important;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            @page {
                margin: 0.2cm;
                size: landscape;
            }
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: #f9fafb;
        }

        .table-tight th,
        .table-tight td {
            padding: 8px 12px;
        }
    </style>
</head>

<body class="p-4 md:p-10">
    <div class="print-container max-w-full mx-auto bg-white p-8 border border-gray-100 shadow-sm rounded-2xl min-h-screen relative overflow-hidden">
        {{-- Dekorasi pojok kanan atas --}}
        <div class="absolute top-0 right-0 w-40 h-40 rounded-bl-[80px] opacity-[0.08] pointer-events-none"
            style="background: linear-gradient(135deg, #1e40af 0%, #7c3aed 55%, #db2877 100%);"></div>
        <div class="absolute top-0 right-0 w-20 h-20 rounded-bl-[40px] opacity-[0.13] pointer-events-none"
            style="background: linear-gradient(135deg, #1e40af 0%, #7c3aed 55%, #db2877 100%);"></div>

        <!-- Bilah Alat Aksi (Tersembunyi saat Cetak) -->
        <div class="no-print flex justify-between items-center mb-8 pb-6 border-b border-gray-100">
            <a href="{{ route('penyablonan.laporan', ['tanggal' => $tanggal, 'gilir' => $gilir]) }}"
                class="text-sm font-medium text-gray-500 hover:text-indigo-600 flex items-center transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Dashboard Laporan
            </a>
            <button onclick="window.print()"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-indigo-100 transition-all flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak Laporan / Simpan PDF
            </button>
        </div>

        <!-- Header Section -->
        <header class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 mb-1 tracking-tight uppercase">Laporan Penyablonan Dus HCS</h1>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-4">Seksi Khazanah Produk Akhir - Khazprokhir</p>

                <!-- Filter Context -->
                <div class="flex flex-wrap items-center gap-3 text-xs">
                    <div class="bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100 flex items-center">
                        <span class="text-gray-400 mr-2 uppercase font-bold text-[9px]">Tanggal:</span>
                        <span class="font-bold text-gray-700">
                            {{ $tanggal ? \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('D MMMM YYYY') : 'Semua Tanggal' }}
                        </span>
                    </div>
                    @if($gilir)
                    <div class="bg-indigo-50 px-3 py-1.5 rounded-lg border border-indigo-100 flex items-center">
                        <span class="text-indigo-400 mr-2 uppercase font-bold text-[9px]">Shift:</span>
                        <span class="font-bold text-indigo-700 uppercase">{{ $gilir }}</span>
                    </div>
                    @endif
                    <div class="bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-100 flex items-center">
                        <span class="text-emerald-400 mr-2 uppercase font-bold text-[9px]">Persediaan Blanko:</span>
                        <span class="font-bold text-emerald-700 uppercase">{{ number_format($sisaStok, 0, ',', '.') }} Pcs</span>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Dicetak Pada</p>
                <p class="text-xs font-bold text-gray-700">{{ \Carbon\Carbon::now()->format('d F Y, H:i') }}</p>
            </div>
        </header>

        <!-- Tabel Laporan Rinci -->
        <div class="mb-10">
            <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <table class="w-full text-left border-collapse table-tight">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-[10px] font-bold text-gray-500 uppercase">Tanggal</th>
                            <th class="text-[10px] font-bold text-gray-500 uppercase text-center">Shift</th>
                            <th class="text-[10px] font-bold text-gray-500 uppercase text-center">Pec</th>
                            <th class="text-[10px] font-bold text-gray-500 uppercase text-center">Emisi</th>
                            <th class="text-[10px] font-bold text-gray-500 uppercase text-center">TA</th>
                            <th class="text-[10px] font-bold text-gray-500 uppercase text-center">No Dus Awal</th>
                            <th class="text-[10px] font-bold text-gray-500 uppercase text-center">No Dus Akhir</th>
                            <th class="text-[10px] font-bold text-gray-500 uppercase text-right">Jumlah (Dus)</th>
                            <th class="text-[10px] font-bold text-gray-500 uppercase">Petugas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @php
                            $pecahanColors = [
                                'S' => 'text-lime-600',
                                'T' => 'text-gray-500',
                                'U' => 'text-amber-500',
                                'V' => 'text-purple-600',
                                'W' => 'text-emerald-600',
                                'X' => 'text-blue-600',
                                'Y' => 'text-red-600',
                            ];
                        @endphp
                        @forelse($data as $row)
                            <tr>
                                <td class="text-xs font-bold text-gray-900">{{ $row->tanggal->locale('id')->isoFormat('D MMMM YYYY') }}</td>
                                <td class="text-center text-[10px] font-bold text-gray-500 uppercase">{{ $row->gilir }}</td>
                                <td class="text-center">
                                    <span class="font-black {{ $pecahanColors[$row->pecahan] ?? 'text-gray-900' }}">{{ $row->pecahan }}</span>
                                </td>
                                <td class="text-center text-xs font-bold text-gray-700">{{ $row->te }}</td>
                                <td class="text-center text-xs font-bold text-gray-700">{{ $row->ta }}</td>
                                <td class="text-center text-xs font-black text-indigo-600 italic">{{ $row->no_awal }}</td>
                                <td class="text-center text-xs font-black text-indigo-600 italic">{{ $row->no_akhir }}</td>
                                <td class="text-right text-xs font-black text-gray-900">{{ number_format($row->jumlah, 0, ',', '.') }}</td>
                                <td class="text-[10px] font-medium text-gray-400 uppercase italic">{{ $row->user->name ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-10 text-gray-400 text-sm italic">Tidak ada data ditemukan untuk periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-gray-50 border-t border-gray-200">
                        <tr>
                            <th colspan="7" class="text-xs font-bold text-gray-700 uppercase p-3 text-right">Total Produksi Penyablonan</th>
                            <td class="text-xs font-black text-indigo-700 text-right p-3">{{ number_format($data->sum('jumlah'), 0, ',', '.') }} Dus</td>
                            <td class="bg-gray-50"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Footer / Approval -->
        <footer class="mt-20 pt-10 border-t border-dashed border-gray-200 grid grid-cols-2 gap-20">
            <div class="text-center">
                <p class="text-[10px] text-gray-400 uppercase font-bold mb-16">Dibuat Oleh,</p>
                <div class="w-40 h-px bg-gray-200 mx-auto mb-2"></div>
                <p class="text-sm font-bold text-gray-800 italic">( ........................................ )</p>
                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tight">Operator Penyablonan</p>
            </div>
            <div class="text-center">
                <p class="text-[10px] text-gray-400 uppercase font-bold mb-16">Mengetahui,</p>
                <div class="w-40 h-px bg-gray-200 mx-auto mb-2"></div>
                <p class="text-sm font-bold text-gray-800 italic">( ........................................ )</p>
                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tight">Kepala Seksi</p>
            </div>
        </footer>

    </div>

    <!-- Pemicu cetak otomatis jika diminta melalui parameter query -->
    <script>
        window.onload = function () {
            // Uncomment to auto print on load
            // setTimeout(() => { window.print(); }, 500);
        };
    </script>
</body>

</html>
