<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Penyerahan ke BI') }}
        </h2>
    </x-slot>

    @php
        $themeClasses = [
            'S' => ['bg' => 'bg-lime-500',   'border' => 'border-lime-500',   'ring' => 'focus:ring-lime-500',   'focus' => 'focus:border-lime-500',   'btn' => 'bg-lime-500',   'text' => 'text-white'],
            'T' => ['bg' => 'bg-gray-400',   'border' => 'border-gray-400',   'ring' => 'focus:ring-gray-400',   'focus' => 'focus:border-gray-400',   'btn' => 'bg-gray-400',   'text' => 'text-white'],
            'U' => ['bg' => 'bg-amber-400',  'border' => 'border-amber-400',  'ring' => 'focus:ring-amber-400',  'focus' => 'focus:border-amber-400',  'btn' => 'bg-amber-400',  'text' => 'text-white'],
            'V' => ['bg' => 'bg-purple-500', 'border' => 'border-purple-500', 'ring' => 'focus:ring-purple-500', 'focus' => 'focus:border-purple-500', 'btn' => 'bg-purple-500', 'text' => 'text-white'],
            'W' => ['bg' => 'bg-green-500',  'border' => 'border-green-500',  'ring' => 'focus:ring-green-500',  'focus' => 'focus:border-green-500',  'btn' => 'bg-green-500',  'text' => 'text-white'],
            'X' => ['bg' => 'bg-blue-500',   'border' => 'border-blue-500',   'ring' => 'focus:ring-blue-500',   'focus' => 'focus:border-blue-500',   'btn' => 'bg-blue-500',   'text' => 'text-white'],
            'Y' => ['bg' => 'bg-red-500',    'border' => 'border-red-500',    'ring' => 'focus:ring-red-500',    'focus' => 'focus:border-red-500',    'btn' => 'bg-red-500',    'text' => 'text-white'],
        ];
        $selectedPecahan = request('pecahan', '');

        function sortIcon2($col) {
            if (request('sort') === $col) return request('direction') === 'asc' ? '↑' : '↓';
            return '';
        }
        $direction = request('direction') === 'asc' ? 'desc' : 'asc';
    @endphp

    <div class="py-8">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Warning dari session (setelah simpan ada dus yang belum ada) --}}
            @if(session('warning_penyerahan'))
                @php $warn = session('warning_penyerahan'); @endphp
                <div class="bg-amber-50 border-l-4 border-amber-400 p-5 rounded-r-lg shadow-sm">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                        <div>
                            <h3 class="font-bold text-amber-800 text-sm">Data disimpan — Sebagian Nomor Dus Belum Ada di Sistem</h3>
                            <div class="mt-2 grid grid-cols-2 md:grid-cols-5 gap-2 text-xs">
                                <div class="bg-amber-100 rounded p-2"><span class="text-amber-500">Range Diminta</span><div class="font-bold text-amber-800">{{ $warn['range_diminta'] }}</div></div>
                                <div class="bg-amber-100 rounded p-2"><span class="text-amber-500">Jml Diminta</span><div class="font-bold text-amber-800">{{ $warn['jumlah_diminta'] }}</div></div>
                                <div class="bg-green-100 rounded p-2"><span class="text-green-500">Ada di Sistem</span><div class="font-bold text-green-800">{{ $warn['jumlah_ada'] }}</div></div>
                                <div class="bg-red-100 rounded p-2"><span class="text-red-500">Belum Ada</span><div class="font-bold text-red-800">{{ $warn['jumlah_belum_ada'] }}</div></div>
                                <div class="bg-gray-100 rounded p-2"><span class="text-gray-500">Est. Bilyet Belum Tercatat</span><div class="font-bold text-gray-800">{{ number_format($warn['estimasi_bilyet'], 0, ',', '.') }}</div></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-r-lg shadow-sm">{{ session('success') }}</div>
            @endif

            {{-- Panel: Warning nomor dus belum dikemas (seluruh penyerahan) --}}
            @if($missingWarnings->isNotEmpty())
                <div class="bg-red-50 border border-red-200 rounded-xl shadow-sm overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-3 bg-red-100 border-b border-red-200">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <h3 class="font-bold text-red-800 text-sm">{{ $missingWarnings->count() }} Penyerahan Belum Lengkap — Nomor Dus Belum Dikemas</h3>
                        </div>
                        <span class="text-[10px] text-red-500">Perlu tindak lanjut</span>
                    </div>
                    <div class="divide-y divide-red-100">
                        @foreach($missingWarnings as $warn)
                            @php
                                $colorMap = ['S'=>'bg-lime-500','T'=>'bg-gray-400','U'=>'bg-amber-400','V'=>'bg-purple-500','W'=>'bg-green-500','X'=>'bg-blue-500','Y'=>'bg-red-500'];
                                $bgColor = $colorMap[$warn['pecahan']] ?? 'bg-gray-400';
                            @endphp
                            <div class="px-5 py-3 grid grid-cols-1 md:grid-cols-2 gap-2">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="{{ $bgColor }} text-white text-[9px] font-black px-2 py-0.5 rounded">{{ $warn['pecahan'] }}</span>
                                    <span class="text-[10px] font-bold text-gray-600">TA {{ $warn['tahun_anggaran'] }}</span>
                                    <span class="text-gray-300">·</span>
                                    <span class="text-[10px] font-bold text-gray-600">TE {{ $warn['tahun_emisi'] }}</span>
                                    <span class="text-gray-300">·</span>
                                    <span class="text-[10px] font-bold text-indigo-600 font-mono">Dus {{ $warn['nomor_range'] }}</span>
                                    <span class="text-gray-300">·</span>
                                    <span class="text-[10px] font-mono text-red-500 font-bold">BA: {{ $warn['nomor_ba'] }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] text-red-500 font-bold shrink-0">Belum Dikemas ({{ $warn['missing_count'] }} dus):</span>
                                    <span class="text-[10px] font-mono font-bold text-red-800 bg-red-100 px-2 py-0.5 rounded break-all">{{ $warn['missing_ranges'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div x-data="{
                    selectedPecahan: '{{ $selectedPecahan }}',
                    themes: {{ json_encode($themeClasses) }},
                    get currentTheme() { return this.themes[this.selectedPecahan] || null }
                }"
                class="bg-white overflow-hidden shadow-sm rounded-xl border-t-4 transition-all duration-500"
                :class="currentTheme ? currentTheme.border : 'border-indigo-500'">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 border-l-4 pl-4 mb-6 transition-colors duration-500" :class="currentTheme ? currentTheme.border : 'border-indigo-600'">
                        Laporan Penyerahan ke BI
                    </h3>
                    <form method="GET" action="{{ route('penyerahan-bi.index') }}">
                        @if(request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                            <input type="hidden" name="direction" value="{{ request('direction') }}">
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                            {{-- Pecahan --}}
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Pecahan</label>
                                <select name="pecahan" x-model="selectedPecahan"
                                    class="block w-full border-gray-200 rounded-lg shadow-sm text-sm py-3 px-3 transition-all font-bold focus:ring-opacity-50"
                                    :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'">
                                    <option value="">Semua</option>
                                    @foreach(['S','T','U','V','W','X','Y'] as $p)
                                        <option value="{{ $p }}" {{ request('pecahan')==$p ? 'selected' : '' }}>{{ $p }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Tahun Anggaran --}}
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Tahun Anggaran</label>
                                <select name="tahun_anggaran"
                                    class="block w-full border-gray-200 rounded-lg shadow-sm text-sm py-3 px-3 transition-all font-bold focus:ring-opacity-50"
                                    :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'">
                                    <option value="">Semua</option>
                                    @foreach($availableYears as $ta)
                                        <option value="{{ $ta }}" {{ request('tahun_anggaran')==$ta ? 'selected' : '' }}>{{ $ta }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Tanggal Awal --}}
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Tanggal Awal</label>
                                <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}"
                                    class="block w-full border-gray-200 rounded-lg shadow-sm text-sm py-3 px-3 transition-all focus:ring-opacity-50"
                                    :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'">
                            </div>

                            {{-- Tanggal Akhir --}}
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Tanggal Akhir</label>
                                <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}"
                                    class="block w-full border-gray-200 rounded-lg shadow-sm text-sm py-3 px-3 transition-all focus:ring-opacity-50"
                                    :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end mt-4">
                            {{-- Search umum --}}
                            <div class="lg:col-span-2">
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Cari (Nomor BA, Pecahan, dll)</label>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari data..."
                                    class="block w-full border-gray-200 rounded-lg shadow-sm text-sm py-3 px-3 transition-all focus:ring-opacity-50"
                                    :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'">
                            </div>

                            {{-- Nomor BA spesifik --}}
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Nomor BA Spesifik</label>
                                <input type="text" name="nomor_ba" value="{{ request('nomor_ba') }}" placeholder="BA-xxx/BI/xxxx"
                                    class="block w-full border-gray-200 rounded-lg shadow-sm text-sm py-3 px-3 transition-all font-mono text-xs focus:ring-opacity-50"
                                    :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'">
                            </div>

                            {{-- Tombol Aksi --}}
                            <div class="flex gap-2 h-full items-end">
                                <button type="submit"
                                    class="flex-1 inline-flex justify-center items-center px-4 py-3 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest shadow-md active:scale-95 transition-all duration-300"
                                    :class="currentTheme ? currentTheme.btn : 'bg-gray-800 hover:bg-gray-700'">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    Cari
                                </button>
                                <a href="{{ route('penyerahan-bi.index') }}"
                                    class="flex-1 inline-flex justify-center items-center px-4 py-3 bg-gray-100 border border-gray-200 rounded-lg font-bold text-xs text-gray-400 uppercase tracking-widest shadow-sm hover:bg-gray-200 active:scale-95 transition-all @if(!request()->hasAny(['search','pecahan','nomor_ba','tanggal_awal','tanggal_akhir','tahun_anggaran'])) opacity-50 pointer-events-none @endif">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    Reset
                                </a>
                            </div>
                        </div>

                        {{-- Tombol export & aksi --}}
                        <div class="mt-6 pt-4 border-t border-gray-100 flex flex-wrap gap-2 justify-between items-center">
                            <a href="{{ route('penyerahan-bi.create') }}"
                                class="inline-flex items-center justify-center px-4 py-2 text-sm font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100 transition-colors shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Input Penyerahan
                            </a>
                            <div class="flex gap-2">
                                <a href="{{ route('penyerahan-bi.export', request()->all()) }}"
                                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg hover:bg-emerald-100 transition-colors shadow-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Excel
                                </a>
                                <a href="{{ route('penyerahan-bi.print', request()->all()) }}" target="_blank"
                                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium bg-rose-50 text-rose-700 border border-rose-200 rounded-lg hover:bg-rose-100 transition-colors shadow-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                    PDF / Print
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Tabel Data --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border-t-4 border-gray-100">
                <div class="p-6">
                    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    @php
                                        $cols = [
                                            'tanggal_penyerahan' => 'Tanggal',
                                            'nomor_ba'           => 'Nomor BA',
                                            'pecahan'            => 'Identitas',
                                            'nomor_dus_awal'     => 'Rentang Dus',
                                            'jumlah_dus'         => 'Dus',
                                            'jumlah_bilyet'      => 'Bilyet',
                                            'status_data'        => 'Status',
                                        ];
                                    @endphp
                                    @foreach($cols as $col => $label)
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider bg-gray-50 border-b border-gray-200">
                                            <a href="{{ route('penyerahan-bi.index', array_merge(request()->query(), ['sort' => $col, 'direction' => $direction])) }}"
                                               class="hover:text-indigo-600 flex items-center gap-1">
                                               {{ $label }} {{ sortIcon2($col) }}
                                            </a>
                                        </th>
                                    @endforeach
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider bg-gray-50 border-b border-gray-200">Petugas</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider bg-gray-50 border-b border-gray-200">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($penyerahans as $row)
                                    @php
                                        $colorMap = [
                                            'S' => ['bg' => 'bg-lime-500',   'text' => 'text-lime-700',   'light' => 'bg-lime-50'],
                                            'T' => ['bg' => 'bg-gray-400',   'text' => 'text-gray-600',   'light' => 'bg-gray-50'],
                                            'U' => ['bg' => 'bg-amber-400',  'text' => 'text-amber-700',  'light' => 'bg-amber-50'],
                                            'V' => ['bg' => 'bg-purple-500', 'text' => 'text-purple-700', 'light' => 'bg-purple-50'],
                                            'W' => ['bg' => 'bg-green-500',  'text' => 'text-green-700',  'light' => 'bg-green-50'],
                                            'X' => ['bg' => 'bg-blue-500',   'text' => 'text-blue-700',   'light' => 'bg-blue-50'],
                                            'Y' => ['bg' => 'bg-red-500',    'text' => 'text-red-700',    'light' => 'bg-red-50'],
                                        ];
                                        $c = $colorMap[$row->pecahan] ?? ['bg' => 'bg-gray-400', 'text' => 'text-gray-600', 'light' => 'bg-gray-50'];
                                    @endphp
                                    <tr class="hover:bg-indigo-50/30 transition-colors duration-150">
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 font-medium">
                                            {{ \Carbon\Carbon::parse($row->tanggal_penyerahan)->locale('id')->isoFormat('D MMMM YYYY') }}
                                        </td>
                                        <td class="px-4 py-3 text-xs font-mono font-bold text-gray-800">
                                            {{ $row->nomor_ba }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="flex flex-col gap-0.5">
                                                <div class="flex items-center gap-1.5">
                                                    <span class="{{ $c['bg'] }} text-white text-[9px] font-bold px-1.5 py-0.5 rounded">{{ $row->pecahan }}</span>
                                                    <span class="text-[10px] font-bold text-gray-700">{{ $row->tahun_anggaran }}</span>
                                                    <span class="text-gray-300 text-[10px]">/</span>
                                                    <span class="text-[10px] font-bold text-gray-500">{{ $row->tahun_emisi }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-center">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                {{ $row->nomor_dus_awal }} – {{ $row->nomor_dus_akhir }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-center">
                                            <span> {{ number_format($row->jumlah_dus, 0, ',', '.') }}</span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-xs font-black text-emerald-600 text-right">
                                            {{ number_format($row->jumlah_bilyet, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @if($row->status_data === 'Lengkap')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-700 border border-green-200">
                                                    ✓ Lengkap
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700 border border-amber-200">
                                                    ⚠ Belum Lengkap
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-[10px] text-gray-500">
                                            <div class="flex items-center gap-1">
                                                <div class="h-4 w-4 rounded-full bg-gray-100 flex items-center justify-center text-[8px] font-bold text-gray-500 border border-gray-200">{{ substr($row->user->name ?? '?', 0, 1) }}</div>
                                                <span class="truncate max-w-[70px]">{{ $row->user->name ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('penyerahan-bi.edit', $row->id) }}" class="p-1.5 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit Data">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                </a>
                                                <form id="delete-form-{{ $row->id }}" action="{{ route('penyerahan-bi.destroy', $row->id) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" onclick="confirmDelete({{ $row->id }})" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus Data">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v2m3 4h.01"/></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="px-4 py-8 text-center text-gray-400">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-12 h-12 mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                                                <p class="text-sm font-medium">Belum ada data penyerahan untuk filter ini.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $penyerahans->onEachSide(1)->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Data Penyerahan?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: 'font-bold uppercase tracking-widest text-xs px-6 py-2.5 rounded-lg shadow-md',
                    cancelButton: 'font-bold uppercase tracking-widest text-xs px-6 py-2.5 rounded-lg shadow-sm'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>
    @endpush
</x-app-layout>
