<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-start gap-4 sm:gap-6">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Laporan Harian Realtime') }}
            </h2>
            <div class="w-fit px-4 py-1.5 bg-white/10 backdrop-blur-sm border border-white/20 rounded-full flex items-center gap-2 shadow-inner">
                <span class="flex h-2 w-2 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span class="text-[8px] font-black text-white uppercase tracking-[0.2em]">LIVE UPDATING</span>
            </div>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ 
        loading: false,
        lastUpdate: '{{ now()->format('H:i:s') }}',
        tahunAnggaran: '{{ $tahunAnggaran }}',
        tahunEmisi: '{{ $tahunEmisi }}',
        search: '', // Keep for compatibility with partial but hide UI
        tablesHtml: '',
        showHcs: true, 
        showMonitoring: true, 
        showHcts: true, 
        showAnnual: true, 
        showMonthly: true,
        fetchData() {
            this.loading = true;
            const params = new URLSearchParams({
                tahun_anggaran: this.tahunAnggaran,
                tahun_emisi: this.tahunEmisi
            });
            fetch(`{{ route('laporan-harian.realtime-partial') }}?${params.toString()}`)
                .then(res => res.text())
                .then(html => {
                    this.tablesHtml = html;
                    this.lastUpdate = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                    this.loading = false;
                })
                .catch(err => {
                    console.error('Fetch error:', err);
                    this.loading = false;
                });
        }
    }" 
    x-init="tablesHtml = $refs.initialContent.innerHTML; setInterval(() => fetchData(), 10000)">
        <div class="w-full mx-auto sm:px-6 lg:px-8 overflow-hidden">
            <!-- Status & Action Row -->
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl mb-8 border border-gray-100 p-1">
                <div class="bg-gray-50/50 rounded-[1.25rem] p-6 flex flex-col lg:flex-row justify-between items-center gap-6">
                    <div class="flex items-center gap-6 w-full lg:w-auto">
                        <div class="flex items-center gap-4 bg-white p-3 rounded-2xl shadow-sm border border-gray-50">
                            <div class="p-3 bg-indigo-600 rounded-xl shadow-lg shadow-indigo-200">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Terakhir Update</h3>
                                <p class="text-xl font-black text-gray-800 tabular-nums" x-text="lastUpdate">{{ now()->format('H:i:s') }}</p>
                            </div>
                        </div>

                        <div class="hidden sm:block h-12 w-px bg-gray-200"></div>

                        <div class="hidden sm:block">
                            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Status Koneksi</h3>
                            <div class="flex items-center gap-2">
                                <template x-if="!loading">
                                    <span class="flex items-center gap-2">
                                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                        <span class="text-sm font-bold text-gray-600">Terhubung</span>
                                    </span>
                                </template>
                                <template x-if="loading">
                                    <span class="flex items-center gap-2">
                                        <span class="h-2 w-2 rounded-full bg-amber-500 animate-pulse"></span>
                                        <span class="text-sm font-bold text-gray-600">Sinkronisasi...</span>
                                    </span>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center gap-6 w-full lg:w-auto">
                        <!-- Filters -->
                        <div class="flex gap-4">
                            <div class="relative min-w-[140px]">
                                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Tahun Anggaran</h3>
                                <select x-model="tahunAnggaran" @change="fetchData()"
                                    class="w-full rounded-xl border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 py-3 text-center font-black text-gray-700 bg-white">
                                    @foreach($tahunAnggaranOptions as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="relative min-w-[140px]">
                                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Tahun Emisi</h3>
                                <select x-model="tahunEmisi" @change="fetchData()"
                                    class="w-full rounded-xl border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 py-3 text-center font-black text-gray-700 bg-white">
                                    @foreach($tahunEmisiOptions as $emisi)
                                        <option value="{{ $emisi }}">{{ $emisi }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="h-8 w-px bg-gray-200 hidden sm:block"></div>

                        <div class="text-right">
                            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Hari Ini</h3>
                            <p class="text-sm font-black text-indigo-600 bg-indigo-50 px-3 py-1.5 rounded-lg border border-indigo-100">
                                {{ \Carbon\Carbon::parse($tanggalLaporan)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Report Tables Container -->
            <div id="report-container" x-ref="initialContent" x-html="tablesHtml" class="space-y-8 min-h-[400px]">
                @include('laporan-harian.partials.report-tables')
            </div>

            <!-- Floating Loading Indicator -->
            <div x-show="loading" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-4"
                 class="fixed bottom-8 left-1/2 transform -translate-x-1/2 z-50">
                <div class="bg-gray-900/90 backdrop-blur-md text-white px-6 py-3 rounded-full shadow-2xl flex items-center gap-3 border border-white/10">
                    <svg class="animate-spin h-4 w-4 text-indigo-400" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs font-black tracking-widest uppercase">Memperbarui Data...</span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
