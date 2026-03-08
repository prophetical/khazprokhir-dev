<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan Harian</h2>
    </x-slot>

    @php
        $pecahans = ['S', 'T', 'U', 'V', 'W', 'X', 'Y'];
        $colorMap = [
            'S' => ['bg' => 'bg-lime-500',   'light' => 'bg-lime-50',   'border' => 'border-lime-200',  'text' => 'text-lime-700',   'badge' => 'bg-lime-500'],
            'T' => ['bg' => 'bg-gray-400',   'light' => 'bg-gray-50',   'border' => 'border-gray-200',  'text' => 'text-gray-600',   'badge' => 'bg-gray-400'],
            'U' => ['bg' => 'bg-amber-400',  'light' => 'bg-amber-50',  'border' => 'border-amber-200', 'text' => 'text-amber-700',  'badge' => 'bg-amber-400'],
            'V' => ['bg' => 'bg-purple-500', 'light' => 'bg-purple-50', 'border' => 'border-purple-200','text' => 'text-purple-700', 'badge' => 'bg-purple-500'],
            'W' => ['bg' => 'bg-green-500',  'light' => 'bg-green-50',  'border' => 'border-green-200', 'text' => 'text-green-700',  'badge' => 'bg-green-500'],
            'X' => ['bg' => 'bg-blue-500',   'light' => 'bg-blue-50',   'border' => 'border-blue-200',  'text' => 'text-blue-700',   'badge' => 'bg-blue-500'],
            'Y' => ['bg' => 'bg-red-500',    'light' => 'bg-red-50',    'border' => 'border-red-200',   'text' => 'text-red-700',    'badge' => 'bg-red-500'],
        ];
    @endphp

    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Filter Panel --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-widest">Filter Laporan</h3>
                </div>
                <div class="p-6">
                    <form method="GET" action="{{ route('laporan-harian.index') }}">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                            {{-- Tanggal --}}
                            <div>
                                <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2">Tanggal</label>
                                <input type="date" name="tanggal" value="{{ $tanggal }}"
                                    class="block w-full border-gray-200 rounded-lg shadow-sm text-sm py-2.5 px-3 focus:border-indigo-400 focus:ring-indigo-400 focus:ring-opacity-30 transition-all">
                            </div>
                            {{-- Pecahan --}}
                            <div>
                                <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2">Pecahan</label>
                                <select name="pecahan" class="block w-full border-gray-200 rounded-lg shadow-sm text-sm py-2.5 px-3 font-bold focus:border-indigo-400 focus:ring-indigo-400 focus:ring-opacity-30 transition-all">
                                    <option value="">Semua Pecahan</option>
                                    @foreach($pecahans as $p)
                                        <option value="{{ $p }}" {{ $pecahan == $p ? 'selected' : '' }}>{{ $p }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- Tahun Anggaran --}}
                            <div>
                                <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2">Tahun Anggaran</label>
                                <select name="tahun_anggaran" class="block w-full border-gray-200 rounded-lg shadow-sm text-sm py-2.5 px-3 font-bold focus:border-indigo-400 focus:ring-indigo-400 focus:ring-opacity-30 transition-all">
                                    <option value="">Semua TA</option>
                                    @foreach($allYearsAnggaran as $y)
                                        <option value="{{ $y }}" {{ $tahunAnggaran == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- Tahun Emisi --}}
                            <div>
                                <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2">Tahun Emisi</label>
                                <select name="tahun_emisi" class="block w-full border-gray-200 rounded-lg shadow-sm text-sm py-2.5 px-3 font-bold focus:border-indigo-400 focus:ring-indigo-400 focus:ring-opacity-30 transition-all">
                                    <option value="">Semua TE</option>
                                    @foreach($allYearsEmisi as $y)
                                        <option value="{{ $y }}" {{ $tahunEmisi == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-bold text-white rounded-lg shadow-md hover:-translate-y-0.5 hover:shadow-lg active:translate-y-0 transition-all duration-200" style="background: linear-gradient(135deg, #2563eb, #7c3aed, #db2877);">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                Tampilkan
                            </button>

                            <a href="{{ route('laporan-harian.print', request()->query()) }}" target="_blank" 
                               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-bold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all shadow-sm">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                Cetak PDF
                            </a>

                            <a href="{{ route('laporan-harian.export', request()->query()) }}" 
                               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg hover:bg-emerald-100 transition-all shadow-sm">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Export CSV
                            </a>

                            <a href="{{ route('laporan-harian.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-bold text-gray-500 bg-gray-100 border border-gray-200 rounded-lg hover:bg-gray-200 transition-all ml-auto">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Tanggal Header --}}
            <div class="flex items-center gap-4">
                <div class="flex-1 h-px bg-gray-200"></div>
                <div class="flex items-center gap-3 text-xs">
                    <span class="text-gray-400 font-medium">Laporan untuk</span>
                    <span class="font-extrabold text-gray-800 text-base">{{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
                    @if($pecahan)
                        <span class="{{ $colorMap[$pecahan]['bg'] }} text-white text-[10px] font-black px-2 py-0.5 rounded">{{ $pecahan }}</span>
                    @endif
                    @if($tahunAnggaran)
                        <span class="bg-indigo-100 text-indigo-700 text-[10px] font-bold px-2 py-0.5 rounded">TA {{ $tahunAnggaran }}</span>
                    @endif
                    @if($tahunEmisi)
                        <span class="bg-purple-100 text-purple-700 text-[10px] font-bold px-2 py-0.5 rounded">TE {{ $tahunEmisi }}</span>
                    @endif
                </div>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>

            {{-- Summary Cards: 4 modul --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                @php
                    $summaryCards = [
                        ['label' => 'Total Penerimaan', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10', 'value' => number_format(array_sum(array_column($penerimaanPerPecahan, 'jumlah')), 0, ',', '.'), 'sub' => 'Bilyet diterima', 'color' => 'from-blue-500 to-blue-600'],
                        ['label' => 'Total Sortir', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01', 'value' => number_format(array_sum(array_column($sortirPerPecahan, 'jumlah_bilyet')), 0, ',', '.'), 'sub' => 'Bilyet tersortir', 'color' => 'from-purple-500 to-purple-600'],
                        ['label' => 'Total Kemas', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', 'value' => number_format(array_sum(array_column($pengemasanPerPecahan, 'jumlah_pack')), 0, ',', '.'), 'sub' => 'Pack dikemas', 'color' => 'from-green-500 to-emerald-600'],
                        ['label' => 'Total Serah ke BI', 'icon' => 'M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4', 'value' => number_format(array_sum(array_column($penyerahanPerPecahan, 'jumlah_bilyet')), 0, ',', '.'), 'sub' => 'Bilyet diserahkan', 'color' => 'from-rose-500 to-pink-600'],
                    ];
                @endphp
                @foreach($summaryCards as $card)
                    <div class="rounded-xl overflow-hidden shadow-sm">
                        <div class="bg-gradient-to-br {{ $card['color'] }} p-4 text-white">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[10px] font-bold uppercase tracking-widest opacity-80">{{ $card['label'] }}</span>
                                <div class="w-7 h-7 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/></svg>
                                </div>
                            </div>
                            <p class="text-2xl font-extrabold tracking-tight">{{ $card['value'] }}</p>
                            <p class="text-[10px] opacity-70 mt-0.5">{{ $card['sub'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Tabel per Pecahan --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-gray-800">Ringkasan Per Pecahan</h3>
                    <p class="text-[11px] text-gray-400 mt-0.5">Semua kegiatan hari ini dikelompokkan berdasarkan pecahan</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="text-left text-[11px] font-bold text-gray-500 uppercase tracking-widest px-6 py-3 w-16">Pecahan</th>
                                {{-- Penerimaan --}}
                                <th colspan="1" class="text-center text-[11px] font-bold text-blue-600 uppercase tracking-widest px-3 py-3 border-l border-gray-100 bg-blue-50/50">Penerimaan</th>
                                {{-- Penyortiran --}}
                                <th colspan="2" class="text-center text-[11px] font-bold text-purple-600 uppercase tracking-widest px-3 py-3 border-l border-gray-100 bg-purple-50/50">Penyortiran</th>
                                {{-- Pengemasan --}}
                                <th colspan="2" class="text-center text-[11px] font-bold text-green-600 uppercase tracking-widest px-3 py-3 border-l border-gray-100 bg-green-50/50">Pengemasan</th>
                                {{-- Penyerahan --}}
                                <th colspan="2" class="text-center text-[11px] font-bold text-rose-600 uppercase tracking-widest px-3 py-3 border-l border-gray-100 bg-rose-50/50">Penyerahan BI</th>
                            </tr>
                            <tr class="bg-gray-50/50 border-b border-gray-200 text-[10px] text-gray-400 font-bold uppercase">
                                <th class="px-6 py-2"></th>
                                <th class="px-3 py-2 border-l border-gray-100 text-right text-blue-500">Bilyet</th>
                                <th class="px-3 py-2 border-l border-gray-100 text-right text-purple-500">Pack</th>
                                <th class="px-3 py-2 text-right text-purple-400">Bilyet</th>
                                <th class="px-3 py-2 border-l border-gray-100 text-right text-green-500">Pack</th>
                                <th class="px-3 py-2 text-right text-green-400">Dus</th>
                                <th class="px-3 py-2 border-l border-gray-100 text-right text-rose-500">Bilyet</th>
                                <th class="px-3 py-2 text-right text-rose-400">Dus</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($pecahans as $p)
                                @php
                                    $c = $colorMap[$p];
                                    $terima  = $penerimaanPerPecahan[$p];
                                    $sortir  = $sortirPerPecahan[$p];
                                    $kemas   = $pengemasanPerPecahan[$p];
                                    $serah   = $penyerahanPerPecahan[$p];
                                    $anyData = $terima['jumlah'] > 0 || $sortir['jumlah_bilyet'] > 0 || $kemas['jumlah_pack'] > 0 || $serah['jumlah_bilyet'] > 0;
                                @endphp
                                <tr class="{{ $anyData ? '' : 'opacity-40' }} hover:bg-gray-50/70 transition-colors">
                                    <td class="px-6 py-3">
                                        <span class="{{ $c['bg'] }} text-white text-[10px] font-black px-2.5 py-1 rounded-lg">{{ $p }}</span>
                                    </td>
                                    {{-- Penerimaan --}}
                                    <td class="px-3 py-3 text-right text-sm font-bold {{ $terima['jumlah'] > 0 ? 'text-blue-700' : 'text-gray-300' }} border-l border-gray-100 bg-blue-50/20">
                                        {{ $terima['jumlah'] > 0 ? number_format($terima['jumlah'], 0, ',', '.') : '—' }}
                                    </td>
                                    {{-- Sortir --}}
                                    <td class="px-3 py-3 text-right text-sm font-bold {{ $sortir['jumlah_pack'] > 0 ? 'text-purple-700' : 'text-gray-300' }} border-l border-gray-100 bg-purple-50/20">
                                        {{ $sortir['jumlah_pack'] > 0 ? number_format($sortir['jumlah_pack'], 0, ',', '.') : '—' }}
                                    </td>
                                    <td class="px-3 py-3 text-right text-xs font-medium {{ $sortir['jumlah_bilyet'] > 0 ? 'text-purple-500' : 'text-gray-300' }} bg-purple-50/20">
                                        {{ $sortir['jumlah_bilyet'] > 0 ? number_format($sortir['jumlah_bilyet'], 0, ',', '.') : '—' }}
                                    </td>
                                    {{-- Pengemasan --}}
                                    <td class="px-3 py-3 text-right text-sm font-bold {{ $kemas['jumlah_pack'] > 0 ? 'text-green-700' : 'text-gray-300' }} border-l border-gray-100 bg-green-50/20">
                                        {{ $kemas['jumlah_pack'] > 0 ? number_format($kemas['jumlah_pack'], 0, ',', '.') : '—' }}
                                    </td>
                                    <td class="px-3 py-3 text-right text-xs font-medium {{ $kemas['jumlah_dus'] > 0 ? 'text-green-500' : 'text-gray-300' }} bg-green-50/20">
                                        {{ $kemas['jumlah_dus'] > 0 ? number_format($kemas['jumlah_dus'], 0, ',', '.') : '—' }}
                                    </td>
                                    {{-- Penyerahan --}}
                                    <td class="px-3 py-3 text-right text-sm font-bold {{ $serah['jumlah_bilyet'] > 0 ? 'text-rose-700' : 'text-gray-300' }} border-l border-gray-100 bg-rose-50/20">
                                        {{ $serah['jumlah_bilyet'] > 0 ? number_format($serah['jumlah_bilyet'], 0, ',', '.') : '—' }}
                                    </td>
                                    <td class="px-3 py-3 text-right text-xs font-medium {{ $serah['jumlah_dus'] > 0 ? 'text-rose-500' : 'text-gray-300' }} bg-rose-50/20">
                                        {{ $serah['jumlah_dus'] > 0 ? number_format($serah['jumlah_dus'], 0, ',', '.') : '—' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 border-t-2 border-gray-200">
                            <tr class="text-xs font-extrabold">
                                <td class="px-6 py-3 text-gray-500 uppercase tracking-wider">TOTAL</td>
                                <td class="px-3 py-3 text-right text-blue-700 border-l border-gray-200 bg-blue-50/40">
                                    {{ number_format(array_sum(array_column($penerimaanPerPecahan, 'jumlah')), 0, ',', '.') }}
                                </td>
                                <td class="px-3 py-3 text-right text-purple-700 border-l border-gray-200 bg-purple-50/40">
                                    {{ number_format(array_sum(array_column($sortirPerPecahan, 'jumlah_pack')), 0, ',', '.') }}
                                </td>
                                <td class="px-3 py-3 text-right text-purple-500 bg-purple-50/40">
                                    {{ number_format(array_sum(array_column($sortirPerPecahan, 'jumlah_bilyet')), 0, ',', '.') }}
                                </td>
                                <td class="px-3 py-3 text-right text-green-700 border-l border-gray-200 bg-green-50/40">
                                    {{ number_format(array_sum(array_column($pengemasanPerPecahan, 'jumlah_pack')), 0, ',', '.') }}
                                </td>
                                <td class="px-3 py-3 text-right text-green-500 bg-green-50/40">
                                    {{ number_format(array_sum(array_column($pengemasanPerPecahan, 'jumlah_dus')), 0, ',', '.') }}
                                </td>
                                <td class="px-3 py-3 text-right text-rose-700 border-l border-gray-200 bg-rose-50/40">
                                    {{ number_format(array_sum(array_column($penyerahanPerPecahan, 'jumlah_bilyet')), 0, ',', '.') }}
                                </td>
                                <td class="px-3 py-3 text-right text-rose-500 bg-rose-50/40">
                                    {{ number_format(array_sum(array_column($penyerahanPerPecahan, 'jumlah_dus')), 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Detail 4 modul --}}
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

                {{-- Penerimaan Detail --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2" style="background: linear-gradient(135deg, #eff6ff, #ffffff);">
                        <div class="w-2.5 h-2.5 rounded-full bg-blue-500"></div>
                        <h4 class="text-sm font-bold text-blue-700 uppercase tracking-widest">Detail Penerimaan</h4>
                        <span class="ml-auto text-[10px] font-bold bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full">{{ $penerimaans->count() }} record</span>
                    </div>
                    @if($penerimaans->isEmpty())
                        <div class="px-5 py-8 text-center text-gray-300 text-sm">Tidak ada data penerimaan pada tanggal ini</div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50/80">
                                    <tr class="text-[10px] font-bold text-gray-400 uppercase">
                                        <th class="px-4 py-2 text-left">No Bon</th>
                                        <th class="px-4 py-2 text-center">Pch</th>
                                        <th class="px-4 py-2 text-center">Emisi</th>
                                        <th class="px-4 py-2 text-center">TA</th>
                                        <th class="px-4 py-2 text-right">Jumlah</th>
                                        <th class="px-4 py-2 text-center">Gilir</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach($penerimaans as $row)
                                        <tr class="hover:bg-blue-50/30 transition-colors">
                                            <td class="px-4 py-1.5 text-xs font-bold text-gray-700">{{ $row->nomor_bon }}</td>
                                            <td class="px-4 py-1.5 text-center">
                                                <span class="{{ $colorMap[$row->pecahan]['bg'] ?? 'bg-gray-400' }} text-white text-[9px] font-bold px-1.5 py-0.5 rounded">{{ $row->pecahan }}</span>
                                            </td>
                                            <td class="px-4 py-1.5 text-xs font-bold text-center text-gray-600">{{ $row->emisi }}</td>
                                            <td class="px-4 py-1.5 text-xs font-bold text-center text-gray-500">{{ $row->tahun_anggaran ?? '—' }}</td>
                                            <td class="px-4 py-1.5 text-xs font-bold text-right text-blue-700">{{ number_format($row->jumlah, 0, ',', '.') }}</td>
                                            <td class="px-4 py-1.5 text-[10px] text-center text-gray-400">{{ $row->gilir }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                {{-- Penyortiran Detail --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2" style="background: linear-gradient(135deg, #faf5ff, #ffffff);">
                        <div class="w-2.5 h-2.5 rounded-full bg-purple-500"></div>
                        <h4 class="text-sm font-bold text-purple-700 uppercase tracking-widest">Detail Penyortiran</h4>
                        <span class="ml-auto text-[10px] font-bold bg-purple-100 text-purple-600 px-2 py-0.5 rounded-full">{{ $sortirs->count() }} record</span>
                    </div>
                    @if($sortirs->isEmpty())
                        <div class="px-5 py-8 text-center text-gray-300 text-sm">Tidak ada data penyortiran pada tanggal ini</div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50/80">
                                    <tr class="text-[10px] font-bold text-gray-400 uppercase">
                                        <th class="px-4 py-2 text-center">Pch</th>
                                        <th class="px-4 py-2 text-left">Batch/Seri</th>
                                        <th class="px-4 py-2 text-center">Emisi</th>
                                        <th class="px-4 py-2 text-center">TA</th>
                                        <th class="px-4 py-2 text-right">Pack</th>
                                        <th class="px-4 py-2 text-right">Bilyet</th>
                                        <th class="px-4 py-2 text-center">Gilir</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach($sortirs as $row)
                                        <tr class="hover:bg-purple-50/30 transition-colors">
                                            <td class="px-4 py-1.5 text-center">
                                                <span class="{{ $colorMap[$row->pecahan]['bg'] ?? 'bg-gray-400' }} text-white text-[9px] font-bold px-1.5 py-0.5 rounded">{{ $row->pecahan }}</span>
                                            </td>
                                            <td class="px-4 py-1.5 text-xs font-bold text-gray-700">{{ $row->batch }}/{{ $row->seri }}</td>
                                            <td class="px-4 py-1.5 text-xs font-bold text-center text-gray-600">{{ $row->emisi }}</td>
                                            <td class="px-4 py-1.5 text-xs font-bold text-center text-gray-500">{{ $row->tahun_anggaran }}</td>
                                            <td class="px-4 py-1.5 text-xs font-bold text-right text-purple-700">{{ number_format($row->jumlah_pack, 0, ',', '.') }}</td>
                                            <td class="px-4 py-1.5 text-xs font-medium text-right text-purple-500">{{ number_format($row->jumlah_bilyet, 0, ',', '.') }}</td>
                                            <td class="px-4 py-1.5 text-[10px] text-center text-gray-400">{{ $row->gilir }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                {{-- Pengemasan Detail --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2" style="background: linear-gradient(135deg, #f0fdf4, #ffffff);">
                        <div class="w-2.5 h-2.5 rounded-full bg-green-500"></div>
                        <h4 class="text-sm font-bold text-green-700 uppercase tracking-widest">Detail Pengemasan</h4>
                        <span class="ml-auto text-[10px] font-bold bg-green-100 text-green-600 px-2 py-0.5 rounded-full">{{ $pengemasans->count() }} record</span>
                    </div>
                    @if($pengemasans->isEmpty())
                        <div class="px-5 py-8 text-center text-gray-300 text-sm">Tidak ada data pengemasan pada tanggal ini</div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50/80">
                                    <tr class="text-[10px] font-bold text-gray-400 uppercase">
                                        <th class="px-4 py-2 text-center">Pch</th>
                                        <th class="px-4 py-2 text-left">Batch/Seri</th>
                                        <th class="px-4 py-2 text-center">TE</th>
                                        <th class="px-4 py-2 text-center">TA</th>
                                        <th class="px-4 py-2 text-right">Pack</th>
                                        <th class="px-4 py-2 text-right">Dus</th>
                                        <th class="px-4 py-2 text-center">Gilir</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach($pengemasans as $row)
                                        <tr class="hover:bg-green-50/30 transition-colors">
                                            <td class="px-4 py-1.5 text-center">
                                                <span class="{{ $colorMap[$row->pecahan]['bg'] ?? 'bg-gray-400' }} text-white text-[9px] font-bold px-1.5 py-0.5 rounded">{{ $row->pecahan }}</span>
                                            </td>
                                            <td class="px-4 py-1.5 text-xs font-bold text-gray-700">{{ $row->batch }}/{{ $row->seri }}</td>
                                            <td class="px-4 py-1.5 text-xs font-bold text-center text-gray-600">{{ $row->tahun_emisi }}</td>
                                            <td class="px-4 py-1.5 text-xs font-bold text-center text-gray-500">{{ $row->tahun_anggaran }}</td>
                                            <td class="px-4 py-1.5 text-xs font-bold text-right text-green-700">{{ number_format($row->jumlah_pack, 0, ',', '.') }}</td>
                                            <td class="px-4 py-1.5 text-xs font-medium text-right text-green-500">{{ number_format($row->jumlah_dus, 0, ',', '.') }}</td>
                                            <td class="px-4 py-1.5 text-[10px] text-center text-gray-400">{{ $row->gilir }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                {{-- Penyerahan Detail --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2" style="background: linear-gradient(135deg, #fff1f2, #ffffff);">
                        <div class="w-2.5 h-2.5 rounded-full bg-rose-500"></div>
                        <h4 class="text-sm font-bold text-rose-700 uppercase tracking-widest">Detail Penyerahan ke BI</h4>
                        <span class="ml-auto text-[10px] font-bold bg-rose-100 text-rose-600 px-2 py-0.5 rounded-full">{{ $penyerahans->count() }} record</span>
                    </div>
                    @if($penyerahans->isEmpty())
                        <div class="px-5 py-8 text-center text-gray-300 text-sm">Tidak ada data penyerahan pada tanggal ini</div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50/80">
                                    <tr class="text-[10px] font-bold text-gray-400 uppercase">
                                        <th class="px-4 py-2 text-left">Nomor BA</th>
                                        <th class="px-4 py-2 text-center">Pch</th>
                                        <th class="px-4 py-2 text-center">TE</th>
                                        <th class="px-4 py-2 text-center">TA</th>
                                        <th class="px-4 py-2 text-right">Bilyet</th>
                                        <th class="px-4 py-2 text-right">Dus</th>
                                        <th class="px-4 py-2 text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach($penyerahans as $row)
                                        <tr class="hover:bg-rose-50/30 transition-colors">
                                            <td class="px-4 py-1.5 text-xs font-bold font-mono text-gray-700">{{ $row->nomor_ba }}</td>
                                            <td class="px-4 py-1.5 text-center">
                                                <span class="{{ $colorMap[$row->pecahan]['bg'] ?? 'bg-gray-400' }} text-white text-[9px] font-bold px-1.5 py-0.5 rounded">{{ $row->pecahan }}</span>
                                            </td>
                                            <td class="px-4 py-1.5 text-xs font-bold text-center text-gray-600">{{ $row->tahun_emisi }}</td>
                                            <td class="px-4 py-1.5 text-xs font-bold text-center text-gray-500">{{ $row->tahun_anggaran }}</td>
                                            <td class="px-4 py-1.5 text-xs font-bold text-right text-rose-700">{{ number_format($row->jumlah_bilyet, 0, ',', '.') }}</td>
                                            <td class="px-4 py-1.5 text-xs font-medium text-right text-rose-500">{{ number_format($row->jumlah_dus, 0, ',', '.') }}</td>
                                            <td class="px-4 py-1.5 text-center">
                                                @if($row->status_data === 'Lengkap')
                                                    <span class="text-[9px] font-bold text-green-700 bg-green-100 px-1.5 py-0.5 rounded">✓</span>
                                                @else
                                                    <span class="text-[9px] font-bold text-amber-700 bg-amber-100 px-1.5 py-0.5 rounded">⚠</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
