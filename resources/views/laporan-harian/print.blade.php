<x-guest-layout>
    <div class="bg-white min-h-screen p-8 text-gray-900 relative overflow-hidden print-container">
        {{-- Gradient Accents for Print --}}
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-400 to-purple-500 opacity-20 rounded-bl-full print-accent"></div>
        <div class="absolute bottom-0 right-0 w-48 h-48 bg-gradient-to-tl from-purple-500 to-pink-500 opacity-10 rounded-tl-full print-accent"></div>

        <div class="max-w-5xl mx-auto">
            {{-- Header --}}
            <div class="flex justify-between items-start border-b-2 border-gray-900 pb-4 mb-6">
                <div>
                    <h1 class="text-3xl font-black uppercase tracking-tighter text-gray-900">LAPORAN HARIAN TERINTEGRASI</h1>
                    <p class="text-sm font-bold text-gray-500 mt-1 uppercase tracking-widest">Sistem Pengelolaan HCS — Khazprokhir</p>
                </div>
                <div class="text-right">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Tanggal Laporan</p>
                    <p class="text-lg font-black text-gray-900">{{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</p>
                </div>
            </div>

            {{-- Filter Info --}}
            <div class="flex gap-4 mb-8">
                @if($pecahan)
                    <div class="bg-gray-100 px-3 py-1 rounded border border-gray-200">
                        <span class="text-[10px] font-bold text-gray-400 uppercase block">Pecahan</span>
                        <span class="text-sm font-black text-gray-900">{{ $pecahan }}</span>
                    </div>
                @endif
                @if($tahunAnggaran)
                    <div class="bg-gray-100 px-3 py-1 rounded border border-gray-200">
                        <span class="text-[10px] font-bold text-gray-400 uppercase block">Tahun Anggaran</span>
                        <span class="text-sm font-black text-gray-900">{{ $tahunAnggaran }}</span>
                    </div>
                @endif
                @if($tahunEmisi)
                    <div class="bg-gray-100 px-3 py-1 rounded border border-gray-200">
                        <span class="text-[10px] font-bold text-gray-400 uppercase block">Tahun Emisi</span>
                        <span class="text-sm font-black text-gray-900">{{ $tahunEmisi }}</span>
                    </div>
                @endif
            </div>

            {{-- Table Ringkasan --}}
            <div class="mb-10">
                <h2 class="text-sm font-black uppercase tracking-widest mb-3 flex items-center gap-2">
                    <span class="w-2 h-4 bg-gray-900"></span>
                    Ringkasan Per Pecahan
                </h2>
                <table class="w-full border-collapse border-t-2 border-b-2 border-gray-900">
                    <thead>
                        <tr class="bg-gray-50 text-[10px] font-black uppercase text-gray-500 tracking-wider border-b border-gray-200">
                            <th class="px-3 py-2 text-left border-r border-gray-100">Pecahan</th>
                            <th class="px-3 py-2 text-right border-r border-gray-100 bg-blue-50/30">Terima (Bilyet)</th>
                            <th class="px-3 py-2 text-right border-r border-gray-100 bg-purple-50/30">Sortir (Pack)</th>
                            <th class="px-3 py-2 text-right border-r border-gray-100 bg-purple-50/30">Sortir (Bilyet)</th>
                            <th class="px-3 py-2 text-right border-r border-gray-100 bg-green-50/30">Kemas (Pack)</th>
                            <th class="px-3 py-2 text-right border-r border-gray-100 bg-green-50/30">Kemas (Dus)</th>
                            <th class="px-3 py-2 text-right border-r border-gray-100 bg-rose-50/30">Serah BI (Bilyet)</th>
                            <th class="px-3 py-2 text-right bg-rose-50/30">Serah BI (Dus)</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs font-bold divide-y divide-gray-100">
                        @foreach(['S', 'T', 'U', 'V', 'W', 'X', 'Y'] as $p)
                            @php
                                $terima = $penerimaanPerPecahan[$p];
                                $sortir = $sortirPerPecahan[$p];
                                $kemas  = $pengemasanPerPecahan[$p];
                                $serah  = $penyerahanPerPecahan[$p];
                                $any = $terima['jumlah'] > 0 || $sortir['jumlah_bilyet'] > 0 || $kemas['jumlah_pack'] > 0 || $serah['jumlah_bilyet'] > 0;
                            @endphp
                            <tr class="{{ $any ? '' : 'text-gray-300' }}">
                                <td class="px-3 py-1.5 border-r border-gray-100 font-black text-gray-900 bg-gray-50/50">{{ $p }}</td>
                                <td class="px-3 py-1.5 text-right border-r border-gray-100">{{ $terima['jumlah'] > 0 ? number_format($terima['jumlah'], 0, ',', '.') : '—' }}</td>
                                <td class="px-3 py-1.5 text-right border-r border-gray-100">{{ $sortir['jumlah_pack'] > 0 ? number_format($sortir['jumlah_pack'], 0, ',', '.') : '—' }}</td>
                                <td class="px-3 py-1.5 text-right border-r border-gray-100">{{ $sortir['jumlah_bilyet'] > 0 ? number_format($sortir['jumlah_bilyet'], 0, ',', '.') : '—' }}</td>
                                <td class="px-3 py-1.5 text-right border-r border-gray-100">{{ $kemas['jumlah_pack'] > 0 ? number_format($kemas['jumlah_pack'], 0, ',', '.') : '—' }}</td>
                                <td class="px-3 py-1.5 text-right border-r border-gray-100">{{ $kemas['jumlah_dus'] > 0 ? number_format($kemas['jumlah_dus'], 0, ',', '.') : '—' }}</td>
                                <td class="px-3 py-1.5 text-right border-r border-gray-100">{{ $serah['jumlah_bilyet'] > 0 ? number_format($serah['jumlah_bilyet'], 0, ',', '.') : '—' }}</td>
                                <td class="px-3 py-1.5 text-right">{{ $serah['jumlah_dus'] > 0 ? number_format($serah['jumlah_dus'], 0, ',', '.') : '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="border-t-2 border-gray-900 bg-gray-50 text-xs font-black">
                        <tr>
                            <td class="px-3 py-2 uppercase">TOTAL</td>
                            <td class="px-3 py-2 text-right">{{ number_format(array_sum(array_column($penerimaanPerPecahan, 'jumlah')), 0, ',', '.') }}</td>
                            <td class="px-3 py-2 text-right">{{ number_format(array_sum(array_column($sortirPerPecahan, 'jumlah_pack')), 0, ',', '.') }}</td>
                            <td class="px-3 py-2 text-right">{{ number_format(array_sum(array_column($sortirPerPecahan, 'jumlah_bilyet')), 0, ',', '.') }}</td>
                            <td class="px-3 py-2 text-right">{{ number_format(array_sum(array_column($pengemasanPerPecahan, 'jumlah_pack')), 0, ',', '.') }}</td>
                            <td class="px-3 py-2 text-right">{{ number_format(array_sum(array_column($pengemasanPerPecahan, 'jumlah_dus')), 0, ',', '.') }}</td>
                            <td class="px-3 py-2 text-right">{{ number_format(array_sum(array_column($penyerahanPerPecahan, 'jumlah_bilyet')), 0, ',', '.') }}</td>
                            <td class="px-3 py-2 text-right">{{ number_format(array_sum(array_column($penyerahanPerPecahan, 'jumlah_dus')), 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Detail Sections (Hanya jika ada data) --}}
            @if($penerimaans->isNotEmpty())
                <div class="mb-8 avoid-break">
                    <h3 class="text-[10px] font-black uppercase tracking-widest mb-2 text-blue-600">Detail Penerimaan</h3>
                    <table class="w-full text-[10px] border border-gray-200">
                        <thead class="bg-blue-50/50">
                            <tr class="font-bold border-b border-gray-200 text-gray-500">
                                <th class="px-2 py-1 text-left">No Bon</th>
                                <th class="px-2 py-1 text-center">Pch</th>
                                <th class="px-2 py-1 text-center">TE</th>
                                <th class="px-2 py-1 text-center">TA</th>
                                <th class="px-2 py-1 text-right">Jumlah</th>
                                <th class="px-2 py-1 text-center">Gilir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($penerimaans as $row)
                                <tr>
                                    <td class="px-2 py-1 font-bold">{{ $row->nomor_bon }}</td>
                                    <td class="px-2 py-1 text-center font-bold">{{ $row->pecahan }}</td>
                                    <td class="px-2 py-1 text-center">{{ $row->emisi }}</td>
                                    <td class="px-2 py-1 text-center">{{ $row->tahun_anggaran }}</td>
                                    <td class="px-2 py-1 text-right font-bold">{{ number_format($row->jumlah, 0, ',', '.') }}</td>
                                    <td class="px-2 py-1 text-center uppercase">{{ $row->gilir }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @if($sortirs->isNotEmpty())
                <div class="mb-8 avoid-break">
                    <h3 class="text-[10px] font-black uppercase tracking-widest mb-2 text-purple-600">Detail Penyortiran</h3>
                    <table class="w-full text-[10px] border border-gray-200">
                        <thead class="bg-purple-50/50">
                            <tr class="font-bold border-b border-gray-200 text-gray-500">
                                <th class="px-2 py-1 text-center">Pch</th>
                                <th class="px-2 py-1 text-left">Batch/Seri</th>
                                <th class="px-2 py-1 text-center">TE</th>
                                <th class="px-2 py-1 text-center">TA</th>
                                <th class="px-2 py-1 text-right">Pack</th>
                                <th class="px-2 py-1 text-right">Bilyet</th>
                                <th class="px-2 py-1 text-center">Gilir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($sortirs as $row)
                                <tr>
                                    <td class="px-2 py-1 text-center font-bold">{{ $row->pecahan }}</td>
                                    <td class="px-2 py-1 font-bold">{{ $row->batch }}/{{ $row->seri }}</td>
                                    <td class="px-2 py-1 text-center">{{ $row->emisi }}</td>
                                    <td class="px-2 py-1 text-center">{{ $row->tahun_anggaran }}</td>
                                    <td class="px-2 py-1 text-right font-bold">{{ number_format($row->jumlah_pack, 0, ',', '.') }}</td>
                                    <td class="px-2 py-1 text-right">{{ number_format($row->jumlah_bilyet, 0, ',', '.') }}</td>
                                    <td class="px-2 py-1 text-center uppercase">{{ $row->gilir }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @if($pengemasans->isNotEmpty())
                <div class="mb-8 avoid-break">
                    <h3 class="text-[10px] font-black uppercase tracking-widest mb-2 text-green-600">Detail Pengemasan</h3>
                    <table class="w-full text-[10px] border border-gray-200">
                        <thead class="bg-green-50/50">
                            <tr class="font-bold border-b border-gray-200 text-gray-500">
                                <th class="px-2 py-1 text-center">Pch</th>
                                <th class="px-2 py-1 text-left">Batch/Seri</th>
                                <th class="px-2 py-1 text-center">TE</th>
                                <th class="px-2 py-1 text-center">TA</th>
                                <th class="px-2 py-1 text-right">Pack</th>
                                <th class="px-2 py-1 text-right">Dus</th>
                                <th class="px-2 py-1 text-center">Gilir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($pengemasans as $row)
                                <tr>
                                    <td class="px-2 py-1 text-center font-bold">{{ $row->pecahan }}</td>
                                    <td class="px-2 py-1 font-bold">{{ $row->batch }}/{{ $row->seri }}</td>
                                    <td class="px-2 py-1 text-center">{{ $row->tahun_emisi }}</td>
                                    <td class="px-2 py-1 text-center">{{ $row->tahun_anggaran }}</td>
                                    <td class="px-2 py-1 text-right font-bold">{{ number_format($row->jumlah_pack, 0, ',', '.') }}</td>
                                    <td class="px-2 py-1 text-right font-bold">{{ number_format($row->jumlah_dus, 0, ',', '.') }}</td>
                                    <td class="px-2 py-1 text-center uppercase">{{ $row->gilir }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @if($penyerahans->isNotEmpty())
                <div class="mb-8 avoid-break">
                    <h3 class="text-[10px] font-black uppercase tracking-widest mb-2 text-rose-600">Detail Penyerahan BI</h3>
                    <table class="w-full text-[10px] border border-gray-200">
                        <thead class="bg-rose-50/50">
                            <tr class="font-bold border-b border-gray-200 text-gray-500">
                                <th class="px-2 py-1 text-left">Nomor BA</th>
                                <th class="px-2 py-1 text-center">Pch</th>
                                <th class="px-2 py-1 text-center">TE</th>
                                <th class="px-2 py-1 text-center">TA</th>
                                <th class="px-2 py-1 text-right">Bilyet</th>
                                <th class="px-2 py-1 text-right">Dus</th>
                                <th class="px-2 py-1 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($penyerahans as $row)
                                <tr>
                                    <td class="px-2 py-1 font-black font-mono text-gray-700">{{ $row->nomor_ba }}</td>
                                    <td class="px-2 py-1 text-center font-bold">{{ $row->pecahan }}</td>
                                    <td class="px-2 py-1 text-center">{{ $row->tahun_emisi }}</td>
                                    <td class="px-2 py-1 text-center">{{ $row->tahun_anggaran }}</td>
                                    <td class="px-2 py-1 text-right font-bold">{{ number_format($row->jumlah_bilyet, 0, ',', '.') }}</td>
                                    <td class="px-2 py-1 text-right font-bold">{{ number_format($row->jumlah_dus, 0, ',', '.') }}</td>
                                    <td class="px-2 py-1 text-center font-black uppercase text-[8px]">{{ $row->status_data }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            {{-- Footer / Signature --}}
            <div class="mt-16 flex justify-between items-end">
                <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                    Dicetak pada: {{ now()->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }}<br>
                    Oleh: {{ auth()->user()->name }}
                </div>
                <div class="text-center w-48">
                    <p class="text-[10px] font-bold uppercase tracking-widest mb-16">Penanggung Jawab</p>
                    <div class="border-t border-gray-900 w-full pt-1">
                        <p class="text-xs font-black uppercase text-gray-900">( ................................ )</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            body { background: white !important; }
            .print-container { padding: 0 !important; width: 100% !important; max-width: 100% !important; border: none !important; box-shadow: none !important; }
            .avoid-break { page-break-inside: avoid; }
            .print-accent { display: block !important; }
        }
        @page {
            size: A4;
            margin: 1cm;
        }
    </style>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</x-guest-layout>
