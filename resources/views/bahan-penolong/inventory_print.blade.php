<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stok Bahan Penolong - {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY') }}</title>
    <!-- Tailwind Local -->
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
                margin: 0.5cm;
            }
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: #f9fafb;
            transition: background-color 0.3s;
        }

        .table-tight th,
        .table-tight td {
            padding: 8px 12px;
        }
    </style>
</head>

<body class="p-4 md:p-10">
    <div
        class="print-container max-w-5xl mx-auto bg-white p-8 border border-gray-100 shadow-sm rounded-3xl min-h-screen relative overflow-hidden">
        {{-- Dekorasi--}}
        <div class="absolute top-0 right-0 w-48 h-48 rounded-bl-[100px] opacity-[0.08] pointer-events-none"
            style="background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%);"></div>
        <div class="absolute top-0 right-0 w-24 h-24 rounded-bl-[50px] opacity-[0.12] pointer-events-none"
            style="background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%);"></div>

        <!-- Action Toolbar -->
        <div class="no-print flex justify-between items-center mb-8 pb-6 border-b border-gray-100">
            <a href="{{ route('bahan-penolong.persediaan') }}"
                class="text-sm font-bold text-gray-400 hover:text-indigo-600 flex items-center transition-all">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
            <button onclick="window.print()"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-2xl font-black text-sm shadow-xl shadow-indigo-100 transition-all flex items-center uppercase tracking-widest">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak / Simpan PDF
            </button>
        </div>

        <!-- Header -->
        <header class="flex justify-between items-start mb-10">
            <div>
                <h1 class="text-3xl font-black text-gray-900 mb-1 tracking-tight">STOK BAHAN PENOLONG</h1>
                <p class="text-[11px] text-gray-400 font-black uppercase tracking-[0.2em] mb-6">Khazprokhir Inventory
                    System</p>

                <div class="flex items-center gap-3">
                    <div class="bg-gray-50 px-4 py-2 rounded-xl border border-gray-100 flex items-center">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mr-3">Status:</span>
                        <span class="text-xs font-black text-gray-700 uppercase">Per Tanggal
                            {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY') }}</span>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-black mb-1">Dibuat Pada</p>
                <p class="text-xs font-black text-gray-900">{{ \Carbon\Carbon::now()->format('H:i') }} WIB</p>
            </div>
        </header>

        <!-- Table -->
        <div class="border border-gray-100 rounded-3xl overflow-hidden shadow-sm mb-10">
            <table class="w-full text-left border-collapse table-tight">
                <thead class="bg-gray-50/50 border-b border-gray-100">
                    <tr>
                        <th class="text-[10px] font-black text-gray-400 uppercase tracking-widest">No</th>
                        <th class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Material</th>
                        <th class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Kode</th>
                        <th class="text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Stok</th>
                        <th class="text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Min. Stok
                        </th>
                        <th class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Satuan</th>
                        <th class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($materials as $index => $m)
                        <tr>
                            <td class="text-xs font-bold text-gray-400">{{ $index + 1 }}</td>
                            <td class="text-xs font-black text-gray-900">{{ $m->nama_bahan }}</td>
                            <td class="text-[10px] font-mono font-bold text-gray-500 uppercase">
                                {{ $m->kode_material ?: '-' }}</td>
                            <td
                                class="text-sm font-black text-center {{ $m->stok <= $m->min_stok ? 'text-rose-600' : 'text-indigo-600' }}">
                                {{ number_format($m->stok) }}
                            </td>
                            <td class="text-xs font-bold text-gray-400 text-center">{{ number_format($m->min_stok) }}</td>
                            <td class="text-[10px] font-black text-gray-500 uppercase tracking-wider">{{ $m->satuan }}</td>
                            <td class="text-[10px] text-gray-400 italic leading-relaxed">{{ $m->keterangan ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-gray-400 text-xs italic">Tidak ada data material.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer / Signature -->
        <footer class="mt-20 pt-10 border-t border-dashed border-gray-200 grid grid-cols-2 gap-20">
            <div class="text-center">
                <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mb-20">Petugas Gudang</p>
                <div class="w-48 h-px bg-gray-200 mx-auto mb-3"></div>
                <p class="text-xs font-black text-gray-900 uppercase underline decoration-gray-200 underline-offset-4">
                    {{ auth()->user()->name }}</p>
            </div>
            <div class="text-center">
                <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mb-20">Supervisor / Admin</p>
                <div class="w-48 h-px bg-gray-200 mx-auto mb-3"></div>
                <p class="text-xs font-black text-gray-900 uppercase italic">( ................................ )</p>
            </div>
        </footer>

        {{-- Watermark --}}
        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 no-print">
            <p class="text-[8px] font-black text-gray-300 uppercase tracking-[0.4em]">Prophetical / Khazprokhir</p>
        </div>
    </div>

    <script>
        window.onload = function () {
            if (window.location.search.includes('autoprint')) {
                setTimeout(() => { window.print(); }, 500);
            }
        };
    </script>
</body>

</html>