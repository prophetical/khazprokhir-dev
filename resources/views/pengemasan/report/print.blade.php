<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pengemasan HCS - {{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('D MMMM YYYY') }}</title>
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
                padding: 0.5cm !important;
                border-radius: 0 !important;
            }

            /* Paksa warna latar belakang saat mencetak */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            @page {
                size: A4 landscape;
                margin: 0.5cm;
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
        
        /* Custom font smoothing */
        * {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
    </style>
</head>

<body class="p-4 md:p-10">
    <div
        class="print-container max-w-[29.7cm] mx-auto bg-white p-10 border border-gray-100 shadow-sm rounded-2xl min-h-[21cm] relative overflow-hidden">
        
        {{-- Dekorasi pojok kanan atas --}}
        <div class="absolute top-0 right-0 w-64 h-64 rounded-bl-[120px] opacity-[0.08] pointer-events-none"
            style="background: linear-gradient(135deg, #1e40af 0%, #7c3aed 55%, #db2877 100%);"></div>
        <div class="absolute top-0 right-0 w-32 h-32 rounded-bl-[60px] opacity-[0.13] pointer-events-none"
            style="background: linear-gradient(135deg, #1e40af 0%, #7c3aed 55%, #db2877 100%);"></div>
        
        {{-- Dekorasi pojok kiri bawah --}}
        <div class="absolute bottom-0 left-0 w-64 h-64 rounded-tr-[120px] opacity-[0.05] pointer-events-none"
            style="background: linear-gradient(315deg, #1e40af 0%, #7c3aed 55%, #db2877 100%);"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 rounded-tr-[60px] opacity-[0.10] pointer-events-none"
            style="background: linear-gradient(315deg, #1e40af 0%, #7c3aed 55%, #db2877 100%);"></div>

        <!-- Bilah Alat Aksi (Tersembunyi saat Cetak) -->
        <div class="no-print flex justify-between items-center mb-10 pb-6 border-b border-gray-100">
            <a href="{{ route('pengemasan.report.index', ['tanggal' => $tanggal, 'gilir' => $gilir]) }}"
                class="text-sm font-medium text-gray-500 hover:text-indigo-600 flex items-center transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Dashboard
            </a>
            <button onclick="window.print()"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-indigo-100 transition-all flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak Laporan / Simpan PDF
            </button>
        </div>

        <!-- Header Section -->
        <header class="flex justify-between items-start mb-12">
            <div>
                <h1 class="text-3xl font-black text-gray-900 mb-1 tracking-tight">LAPORAN PENGEMASAN HARIAN</h1>
                <h2 class="text-lg font-bold text-gray-400 uppercase tracking-widest mb-6">SEKSI KHAZANAH PRODUK AKHIR</h2>

                <!-- Filter Context -->
                <div class="flex flex-wrap items-center gap-3 text-xs mb-4">
                    <div class="bg-gray-50 px-4 py-2 rounded-xl border border-gray-100 flex items-center shadow-sm">
                        <span class="text-gray-400 font-bold uppercase text-[9px] tracking-wider mr-3">Tanggal Laporan:</span>
                        <span class="font-black text-gray-700">
                            {{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                        </span>
                    </div>
                    @if($gilir)
                    <div class="bg-indigo-50 px-4 py-2 rounded-xl border border-indigo-100 flex items-center shadow-sm">
                        <span class="text-indigo-400 font-bold uppercase text-[9px] tracking-wider mr-3">Gilir / Shift:</span>
                        <span class="font-black text-indigo-700">GILIR {{ $gilir }}</span>
                    </div>
                    @endif
                </div>
            </div>
            <div class="text-right">
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-black mb-1">Generated At</p>
                <p class="text-xs font-black text-gray-800">{{ \Carbon\Carbon::now()->format('d F Y, H:i') }}</p>
                <div class="mt-2 text-[9px] font-bold text-gray-300 uppercase italic">Khazprokhir Management System</div>
            </div>
        </header>

        <!-- Tabel Laporan Rinci -->
        <div class="mb-12">
            <div class="border border-gray-200 rounded-2xl overflow-hidden shadow-sm overflow-x-auto">
                <table class="w-full text-left border-collapse table-tight">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-[10px] font-black text-gray-400 uppercase tracking-wider py-4">Pecahan</th>
                            <th class="text-[10px] font-black text-gray-400 uppercase tracking-wider py-4 text-center">TA</th>
                            <th class="text-[10px] font-black text-gray-400 uppercase tracking-wider py-4 text-center">TE</th>
                            <th class="text-[10px] font-black text-gray-400 uppercase tracking-wider py-4 text-center">No Dus Awal</th>
                            <th class="text-[10px] font-black text-gray-400 uppercase tracking-wider py-4 text-center">No Dus Akhir</th>
                            <th class="text-[10px] font-black text-gray-400 uppercase tracking-wider py-4 text-right">Jumlah Bilyet</th>
                            <th class="text-[10px] font-black text-gray-400 uppercase tracking-wider py-4 text-right">Jumlah Dus</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @php
                            $pecahanMeta = [
                                'S' => ['color' => 'bg-stone-600', 'label' => 'Rp1.000'],
                                'T' => ['color' => 'bg-slate-500', 'label' => 'Rp2.000'],
                                'U' => ['color' => 'bg-orange-500', 'label' => 'Rp5.000'],
                                'V' => ['color' => 'bg-purple-600', 'label' => 'Rp10.000'],
                                'W' => ['color' => 'bg-green-600', 'label' => 'Rp20.000'],
                                'X' => ['color' => 'bg-blue-600', 'label' => 'Rp50.000'],
                                'Y' => ['color' => 'bg-red-600', 'label' => 'Rp100.000'],
                            ];
                        @endphp
                        @forelse($reports as $row)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-2">
                                        <div class="{{ $pecahanMeta[$row['pecahan']]['color'] ?? 'bg-gray-500' }} w-8 h-8 rounded-lg flex items-center justify-center text-white font-black text-xs shadow-sm">
                                            {{ $row['pecahan'] }}
                                        </div>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase">{{ $pecahanMeta[$row['pecahan']]['label'] ?? '' }}</span>
                                    </div>
                                </td>
                                <td class="text-center py-3">
                                    <span class="text-sm font-black text-gray-700">{{ $row['ta'] }}</span>
                                </td>
                                <td class="text-center py-3">
                                    <span class="text-sm font-black text-gray-400">{{ $row['te'] }}</span>
                                </td>
                                <td class="text-center py-3">
                                    <span class="text-sm font-black text-gray-900 bg-gray-50 px-3 py-1 rounded-lg border border-gray-100">{{ number_format($row['nomor_dus_awal'], 0, ',', '.') }}</span>
                                </td>
                                <td class="text-center py-3">
                                    <span class="text-sm font-black text-gray-900 bg-gray-50 px-3 py-1 rounded-lg border border-gray-100">{{ number_format($row['nomor_dus_akhir'], 0, ',', '.') }}</span>
                                </td>
                                <td class="text-right py-3">
                                    <span class="text-sm font-black text-emerald-600 tracking-tight">{{ number_format($row['jumlah_bilyet'], 0, ',', '.') }}</span>
                                </td>
                                <td class="text-right py-3">
                                    <div class="flex flex-col items-end">
                                        <span class="text-sm font-black text-indigo-700">{{ number_format($row['jumlah_dus'], 0, ',', '.') }}</span>
                                        <span class="text-[8px] font-bold text-gray-400 uppercase tracking-tighter">Box / Dus</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-20">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                        </svg>
                                        <p class="text-sm font-bold text-gray-400 italic">Tidak ada data ditemukan untuk periode ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if(count($reports) > 0)
                        <tfoot class="bg-gray-50/80 border-t-2 border-gray-200">
                            <tr>
                                <th colspan="5" class="text-[11px] font-black text-gray-700 uppercase p-4 text-right tracking-widest">Grand Total Pengemasan</th>
                                <td class="text-sm font-black text-emerald-700 text-right p-4 border-l border-gray-100">
                                    {{ number_format(collect($reports)->sum('jumlah_bilyet'), 0, ',', '.') }}
                                </td>
                                <td class="text-sm font-black text-indigo-800 text-right p-4 bg-indigo-50/30">
                                    {{ number_format(collect($reports)->sum('jumlah_dus'), 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>

        <!-- Footer / Signature Area -->
        <footer class="mt-20 pt-10 border-t border-dashed border-gray-200">
            <div class="grid grid-cols-2 gap-20">
                <div class="text-center">
                    <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mb-20 px-10">Petugas Pembuat DIK</p>
                    <div class="relative inline-block px-10">
                        <p class="text-base font-black text-gray-900 uppercase min-w-[200px] border-b-2 border-gray-900 pb-1">
                            {{ $petugas ?: '( ........................................ )' }}
                        </p>
                        <span class="text-[8px] font-bold text-gray-400 uppercase tracking-widest block mt-2 text-center">Nama Lengkap & Tanda Tangan</span>
                    </div>
                </div>
                <div class="text-center">
                    <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mb-20 px-10">Penanggung Jawab</p>
                    <div class="relative inline-block px-10">
                        <p class="text-base font-black text-gray-900 uppercase min-w-[200px] border-b-2 border-gray-900 pb-1">
                            {{ $penanggung_jawab ?: '( ........................................ )' }}
                        </p>
                        <span class="text-[8px] font-bold text-gray-400 uppercase tracking-widest block mt-2 text-center">Nama Lengkap & Tanda Tangan</span>
                    </div>
                </div>
            </div>
            
            <div class="mt-20 flex justify-between items-center text-[8px] font-bold text-gray-300 uppercase tracking-[0.2em] italic">
                <div>Source: Khazprokhir Database Extraction</div>
                <div>Internal Document - Strictly Confidential</div>
            </div>
        </footer>

    </div>

    <!-- Pemicu cetak otomatis jika diminta melalui parameter query -->
    @if(request()->has('autoprint'))
        <script>
            window.onload = function () {
                setTimeout(() => { window.print(); }, 500);
            };
        </script>
    @endif
</body>

</html>
