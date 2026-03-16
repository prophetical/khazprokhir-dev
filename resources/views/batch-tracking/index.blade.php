<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Batch Tracking') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-5">

            {{-- ===== SEARCH & FILTER FORM ===== --}}
            @php
                $themeClasses = [
                    'S' => ['bg' => 'bg-lime-500', 'border' => 'border-lime-500', 'ring' => 'focus:ring-lime-500', 'focus' => 'focus:border-lime-500', 'btn' => 'bg-lime-500', 'text' => 'text-gray-900', 'label' => '', 'subtle' => 'bg-lime-50 border-lime-200 text-lime-700 hover:bg-lime-100'],
                    'T' => ['bg' => 'bg-gray-400', 'border' => 'border-gray-400', 'ring' => 'focus:ring-gray-400', 'focus' => 'focus:border-gray-400', 'btn' => 'bg-gray-400', 'text' => 'text-white', 'label' => '', 'subtle' => 'bg-gray-50 border-gray-200 text-gray-700 hover:bg-gray-100'],
                    'U' => ['bg' => 'bg-amber-400', 'border' => 'border-amber-400', 'ring' => 'focus:ring-amber-400', 'focus' => 'focus:border-amber-400', 'btn' => 'bg-amber-400', 'text' => 'text-gray-900', 'label' => '', 'subtle' => 'bg-amber-50 border-amber-200 text-amber-700 hover:bg-amber-100'],
                    'V' => ['bg' => 'bg-purple-500', 'border' => 'border-purple-500', 'ring' => 'focus:ring-purple-500', 'focus' => 'focus:border-purple-500', 'btn' => 'bg-purple-500', 'text' => 'text-white', 'label' => '', 'subtle' => 'bg-purple-50 border-purple-200 text-purple-700 hover:bg-purple-100'],
                    'W' => ['bg' => 'bg-green-500', 'border' => 'border-green-500', 'ring' => 'focus:ring-green-500', 'focus' => 'focus:border-green-500', 'btn' => 'bg-green-500', 'text' => 'text-white', 'label' => '', 'subtle' => 'bg-green-50 border-green-200 text-green-700 hover:bg-green-100'],
                    'X' => ['bg' => 'bg-blue-500', 'border' => 'border-blue-500', 'ring' => 'focus:ring-blue-500', 'focus' => 'focus:border-blue-500', 'btn' => 'bg-blue-500', 'text' => 'text-white', 'label' => '', 'subtle' => 'bg-blue-50 border-blue-200 text-blue-700 hover:bg-blue-100'],
                    'Y' => ['bg' => 'bg-red-500', 'border' => 'border-red-500', 'ring' => 'focus:ring-red-500', 'focus' => 'focus:border-red-500', 'btn' => 'bg-red-500', 'text' => 'text-white', 'label' => '', 'subtle' => 'bg-red-50 border-red-200 text-red-700 hover:bg-red-100'],
                ];
                $selectedPecahan = $pecahanFilter ?? '';
            @endphp

            <div x-data="{ 
                selectedPecahan: '{{ $selectedPecahan }}',
                themes: {{ json_encode($themeClasses) }},
                get currentTheme() { return this.themes[this.selectedPecahan] || null },
                setPecahan(val) {
                    if (this.selectedPecahan === val) {
                        this.selectedPecahan = '';
                    } else {
                        this.selectedPecahan = val;
                    }
                    this.$nextTick(() => { document.getElementById('filterForm').submit(); });
                }
            }" 
            class="bg-white shadow-sm sm:rounded-xl border-t-4 transition-all duration-500 overflow-hidden"
            :class="currentTheme ? currentTheme.border : 'border-gray-100'">
                <form id="filterForm" action="{{ route('batch-tracking.index') }}" method="GET">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Cari & Filter</h3>
                        @if($search || $startDate || $endDate || $pecahanFilter)
                            <a href="{{ route('batch-tracking.index') }}" class="ml-auto text-[10px] font-bold text-red-500 uppercase tracking-widest flex items-center gap-1 transition-colors hover:text-red-700">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Reset Filter
                            </a>
                        @endif
                    </div>
                    <div class="px-6 py-6 flex flex-wrap gap-6 items-end">

                        {{-- Keyword Search --}}
                        <div class="flex-1 min-w-[220px]">
                            <label for="search" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Cari Batch / Seri</label>
                            <div class="relative">
                                <input id="search" name="search" type="text"
                                    value="{{ $search }}"
                                    placeholder="Cari..."
                                    class="block w-full text-sm border-gray-200 rounded-lg shadow-sm px-4 py-3 text-center transition-all duration-300" 
                                    :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'"/>
                            </div>
                        </div>

                        {{-- Start Date --}}
                        <div class="min-w-[180px]">
                            <label for="start_date" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Dari Tanggal</label>
                            <input id="start_date" name="start_date" type="date"
                                value="{{ $startDate }}"
                                class="block w-full text-sm border-gray-200 rounded-lg shadow-sm px-4 py-3 text-center transition-all duration-300" 
                                :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'"/>
                        </div>

                        {{-- End Date --}}
                        <div class="min-w-[180px]">
                            <label for="end_date" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Sampai Tanggal</label>
                            <input id="end_date" name="end_date" type="date"
                                value="{{ $endDate }}"
                                class="block w-full text-sm border-gray-200 rounded-lg shadow-sm px-4 py-3 text-center transition-all duration-300"
                                :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'"/>
                        </div>

                        {{-- Submit --}}
                        <div class="flex gap-2">
                            <button type="submit"
                                class="inline-flex items-center justify-center gap-1.5 px-6 py-3 rounded-lg font-bold text-xs uppercase tracking-widest transition-all shadow-md active:scale-95"
                                :class="currentTheme ? (currentTheme.btn + ' ' + currentTheme.text + ' brightness-95 hover:brightness-105') : 'bg-gray-800 text-white'">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                Cari
                            </button>
                        </div>

                    </div>

                    {{-- Pecahan Filter Buttons --}}
                    <input type="hidden" name="pecahan" x-model="selectedPecahan">
                    <div class="px-6 pb-6 pt-2">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mr-2">Filter Pecahan:</span>
                            @foreach(['S', 'T', 'U', 'V', 'W', 'X', 'Y'] as $pec)
                                <button type="button"
                                    @click="setPecahan('{{ $pec }}')"
                                    class="inline-flex flex-col items-center px-4 py-2 rounded-xl border-2 text-xs font-black transition-all shadow-sm active:scale-95"
                                    :class="selectedPecahan === '{{ $pec }}' ? (themes['{{ $pec }}'].bg + ' ' + themes['{{ $pec }}'].border + ' ' + themes['{{ $pec }}'].text) : (themes['{{ $pec }}'].subtle)">
                                    <span class="text-base leading-none">{{ $pec }}</span>
                                    <span class="text-[8px] font-bold leading-none mt-1 opacity-80 uppercase tracking-tighter">{{ $themeClasses[$pec]['label'] }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Active filter tags --}}
                    @if($search || $startDate || $endDate || $pecahanFilter)
                        <div class="px-6 pb-4 flex flex-wrap gap-2">
                            @if($search)
                                <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-200">
                                    Keyword: <strong class="font-mono ml-1">{{ strtoupper($search) }}</strong>
                                </span>
                            @endif
                            @if($startDate)
                                <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-200">
                                    Dari: <span class="ml-1">{{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }}</span>
                                </span>
                            @endif
                            @if($pecahanFilter)
                                @php
                                    $theme = $themeClasses[$pecahanFilter] ?? null;
                                    $bgColor = $theme ? str_replace('bg-', 'bg-', $theme['bg']) : 'bg-gray-100';
                                    $textColor = $theme ? $theme['text'] : 'text-gray-700';
                                @endphp
                                <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $bgColor }} {{ $textColor }} brightness-95">
                                    Pecahan: <strong class="ml-1">{{ $pecahanFilter }} · {{ $themeClasses[$pecahanFilter]['label'] }}</strong>
                                </span>
                            @endif
                            @if($endDate)
                                <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-200">
                                    Sampai: <span class="ml-1">{{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}</span>
                                </span>
                            @endif
                        </div>
                    @endif
                </form>
            </div>
            {{-- ===== END FILTER FORM ===== --}}

            @if(count($trackingData) === 0)
                <div class="bg-white shadow-sm sm:rounded-lg p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-700">
                        {{ ($search || $startDate || $endDate || $pecahanFilter) ? 'Tidak ada data yang sesuai filter' : 'Belum ada data penerimaan' }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ ($search || $startDate || $endDate || $pecahanFilter) ? 'Coba ubah kata kunci, rentang tanggal, atau filter pecahan.' : 'Data batch tracking akan muncul setelah ada input penerimaan HCS.' }}
                    </p>
                </div>
            @else
                {{-- Result count --}}
                <div class="flex items-center justify-between px-1">
                    <span class="text-sm text-gray-500">Menampilkan <strong class="text-gray-700">{{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}</strong> dari <strong class="text-gray-700">{{ $paginator->total() }}</strong> kombinasi batch/seri</span>
                    {{-- Legend --}}
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs">
                        <span class="flex items-center gap-1.5"><span class="inline-block w-3.5 h-3.5 rounded bg-blue-200 border border-blue-400"></span><span class="text-gray-600">Cutpack</span></span>
                        <span class="flex items-center gap-1.5"><span class="inline-block w-3.5 h-3.5 rounded bg-green-200 border border-green-400"></span><span class="text-gray-600">Rikyet</span></span>
                        <span class="flex items-center gap-1.5"><span class="inline-block w-3.5 h-3.5 rounded bg-gray-100 border border-gray-300"></span><span class="text-gray-600">Belum diinput</span></span>
                    </div>
                </div>

                @foreach($trackingData as $idx => $item)
                    @php
                        $cardId = 'detail-' . Str::slug($item['batch']) . '-' . Str::slug($item['seri']);
                    @endphp

                    <div class="bg-white shadow-sm sm:rounded-xl overflow-visible border border-gray-100">

                        {{-- Card Header --}}
                        <div class="px-6 py-4 bg-gradient-to-r from-slate-50 to-white border-b border-gray-100 flex flex-wrap items-center gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="flex-shrink-0 text-xs font-semibold text-slate-400">#{{ $idx + 1 }}</span>
                                <div class="flex flex-wrap items-center gap-x-3 gap-y-0.5">
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-xs text-gray-400 uppercase tracking-wide">Batch</span>
                                        <span class="font-mono font-bold text-gray-800 text-base">{{ strtoupper($item['batch']) }}</span>
                                    </div>
                                    <span class="text-gray-300">/</span>
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-xs text-gray-400 uppercase tracking-wide">Seri</span>
                                        <span class="font-mono font-bold text-indigo-700 text-base">{{ strtoupper($item['seri']) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center gap-2 ml-auto">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700">{{ $item['total_packs'] }} pack</span>
                                @if($item['cutpack'] > 0)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700"><span class="w-1.5 h-1.5 rounded-full bg-blue-400 inline-block"></span>Cutpack: {{ $item['cutpack'] }}</span>
                                @endif
                                @if($item['rikyet'] > 0)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700"><span class="w-1.5 h-1.5 rounded-full bg-green-400 inline-block"></span>Rikyet: {{ $item['rikyet'] }}</span>
                                @endif
                                <span class="text-xs text-gray-400">Bilyet: <strong class="text-gray-600">{{ number_format($item['total_jumlah'], 0, ',', '.') }}</strong></span>
                            </div>
                        </div>

                        {{-- Action Bar --}}
                        <div class="px-6 py-3 bg-gray-50/50 border-b border-gray-100 flex flex-wrap items-center gap-2">
                            @if($item['total_packs'] > 0)
                                <button type="button" onclick="toggleDetail('{{ $cardId }}')" id="btn-{{ $cardId }}"
                                    class="inline-flex items-center gap-1.5 text-xs font-medium text-indigo-600 hover:text-indigo-900 px-3 py-1.5 rounded-md border border-indigo-200 hover:bg-indigo-50 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    Lihat Detail Pack
                                </button>
                            @endif

                            @foreach($item['pecahan_groups'] as $pecahan => $packMap)
                                @php
                                    $modalId = 'modal-' . Str::slug($item['batch']) . '-' . Str::slug($item['seri']) . '-' . $pecahan;
                                    $pecahanLabel = ['S'=>'S · Rp1.000','T'=>'T · Rp2.000','U'=>'U · Rp5.000','V'=>'V · Rp10.000','W'=>'W · Rp20.000','X'=>'X · Rp50.000','Y'=>'Y · Rp100.000'][$pecahan] ?? $pecahan;
                                @endphp
                                <button type="button" onclick="openModal('{{ $modalId }}')"
                                    class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-md border transition-all active:scale-95 shadow-sm {{ $themeClasses[$pecahan]['subtle'] }}">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                    Rekap Pecahan {{ $pecahan }} Batch {{ $item['batch'] }}
                                </button>

                                {{-- ===== MODAL REKAP GRID ===== --}}
                                <div id="{{ $modalId }}" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
                                    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl flex flex-col max-h-[95vh] border-t-8 {{ $themeClasses[$pecahan]['border'] }}">

                                        {{-- Modal Header --}}
                                        <div class="flex items-center justify-between px-8 py-6 border-b border-gray-100 flex-shrink-0 bg-slate-50/50 rounded-t-2xl">
                                            <div class="flex items-center gap-4">
                                                <div class="p-3 rounded-xl {{ $themeClasses[$pecahan]['bg'] }} shadow-lg shadow-inner">
                                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                                </div>
                                                <div>
                                                    <h4 class="text-xl font-black text-gray-900 uppercase tracking-tighter leading-none">Rekap Grid Pack</h4>
                                                    <div class="mt-1.5 flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-400 font-bold uppercase tracking-widest">
                                                        <span class="flex items-center gap-1.5">Batch: <span class="text-gray-700 font-mono">{{ strtoupper($item['batch']) }}</span></span>
                                                        <span class="text-gray-300">/</span>
                                                        <span class="flex items-center gap-1.5">Seri: <span class="text-indigo-600 font-mono">{{ strtoupper($item['seri']) }}</span></span>
                                                        <span class="text-gray-300">/</span>
                                                        <span>Pecahan: <span class="{{ $themeClasses[$pecahan]['text'] }} {{ $themeClasses[$pecahan]['bg'] }} px-2 py-0.5 rounded text-[10px]">{{ $pecahan }}</span></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" onclick="closeModal('{{ $modalId }}')" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all duration-300">
                                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </div>

                                        {{-- Modal Layout: Info Sidebar + Grid --}}
                                        <div class="flex-1 overflow-hidden flex flex-col lg:flex-row">
                                            
                                            {{-- Sidebar: Legend & Stats --}}
                                            <div class="w-full lg:w-64 bg-gray-50/50 border-r border-gray-100 p-5 overflow-y-auto space-y-5 flex-shrink-0">
                                                
                                                {{-- Legend Group --}}
                                                <div>
                                                    <h5 class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-3">Keterangan Warna</h5>
                                                    <div class="space-y-2.5">
                                                        <div class="flex items-center gap-2.5">
                                                            <div class="w-7 h-7 rounded-lg bg-blue-100 border-2 border-blue-300 shadow-sm flex-shrink-0"></div>
                                                            <div class="flex flex-col">
                                                                <span class="text-[10px] font-black text-blue-800 leading-none">CUTPACK</span>
                                                                <span class="text-[9px] font-medium text-blue-400 mt-0.5 uppercase">Belum Sortir</span>
                                                            </div>
                                                        </div>
                                                        <div class="flex items-center gap-2.5">
                                                            <div class="w-7 h-7 rounded-lg bg-blue-600 border-2 border-blue-800 shadow-md flex-shrink-0"></div>
                                                            <div class="flex flex-col">
                                                                <span class="text-[10px] font-black text-blue-900 leading-none">CUTPACK</span>
                                                                <span class="text-[9px] font-bold text-blue-600 mt-0.5 uppercase">Sudah Sortir</span>
                                                            </div>
                                                        </div>
                                                        <div class="h-px bg-gray-200 my-3"></div>
                                                        <div class="flex items-center gap-2.5">
                                                            <div class="w-7 h-7 rounded-lg bg-green-100 border-2 border-green-300 shadow-sm flex-shrink-0"></div>
                                                            <div class="flex flex-col">
                                                                <span class="text-[10px] font-black text-green-800 leading-none">RIKYET</span>
                                                                <span class="text-[9px] font-medium text-green-400 mt-0.5 uppercase">Belum Sortir</span>
                                                            </div>
                                                        </div>
                                                        <div class="flex items-center gap-2.5">
                                                            <div class="w-7 h-7 rounded-lg bg-green-600 border-2 border-green-800 shadow-md flex-shrink-0"></div>
                                                            <div class="flex flex-col">
                                                                <span class="text-[10px] font-black text-green-900 leading-none">RIKYET</span>
                                                                <span class="text-[9px] font-bold text-green-600 mt-0.5 uppercase">Sudah Sortir</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Stats Summary --}}
                                                @php
                                                    $pFilled  = count($packMap);
                                                    $pSorted  = 0;
                                                    foreach($packMap as $cm) {
                                                        if ($cm['sorted'] ?? false) $pSorted++;
                                                    }
                                                    $pUnsorted = $pFilled - $pSorted;
                                                    $diterima = $pFilled > 0 ? round(($pSorted / $pFilled) * 100) : 0;
                                                @endphp
                                                <div>
                                                    <h5 class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-3">Statistik Input</h5>
                                                    <div class="grid grid-cols-1 gap-1.5">
                                                        <div class="bg-white p-2.5 rounded-lg border border-gray-100 shadow-sm">
                                                            <div class="text-[8px] font-bold text-gray-400 uppercase">Input / Total</div>
                                                            <div class="text-base font-black text-gray-900">{{ $pFilled }} <span class="text-[9px] text-gray-400 font-medium">/ 100</span></div>
                                                        </div>
                                                        <div class="bg-indigo-600 p-2.5 rounded-lg shadow-md">
                                                            <div class="text-[8px] font-bold text-indigo-200 uppercase">Sudah Sortir</div>
                                                            <div class="text-base font-black text-white">{{ $pSorted }} <span class="text-[9px] text-indigo-300 font-medium lowercase">packs</span></div>
                                                        </div>
                                                        <div class="bg-white p-2.5 rounded-lg border border-gray-100 shadow-sm">
                                                            <div class="text-[8px] font-bold text-gray-400 uppercase">SORTIR/PENERIMAAN</div>
                                                            <div class="text-base font-black text-gray-900">{{ $diterima }}%</div>
                                                            <div class="w-full bg-gray-100 h-1 rounded-full mt-1.5 overflow-hidden">
                                                                <div class="bg-indigo-600 h-full rounded-full transition-all duration-1000" style="width: {{ $diterima }}%"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Grid Area --}}
                                            <div class="flex-1 p-4 lg:p-6 overflow-auto bg-white flex justify-center items-center">
                                                <div class="grid grid-cols-10 gap-1.2 w-full max-w-fit mx-auto">
                                                    @for($r = 0; $r < 10; $r++)
                                                        @for($c = 0; $c < 10; $c++)
                                                            @php
                                                                $p = ($c * 10) + $r + 1;
                                                                $cell  = $packMap[$p] ?? null;
                                                                $months = ['','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
                                                                $pecTheme = $themeClasses[$pecahan] ?? [
                                                                    'bg' => 'bg-slate-500', 
                                                                    'border' => 'border-slate-500', 
                                                                    'text' => 'text-white'
                                                                ];

                                                                if ($cell) {
                                                                    $carbon    = \Carbon\Carbon::parse($cell['date']);
                                                                    $dateLabel = $carbon->day . ' ' . $months[$carbon->month] . ' ' . $carbon->year . ', ' . $carbon->format('H:i');
                                                                    $isSorted  = !empty($cell['sorted']);
                                                                    
                                                                    if ($cell['supplier'] === 'Cutpack') {
                                                                        $cellClass = $isSorted 
                                                                            ? 'bg-blue-600 border-blue-800 text-white shadow-md ring-2 ring-inset ' . str_replace('border-', 'ring-', $pecTheme['border'])
                                                                            : 'bg-blue-100 border-blue-300 text-blue-800';
                                                                    } else {
                                                                        $cellClass = $isSorted
                                                                            ? 'bg-green-600 border-green-800 text-white shadow-md ring-2 ring-inset ' . str_replace('border-', 'ring-', $pecTheme['border'])
                                                                            : 'bg-green-100 border-green-300 text-green-800';
                                                                    }
                                                                } else {
                                                                    $dateLabel = '';
                                                                    $cellClass = 'bg-gray-50 border-gray-200 text-gray-300 hover:border-gray-400';
                                                                }
                                                            @endphp
                                                            @php
                                                                // r = row index (0-9), c = col index (0-9)
                                                                $vClass = ($r < 4) ? 'top-full mt-2 flex-col-reverse' : 'bottom-full mb-2 flex-col';
                                                                $arrowV = ($r < 4) ? '-mb-1' : '-mt-1';
                                                                
                                                                if ($c < 3) {
                                                                    $hClass = 'left-0 translate-x-0';
                                                                    $arrowH = 'left-3 translate-x-0';
                                                                } elseif ($c > 6) {
                                                                    $hClass = 'right-0 left-auto translate-x-0';
                                                                    $arrowH = 'right-3 translate-x-0';
                                                                } else {
                                                                    $hClass = 'left-1/2 -translate-x-1/2';
                                                                    $arrowH = 'left-1/2 -translate-x-1/2';
                                                                }
                                                            @endphp
                                                            <div class="relative group w-8 h-8 flex items-center justify-center rounded-lg border-2 font-black text-[10px] cursor-default select-none transition-all duration-300 hover:scale-110 hover:z-50 {{ $cellClass }}">
                                                                {{ $p }}
                                                                @if($cell)
                                                                    <div class="pointer-events-none absolute {{ $vClass }} {{ $hClass }} z-[100] hidden group-hover:flex items-center">
                                                                        <div class="bg-gray-900/95 backdrop-blur-sm text-white text-[10px] rounded-xl px-3 py-2 whitespace-nowrap shadow-2xl text-center leading-tight border border-white/10">
                                                                            <div class="font-black border-b border-white/20 pb-1.5 mb-1.5 flex items-center justify-center gap-2">
                                                                                PACK {{ $p }}
                                                                                @if(!empty($cell['sorted']))
                                                                                    <span class="px-2 py-0.5 rounded-full bg-green-500 text-[8px] text-white">TERSORTIR</span>
                                                                                @endif
                                                                            </div>
                                                                            <div class="font-bold {{ ($cell['supplier'] ?? '') === 'Cutpack' ? 'text-blue-300' : 'text-green-300' }} uppercase tracking-tighter">{{ $cell['supplier'] ?? '' }}</div>
                                                                            <div class="text-gray-400 text-[9px] mt-1 font-medium">{{ $dateLabel }}</div>
                                                                        </div>
                                                                        <div class="w-2 h-2 bg-gray-900 rotate-45 {{ $arrowV }} {{ $arrowH }}"></div>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endfor
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Modal Footer --}}
                                        <div class="px-8 py-4 border-t border-gray-100 flex justify-between items-center bg-gray-50/50 rounded-b-2xl flex-shrink-0">
                                            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                                * Pastikan data yang anda input selalu sesuai dengan fisik
                                            </div>
                                            <button type="button" onclick="closeModal('{{ $modalId }}')"
                                                class="px-8 py-2.5 text-xs font-black uppercase tracking-widest rounded-xl bg-gray-900 text-white hover:bg-black transition-all shadow-lg shadow-gray-200 active:scale-95">
                                                Selesai
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                {{-- ===== END MODAL ===== --}}
                            @endforeach
                        </div>

                        {{-- Expandable Detail Pack --}}
                        @if($item['total_packs'] > 0)
                            <div id="{{ $cardId }}" class="hidden px-6 py-5 border-t border-gray-50">
                                <div class="mb-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                    Semua pack — Batch <span class="font-mono text-gray-700">{{ strtoupper($item['batch']) }}</span> / Seri <span class="font-mono text-indigo-600">{{ strtoupper($item['seri']) }}</span>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($item['packs'] as $pack)
                                        @php
                                            $carbon    = \Carbon\Carbon::parse($pack->created_at);
                                            $months    = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                                            $dateLabel = $carbon->day.' '.$months[$carbon->month].' '.$carbon->year.', '.$carbon->format('H:i').' WIB';
                                            $pPecahan  = $pack->hcsReceiving->pecahan ?? '?';
                                            $colorCls  = $pack->supplier === 'Cutpack'
                                                ? 'bg-blue-200 border-blue-400 text-blue-900 hover:bg-blue-300'
                                                : 'bg-green-200 border-green-400 text-green-900 hover:bg-green-300';
                                        @endphp
                                        <div class="relative group cursor-default select-none w-11 h-11 flex items-center justify-center rounded-lg border-2 font-bold text-sm transition-all {{ $colorCls }}">
                                            {{ $pack->pack_number }}
                                            <div class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-2 z-50 hidden group-hover:flex flex-col items-center">
                                                <div class="bg-gray-900 text-white text-xs rounded-lg px-3 py-2 whitespace-nowrap shadow-xl text-center leading-relaxed">
                                                    <div class="font-semibold">Pack {{ $pack->pack_number }}</div>
                                                    <div class="text-gray-300">{{ $pack->supplier }} · Pec. {{ $pPecahan }}</div>
                                                    <div class="text-gray-400 mt-0.5">{{ $dateLabel }}</div>
                                                </div>
                                                <div class="w-2 h-2 bg-gray-900 rotate-45 -mt-1"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-3 pt-3 border-t border-gray-100 flex flex-wrap gap-4 text-xs text-gray-500">
                                    <span>Total: <strong class="text-gray-700">{{ $item['total_packs'] }} pack</strong></span>
                                    @if($item['cutpack'] > 0)<span class="text-blue-600">Cutpack: <strong>{{ $item['cutpack'] }}</strong></span>@endif
                                    @if($item['rikyet'] > 0)<span class="text-green-600">Rikyet: <strong>{{ $item['rikyet'] }}</strong></span>@endif
                                    <span>Total Bilyet: <strong class="text-gray-700">{{ number_format($item['total_jumlah'], 0, ',', '.') }}</strong></span>
                                </div>
                            </div>
                        @endif

                    </div>{{-- end card --}}
                @endforeach

                {{-- Pagination --}}
                @if($paginator->hasPages())
                    <div class="flex items-center justify-between px-1">
                        <p class="text-sm text-gray-500">
                            Halaman <strong>{{ $paginator->currentPage() }}</strong> dari <strong>{{ $paginator->lastPage() }}</strong>
                        </p>
                        <div class="flex items-center gap-1">
                            {{-- Previous --}}
                            @if($paginator->onFirstPage())
                                <span class="inline-flex items-center px-3 py-1.5 text-sm text-gray-300 bg-white border border-gray-200 rounded-md cursor-not-allowed">
                                    &laquo; Sebelumnya
                                </span>
                            @else
                                <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex items-center px-3 py-1.5 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                                    &laquo; Sebelumnya
                                </a>
                            @endif

                            {{-- Page numbers --}}
                            @foreach($paginator->getUrlRange(max(1, $paginator->currentPage()-2), min($paginator->lastPage(), $paginator->currentPage()+2)) as $pageNum => $url)
                                @if($pageNum == $paginator->currentPage())
                                    <span class="inline-flex items-center justify-center w-9 h-9 text-sm font-semibold text-white bg-indigo-600 border border-indigo-600 rounded-md">
                                        {{ $pageNum }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="inline-flex items-center justify-center w-9 h-9 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                                        {{ $pageNum }}
                                    </a>
                                @endif
                            @endforeach

                            {{-- Next --}}
                            @if($paginator->hasMorePages())
                                <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex items-center px-3 py-1.5 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                                    Berikutnya &raquo;
                                </a>
                            @else
                                <span class="inline-flex items-center px-3 py-1.5 text-sm text-gray-300 bg-white border border-gray-200 rounded-md cursor-not-allowed">
                                    Berikutnya &raquo;
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
            @endif


        </div>
    </div>

    @push('css')
    <style>
        /* Dark Mode Refinements for Batch Tracking Page */
        body.dark-mode .bg-white {
            background-color: #111827 !important;
            color: #f3f4f6 !important;
        }
        body.dark-mode .border-gray-100,
        body.dark-mode .border-gray-50,
        body.dark-mode .border-t-4.border-gray-100 {
            border-color: #313131 !important;
        }

        /* Header Card Gradients - Fix for Dark Mode */
        body.dark-mode .bg-gradient-to-r.from-slate-50.to-white {
            background: #1f2937 !important;
        }

        /* Action Bar & Modal Sections */
        body.dark-mode .bg-gray-50\/50,
        body.dark-mode .bg-gray-50\/30,
        body.dark-mode .bg-slate-50\/50 {
            background-color: #1a2232 !important;
        }

        /* Text Contrast Improvements */
        body.dark-mode .text-gray-400,
        body.dark-mode .text-slate-400,
        body.dark-mode .text-gray-500 {
            color: #9ca3af !important;
        }
        body.dark-mode .text-gray-800,
        body.dark-mode .text-gray-700,
        body.dark-mode .text-gray-900 {
            color: #f9fafb !important;
        }
        body.dark-mode .text-indigo-700,
        body.dark-mode .text-indigo-600 {
            color: #a5b4fc !important;
        }

        /* Modal specific */
        body.dark-mode div[id^="modal-"] .bg-white {
            background-color: #111827 !important;
        }

        /* Badges */
        body.dark-mode .bg-indigo-50 {
            background-color: rgba(79, 70, 229, 0.2) !important;
            color: #a5b4fc !important;
        }
        body.dark-mode .bg-blue-100 {
            background-color: rgba(30, 64, 175, 0.2) !important;
            color: #93c5fd !important;
        }
        body.dark-mode .bg-green-100 {
            background-color: rgba(6, 74, 24, 0.2) !important;
            color: #6ee7b7 !important;
        }
        
        body.dark-mode .bg-gray-100 {
            background-color: #374151 !important;
            color: #d1d5db !important;
        }
    </style>
    @endpush


    @push('scripts')
    <script>
        function toggleDetail(id) {
            const el  = document.getElementById(id);
            const btn = document.getElementById('btn-' + id);
            if (!el) return;
            const opening = el.classList.contains('hidden');
            el.classList.toggle('hidden', !opening);
            if (btn) btn.innerHTML = opening
                ? '<svg class="w-3.5 h-3.5" style="transform:rotate(180deg)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg> Tutup Detail'
                : '<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg> Lihat Detail Pack';
        }

        function openModal(id) {
            const m = document.getElementById(id);
            if (!m) return;
            m.classList.remove('hidden');
            m.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(id) {
            const m = document.getElementById(id);
            if (!m) return;
            m.classList.add('hidden');
            m.classList.remove('flex');
            document.body.style.overflow = '';
        }

        // Backdrop click
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('fixed') && e.target.classList.contains('inset-0')) {
                closeModal(e.target.id);
            }
        });

        // ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.fixed.inset-0:not(.hidden)').forEach(m => closeModal(m.id));
            }
        });

        // Auto-uppercase search input
        const searchInput = document.getElementById('search');
        if (searchInput) searchInput.addEventListener('input', () => {
            const pos = searchInput.selectionStart;
            searchInput.value = searchInput.value.toUpperCase();
            searchInput.setSelectionRange(pos, pos);
        });
    </script>
    @endpush
</x-app-layout>
