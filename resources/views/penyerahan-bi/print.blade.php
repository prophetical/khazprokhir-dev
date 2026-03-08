<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penyerahan ke BI - {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY') }}</title>
    <script src="{{ asset('vendor/tailwindcss/tailwindcss.min.js') }}"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { padding: 0 !important; margin: 0 !important; background: white; }
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            @page { margin: 0.3cm; }
        }
        body { font-family: 'Inter', system-ui, sans-serif; background-color: #f9fafb; }
        .table-tight th, .table-tight td { padding: 5px 8px; }
    </style>
</head>
<body class="p-4 md:p-10">
    <div class="max-w-6xl mx-auto bg-white p-8 border border-gray-100 shadow-sm rounded-2xl min-h-screen relative overflow-hidden">
        {{-- Dekorasi pojok kanan atas --}}
        <div class="absolute top-0 right-0 w-40 h-40 rounded-bl-[80px] opacity-[0.08] pointer-events-none" style="background: linear-gradient(135deg, #1e40af 0%, #7c3aed 55%, #db2877 100%);"></div>
        <div class="absolute top-0 right-0 w-20 h-20 rounded-bl-[40px] opacity-[0.13] pointer-events-none" style="background: linear-gradient(135deg, #1e40af 0%, #7c3aed 55%, #db2877 100%);"></div>
        {{-- Dekorasi pojok kanan bawah --}}
        <div class="absolute bottom-0 right-0 w-40 h-40 rounded-tl-[80px] opacity-[0.05] pointer-events-none" style="background: linear-gradient(315deg, #1e40af 0%, #7c3aed 55%, #db2877 100%);"></div>
        <div class="absolute bottom-0 right-0 w-20 h-20 rounded-tl-[40px] opacity-[0.10] pointer-events-none" style="background: linear-gradient(315deg, #1e40af 0%, #7c3aed 55%, #db2877 100%);"></div>

        {{-- Toolbar --}}
        <div class="no-print flex justify-between items-center mb-8 pb-6 border-b border-gray-100">
            <a href="{{ route('penyerahan-bi.index', request()->all()) }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 flex items-center transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Laporan
            </a>
            <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-lg transition-all flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak / Simpan PDF
            </button>
        </div>

        {{-- Header --}}
        <header class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 mb-1 tracking-tight">LAPORAN PENYERAHAN KE BANK INDONESIA</h1>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-4">Khazprokhir Management System</p>

                {{-- Filter context badges --}}
                <div class="flex flex-wrap items-center gap-2 text-xs mb-4">
                    @if(request('pecahan'))
                        <div class="bg-indigo-50 px-3 py-1.5 rounded-lg border border-indigo-100 flex items-center">
                            <span class="text-indigo-400 mr-1">Pecahan:</span>
                            <span class="font-bold text-indigo-700">{{ request('pecahan') }}</span>
                        </div>
                    @endif
                    @if(request('tahun_anggaran'))
                        <div class="bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100 flex items-center">
                            <span class="text-blue-400 mr-1">TA:</span>
                            <span class="font-bold text-blue-700">{{ request('tahun_anggaran') }}</span>
                        </div>
                    @endif
                    @if(request('nomor_ba'))
                        <div class="bg-amber-50 px-3 py-1.5 rounded-lg border border-amber-100 flex items-center">
                            <span class="text-amber-400 mr-1">BA:</span>
                            <span class="font-bold text-amber-700 font-mono">{{ request('nomor_ba') }}</span>
                        </div>
                    @endif
                    @if(request('tanggal_awal') || request('tanggal_akhir'))
                        <div class="bg-rose-50 px-3 py-1.5 rounded-lg border border-rose-100 flex items-center">
                            <span class="text-rose-400 mr-1">Periode:</span>
                            <span class="font-bold text-rose-700">
                                {{ request('tanggal_awal') ? \Carbon\Carbon::parse(request('tanggal_awal'))->format('d/m/Y') : '...' }}
                                –
                                {{ request('tanggal_akhir') ? \Carbon\Carbon::parse(request('tanggal_akhir'))->format('d/m/Y') : '...' }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
            <div class="text-right">
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Dicetak Pada</p>
                <p class="text-xs font-bold text-gray-700">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }}</p>
            </div>
        </header>

        {{-- Tabel --}}
        <div class="mb-10">
            <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Rincian Penyerahan</h2>
            <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <table class="w-full text-left border-collapse table-tight">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-[10px] font-bold text-gray-500 uppercase">Tanggal</th>
                            <th class="text-[10px] font-bold text-gray-500 uppercase">Nomor BA</th>
                            <th class="text-[10px] font-bold text-gray-500 uppercase text-center">Pch</th>
                            <th class="text-[10px] font-bold text-gray-500 uppercase text-center">TA</th>
                            <th class="text-[10px] font-bold text-gray-500 uppercase text-center">TE</th>
                            <th class="text-[10px] font-bold text-gray-500 uppercase text-center">Rentang Dus</th>
                            <th class="text-[10px] font-bold text-gray-500 uppercase text-right">Jml Dus</th>
                            <th class="text-[10px] font-bold text-gray-500 uppercase text-right">Jml Bilyet</th>
                            <th class="text-[10px] font-bold text-gray-500 uppercase text-center">Status</th>
                            <th class="text-[10px] font-bold text-gray-500 uppercase">Petugas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @php
                            $colorMap = ['S'=>'bg-lime-500','T'=>'bg-gray-500','U'=>'bg-amber-500','V'=>'bg-purple-500','W'=>'bg-green-500','X'=>'bg-blue-500','Y'=>'bg-red-500'];
                        @endphp
                        @forelse($penyerahans as $row)
                            <tr>
                                <td class="text-[10px] font-medium text-gray-600">{{ \Carbon\Carbon::parse($row->tanggal_penyerahan)->format('d/m/Y') }}</td>
                                <td class="text-[10px] font-bold text-gray-900 font-mono">{{ $row->nomor_ba }}</td>
                                <td class="text-center">
                                    <span class="{{ $colorMap[$row->pecahan] ?? 'bg-gray-500' }} text-white text-[9px] font-bold px-1.5 py-0.5 rounded">{{ $row->pecahan }}</span>
                                </td>
                                <td class="text-[10px] text-center font-bold text-gray-700">{{ $row->tahun_anggaran }}</td>
                                <td class="text-[10px] text-center font-bold text-gray-600">{{ $row->tahun_emisi }}</td>
                                <td class="text-[10px] font-medium text-gray-600 text-center bg-indigo-50/50 rounded px-2">{{ $row->nomor_dus_awal }} – {{ $row->nomor_dus_akhir }}</td>
                                <td class="text-[10px] font-bold text-purple-700 text-right">{{ number_format($row->jumlah_dus, 0, ',', '.') }}</td>
                                <td class="text-[10px] font-bold text-emerald-600 text-right">{{ number_format($row->jumlah_bilyet, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    @if($row->status_data === 'Lengkap')
                                        <span class="text-[9px] font-bold text-green-700 bg-green-100 px-1.5 py-0.5 rounded">✓ Lengkap</span>
                                    @else
                                        <span class="text-[9px] font-bold text-amber-700 bg-amber-100 px-1.5 py-0.5 rounded">⚠ Belum</span>
                                    @endif
                                </td>
                                <td class="text-[10px] text-gray-600">{{ $row->user->name ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-10 text-gray-400 text-sm italic">Tidak ada data ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-gray-50 border-t border-gray-200">
                        <tr>
                            <td colspan="6" class="text-[10px] font-bold text-gray-700 uppercase p-3 text-right">Total</td>
                            <td class="text-[10px] font-extrabold text-purple-700 text-right p-3">{{ number_format($penyerahans->sum('jumlah_dus'), 0, ',', '.') }}</td>
                            <td class="text-[10px] font-extrabold text-emerald-600 text-right p-3">{{ number_format($penyerahans->sum('jumlah_bilyet'), 0, ',', '.') }}</td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Footer --}}
        <footer class="mt-20 pt-10 border-t border-dashed border-gray-200 grid grid-cols-2 gap-20">
            <div class="text-center">
                <p class="text-[10px] text-gray-400 uppercase font-bold mb-16">Penanggung Jawab</p>
                <div class="w-40 h-px bg-gray-200 mx-auto mb-2"></div>
                <p class="text-sm font-bold text-gray-800">( ........................................ )</p>
            </div>
            <div class="text-center">
                <p class="text-[10px] text-gray-400 uppercase font-bold mb-16">Kepala Seksi</p>
                <div class="w-40 h-px bg-gray-200 mx-auto mb-2"></div>
                <p class="text-sm font-bold text-gray-800">( ........................................ )</p>
            </div>
        </footer>
    </div>

    @if(request()->has('autoprint'))
    <script>window.onload = function() { setTimeout(() => window.print(), 500); };</script>
    @endif
</body>
</html>
