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

    <div class="py-10 min-h-screen bg-[#f8fafc]" x-data="sortingGrid()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
            
            @if ($errors->any())
                <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 rounded-xl shadow-sm mb-6 flex items-start gap-3" role="alert">
                    <div class="p-1 bg-rose-500 rounded-full">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" /></svg>
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
                <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 rounded-xl shadow-sm mb-6 flex items-center gap-3" role="alert">
                    <div class="p-1 bg-rose-500 rounded-full">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" /></svg>
                    </div>
                    <span class="text-sm font-bold">{{ session('error') }}</span>
                </div>
            @endif

            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Left Column: Grid Selection -->
                <div class="flex-1">
                    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden group">
                        <!-- Card Header -->
                        <div class="px-8 py-6 border-b border-slate-50 flex justify-between items-center bg-gradient-to-r from-white to-slate-50/50">
                            <div>
                                <h3 class="text-xl font-black text-slate-800 flex items-center gap-2">
                                    <span class="w-2 h-8 bg-indigo-600 rounded-full"></span>
                                    Visualisasi Data Pack
                                </h3>
                                <p class="text-xs text-slate-400 font-medium mt-1">Ubah atau sesuaikan pilihan pack yang disortir untuk batch ini.</p>
                                <div class="flex items-center gap-2 mt-3">
                                    <span class="px-2 py-1 bg-slate-100 rounded-lg text-[10px] font-bold text-slate-600 uppercase tracking-wider border border-slate-200 shadow-sm">Batch: {{ $hcs_sorting_report->batch }}</span>
                                    <span class="px-2 py-1 bg-slate-100 rounded-lg text-[10px] font-bold text-slate-600 uppercase tracking-wider border border-slate-200 shadow-sm">Seri: {{ $hcs_sorting_report->seri }}</span>
                                    @php
                                        $pecahanColors = [
                                            'S' => 'bg-lime-500', 'T' => 'bg-gray-400', 'U' => 'bg-amber-400', 
                                            'V' => 'bg-purple-500', 'W' => 'bg-green-500', 'X' => 'bg-blue-500', 'Y' => 'bg-red-500'
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 {{ $pecahanColors[$hcs_sorting_report->pecahan] ?? 'bg-indigo-500' }} rounded-lg text-[10px] font-black text-white uppercase tracking-wider border border-white/20 shadow-md">Pecahan: {{ $hcs_sorting_report->pecahan }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-8">
                            <!-- Legend Card -->
                            <div class="mb-8 p-5 bg-slate-50/80 rounded-2xl border border-slate-100/50 grid grid-cols-2 md:grid-cols-3 gap-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-5 h-5 rounded-lg bg-gradient-to-br from-blue-400 to-blue-600 shadow-lg shadow-blue-200 ring-2 ring-white"></div>
                                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-tighter">Rikyet (Tersedia)</span>
                                </div>
                                <div class="flex items-center gap-2.5">
                                    <div class="w-5 h-5 rounded-lg bg-gradient-to-br from-emerald-400 to-emerald-600 shadow-lg shadow-emerald-200 ring-2 ring-white"></div>
                                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-tighter">Cutpack (Tersedia)</span>
                                </div>
                                <div class="flex items-center gap-2.5">
                                    <div class="w-5 h-5 rounded-lg bg-blue-100 border border-blue-200 relative overflow-hidden ring-2 ring-white">
                                        <div class="absolute inset-0 opacity-20" style="background-image: repeating-linear-gradient(45deg, #1e40af, #1e40af 2px, transparent 2px, transparent 4px);"></div>
                                    </div>
                                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-tighter">Rikyet (Sesi Lain)</span>
                                </div>
                                <div class="flex items-center gap-2.5">
                                    <div class="w-5 h-5 rounded-lg bg-emerald-100 border border-emerald-200 relative overflow-hidden ring-2 ring-white">
                                        <div class="absolute inset-0 opacity-20" style="background-image: repeating-linear-gradient(-45deg, #065f46, #065f46 2px, transparent 2px, transparent 4px);"></div>
                                    </div>
                                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-tighter">Cutpack (Sesi Lain)</span>
                                </div>
                                <div class="flex items-center gap-2.5">
                                    <div class="w-5 h-5 rounded-lg bg-white border-2 border-dashed border-indigo-400 ring-4 ring-indigo-100 shadow-sm ring-inset"></div>
                                    <span class="text-[11px] font-bold text-indigo-600 uppercase tracking-tighter italic">Sedang Dipilih</span>
                                </div>
                                <div class="flex items-center gap-2.5">
                                    <div class="w-5 h-5 rounded-lg bg-slate-200 border border-slate-300 ring-2 ring-white shadow-inner"></div>
                                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-tighter">Belum Diinput</span>
                                </div>
                            </div>

                            <!-- 10x10 Grid Container -->
                            <div class="relative group/grid bg-slate-100/30 p-4 rounded-3xl border border-slate-100 shadow-inner overflow-x-auto lg:overflow-visible">
                                <div class="grid grid-flow-col gap-2 min-w-[700px] lg:min-w-0" style="grid-template-rows: repeat(10, minmax(0, 1fr));" @mouseleave="isDragging = false">
                                    @for ($i = 1; $i <= 100; $i++)
                                        @php
                                            $pack = $packsData->get($i);
                                            $statusStyle = '';
                                            $isReady = false;
                                            $extraClasses = '';

                                            if ($pack) {
                                                $supplier = strtolower($pack->pack_supplier);
                                                $isSortedByOther = !is_null($pack->hcs_sorting_id) && $pack->hcs_sorting_id !== $hcs_sorting_report->id;

                                                if ($isSortedByOther) {
                                                    if (str_contains($supplier, 'rikyet')) {
                                                        $statusStyle = 'background-color: #dbeafe; border-color: #bfdbfe; color: #1e40af;';
                                                        $hatchColor = 'rgba(30, 64, 175, 0.1)';
                                                        $hatchPattern = "background-image: repeating-linear-gradient(45deg, $hatchColor, $hatchColor 4px, transparent 4px, transparent 8px);";
                                                    } else {
                                                        $statusStyle = 'background-color: #dcfce7; border-color: #bbf7d0; color: #166534;';
                                                        $hatchColor = 'rgba(22, 101, 52, 0.1)';
                                                        $hatchPattern = "background-image: repeating-linear-gradient(-45deg, $hatchColor, $hatchColor 4px, transparent 4px, transparent 8px);";
                                                    }
                                                    $statusStyle .= $hatchPattern;
                                                    $extraClasses = 'cursor-not-allowed opacity-60';
                                                } else {
                                                    $isReady = true;
                                                    if (str_contains($supplier, 'rikyet')) {
                                                        $extraClasses = 'bg-gradient-to-br from-blue-500 to-blue-700 text-white hover:scale-105 hover:shadow-lg shadow-blue-200 border-blue-400 active:scale-95';
                                                    } else {
                                                        $extraClasses = 'bg-gradient-to-br from-emerald-500 to-emerald-700 text-white hover:scale-105 hover:shadow-lg shadow-emerald-200 border-emerald-400 active:scale-95';
                                                    }
                                                }
                                            } else {
                                                $extraClasses = 'bg-slate-200 text-slate-400 border-slate-300 opacity-40 cursor-not-allowed';
                                            }
                                        @endphp

                                        <div 
                                            class="h-10 w-full flex items-center justify-center rounded-lg text-sm font-black border-2 select-none transition-all duration-200 relative overflow-hidden shadow-sm
                                                   {{ $extraClasses }}"
                                            style="{{ $statusStyle }}"
                                            :class="{
                                                'ring-[5px] ring-indigo-400 ring-offset-2 z-20 !scale-110 !opacity-100 !border-indigo-500 shadow-xl shadow-indigo-200': isSelected({{ $i }})
                                            }"
                                            title="Pack {{ $i }} {{ $pack ? '- ' . $pack->pack_supplier : '(Kosong)' }}"
                                            @if($isReady)
                                                @mousedown="startSelection({{ $i }})"
                                                @mouseenter="onHover({{ $i }})"
                                                @mouseup="endSelection()"
                                            @endif
                                        >
                                            <span class="relative z-10">{{ $i }}</span>
                                            <!-- Selection Glow Effect -->
                                            <div x-show="isSelected({{ $i }})" x-cloak class="absolute inset-0 bg-white/20 animate-pulse"></div>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                            
                            <!-- Instruction & Error Messages -->
                            <div class="mt-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                                <div class="flex items-center gap-3 text-slate-400 text-xs font-bold uppercase tracking-widest bg-slate-50 px-4 py-3 rounded-xl border border-slate-100">
                                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    Klik & Geser (Drag) untuk memilih kelipatan 4 pack secara berurutan.
                                </div>

                                <div x-show="validationError" x-cloak 
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0 -translate-y-2"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     class="text-rose-600 text-sm font-black py-4 px-6 bg-rose-50 rounded-2xl border-2 border-rose-200 shadow-sm flex items-center gap-3 max-w-md">
                                     <div class="p-1 bg-rose-500 rounded-full shrink-0">
                                         <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                     </div>
                                     <span x-text="validationError"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Form -->
                <div class="w-full lg:w-[380px] shrink-0">
                    <div class="bg-white rounded-3xl shadow-2xl shadow-slate-200/80 border border-slate-100 overflow-hidden sticky top-24">
                        <!-- Dashboard style sumarry -->
                        <div class="px-8 pt-8 pb-4">
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Ringkasan Seleksi</h3>
                            <div class="grid grid-cols-1 gap-4">
                                <div class="bg-gradient-to-br from-indigo-500 to-indigo-700 p-6 rounded-3xl shadow-lg shadow-indigo-100 text-white relative overflow-hidden group">
                                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                                    <div class="relative z-10">
                                        <p class="text-[10px] font-bold text-white/70 uppercase tracking-widest">Pilihan Aktif</p>
                                        <div class="flex items-baseline gap-1 mt-1">
                                            <span class="text-4xl font-black" x-text="selectedPacks.length"></span>
                                            <span class="text-sm font-bold opacity-80">Packs</span>
                                        </div>
                                    </div>
                                    <svg class="absolute top-6 right-6 w-8 h-8 opacity-20" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/></svg>
                                </div>
                                
                                <div class="bg-gradient-to-br from-emerald-500 to-emerald-700 p-6 rounded-3xl shadow-lg shadow-emerald-100 text-white relative overflow-hidden group">
                                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                                    <div class="relative z-10">
                                        <p class="text-[10px] font-bold text-white/70 uppercase tracking-widest">Total Bilyet</p>
                                        <div class="mt-1">
                                            <span class="text-2xl font-black tracking-tight" x-text="formatNumber(totalBilyet)"></span>
                                        </div>
                                    </div>
                                    <svg class="absolute top-6 right-6 w-8 h-8 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                            </div>
                        </div>

                        <div class="p-8 pt-4">
                            <form method="POST" action="{{ route('hcs-sorting-reports.update', $hcs_sorting_report->id) }}" id="sortingForm" @submit="validateSubmission">
                                @csrf
                                @method('PUT')
                                
                                <!-- Hidden inputs for selected packs -->
                                <template x-for="pack in selectedPacks" :key="pack">
                                    <input type="hidden" name="selected_packs[]" :value="pack">
                                </template>

                                <!-- Manual Toggle Slider -->
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100 transition-all duration-300" 
                                     :class="isManual ? 'ring-2 ring-red-500/20 border-red-100 bg-red-50/30' : ''">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 transition-colors duration-300"
                                             :class="isManual ? 'bg-red-500 text-white shadow-lg shadow-red-500/30' : 'bg-gray-200 text-gray-400'">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        </div>
                                        <span class="text-[10px] font-black uppercase tracking-widest transition-colors duration-300"
                                              :class="isManual ? 'text-red-600' : 'text-gray-500'">
                                            Input Sisa Pack (Bukan Kelipatan 4)
                                        </span>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" id="is_manual" name="is_manual" class="sr-only peer" x-model="isManual" @change="validateSelection()">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-500 shadow-inner"></div>
                                    </label>
                                </div>

                                <div class="space-y-5">
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] mb-2 px-1">Supplier Utama</label>
                                        <div class="relative group">
                                            <select id="supplier" name="supplier" class="w-full bg-slate-50 border-2 border-slate-100 focus:border-indigo-500 focus:ring-0 rounded-2xl px-4 py-3 text-sm font-bold text-slate-700 transition-all appearance-none outline-none" required>
                                                <option value="Cutpack" {{ old('supplier', $hcs_sorting_report->supplier) == 'Cutpack' ? 'selected' : '' }}>Cutpack</option>
                                                <option value="Rikyet" {{ old('supplier', $hcs_sorting_report->supplier) == 'Rikyet' ? 'selected' : '' }}>Rikyet</option>
                                            </select>
                                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" /></svg>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] mb-2 px-1">Tahun Emisi & Anggaran</label>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div class="relative group">
                                                <input type="text" name="emisi" value="{{ old('emisi', $hcs_sorting_report->emisi) }}" readonly class="w-full bg-slate-100 border-2 border-slate-100 rounded-2xl px-4 py-3 text-sm font-black text-slate-500 cursor-not-allowed outline-none focus:ring-0">
                                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[10px] font-black text-slate-300">EMISI</span>
                                            </div>
                                            <div class="relative group">
                                                <input type="text" name="tahun_anggaran" value="{{ old('tahun_anggaran', $hcs_sorting_report->tahun_anggaran) }}" readonly class="w-full bg-slate-100 border-2 border-slate-100 rounded-2xl px-4 py-3 text-sm font-black text-slate-500 cursor-not-allowed outline-none focus:ring-0">
                                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[10px] font-black text-slate-300">T.A</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] mb-2 px-1">Petugas Penyortir</label>
                                        <div class="space-y-3">
                                            <div class="relative group">
                                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-indigo-400 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                                </div>
                                                <input id="petugas_1" type="text" name="petugas_1" value="{{ old('petugas_1', $hcs_sorting_report->petugas_1) }}" required placeholder="Nama Petugas 1"
                                                       class="w-full bg-slate-50 border-2 border-slate-100 focus:border-indigo-500 focus:ring-0 rounded-2xl pl-11 pr-4 py-3 text-sm font-bold text-slate-700 transition-all outline-none">
                                            </div>
                                            <div class="relative group">
                                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-indigo-400 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                                </div>
                                                <input id="petugas_2" type="text" name="petugas_2" value="{{ old('petugas_2', $hcs_sorting_report->petugas_2) }}" placeholder="Nama Petugas 2 (Opsional)"
                                                       class="w-full bg-slate-50 border-2 border-slate-100 focus:border-indigo-500 focus:ring-0 rounded-2xl pl-11 pr-4 py-3 text-sm font-bold text-slate-700 transition-all outline-none">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] mb-2 px-1">Tgl Sortir</label>
                                            <input id="tanggal_sortir" type="date" name="tanggal_sortir" value="{{ old('tanggal_sortir', $hcs_sorting_report->tanggal_sortir->format('Y-m-d')) }}" required
                                                   class="w-full bg-slate-50 border-2 border-slate-100 focus:border-indigo-500 focus:ring-0 rounded-2xl px-4 py-3 text-sm font-bold text-slate-700 transition-all outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] mb-2 px-1">Gilir</label>
                                            <div class="relative group">
                                                <select id="gilir" name="gilir" class="w-full bg-slate-50 border-2 border-slate-100 focus:border-indigo-500 focus:ring-0 rounded-2xl px-4 py-3 text-sm font-bold text-slate-700 transition-all appearance-none outline-none" required>
                                                    <option value="Gilir 1" {{ old('gilir', $hcs_sorting_report->gilir) == 'Gilir 1' ? 'selected' : '' }}>Gilir 1</option>
                                                    <option value="Gilir 2" {{ old('gilir', $hcs_sorting_report->gilir) == 'Gilir 2' ? 'selected' : '' }}>Gilir 2</option>
                                                    <option value="Gilir 3" {{ old('gilir', $hcs_sorting_report->gilir) == 'Gilir 3' ? 'selected' : '' }}>Gilir 3</option>
                                                </select>
                                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" /></svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="pt-6 flex flex-col gap-3">
                                        <button type="submit" 
                                                class="w-full py-4 px-6 rounded-2xl shadow-xl shadow-indigo-100 text-sm font-black text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-100 transition-all active:scale-[0.98] disabled:opacity-50 disabled:grayscale disabled:scale-100 disabled:cursor-not-allowed flex items-center justify-center gap-3"
                                                :disabled="selectedPacks.length === 0 || validationError !== ''">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                                            Simpan Perubahan
                                        </button>
                                        <a href="{{ route('hcs-sorting-reports.index') }}" 
                                           class="w-full py-4 px-6 rounded-2xl text-sm font-black text-slate-500 bg-white border-2 border-slate-100 hover:bg-slate-50 hover:border-slate-200 transition-all flex items-center justify-center gap-2">
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

    <!-- Alpine.js script for sorting layout -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('sortingGrid', () => ({
                // Initialize with existing packs
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
                     if(!this.isSelected(num)) {
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
                    this.selectedPacks.sort((a,b) => a-b);
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
                    if(this.validationError !== '' || this.selectedPacks.length === 0) {
                        e.preventDefault();
                        if(this.selectedPacks.length === 0) {
                            this.validationError = 'Silahkan pilih minimal satu kelompok pack (4 pack) terlebih dahulu.';
                        }
                    }
                }
            }));
        });
    </script>
</x-app-layout>
