<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-xl leading-tight transition-colors" :class="darkMode ? 'text-white' : 'text-slate-800'">
            {{ __('Edit Data Penerimaan HCS ') . $hcsReceiving->batch }}
        </h2>
    </x-slot>

    <div class="py-6 bg-gray-50/30 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white/70 backdrop-blur-xl overflow-hidden shadow-[0_20px_50px_-12px_rgba(0,0,0,0.1)] sm:rounded-3xl border border-white">
                @php
                    $selectedPecahan = old('pecahan', $hcsReceiving->pecahan);
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
                    }" class="border-t-8 transition-all duration-700"
                    :class="currentTheme ? currentTheme.border : 'border-indigo-500'">
                    <form id="hcs-form" action="{{ route('hcs-receiving.update', $hcsReceiving->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="flex flex-col lg:flex-row gap-0">

                            <!-- Kiri: Field Form -->
                            <div class="w-full lg:w-[45%] p-6 lg:p-8 border-r border-gray-100 bg-white/40">
                                <div class="mb-8 flex items-center justify-between">
                                    <h3 class="text-xl font-black text-gray-900 tracking-tight flex items-center">
                                        <span class="w-2 h-8 mr-4 rounded-full transition-all duration-700"
                                            :class="currentTheme ? currentTheme.bg : 'bg-indigo-500'"></span>
                                        Detail Penerimaan
                                        <span
                                            class="ml-3 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider transition-all duration-700"
                                            :class="currentTheme ? (currentTheme.soft + ' ' + currentTheme.icon + ' border ' + currentTheme.border) : 'bg-gray-100 text-gray-500 border border-gray-200'"
                                            x-text="selectedPecahan ? (selectedPecahan) : ''"></span>
                                    </h3>
                                    <div class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border transition-all duration-700"
                                        :class="currentTheme ? (currentTheme.border + ' ' + currentTheme.icon + ' ' + currentTheme.soft) : 'border-indigo-200 text-indigo-600 bg-indigo-50'">
                                        HCS
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5">
                                    <div class="space-y-1.5">
                                        <label for="nomor_bon"
                                            class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Nomor
                                            Bon</label>
                                        <input id="nomor_bon" name="nomor_bon" type="text"
                                            class="block w-full py-2.5 px-4 border-gray-200 rounded-xl bg-white/50 backdrop-blur-sm shadow-sm transition-all duration-300 font-bold text-sm opacity-70 cursor-not-allowed"
                                            value="{{ old('nomor_bon', $hcsReceiving->nomor_bon) }}" readonly
                                            required />
                                    </div>

                                    <div class="space-y-1.5">
                                        <label for="tanggal_penerimaan"
                                            class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Tanggal
                                            Penerimaan</label>
                                        <input id="tanggal_penerimaan" name="tanggal_penerimaan" type="date"
                                            class="block w-full py-2.5 px-4 border-gray-200 rounded-xl bg-white/50 backdrop-blur-sm shadow-sm transition-all duration-300 focus:ring-4 font-bold text-sm"
                                            :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring.replace('focus:', '')) : 'focus:border-indigo-500 focus:ring-indigo-500/20'"
                                            value="{{ old('tanggal_penerimaan', $hcsReceiving->tanggal_penerimaan) }}"
                                            required />
                                    </div>

                                    <div class="space-y-1.5 relative">
                                        <label for="pecahan"
                                            class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1 flex items-center gap-1">
                                            Pecahan
                                            <svg class="w-3 h-3 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </label>
                                        <select id="pecahan_select" name="pecahan" x-model="selectedPecahan"
                                            class="block w-full py-2.5 px-4 border-gray-200 rounded-xl bg-white/50 pointer-events-none opacity-60 shadow-sm transition-all duration-300 font-black text-sm"
                                            :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring.replace('focus:', '')) : 'focus:border-indigo-500 focus:ring-indigo-500/20 text-indigo-600'"
                                            tabindex="-1" required>
                                            <option value="">Pilih Pecahan</option>
                                            @foreach(['S' => '1.000', 'T' => '2.000', 'U' => '5.000', 'V' => '10.000', 'W' => '20.000', 'X' => '50.000', 'Y' => '100.000'] as $key => $label)
                                                <option value="{{ $key }}" {{ $selectedPecahan == $key ? 'selected' : '' }}>
                                                    {{ $key }} - {{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="pecahan" value="{{ $selectedPecahan }}">
                                    </div>

                                    <div class="space-y-1.5">
                                        <label for="jumlah_display"
                                            class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Jumlah
                                            Bilyet</label>
                                        <div class="relative">
                                            <input id="jumlah_display" type="tel"
                                                class="block w-full py-3 px-4 text-right font-black text-xl pr-14 border-gray-200 rounded-xl bg-white/50 backdrop-blur-sm shadow-sm transition-all duration-300 focus:ring-4 placeholder-gray-300"
                                                :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring.replace('focus:', '')) : 'focus:border-indigo-500 focus:ring-indigo-500/20'"
                                                value="{{ old('jumlahDisplay', number_format($hcsReceiving->jumlah, 0, ',', '.')) }}"
                                                placeholder="0" required />
                                            <div
                                                class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-300 font-bold text-xs">
                                                Bilyet</div>
                                        </div>
                                        <input type="hidden" id="jumlah_original" name="jumlah"
                                            value="{{ old('jumlah', $hcsReceiving->jumlah) }}">
                                    </div>

                                    <div class="space-y-1.5">
                                        <label for="gilir"
                                            class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Gilir</label>
                                        <select id="gilir" name="gilir"
                                            class="block w-full py-2.5 px-4 border-gray-200 rounded-xl bg-white/50 backdrop-blur-sm shadow-sm transition-all duration-300 focus:ring-4 font-bold text-sm"
                                            :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring.replace('focus:', '')) : 'focus:border-indigo-500 focus:ring-indigo-500/20'"
                                            required>
                                            <option value="">Pilih Gilir</option>
                                            <option value="Gilir 1" {{ old('gilir', $hcsReceiving->gilir) == 'Gilir 1' ? 'selected' : '' }}>Gilir 1</option>
                                            <option value="Gilir 2" {{ old('gilir', $hcsReceiving->gilir) == 'Gilir 2' ? 'selected' : '' }}>Gilir 2</option>
                                            <option value="Gilir 3" {{ old('gilir', $hcsReceiving->gilir) == 'Gilir 3' ? 'selected' : '' }}>Gilir 3</option>
                                        </select>
                                    </div>

                                    <div class="space-y-1.5">
                                        <label for="mesin"
                                            class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Mesin</label>
                                        <input id="mesin" name="mesin" type="text"
                                            class="block w-full py-2.5 px-4 border-gray-200 rounded-xl bg-white/50 backdrop-blur-sm shadow-sm transition-all duration-300 focus:ring-4 placeholder-gray-300 font-bold text-sm"
                                            :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring.replace('focus:', '')) : 'focus:border-indigo-500 focus:ring-indigo-500/20'"
                                            value="{{ old('mesin', $hcsReceiving->mesin) }}" placeholder="Mesin"
                                            required />
                                    </div>

                                    <div class="space-y-1.5">
                                        <label for="supplier"
                                            class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Supplier</label>
                                        <select id="supplier" name="supplier"
                                            class="block w-full py-2.5 px-4 border-gray-200 rounded-xl bg-white/50 backdrop-blur-sm shadow-sm transition-all duration-300 focus:ring-4 font-bold text-sm"
                                            :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring.replace('focus:', '')) : 'focus:border-indigo-500 focus:ring-indigo-500/20'"
                                            required>
                                            <option value="">Pilih Supplier</option>
                                            <option value="Cutpack" {{ old('supplier', $hcsReceiving->supplier) == 'Cutpack' ? 'selected' : '' }}>Cutpack
                                            </option>
                                            <option value="Rikyet" {{ old('supplier', $hcsReceiving->supplier) == 'Rikyet' ? 'selected' : '' }}>Rikyet</option>
                                        </select>
                                    </div>

                                    <div class="space-y-1.5 relative">
                                        <label for="batch"
                                            class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1 flex items-center gap-1">
                                            Batch
                                            <svg class="w-3 h-3 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </label>
                                        <input id="batch" name="batch" type="text" maxlength="7"
                                            class="block w-full py-2.5 px-4 font-mono uppercase bg-white/50 border-gray-200 rounded-xl backdrop-blur-sm shadow-sm transition-all duration-300 tracking-tighter placeholder-gray-300 font-bold text-sm opacity-60 cursor-not-allowed"
                                            value="{{ old('batch', $hcsReceiving->batch) }}" readonly required />
                                    </div>

                                    <div class="space-y-1.5 relative">
                                        <label for="seri"
                                            class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1 flex items-center gap-1">
                                            Seri
                                            <svg class="w-3 h-3 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </label>
                                        <input id="seri" name="seri" type="text"
                                            class="block w-full py-2.5 px-4 font-mono uppercase bg-white/50 border-gray-200 rounded-xl backdrop-blur-sm shadow-sm transition-all duration-300 tracking-[0.2em] placeholder-gray-300 font-bold text-sm opacity-60 cursor-not-allowed"
                                            placeholder="Seri" value="{{ old('seri', $hcsReceiving->seri) }}" readonly
                                            required />
                                    </div>

                                    <div class="space-y-1.5 relative">
                                        <label for="emisi"
                                            class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1 flex items-center gap-1">
                                            Emisi (Tahun)
                                            <svg class="w-3 h-3 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </label>
                                        <input id="emisi" name="emisi" type="number" min="2000" max="2100"
                                            class="block w-full py-2.5 px-4 border-gray-200 rounded-xl bg-white/50 pointer-events-none opacity-60 shadow-sm transition-all duration-300 font-bold text-sm cursor-not-allowed"
                                            :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring.replace('focus:', '')) : 'focus:border-indigo-500 focus:ring-indigo-500/20'"
                                            value="{{ old('emisi', $hcsReceiving->emisi) }}" readonly required />
                                    </div>

                                    <div class="space-y-1.5 relative">
                                        <label for="tahun_anggaran"
                                            class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1 flex items-center gap-1">
                                            Tahun Anggaran
                                            <svg class="w-3 h-3 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </label>
                                        <select id="tahun_anggaran" name="tahun_anggaran"
                                            class="block w-full py-2.5 px-4 border-gray-200 rounded-xl bg-white/50 pointer-events-none opacity-60 shadow-sm transition-all duration-300 font-bold text-sm"
                                            :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring.replace('focus:', '')) : 'focus:border-indigo-500 focus:ring-indigo-500/20'"
                                            tabindex="-1" required>
                                            @foreach(['2024', '2025', '2026', '2027'] as $year)
                                                <option value="{{ $year }}" {{ old('tahun_anggaran', $hcsReceiving->tahun_anggaran ?? '2025') == $year ? 'selected' : '' }}>
                                                    {{ $year }}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="tahun_anggaran"
                                            value="{{ old('tahun_anggaran', $hcsReceiving->tahun_anggaran) }}">
                                    </div>

                                    <div class="col-span-1 sm:col-span-2 mt-4">
                                        <div
                                            class="p-6 rounded-[2rem] border border-gray-100 bg-white/80 shadow-inner flex flex-col sm:flex-row items-center justify-between gap-6 overflow-hidden relative group">
                                            <div
                                                class="absolute -right-4 -top-4 w-24 h-24 bg-gray-50 rounded-full blur-3xl transition-all duration-700 group-hover:bg-indigo-50">
                                            </div>

                                            <div class="flex flex-col gap-3 relative"
                                                x-data="{ isManual: {{ ($hcsReceiving->is_manual || ($hcsReceiving->jumlah % 45000 !== 0)) ? 'true' : 'false' }} }">
                                                <!-- Slider Pengalih untuk Manual -->
                                                <div
                                                    class="flex items-center justify-between bg-white/50 border border-gray-200 p-2 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 min-w-[280px]">
                                                    <div class="flex items-center mr-4">
                                                        <span class="text-xs font-black transition-colors duration-300"
                                                            :class="isManual ? 'text-red-600' : 'text-gray-500'">
                                                            Input Pack Tidak Full ( < 45.000 ) </span>
                                                    </div>
                                                    <label class="relative inline-flex items-center cursor-pointer">
                                                        <input type="checkbox" id="is_manual" name="is_manual" value="1"
                                                            x-model="isManual"
                                                            @change="typeof updateCalculations === 'function' ? updateCalculations(jumlahOriginal, isManual) : null; typeof renderGrid === 'function' ? renderGrid() : null"
                                                            class="sr-only peer">
                                                        <div
                                                            class="w-10 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-amber-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-red-500">
                                                        </div>
                                                    </label>
                                                </div>

                                                <div class="flex items-center relative gap-4 ml-1">
                                                    <div class="relative inline-flex items-center cursor-pointer group">
                                                        <input id="repass" name="repass" value="repass" type="checkbox"
                                                            class="w-5 h-5 rounded-lg border-gray-300 shadow-sm transition-all duration-300 text-indigo-600 focus:ring-indigo-500"
                                                            {{ old('repass', $hcsReceiving->repass) ? 'checked' : '' }}>
                                                        <label for="repass"
                                                            class="ml-3 text-sm font-bold text-gray-600 cursor-pointer">Tandai
                                                            sebagai Repass</label>
                                                    </div>
                                                    <div class="h-8 w-[1px] bg-gray-100 hidden sm:block"></div>
                                                    <div
                                                        class="text-[10px] font-black uppercase tracking-widest text-gray-400 leading-tight">
                                                        Total Pack:<br>
                                                        <span id="packs_needed_display"
                                                            class="text-xl transition-colors duration-500"
                                                            :class="currentTheme ? currentTheme.icon : 'text-indigo-600'">0</span>
                                                    </div>
                                                </div>

                                                <div class="pt-2">
                                                    <button type="submit"
                                                        class="w-full sm:w-auto flex justify-center items-center py-3.5 px-10 border border-transparent shadow-xl text-xs font-black rounded-xl transition-all duration-300 uppercase tracking-[0.2em] relative overflow-hidden group min-w-[200px] hover:scale-[1.02] active:scale-[0.98] hover:shadow-2xl"
                                                        :class="currentTheme ? (currentTheme.btn + ' ' + currentTheme.text) : 'bg-indigo-600 text-white'">
                                                        <!-- Efek Kilau -->
                                                        <div
                                                            class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:animate-[shine_1.5s_infinite]">
                                                        </div>

                                                        <span class="relative z-10">Update Penerimaan</span>
                                                        <svg class="relative z-10 ml-2 w-4 h-4 transform group-hover:translate-x-1 transition-transform"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <p
                                            class="mt-4 px-6 text-[10px] text-red-500 font-black uppercase tracking-widest text-center animate-pulse">
                                            ⚠️ Data Batch, Seri, Pecahan, TA, dan TE telah dikunci untuk menjaga
                                            integritas satu batch.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Kanan: Grid Pack -->
                            <div class="w-full lg:w-[55%] p-8 lg:p-10 bg-gray-50/20 backdrop-blur-sm">
                                <div class="mb-1">
                                    <h3 class="text-xl font-black text-gray-900 tracking-tight flex items-center">
                                        <svg class="w-6 h-6 mr-3 text-indigo-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                        </svg>
                                        Tabel Penerimaan HCS
                                    </h3>
                                </div>

                                <!-- Kartu Ringkasan -->
                                <div class="grid grid-cols-2 gap-2 mb-1">
                                    <div
                                        class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm transition-all duration-500 hover:shadow-md">
                                        <span
                                            class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-1">Pack
                                            Dipilih</span>
                                        <div class="flex items-end gap-1">
                                            <span id="selected_packs_length"
                                                class="text-2xl font-black leading-none transition-colors duration-500"
                                                :class="currentTheme ? (currentTheme.blue || 'text-indigo-600') : 'text-indigo-600'">0</span>
                                            <span
                                                class="text-[10px] font-bold text-gray-300 mb-1 uppercase tracking-tight">Pack</span>
                                        </div>
                                    </div>
                                    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                                        <span
                                            class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-1">Dibutuhkan</span>
                                        <div class="flex items-end gap-1">
                                            <span id="packs_needed_length"
                                                class="text-2xl font-black leading-none text-gray-900">0</span>
                                            <span
                                                class="text-[10px] font-bold text-gray-300 mb-1 uppercase tracking-tight">Pack</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Keterangan -->
                                <div class="bg-white/40 p-4 rounded-xl border border-white mb-1">
                                    <h4
                                        class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3 ml-1">
                                        Keterangan</h4>
                                    <div class="flex flex-wrap gap-x-6 gap-y-3">
                                        <div class="flex items-center gap-1">
                                            <div class="w-4 h-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                                            </div> <span class="text-[10px] font-bold text-gray-600">Kosong</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <div class="w-4 h-4 bg-blue-300 rounded-lg shadow-sm"></div> <span
                                                class="text-[10px] font-bold text-gray-600">Cutpack</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <div class="w-4 h-4 bg-green-300 rounded-lg shadow-sm"></div> <span
                                                class="text-[10px] font-bold text-gray-600">Rikyet</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <div
                                                class="w-4 h-4 bg-red-500 border border-red-600 rounded-lg shadow-lg shadow-red-500/20">
                                            </div> <span class="text-[10px] font-bold text-gray-600">Tersortir</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <div class="flex -space-x-1">
                                                <div class="w-4 h-4 bg-sky-400 rounded-lg shadow-sm"></div>
                                                <div class="w-4 h-4 bg-emerald-400 rounded-lg shadow-sm"></div>
                                            </div>
                                            <span class="text-[10px] font-bold text-gray-600 ml-2">Pilihan Sesi
                                                Ini</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Input Tersembunyi untuk mengirim array -->
                                <div id="hidden_packs_container"></div>

                                <!-- Grid Container -->
                                <div class="relative group/grid">
                                    <div
                                        class="absolute inset-0 bg-indigo-500/5 blur-[100px] rounded-full opacity-0 group-hover/grid:opacity-100 transition-opacity duration-1000">
                                    </div>
                                    <div class="grid grid-rows-10 grid-flow-col gap-1 sm:gap-1.5 auto-cols-[minmax(0,_1fr)] relative"
                                        id="pack_grid">
                                        @for ($i = 1; $i <= 100; $i++)
                                            @php
                                                $row = ($i - 1) % 10;
                                                $col = floor(($i - 1) / 10);

                                                // Positioning logic mirroring batch-tracking
                                                $vClass = ($row < 4) ? 'top-full mt-2 flex-col-reverse' : 'bottom-full mb-2 flex-col';
                                                $arrowV = ($row < 4) ? '-mb-1' : '-mt-1';

                                                if ($col < 3) {
                                                    $hClass = 'left-0 translate-x-0';
                                                    $arrowH = 'left-3 translate-x-0';
                                                } elseif ($col > 6) {
                                                    $hClass = 'right-0 left-auto translate-x-0';
                                                    $arrowH = 'right-3 translate-x-0';
                                                } else {
                                                    $hClass = 'left-1/2 -translate-x-1/2';
                                                    $arrowH = 'left-1/2 -translate-x-1/2';
                                                }
                                            @endphp
                                            <div class="relative group">
                                                <button type="button" data-pack="{{ $i }}"
                                                    class="pack-btn w-full aspect-square flex items-center justify-center text-[10px] sm:text-xs font-black rounded-lg transition-all duration-300 pack-btn-available shadow-sm hover:scale-105 hover:z-10 focus:outline-none focus:ring-4"
                                                    :class="currentTheme ? (currentTheme.ring.replace('focus:', '')) : 'focus:ring-indigo-500/20'">
                                                    <span>{{ $i }}</span>
                                                </button>
                                                <div
                                                    class="pack-tooltip pointer-events-none absolute {{ $vClass }} {{ $hClass }} z-[100] hidden group-hover:flex items-center">
                                                    <div
                                                        class="bg-gray-900/95 backdrop-blur-sm text-white text-[10px] rounded-xl px-3 py-2 whitespace-nowrap shadow-2xl text-center leading-tight border border-white/10 min-w-[140px]">
                                                        <div
                                                            class="tooltip-header font-black border-b border-white/20 pb-1.5 mb-1.5 flex items-center justify-center gap-2">
                                                            PACK {{ $i }}
                                                            <span
                                                                class="tooltip-badge hidden px-2 py-0.5 rounded-full text-[8px] text-white"></span>
                                                        </div>
                                                        <div
                                                            class="tooltip-supplier font-bold uppercase tracking-tighter text-indigo-300">
                                                            KOSONG</div>
                                                        <div
                                                            class="tooltip-status text-gray-400 text-[9px] mt-1 font-medium">
                                                            Bisa Dipilih</div>
                                                    </div>
                                                    <div class="w-2 h-2 bg-gray-900 rotate-45 {{ $arrowV }} {{ $arrowH }}">
                                                    </div>
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const inputJumlahDisplay = document.getElementById('jumlah_display');
                const inputJumlahOriginal = document.getElementById('jumlah_original');
                const spanPacksNeededDisplay = document.getElementById('packs_needed_display');
                const spanSelectedPacksLength = document.getElementById('selected_packs_length');
                const spanPacksNeededLength = document.getElementById('packs_needed_length');

                const selectSupplier = document.getElementById('supplier');
                const inputBatch = document.getElementById('batch');
                const inputSeri = document.getElementById('seri');
                const gridContainer = document.getElementById('pack_grid');
                const hiddenPacksContainer = document.getElementById('hidden_packs_container');
                const form = document.getElementById('hcs-form');
                const toggleManual = document.getElementById('is_manual');

                let jumlahOriginal = parseInt(inputJumlahOriginal.value, 10) || 0;
                let isInitialManual = {{ ($hcsReceiving->is_manual || ($hcsReceiving->jumlah % 45000 !== 0)) ? 'true' : 'false' }};
                let packsNeeded = isInitialManual ? (jumlahOriginal > 0 ? 1 : 0) : (Math.floor(jumlahOriginal / 45000) || 0);
                let dbPacks = {!! json_encode($hcsReceiving->packs->pluck('pack_number')->toArray()) !!};
                let oldPacks = {!! json_encode(old('packs', null)) !!};
                let selectedPacks = oldPacks ? oldPacks.map(Number) : dbPacks.map(Number);
                let usedPacks = [];
                const lockedPacks = {!! json_encode($sortedPacks) !!}.map(Number);
                const minPacks = {{ $hasSortedPacks ? $sortedPacksCount : 0 }};

                function init() {
                    if (jumlahOriginal > 0) {
                        inputJumlahDisplay.value = jumlahOriginal.toLocaleString('id-ID');
                    }

                    // Set initial display values based on pre-calculated packsNeeded
                    spanPacksNeededDisplay.textContent = packsNeeded;
                    spanPacksNeededLength.textContent = packsNeeded;

                    if (inputBatch.value.length === 7 && inputSeri.value.length > 5) {
                        fetchUsedPacks();
                    }
                    renderGrid();
                }

                // Expose logic to Alpine
                window.jumlahOriginal = jumlahOriginal;
                window.updateCalculations = updateCalculations;
                window.renderGrid = renderGrid;

                function handleJumlahInput(e) {
                    let textValue = String(e.target.value);
                    let rawDigits = textValue.replace(/\D/g, '');
                    let number = parseInt(rawDigits, 10) || 0;

                    if (toggleManual.checked && number > 45000) {
                        number = 45000;
                    }

                    jumlahOriginal = number;
                    inputJumlahOriginal.value = number;

                    if (number > 0) {
                        e.target.value = number.toLocaleString('id-ID');
                    } else {
                        e.target.value = '';
                    }

                    updateCalculations(number);
                    renderGrid();
                }

                function updateCalculations(numVal, isManualOverride = null) {
                    const isManual = isManualOverride !== null ? isManualOverride : toggleManual.checked;
                    if (isManual) {
                        packsNeeded = numVal > 0 ? 1 : 0;
                    } else {
                        packsNeeded = Math.floor(numVal / 45000) || 0;
                    }

                    spanPacksNeededDisplay.textContent = packsNeeded;
                    spanPacksNeededLength.textContent = packsNeeded;

                    if (selectedPacks.length > packsNeeded) {
                        const lockedInSelection = selectedPacks.filter(p => lockedPacks.includes(p));
                        const nonLockedInSelection = selectedPacks.filter(p => !lockedPacks.includes(p));

                        if (lockedInSelection.length >= packsNeeded) {
                            selectedPacks = lockedInSelection.slice(0, packsNeeded);
                        } else {
                            selectedPacks = [...lockedInSelection, ...nonLockedInSelection.slice(0, packsNeeded - lockedInSelection.length)];
                        }
                    }
                }

                function fetchUsedPacks() {
                    const batchVal = inputBatch.value.toUpperCase();
                    const seriVal = inputSeri.value.toUpperCase();

                    if (batchVal.length === 7 && seriVal.length > 0) {
                        const pecahanVal = document.getElementById('pecahan_select')?.value || '';
                        const emisiVal = document.getElementById('emisi')?.value || '';
                        const taVal = document.getElementById('tahun_anggaran')?.value || '';

                        let url = `/api/packs/used?batch=${batchVal}&seri=${seriVal}&exclude_hcs_id={{ $hcsReceiving->id }}`;
                        if (pecahanVal) url += `&pecahan=${pecahanVal}`;
                        if (emisiVal) url += `&emisi=${emisiVal}`;
                        if (taVal) url += `&tahun_anggaran=${taVal}`;

                        fetch(url)
                            .then(res => res.json())
                            .then(data => {
                                usedPacks = data.map(p => ({ pack_number: parseInt(p.pack_number, 10), supplier: p.supplier, hcs_sorting_id: p.hcs_sorting_id }));
                                const usedPackNumbers = usedPacks.map(p => p.pack_number);
                                selectedPacks = selectedPacks.filter(p => !usedPackNumbers.includes(p));
                                renderGrid();
                            }).catch(e => console.error(e));
                    } else {
                        usedPacks = [];
                        renderGrid();
                    }
                }

                function togglePack(number) {
                    if (usedPacks.some(p => p.pack_number === number)) return;
                    if (lockedPacks.includes(number)) {
                        Swal.fire({
                            title: 'Data Terkunci',
                            text: 'Pack ini sudah disortir dan tidak dapat dibuang.',
                            icon: 'warning',
                            confirmButtonColor: '#4f46e5'
                        });
                        return;
                    }

                    let index = selectedPacks.indexOf(number);
                    if (index > -1) {
                        selectedPacks.splice(index, 1);
                    } else {
                        if (selectedPacks.length < packsNeeded) {
                            selectedPacks.push(number);
                        } else {
                            if (packsNeeded > 0) {
                                Swal.fire({
                                    title: 'Batas Terlampaui',
                                    text: `Anda hanya dapat memilih ${packsNeeded} packs sesuai dengan jumlah bilyet.`,
                                    icon: 'warning',
                                    confirmButtonColor: '#4f46e5'
                                });
                            } else {
                                Swal.fire({
                                    title: 'Perhatian',
                                    text: 'Silakan masukkan Jumlah Bilyet terlebih dahulu.',
                                    icon: 'info',
                                    confirmButtonColor: '#4f46e5'
                                });
                            }
                        }
                    }
                    renderGrid();
                }

                function renderGrid() {
                    hiddenPacksContainer.innerHTML = '';
                    selectedPacks.forEach(pack => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'packs[]';
                        input.value = pack;
                        hiddenPacksContainer.appendChild(input);
                    });

                    spanSelectedPacksLength.textContent = selectedPacks.length;
                    const currentSupplier = selectSupplier.value;
                    const buttons = gridContainer.querySelectorAll('.pack-btn');

                    buttons.forEach(btn => {
                        const num = parseInt(btn.getAttribute('data-pack'), 10);
                        const group = btn.closest('.group');
                        const tooltip = group.querySelector('.pack-tooltip');
                        const badge = tooltip.querySelector('.tooltip-badge');
                        const supplierEl = tooltip.querySelector('.tooltip-supplier');
                        const statusEl = tooltip.querySelector('.tooltip-status');

                        badge.classList.add('hidden');
                        supplierEl.className = 'tooltip-supplier font-bold uppercase tracking-tighter text-indigo-300';
                        supplierEl.textContent = 'KOSONG';
                        statusEl.textContent = 'Bisa Dipilih';

                        btn.className = 'pack-btn w-full aspect-square flex items-center justify-center text-[10px] sm:text-xs font-black rounded-lg transition-all duration-300 hover:scale-110 hover:z-10 focus:outline-none focus:ring-4';

                        const usedPack = usedPacks.find(p => p.pack_number === num);
                        if (usedPack) {
                            supplierEl.textContent = usedPack.supplier;
                            supplierEl.className = 'tooltip-supplier font-bold uppercase tracking-tighter ' + (usedPack.supplier === 'Cutpack' ? 'text-blue-300' : 'text-green-300');
                            statusEl.textContent = (usedPack.hcs_sorting_id ? 'Sudah Disortir (Record Lain)' : 'Terpakai (Record Lain)');

                            btn.classList.add('cursor-not-allowed', 'shadow-none');
                            if (usedPack.hcs_sorting_id) {
                                badge.textContent = 'TERSORTIR';
                                badge.className = 'tooltip-badge px-2 py-0.5 rounded-full text-[8px] text-white bg-red-500';
                                badge.classList.remove('hidden');
                                btn.classList.add('bg-red-400', 'text-white', 'border-red-500', 'shadow-lg', 'shadow-red-500/20');
                            } else {
                                if (usedPack.supplier === 'Cutpack') {
                                    btn.classList.add('bg-blue-100', 'text-blue-900', 'border-blue-200');
                                } else if (usedPack.supplier === 'Rikyet') {
                                    btn.classList.add('bg-green-100', 'text-green-900', 'border-green-200');
                                } else {
                                    btn.classList.add('bg-gray-100', 'text-gray-500');
                                }
                            }
                        } else if (lockedPacks.includes(num)) {
                            supplierEl.textContent = currentSupplier || 'HCS';
                            statusEl.textContent = 'Sudah Disortir (Terkunci)';
                            badge.textContent = 'TERKUNCI';
                            badge.className = 'tooltip-badge px-2 py-0.5 rounded-full text-[8px] text-white bg-red-600';
                            badge.classList.remove('hidden');
                            btn.classList.add('bg-red-600', 'text-white', 'border-red-700', 'shadow-lg', 'shadow-red-500/30', 'cursor-not-allowed');
                        } else if (selectedPacks.includes(num)) {
                            supplierEl.textContent = currentSupplier;
                            supplierEl.className = 'tooltip-supplier font-bold uppercase tracking-tighter ' + (currentSupplier === 'Cutpack' ? 'text-blue-300' : (currentSupplier === 'Rikyet' ? 'text-green-300' : 'text-indigo-300'));
                            statusEl.textContent = 'Dipilih (Penerimaan)';
                            badge.textContent = 'DIPILIH';
                            badge.className = 'tooltip-badge px-2 py-0.5 rounded-full text-[8px] text-white bg-indigo-500';
                            badge.classList.remove('hidden');

                            btn.classList.add('shadow-xl', 'text-white', 'scale-105', 'z-10');
                            if (currentSupplier === 'Cutpack') {
                                btn.classList.add('bg-blue-400', 'border-blue-500', 'shadow-blue-300/50');
                            } else if (currentSupplier === 'Rikyet') {
                                btn.classList.add('bg-emerald-400', 'border-emerald-500', 'shadow-emerald-300/50');
                            } else {
                                btn.classList.add('bg-indigo-500', 'border-indigo-600', 'shadow-indigo-300/50');
                            }
                        } else {
                            btn.classList.add('bg-white', 'text-gray-400', 'border-gray-100', 'shadow-sm', 'hover:bg-gray-50', 'hover:text-gray-600', 'hover:border-gray-200');
                        }
                    });
                }

                // Bind Events
                inputJumlahDisplay.addEventListener('input', handleJumlahInput);

                inputBatch.addEventListener('input', () => {
                    inputBatch.value = inputBatch.value.toUpperCase();
                    fetchUsedPacks();
                });

                inputSeri.addEventListener('input', (e) => {
                    let val = e.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
                    let formatted = '';
                    if (val.length > 0) {
                        formatted = val.substring(0, 2).replace(/[^A-Z]/g, '');
                        if (val.length > 2) {
                            formatted += '-' + val.substring(2, 4).replace(/[^A-Z]/g, '');
                            if (val.length > 4) {
                                formatted += val.substring(4, 5).replace(/[^0-9]/g, '');
                            }
                        }
                    }
                    e.target.value = formatted.substring(0, 6);
                    fetchUsedPacks();
                });

                selectSupplier.addEventListener('change', renderGrid);

                gridContainer.addEventListener('click', (e) => {
                    const btn = e.target.closest('.pack-btn');
                    if (btn) {
                        const packNum = parseInt(btn.getAttribute('data-pack'), 10);
                        togglePack(packNum);
                    }
                });

                form.addEventListener('submit', (e) => {
                    if (packsNeeded === 0 || jumlahOriginal === 0) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Data Tidak Valid',
                            text: 'Silakan masukkan Jumlah Bilyet yang valid terlebih dahulu.',
                            icon: 'error',
                            confirmButtonColor: '#4f46e5'
                        });
                        return;
                    }

                    if (selectedPacks.length === 0) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Pack Belum Dipilih',
                            text: `Anda belum memilih pack apapun. Silakan pilih ${packsNeeded} pack pada grid di sebelah kanan.`,
                            icon: 'warning',
                            confirmButtonColor: '#4f46e5'
                        });
                        return;
                    }

                    if (selectedPacks.length !== packsNeeded) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Jumlah Pack Tidak Sesuai',
                            text: `Jumlah pack yang dipilih (${selectedPacks.length}) tidak sesuai kebutuhan. Dibutuhkan ${packsNeeded} pack(s).`,
                            icon: 'error',
                            confirmButtonColor: '#4f46e5'
                        });
                        return;
                    }

                    if (!toggleManual.checked && (jumlahOriginal % 45000 !== 0)) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Jumlah Bilyet Tidak Valid',
                            text: 'Jumlah bilyet harus kelipatan 45.000 jika tidak menggunakan mode pack tidak full.',
                            icon: 'error',
                            confirmButtonColor: '#4f46e5'
                        });
                        return;
                    }

                    if (packsNeeded < minPacks) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Jumlah Bilyet Terlalu Kecil',
                            text: `Minimal pack yang dimasukkan harus ${minPacks} pack, sesuai dengan pack yang sudah disortir.`,
                            icon: 'error',
                            confirmButtonColor: '#4f46e5'
                        });
                        return;
                    }
                });

                init();
            });
        </script>
    @endpush

    @push('css')
        <style>
            [x-cloak] { display: none !important; }

            /* Mengembalikan Estetika Mode Gelap High-Fidelity untuk Halaman Edit */
            body.dark-mode [class*="bg-gray-50/30"] { background-color: var(--theme-bg-main) !important; }
            body.dark-mode [class*="bg-white/70"] { background-color: rgba(30, 41, 59, 0.7) !important; border-color: var(--theme-border-main) !important; backdrop-blur: 40px !important; }
            
            body.dark-mode .lg\:w-\[45\%\] { background-color: rgba(15, 23, 42, 0.4) !important; border-right-color: var(--theme-border-main) !important; }
            body.dark-mode .lg\:w-\[55\%\] { background-color: rgba(15, 23, 42, 0.2) !important; }
            
            /* Elemen Bersarang & Kartu Dalam - Restorasi Kedalaman */
            body.dark-mode .bg-white\/80, 
            body.dark-mode .bg-white\/40, 
            body.dark-mode .bg-white\/50,
            body.dark-mode .bg-white\/20:not(nav *) { 
                background-color: rgba(30, 41, 59, 0.5) !important; 
                border-color: var(--theme-border-main) !important; 
                backdrop-blur: 10px !important;
            }

            /* Pengambilalihan Input & Field */
            body.dark-mode input.bg-white\/50,
            body.dark-mode select.bg-white\/50 {
                background-color: rgba(15, 23, 42, 0.6) !important;
                border-color: var(--theme-border-main) !important;
                color: #f8fafc !important;
            }

            body.dark-mode .text-gray-900, body.dark-mode .text-gray-800 { color: var(--theme-text-main) !important; }
            body.dark-mode .text-gray-600, body.dark-mode .text-gray-700 { color: var(--theme-text-muted) !important; }
            
            /* Border & Utilitas */
            body.dark-mode .border-white, 
            body.dark-mode .border-gray-100, 
            body.dark-mode .border-gray-200 { 
                border-color: var(--theme-border-main) !important; 
            }

            /* Tooltip Grid Pack & Elemen Grid */
            body.dark-mode .pack-btn-available {
                background-color: #1a2434 !important;
                color: #64748b !important;
                border: 1px solid #334155 !important;
            }
            body.dark-mode .pack-btn-available:hover {
                background-color: #243047 !important;
                color: #94a3b8 !important;
            }

            body.light-mode .pack-btn-available {
                background-color: #ffffff !important;
                color: #94a3b8 !important;
                border: 1px solid #f1f5f9 !important;
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
            }
            body.light-mode .pack-btn-available:hover {
                background-color: #f8fafc !important;
                color: #475569 !important;
                border-color: #e2e8f0 !important;
            }

            /* Pemurnian Mode Terang untuk Halaman Edit */
            body.light-mode .bg-white\/70.backdrop-blur-xl { background-color: #ffffff !important; border-color: #e5e7eb !important; }
            body.light-mode .lg\:w-\[45\%\]\.bg-white\/40 { background-color: #f8fafc !important; border-right-color: #e5e7eb !important; }
            body.light-mode .lg\:w-\[55\%\]\.bg-gray-50\/20 { background-color: #ffffff !important; }
            body.light-mode input, body.light-mode select, body.light-mode textarea {
                background-color: #ffffff !important;
                border-color: #d1d5db !important;
            }
            body.light-mode .bg-white\/50 { background-color: #ffffff !important; border-color: #d1d5db !important; }
            body.light-mode .bg-white\/80 { background-color: #ffffff !important; border-color: #e5e7eb !important; }
            body.light-mode .bg-white\/40 { background-color: #f1f5f9 !important; border-color: #e5e7eb !important; }
        </style>
    @endpush
</x-app-layout>