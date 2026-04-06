<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rekomendasi Penerimaan HCS - {{ \Carbon\Carbon::now()->format('d/m/Y') }}</title>
    <!-- Tailwind Lokal -->
    <script src="{{ asset('vendor/tailwindcss/tailwindcss.min.js') }}"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { padding: 0 !important; margin: 0 !important; background: white; }
            .print-container { width: 100% !important; max-width: none !important; border: none !important; shadow: none !important; padding: 0.2cm !important; border-radius: 0 !important; }
            /* Paksa warna latar belakang saat cetak */
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            @page { margin: 0.2cm; }
        }
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; background-color: #f9fafb; transition: all 0.3s ease; }
        .table-tight th, .table-tight td { padding: 8px 12px; }
    </style>
</head>
<body class="p-4 md:p-10">
    <div class="print-container max-w-5xl mx-auto bg-white p-8 border border-gray-100 shadow-sm rounded-2xl min-h-screen relative overflow-hidden">
        {{-- Dekorasi pojok kanan atas --}}
        <div class="absolute top-0 right-0 w-40 h-40 rounded-bl-[80px] opacity-[0.08] pointer-events-none" style="background: linear-gradient(135deg, #1e40af 0%, #7c3aed 55%, #db2877 100%);"></div>
        <div class="absolute top-0 right-0 w-20 h-20 rounded-bl-[40px] opacity-[0.13] pointer-events-none" style="background: linear-gradient(135deg, #1e40af 0%, #7c3aed 55%, #db2877 100%);"></div>
        {{-- Dekorasi pojok kanan bawah --}}
        <div class="absolute bottom-0 right-0 w-40 h-40 rounded-tl-[80px] opacity-[0.05] pointer-events-none" style="background: linear-gradient(315deg, #1e40af 0%, #7c3aed 55%, #db2877 100%);"></div>
        <div class="absolute bottom-0 right-0 w-20 h-20 rounded-tl-[40px] opacity-[0.10] pointer-events-none" style="background: linear-gradient(315deg, #1e40af 0%, #7c3aed 55%, #db2877 100%);"></div>

        <!-- Toolbar Aksi (Tersembunyi saat Cetak) -->
        <div class="no-print flex justify-between items-center mb-8 pb-6 border-b border-gray-100">
            <a href="{{ route('rekomendasi-penerimaan.index', request()->all()) }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 flex items-center transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali ke Dashboard
            </a>
            <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-indigo-100 transition-all flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 00-2 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                Cetak Laporan / Simpan PDF
            </button>
        </div>

        <!-- Header Section -->
        <header class="flex justify-between items-start mb-10">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 mb-1 tracking-tight">LAPORAN REKOMENDASI PENERIMAAN</h1>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-6">Khazprokhir Management System</p>
                
                @php
                    $colorMap = [
                        'S' => 'bg-lime-500 border-lime-600 text-white',
                        'T' => 'bg-gray-400 border-gray-500 text-white',
                        'U' => 'bg-amber-400 border-amber-500 text-white',
                        'V' => 'bg-purple-500 border-purple-600 text-white',
                        'W' => 'bg-green-500 border-green-600 text-white',
                        'X' => 'bg-blue-500 border-blue-600 text-white',
                        'Y' => 'bg-red-500 border-red-600 text-white',
                    ];
                @endphp

                <!-- Konteks Filter & Keterangan -->
                <div class="flex items-center justify-between mt-6">
                    <div class="flex items-center space-x-3 text-xs">
                        @if(request('pecahan'))
                        <div class="bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100 flex items-center">
                            <span class="text-gray-400 mr-2">Pecahan:</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $colorMap[request('pecahan')] ?? 'bg-indigo-600 text-white' }}">
                                {{ request('pecahan') }}
                            </span>
                        </div>
                        @endif
                        @if(request('batch'))
                        <div class="bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-100 flex items-center">
                            <span class="text-emerald-400 mr-2">Batch:</span>
                            <span class="font-bold text-emerald-700 uppercase">{{ request('batch') }}</span>
                        </div>
                        @endif
                        @if(request('seri'))
                        <div class="bg-amber-50 px-3 py-1.5 rounded-lg border border-amber-100 flex items-center">
                            <span class="text-amber-400 mr-2">Seri:</span>
                            <span class="font-bold text-amber-700 uppercase italic">{{ request('seri') }}</span>
                        </div>
                        @endif
                    </div>

                    <!-- Keterangan -->
                    <div class="flex items-center gap-4 bg-gray-50/50 px-4 py-1.5 rounded-lg border border-gray-100">
                        <span class="text-[9px] font-black uppercase tracking-widest text-gray-400">Keterangan:</span>
                        <div class="flex items-center gap-2">
                            <div class="w-2.5 h-2.5 rounded bg-emerald-500 border border-emerald-600"></div>
                            <span class="text-[9px] font-bold text-gray-600 uppercase tracking-tighter">Pack Cutpack</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-2.5 h-2.5 rounded bg-sky-500 border border-sky-600"></div>
                            <span class="text-[9px] font-bold text-gray-600 uppercase tracking-tighter">Pack Rikyet</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Dicetak Pada</p>
                <p class="text-xs font-bold text-gray-700">{{ \Carbon\Carbon::now()->format('d F Y, H:i') }}</p>
            </div>
        </header>

        <!-- Konten Utama -->
        <div class="mb-12">
            <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Daftar Rekomendasi Pack</h2>
            <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <table class="w-full text-left border-collapse table-tight">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="py-3 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Pec/Batch/Seri</th>
                            <th class="py-3 text-[10px] font-bold text-gray-500 uppercase tracking-widest text-center">TA/Emisi</th>
                            <th class="py-3 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Pack Existing</th>
                            <th class="py-3 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Rekomendasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($batches as $batch)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold border {{ $colorMap[$batch->pecahan] ?? 'bg-gray-500 text-white' }}">
                                        {{ $batch->pecahan }}
                                    </span>
                                    <span class="text-xs font-bold text-gray-900">{{ $batch->batch }}</span>
                                    <span class="text-[10px] text-gray-400 italic">/ {{ $batch->seri }}</span>
                                </div>
                            </td>
                            <td class="py-4 text-center">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-gray-800">{{ $batch->tahun_anggaran }}</span>
                                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">Em{{ $batch->emisi }}</span>
                                </div>
                            </td>
                            <td class="py-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($batch->existing_unsorted_single as $pack)
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded shadow-sm text-[9px] font-bold border {{ $pack['supplier'] === 'Cutpack' ? 'bg-emerald-500 border-emerald-600 text-white' : 'bg-sky-500 border-sky-600 text-white' }}">
                                            {{ $pack['number'] }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="py-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($batch->recommended_packs as $pack)
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded shadow-sm text-[9px] font-bold border {{ $pack['supplier'] === 'Cutpack' ? 'bg-emerald-500 border-emerald-600 text-white' : 'bg-sky-500 border-sky-600 text-white' }}">
                                            {{ $pack['number'] }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-gray-400 font-medium italic">Tidak ada data rekomendasi untuk kriteria ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer / Persetujuan -->
        <footer class="mt-auto pt-10 border-t border-dashed border-gray-200 grid grid-cols-2 gap-20">
            <div class="text-center">
                <p class="text-[10px] text-gray-400 uppercase font-bold mb-16">Penanggung Jawab</p>
                <div class="w-40 h-px bg-gray-200 mx-auto mb-2"></div>
                <p class="text-xs font-bold text-gray-800">( ........................................ )</p>
            </div>
            <div class="text-center">
                <p class="text-[10px] text-gray-400 uppercase font-bold mb-16">Kepala Seksi</p>
                <div class="w-40 h-px bg-gray-200 mx-auto mb-2"></div>
                <p class="text-xs font-bold text-gray-800">( ........................................ )</p>
            </div>
        </footer>
    </div>

    <script>
        // Cetak otomatis jika diminta
        if (window.location.search.indexOf('autoprint=1') > -1) {
            window.onload = function() {
                setTimeout(() => { window.print(); }, 500);
            };
        }
    </script>
</body>
</html>
