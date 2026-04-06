<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <div class="flex-1 [&_h2]:text-white [&_h2]:font-extrabold [&_h2]:tracking-tight [&_h2]:drop-shadow">
                <h2 class="font-black text-2xl text-white leading-tight drop-shadow-md tracking-tight">
                    {{ __('Edit Laporan Penyortiran') }}
                </h2>
            </div>
        </div>
    </x-slot>


    <div id="main-wrapper" class="py-10 min-h-screen bg-[#f8fafc] dark:bg-slate-900 transition-colors duration-500"
        x-data="sortingGrid()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">

            @if ($errors->any())
                <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 rounded-xl shadow-sm mb-6 flex items-start gap-3"
                    role="alert">
                    <div class="p-1 bg-rose-500 rounded-full">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-sm">Terjadi Kesalahan!</p>
                        <ul class="mt-1 list-disc list-inside text-xs opacity-90">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 rounded-xl shadow-sm mb-6 flex items-center gap-3"
                    role="alert">
                    <div class="p-1 bg-rose-500 rounded-full">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <span class="text-sm font-bold">{{ session('error') }}</span>
                </div>
            @endif

            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Kolom Kiri: Pilihan Grid -->
                <div class="flex-1">
                    <div
                        class="bg-white dark:bg-slate-800/50 rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 dark:border-slate-700/50 overflow-hidden group">
                        <!-- Card Header -->
                        <div
                            class="px-8 py-6 border-b border-slate-50 dark:border-slate-700/30 flex justify-between items-center bg-white dark:bg-slate-800/50">
                            <div>
                                <h3 class="text-xl font-black text-gray-800 dark:text-gray-100 flex items-center gap-2">
                                    <span class="w-2 h-8 bg-indigo-600 dark:bg-indigo-500 rounded-full"></span>
                                    Visualisasi Data Pack
                                </h3>
                                <p class="text-xs text-gray-400 dark:text-gray-500 font-medium mt-1">Ubah atau sesuaikan
                                    pilihan pack yang disortir untuk batch ini.</p>
                                <div class="flex items-center gap-2 mt-3">
                                    <span
                                        class="px-2 py-1 bg-slate-100 dark:bg-slate-900/50 rounded-lg text-[10px] font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider border border-slate-200 dark:border-slate-700 shadow-sm">Batch:
                                        {{ $hcs_sorting_report->batch }}</span>
                                    <span
                                        class="px-2 py-1 bg-slate-100 dark:bg-slate-900/50 rounded-lg text-[10px] font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider border border-slate-200 dark:border-slate-700 shadow-sm">Seri:
                                        {{ $hcs_sorting_report->seri }}</span>
                                    @php
                                        $pecahanColors = [
                                            'S' => 'bg-lime-500',
                                            'T' => 'bg-gray-400',
                                            'U' => 'bg-amber-400',
                                            'V' => 'bg-purple-500',
                                            'W' => 'bg-green-500',
                                            'X' => 'bg-blue-500',
                                            'Y' => 'bg-red-500'
                                        ];
                                    @endphp
                                    <span
                                        class="px-2 py-1 {{ $pecahanColors[$hcs_sorting_report->pecahan] ?? 'bg-indigo-500' }} rounded-lg text-[10px] font-black text-white uppercase tracking-wider border border-white/20 shadow-md">Pecahan:
                                        {{ $hcs_sorting_report->pecahan }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="p-8">
                            <!-- Legend Card -->
                            <div
                                class="mb-8 p-5 bg-slate-50/80 dark:bg-slate-900/40 rounded-2xl border border-slate-100/50 dark:border-slate-700/30 grid grid-cols-2 md:grid-cols-3 gap-4">
                                <div class="flex items-center gap-2.5">
                                    <div
                                        class="w-5 h-5 rounded-lg bg-gradient-to-br from-blue-400 to-blue-600 shadow-lg shadow-blue-200 dark:shadow-none ring-2 ring-white dark:ring-slate-700">
                                    </div>
                                    <span
                                        class="text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-tighter">Rikyet
                                        (Tersedia)</span>
                                </div>
                                <div class="flex items-center gap-2.5">
                                    <div
                                        class="w-5 h-5 rounded-lg bg-gradient-to-br from-emerald-400 to-emerald-600 shadow-lg shadow-emerald-200 dark:shadow-none ring-2 ring-white dark:ring-slate-700">
                                    </div>
                                    <span
                                        class="text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-tighter">Cutpack
                                        (Tersedia)</span>
                                </div>
                                <div class="flex items-center gap-2.5">
                                    <div
                                        class="w-5 h-5 rounded-lg pack-sorted-other-rikyet relative overflow-hidden ring-2 ring-white dark:ring-slate-700">
                                    </div>
                                    <span
                                        class="text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-tighter">Rikyet
                                        (Sesi Lain)</span>
                                </div>
                                <div class="flex items-center gap-2.5">
                                    <div
                                        class="w-5 h-5 rounded-lg pack-sorted-other-cutpack relative overflow-hidden ring-2 ring-white dark:ring-slate-700">
                                    </div>
                                    <span
                                        class="text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-tighter">Cutpack
                                        (Sesi Lain)</span>
                                </div>
                                <div class="flex items-center gap-2.5">
                                    <div
                                        class="w-5 h-5 rounded-lg ring-2 ring-amber-400 bg-white dark:bg-slate-700 flex items-center justify-center text-amber-500 shadow-sm">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <span
                                        class="text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-tighter">Pack
                                        Terpilih</span>
                                </div>
                                <div class="flex items-center gap-2.5">
                                    <div
                                        class="w-5 h-5 rounded-lg bg-slate-200 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 ring-2 ring-white dark:ring-slate-700 shadow-inner">
                                    </div>
                                    <span
                                        class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-tighter">Belum
                                        Diinput</span>
                                </div>
                            </div>

                            <!-- 10x10 Grid Container -->
                            <div
                                class="relative group/grid bg-slate-100/30 dark:bg-slate-950/40 p-4 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-inner overflow-x-auto lg:overflow-visible">
                                <div class="grid grid-flow-col gap-2 min-w-[700px] lg:min-w-0"
                                    style="grid-template-rows: repeat(10, minmax(0, 1fr));"
                                    @mouseleave="isDragging = false">
                                    @for ($i = 1; $i <= 100; $i++)
                                        @php
                                            $pack = $packsData->get($i);
                                            $statusStyle = '';
                                            $isReady = false;
                                            $isSortedByOther = false;
                                            $extraClasses = '';
                                            $statusText = 'Belum Diinput';
                                            $supplierText = 'KOSONG';
                                            $supplierClass = 'text-gray-400';

                                            if ($pack) {
                                                $supplier = $pack->pack_supplier;
                                                $supplierText = $supplier;
                                                $supplierLower = strtolower($supplier);
                                                $isSortedByOther = !is_null($pack->hcs_sorting_id) && $pack->hcs_sorting_id !== $hcs_sorting_report->id;

                                                if ($isSortedByOther) {
                                                    $statusText = "Sudah Tersortir (Sesi Lain)";
                                                    $extraClasses .= ' cursor-not-allowed opacity-60 dark:opacity-40';
                                                    $isRikyet = str_contains($supplierLower, 'rikyet');
                                                    $extraClasses .= $isRikyet ? ' pack-sorted-other-rikyet' : ' pack-sorted-other-cutpack';
                                                    $supplierClass = $isRikyet ? 'text-blue-400' : 'text-emerald-400';
                                                } else {
                                                    $statusText = "Tersedia untuk disortir";
                                                    $isReady = true;
                                                    if (str_contains($supplierLower, 'rikyet')) {
                                                        $extraClasses = 'bg-gradient-to-br from-blue-500 to-blue-700 dark:from-blue-600 dark:to-blue-800 text-white hover:scale-105 hover:shadow-lg shadow-blue-200 dark:shadow-none border-blue-400 dark:border-blue-500 active:scale-95';
                                                        $supplierClass = 'text-blue-400';
                                                    } else {
                                                        $extraClasses = 'bg-gradient-to-br from-emerald-500 to-emerald-700 dark:from-emerald-600 dark:to-emerald-800 text-white hover:scale-105 hover:shadow-lg shadow-emerald-200 dark:shadow-none border-emerald-400 dark:border-emerald-500 active:scale-95';
                                                        $supplierClass = 'text-emerald-400';
                                                    }
                                                }
                                            } else {
                                                $extraClasses = 'bg-slate-200 dark:bg-slate-700/50 text-slate-400 dark:text-slate-500 border-slate-300 dark:border-slate-600/50 opacity-40 cursor-not-allowed';
                                            }

                                            // Perhitungan grid yang diperbaiki untuk grid-flow-col (major kolom)
                                            $gridRow = ($i - 1) % 10;
                                            $gridCol = floor(($i - 1) / 10);

                                            // Vertikal: Hindari pemotongan atas/bawah
                                            $vClass = ($gridRow < 4) ? 'top-full mt-2 flex-col-reverse' : 'bottom-full mb-2 flex-col';
                                            $arrowV = ($gridRow < 4) ? '-mb-1' : '-mt-1';

                                            // Horizontal: Hindari pemotongan kiri/kanan
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
                                            <div class="h-10 w-full flex items-center justify-center rounded-lg text-sm font-black border-2 select-none transition-all duration-200 relative overflow-hidden shadow-sm dark:shadow-none
                                                           {{ $extraClasses }}" :class="{
                                                        'ring-4 scale-110 z-10 shadow-xl brightness-125 ring-amber-400 ring-offset-0 !opacity-100 !border-amber-500': isSelected({{ $i }})
                                                    }" @if($isReady) @mousedown="startSelection({{ $i }})"
                                                    @mouseenter="onHover({{ $i }})" @mouseup="endSelection()" @endif>
                                                <span class="relative z-10">{{ $i }}</span>

                                                <!-- Selected Overlay -->
                                                <template x-if="isSelected({{ $i }})">
                                                    <div
                                                        class="absolute -top-1 -right-1 w-5 h-5 bg-amber-400 rounded-full flex items-center justify-center shadow-lg border-2 border-white animate-bounce-subtle z-20">
                                                        <svg class="w-3 h-3 text-gray-900" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                </template>
                                            </div>

                                            <!-- Custom Tooltip -->
                                            <div
                                                class="pointer-events-none absolute {{ $vClass }} {{ $hClass }} z-[100] hidden group-hover/pack:flex items-center transition-all duration-300">
                                                <div
                                                    class="bg-gray-900/95 dark:bg-slate-900/95 backdrop-blur-md text-white text-[10px] rounded-2xl px-4 py-3 whitespace-nowrap shadow-2xl text-center leading-tight border border-white/10 dark:border-slate-700/50 min-w-[150px]">
                                                    <div
                                                        class="font-black border-b border-white/10 dark:border-slate-700 pb-2 mb-2 flex items-center justify-center gap-2">
                                                        PACK {{ $i }}
                                                        <span class="px-2 py-0.5 rounded-full text-[8px] text-white"
                                                            :class="isSelected({{ $i }}) ? 'bg-amber-500' : 'bg-rose-500'"
                                                            x-show="isSelected({{ $i }}) || {{ $isSortedByOther ? 'true' : 'false' }}">
                                                            <span x-show="isSelected({{ $i }})">DIPILIH</span>
                                                            <span
                                                                x-show="!isSelected({{ $i }}) && {{ $isSortedByOther ? 'true' : 'false' }}">TERSORTIR</span>
                                                        </span>
                                                    </div>
                                                    <div class="font-black uppercase tracking-wider text-xs"
                                                        :class="isSelected({{ $i }}) ? 'text-amber-400' : '{{ $supplierClass }}'">
                                                        {{ $supplierText }}
                                                    </div>
                                                    <div class="text-gray-400 dark:text-slate-500 text-[9px] mt-1.5 font-bold uppercase tracking-widest"
                                                        x-text="isSelected({{ $i }}) ? 'Pack Terpilih di Sesi Ini' : '{{ $statusText }}'">
                                                    </div>
                                                </div>
                                                <div
                                                    class="w-2.5 h-2.5 bg-gray-900 dark:bg-slate-900 rotate-45 border-r border-b border-white/10 dark:border-slate-700/50 {{ $arrowV }} {{ $arrowH }}">
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                            </div>

                            <!-- Instruksi & Pesan Kesalahan -->
                            <div
                                class="mt-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                                <div
                                    class="flex items-center gap-3 text-gray-400 dark:text-gray-500 text-xs font-bold uppercase tracking-widest bg-slate-50 dark:bg-slate-900/40 px-4 py-3 rounded-xl border border-slate-100 dark:border-slate-800">
                                    <svg class="w-5 h-5 text-indigo-400 dark:text-indigo-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Klik & Geser (Drag) untuk memilih kelipatan 4 pack secara berurutan.
                                </div>

                                <div x-show="validationError" x-cloak
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 -translate-y-2"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    class="text-rose-600 text-sm font-black py-4 px-6 bg-rose-50 rounded-2xl border-2 border-rose-200 shadow-sm flex items-center gap-3 max-w-md">
                                    <div class="p-1 bg-rose-500 rounded-full shrink-0">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <span x-text="validationError"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Form -->
                <div class="w-full lg:w-[380px] shrink-0">
                    <div
                        class="bg-white dark:bg-slate-800/50 rounded-3xl shadow-2xl shadow-slate-200/80 dark:shadow-none border border-slate-100 dark:border-slate-700/50 overflow-hidden sticky top-24">
                        <!-- Ringkasan gaya dashboard -->
                        <div class="px-8 pt-8 pb-4">
                            <h3
                                class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mb-4">
                                Ringkasan Seleksi</h3>
                            <div class="grid grid-cols-1 gap-4">
                                <div
                                    class="bg-gradient-to-br from-indigo-500 to-indigo-700 p-4 rounded-2xl text-white relative overflow-hidden group">
                                    <div
                                        class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700">
                                    </div>
                                    <div class="relative z-10">
                                        <p class="text-[9px] font-bold text-white/70 uppercase tracking-widest">Pilihan
                                            Aktif</p>
                                        <div class="flex items-baseline gap-1 mt-0.5">
                                            <span class="text-3xl font-black" x-text="selectedPacks.length"></span>
                                            <span class="text-xs font-bold opacity-80">Pack</span>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="bg-gradient-to-br from-emerald-500 to-emerald-700 p-4 rounded-2xl text-white relative overflow-hidden group">
                                    <div
                                        class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700">
                                    </div>
                                    <div class="relative z-10">
                                        <p class="text-[9px] font-bold text-white/70 uppercase tracking-widest">Total
                                            Bilyet</p>
                                        <div class="mt-0.5">
                                            <span class="text-xl font-black tracking-tight"
                                                x-text="formatNumber(totalBilyet)"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-8 pt-4">
                            <form method="POST"
                                action="{{ route('hcs-sorting-reports.update', $hcs_sorting_report->id) }}"
                                id="sortingForm" @submit="validateSubmission">
                                @csrf
                                @method('PUT')

                                <!-- Input tersembunyi untuk pack terpilih -->
                                <template x-for="pack in selectedPacks" :key="pack">
                                    <input type="hidden" name="selected_packs[]" :value="pack">
                                </template>

                                <!-- Slider Toggle Manual -->
                                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-slate-900/40 rounded-xl border border-gray-100 dark:border-slate-700/50 transition-all duration-300"
                                    :class="isManual ? 'ring-2 ring-red-500/20 border-red-100 dark:border-red-900/30 bg-red-50/30 dark:bg-red-950/20' : ''">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 transition-colors duration-300"
                                            :class="isManual ? 'bg-red-500 text-white shadow-lg shadow-red-500/30' : 'bg-gray-200 dark:bg-slate-700 text-gray-400 dark:text-gray-500'">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        </div>
                                        <span
                                            class="text-[10px] font-black uppercase tracking-widest transition-colors duration-300"
                                            :class="isManual ? 'text-red-600 dark:text-red-400' : 'text-gray-500 dark:text-gray-400'">
                                            Input Sisa Pack (Bukan Kelipatan 4)
                                        </span>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" id="is_manual" name="is_manual" class="sr-only peer"
                                            x-model="isManual" @change="validateSelection()">
                                        <div
                                            class="w-11 h-6 bg-gray-200 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-500 shadow-inner">
                                        </div>
                                    </label>
                                </div>

                                <div class="space-y-5">
                                    <div>
                                        <label
                                            class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.15em] mb-2 px-1">Supplier
                                            Utama</label>
                                        <div class="relative group">
                                            <select id="supplier" name="supplier"
                                                class="w-full bg-slate-50 dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 focus:border-indigo-500 focus:ring-0 rounded-2xl px-4 py-3 text-sm font-bold text-gray-700 dark:text-gray-200 transition-all appearance-none outline-none"
                                                required>
                                                <option value="Cutpack" {{ old('supplier', $hcs_sorting_report->supplier) == 'Cutpack' ? 'selected' : '' }}>
                                                    Cutpack</option>
                                                <option value="Rikyet" {{ old('supplier', $hcs_sorting_report->supplier) == 'Rikyet' ? 'selected' : '' }}>Rikyet
                                                </option>
                                            </select>
                                            <div
                                                class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400 dark:text-gray-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="3" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.15em] mb-2 px-1">Tahun
                                            Emisi & Anggaran</label>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div class="relative group">
                                                <input type="text" name="emisi"
                                                    value="{{ old('emisi', $hcs_sorting_report->emisi) }}" readonly
                                                    class="w-full bg-slate-100 dark:bg-slate-900/50 border-2 border-slate-100 dark:border-slate-800 rounded-2xl px-4 py-3 text-sm font-black text-gray-500 dark:text-gray-600 cursor-not-allowed outline-none focus:ring-0">
                                                <span
                                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-[10px] font-black text-gray-300 dark:text-gray-700">EMISI</span>
                                            </div>
                                            <div class="relative group">
                                                <input type="text" name="tahun_anggaran"
                                                    value="{{ old('tahun_anggaran', $hcs_sorting_report->tahun_anggaran) }}"
                                                    readonly
                                                    class="w-full bg-slate-100 dark:bg-slate-900/50 border-2 border-slate-100 dark:border-slate-800 rounded-2xl px-4 py-3 text-sm font-black text-gray-500 dark:text-gray-600 cursor-not-allowed outline-none focus:ring-0">
                                                <span
                                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-[10px] font-black text-gray-300 dark:text-gray-700">T.A</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.15em] mb-2 px-1">Petugas
                                            Penyortir</label>
                                        <div class="space-y-3">
                                            <div class="relative group">
                                                <div
                                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 dark:text-gray-600 group-focus-within:text-indigo-400 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2.5"
                                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                </div>
                                                <input id="petugas_1" type="text" name="petugas_1"
                                                    value="{{ old('petugas_1', $hcs_sorting_report->petugas_1) }}"
                                                    required placeholder="Nama Petugas 1"
                                                    class="w-full bg-slate-50 dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 focus:border-indigo-500 focus:ring-0 rounded-2xl pl-11 pr-4 py-3 text-sm font-bold text-gray-700 dark:text-gray-200 transition-all outline-none">
                                            </div>
                                            <div class="relative group">
                                                <div
                                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 dark:text-gray-600 group-focus-within:text-indigo-400 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2.5"
                                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                </div>
                                                <input id="petugas_2" type="text" name="petugas_2"
                                                    value="{{ old('petugas_2', $hcs_sorting_report->petugas_2) }}"
                                                    placeholder="Nama Petugas 2 (Opsional)"
                                                    class="w-full bg-slate-50 dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 focus:border-indigo-500 focus:ring-0 rounded-2xl pl-11 pr-4 py-3 text-sm font-bold text-gray-700 dark:text-gray-200 transition-all outline-none">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label
                                                class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.15em] mb-2 px-1">Tgl
                                                Sortir</label>
                                            <input id="tanggal_sortir" type="date" name="tanggal_sortir"
                                                value="{{ old('tanggal_sortir', $hcs_sorting_report->tanggal_sortir->format('Y-m-d')) }}"
                                                required
                                                class="w-full bg-slate-50 dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 focus:border-indigo-500 focus:ring-0 rounded-2xl px-4 py-3 text-sm font-bold text-gray-700 dark:text-gray-200 transition-all outline-none">
                                        </div>
                                        <div>
                                            <label
                                                class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.15em] mb-2 px-1">Gilir</label>
                                            <div class="relative group">
                                                <select id="gilir" name="gilir"
                                                    class="w-full bg-slate-50 dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 focus:border-indigo-500 focus:ring-0 rounded-2xl px-4 py-3 text-sm font-bold text-gray-700 dark:text-gray-200 transition-all appearance-none outline-none"
                                                    required>
                                                    <option value="Gilir 1" {{ old('gilir', $hcs_sorting_report->gilir) == 'Gilir 1' ? 'selected' : '' }}>
                                                        Gilir 1</option>
                                                    <option value="Gilir 2" {{ old('gilir', $hcs_sorting_report->gilir) == 'Gilir 2' ? 'selected' : '' }}>
                                                        Gilir 2</option>
                                                    <option value="Gilir 3" {{ old('gilir', $hcs_sorting_report->gilir) == 'Gilir 3' ? 'selected' : '' }}>
                                                        Gilir 3</option>
                                                </select>
                                                <div
                                                    class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400 dark:text-gray-600">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="3" d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="pt-6 flex flex-col gap-3">
                                        <button type="submit"
                                            class="w-full py-4 px-6 rounded-2xl text-sm font-black text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-100 dark:focus:ring-indigo-900 transition-all active:scale-[0.98] disabled:opacity-50 disabled:grayscale disabled:scale-100 disabled:cursor-not-allowed flex items-center justify-center gap-3"
                                            :disabled="selectedPacks.length === 0 || validationError !== ''">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                            Simpan Perubahan
                                        </button>
                                        <a href="{{ route('hcs-sorting-reports.index') }}"
                                            class="w-full py-3.5 px-6 rounded-2xl text-xs font-black text-gray-500 dark:text-gray-400 bg-white dark:bg-slate-800 border-2 border-slate-100 dark:border-slate-700 hover:bg-rose-50 dark:hover:bg-rose-950/20 hover:text-rose-600 dark:hover:text-rose-400 hover:border-rose-100 dark:hover:border-rose-900/40 transition-all duration-300 flex items-center justify-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Batalkan
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Alpine.js untuk tata letak penyortiran -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('sortingGrid', () => ({
                // Inisialisasi dengan pack yang ada
                selectedPacks: {!! json_encode(old('selected_packs', $hcs_sorting_report->packs_selected)) !!}.map(Number),
                isDragging: false,
                dragStart: null,
                validationError: '',
                isManual: {{ (old('is_manual') || $hcs_sorting_report->jumlah_pack % 4 !== 0 || $hcs_sorting_report->jumlah_bilyet !== $hcs_sorting_report->jumlah_pack * 45000) ? 'true' : 'false' }},
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
            this.isDragging = true;
            this.togglePack(num);
        },

            onHover(num) {
            if(!this.isDragging) return;
        if (!this.isSelected(num)) {
            this.togglePack(num);
        }
                },

        endSelection() {
            this.isDragging = false;
            this.validateSelection();
        },

        togglePack(num) {
            if (this.isSelected(num)) {
                this.selectedPacks = this.selectedPacks.filter(p => p !== num);
            } else {
                this.selectedPacks.push(num);
            }
            this.selectedPacks.sort((a, b) => a - b);
            this.validateSelection();
        },

        validateSelection() {
            this.validationError = '';
            if (this.selectedPacks.length === 0) return;

            // Kelompokkan menjadi blok yang berurutan
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

            // Validasi setiap blok
            let hasError = false;
            for (let block of blocks) {
                if (!this.isManual) {
                    if (block.length % 4 !== 0 || (block[0] - 1) % 4 !== 0) {
                        hasError = true;
                        break;
                    }
                }
            }

            if (hasError) {
                this.validationError = 'Packs harus dipilih secara berurutan dalam kelipatan 4 (misal: 1-4, 5-8) dan dimulai dari urutan standar (1, 5, 9, ...).';
            }
        },

        validateSubmission(e) {
            this.validateSelection();
            if (this.validationError !== '' || this.selectedPacks.length === 0) {
                e.preventDefault();
                if (this.selectedPacks.length === 0) {
                    this.validationError = 'Silahkan pilih minimal satu kelompok pack (4 pack) terlebih dahulu.';
                }
            }
        }
            }));
        });
    </script>
</x-app-layout>