<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('x-pengganti.mapping.index') }}"
                class="p-1.5 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition-all">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="font-black text-xl text-gray-800 dark:text-white leading-tight tracking-tight">Input Inschiet
                Seri</h2>
        </div>
    </x-slot>
    <style>
        input::placeholder {
            font-size: 0.7rem !important;
            text-transform: none !important;
            font-weight: 500 !important;
            letter-spacing: normal !important;
            opacity: 0.6;
        }
    </style>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Seri Info Card --}}
            <div
                class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 dark:border-slate-700">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <span
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg {{ $seri->pecahan_color_class }} text-white font-black text-sm shadow-md">{{ $seri->pecahan }}</span>
                                <h3 class="text-lg font-black text-gray-800 dark:text-white">Seri {{ $seri->seri }}</h3>
                            </div>
                            <div
                                class="flex flex-wrap gap-4 text-[10px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">
                                <span>Batch: {{ $seri->batch }}</span>
                                <span>TA: {{ $seri->tahun_anggaran }}</span>
                                <span>TE: {{ $seri->tahun_emisi }}</span>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <div class="text-center px-4 py-2 bg-violet-50 dark:bg-violet-900/20 rounded-xl">
                                <div class="text-lg font-black text-violet-600 dark:text-violet-400">
                                    {{ number_format($totalMappings) }}
                                </div>
                                <div class="text-[8px] font-black text-violet-400 uppercase tracking-widest">Total
                                    Inschiet</div>
                            </div>
                            <div class="text-center px-4 py-2 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl">
                                <div class="text-lg font-black text-emerald-600 dark:text-emerald-400">
                                    {{ number_format($totalBilyet) }}
                                </div>
                                <div class="text-[8px] font-black text-emerald-400 uppercase tracking-widest">Total
                                    Bilyet</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Flash Messages --}}
            @if(session('x_success'))
                <div
                    class="p-4 bg-emerald-50 dark:bg-emerald-900/20 border-l-4 border-emerald-500 text-emerald-700 dark:text-emerald-400 shadow-sm rounded-r-lg flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-bold text-sm">{{ session('x_success') }}</span>
                </div>
            @endif
            @if($errors->any())
                <div
                    class="p-4 bg-rose-50 dark:bg-rose-900/20 border-l-4 border-rose-500 text-rose-700 dark:text-rose-400 shadow-sm rounded-r-lg">
                    <ul class="list-disc list-inside text-sm font-bold">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- 4-Mode Input Tabs --}}
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 dark:border-slate-700"
                x-data="{ activeTab: 'pack' }">
                {{-- Tab Header --}}
                <div class="flex border-b border-gray-100 dark:border-slate-700">
                    <button @click="activeTab = 'pack'"
                        :class="activeTab === 'pack' ? 'bg-violet-600 text-white' : 'text-gray-500 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-700'"
                        class="flex-1 px-2 md:px-4 py-3 text-[10px] font-black uppercase tracking-widest transition-all duration-200 flex items-center justify-center gap-2 border-r border-gray-100 dark:border-slate-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <span class="hidden md:inline">Inschiet Pack</span><span class="md:hidden">Pack</span>
                    </button>
                    <button @click="activeTab = 'brood'"
                        :class="activeTab === 'brood' ? 'bg-amber-600 text-white' : 'text-gray-500 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-700'"
                        class="flex-1 px-2 md:px-4 py-3 text-[10px] font-black uppercase tracking-widest transition-all duration-200 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        <span class="hidden md:inline">Inschiet Brood</span><span class="md:hidden">Brood</span>
                    </button>
                    <button @click="activeTab = 'partial'"
                        :class="activeTab === 'partial' ? 'bg-cyan-600 text-white' : 'text-gray-500 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-700'"
                        class="flex-1 px-2 md:px-4 py-3 text-[10px] font-black uppercase tracking-widest transition-all duration-200 flex items-center justify-center gap-2 border-r border-gray-100 dark:border-slate-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="hidden md:inline">Inschiet Parsial</span><span class="md:hidden">Parsial</span>
                    </button>
                    <button @click="activeTab = 'vell'"
                        :class="activeTab === 'vell' ? 'bg-blue-600 text-white' : 'text-gray-500 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-700'"
                        class="flex-1 px-2 md:px-4 py-3 text-[10px] font-black uppercase tracking-widest transition-all duration-200 flex items-center justify-center gap-2 border-l border-gray-100 dark:border-slate-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                        </svg>
                        <span class="hidden md:inline">Inschiet Vell</span><span class="md:hidden">Vell</span>
                    </button>
                    <button @click="activeTab = 'single'"
                        :class="activeTab === 'single' ? 'bg-emerald-600 text-white' : 'text-gray-500 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-700'"
                        class="flex-1 px-2 md:px-4 py-3 text-[10px] font-black uppercase tracking-widest transition-all duration-200 flex items-center justify-center gap-2 border-l border-gray-100 dark:border-slate-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        <span class="hidden md:inline">Inschiet Bilyet</span><span class="md:hidden">Bilyet</span>
                    </button>
                </div>

                <div class="p-6">
                    {{-- Tab 1: Inschiet Pack --}}
                    <div x-show="activeTab === 'pack'" x-transition style="display: none;">
                        <form action="{{ route('x-pengganti.mapping.store.pack') }}" method="POST">
                            @csrf
                            <input type="hidden" name="x_pengganti_seri_id" value="{{ $seri->id }}">
                            <div
                                class="mb-6 p-4 bg-violet-50/80 dark:bg-violet-900/10 border border-violet-100 dark:border-violet-800/30 rounded-xl flex gap-4">
                                <div class="shrink-0 text-violet-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h5
                                        class="text-xs font-black text-violet-800 dark:text-violet-300 uppercase tracking-widest mb-1">
                                        Inschiet Pack</h5>
                                    <p
                                        class="text-[11px] font-medium text-violet-600/80 dark:text-violet-400/80 leading-relaxed">
                                        Gunakan mode ini jika ada 1 pack yang di inschiet. Sistem akan otomatis
                                        men-generate 45 baris data (45 prefix seri) dari seri asal ke seri pengganti.
                                    </p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <h4 class="text-xs font-black text-violet-600 uppercase tracking-widest">Seri Asal
                                        (Rusak)</h4>
                                    <div>
                                        <label
                                            class="block text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1">Nomor
                                            Pack</label>
                                        <input type="number" name="pack_number" required min="1" max="1000"
                                            class="w-full py-2.5 px-4 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-bold focus:ring-violet-500 focus:border-violet-500"
                                            placeholder="Contoh: 701" value="{{ old('pack_number') }}">
                                    </div>
                                    <div class="p-3 bg-violet-50 dark:bg-violet-900/10 rounded-xl">
                                        <p class="text-[9px] font-black text-violet-500 uppercase tracking-widest">45
                                            brood akan otomatis di-generate dari Seri {{ $seri->seri }}</p>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <h4 class="text-xs font-black text-emerald-600 uppercase tracking-widest">Seri
                                        Pengganti (X)</h4>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label
                                                class="block text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1">
                                                Seri 1 (2 huruf)</label>
                                            <input type="text" name="rep_seri1_base" required maxlength="2"
                                                pattern="[A-Za-z]{2}" oninput="this.value = this.value.toUpperCase()"
                                                class="w-full py-2.5 px-4 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-bold uppercase focus:ring-emerald-500 focus:border-emerald-500"
                                                placeholder="Contoh: AA" value="{{ old('rep_seri1_base') }}">
                                        </div>
                                        <div>
                                            <label
                                                class="block text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1">
                                                Seri 2 (2 huruf)</label>
                                            <input type="text" name="rep_seri2_base" required maxlength="2"
                                                pattern="[A-Za-z]{2}" oninput="this.value = this.value.toUpperCase()"
                                                class="w-full py-2.5 px-4 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-bold uppercase focus:ring-emerald-500 focus:border-emerald-500"
                                                placeholder="Contoh: RE" value="{{ old('rep_seri2_base') }}">
                                        </div>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1">Nomor
                                            Pack Pengganti</label>
                                        <input type="text" name="rep_pack_number" required maxlength="6"
                                            inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                            class="w-full py-2.5 px-4 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-bold focus:ring-emerald-500 focus:border-emerald-500"
                                            placeholder="Contoh: 001" value="{{ old('rep_pack_number') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6 flex justify-end">
                                <button type="submit"
                                    class="px-6 py-3 bg-gradient-to-r from-violet-600 to-purple-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:from-violet-700 hover:to-purple-700 transition-all shadow-lg active:scale-95">
                                    Simpan Inschiet Pack
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Tab 2: Per Brood --}}
                    <div x-show="activeTab === 'brood'" x-transition style="display: none;">
                        <form action="{{ route('x-pengganti.mapping.store.brood') }}" method="POST">
                            @csrf
                            <input type="hidden" name="x_pengganti_seri_id" value="{{ $seri->id }}">
                            <div
                                class="mb-6 p-4 bg-amber-50/80 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-800/30 rounded-xl flex gap-4">
                                <div class="shrink-0 text-amber-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h5
                                        class="text-xs font-black text-amber-800 dark:text-amber-300 uppercase tracking-widest mb-1">
                                        Inschiet Brood</h5>
                                    <p
                                        class="text-[11px] font-medium text-amber-600/80 dark:text-amber-400/80 leading-relaxed">
                                        Input seri inschiet untuk 1 brood pada satu spesifik prefix seri akhir.</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <h4 class="text-xs font-black text-amber-600 uppercase tracking-widest">Seri Asal
                                        (Rusak)</h4>
                                    <div>
                                        <label
                                            class="block text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1">Nomor
                                            Pack</label>
                                        <input type="number" name="pack_number" required min="1"
                                            class="w-full py-2.5 px-4 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-bold focus:ring-amber-500 focus:border-amber-500"
                                            placeholder="Contoh: 701" value="{{ old('pack_number') }}">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1">Prefix
                                            Huruf Seri (3 huruf)</label>
                                        <input type="text" name="source_prefix" required maxlength="3"
                                            pattern="[A-Za-z]{3}" oninput="this.value = this.value.toUpperCase()"
                                            class="w-full py-2.5 px-4 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-bold uppercase focus:ring-amber-500 focus:border-amber-500"
                                            placeholder="Contoh: ABA" value="{{ old('source_prefix') }}">
                                    </div>
                                    <div
                                        class="p-3 bg-amber-50 dark:bg-amber-900/10 rounded-xl border border-amber-100/50 dark:border-amber-800/20">
                                        <p
                                            class="text-[9px] font-black text-amber-600 dark:text-amber-400 uppercase tracking-widest leading-relaxed">
                                            Akan dihitung otomatis berdasarkan seri dan nomor pack.
                                        </p>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <h4 class="text-xs font-black text-emerald-600 uppercase tracking-widest">Seri
                                        Pengganti (X)</h4>
                                    <div>
                                        <label
                                            class="block text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1">Prefix
                                            Huruf Seri (3 huruf)</label>
                                        <input type="text" name="replacement_prefix" required maxlength="3"
                                            pattern="[A-Za-z]{3}" oninput="this.value = this.value.toUpperCase()"
                                            class="w-full py-2.5 px-4 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-bold uppercase focus:ring-emerald-500 focus:border-emerald-500"
                                            placeholder="Contoh: RBC" value="{{ old('replacement_prefix') }}">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1">Nomor
                                            Pack Pengganti</label>
                                        <input type="number" name="replacement_pack_number" required min="1"
                                            class="w-full py-2.5 px-4 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-bold focus:ring-emerald-500 focus:border-emerald-500"
                                            placeholder="Contoh: 1" value="{{ old('replacement_pack_number') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end">
                                <button type="submit"
                                    class="px-6 py-3 bg-gradient-to-r from-amber-600 to-orange-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:from-amber-700 hover:to-orange-700 transition-all shadow-lg active:scale-95">
                                    Simpan Inschiet Brood
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Tab 3: Inschiet Parsial --}}
                    <div x-show="activeTab === 'partial'" x-transition style="display: none;">
                        <form action="{{ route('x-pengganti.mapping.store.partial') }}" method="POST">
                            @csrf
                            <input type="hidden" name="x_pengganti_seri_id" value="{{ $seri->id }}">
                            <div
                                class="mb-6 p-4 bg-cyan-50/80 dark:bg-cyan-900/10 border border-cyan-100 dark:border-cyan-800/30 rounded-xl flex gap-4">
                                <div class="shrink-0 text-cyan-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h5
                                        class="text-xs font-black text-cyan-800 dark:text-cyan-300 uppercase tracking-widest mb-1">
                                        Inschiet Parsial</h5>
                                    <p
                                        class="text-[11px] font-medium text-cyan-600/80 dark:text-cyan-400/80 leading-relaxed">
                                        Input penggantian nomor seri dalam rentang bilyet tertentu (nomor seri awal s/d
                                        nomor seri akhir).</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                {{-- Sisi Asal --}}
                                <div class="space-y-6">
                                    <h4
                                        class="text-xs font-black text-cyan-600 uppercase tracking-widest flex items-center gap-2">
                                        <span
                                            class="w-5 h-5 rounded-full bg-cyan-100 flex items-center justify-center text-[10px]">1</span>
                                        Seri Diganti
                                    </h4>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="col-span-1">
                                            <label
                                                class="block text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1.5">No
                                                Pack</label>
                                            <input type="number" name="pack_number" required min="1"
                                                class="w-full py-2.5 px-4 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-bold focus:ring-cyan-500 focus:border-cyan-500"
                                                placeholder="701" value="{{ old('pack_number') }}">
                                        </div>
                                        <div class="col-span-1">
                                            <label
                                                class="block text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1.5">Prefix</label>
                                            <input type="text" name="source_prefix" required maxlength="3"
                                                class="w-full py-2.5 px-4 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-bold uppercase focus:ring-cyan-500 focus:border-cyan-500"
                                                placeholder="ABA" value="{{ old('source_prefix') }}">
                                        </div>
                                    </div>

                                    <div
                                        class="p-5 bg-cyan-50/50 dark:bg-cyan-900/10 rounded-2xl border border-cyan-100 dark:border-cyan-800/20 space-y-4">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label
                                                    class="block text-[9px] font-black text-cyan-600 uppercase tracking-widest mb-1.5">Digit
                                                    Awal</label>
                                                <input type="text" name="src_start_offset" required maxlength="3"
                                                    class="w-full py-2.5 px-4 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-bold focus:ring-cyan-500 focus:border-cyan-500"
                                                    placeholder="001" value="{{ old('src_start_offset') }}">
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-[9px] font-black text-cyan-600 uppercase tracking-widest mb-1.5">Digit
                                                    Akhir</label>
                                                <input type="text" name="src_end_offset" required maxlength="3"
                                                    class="w-full py-2.5 px-4 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-bold focus:ring-cyan-500 focus:border-cyan-500"
                                                    placeholder="500" value="{{ old('src_end_offset') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Sisi Pengganti --}}
                                <div class="space-y-6">
                                    <h4
                                        class="text-xs font-black text-emerald-600 uppercase tracking-widest flex items-center gap-2">
                                        <span
                                            class="w-5 h-5 rounded-full bg-emerald-100 flex items-center justify-center text-[10px]">2</span>
                                        Seri Pengganti
                                    </h4>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="col-span-1">
                                            <label
                                                class="block text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1.5">No
                                                Pack</label>
                                            <input type="number" name="replacement_pack_number" required min="1"
                                                class="w-full py-2.5 px-4 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-bold focus:ring-emerald-500 focus:border-emerald-500"
                                                placeholder="1" value="{{ old('replacement_pack_number') }}">
                                        </div>
                                        <div class="col-span-1">
                                            <label
                                                class="block text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1.5">Prefix</label>
                                            <input type="text" name="replacement_prefix" required maxlength="3"
                                                class="w-full py-2.5 px-4 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-bold uppercase focus:ring-emerald-500 focus:border-emerald-500"
                                                placeholder="RBC" value="{{ old('replacement_prefix') }}">
                                        </div>
                                    </div>

                                    <div
                                        class="p-5 bg-emerald-50/50 dark:bg-emerald-900/10 rounded-2xl border border-emerald-100 dark:border-emerald-800/20 space-y-4">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label
                                                    class="block text-[9px] font-black text-emerald-600 uppercase tracking-widest mb-1.5">Digit
                                                    Awal</label>
                                                <input type="text" name="rep_start_offset" required maxlength="3"
                                                    class="w-full py-2.5 px-4 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-bold focus:ring-emerald-500 focus:border-emerald-500"
                                                    placeholder="001" value="{{ old('rep_start_offset') }}">
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-[9px] font-black text-emerald-600 uppercase tracking-widest mb-1.5">Digit
                                                    Akhir</label>
                                                <input type="text" name="rep_end_offset" required maxlength="3"
                                                    class="w-full py-2.5 px-4 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-bold focus:ring-emerald-500 focus:border-emerald-500"
                                                    placeholder="500" value="{{ old('rep_end_offset') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="mt-8 pt-6 border-t border-gray-100 dark:border-slate-700 flex justify-between items-center">
                                <p class="text-[10px] font-medium text-red-400 italic">* Gunakan 000 untuk seri akhir
                                    1.000.
                                    Jumlah bilyet seri asal dan seri pengganti harus sama.</p>
                                <button type="submit"
                                    class="px-8 py-3 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all shadow-lg active:scale-95">
                                    Simpan Inschiet Parsial
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Tab 4: Inschiet Vell --}}
                    <div x-show="activeTab === 'vell'" x-transition style="display: none;">
                        <form action="{{ route('x-pengganti.mapping.store.vell') }}" method="POST">
                            @csrf
                            <input type="hidden" name="x_pengganti_seri_id" value="{{ $seri->id }}">
                            <div
                                class="mb-6 p-4 bg-blue-50/80 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-800/30 rounded-xl flex gap-4">
                                <div class="shrink-0 text-blue-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h5
                                        class="text-xs font-black text-blue-800 dark:text-blue-300 uppercase tracking-widest mb-1">
                                        Inschiet Vell</h5>
                                    <p
                                        class="text-[11px] font-medium text-blue-600/80 dark:text-blue-400/80 leading-relaxed">
                                        Untuk inschiet per vell. Sistem akan
                                        otomatis mencari dan memotong <i>(split-range)</i> nomor seri tersebut dari 45
                                        prefix
                                        seri secara bersamaan dalam satu kali eksekusi simpan.</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <h4 class="text-xs font-black text-blue-600 uppercase tracking-widest">Seri Asal
                                        (Rusak)</h4>
                                    <div>
                                        <label
                                            class="block text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1">Nomor
                                            Seri (Vell)</label>
                                        <input type="text" name="source_serial" required maxlength="6"
                                            inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                            class="w-full py-2.5 px-4 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-bold focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Contoh: 701500" value="{{ old('source_serial') }}">
                                    </div>
                                    <div class="p-3 bg-blue-50 dark:bg-blue-900/10 rounded-xl">
                                        <p class="text-[9px] font-black text-blue-500 uppercase tracking-widest">Akan
                                            diulang otomatis ke seluruh 45 prefix seri</p>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <h4 class="text-xs font-black text-emerald-600 uppercase tracking-widest">Seri
                                        Pengganti (X)</h4>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label
                                                class="block text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1">
                                                Seri 1 (2 huruf)</label>
                                            <input type="text" name="rep_seri1_base" required maxlength="2"
                                                pattern="[A-Za-z]{2}" oninput="this.value = this.value.toUpperCase()"
                                                class="w-full py-2.5 px-4 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-bold uppercase focus:ring-emerald-500 focus:border-emerald-500"
                                                placeholder="Contoh: NC" value="{{ old('rep_seri1_base') }}">
                                        </div>
                                        <div>
                                            <label
                                                class="block text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1">
                                                Seri 2 (2 huruf)</label>
                                            <input type="text" name="rep_seri2_base" required maxlength="2"
                                                pattern="[A-Za-z]{2}" oninput="this.value = this.value.toUpperCase()"
                                                class="w-full py-2.5 px-4 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-bold uppercase focus:ring-emerald-500 focus:border-emerald-500"
                                                placeholder="Contoh: RB" value="{{ old('rep_seri2_base') }}">
                                        </div>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1">Nomor
                                            Seri Pengganti (Vell)</label>
                                        <input type="text" name="replacement_serial" required maxlength="6"
                                            inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                            class="w-full py-2.5 px-4 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-bold focus:ring-emerald-500 focus:border-emerald-500"
                                            placeholder="Contoh: 000500" value="{{ old('replacement_serial') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6 flex justify-end">
                                <button type="submit"
                                    class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg active:scale-95">
                                    Simpan Inschiet Vell
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Tab 5: Per Bilyet --}}
                    <div x-show="activeTab === 'single'" x-transition style="display: none;">
                        <form action="{{ route('x-pengganti.mapping.store.single') }}" method="POST">
                            @csrf
                            <input type="hidden" name="x_pengganti_seri_id" value="{{ $seri->id }}">
                            <div
                                class="mb-6 p-4 bg-emerald-100 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700/50 rounded-xl flex gap-4">
                                <div class="shrink-0 text-emerald-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h5
                                        class="text-xs font-black text-emerald-900 dark:text-emerald-200 uppercase tracking-widest mb-1">
                                        Inschiet Bilyet</h5>
                                    <p
                                        class="text-[11px] font-medium text-emerald-600/80 dark:text-emerald-400/80 leading-relaxed">
                                        Inschiet per 1 bilyet. Jika seri x pengganti ini memotong/masuk di tengah range
                                        inschiet yang
                                        sudah ada, algoritma ini otomatis akan memecah range asal menjadi
                                        3 bagian terpisah tanpa menghapus data aslinya.</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <h4 class="text-xs font-black text-emerald-600 uppercase tracking-widest">Seri Asal
                                        (Rusak)</h4>
                                    <div>
                                        <label
                                            class="block text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1">Prefix
                                            Huruf Seri (3 huruf)</label>
                                        <input type="text" name="source_prefix" required maxlength="3"
                                            pattern="[A-Za-z]{3}" oninput="this.value = this.value.toUpperCase()"
                                            class="w-full py-2.5 px-4 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-bold uppercase focus:ring-emerald-500 focus:border-emerald-500"
                                            placeholder="Contoh: ABA" value="{{ old('source_prefix') }}">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1">Nomor
                                            Serial</label>
                                        <input type="text" name="source_serial" required maxlength="6"
                                            inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                            class="w-full py-2.5 px-4 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-bold focus:ring-emerald-500 focus:border-emerald-500"
                                            placeholder="Contoh: 701500" value="{{ old('source_serial') }}">
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <h4 class="text-xs font-black text-emerald-600 uppercase tracking-widest">Seri
                                        Pengganti (X)</h4>
                                    <div>
                                        <label
                                            class="block text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1">Prefix
                                            Huruf Seri (3 huruf)</label>
                                        <input type="text" name="replacement_prefix" required maxlength="3"
                                            pattern="[A-Za-z]{3}" oninput="this.value = this.value.toUpperCase()"
                                            class="w-full py-2.5 px-4 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-bold uppercase focus:ring-emerald-500 focus:border-emerald-500"
                                            placeholder="Contoh: RCA" value="{{ old('replacement_prefix') }}">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1">Nomor
                                            Seri</label>
                                        <input type="text" name="replacement_serial" required maxlength="6"
                                            inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                            class="w-full py-2.5 px-4 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-bold focus:ring-emerald-500 focus:border-emerald-500"
                                            placeholder="Contoh: 000001" value="{{ old('replacement_serial') }}">
                                    </div>
                                </div>
                            </div>
                            <div
                                class="mt-4 p-3 bg-emerald-100 dark:bg-emerald-950/30 rounded-xl border border-emerald-200 dark:border-emerald-800/50">
                                <p
                                    class="text-[9px] font-black text-emerald-800 dark:text-emerald-400 uppercase tracking-widest">
                                    ⚠️ Jika seri
                                    ini ada di dalam range x pengganti yang sudah di input sebelumnya, range akan
                                    otomatis di-split menjadi 3 bagian</p>
                            </div>
                            <div class="mt-4 flex justify-end">
                                <button type="submit"
                                    class="px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:from-emerald-700 hover:to-teal-700 transition-all shadow-lg active:scale-95">
                                    Simpan Inschiet Bilyet
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Existing Mappings Table --}}
            <div
                class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 dark:border-slate-700">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700">
                    <h3 class="text-sm font-black text-gray-700 dark:text-white uppercase tracking-widest">Data Inschiet
                        ({{ number_format($totalMappings) }} Entri)</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-gray-50/50 dark:bg-slate-700/50 text-[9px] font-black text-gray-400 dark:text-slate-400 uppercase tracking-widest border-b border-gray-100 dark:border-slate-700">
                                <th class="px-3 py-2.5 text-center">Pack</th>
                                <th class="px-3 py-2.5 text-center">Kategori</th>
                                <th class="px-3 py-2.5 text-left">Seri Asal</th>
                                <th class="px-3 py-2.5 text-center">→</th>
                                <th class="px-3 py-2.5 text-left">Seri Pengganti</th>
                                <th class="px-3 py-2.5 text-center">Bilyet</th>
                                <th class="px-3 py-2.5 text-center">Tipe</th>
                                <th class="px-3 py-2.5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                            @forelse($mappings as $m)
                                @php
                                    $catColors = [
                                        'seri_1' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                        'seri_2' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
                                        'campuran_1' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                        'campuran_2' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                        'manual' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400',
                                    ];
                                    $typeIcons = [
                                        'pack' => 'pack',
                                        'brood' => 'brood',
                                        'vell' => 'vell',
                                        'bilyet' => 'bilyet',
                                    ];
                                @endphp
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-700/50 transition-colors text-[11px]">
                                    <td class="px-3 py-2 text-center font-black text-gray-600 dark:text-gray-300">
                                        {{ $m->nomor_pack }}
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        <span
                                            class="inline-block px-2 py-0.5 rounded-md text-[8px] font-black uppercase {{ $catColors[$m->source_category] ?? 'bg-gray-100 text-gray-600' }}">{{ str_replace('_', ' ', $m->source_category) }}</span>
                                    </td>
                                    <td class="px-3 py-2 font-mono font-bold text-gray-700 dark:text-gray-200 text-[10px]">
                                        {{ $m->source_display }}
                                    </td>
                                    <td class="px-3 py-2 text-center text-gray-300 dark:text-slate-600">→</td>
                                    <td
                                        class="px-3 py-2 font-mono font-bold text-emerald-700 dark:text-emerald-400 text-[10px]">
                                        {{ $m->replacement_display }}
                                    </td>
                                    <td class="px-3 py-2 text-center font-bold text-gray-600 dark:text-gray-300">
                                        {{ number_format($m->bilyet_count) }}
                                    </td>
                                    <td class="px-3 py-2 text-center">{{ $typeIcons[$m->unit_type] ?? '❓' }}</td>
                                    <td class="px-3 py-2 text-center">
                                        <form action="{{ route('x-pengganti.mapping.destroy', $m->id) }}" method="POST"
                                            class="inline delete-confirm">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="p-1 text-rose-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/30 rounded-lg transition-all"
                                                title="Hapus">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-10 text-center">
                                        <p
                                            class="text-xs font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest italic">
                                            Belum ada inschiet untuk seri ini</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($mappings->hasPages())
                    <div
                        class="px-6 py-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-700/30">
                        {{ $mappings->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    <script>
        document.querySelectorAll('.delete-confirm').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Hapus Mapping?', text: 'Data mapping ini akan dihapus.', icon: 'warning',
                        showCancelButton: true, confirmButtonColor: '#e11d48', cancelButtonColor: '#64748b',
                        confirmButtonText: 'Ya, Hapus', cancelButtonText: 'Batal'
                    }).then((result) => { if (result.isConfirmed) form.submit(); });
                } else if (confirm('Hapus mapping ini?')) { form.submit(); }
            });
        });

        // Pencegahan Double Submit (Menghindari PostgreSQL Lock / Waktu Loading Lama)
        document.querySelectorAll('form').forEach(form => {
            if (!form.classList.contains('delete-confirm')) {
                form.addEventListener('submit', function (e) {
                    const btn = this.querySelector('button[type="submit"]');
                    if (btn) {
                        if (btn.classList.contains('processing')) {
                            e.preventDefault();
                            return;
                        }
                        btn.classList.add('processing');
                        const originalText = btn.innerHTML;
                        btn.innerHTML = '<span class="inline-block animate-spin mr-2">↻</span> Menyimpan...';
                        btn.style.opacity = '0.7';
                        btn.style.cursor = 'not-allowed';
                    }
                });
            }
        });
    </script>
</x-app-layout>