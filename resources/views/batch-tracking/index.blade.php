<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Batch Tracking') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-5">

            {{-- ===== SEARCH & FILTER FORM ===== --}}
            <div class="bg-white shadow-sm sm:rounded-xl border border-gray-100">
                <form action="{{ route('batch-tracking.index') }}" method="GET">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
                        <h3 class="text-sm font-semibold text-gray-700">Cari & Filter</h3>
                        @if($search || $startDate || $endDate || $pecahanFilter)
                            <a href="{{ route('batch-tracking.index') }}" class="ml-auto text-xs text-red-500 hover:text-red-700 flex items-center gap-1 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Reset Filter
                            </a>
                        @endif
                    </div>
                    <div class="px-6 py-4 flex flex-wrap gap-4 items-end">

                        {{-- Keyword Search --}}
                        <div class="flex-1 min-w-[180px]">
                            <label for="search" class="block text-xs font-medium text-gray-500 mb-1">Cari Batch / Seri</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                                <input id="search" name="search" type="text"
                                    value="{{ $search }}"
                                    placeholder="Contoh: 1322001 atau AA-BA3"
                                    class="pl-9 block w-full text-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm font-mono uppercase" />
                            </div>
                        </div>

                        {{-- Start Date --}}
                        <div class="min-w-[160px]">
                            <label for="start_date" class="block text-xs font-medium text-gray-500 mb-1">Tanggal Dari</label>
                            <input id="start_date" name="start_date" type="date"
                                value="{{ $startDate }}"
                                class="block w-full text-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
                        </div>

                        {{-- End Date --}}
                        <div class="min-w-[160px]">
                            <label for="end_date" class="block text-xs font-medium text-gray-500 mb-1">Tanggal Sampai</label>
                            <input id="end_date" name="end_date" type="date"
                                value="{{ $endDate }}"
                                class="block w-full text-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
                        </div>

                        {{-- Submit --}}
                        <div class="flex gap-2">
                            <button type="submit"
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                Cari
                            </button>
                        </div>

                    </div>

                    {{-- Pecahan Filter Buttons --}}
                    <input type="hidden" id="pecahan_input" name="pecahan" value="{{ $pecahanFilter }}">
                    <div class="px-6 pb-4 flex flex-wrap items-center gap-2">
                        <span class="text-xs font-medium text-gray-500">Filter Pecahan:</span>
                        @php
                            $pecahanList = [
                                'S' => ['label' => 'S · Rp1.000',  'active' => 'bg-stone-600 border-stone-700 text-white',   'idle' => 'bg-stone-100   border-stone-400  text-stone-700  hover:bg-stone-200'],
                                'T' => ['label' => 'T · Rp2.000',  'active' => 'bg-slate-500  border-slate-600  text-white',   'idle' => 'bg-slate-100   border-slate-400  text-slate-700  hover:bg-slate-200'],
                                'U' => ['label' => 'U · Rp5.000',  'active' => 'bg-orange-500 border-orange-600 text-white',   'idle' => 'bg-orange-100  border-orange-400 text-orange-700 hover:bg-orange-200'],
                                'V' => ['label' => 'V · Rp10.000', 'active' => 'bg-purple-600 border-purple-700 text-white',   'idle' => 'bg-purple-100  border-purple-400 text-purple-700 hover:bg-purple-200'],
                                'W' => ['label' => 'W · Rp20.000', 'active' => 'bg-green-600  border-green-700  text-white',   'idle' => 'bg-green-100   border-green-400  text-green-700  hover:bg-green-200'],
                                'X' => ['label' => 'X · Rp50.000', 'active' => 'bg-blue-600   border-blue-700   text-white',   'idle' => 'bg-blue-100    border-blue-400   text-blue-700   hover:bg-blue-200'],
                                'Y' => ['label' => 'Y · Rp100.000','active' => 'bg-red-600    border-red-700    text-white',   'idle' => 'bg-red-100    border-red-400    text-red-700    hover:bg-red-200'],
                            ];
                        @endphp
                        @foreach($pecahanList as $key => $pec)
                            @php
                                $isActive  = $pecahanFilter === $key;
                                $btnClass  = $isActive ? $pec['active'] : $pec['idle'];
                            @endphp
                            <button type="button"
                                onclick="setPecahan('{{ $key }}', this)"
                                data-pecahan="{{ $key }}"
                                class="pecahan-btn inline-flex flex-col items-center px-3 py-1.5 rounded-lg border-2 text-xs font-bold transition-all shadow-sm {{ $btnClass }}">
                                <span class="text-base leading-none">{{ $key }}</span>
                                <span class="text-[9px] font-normal leading-none mt-0.5 opacity-80">{{ explode(' · ', $pec['label'])[1] }}</span>
                            </button>
                        @endforeach
                    </div>

                    {{-- Active filter tags --}}
                    @if($search || $startDate || $endDate || $pecahanFilter)
                        <div class="px-6 pb-4 flex flex-wrap gap-2">
                            @if($search)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-200">
                                    Keyword: <strong class="font-mono">{{ strtoupper($search) }}</strong>
                                </span>
                            @endif
                            @if($startDate)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                    Dari: {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }}
                                </span>
                            @endif
                            @if($pecahanFilter)
                                @php
                                    $pecColors = ['S'=>'stone','T'=>'slate','U'=>'orange','V'=>'purple','W'=>'green','X'=>'blue','Y'=>'red'];
                                    $pc = $pecColors[$pecahanFilter] ?? 'gray';
                                    $pecLabels = ['S'=>'Rp1.000','T'=>'Rp2.000','U'=>'Rp5.000','V'=>'Rp10.000','W'=>'Rp20.000','X'=>'Rp50.000','Y'=>'Rp100.000'];
                                @endphp
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-{{ $pc }}-50 text-{{ $pc }}-700 border border-{{ $pc }}-300">
                                    Pecahan: <strong>{{ $pecahanFilter }} · {{ $pecLabels[$pecahanFilter] ?? '' }}</strong>
                                </span>
                            @endif
                            @if($endDate)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                    Sampai: {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}
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
                                    class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-md border bg-amber-50 border-amber-300 text-amber-700 hover:bg-amber-100 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                    Rekap Grid · Pec. {{ $pecahan }}
                                </button>

                                {{-- ===== MODAL REKAP GRID ===== --}}
                                <div id="{{ $modalId }}" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
                                    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl flex flex-col max-h-[90vh]">

                                        {{-- Modal Header --}}
                                        <div class="flex items-start justify-between px-6 py-4 border-b border-gray-100 flex-shrink-0">
                                            <div>
                                                <h4 class="text-base font-bold text-gray-900">Rekap Grid Pack</h4>
                                                <div class="mt-1 flex flex-wrap gap-x-4 gap-y-0.5 text-xs text-gray-500">
                                                    <span>Batch: <strong class="font-mono text-gray-700">{{ strtoupper($item['batch']) }}</strong></span>
                                                    <span>Seri: <strong class="font-mono text-gray-700">{{ strtoupper($item['seri']) }}</strong></span>
                                                    <span>Pecahan: <strong class="text-gray-700">{{ $pecahanLabel }}</strong></span>
                                                </div>
                                            </div>
                                            <button type="button" onclick="closeModal('{{ $modalId }}')" class="ml-4 text-gray-400 hover:text-gray-600 transition-colors">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </div>

                                        {{-- Modal Legend --}}
                                        <div class="px-6 py-2 flex gap-4 text-xs border-b border-gray-100 bg-gray-50/60 flex-shrink-0">
                                            <span class="flex items-center gap-1.5"><span class="w-4 h-4 rounded bg-blue-200 border border-blue-400 inline-block"></span>Cutpack</span>
                                            <span class="flex items-center gap-1.5"><span class="w-4 h-4 rounded bg-green-200 border border-green-400 inline-block"></span>Rikyet</span>
                                            <span class="flex items-center gap-1.5"><span class="w-4 h-4 rounded bg-gray-100 border border-gray-300 inline-block"></span>Belum diinput</span>
                                        </div>

                                        {{-- 10×10 Grid — VERTICAL flow: col1=1-10, col2=11-20, … col10=91-100 --}}
                                        <div class="p-5 overflow-y-auto flex-1">
                                            <div class="grid gap-1.5" style="grid-template-rows: repeat(10, minmax(0, 1fr)); grid-template-columns: repeat(10, minmax(0, 1fr)); grid-auto-flow: column;">
                                                @for($p = 1; $p <= 100; $p++)
                                                    @php
                                                        $cell  = $packMap[$p] ?? null;
                                                        $months = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                                                        if ($cell) {
                                                            $carbon    = \Carbon\Carbon::parse($cell['date']);
                                                            $dateLabel = $carbon->day . ' ' . $months[$carbon->month] . ' ' . $carbon->year . ', ' . $carbon->format('H:i') . ' WIB';
                                                            $cellClass = $cell['supplier'] === 'Cutpack'
                                                                ? 'bg-blue-200 border-blue-400 text-blue-900 hover:bg-blue-300'
                                                                : 'bg-green-200 border-green-400 text-green-900 hover:bg-green-300';
                                                        } else {
                                                            $dateLabel = '';
                                                            $cellClass = 'bg-gray-100 border-gray-300 text-gray-400';
                                                        }
                                                    @endphp
                                                    <div class="relative group w-full aspect-square flex items-center justify-center rounded-md border-2 font-bold text-xs cursor-default select-none transition-all {{ $cellClass }}">
                                                        {{ $p }}
                                                        @if($cell)
                                                            <div class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-2 z-[70] hidden group-hover:flex flex-col items-center">
                                                                <div class="bg-gray-900 text-white text-xs rounded-lg px-3 py-2 whitespace-nowrap shadow-xl text-center leading-snug">
                                                                    <div class="font-semibold">Pack {{ $p }}</div>
                                                                    <div class="{{ $cell['supplier'] === 'Cutpack' ? 'text-blue-300' : 'text-green-300' }}">{{ $cell['supplier'] }}</div>
                                                                    <div class="text-gray-400 text-[10px] mt-0.5">{{ $dateLabel }}</div>
                                                                </div>
                                                                <div class="w-2 h-2 bg-gray-900 rotate-45 -mt-1"></div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endfor
                                            </div>

                                            {{-- Summary --}}
                                            @php
                                                $pFilled  = count($packMap);
                                                $pCutpack = collect($packMap)->where('supplier', 'Cutpack')->count();
                                                $pRikyet  = collect($packMap)->where('supplier', 'Rikyet')->count();
                                                $pEmpty   = 100 - $pFilled;
                                            @endphp
                                            <div class="mt-4 pt-3 border-t border-gray-100 flex flex-wrap gap-x-5 gap-y-1 text-xs text-gray-500">
                                                <span>Terisi: <strong class="text-gray-700">{{ $pFilled }}/100</strong></span>
                                                @if($pCutpack > 0)<span class="text-blue-600">Cutpack: <strong>{{ $pCutpack }}</strong></span>@endif
                                                @if($pRikyet > 0)<span class="text-green-600">Rikyet: <strong>{{ $pRikyet }}</strong></span>@endif
                                                <span>Kosong: <strong>{{ $pEmpty }}</strong></span>
                                            </div>
                                        </div>

                                        <div class="px-6 py-3 border-t border-gray-100 flex justify-end flex-shrink-0">
                                            <button type="button" onclick="closeModal('{{ $modalId }}')"
                                                class="px-4 py-2 text-sm font-medium rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors">
                                                Tutup
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

        function setPecahan(value, btn) {
            const input = document.getElementById('pecahan_input');
            const form = input.closest('form');
            
            // If already active, clear it. Otherwise, set it.
            if (input.value === value) {
                input.value = '';
            } else {
                input.value = value;
            }
            
            form.submit();
        }
    </script>
    @endpush
</x-app-layout>
