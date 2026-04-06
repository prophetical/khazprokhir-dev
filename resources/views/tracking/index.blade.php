<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pelacakan Pack') }}
        </h2>
    </x-slot>

    <div class="py-12 relative min-h-screen">
        {{-- Background Effects --}}
        <div
            class="absolute inset-x-0 top-0 h-96 bg-gradient-to-b from-indigo-50/50 dark:from-indigo-900/20 to-transparent -z-10 pointer-events-none">
        </div>

        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Judul Header --}}
            <div class="text-center space-y-2 mb-10">
                <span
                    class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-100/50 dark:border-indigo-800/50 text-indigo-600 dark:text-indigo-400 text-[10px] font-black uppercase tracking-[0.2em] shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    End-to-End Tracking
                </span>
                <h2 class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white tracking-tighter">Penelusuran
                    Pack</h2>
                <p class="text-gray-500 dark:text-gray-400 font-medium max-w-xl mx-auto text-sm">Lacak riwayat lengkap
                    pack dari penerimaan hingga penyerahan akhir.</p>
            </div>

            {{-- Form Pencarian --}}
            <div x-data="{ 
                selectedPack: '{{ request('pack_number') }}', 
                seriValue: '{{ request('seri') }}',
                formatSeri() {
                    let raw = this.seriValue.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
                    let l1 = raw.substring(0, 2).replace(/[^A-Z]/g, '');
                    let l2 = raw.substring(2, 4).replace(/[^A-Z]/g, '');
                    let num = raw.substring(4).replace(/[^0-9]/g, '');
                    let res = l1;
                    if (raw.length > 2) {
                        res += '-' + l2;
                    }
                    if (raw.length > 4) {
                        res += num;
                    }
                    this.seriValue = res;
                }
            }"
                class="bg-white dark:bg-gray-800 p-6 md:p-8 rounded-[2rem] shadow-xl shadow-gray-200/30 dark:shadow-none border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
                <div
                    class="absolute top-0 right-0 w-64 h-64 bg-indigo-500/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3 group-hover:bg-indigo-500/10 transition-colors duration-500">
                </div>

                <form action="{{ route('tracking.index') }}" method="GET" class="relative z-10">
                    <input type="hidden" name="pack_number" :value="selectedPack" required>

                    <div class="space-y-6">
                        {{-- Grid Nomor Pack --}}
                        <div class="space-y-2">
                            <label
                                class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest pl-1 text-center block">Pilih
                                Nomor Pack (1-100) *</label>
                            <div class="max-w-xs sm:max-w-sm mx-auto">
                                <div
                                    class="grid grid-rows-10 grid-flow-col auto-cols-fr gap-0.5 sm:gap-1 p-2 sm:p-3 bg-gray-50/80 dark:bg-gray-900/50 rounded-2xl border border-gray-200/60 dark:border-gray-700/60 shadow-inner">
                                    <template x-for="i in 100" :key="i">
                                        <button type="button" @click="selectedPack = i"
                                            :class="selectedPack == i ? 'bg-indigo-600 dark:bg-indigo-500 text-white shadow-md ring-2 ring-indigo-200 dark:ring-indigo-900 scale-105' : 'bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-slate-100 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700 shadow-sm'"
                                            class="aspect-square flex items-center justify-center rounded-lg text-[8px] sm:text-[10px] font-black transition-all duration-300 w-full"
                                            x-text="i">
                                        </button>
                                    </template>
                                </div>
                            </div>
                            <p x-show="!selectedPack"
                                class="text-[10px] text-rose-500 dark:text-rose-400 font-bold ml-1 animate-pulse text-center">
                                Silakan ketuk salah satu nomor pack.</p>
                        </div>

                        {{-- Parameter Tambahan --}}
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div class="space-y-1">
                                <label
                                    class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest pl-1">Seri
                                    *</label>
                                <input type="text" name="seri" x-model="seriValue" @input="formatSeri()"
                                    pattern="^[A-Z]{2}-[A-Z]{2}\d+$" title="Format yang benar: AA-BA5" required
                                    class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2.5 text-sm font-bold text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:focus:ring-indigo-500 dark:focus:border-indigo-500 transition-all placeholder:normal-case placeholder:text-gray-400 dark:placeholder:text-gray-500 placeholder:font-medium"
                                    placeholder="Contoh : JA-KA0">
                            </div>

                            <div class="space-y-1">
                                <label
                                    class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest pl-1">Pecahan
                                    *</label>
                                <select name="pecahan" required
                                    class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2.5 text-sm font-black text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:focus:ring-indigo-500 transition-all cursor-pointer appearance-none">
                                    <option value="" class="font-normal text-gray-400 dark:text-gray-500">Pilih Pecahan
                                    </option>
                                    @foreach($pecahanList as $pecahan)
                                        <option value="{{ $pecahan }}" {{ request('pecahan') == $pecahan ? 'selected' : '' }}>
                                            {{ $pecahan }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-1">
                                <label
                                    class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest pl-1">Tahun
                                    Anggaran *</label>
                                <select name="tahun_anggaran" required
                                    class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2.5 text-sm font-bold text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:focus:ring-indigo-500 transition-all cursor-pointer">
                                    <option value="" class="font-normal text-gray-400 dark:text-gray-500">Pilih TA
                                    </option>
                                    @foreach($tahunAnggaranList as $ta)
                                        <option value="{{ $ta }}" {{ request('tahun_anggaran') == $ta ? 'selected' : '' }}>
                                            {{ $ta }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-1">
                                <label
                                    class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest pl-1">Tahun
                                    Emisi *</label>
                                <select name="tahun_emisi" required
                                    class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2.5 text-sm font-bold text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:focus:ring-indigo-500 transition-all cursor-pointer">
                                    <option value="" class="font-normal text-gray-400 dark:text-gray-500">Pilih TE
                                    </option>
                                    @foreach($tahunEmisiList as $te)
                                        <option value="{{ $te }}" {{ request('tahun_emisi') == $te ? 'selected' : '' }}>
                                            {{ $te }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-center pt-4 border-t border-gray-100 dark:border-gray-700">
                        <button type="submit" :disabled="!selectedPack || !seriValue"
                            :class="!selectedPack || !seriValue ? 'opacity-50 cursor-not-allowed grayscale' : 'hover:bg-indigo-700 dark:hover:bg-indigo-500 shadow-lg shadow-indigo-200 dark:shadow-none hover:shadow-xl hover:-translate-y-0.5'"
                            class="inline-flex items-center gap-2 px-8 py-3 bg-indigo-600 dark:bg-indigo-500/90 text-white text-sm font-black uppercase tracking-wider rounded-xl transition-all duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Lacak Pack
                        </button>
                    </div>
                </form>
            </div>

            {{-- Timeline Area --}}
            @if($hasSearched)
                @if(!$pack)
                    <!-- Kondisi Kosong -->
                    <div
                        class="bg-white dark:bg-gray-800 p-12 rounded-[2rem] shadow-xl shadow-gray-200/30 dark:shadow-none border border-gray-100 dark:border-gray-700 text-center animate-fade-in">
                        <div
                            class="w-20 h-20 bg-rose-50 dark:bg-rose-900/30 text-rose-500 dark:text-rose-400 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-rose-100 dark:border-rose-900/50">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-black text-gray-900 dark:text-white tracking-tight">Data Tidak Ditemukan</h3>
                        <p class="text-gray-500 dark:text-gray-400 font-medium text-sm mt-2 max-w-sm mx-auto">Pack dengan nomor
                            <span class="font-bold text-gray-900 dark:text-gray-200">#{{ request('pack_number') }}</span> seri
                            <span class="font-bold text-gray-900 dark:text-gray-200">{{ request('seri') }}</span> tidak
                            terdaftar di sistem untuk TA/TE tersebut.</p>
                    </div>
                @else
                    <!-- Stepper Timeline -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-[2rem] shadow-xl shadow-gray-200/30 dark:shadow-none border border-gray-100 dark:border-gray-700 overflow-hidden relative">
                        {{-- Data Header --}}
                        <div
                            class="bg-gradient-to-r from-gray-50 to-white dark:from-gray-900/50 dark:to-gray-800 px-8 py-6 border-b border-gray-100 dark:border-gray-700 flex flex-col md:flex-row justify-between items-center gap-4">
                            <div>
                                <div class="flex items-center gap-3 mb-1">
                                    <span
                                        class="px-2 py-0.5 bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-400 text-[9px] font-black uppercase tracking-wider rounded-md">ID:
                                        {{ $pack->id }}</span>
                                    <h3 class="text-xl font-black text-gray-900 dark:text-white tracking-tight leading-none">
                                        Pack #{{ $pack->pack_number }}</h3>
                                </div>
                                <p class="text-sm font-bold text-gray-500 dark:text-gray-400">Seri: <span
                                        class="text-gray-900 dark:text-gray-200 uppercase">{{ $pack->seri }}</span> &bull;
                                    Batch: <span class="text-gray-900 dark:text-gray-200 uppercase">{{ $pack->batch }}</span>
                                </p>
                            </div>
                            <div class="flex gap-4">
                                <div
                                    class="text-center px-4 py-2 bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-700/50 shadow-sm dark:shadow-none">
                                    <p
                                        class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-0.5">
                                        TA</p>
                                    <p class="text-sm font-black text-indigo-600 dark:text-indigo-400 leading-none">
                                        {{ $pack->hcsReceiving->tahun_anggaran }}</p>
                                </div>
                                <div
                                    class="text-center px-4 py-2 bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-700/50 shadow-sm dark:shadow-none">
                                    <p
                                        class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-0.5">
                                        TE</p>
                                    <p class="text-sm font-black text-pink-600 dark:text-pink-400 leading-none">
                                        {{ $pack->hcsReceiving->emisi }}</p>
                                </div>
                                <div
                                    class="text-center px-4 py-2 bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-700/50 shadow-sm dark:shadow-none">
                                    <p
                                        class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-0.5">
                                        Pecahan</p>
                                    <p class="text-sm font-black text-gray-800 dark:text-gray-300 leading-none">
                                        {{ $pack->hcsReceiving->pecahan }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Timeline Content --}}
                        <div class="p-8 md:px-12 md:py-10">
                            <div class="relative max-w-2xl mx-auto">
                                {{-- Garis Tengah --}}
                                <div
                                    class="absolute left-8 md:left-1/2 top-4 bottom-4 w-px bg-gray-200 dark:bg-gray-700 -translate-x-1/2 hidden md:block">
                                </div>
                                <div class="absolute left-8 top-4 bottom-4 w-px bg-gray-200 dark:bg-gray-700 md:hidden"></div>

                                {{-- Tahap 1: Penerimaan HCS --}}
                                <div
                                    class="relative flex items-center md:justify-end md:w-1/2 md:-ml-px md:pr-12 mb-12 w-full pl-16 md:pl-0">
                                    <div
                                        class="absolute left-8 md:left-auto md:right-0 w-8 h-8 rounded-full bg-emerald-500 dark:bg-emerald-600 border-4 border-white dark:border-gray-800 shadow flex items-center justify-center -translate-x-1/2 md:translate-x-1/2">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <div
                                        class="bg-white dark:bg-gray-800/80 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-lg shadow-gray-200/40 dark:shadow-none hover:-translate-y-1 transition-transform w-full relative">
                                        <div
                                            class="absolute top-1/2 -right-2 w-4 h-4 bg-white dark:bg-gray-800/80 border-t border-r border-gray-100 dark:border-gray-700 rotate-45 -translate-y-1/2 hidden md:block">
                                        </div>
                                        <div
                                            class="absolute top-1/2 -left-2 w-4 h-4 bg-white dark:bg-gray-800/80 border-b border-l border-gray-100 dark:border-gray-700 rotate-45 -translate-y-1/2 md:hidden">
                                        </div>

                                        <span
                                            class="text-[10px] font-black text-emerald-500 dark:text-emerald-400 uppercase tracking-widest mb-1 block">{{ \Carbon\Carbon::parse($pack->hcsReceiving->tanggal_penerimaan)->translatedFormat('d F Y') }}</span>
                                        <h4 class="text-lg font-black text-gray-900 dark:text-white leading-none mb-2">1.
                                            Penerimaan</h4>
                                        <div class="space-y-1 text-sm font-medium text-gray-500 dark:text-gray-400">
                                            <p><span class="text-gray-400 dark:text-gray-500">No Bon:</span> <span
                                                    class="text-gray-800 dark:text-gray-200 font-bold">{{ $pack->hcsReceiving->nomor_bon }}</span>
                                            </p>
                                            <p><span class="text-gray-400 dark:text-gray-500">Supplier:</span>
                                                {{ $pack->hcsReceiving->supplier }}</p>
                                            <p><span class="text-gray-400 dark:text-gray-500">Mesin:</span>
                                                {{ $pack->hcsReceiving->mesin }}</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Tahap 2: Penyortiran HCS --}}
                                @php $isSorted = !is_null($pack->hcs_sorting_id); @endphp
                                <div
                                    class="relative flex items-center md:justify-start md:w-1/2 md:ml-auto md:pl-12 mb-12 w-full pl-16 md:pl-0">
                                    <div
                                        class="absolute left-8 md:-left-px w-8 h-8 rounded-full {{ $isSorted ? 'bg-emerald-500 dark:bg-emerald-600' : 'bg-gray-200 dark:bg-gray-700' }} border-4 border-white dark:border-gray-800 shadow flex items-center justify-center -translate-x-1/2">
                                        @if($isSorted)
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        @else
                                            <div class="w-2.5 h-2.5 bg-gray-400 dark:bg-gray-500 rounded-full"></div>
                                        @endif
                                    </div>
                                    <div
                                        class="bg-white dark:bg-gray-800/80 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-lg shadow-gray-200/40 dark:shadow-none {{ $isSorted ? 'hover:-translate-y-1 transition-transform' : 'opacity-60 dark:opacity-40 hover:opacity-100 dark:hover:opacity-75 transition-opacity' }} w-full relative">
                                        <div
                                            class="absolute top-1/2 -left-2 w-4 h-4 bg-white dark:bg-gray-800/80 border-b border-l border-gray-100 dark:border-gray-700 rotate-45 -translate-y-1/2">
                                        </div>

                                        @if($isSorted)
                                            <span
                                                class="text-[10px] font-black text-emerald-500 dark:text-emerald-400 uppercase tracking-widest mb-1 block">{{ \Carbon\Carbon::parse($pack->hcsSorting->tanggal_sortir)->translatedFormat('d F Y') }}</span>
                                            <h4 class="text-lg font-black text-gray-900 dark:text-white leading-none mb-2">2.
                                                Penyortiran</h4>
                                            <div class="space-y-1 text-sm font-medium text-gray-500 dark:text-gray-400">
                                                <p><span class="text-gray-400 dark:text-gray-500">Petugas 1:</span>
                                                    {{ $pack->hcsSorting->petugas_1 }}</p>
                                                @if($pack->hcsSorting->petugas_2)
                                                    <p><span class="text-gray-400 dark:text-gray-500">Petugas 2:</span>
                                                        {{ $pack->hcsSorting->petugas_2 }}</p>
                                                @endif
                                                <p><span class="text-gray-400 dark:text-gray-500">Gilir:</span>
                                                    {{ $pack->hcsSorting->gilir }}</p>
                                            </div>
                                        @else
                                            <span
                                                class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1 block">Menunggu</span>
                                            <h4 class="text-lg font-black text-gray-400 dark:text-gray-500 leading-none mb-1">2.
                                                Penyortiran</h4>
                                            <p class="text-xs text-gray-400 dark:text-gray-500">Pack belum disortir.</p>
                                        @endif
                                    </div>
                                </div>

                                {{-- Tahap 3: Pengemasan --}}
                                @php $isPacked = !is_null($pack->id_pengemasan); @endphp
                                <div
                                    class="relative flex items-center md:justify-end md:w-1/2 md:-ml-px md:pr-12 mb-12 w-full pl-16 md:pl-0">
                                    <div
                                        class="absolute left-8 md:left-auto md:right-0 w-8 h-8 rounded-full {{ $isPacked ? 'bg-emerald-500 dark:bg-emerald-600' : 'bg-gray-200 dark:bg-gray-700' }} border-4 border-white dark:border-gray-800 shadow flex items-center justify-center -translate-x-1/2 md:translate-x-1/2">
                                        @if($isPacked)
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        @else
                                            <div class="w-2.5 h-2.5 bg-gray-400 dark:bg-gray-500 rounded-full"></div>
                                        @endif
                                    </div>
                                    <div
                                        class="bg-white dark:bg-gray-800/80 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-lg shadow-gray-200/40 dark:shadow-none {{ $isPacked ? 'hover:-translate-y-1 transition-transform' : 'opacity-60 dark:opacity-40 hover:opacity-100 dark:hover:opacity-75 transition-opacity' }} w-full relative">
                                        <div
                                            class="absolute top-1/2 -right-2 w-4 h-4 bg-white dark:bg-gray-800/80 border-t border-r border-gray-100 dark:border-gray-700 rotate-45 -translate-y-1/2 hidden md:block">
                                        </div>
                                        <div
                                            class="absolute top-1/2 -left-2 w-4 h-4 bg-white dark:bg-gray-800/80 border-b border-l border-gray-100 dark:border-gray-700 rotate-45 -translate-y-1/2 md:hidden">
                                        </div>

                                        @if($isPacked)
                                            <span
                                                class="text-[10px] font-black text-emerald-500 dark:text-emerald-400 uppercase tracking-widest mb-1 block">{{ \Carbon\Carbon::parse($pack->pengemasan->tanggal_pengemasan)->translatedFormat('d F Y') }}</span>
                                            <h4 class="text-lg font-black text-gray-900 dark:text-white leading-none mb-2">3.
                                                Pengemasan</h4>
                                            <div class="space-y-1 text-sm font-medium text-gray-500 dark:text-gray-400">
                                                <p><span class="text-gray-400 dark:text-gray-500">Rentang Dus:</span> <span
                                                        class="text-gray-800 dark:text-gray-200 font-bold">{{ $pack->pengemasan->dus_awal }}
                                                        - {{ $pack->pengemasan->dus_akhir }}</span></p>
                                                <p><span class="text-gray-400 dark:text-gray-500">Gilir:</span>
                                                    {{ $pack->pengemasan->gilir }}</p>
                                                <p><span class="text-gray-400 dark:text-gray-500">Operator:</span>
                                                    {{ $pack->pengemasan->user->name ?? 'User' }}</p>
                                            </div>
                                        @else
                                            <span
                                                class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1 block">Menunggu</span>
                                            <h4 class="text-lg font-black text-gray-400 dark:text-gray-500 leading-none mb-1">3.
                                                Pengemasan</h4>
                                            <p class="text-xs text-gray-400 dark:text-gray-500">Pack belum dikemas.</p>
                                        @endif
                                    </div>
                                </div>

                                {{-- Tahap 4: Penyerahan --}}
                                @php $isSubmitted = !is_null($submissionStatus); @endphp
                                <div
                                    class="relative flex items-center md:justify-start md:w-1/2 md:ml-auto md:pl-12 w-full pl-16 md:pl-0">
                                    <div
                                        class="absolute left-8 md:-left-px w-8 h-8 rounded-full {{ $isSubmitted ? 'bg-indigo-600 dark:bg-indigo-500' : 'bg-gray-200 dark:bg-gray-700' }} border-4 border-white dark:border-gray-800 shadow flex items-center justify-center -translate-x-1/2">
                                        @if($isSubmitted)
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        @else
                                            <div class="w-2.5 h-2.5 bg-gray-400 dark:bg-gray-500 rounded-full"></div>
                                        @endif
                                    </div>
                                    <div
                                        class="bg-white dark:bg-gray-800/80 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-lg shadow-gray-200/40 dark:shadow-none {{ $isSubmitted ? 'hover:-translate-y-1 transition-transform border-indigo-100 dark:border-indigo-500/30 ring-2 ring-indigo-50/50 dark:ring-indigo-900/30' : 'opacity-60 dark:opacity-40 hover:opacity-100 dark:hover:opacity-75 transition-opacity' }} w-full relative">
                                        <div
                                            class="absolute top-1/2 -left-2 w-4 h-4 bg-white dark:bg-gray-800/80 border-b border-l border-gray-100 dark:border-gray-700 rotate-45 -translate-y-1/2">
                                        </div>

                                        @if($isSubmitted)
                                            <span
                                                class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest mb-1 block">{{ \Carbon\Carbon::parse($submissionData['tanggal'])->translatedFormat('d F Y') }}</span>

                                            @if($submissionStatus === 'bi')
                                                <h4 class="text-lg font-black text-gray-900 dark:text-white leading-none mb-2">4.
                                                    Penyerahan</h4>
                                                <div class="space-y-1 text-sm font-medium text-gray-500 dark:text-gray-400">
                                                    <p><span class="text-gray-400 dark:text-gray-500">Nomor BA:</span> <span
                                                            class="text-indigo-600 dark:text-indigo-300 font-bold bg-indigo-50 dark:bg-indigo-900/40 px-1.5 py-0.5 rounded">{{ $submissionData['nomor_ba'] }}</span>
                                                    </p>
                                                    <p><span class="text-gray-400 dark:text-gray-500">Status Data:</span>
                                                        <span
                                                            class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider {{ $submissionData['status_data'] == 'Lengkap' ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400' : 'bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400' }}">
                                                            {{ $submissionData['status_data'] }}
                                                        </span>
                                                    </p>
                                                </div>
                                            @else
                                                <h4 class="text-lg font-black text-gray-900 dark:text-white leading-none mb-2">4.
                                                    Diserahkan (HCTS)</h4>
                                                <div class="space-y-1 text-sm font-medium text-gray-500 dark:text-gray-400">
                                                    <p><span class="text-gray-400 dark:text-gray-500">Nomor BA:</span> <span
                                                            class="text-indigo-600 dark:text-indigo-300 font-bold bg-indigo-50 dark:bg-indigo-900/40 px-1.5 py-0.5 rounded">{{ $submissionData['nomor_ba'] }}</span>
                                                    </p>
                                                    <p><span class="text-gray-400 dark:text-gray-500">Pemasok:</span>
                                                        {{ $submissionData['pemasok'] }}</p>
                                                </div>
                                            @endif
                                        @else
                                            <span
                                                class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1 block">Menunggu</span>
                                            <h4 class="text-lg font-black text-gray-400 dark:text-gray-500 leading-none mb-1">4.
                                                Penyerahan Final</h4>
                                            <p class="text-xs text-gray-400 dark:text-gray-500">Pack masih belum diserahkan</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>