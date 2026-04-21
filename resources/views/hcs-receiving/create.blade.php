<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-xl leading-tight transition-colors"
            :class="darkMode ? 'text-white' : 'text-slate-800'">
            {{ __('Tambah Data Penerimaan HCS') }}
        </h2>
    </x-slot>

    <div class="py-1 bg-gray-50/30 h-[calc(100vh-65px)] overflow-hidden">
        <div class="max-w-full mx-auto px-4 lg:px-4 h-full">
            <div
                class="bg-white/70 backdrop-blur-xl h-full overflow-hidden shadow-xl sm:rounded-2xl border border-white flex flex-col">
                @php
                    $selectedPecahan = old('pecahan', request('pecahan', ''));
                    // Kunci jika menambahkan ke batch yang ada (dari tombol tambah di index)
                    $isLocked = request()->has('batch');
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
                        inputType: 'registration',
                        get currentTheme() { return this.themes[this.selectedPecahan] || null }
                    }" class="border-t-8 transition-all duration-700"
                    }" class="border-t-8 transition-all duration-700 h-full overflow-hidden"
                    :class="currentTheme ? currentTheme.border : 'border-indigo-500'">
                    <form id="hcs-form" action="{{ route('hcs-receiving.store') }}" method="POST" class="h-full overflow-hidden">
                        @csrf

                        <div class="flex flex-col lg:flex-row h-full overflow-hidden">

                            <!-- Kiri: Field Form -->
                            <div class="w-full lg:w-[40%] p-3 lg:p-4 border-r border-gray-100 bg-white/40 h-full overflow-y-auto custom-scrollbar">
                                <div
                                    class="mb-3 p-3 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-dashed border-slate-200 dark:border-slate-800">

                                    <div class="mb-3 flex items-center justify-between">
                                        <h3 class="text-base font-black text-gray-900 tracking-tight flex items-center">
                                            <span class="w-1.5 h-5 mr-3 rounded-full transition-all duration-700"
                                                :class="currentTheme ? currentTheme.bg : 'bg-indigo-500'"></span>
                                            Detail Penerimaan
                                            <span
                                                class="ml-2 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider transition-all duration-700"
                                                :class="currentTheme ? (currentTheme.soft + ' ' + currentTheme.icon + ' border ' + currentTheme.border) : 'bg-gray-100 text-gray-500 border border-gray-200'"
                                                x-text="selectedPecahan ? (selectedPecahan) : ''"></span>
                                        </h3>
                                        <div class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border transition-all duration-700"
                                            :class="currentTheme ? (currentTheme.border + ' ' + currentTheme.icon + ' ' + currentTheme.soft) : 'border-indigo-200 text-indigo-600 bg-indigo-50'">
                                            HCS
                                        </div>
                                    </div>
                                    <h4
                                        class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3 text-center">
                                        Tipe Input Data</h4>
                                    <div class="grid grid-cols-2 gap-4">

                                        <!-- Opsi Registrasi -->
                                        <label
                                            class="relative flex flex-col p-3 bg-white dark:bg-slate-800 rounded-xl border-2 cursor-pointer transition-all duration-300 hover:scale-[1.02]"
                                            :class="inputType === 'registration' ? (currentTheme ? currentTheme.border : 'border-indigo-500') : 'border-transparent opacity-60'">
                                            <input type="radio" name="input_type" value="registration"
                                                x-model="inputType" class="sr-only">
                                            <div class="flex items-center gap-2 mb-1.5">
                                                <div class="w-6 h-6 rounded flex items-center justify-center transition-colors"
                                                    :class="inputType === 'registration' ? (currentTheme ? currentTheme.bg : 'bg-indigo-500') : 'bg-slate-100'">
                                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2.5"
                                                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <span class="text-[10px] font-black uppercase tracking-widest"
                                                    :class="inputType === 'registration' ? 'text-slate-900 dark:text-white' : 'text-slate-400'">Registrasi</span>
                                            </div>
                                            <p
                                                class="text-[8px] font-bold text-slate-400 leading-tight uppercase tracking-tighter">
                                                Antrean & Barcode</p>
                                        </label>

                                        <!-- Opsi Manual -->
                                        <label
                                            class="relative flex flex-col p-3 bg-white dark:bg-slate-800 rounded-xl border-2 cursor-pointer transition-all duration-300 hover:scale-[1.02]"
                                            :class="inputType === 'direct' ? (currentTheme ? currentTheme.border : 'border-indigo-500') : 'border-transparent opacity-60'">
                                            <input type="radio" name="input_type" value="direct" x-model="inputType"
                                                class="sr-only">
                                            <div class="flex items-center gap-2 mb-1.5">
                                                <div class="w-6 h-6 rounded flex items-center justify-center transition-colors"
                                                    :class="inputType === 'direct' ? (currentTheme ? currentTheme.bg : 'bg-indigo-500') : 'bg-slate-100'">
                                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                                    </svg>
                                                </div>
                                                <span class="text-[10px] font-black uppercase tracking-widest"
                                                    :class="inputType === 'direct' ? 'text-slate-900 dark:text-white' : 'text-slate-400'">Manual</span>
                                            </div>
                                            <p
                                                class="text-[8px] font-bold text-slate-400 leading-tight uppercase tracking-tighter">
                                                Langsung Persediaan</p>
                                        </label>
                                    </div>
                                </div>


                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-3">
                                    <div class="space-y-1">
                                        <label for="nomor_bon"
                                            class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest ml-1">Nomor
                                            Bon</label>
                                        <input id="nomor_bon" name="nomor_bon" type="text"
                                            class="block w-full py-2 px-3 border-gray-200 rounded-lg bg-white/50 backdrop-blur-sm shadow-sm transition-all duration-300 focus:ring-4 placeholder-gray-300 font-bold text-xs"
                                            :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring.replace('ring-', 'ring-').replace('focus:', '')) : 'focus:border-indigo-500 focus:ring-indigo-500/20'"
                                            value="{{ old('nomor_bon') }}" placeholder="Nomor Bon" required />
                                    </div>

                                    <div class="space-y-1">
                                        <label for="tanggal_penerimaan"
                                            class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest ml-1">Tanggal
                                            Penerimaan</label>
                                        <input id="tanggal_penerimaan" name="tanggal_penerimaan" type="date"
                                            class="block w-full py-2 px-3 border-gray-200 rounded-lg bg-white/50 backdrop-blur-sm shadow-sm transition-all duration-300 focus:ring-4 font-bold text-xs"
                                            :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring.replace('focus:', '')) : 'focus:border-indigo-500 focus:ring-indigo-500/20'"
                                            value="{{ old('tanggal_penerimaan', date('Y-m-d')) }}" required />
                                    </div>

                                    <div class="space-y-1 relative">
                                        <label for="pecahan"
                                            class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest ml-1 flex items-center gap-1">
                                            Pecahan
                                            @if($isLocked)
                                                <svg class="w-3 h-3 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            @endif
                                        </label>
                                        <select id="pecahan" name="pecahan" x-model="selectedPecahan"
                                            class="block w-full py-2 px-3 border-gray-200 rounded-lg bg-white/50 backdrop-blur-sm shadow-sm transition-all duration-300 focus:ring-4 font-black text-xs {{ $isLocked ? 'bg-gray-100/80 pointer-events-none opacity-60' : '' }}"
                                            :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring.replace('focus:', '')) : 'focus:border-indigo-500 focus:ring-indigo-500/20 text-indigo-600'"
                                            {{ $isLocked ? 'tabindex="-1"' : '' }} required>
                                            <option value="">Pilih Pecahan</option>
                                            @foreach(['S' => '1.000', 'T' => '2.000', 'U' => '5.000', 'V' => '10.000', 'W' => '20.000', 'X' => '50.000', 'Y' => '100.000'] as $key => $label)
                                                <option value="{{ $key }}" {{ $selectedPecahan == $key ? 'selected' : '' }}>
                                                    {{ $key }} - {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if($isLocked) <input type="hidden" name="pecahan"
                                        value="{{ $selectedPecahan }}"> @endif
                                    </div>

                                    <div class="space-y-1">
                                        <label for="jumlah_display"
                                            class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest ml-1">Jumlah
                                            Bilyet</label>
                                        <div class="relative">
                                            <input id="jumlah_display" type="tel"
                                                class="block w-full py-1.5 px-3 text-right font-black text-lg pr-12 border-gray-200 rounded-lg bg-white/50 backdrop-blur-sm shadow-sm transition-all duration-300 focus:ring-4 placeholder-gray-300"
                                                :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring.replace('focus:', '')) : 'focus:border-indigo-500 focus:ring-indigo-500/20'"
                                                value="{{ old('jumlahDisplay') }}" placeholder="0" required />
                                            <div
                                                class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-300 font-bold text-[9px]">
                                                Bilyet</div>
                                        </div>
                                        <input type="hidden" id="jumlah_original" name="jumlah"
                                            value="{{ old('jumlah', 0) }}">
                                    </div>

                                    <div class="space-y-1">
                                        <label for="gilir"
                                            class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest ml-1">Gilir</label>
                                        <select id="gilir" name="gilir"
                                            class="block w-full py-2 px-3 border-gray-200 rounded-lg bg-white/50 backdrop-blur-sm shadow-sm transition-all duration-300 focus:ring-4 font-bold text-xs"
                                            :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring.replace('focus:', '')) : 'focus:border-indigo-500 focus:ring-indigo-500/20'"
                                            required>
                                            <option value="">Pilih Gilir</option>
                                            <option value="Gilir 1" {{ old('gilir') == 'Gilir 1' ? 'selected' : '' }}>
                                                Gilir 1</option>
                                            <option value="Gilir 2" {{ old('gilir') == 'Gilir 2' ? 'selected' : '' }}>
                                                Gilir 2</option>
                                            <option value="Gilir 3" {{ old('gilir') == 'Gilir 3' ? 'selected' : '' }}>
                                                Gilir 3</option>
                                        </select>
                                    </div>

                                    <div class="space-y-1">
                                        <label for="mesin"
                                            class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest ml-1">Mesin</label>
                                        <input id="mesin" name="mesin" type="text"
                                            class="block w-full py-2 px-3 border-gray-200 rounded-lg bg-white/50 backdrop-blur-sm shadow-sm transition-all duration-300 focus:ring-4 placeholder-gray-300 font-bold text-xs"
                                            :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring.replace('focus:', '')) : 'focus:border-indigo-500 focus:ring-indigo-500/20'"
                                            value="{{ old('mesin') }}" placeholder="Mesin" required />
                                    </div>

                                    <div class="space-y-1">
                                        <label for="supplier"
                                            class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest ml-1">Supplier</label>
                                        <select id="supplier" name="supplier"
                                            class="block w-full py-2 px-3 border-gray-200 rounded-lg bg-white/50 backdrop-blur-sm shadow-sm transition-all duration-300 focus:ring-4 font-bold text-xs"
                                            :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring.replace('focus:', '')) : 'focus:border-indigo-500 focus:ring-indigo-500/20'"
                                            required>
                                            <option value="">Pilih Supplier</option>
                                            <option value="Cutpack" {{ old('supplier') == 'Cutpack' ? 'selected' : '' }}>
                                                Cutpack</option>
                                            <option value="Rikyet" {{ old('supplier') == 'Rikyet' ? 'selected' : '' }}>
                                                Rikyet</option>
                                        </select>
                                    </div>

                                    <div class="space-y-1 relative">
                                        <label for="batch"
                                            class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest ml-1 flex items-center gap-1">
                                            Batch
                                            @if($isLocked)
                                                <svg class="w-3 h-3 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            @endif
                                        </label>
                                        <input id="batch" name="batch" type="text" maxlength="7"
                                            class="block w-full py-2 px-3 font-mono uppercase border-gray-200 rounded-lg bg-white/50 backdrop-blur-sm shadow-sm transition-all duration-300 focus:ring-4 tracking-tighter placeholder-gray-300 font-bold text-xs {{ $isLocked ? 'bg-gray-100/80 opacity-60 cursor-not-allowed' : '' }}"
                                            :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring.replace('focus:', '')) : 'focus:border-indigo-500 focus:ring-indigo-500/20'"
                                            value="{{ old('batch', request('batch')) }}" placeholder="0000000" {{ $isLocked ? 'readonly' : '' }} required />
                                    </div>

                                    <div class="space-y-1 relative">
                                        <label for="seri"
                                            class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest ml-1 flex items-center gap-1">
                                            Seri
                                            @if($isLocked)
                                                <svg class="w-3 h-3 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            @endif
                                        </label>
                                        <input id="seri" name="seri" type="text"
                                            class="block w-full py-2 px-3 font-mono uppercase border-gray-200 rounded-lg bg-white/50 backdrop-blur-sm shadow-sm transition-all duration-300 focus:ring-4 tracking-widest placeholder-gray-300 font-bold text-xs {{ $isLocked ? 'bg-gray-100/80 opacity-60 cursor-not-allowed' : '' }}"
                                            :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring.replace('focus:', '')) : 'focus:border-indigo-500 focus:ring-indigo-500/20'"
                                            placeholder="Seri" value="{{ old('seri', request('seri')) }}" {{ $isLocked ? 'readonly' : '' }} required />
                                    </div>

                                    <div class="space-y-1 relative">
                                        <label for="emisi"
                                            class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest ml-1 flex items-center gap-1">
                                            Emisi (Tahun)
                                            @if($isLocked)
                                                <svg class="w-3 h-3 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            @endif
                                        </label>
                                        <input id="emisi" name="emisi" type="number" min="2000" max="2100"
                                            class="block w-full py-2 px-3 border-gray-200 rounded-lg bg-white/50 backdrop-blur-sm shadow-sm transition-all duration-300 focus:ring-4 placeholder-gray-300 font-bold text-xs {{ $isLocked ? 'bg-gray-100/80 opacity-60 cursor-not-allowed' : '' }}"
                                            :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring.replace('focus:', '')) : 'focus:border-indigo-500 focus:ring-indigo-500/20'"
                                            value="{{ old('emisi', request('emisi', $lastReceiving->emisi ?? '2022')) }}"
                                            {{ $isLocked ? 'readonly' : '' }} required />
                                    </div>

                                    <div class="space-y-1 relative">
                                        <label for="tahun_anggaran"
                                            class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest ml-1 flex items-center gap-1">
                                            Tahun Anggaran
                                            @if($isLocked)
                                                <svg class="w-3 h-3 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            @endif
                                        </label>
                                        <select id="tahun_anggaran" name="tahun_anggaran"
                                            class="block w-full py-2 px-3 border-gray-200 rounded-lg bg-white/50 backdrop-blur-sm shadow-sm transition-all duration-300 focus:ring-4 font-bold text-xs {{ $isLocked ? 'bg-gray-100/80 pointer-events-none opacity-60' : '' }}"
                                            :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring.replace('focus:', '')) : 'focus:border-indigo-500 focus:ring-indigo-500/20'"
                                            {{ $isLocked ? 'tabindex="-1"' : '' }} required>
                                            @foreach(['2024', '2025', '2026', '2027'] as $year)
                                                <option value="{{ $year }}" {{ old('tahun_anggaran', request('tahun_anggaran', $lastReceiving->tahun_anggaran ?? '2025')) == $year ? 'selected' : '' }}>{{ $year }}</option>
                                            @endforeach
                                        </select>
                                        @if($isLocked) <input type="hidden" name="tahun_anggaran"
                                            value="{{ request('tahun_anggaran', $lastReceiving->tahun_anggaran ?? '2025') }}">
                                        @endif
                                    </div>

                                    <div class="col-span-1 sm:col-span-2 mt-2">
                                        <div
                                            class="p-4 rounded-2xl border border-gray-100 bg-white/80 shadow-inner flex flex-col sm:flex-row items-center justify-between gap-4 overflow-hidden relative group">
                                            <div
                                                class="absolute -right-4 -top-4 w-20 h-20 bg-gray-50 rounded-full blur-2xl transition-all duration-700 group-hover:bg-indigo-50">
                                            </div>

                                            <div class="flex flex-col gap-2 relative"
                                                x-data="{ isManual: {{ old('is_manual') ? 'true' : 'false' }} }">
                                                <!-- Slider Pengalih untuk Manual -->
                                                <div
                                                    class="flex items-center justify-between bg-white/50 border border-gray-200 p-1.5 rounded-lg shadow-sm hover:shadow-md transition-all duration-300 min-w-[250px]">
                                                    <div class="flex items-center mr-3">
                                                        <span
                                                            class="text-[9px] font-black transition-colors duration-300"
                                                            :class="isManual ? 'text-red-600' : 'text-gray-500'">
                                                            Pack Tidak Full ( < 45.000 ) </span>
                                                    </div>
                                                    <label
                                                        class="relative inline-flex items-center cursor-pointer scale-90">
                                                        <input type="checkbox" id="is_manual" name="is_manual" value="1"
                                                            x-model="isManual" @change="handleToggleManual()"
                                                            class="sr-only peer" {{ old('is_manual') ? 'checked' : '' }}>
                                                        <div
                                                            class="w-10 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-amber-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-red-500">
                                                        </div>
                                                    </label>
                                                </div>

                                                <div class="flex items-center relative gap-3 ml-1">
                                                    <div class="relative inline-flex items-center cursor-pointer group">
                                                        <input id="repass" name="repass" value="repass" type="checkbox"
                                                            class="w-4 h-4 rounded-md border-gray-300 shadow-sm transition-all duration-300 text-indigo-600 focus:ring-indigo-500"
                                                            {{ old('repass') ? 'checked' : '' }}>
                                                        <label for="repass"
                                                            class="ml-2 text-[10px] font-bold text-gray-600 cursor-pointer">Repass</label>
                                                    </div>
                                                    <div class="h-6 w-[1px] bg-gray-100 hidden sm:block"></div>
                                                    <div
                                                        class="text-[9px] font-black uppercase tracking-widest text-gray-400 leading-tight">
                                                        Total Pack: <span id="packs_needed_display"
                                                            class="text-lg transition-colors duration-500 ml-1"
                                                            :class="currentTheme ? currentTheme.icon : 'text-indigo-600'">0</span>
                                                    </div>
                                                </div>

                                                <div class="pt-1">
                                                    <button type="submit"
                                                        class="w-full sm:w-auto flex justify-center items-center py-2.5 px-8 border border-transparent shadow-lg text-[10px] font-black rounded-lg transition-all duration-300 uppercase tracking-[0.2em] relative overflow-hidden group min-w-[180px] hover:scale-[1.02] active:scale-[0.98] hover:shadow-xl"
                                                        :class="currentTheme ? (currentTheme.btn + ' ' + currentTheme.text) : 'bg-indigo-600 text-white'">
                                                        <span class="relative z-10">Simpan Penerimaan</span>
                                                        <svg class="relative z-10 ml-2 w-3 h-3 transform group-hover:translate-x-1 transition-transform"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                             <!-- Kanan: Grid Pack -->
                            <div class="w-full lg:w-[60%] p-3 lg:p-4 bg-gray-50/20 backdrop-blur-sm h-full overflow-y-auto custom-scrollbar flex flex-col">
                                <div class="mb-2">
                                    <h3 class="text-base font-black text-gray-900 tracking-tight flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                        </svg>
                                        Tabel Penerimaan HCS
                                    </h3>
                                </div>

                                 <!-- Kartu Ringkasan -->
                                <div class="grid grid-cols-2 gap-2 mb-3">
                                    <div
                                        class="bg-white p-2.5 rounded-xl border border-gray-100 shadow-sm transition-all duration-500 hover:shadow-md">
                                        <span
                                            class="text-[7px] font-black text-gray-400 uppercase tracking-widest block mb-0.5">Pack
                                            Dipilih</span>
                                        <div class="flex items-end gap-1">
                                            <span id="selected_packs_length"
                                                class="text-base font-black leading-none transition-colors duration-500"
                                                :class="currentTheme ? currentTheme.icon : 'text-indigo-600'">0</span>
                                            <span
                                                class="text-[8px] font-bold text-gray-300 mb-0.5 uppercase tracking-tight">Pack</span>
                                        </div>
                                    </div>
                                    <div class="bg-white p-2.5 rounded-xl border border-gray-100 shadow-sm">
                                        <span
                                            class="text-[7px] font-black text-gray-400 uppercase tracking-widest block mb-0.5">Dibutuhkan</span>
                                        <div class="flex items-end gap-1">
                                            <span id="packs_needed_length"
                                                class="text-base font-black leading-none text-gray-900">0</span>
                                            <span
                                                class="text-[8px] font-bold text-gray-300 mb-0.5 uppercase tracking-tight">Pack</span>
                                        </div>
                                    </div>
                                </div>

                                 <!-- Keterangan -->
                                <div class="bg-white/40 p-1.5 rounded-xl border border-white mb-0.5">
                                    <div class="flex flex-wrap gap-x-3 gap-y-1">
                                        <div class="flex items-center gap-1">
                                            <div class="w-2.5 h-2.5 bg-white border border-gray-200 rounded shadow-sm">
                                            </div> <span class="text-[8px] font-bold text-gray-500">Kosong</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <div class="flex -space-x-0.5">
                                                <div class="w-2.5 h-2.5 bg-blue-700 rounded shadow-sm"></div>
                                                <div class="w-2.5 h-2.5 bg-green-700 rounded shadow-sm"></div>
                                            </div>
                                            <span class="text-[8px] font-bold text-gray-500 ml-0.5">Terisi</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <div class="w-2.5 h-2.5 bg-red-600 border border-red-700 rounded shadow-sm">
                                            </div> <span class="text-[8px] font-bold text-gray-500">Sorted</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <div class="flex -space-x-0.5">
                                                <div class="w-2.5 h-2.5 bg-blue-400 rounded">
                                                </div>
                                                <div class="w-2.5 h-2.5 bg-green-400 rounded">
                                                </div>
                                            </div>
                                            <span class="text-[8px] font-bold text-gray-500 ml-0.5">Sesi Ini</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Input Tersembunyi untuk mengirim array -->
                                <div id="hidden_packs_container"></div>

                                <!-- Kontainer Grid dengan Tooltip Kaya -->
                                <div class="relative group/grid flex-grow mt-3">
                                    <div class="grid grid-rows-10 grid-flow-col gap-1 sm:gap-1 relative" id="pack_grid">
                                        @for ($i = 1; $i <= 100; $i++)
                                            @php
                                                $row = ($i - 1) % 10;
                                                $col = floor(($i - 1) / 10);

                                                // Logika posisi tooltip agar tidak terpotong layar
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
                                                    class="pack-btn w-full aspect-square flex items-center justify-center text-[9px] font-black rounded-lg transition-all duration-300 hover:scale-110 hover:z-10 focus:outline-none focus:ring-2"
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

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const inputJumlahDisplay = document.getElementById('jumlah_display');
                const inputJumlahOriginal = document.getElementById('jumlah_original');
                const toggleManual = document.getElementById('is_manual');
                const spanPacksNeededDisplay = document.getElementById('packs_needed_display');
                const spanSelectedPacksLength = document.getElementById('selected_packs_length');
                const spanPacksNeededLength = document.getElementById('packs_needed_length');

                const selectSupplier = document.getElementById('supplier');
                const inputBatch = document.getElementById('batch');
                const inputSeri = document.getElementById('seri');
                const gridContainer = document.getElementById('pack_grid');
                const hiddenPacksContainer = document.getElementById('hidden_packs_container');
                const form = document.getElementById('hcs-form');

                // Cache tombol grid supaya tidak boros query DOM
                const gridButtons = Array.from(gridContainer.querySelectorAll('.pack-btn'));

                const selectPecahan = document.getElementById('pecahan');
                const inputEmisi = document.getElementById('emisi');
                const selectTA = document.getElementById('tahun_anggaran');

                let jumlahOriginal = parseInt(inputJumlahOriginal.value, 10) || 0;
                let packsNeeded = 0;
                let selectedPacks = {!! json_encode(array_map('intval', old('packs', []))) !!} || [];
                let usedPacks = []; // array dari { pack_number, supplier, hcs_sorting_id, nomor_bon }

                // Fungsi pembantu untuk membatasi eksekusi fungsi (debounce)
                function debounce(func, wait) {
                    let timeout;
                    return function (...args) {
                        clearTimeout(timeout);
                        timeout = setTimeout(() => func.apply(this, args), wait);
                    };
                }
                function init() {
                    if (jumlahOriginal > 0) {
                        inputJumlahDisplay.value = jumlahOriginal.toLocaleString('id-ID');
                    }
                    updateCalculations(jumlahOriginal);

                    if (inputBatch.value.length === 7 && inputSeri.value.length > 0) {
                        fetchUsedPacks();
                    }
                    renderGrid();
                }

                function handleToggleManual() {
                    const isChecked = toggleManual.checked;
                    if (isChecked) {
                        if (jumlahOriginal > 45000) {
                            Swal.fire({
                                title: 'Batas Terlampaui',
                                text: 'Untuk pack tidak full, jumlah bilyet tidak boleh melebihi 45.000.',
                                icon: 'warning',
                                confirmButtonColor: '#4f46e5'
                            });
                            jumlahOriginal = 45000;
                            inputJumlahOriginal.value = 45000;
                            inputJumlahDisplay.value = jumlahOriginal.toLocaleString('id-ID');
                        }
                        if (selectedPacks.length > 1) {
                            selectedPacks = selectedPacks.slice(0, 1);
                        }
                    }
                    updateCalculations(jumlahOriginal);
                    renderGrid();
                }

                // Debounce untuk pengambilan data batch (biar tidak lag pas ngetik)
                const debouncedFetchUsedPacks = debounce(fetchUsedPacks, 300);
                const debouncedRenderGrid = debounce(renderGrid, 50);

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
                    debouncedRenderGrid(); // Tunda render grid sedikit agar lebih mulus
                }

                function updateCalculations(numVal) {
                    if (toggleManual.checked) {
                        packsNeeded = numVal > 0 ? 1 : 0;
                    } else {
                        packsNeeded = Math.floor(numVal / 45000) || 0;
                    }

                    spanPacksNeededDisplay.textContent = packsNeeded;
                    spanPacksNeededLength.textContent = packsNeeded;

                    if (selectedPacks.length > packsNeeded) {
                        selectedPacks = selectedPacks.slice(0, packsNeeded);
                    }
                }

                function fetchUsedPacks() {
                    const batchVal = inputBatch.value.toUpperCase();
                    const seriVal = inputSeri.value.toUpperCase();
                    const pecahanVal = selectPecahan.value;
                    const emisiVal = inputEmisi.value;
                    const taVal = selectTA.value;

                    if (batchVal.length === 7 && seriVal.length > 0) {
                        let url = `/api/packs/used?batch=${batchVal}&seri=${seriVal}`;
                        if (pecahanVal) url += `&pecahan=${pecahanVal}`;
                        if (emisiVal) url += `&emisi=${emisiVal}`;
                        if (taVal) url += `&tahun_anggaran=${taVal}`;

                        fetch(url)
                            .then(res => res.json())
                            .then(data => {
                                usedPacks = data.map(p => ({
                                    pack_number: parseInt(p.pack_number, 10),
                                    supplier: p.supplier,
                                    hcs_sorting_id: p.hcs_sorting_id,
                                    nomor_bon: (p.hcs_receiving && p.hcs_receiving.nomor_bon) ? p.hcs_receiving.nomor_bon : '-'
                                }));
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
                    // Pakai rAF agar rendering di browser lebih smooth
                    requestAnimationFrame(() => {
                        // Update input tersembunyi
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
                        const isDarkMode = document.body.classList.contains('dark-mode');

                        // Update class tombol dan tooltip menggunakan tombol yang sudah di-cache
                        gridButtons.forEach(btn => {
                            const num = parseInt(btn.getAttribute('data-pack'), 10);
                            const group = btn.closest('.group');
                            const tooltip = group.querySelector('.pack-tooltip');
                            const badge = tooltip.querySelector('.tooltip-badge');
                            const supplierEl = tooltip.querySelector('.tooltip-supplier');
                            const statusEl = tooltip.querySelector('.tooltip-status');

                            // Reset status tooltip
                            badge.classList.add('hidden');
                            supplierEl.className = 'tooltip-supplier font-bold uppercase tracking-tighter text-indigo-300';
                            supplierEl.textContent = 'KOSONG';
                            statusEl.textContent = 'Bisa Dipilih';

                            // Reset ke style dasar tanpa warna latar atau teks
                            const baseClasses = 'pack-btn w-full aspect-square flex items-center justify-center text-[10px] sm:text-xs font-black rounded-lg transition-all duration-300 focus:outline-none focus:ring-4';
                            btn.className = baseClasses;

                            const usedPack = usedPacks.find(p => p.pack_number === num);
                            if (usedPack) {
                                supplierEl.textContent = usedPack.supplier || 'HCS';
                                supplierEl.className = 'tooltip-supplier font-bold uppercase tracking-tighter ' + (usedPack.supplier === 'Cutpack' ? 'text-blue-300' : 'text-green-300');

                                btn.classList.add('cursor-not-allowed', 'shadow-none', 'text-white');
                                if (usedPack.hcs_sorting_id) {
                                    statusEl.textContent = 'Sudah Disortir (Record Lain)';
                                    badge.textContent = 'TERSORTIR';
                                    badge.className = 'tooltip-badge px-2 py-0.5 rounded-full text-[8px] text-white bg-red-500';
                                    badge.classList.remove('hidden');
                                    // Merah - Sudah Tersortir (Solid)
                                    btn.classList.add('bg-red-600', 'border-red-700', 'shadow-lg');
                                } else {
                                    statusEl.textContent = 'Terpakai (Record Lain)';
                                    badge.textContent = 'TERPAKAI';
                                    badge.className = 'tooltip-badge px-2 py-0.5 rounded-full text-[8px] text-white ' + (usedPack.supplier === 'Cutpack' ? 'bg-blue-600' : 'bg-green-600');
                                    badge.classList.remove('hidden');

                                    // Warna solid untuk input terdahulu (Record Lain)
                                    if (usedPack.supplier === 'Cutpack') {
                                        btn.classList.add('bg-blue-700', 'border-blue-800', 'shadow-md');
                                    } else if (usedPack.supplier === 'Rikyet') {
                                        btn.classList.add('bg-green-700', 'border-green-800', 'shadow-md');
                                    } else {
                                        btn.classList.add('bg-gray-600', 'border-gray-700');
                                    }
                                }
                            } else if (selectedPacks.includes(num)) {
                                // Sedang Dipilih (Warna cerah untuk membedakan dengan data lama)
                                supplierEl.textContent = currentSupplier || 'HCS';
                                supplierEl.className = 'tooltip-supplier font-bold uppercase tracking-tighter ' + (currentSupplier === 'Cutpack' ? 'text-blue-200' : (currentSupplier === 'Rikyet' ? 'text-green-200' : 'text-indigo-200'));
                                statusEl.textContent = 'Dipilih (Penerimaan)';
                                badge.textContent = 'DIPILIH';
                                badge.className = 'tooltip-badge px-2 py-0.5 rounded-full text-[8px] text-white bg-indigo-500';
                                badge.classList.remove('hidden');

                                btn.classList.add('shadow-xl', 'text-white', 'scale-105', 'z-10');
                                if (currentSupplier === 'Cutpack') {
                                    btn.classList.add('bg-sky-450', 'bg-blue-400', 'border-blue-500', 'shadow-blue-300/50');
                                } else if (currentSupplier === 'Rikyet') {
                                    btn.classList.add('bg-emerald-400', 'border-emerald-500', 'shadow-emerald-300/50');
                                } else {
                                    btn.classList.add('bg-indigo-500', 'border-indigo-600', 'shadow-indigo-300/50');
                                }
                            } else {
                                // TERSEDIA - Pakai class CSS supaya otomatis switch tema
                                btn.classList.add('pack-btn-available');
                            }
                        });
                    });
                }

                // Pasang Event Listener
                inputJumlahDisplay.addEventListener('input', handleJumlahInput);
                toggleManual.addEventListener('change', handleToggleManual);

                [inputBatch, selectPecahan, inputEmisi, selectTA].forEach(el => {
                    el.addEventListener('input', () => {
                        if (el === inputBatch) inputBatch.value = inputBatch.value.toUpperCase();
                        debouncedFetchUsedPacks();
                    });
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
                            text: `Jumlah packs yang dipilih (${selectedPacks.length}) tidak sesuai kebutuhan. Dibutuhkan ${packsNeeded} pack(s).`,
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
                });

                window.addEventListener('theme-changed', renderGrid);

                init();
            });
        </script>
    @endpush

    @push('css')
        <style>
            @keyframes shine {
                0% {
                    transform: translateX(-100%) skewX(-15deg);
                }

                100% {
                    transform: translateX(200%) skewX(-15deg);
                }
            }

            /* Mengembalikan Estetika Mode Gelap High-Fidelity untuk Halaman Tambah */
            body.dark-mode [class*="bg-gray-50/30"] {
                background-color: var(--theme-bg-main) !important;
            }

            body.dark-mode [class*="bg-white/70"] {
                background-color: rgba(30, 41, 59, 0.7) !important;
                border-color: var(--theme-border-main) !important;
                backdrop-blur: 40px !important;
            }

            body.dark-mode .lg\:w-\[45\%\] {
                background-color: rgba(15, 23, 42, 0.4) !important;
                border-right-color: var(--theme-border-main) !important;
            }

            body.dark-mode .lg\:w-\[55\%\] {
                background-color: rgba(15, 23, 42, 0.2) !important;
            }

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

            body.dark-mode .text-gray-900,
            body.dark-mode .text-gray-800 {
                color: var(--theme-text-main) !important;
            }

            body.dark-mode .text-gray-600,
            body.dark-mode .text-gray-700 {
                color: var(--theme-text-muted) !important;
            }

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

            /* Pemurnian Mode Terang untuk Halaman Tambah */
            body.light-mode .bg-white\/70.backdrop-blur-xl {
                background-color: #ffffff !important;
                border-color: #e5e7eb !important;
            }

            body.light-mode .lg\:w-\[45\%\]\.bg-white\/40 {
                background-color: #f8fafc !important;
                border-right-color: #e5e7eb !important;
            }

            body.light-mode .lg\:w-\[55\%\]\.bg-gray-50\/20 {
                background-color: #ffffff !important;
            }

            body.light-mode input,
            body.light-mode select,
            body.light-mode textarea {
                background-color: #ffffff !important;
                border-color: #d1d5db !important;
            }

            body.light-mode .bg-white\/50 {
                background-color: #ffffff !important;
                border-color: #d1d5db !important;
            }

            body.light-mode .bg-white\/80 {
                background-color: #ffffff !important;
                border-color: #e5e7eb !important;
            }

            body.light-mode .bg-white\/40 {
                background-color: #f1f5f9 !important;
                border-color: #e5e7eb !important;
            }
        </style>
    @endpush
</x-app-layout>