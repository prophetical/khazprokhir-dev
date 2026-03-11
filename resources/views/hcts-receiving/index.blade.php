<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Penerimaan HCTS') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-10">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900">
                    
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                        <h3 class="text-lg font-bold text-gray-900 uppercase tracking-tighter">Data Penerimaan HCTS</h3>
                        @if(in_array(auth()->user()->role, ['sortir', 'admin']))
                            <a href="{{ route('hcts-receiving.create') }}" class="inline-flex items-center px-4 py-2 bg-rose-600 border border-transparent rounded-lg font-black text-xs text-white uppercase tracking-widest hover:bg-rose-700 shadow-lg shadow-rose-100 transition-all active:scale-95">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                Input HCTS Baru
                            </a>
                        @endif
                    </div>

                    <!-- Filters & Actions -->
                    @php
                        $selectedPecahan = request('pecahan', '');
                        $themeClasses = [
                            'S' => ['bg' => 'bg-lime-500', 'border' => 'border-lime-500', 'ring' => 'focus:ring-lime-500', 'focus' => 'focus:border-lime-500', 'btn' => 'bg-gradient-to-r from-lime-500 to-lime-600', 'text' => 'text-gray-900', 'soft' => 'bg-lime-50', 'icon' => 'text-lime-600'],
                            'T' => ['bg' => 'bg-gray-400', 'border' => 'border-gray-400', 'ring' => 'focus:ring-gray-400', 'focus' => 'focus:border-gray-400', 'btn' => 'bg-gradient-to-r from-gray-400 to-gray-500', 'text' => 'text-white', 'soft' => 'bg-gray-50', 'icon' => 'text-gray-600'],
                            'U' => ['bg' => 'bg-amber-400', 'border' => 'border-amber-400', 'ring' => 'focus:ring-amber-400', 'focus' => 'focus:border-amber-400', 'btn' => 'bg-gradient-to-r from-amber-400 to-amber-500', 'text' => 'text-gray-900', 'soft' => 'bg-amber-50', 'icon' => 'text-amber-600'],
                            'V' => ['bg' => 'bg-purple-500', 'border' => 'border-purple-500', 'ring' => 'focus:ring-purple-500', 'focus' => 'focus:border-purple-500', 'btn' => 'bg-gradient-to-r from-purple-500 to-purple-600', 'text' => 'text-white', 'soft' => 'bg-purple-50', 'icon' => 'text-purple-600'],
                            'W' => ['bg' => 'bg-green-500', 'border' => 'border-green-500', 'ring' => 'focus:ring-green-500', 'focus' => 'focus:border-green-500', 'btn' => 'bg-gradient-to-r from-green-500 to-green-600', 'text' => 'text-white', 'soft' => 'bg-green-50', 'icon' => 'text-green-600'],
                            'X' => ['bg' => 'bg-blue-500', 'border' => 'border-blue-500', 'ring' => 'focus:ring-blue-500', 'focus' => 'focus:border-blue-500', 'btn' => 'bg-gradient-to-r from-blue-500 to-blue-600', 'text' => 'text-white', 'soft' => 'bg-blue-50', 'icon' => 'text-blue-600'],
                            'Y' => ['bg' => 'bg-red-500', 'border' => 'border-red-500', 'ring' => 'focus:ring-red-500', 'focus' => 'focus:border-red-500', 'btn' => 'bg-gradient-to-r from-red-500 to-red-600', 'text' => 'text-white', 'soft' => 'bg-red-50', 'icon' => 'text-red-600'],
                        ];
                    @endphp

                    <div x-data="{ 
                        selectedPecahan: '{{ $selectedPecahan }}',
                        themes: {{ json_encode($themeClasses) }},
                        get currentTheme() { return this.themes[this.selectedPecahan] || null }
                    }" 
                    class="bg-gray-50/50 rounded-3xl p-6 mb-8 border transition-all duration-500"
                    :class="currentTheme ? currentTheme.border : 'border-gray-100'">
                        <form action="{{ route('hcts-receiving.index') }}" method="GET" class="space-y-6">
                            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                                <!-- Group 1: Periode & Anggaran -->
                                <div class="lg:col-span-5 grid grid-cols-2 md:grid-cols-4 gap-3 p-4 bg-white/50 rounded-2xl border border-gray-100 shadow-sm">
                                    <div class="col-span-2 md:col-span-4 mb-1">
                                        <span class="text-[9px] font-black uppercase tracking-[0.2em] text-rose-600/50">Periode & Anggaran</span>
                                    </div>
                                    <div class="col-span-1">
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Dari</label>
                                        <input name="start_date" type="date" value="{{ $startDate }}" 
                                            class="block w-full border-gray-100 rounded-xl transition-all text-xs py-2.5 text-center shadow-sm"
                                            :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-rose-500 focus:ring-rose-500'">
                                    </div>
                                    <div class="col-span-1">
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Sampai</label>
                                        <input name="end_date" type="date" value="{{ $endDate }}" 
                                            class="block w-full border-gray-100 rounded-xl transition-all text-xs py-2.5 text-center shadow-sm"
                                            :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-rose-500 focus:ring-rose-500'">
                                    </div>
                                    <div class="col-span-1">
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">TA</label>
                                        <select name="tahun_anggaran" 
                                                class="block w-full border-gray-100 rounded-xl transition-all text-xs py-2.5 font-bold text-center shadow-sm"
                                                :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-rose-500 focus:ring-rose-500'">
                                            <option value="">Semua</option>
                                            @foreach($availableYears as $year)
                                                <option value="{{ $year }}" {{ request('tahun_anggaran') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-span-1">
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Emisi</label>
                                        <select name="tahun_emisi" 
                                                class="block w-full border-gray-100 rounded-xl transition-all text-xs py-2.5 font-bold text-center shadow-sm"
                                                :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-rose-500 focus:ring-rose-500'">
                                            <option value="">Semua</option>
                                            @foreach($availableEmissions as $emisi)
                                                <option value="{{ $emisi }}" {{ request('tahun_emisi') == $emisi ? 'selected' : '' }}>{{ $emisi }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Group 2: Spesifikasi -->
                                <div class="lg:col-span-3 grid grid-cols-2 gap-3 p-4 bg-white/50 rounded-2xl border border-gray-100 shadow-sm h-full">
                                    <div class="col-span-2 mb-1">
                                        <span class="text-[9px] font-black uppercase tracking-[0.2em] text-rose-600/50">Spesifikasi</span>
                                    </div>
                                    <div class="col-span-1">
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Pecahan</label>
                                        <select name="pecahan" x-model="selectedPecahan"
                                                class="block w-full border-gray-100 rounded-xl transition-all text-xs py-2.5 font-black text-center shadow-sm"
                                                :class="currentTheme ? (currentTheme.bg + ' ' + currentTheme.text + ' ' + currentTheme.border) : 'focus:border-rose-500 focus:ring-rose-500'">
                                            <option value="" class="bg-white text-gray-900">Semua</option>
                                            @foreach(['S','T','U','V','W','X','Y'] as $p)
                                                <option value="{{ $p }}" class="bg-white text-gray-900" {{ $pecahanFilter == $p ? 'selected' : '' }}>{{ $p }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-span-1">
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Gilir</label>
                                        <select name="gilir" class="block w-full border-gray-100 rounded-xl transition-all text-xs py-2.5 font-bold text-center shadow-sm"
                                                :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-rose-500 focus:ring-rose-500'">
                                            <option value="">Semua</option>
                                            <option value="Gilir 1" {{ $gilirFilter == 'Gilir 1' ? 'selected' : '' }}>Gilir 1</option>
                                            <option value="Gilir 2" {{ $gilirFilter == 'Gilir 2' ? 'selected' : '' }}>Gilir 2</option>
                                            <option value="Gilir 3" {{ $gilirFilter == 'Gilir 3' ? 'selected' : '' }}>Gilir 3</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Group 3: Pencarian & Aksi -->
                                <div class="lg:col-span-4 flex flex-col md:flex-row gap-3 h-full">
                                    <div class="flex-1 p-4 bg-white/50 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between">
                                        <div>
                                            <div class="mb-1">
                                                <span class="text-[9px] font-black uppercase tracking-[0.2em] text-rose-600/50">Pencarian</span>
                                            </div>
                                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Cari Data</label>
                                            <input name="search" type="text" value="{{ $search }}" placeholder="No. Bon, Batch..." 
                                                class="block w-full border-gray-100 rounded-xl transition-all text-xs py-2.5 text-center shadow-sm focus:border-rose-500 focus:ring-rose-500">
                                        </div>
                                    </div>
                                    <div class="flex flex-row md:flex-col gap-2 min-w-[120px]">
                                        <button type="submit" class="flex-1 text-white font-black px-4 py-3 rounded-2xl transition-all active:scale-95 uppercase text-[10px] tracking-widest shadow-lg shadow-rose-100 flex items-center justify-center gap-2"
                                                :class="currentTheme ? currentTheme.btn : 'bg-gray-900 hover:bg-gray-800'">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                            Filter
                                        </button>
                                        <a href="{{ route('hcts-receiving.index') }}" class="flex-1 inline-flex items-center justify-center p-3 bg-white border border-gray-100 rounded-2xl text-gray-400 hover:text-rose-600 transition-all shadow-sm group">
                                            <svg class="w-4 h-4 transition-transform group-hover:rotate-180 duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 pt-6 border-t border-gray-100 flex flex-wrap gap-3 justify-end uppercase text-[10px] tracking-widest font-bold">
                                <a href="{{ route('hcts-receiving.export', ['start_date'=>$startDate, 'end_date'=>$endDate, 'search'=>$search, 'pecahan'=>$pecahanFilter, 'gilir'=>$gilirFilter]) }}" class="inline-flex items-center px-4 py-2 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-xl hover:bg-emerald-100 transition-all">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    Excel
                                </a>
                                <a href="{{ route('hcts-receiving.print', ['start_date'=>$startDate, 'end_date'=>$endDate, 'search'=>$search, 'pecahan'=>$pecahanFilter, 'gilir'=>$gilirFilter]) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-rose-50 text-rose-700 border border-rose-100 rounded-xl hover:bg-rose-100 transition-all">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 00-2 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                                    PDF / Print
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Summary Cards -->
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4 mb-8">
                        @foreach(['S', 'T', 'U', 'V', 'W', 'X', 'Y'] as $p)
                            @php
                                $pData = $themeClasses[$p] ?? ['bg' => 'bg-gray-500', 'soft' => 'bg-gray-50', 'icon' => 'text-gray-600'];
                                $totalP = $summaryData[$p] ?? 0;
                            @endphp
                            <div class="{{ $pData['soft'] }} border {{ $pData['border'] ?? 'border-gray-100' }} rounded-2xl p-4 transition-all hover:shadow-md">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black {{ $pData['bg'] }} text-white shadow-sm">{{ $p }}</span>
                                    <svg class="w-4 h-4 {{ $pData['icon'] }} opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m.599-2c-.516.494-1.284.814-2.128.814-1.47 0-2.678-.813-2.678-2.013 0-1.125.833-1.874 1.944-2.115" /></svg>
                                </div>
                                <p class="text-sm font-black text-gray-900">{{ number_format($totalP, 0, ',', '.') }}</p>
                            </div>
                        @endforeach

                        <!-- Grand Total -->
                        <div class="bg-rose-600 border border-rose-500 rounded-2xl p-4 shadow-lg shadow-rose-100 flex flex-col justify-center">
                            <p class="text-rose-100 text-[9px] font-black uppercase tracking-widest mb-1 leading-none">Total HCTS</p>
                            <p class="text-white text-sm font-black tracking-tight leading-none">{{ number_format($grandTotal ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto border border-gray-100 rounded-2xl shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50/50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-[10px] font-black text-gray-500 uppercase tracking-widest">Tanggal</th>
                                    <th class="px-6 py-4 text-left text-[10px] font-black text-gray-500 uppercase tracking-widest">No. Bon</th>
                                    <th class="px-6 py-4 text-center text-[10px] font-black text-gray-500 uppercase tracking-widest">Pecahan</th>
                                    <th class="px-6 py-4 text-center text-[10px] font-black text-gray-500 uppercase tracking-widest">Gilir</th>
                                    <th class="px-6 py-4 text-right text-[10px] font-black text-gray-500 uppercase tracking-widest">Jumlah</th>
                                    <th class="px-6 py-4 text-center text-[10px] font-black text-gray-500 uppercase tracking-widest">Batch/Seri</th>
                                    <th class="px-6 py-4 text-center text-[10px] font-black text-gray-500 uppercase tracking-widest">Emisi/TA</th>
                                    <th class="px-6 py-4 text-left text-[10px] font-black text-gray-500 uppercase tracking-widest">No. Segel</th>
                                    <th class="px-6 py-4 text-left text-[10px] font-black text-gray-500 uppercase tracking-widest">Petugas</th>
                                    <th class="px-6 py-4 text-center text-[10px] font-black text-gray-500 uppercase tracking-widest">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse ($receivings as $receiving)
                                    <tr class="hover:bg-rose-50/30 transition-colors group">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ \Carbon\Carbon::parse($receiving->tanggal_penerimaan)->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-black text-gray-900 underline decoration-gray-200 underline-offset-4">{{ $receiving->nomor_bon }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @php
                                                $pchClasses = [
                                                    'S' => 'bg-lime-500 text-white',
                                                    'T' => 'bg-gray-400 text-white',
                                                    'U' => 'bg-amber-400 text-white',
                                                    'V' => 'bg-purple-500 text-white',
                                                    'W' => 'bg-green-500 text-white',
                                                    'X' => 'bg-blue-500 text-white',
                                                    'Y' => 'bg-red-500 text-white',
                                                ];
                                                $currentClass = $pchClasses[$receiving->pecahan] ?? 'bg-gray-900 text-white';
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-black {{ $currentClass }} shadow-sm">{{ $receiving->pecahan }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-xs font-bold text-gray-500 uppercase italic">{{ $receiving->gilir }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-black text-rose-600">{{ number_format($receiving->jumlah, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-gray-700">{{ $receiving->batch }} / {{ $receiving->seri }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-[11px] font-bold text-gray-400 uppercase tracking-tighter">{{ $receiving->emisi }} / {{ $receiving->tahun_anggaran }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-indigo-600">{{ $receiving->nomor_segel }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-xs font-bold text-gray-500 uppercase tracking-tight">{{ $receiving->user->name ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if(in_array(auth()->user()->role, ['sortir', 'admin']))
                                                <div class="flex justify-center items-center space-x-3 transition-opacity">
                                                    <a href="{{ route('hcts-receiving.edit', $receiving->id) }}" class="text-indigo-400 hover:text-indigo-600 transition-colors p-1.5 hover:bg-indigo-50 rounded-lg">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                                    </a>
                                                    <form action="{{ route('hcts-receiving.destroy', $receiving->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-rose-400 hover:text-rose-600 transition-colors p-1.5 hover:bg-rose-50 rounded-lg">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <span class="text-gray-300">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-20 text-center text-gray-400 italic text-sm">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-12 h-12 mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                                                Belum ada data penerimaan HCTS.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6">
                        {{ $receivings->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
