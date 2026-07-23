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
    $tglFormatted = \Carbon\Carbon::parse($tanggalLaporan)->locale('id')->isoFormat('dddd, D MMMM Y');
    $dataUrl = route('laporan-harian.persediaan-detail-data', [
        'pecahan' => $pecahan,
        'tanggal_laporan' => $tanggalLaporan,
        'tahun_anggaran' => $tahunAnggaran,
        'tahun_emisi' => $tahunEmisi,
        'jenis' => $jenis,
    ]);
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-start gap-4 sm:gap-6">
            <h2 class="font-semibold text-xl text-white leading-tight">
                Rincian {{ $jenisLabel }}
            </h2>
            @if ($isRealtime)
                <div
                    class="w-fit px-4 py-1.5 bg-emerald-500/20 backdrop-blur-sm border border-emerald-400/30 rounded-full flex items-center gap-2 shadow-inner">
                    <span class="flex h-2 w-2 relative">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span class="text-[8px] font-black text-emerald-100 uppercase tracking-[0.2em]">LIVE</span>
                </div>
            @else
                <div
                    class="w-fit px-4 py-1.5 bg-white/10 border border-white/20 rounded-full flex items-center gap-2">
                    <svg class="h-3 w-3 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="text-[8px] font-black text-white/80 uppercase tracking-[0.2em]">FINAL</span>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-12" x-data="{
        paused: false,
        loading: false,
        lastUpdate: '{{ now()->format('H:i:s') }}',
        contentHtml: '',
        async fetchData() {
            this.loading = true;
            const params = new URLSearchParams({
                pecahan: '{{ $pecahan }}',
                tanggal_laporan: '{{ $tanggalLaporan }}',
                tahun_anggaran: '{{ $tahunAnggaran }}',
                tahun_emisi: '{{ $tahunEmisi }}',
                jenis: '{{ $jenis }}'
            });
            try {
                const res = await fetch(`{{ $dataUrl }}&${params.toString()}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (res.ok) {
                    this.contentHtml = await res.text();
                    this.lastUpdate = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                }
            } catch (e) { console.error('Refresh error:', e); }
            this.loading = false;
        },
        init() {
            this.contentHtml = this.$refs.initialContent.innerHTML;
            @if ($isRealtime)
            setInterval(() => { if (!this.paused) this.fetchData(); }, 45000);
            @endif
        }
    }" x-init="init()">
        <div class="w-full mx-auto sm:px-6 lg:px-8 overflow-hidden">
            <!-- Header / Context Card -->
            <div
                class="bg-white dark:bg-slate-900 overflow-hidden shadow-2xl sm:rounded-2xl mb-6 border border-gray-100 dark:border-slate-800 p-1">
                <div
                    class="bg-gray-50/50 dark:bg-slate-800/50 rounded-[1.25rem] p-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                    <div class="flex items-center gap-4">
                        <span
                            class="inline-flex items-center justify-center h-11 w-11 rounded-xl shadow-md font-black text-lg border {{ $colorMap[$pecahan] ?? 'bg-gray-900 text-white' }}">
                            {{ $pecahan }}
                        </span>
                        <div>
                            <h3 class="text-lg font-black text-gray-800 dark:text-gray-100 leading-tight">
                                Pecahan {{ $pecahan }} — {{ $jenisLabel }}
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                {{ $tglFormatted }} · TA {{ $tahunAnggaran }}{{ $tahunEmisi ? ' / TE ' . $tahunEmisi : '' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        @if ($isRealtime)
                            <div class="hidden sm:flex flex-col items-end">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Update</span>
                                <span class="text-sm font-black text-gray-700 dark:text-gray-200 tabular-nums" x-text="lastUpdate">{{ now()->format('H:i:s') }}</span>
                            </div>
                            <button @click="paused = !paused" :class="paused ? 'bg-amber-500' : 'bg-emerald-600'"
                                class="text-white px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider shadow-md transition-all hover:-translate-y-0.5 flex items-center gap-2">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <template x-if="!paused"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6" /></template>
                                    <template x-if="paused"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /></template>
                                </svg>
                                <span x-text="paused ? 'Jeda' : 'Live'">Live</span>
                            </button>
                        @endif
                        <button @click="fetchData()" :disabled="loading"
                            class="bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider shadow-md transition-all hover:-translate-y-0.5 flex items-center gap-2">
                            <svg class="h-4 w-4" :class="{ 'animate-spin': loading }" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Refresh
                        </button>
                    </div>
                </div>
            </div>

            <!-- Table Region (auto-refreshed) -->
            <div id="persediaan-detail-content" x-ref="initialContent" x-html="contentHtml"
                class="space-y-4 min-h-[200px] transition-opacity" :class="{ 'opacity-60': loading }">
                @include('laporan-harian.partials.persediaan-detail-table')
            </div>
        </div>
    </div>
</x-app-layout>
