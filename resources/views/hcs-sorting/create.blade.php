<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Proses Penyortiran HCS') }} - {{ $seri }} / {{ $pecahan }}
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
    
    <style>
        @keyframes bounce-subtle {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-3px); }
        }
        .animate-bounce-subtle {
            animation: bounce-subtle 2s infinite ease-in-out;
        }
    </style>

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
                <!-- Kolom Kiri: Pilih Grid -->
                <div class="flex-1 bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-500 sm:rounded-xl border-t-4"
                     :class="currentTheme ? currentTheme.border : 'border-gray-100'">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center uppercase tracking-wider">
                            <div class="w-2 h-6 mr-3 rounded-full transition-all duration-500" :class="currentTheme ? currentTheme.bg : 'bg-gray-400'"></div>
                            Pilih Pack untuk Disortir
                        </h3>
                        
                        <!-- Keterangan Warna -->
                        <div class="flex flex-wrap gap-4 mb-8 text-[10px] font-bold tracking-widest text-gray-500 bg-gray-50/50 p-4 rounded-lg border border-gray-100">
                            <div class="flex items-center"><div class="w-3 h-3 rounded-sm bg-blue-500 mr-2 shadow-sm"></div>Rikyet Siap Sortir</div>
                            <div class="flex items-center"><div class="w-3 h-3 rounded-sm bg-green-500 mr-2 shadow-sm"></div>Cutpack Siap Sortir</div>
                            <div class="flex items-center"><div class="w-3 h-3 rounded-sm bg-blue-200 border border-blue-400 mr-2" style="background-image: repeating-linear-gradient(45deg, rgba(0,0,0,0.1), rgba(0,0,0,0.1) 3px, transparent 3px, transparent 6px);"></div>Rikyet Tersortir</div>
                            <div class="flex items-center"><div class="w-3 h-3 rounded-sm bg-green-200 border border-green-400 mr-2" style="background-image: repeating-linear-gradient(-45deg, rgba(0,0,0,0.1), rgba(0,0,0,0.1) 3px, transparent 3px, transparent 6px);"></div>Cutpack Tersortir</div>
                            <div class="flex items-center"><div class="w-3 h-3 rounded-sm ring-2 ring-amber-400 bg-white mr-2 flex items-center justify-center text-amber-500 font-black text-[8px]"><svg class="w-2 h-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg></div>Pack Terpilih</div>
                            <div class="flex items-center"><div class="w-3 h-3 rounded-sm bg-gray-100 border border-gray-200 mr-2"></div>Kosong</div>
                        </div>

                        <!-- Grid 10x10 untuk memilih pack -->
                        <div class="grid grid-flow-col gap-2 mb-8" style="grid-template-rows: repeat(10, minmax(0, 1fr));" @mouseleave="isDragging = false">
                                @for ($i = 1; $i <= 100; $i++)
                                    @php
                                        $pack = $packsData->get($i);
                                        $status = 'empty';
                                        $statusClass = 'bg-gray-50 dark:bg-slate-900/40 border-gray-100 dark:border-slate-800 text-gray-300 dark:text-slate-600 cursor-not-allowed opacity-50';
                                        $isReady = false;
                                        $isSorted = false;
                                        $statusText = 'Belum Diinput';
                                        $supplierText = 'KOSONG';
                                        $supplierClass = 'text-gray-400';
                                        $hatchStyle = "";

                                        if ($pack) {
                                            $isSorted = !is_null($pack->hcs_sorting_id);
                                            $supplier = $pack->pack_supplier;
                                            $supplierText = $supplier;
                                            $supplierLower = strtolower($supplier);

                                            if ($isSorted) {
                                                $status = 'sorted';
                                                $statusText = "Sudah Tersortir";
                                                if (str_contains($supplierLower, 'rikyet')) {
                                                    $statusClass = 'bg-blue-100 dark:bg-blue-900/30 text-blue-900 dark:text-blue-300 cursor-not-allowed border-blue-200 dark:border-blue-800 shadow-inner opacity-60';
                                                    $hatchStyle = "background-image: repeating-linear-gradient(45deg, rgba(0,0,0,0.05), rgba(0,0,0,0.05) 4px, transparent 4px, transparent 8px);";
                                                    $supplierClass = 'text-blue-400';
                                                } else {
                                                    $statusClass = 'bg-green-100 dark:bg-green-900/30 text-green-900 dark:text-green-300 cursor-not-allowed border-green-200 dark:border-green-800 shadow-inner opacity-60';
                                                    $hatchStyle = "background-image: repeating-linear-gradient(-45deg, rgba(0,0,0,0.05), rgba(0,0,0,0.05) 4px, transparent 4px, transparent 8px);";
                                                    $supplierClass = 'text-green-400';
                                                }
                                            } else {
                                                $status = 'ready';
                                                $statusText = "Tersedia untuk disortir";
                                                $isReady = true;
                                                if (str_contains($supplierLower, 'rikyet')) {
                                                    $statusClass = 'bg-blue-500 text-white hover:bg-blue-600 cursor-pointer shadow-sm hover:scale-105 transform transition-all';
                                                    $supplierClass = 'text-blue-400';
                                                } else {
                                                    $statusClass = 'bg-green-500 text-white hover:bg-green-600 cursor-pointer shadow-sm hover:scale-105 transform transition-all';
                                                    $supplierClass = 'text-green-400';
                                                }
                                            }
                                        }

                                        // Hitungan grid agar pas buat kolom (column-major)
                                        $gridRow = ($i - 1) % 10;
                                        $gridCol = floor(($i - 1) / 10);
                                        
                                        $vClass = ($gridRow < 4) ? 'top-full mt-2 flex-col-reverse' : 'bottom-full mb-2 flex-col';
                                        $arrowV = ($gridRow < 4) ? '-mb-1' : '-mt-1';
                                        if ($gridCol < 2) {
                                            $hClass = 'left-0 translate-x-0';
                                            $arrowH = 'left-3 translate-x-0';
                                        } elseif ($gridCol > 7) {
                                            $hClass = 'right-0 left-auto translate-x-0';
                                            $arrowH = 'right-3 translate-x-0';
                                        } else {
                                            $hClass = 'left-1/2 -translate-x-1/2';
                                            $arrowH = 'left-1/2 -translate-x-1/2';
                                        }
                                    @endphp

                                    <div class="relative group/pack">
                                        <div 
                                            class="h-10 w-full flex items-center justify-center rounded-md text-sm font-black border select-none transition-all duration-75 relative overflow-hidden cursor-pointer
                                                   {{ $statusClass }}"
                                            style="{{ $hatchStyle }}"
                                            :class="{
                                                'ring-4 scale-110 z-10 shadow-lg brightness-125 ring-amber-400 !border-amber-500 !bg-white !text-gray-900': selectedPacks.has({{ $i }}),
                                            }"
                                            @if($isReady)
                                                @click="togglePack({{ $i }})"
                                            @endif
                                        >
                                            <span class="relative z-10">{{ $i }}</span>

                                            <!-- Tampilan saat dipilih -->
                                            <template x-if="selectedPacks.has({{ $i }})">
                                                <div class="absolute -top-1 -right-1 w-5 h-5 bg-amber-400 rounded-full flex items-center justify-center shadow border-2 border-white animate-bounce-subtle z-20">
                                                    <svg class="w-3 h-3 text-gray-900" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                </div>
                                            </template>
                                        </div>

                                        <!-- Tooltip (Optimized Performance: No Backdrop Blur) -->
                                        <div class="pointer-events-none absolute {{ $vClass }} {{ $hClass }} z-[100] hidden group-hover/pack:flex items-center transition-opacity duration-200">
                                            <div class="bg-gray-900/95 dark:bg-slate-900 text-white text-[10px] rounded-2xl px-4 py-3 whitespace-nowrap shadow-xl text-center leading-tight border border-white/10 dark:border-slate-700/50 min-w-[150px]">
                                                <div class="font-black border-b border-white/10 dark:border-slate-700 pb-2 mb-2 flex items-center justify-center gap-2">
                                                    PACK {{ $i }}
                                                    <span class="px-2 py-0.5 rounded-full text-[8px] text-white" 
                                                          :class="selectedPacks.has({{ $i }}) ? 'bg-amber-500' : 'bg-rose-500'" 
                                                          x-show="selectedPacks.has({{ $i }}) || {{ $isSorted ? 'true' : 'false' }}">
                                                        <span x-show="selectedPacks.has({{ $i }})">DIPILIH</span>
                                                        <span x-show="!selectedPacks.has({{ $i }}) && {{ $isSorted ? 'true' : 'false' }}">TERSORTIR</span>
                                                    </span>
                                                </div>
                                                <div class="font-black uppercase tracking-wider text-xs"
                                                     :class="selectedPacks.has({{ $i }}) ? 'text-amber-400' : '{{ $supplierClass }}'">
                                                    {{ $supplierText }}
                                                </div>
                                                <div class="text-gray-400 dark:text-slate-500 text-[9px] mt-1.5 font-bold uppercase tracking-widest"
                                                     x-text="selectedPacks.has({{ $i }}) ? 'Pack Terpilih di Sesi Ini' : '{{ $statusText }}'">
                                                </div>
                                            </div>
                                            <div class="w-2.5 h-2.5 bg-gray-900 dark:bg-slate-900 rotate-45 border-r border-b border-white/10 dark:border-slate-700/50 {{ $arrowV }} {{ $arrowH }}"></div>
                                        </div>
                                    </div>
                                @endfor
                        </div>
                        
                        <!-- Pesan Error Validasi -->
                        <div x-show="validationError" x-cloak 
                             class="text-red-700 text-xs font-bold mt-4 p-4 bg-red-50 rounded-lg border border-red-100 flex items-center space-x-2 animate-pulse" 
                             x-text="validationError"></div>
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-4 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                            Klik pada kotak untuk memilih/menghapus Grup Pack (Otomatis Kelipatan 4).
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Isi Formnya -->
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
                            
                            <!-- Input tersembunyi untuk menyimpan pack yang dipilih -->
                            <template x-for="pack in Array.from(selectedPacks)" :key="pack">
                                <input type="hidden" name="selected_packs[]" :value="pack">
                            </template>

                            <div class="space-y-4">
                                <!-- Tombol memilih Mode Manual -->
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

                                <!-- Isian Form -->
                                    <div class="grid grid-cols-2 gap-3">
                                        <!-- Ringkasan Total (Dibuat menumpuk agar tidak terpotong) -->
                                        <div>
                                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1.5 px-1">Total Pack</label>
                                            <div class="relative group">
                                                <input type="text" :value="selectedPacks.size" 
                                                       class="block w-full border-gray-200 rounded-xl bg-gray-50 shadow-inner text-sm py-3 px-10 font-bold text-center opacity-80"
                                                       readonly />
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1.5 px-1">Total Bilyet</label>
                                            <div class="relative group">
                                                <input type="text" :value="formatNumber(totalBilyet)" 
                                                       class="block w-full border-gray-200 rounded-xl bg-gray-50 shadow-inner text-sm py-3 px-10 font-bold text-center opacity-80"
                                                       readonly />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label for="supplier" class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1.5 px-1">Supplier</label>
                                            <div class="relative group">
                                                <select id="supplier" name="supplier" x-model="supplier"
                                                        class="block w-full border-gray-200 rounded-xl bg-white/50 backdrop-blur-sm shadow-sm text-sm py-3 px-10 transition-all duration-300 focus:ring-4 font-bold text-center appearance-none"
                                                        :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring.replace('focus:', '')) : 'focus:border-indigo-500 focus:ring-indigo-500/20'" 
                                                        required>
                                                    <option value="">Supplier</option>
                                                    <option value="Cutpack">Cutpack</option>
                                                    <option value="Rikyet">Rikyet</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div>
                                            <label for="tanggal" class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1.5 px-1">Tanggal</label>
                                            <div class="relative group">
                                                <input id="tanggal" name="tanggal" type="date"
                                                       class="block w-full border-gray-200 rounded-xl bg-white/50 backdrop-blur-sm shadow-sm text-sm py-3 px-10 font-bold text-center transition-all duration-300 focus:ring-4"
                                                       :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring.replace('focus:', '')) : 'focus:border-indigo-500 focus:ring-indigo-500/20'"
                                                       value="{{ old('tanggal', date('Y-m-d')) }}" required />
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1.5 px-1">Emisi / Tahun Anggaran</label>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div class="relative">
                                                <input type="text" 
                                                       class="block w-full border-gray-200 rounded-xl bg-gray-100/50 shadow-inner text-sm py-3 px-10 font-bold text-center opacity-70"
                                                       value="{{ $emisi }}" readonly />
                                            </div>
                                            <div class="relative">
                                                <input type="text" 
                                                       class="block w-full border-gray-200 rounded-xl bg-gray-100/50 shadow-inner text-sm py-3 px-10 font-bold text-center opacity-70"
                                                       value="{{ $tahun_anggaran }}" readonly />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label for="petugas_1" class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1.5 px-1">Petugas 1</label>
                                            <div class="relative group">
                                                <input id="petugas_1" name="petugas_1" type="text" 
                                                       class="block w-full border-gray-200 rounded-xl bg-white/50 backdrop-blur-sm shadow-sm text-sm py-3 px-10 transition-all duration-300 focus:ring-4 font-bold text-center"
                                                       :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring.replace('focus:', '')) : 'focus:border-indigo-500 focus:ring-indigo-500/20'"
                                                       value="{{ old('petugas_1') }}" placeholder="Petugas 1" required />
                                            </div>
                                        </div>

                                        <div>
                                            <label for="petugas_2" class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1.5 px-1">Petugas 2 (Ops)</label>
                                            <div class="relative group">
                                                <input id="petugas_2" name="petugas_2" type="text"
                                                       class="block w-full border-gray-200 rounded-xl bg-white/50 backdrop-blur-sm shadow-sm text-sm py-3 px-10 transition-all duration-300 focus:ring-4 font-bold text-center"
                                                       :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring.replace('focus:', '')) : 'focus:border-indigo-500 focus:ring-indigo-500/20'"
                                                       value="{{ old('petugas_2') }}" placeholder="Petugas 2" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-5 gap-3 items-end">
                                        <div class="col-span-1">
                                            <label for="gilir" class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1.5 px-1 text-center">Gilir</label>
                                            <div class="relative group">
                                                <select id="gilir" name="gilir" 
                                                        class="block w-full border-gray-200 rounded-xl bg-white/50 backdrop-blur-sm shadow-sm text-[10px] py-4 px-2 transition-all duration-300 focus:ring-4 font-black text-center appearance-none"
                                                        :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring.replace('focus:', '')) : 'focus:border-indigo-500 focus:ring-indigo-500/20'"
                                                        required>
                                                    <option value="">Gilir</option>
                                                    <option value="Gilir 1" {{ old('gilir') == 'Gilir 1' ? 'selected' : '' }}>1</option>
                                                    <option value="Gilir 2" {{ old('gilir') == 'Gilir 2' ? 'selected' : '' }}>2</option>
                                                    <option value="Gilir 3" {{ old('gilir') == 'Gilir 3' ? 'selected' : '' }}>3</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-span-4">
                                            <button type="submit" 
                                                    class="w-full relative group overflow-hidden py-4 px-4 rounded-2xl shadow-[0_10px_30px_-10px_rgba(0,0,0,0.3)] text-xs font-black uppercase tracking-[0.2em] transition-all duration-500 active:scale-95 disabled:opacity-30 disabled:cursor-not-allowed disabled:grayscale"
                                                    :class="currentTheme ? (currentTheme.btn + ' ' + currentTheme.text) : 'bg-gray-900 text-white'"
                                                    :disabled="selectedPacks.size === 0 || validationError !== ''">
                                                <div class="absolute inset-0 bg-white/20 translate-y-full transition-transform duration-300 group-hover:translate-y-0"></div>
                                                <span class="relative z-10 flex items-center justify-center">
                                                    Simpan Data Penyortiran
                                                    <svg class="w-5 h-5 ml-2 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Alpine.js untuk mengatur layout sortir -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('sortingGrid', (initialPecahan = '', themes = {}) => ({
                selectedPecahan: initialPecahan,
                themes: themes,
                get currentTheme() { return this.themes[this.selectedPecahan] || null },
                 @if(request()->has('selected_packs'))
                    selectedPacks: new Set({{ json_encode(array_map('intval', is_array(request('selected_packs')) ? request('selected_packs') : explode(',', request('selected_packs')))) }}),
                @else
                    selectedPacks: new Set(),
                @endif
                init() {
                    // Cek atau pilih supplier otomatis jika pack-nya sudah terpilih dari awal
                    if (this.selectedPacks.size > 0) {
                        this.validateSelection();
                    }
                },
                validationError: '',
                isManual: {{ old('is_manual') ? 'true' : 'false' }},
                supplier: '{{ old('supplier', '') }}',
                packQuantities: {
                    @foreach ($packsData as $pack)
                        {{ $pack->pack_number }}: {{ $pack->jumlah }},
                    @endforeach
                },
                packSuppliers: {
                    @foreach ($packsData as $pack)
                        {{ $pack->pack_number }}: '{{ $pack->pack_supplier }}',
                    @endforeach
                },
                packStatuses: {
                    @foreach ($packsData as $pack)
                        {{ $pack->pack_number }}: {{ $pack->hcs_sorting_id ? 'true' : 'false' }},
                    @endforeach
                },
                
                get totalBilyet() {
                    let total = 0;
                    this.selectedPacks.forEach(num => {
                        total += (this.packQuantities[num] || 45000);
                    });
                    return total;
                },

                formatNumber(num) {
                    return new Intl.NumberFormat('id-ID').format(num);
                },

                 isSelected(num) {
                    return this.selectedPacks.has(num);
                },

                 togglePack(num) {
                    if (!this.isManual) {
                        // Logic Grup Kelipatan 4
                        let startBlock = Math.floor((num - 1) / 4) * 4 + 1;
                        let block = [startBlock, startBlock + 1, startBlock + 2, startBlock + 3];
                        
                        // Cek apakah seluruh blok sudah ada
                        let allSelected = block.every(p => this.selectedPacks.has(p));
                        
                        if (allSelected) {
                            // Hapus satu blok
                            block.forEach(p => this.selectedPacks.delete(p));
                        } else {
                            // Tambah satu blok, tapi hanya yang tersedia (di-input dan belum disortir)
                            let availableInBlock = block.filter(p => this.packQuantities[p] && !this.packStatuses[p]);
                            
                            if (availableInBlock.length < 4) {
                                let missingInBlock = block.filter(p => !this.packQuantities[p]);
                                let sortedInBlock = block.filter(p => this.packStatuses[p]);
                                
                                let errorMsg = '';
                                if (missingInBlock.length > 0) errorMsg += `Pack ${missingInBlock.join(', ')} belum di-input. `;
                                if (sortedInBlock.length > 0) errorMsg += `Pack ${sortedInBlock.join(', ')} sudah disortir. `;
                                
                                Swal.fire({
                                    title: 'Grup Tidak Lengkap',
                                    text: errorMsg + 'Grup kelipatan 4 harus lengkap untuk dipilih dalam mode ini. Gunakan "Mode Sisa Pack" jika ingin memilih secara manual.',
                                    icon: 'warning',
                                    confirmButtonColor: '#4f46e5'
                                });
                                return;
                            }
                            
                            availableInBlock.forEach(p => this.selectedPacks.add(p));
                        }
                    } else {
                        // Logic Manual (Satu per satu)
                        if (this.selectedPacks.has(num)) {
                            this.selectedPacks.delete(num);
                        } else {
                            this.selectedPacks.add(num);
                        }
                    }
                    
                    this.selectedPacks = new Set(this.selectedPacks); // trigger reactivity
                    this.validateSelection();
                },

                validateSelection() {
                    this.validationError = '';
                    if (this.selectedPacks.size === 0) return;
    
                    // Kelompokkan pack yang urutannya menyambung (Convert Set to array strictly for logic)
                    let sortedPacks = Array.from(this.selectedPacks).sort((a, b) => a - b);
                    let blocks = [];
                    let currentBlock = [];
                    
                    for (let i = 0; i < sortedPacks.length; i++) {
                        let pack = sortedPacks[i];
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

                    // Cek tiap blok yang dipilih
                    let hasError = false;
                    for (let block of blocks) {
                        // Jika bukan mode manual (sisa pack), cek apakah sudah kelipatan 4 dan urutannya benar
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
                    
                    // Selalu coba update supplier jika ada pack yang dipilih
                    this.updateAutoSupplier();
                },

                 updateAutoSupplier() {
                    if (this.selectedPacks.size === 0) return;
                    
                    let counts = { 'Cutpack': 0, 'Rikyet': 0 };
                    this.selectedPacks.forEach(num => {
                        let s = this.packSuppliers[num];
                        if (s) {
                            let normalizedS = s.toLowerCase().includes('rikyet') ? 'Rikyet' : 'Cutpack';
                            counts[normalizedS]++;
                        }
                    });

                    if (counts['Cutpack'] > counts['Rikyet']) {
                        this.supplier = 'Cutpack';
                    } else if (counts['Rikyet'] > counts['Cutpack']) {
                        this.supplier = 'Rikyet';
                    }
                },
                
                validateSubmission(e) {
                    this.validateSelection();
                    if(this.validationError !== '' || this.selectedPacks.size === 0) {
                        e.preventDefault();
                        if(this.selectedPacks.size === 0) {
                            this.validationError = 'Silahkan pilih pack terlebih dahulu.';
                        }
                    }
                }
            }));
        });
    </script>
</x-app-layout>
