<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Proses Penyortiran HCS') }} - {{ $batch }} / {{ $seri }} / {{ $pecahan }}
        </h2>
    </x-slot>

    @php
        $themeClasses = [
            'S' => ['bg' => 'bg-lime-500', 'border' => 'border-lime-500', 'ring' => 'focus:ring-lime-500', 'focus' => 'focus:border-lime-500', 'btn' => 'bg-lime-500', 'text' => 'text-gray-900'],
            'T' => ['bg' => 'bg-gray-400', 'border' => 'border-gray-400', 'ring' => 'focus:ring-gray-400', 'focus' => 'focus:border-gray-400', 'btn' => 'bg-gray-400', 'text' => 'text-white'],
            'U' => ['bg' => 'bg-amber-400', 'border' => 'border-amber-400', 'ring' => 'focus:ring-amber-400', 'focus' => 'focus:border-amber-400', 'btn' => 'bg-amber-400', 'text' => 'text-gray-900'],
            'V' => ['bg' => 'bg-purple-500', 'border' => 'border-purple-500', 'ring' => 'focus:ring-purple-500', 'focus' => 'focus:border-purple-500', 'btn' => 'bg-purple-500', 'text' => 'text-white'],
            'W' => ['bg' => 'bg-green-500', 'border' => 'border-green-500', 'ring' => 'focus:ring-green-500', 'focus' => 'focus:border-green-500', 'btn' => 'bg-green-500', 'text' => 'text-white'],
            'X' => ['bg' => 'bg-blue-500', 'border' => 'border-blue-500', 'ring' => 'focus:ring-blue-500', 'focus' => 'focus:border-blue-500', 'btn' => 'bg-blue-500', 'text' => 'text-white'],
            'Y' => ['bg' => 'bg-red-500', 'border' => 'border-red-500', 'ring' => 'focus:ring-red-500', 'focus' => 'focus:border-red-500', 'btn' => 'bg-red-500', 'text' => 'text-white'],
        ];
    @endphp

    <div class="py-6 min-h-screen bg-gray-50 transition-colors duration-500" x-data="sortingGrid('{{ $pecahan }}', {{ json_encode($themeClasses) }})">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-400 text-red-700 px-4 py-3 rounded shadow-sm" role="alert">
                    <strong class="font-bold flex items-center mb-1"><svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg> Terjadi Kesalahan!</strong>
                    <ul class="mt-1 list-disc list-inside text-xs font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-400 text-red-700 px-4 py-3 rounded shadow-sm" role="alert">
                    <span class="block sm:inline text-sm font-medium">{{ session('error') }}</span>
                </div>
            @endif

            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Left Column: Grid Selection -->
                <div class="flex-1 bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-500 sm:rounded-xl border-t-4"
                     :class="currentTheme ? currentTheme.border : 'border-gray-100'">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center uppercase tracking-wider">
                            <div class="w-2 h-6 mr-3 rounded-full transition-all duration-500" :class="currentTheme ? currentTheme.bg : 'bg-gray-400'"></div>
                            Pilih Pack untuk Disortir
                        </h3>
                        
                        <!-- Legend -->
                        <div class="flex flex-wrap gap-4 mb-8 text-[10px] font-bold tracking-widest text-gray-500 bg-gray-50/50 p-4 rounded-lg border border-gray-100">
                            <div class="flex items-center"><div class="w-3 h-3 rounded-sm bg-blue-500 mr-2 shadow-sm"></div>Rikyet Siap Sortir</div>
                            <div class="flex items-center"><div class="w-3 h-3 rounded-sm bg-green-500 mr-2 shadow-sm"></div>Cutpack Siap Sortir</div>
                            <div class="flex items-center"><div class="w-3 h-3 rounded-sm bg-blue-200 border border-blue-400 mr-2" style="background-image: repeating-linear-gradient(45deg, rgba(0,0,0,0.1), rgba(0,0,0,0.1) 3px, transparent 3px, transparent 6px);"></div>Rikyet Tersortir</div>
                            <div class="flex items-center"><div class="w-3 h-3 rounded-sm bg-green-200 border border-green-400 mr-2" style="background-image: repeating-linear-gradient(-45deg, rgba(0,0,0,0.1), rgba(0,0,0,0.1) 3px, transparent 3px, transparent 6px);"></div>Cutpack Tersortir</div>
                            <div class="flex items-center"><div class="w-3 h-3 rounded-sm bg-gray-100 border border-gray-200 mr-2"></div>Kosong</div>
                        </div>

                        <!-- 10x10 Grid -->
                        <div class="grid grid-flow-col gap-2 mb-8" style="grid-template-rows: repeat(10, minmax(0, 1fr));" @mouseleave="isDragging = false">
                            @for ($i = 1; $i <= 100; $i++)
                                @php
                                    $pack = $packsData->get($i);
                                    $status = 'empty'; // default
                                    $statusClass = 'bg-gray-50 border-gray-100 text-gray-300 cursor-not-allowed opacity-50';
                                    $isReady = false;

                                    if ($pack) {
                                        $isSorted = !is_null($pack->hcs_sorting_id);
                                        $supplier = strtolower($pack->pack_supplier);

                                        if ($isSorted) {
                                            $status = 'sorted';
                                            if (str_contains($supplier, 'rikyet')) {
                                                $statusClass = 'bg-blue-100 text-blue-900 cursor-not-allowed border-blue-200 shadow-inner opacity-60';
                                                $hatchStyle = "background-image: repeating-linear-gradient(45deg, rgba(0,0,0,0.05), rgba(0,0,0,0.05) 4px, transparent 4px, transparent 8px);";
                                            } else {
                                                $statusClass = 'bg-green-100 text-green-900 cursor-not-allowed border-green-200 shadow-inner opacity-60';
                                                $hatchStyle = "background-image: repeating-linear-gradient(-45deg, rgba(0,0,0,0.05), rgba(0,0,0,0.05) 4px, transparent 4px, transparent 8px);";
                                            }
                                            $statusClass .= '" style="' . $hatchStyle; 
                                        } else {
                                            $status = 'ready';
                                            $isReady = true;
                                            if (str_contains($supplier, 'rikyet')) {
                                                $statusClass = 'bg-blue-500 text-white hover:bg-blue-600 cursor-pointer shadow-sm hover:scale-105 transform transition-all';
                                            } else {
                                                $statusClass = 'bg-green-500 text-white hover:bg-green-600 cursor-pointer shadow-sm hover:scale-105 transform transition-all';
                                            }
                                        }
                                    }
                                @endphp

                                <div 
                                    class="h-10 w-full flex items-center justify-center rounded-md text-sm font-black border select-none transition-all duration-200
                                           {{ $statusClass }}"
                                    :class="{
                                        'ring-4 scale-110 z-10 shadow-xl brightness-125': isSelected({{ $i }}),
                                        'ring-yellow-400': isSelected({{ $i }}) && !currentTheme,
                                        [currentTheme ? currentTheme.ring.replace('focus:', '') : '']: isSelected({{ $i }})
                                    }"
                                    title="Pack {{ $i }} {{ $pack ? '- ' . $pack->pack_supplier : '(Kosong)' }}"
                                    @if($isReady)
                                        @mousedown="startSelection({{ $i }})"
                                        @mouseenter="onHover({{ $i }})"
                                        @mouseup="endSelection()"
                                    @endif
                                >
                                    {{ $i }}
                                </div>
                            @endfor
                        </div>
                        
                        <!-- Validation Message -->
                        <div x-show="validationError" x-cloak 
                             class="text-red-700 text-xs font-bold mt-4 p-4 bg-red-50 rounded-lg border border-red-100 flex items-center space-x-2 animate-pulse" 
                             x-text="validationError"></div>
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-4 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                            Klik & Geser untuk memilih Pack (Harus berurutan & kelipatan 4).
                        </div>
                    </div>
                </div>

                <!-- Right Column: Form -->
                <div class="w-full lg:w-1/3 bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-500 sm:rounded-xl border-t-4 h-fit"
                     :class="currentTheme ? currentTheme.border : 'border-gray-100'">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center uppercase tracking-wider">
                            <div class="w-2 h-6 mr-3 rounded-full transition-all duration-500 shadow-[0_0_10px_rgba(0,0,0,0.1)]" :class="currentTheme ? currentTheme.bg : 'bg-gray-400'"></div>
                            Detail Penyortiran
                        </h3>
                        
                        <form method="POST" action="{{ route('hcs-sorting.store') }}" id="sortingForm" @submit="validateSubmission">
                            @csrf
                            <input type="hidden" name="pecahan" value="{{ $pecahan }}">
                            <input type="hidden" name="batch" value="{{ $batch }}">
                            <input type="hidden" name="seri" value="{{ $seri }}">
                            <input type="hidden" name="emisi" value="{{ $emisi }}">
                            <input type="hidden" name="tahun_anggaran" value="{{ $tahun_anggaran }}">
                            
                            <!-- Hidden inputs for selected packs -->
                            <template x-for="pack in selectedPacks" :key="pack">
                                <input type="hidden" name="selected_packs[]" :value="pack">
                            </template>

                            <div class="space-y-6">
                                <!-- Manual Toggle Slider -->
                                <div class="flex items-center justify-between p-4 bg-white/40 backdrop-blur-md rounded-2xl border border-white/60 shadow-sm transition-all duration-300" 
                                     :class="isManual ? 'ring-2 ring-red-500/20 border-red-100 bg-red-50/50' : ''">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center mr-3 transition-all duration-300 shadow-sm"
                                             :class="isManual ? 'bg-red-500 text-white shadow-lg shadow-red-500/30' : 'bg-gray-100 text-gray-400'">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[10px] font-black uppercase tracking-[0.15em] transition-colors duration-300"
                                                  :class="isManual ? 'text-red-600' : 'text-gray-500'">
                                                Mode Sisa Pack
                                            </span>
                                            <span class="text-[8px] font-bold text-gray-400 uppercase tracking-widest leading-tight">Gunakan jika < 4 Pack</span>
                                        </div>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer scale-110">
                                        <input type="checkbox" id="is_manual" name="is_manual" class="sr-only peer" x-model="isManual" @change="validateSelection()">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-500 shadow-inner"></div>
                                    </label>
                                </div>

                                <!-- Form Fields -->
                                <div class="grid grid-cols-1 gap-5">
                                    <!-- Total Summary Fields (Stacked to avoid truncation) -->
                                    <div>
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1.5 px-1">Total Pack</label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                            </div>
                                            <input type="text" :value="selectedPacks.length" 
                                                   class="block w-full border-gray-200 rounded-xl bg-gray-50 shadow-inner text-sm py-3 px-10 font-bold text-center opacity-80"
                                                   readonly />
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1.5 px-1">Total Bilyet</label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M12 16V7"/></svg>
                                            </div>
                                            <input type="text" :value="formatNumber(totalBilyet)" 
                                                   class="block w-full border-gray-200 rounded-xl bg-gray-50 shadow-inner text-sm py-3 px-10 font-bold text-center opacity-80"
                                                   readonly />
                                        </div>
                                    </div>

                                    <div>
                                        <label for="supplier" class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1.5 px-1">Supplier (Hasil Sortir)</label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none transition-colors duration-300"
                                                 :class="currentTheme ? currentTheme.text.replace('text-', 'text-opacity-40 text-') : 'text-gray-300 group-focus-within:text-indigo-500'">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                            </div>
                                            <select id="supplier" name="supplier" 
                                                    class="block w-full border-gray-200 rounded-xl bg-white/50 backdrop-blur-sm shadow-sm text-sm py-3 px-10 transition-all duration-300 focus:ring-4 font-bold text-center appearance-none"
                                                    :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring.replace('focus:', '')) : 'focus:border-indigo-500 focus:ring-indigo-500/20'" 
                                                    required>
                                                <option value="">Pilih Supplier</option>
                                                <option value="Cutpack" {{ old('supplier') == 'Cutpack' ? 'selected' : '' }}>Cutpack</option>
                                                <option value="Rikyet" {{ old('supplier') == 'Rikyet' ? 'selected' : '' }}>Rikyet</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1.5 px-1">Emisi / Tahun Anggaran</label>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                                </div>
                                                <input type="text" 
                                                       class="block w-full border-gray-200 rounded-xl bg-gray-100/50 shadow-inner text-sm py-3 px-10 font-bold text-center opacity-70"
                                                       value="{{ $emisi }}" readonly />
                                            </div>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                </div>
                                                <input type="text" 
                                                       class="block w-full border-gray-200 rounded-xl bg-gray-100/50 shadow-inner text-sm py-3 px-10 font-bold text-center opacity-70"
                                                       value="{{ $tahun_anggaran }}" readonly />
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label for="petugas_1" class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1.5 px-1">Petugas 1</label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none transition-colors duration-300"
                                                 :class="currentTheme ? currentTheme.text.replace('text-', 'text-opacity-40 text-') : 'text-gray-300 group-focus-within:text-indigo-500'">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            </div>
                                            <input id="petugas_1" name="petugas_1" type="text" 
                                                   class="block w-full border-gray-200 rounded-xl bg-white/50 backdrop-blur-sm shadow-sm text-sm py-3 px-10 transition-all duration-300 focus:ring-4 font-bold text-center"
                                                   :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring.replace('focus:', '')) : 'focus:border-indigo-500 focus:ring-indigo-500/20'"
                                                   value="{{ old('petugas_1') }}" placeholder="Input Nama Petugas" required />
                                        </div>
                                    </div>

                                    <div>
                                        <label for="petugas_2" class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1.5 px-1">Petugas 2 (Opsional)</label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none transition-colors duration-300"
                                                 :class="currentTheme ? currentTheme.text.replace('text-', 'text-opacity-40 text-') : 'text-gray-300 group-focus-within:text-indigo-500'">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                            </div>
                                            <input id="petugas_2" name="petugas_2" type="text"
                                                   class="block w-full border-gray-200 rounded-xl bg-white/50 backdrop-blur-sm shadow-sm text-sm py-3 px-10 transition-all duration-300 focus:ring-4 font-bold text-center"
                                                   :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring.replace('focus:', '')) : 'focus:border-indigo-500 focus:ring-indigo-500/20'"
                                                   value="{{ old('petugas_2') }}" placeholder="Petugas Pendamping" />
                                        </div>
                                    </div>

                                    <!-- Date and Shift (Stacked to avoid truncation) -->
                                    <div>
                                        <label for="tanggal" class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1.5 px-1">Tanggal</label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none transition-colors duration-300"
                                                 :class="currentTheme ? currentTheme.text.replace('text-', 'text-opacity-40 text-') : 'text-gray-300 group-focus-within:text-indigo-500'">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                            <input id="tanggal" name="tanggal" type="date"
                                                   class="block w-full border-gray-200 rounded-xl bg-white/50 backdrop-blur-sm shadow-sm text-sm py-3 px-10 font-bold text-center transition-all duration-300 focus:ring-4"
                                                   :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring.replace('focus:', '')) : 'focus:border-indigo-500 focus:ring-indigo-500/20'"
                                                   value="{{ old('tanggal', date('Y-m-d')) }}" required />
                                        </div>
                                    </div>

                                    <div>
                                        <label for="gilir" class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1.5 px-1">Gilir</label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none transition-colors duration-300"
                                                 :class="currentTheme ? currentTheme.text.replace('text-', 'text-opacity-40 text-') : 'text-gray-300 group-focus-within:text-indigo-500'">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            </div>
                                            <select id="gilir" name="gilir" 
                                                    class="block w-full border-gray-200 rounded-xl bg-white/50 backdrop-blur-sm shadow-sm text-sm py-3 px-10 transition-all duration-300 focus:ring-4 font-bold text-center appearance-none"
                                                    :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring.replace('focus:', '')) : 'focus:border-indigo-500 focus:ring-indigo-500/20'"
                                                    required>
                                                <option value="">Gilir</option>
                                                <option value="Gilir 1" {{ old('gilir') == 'Gilir 1' ? 'selected' : '' }}>1</option>
                                                <option value="Gilir 2" {{ old('gilir') == 'Gilir 2' ? 'selected' : '' }}>2</option>
                                                <option value="Gilir 3" {{ old('gilir') == 'Gilir 3' ? 'selected' : '' }}>3</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="pt-8">
                                    <button type="submit" 
                                            class="w-full relative group overflow-hidden py-4 px-4 rounded-2xl shadow-[0_10px_30px_-10px_rgba(0,0,0,0.3)] text-xs font-black uppercase tracking-[0.2em] transition-all duration-500 active:scale-95 disabled:opacity-30 disabled:cursor-not-allowed disabled:grayscale"
                                            :class="currentTheme ? (currentTheme.btn + ' ' + currentTheme.text) : 'bg-gray-900 text-white'"
                                            :disabled="selectedPacks.length === 0 || validationError !== ''">
                                        <div class="absolute inset-0 bg-white/20 translate-y-full transition-transform duration-300 group-hover:translate-y-0"></div>
                                        <span class="relative z-10 flex items-center justify-center">
                                            Simpan Data Sortir
                                            <svg class="w-4 h-4 ml-2 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine.js script for sorting layout -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('sortingGrid', (initialPecahan = '', themes = {}) => ({
                selectedPecahan: initialPecahan,
                themes: themes,
                get currentTheme() { return this.themes[this.selectedPecahan] || null },
                @if(request()->has('selected_packs'))
                    selectedPacks: [{{ request('selected_packs') }}],
                @else
                    selectedPacks: [],
                @endif
                isDragging: false,
                dragStart: null,
                validationError: '',
                isManual: {{ old('is_manual') ? 'true' : 'false' }},
                packQuantities: {
                    @foreach ($packsData as $pack)
                        {{ $pack->pack_number }}: {{ $pack->jumlah }},
                    @endforeach
                },
                
                get totalBilyet() {
                    return this.selectedPacks.reduce((total, num) => {
                        return total + (this.packQuantities[num] || 45000);
                    }, 0);
                },

                formatNumber(num) {
                    return new Intl.NumberFormat('id-ID').format(num);
                },

                isSelected(num) {
                    return this.selectedPacks.includes(num);
                },

                startSelection(num) {
                    // Logic to toggle block or start drag
                    this.isDragging = true;
                    this.dragStart = num;
                    
                    // If clicking an already selected one, we don't clear, we just start fresh selection point
                    if (!this.isSelected(num)) {
                       // Optional: If we want strict block clicking, we might just re-evaluate here
                       this.selectedPacks = [num];
                    } else {
                        // Deselect block logic
                        let newSelection = [...this.selectedPacks];
                        const index = newSelection.indexOf(num);
                        if (index > -1) {
                            newSelection.splice(index, 1);
                        }
                        this.selectedPacks = [...newSelection].sort((a,b)=>a-b);
                    }
                    this.validateSelection();
                    
                    // Actually, a better UX is clicking toggles selection. Let's make it simpler for user:
                    // If start click is not selected, select it. If drag, add to it.
                    this.selectedPacks = [num]; // reset on new click to start fresh block selection
                },

                onHover(num) {
                     if(!this.isDragging || this.dragStart === null) return;
                     
                     let start = Math.min(this.dragStart, num);
                     let end = Math.max(this.dragStart, num);
                     
                     let newSelection = [];
                     for(let i=start; i<=end; i++){
                        newSelection.push(i);
                     }
                     this.selectedPacks = [...newSelection];
                },
                
                endSelection() {
                    this.isDragging = false;
                    this.validateSelection();
                },

                togglePack(num) {
                    if (this.isSelected(num)) {
                        this.selectedPacks = this.selectedPacks.filter(p => p !== num);
                    } else {
                        this.selectedPacks = [...this.selectedPacks, num].sort((a,b) => a-b);
                    }
                    this.validateSelection();
                },

                validateSelection() {
                    this.validationError = '';
                    if (this.selectedPacks.length === 0) return;

                    // Group into contiguous blocks
                    let blocks = [];
                    let currentBlock = [];
                    
                    for (let i = 0; i < this.selectedPacks.length; i++) {
                        let pack = this.selectedPacks[i];
                        if (currentBlock.length === 0) {
                            currentBlock.push(pack);
                        } else {
                            if (pack === currentBlock[currentBlock.length - 1] + 1) {
                                currentBlock.push(pack);
                            } else {
                                blocks.push(currentBlock);
                                currentBlock = [pack];
                            }
                        }
                    }
                    if (currentBlock.length > 0) {
                        blocks.push(currentBlock);
                    }

                    // Validate each block
                    let hasError = false;
                    for (let block of blocks) {
                        // Jika mode manual (sisa pack) dimatikan, baru cek kelipatan 4 dan boundary-nya
                        if (!this.isManual) {
                            if (block.length % 4 !== 0 || (block[0] - 1) % 4 !== 0) {
                                hasError = true;
                                break;
                            }
                        }
                    }

                    if (hasError) {
                        this.validationError = 'Pack yang dipilih harus berurutan, berkelipatan 4, dan dimulai dari urutan yang benar (1, 5, 9... dst).';
                    }
                },
                
                validateSubmission(e) {
                    this.validateSelection();
                    if(this.validationError !== '' || this.selectedPacks.length === 0) {
                        e.preventDefault();
                        if(this.selectedPacks.length === 0) {
                            this.validationError = 'Silahkan pilih pack terlebih dahulu.';
                        }
                    }
                }
            }));
        });
    </script>
</x-app-layout>
