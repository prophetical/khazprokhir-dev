<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Penerimaan HCTS') }}
        </h2>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl border border-gray-100">
                <div class="p-4 text-gray-900">
                    @php
                        $selectedPecahan = old('pecahan', $hcts_receiving->pecahan);
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
                        get currentTheme() { return this.themes[this.selectedPecahan] || null },
                        rawJumlah: '{{ old('jumlah', $hcts_receiving->jumlah) }}',
                        formattedJumlah: '',
                        seriValue: '{{ old('seri', $hcts_receiving->seri) }}',
                        batchValue: '{{ old('batch', $hcts_receiving->batch) }}',
                        emisiValue: '{{ old('emisi', $hcts_receiving->emisi) }}',
                        tahunAnggaranValue: '{{ old('tahun_anggaran', $hcts_receiving->tahun_anggaran) }}',
                        hcsTotal: 0,
                        isLoadingHcs: false,
                        
                        formatJumlah(value) {
                            let raw = value.replace(/\D/g, '');
                            this.rawJumlah = raw;
                            this.formattedJumlah = this.numberFormat(raw);
                            if (this.rawJumlah === '0' || this.rawJumlah === '') this.formattedJumlah = '';
                        },
                        numberFormat(x) {
                            if (!x && x !== 0) return "";
                            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        },
                        formatSeri(value) {
                            // Strip non-alphanumeric, convert to uppercase
                            let val = value.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
                            let formatted = '';
                            
                            for (let i = 0; i < val.length && formatted.length < 6; i++) {
                                let char = val[i];
                                if (formatted.length === 0 || formatted.length === 1 || formatted.length === 3 || formatted.length === 4) {
                                    if (/[A-Z]/.test(char)) formatted += char;
                                } else if (formatted.length === 5) {
                                    if (/[0-9]/.test(char)) formatted += char;
                                }
                                
                                if (formatted.length === 2 && i < val.length - 1) {
                                    formatted += '-';
                                }
                            }

                            this.seriValue = formatted;
                            this.fetchHcsTotal();
                        },
                        async fetchHcsTotal() {
                            if (this.selectedPecahan && this.batchValue && this.seriValue && this.emisiValue && this.tahunAnggaranValue) {
                                this.isLoadingHcs = true;
                                try {
                                    const params = new URLSearchParams({
                                        pecahan: this.selectedPecahan,
                                        batch: this.batchValue,
                                        seri: this.seriValue,
                                        emisi: this.emisiValue,
                                        tahun_anggaran: this.tahunAnggaranValue
                                    });
                                    const response = await fetch(`{{ route('hcts-receiving.get-hcs-total') }}?${params}`);
                                    const data = await response.json();
                                    this.hcsTotal = data.total;
                                } catch (error) {
                                    console.error('Error fetching HCS total:', error);
                                } finally {
                                    this.isLoadingHcs = false;
                                }
                            }
                        },
                        init() {
                            if (this.rawJumlah) {
                                this.formattedJumlah = this.numberFormat(this.rawJumlah);
                            }
                            this.fetchHcsTotal(); // Initial fetch
                            this.$watch('selectedPecahan', () => this.fetchHcsTotal());
                            this.$watch('batchValue', () => this.fetchHcsTotal());
                            this.$watch('emisiValue', () => this.fetchHcsTotal());
                            this.$watch('tahunAnggaranValue', () => this.fetchHcsTotal());
                        }
                    }">
                        <div class="mb-4 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="bg-rose-600 p-3 rounded-2xl shadow-lg shadow-rose-200" :class="currentTheme ? currentTheme.btn : 'bg-rose-600 shadow-rose-200'">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-gray-900 uppercase tracking-tighter">Edit Data HCTS</h3>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Sistem Pengelolaan HCTS — Khazprokhir</p>
                                </div>
                            </div>
                        </div>

                        @if ($errors->any())
                            <div class="mb-4 p-4 bg-rose-50 border-l-4 border-rose-500 rounded-2xl">
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 text-rose-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                    <p class="text-sm font-black text-rose-700 uppercase tracking-widest">Terdapat Kesalahan Input</p>
                                </div>
                                <ul class="list-disc list-inside text-xs text-rose-600 font-bold ml-7">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <div class="bg-gray-50/50 p-6 rounded-[2rem] border transition-all duration-500"
                            :class="currentTheme ? currentTheme.border : 'border-gray-100'">

                            <form action="{{ route('hcts-receiving.update', $hcts_receiving->id) }}" method="POST" class="space-y-6">
                                @csrf
                                @method('PUT')

                                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                                    <!-- LEFT COLUMN: Informasi Administrasi (Sidebar style) -->
                                    <div class="lg:col-span-4 space-y-6">
                                        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm relative overflow-hidden h-full">
                                            <div class="absolute top-0 left-0 w-1 h-full" :class="currentTheme ? currentTheme.bg : 'bg-rose-500'"></div>
                                            <div class="flex items-center gap-3 mb-6">
                                                <div class="p-2 rounded-xl bg-gray-50 text-gray-400">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                                </div>
                                                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Administrasi</span>
                                            </div>
                                            
                                            <div class="space-y-4">
                                                <div class="relative group">
                                                    <x-input-label for="nomor_bon" value="Nomor Bon" class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-1 ml-1" />
                                                    <x-text-input id="nomor_bon" name="nomor_bon" type="text" 
                                                        class="block w-full border-gray-100 bg-gray-50/30 rounded-2xl focus:bg-white transition-all py-2 text-center font-black group-hover:border-gray-300" 
                                                        x-bind:class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-rose-500 focus:ring-rose-500'"
                                                        value="{{ old('nomor_bon', $hcts_receiving->nomor_bon) }}" required />
                                                </div>
                                                <div class="relative group">
                                                    <x-input-label for="tanggal_penerimaan" value="Tanggal Penerimaan" class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-1 ml-1" />
                                                    <x-text-input id="tanggal_penerimaan" name="tanggal_penerimaan" type="date" 
                                                        class="block w-full border-gray-100 bg-gray-50/30 rounded-2xl focus:bg-white transition-all py-2 text-center font-black group-hover:border-gray-300" 
                                                        x-bind:class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-rose-500 focus:ring-rose-500'"
                                                        value="{{ old('tanggal_penerimaan', $hcts_receiving->tanggal_penerimaan) }}" required />
                                                </div>
                                                <div class="group-inner p-3 bg-gray-50/50 rounded-2xl border border-dashed border-gray-200">
                                                    <x-input-label for="nomor_segel" value="Nomor Segel" class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-1 ml-1" />
                                                    <x-text-input id="nomor_segel" name="nomor_segel" type="text" 
                                                        class="block w-full border-gray-100 bg-white rounded-xl focus:border-rose-500 focus:ring-rose-500 font-bold transition-all py-2 text-center" 
                                                        value="{{ old('nomor_segel', $hcts_receiving->nomor_segel) }}" required />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- RIGHT COLUMN: Detail Spesifikasi & Amount -->
                                    <div class="lg:col-span-8 space-y-6">
                                        <!-- Detailed Specs Row -->
                                        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm relative overflow-hidden">
                                            <div class="absolute top-0 left-0 w-1 h-full" :class="currentTheme ? currentTheme.bg : 'bg-rose-500'"></div>
                                            <div class="flex items-center gap-3 mb-6">
                                                <div class="p-2 rounded-xl bg-gray-50 text-gray-400">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                </div>
                                                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Spesifikasi Detail</span>
                                            </div>

                                            <div class="space-y-6">
                                                <!-- Consolidated Grid 1: Basic Specs -->
                                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                                    <div>
                                                        <x-input-label for="pecahan" value="Pecahan" class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-1 ml-1" />
                                                        <select id="pecahan" name="pecahan" x-model="selectedPecahan"
                                                                class="block w-full border-gray-100 rounded-xl transition-all py-1.5 text-center font-black"
                                                                :class="currentTheme ? (currentTheme.bg + ' ' + currentTheme.text + ' ' + currentTheme.border) : 'focus:border-rose-500 focus:ring-rose-500'"
                                                                required>
                                                            <option value="" class="bg-white text-gray-900">Pilih Pecahan</option>
                                                            @foreach(['S'=>'1.000','T'=>'2.000','U'=>'5.000','V'=>'10.000','W'=>'20.000','X'=>'50.000','Y'=>'100.000'] as $key => $val)
                                                                <option value="{{ $key }}" class="bg-white text-gray-900" {{ old('pecahan', $hcts_receiving->pecahan) == $key ? 'selected' : '' }}>{{ $key }} ({{ $val }})</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <x-input-label for="gilir" value="Gilir" class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-1 ml-1" />
                                                        <select id="gilir" name="gilir" class="block w-full border-gray-100 rounded-xl bg-white transition-all py-1.5 text-center font-black shadow-sm" 
                                                            :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-rose-500 focus:ring-rose-500'" required>
                                                            <option value="">Pilih Gilir</option>
                                                            @foreach(['Gilir 1', 'Gilir 2', 'Gilir 3'] as $g)
                                                                <option value="{{ $g }}" {{ old('gilir', $hcts_receiving->gilir) == $g ? 'selected' : '' }}>{{ $g }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <x-input-label for="emisi" value="Emisi" class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-1 ml-1" />
                                                        <select id="emisi" name="emisi" x-model="emisiValue" class="block w-full border-gray-100 rounded-xl bg-white transition-all py-1.5 text-center font-black shadow-sm" 
                                                            :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-rose-500 focus:ring-rose-500'" required>
                                                            <option value="2016">2016</option>
                                                            <option value="2022">2022</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <x-input-label for="tahun_anggaran" value="Tahun Anggaran" class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-1 ml-1" />
                                                        <select id="tahun_anggaran" name="tahun_anggaran" x-model="tahunAnggaranValue" class="block w-full border-gray-100 rounded-xl bg-white transition-all py-1.5 text-center font-black shadow-sm" 
                                                            :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-rose-500 focus:ring-rose-500'" required>
                                                            @foreach(['2024','2025','2026','2027'] as $yr)
                                                                <option value="{{ $yr }}">{{ $yr }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- Consolidated Grid 2: Batch & Seri -->
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div class="group">
                                                        <x-input-label for="batch" value="Batch" class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-1 ml-1" />
                                                        <x-text-input id="batch" name="batch" type="text" maxlength="10" placeholder="INPUT BATCH" x-model="batchValue" 
                                                            class="block w-full border-gray-100 bg-gray-50/30 rounded-2xl focus:bg-white transition-all py-2 text-center font-black group-hover:border-gray-300" 
                                                            x-bind:class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-rose-500 focus:ring-rose-500'" required />
                                                    </div>
                                                    <div class="group">
                                                        <x-input-label for="seri" value="Seri" class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-1 ml-1" />
                                                        <x-text-input id="seri" name="seri" type="text" placeholder="Format: AA-AA1" maxlength="6"
                                                            x-model="seriValue"
                                                            @input="formatSeri($event.target.value)"
                                                            class="block w-full border-gray-100 bg-gray-50/30 rounded-2xl focus:bg-white transition-all py-2 uppercase text-center font-black group-hover:border-gray-300" 
                                                            x-bind:class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-rose-500 focus:ring-rose-500'" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Amount & Limit Progress Row -->
                                        <div class="p-6 bg-rose-50/50 rounded-3xl border border-rose-100 relative group transition-all"
                                             :class="currentTheme ? ('bg-' + currentTheme.soft.replace('bg-', '') + '/50 border-' + currentTheme.border.replace('border-', '') + '/20') : ''">
                                            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                                                <div class="md:col-span-5 space-y-4">
                                                    <div>
                                                        <x-input-label for="jumlah_display" value="Jumlah Bilyet" class="text-[11px] font-black uppercase text-rose-600/70 tracking-widest mb-1.5 ml-1" 
                                                                       x-bind:class="currentTheme ? currentTheme.icon : 'text-rose-600/70'"/>
                                                        <div class="relative">
                                                            <x-text-input id="jumlah_display" type="text" 
                                                                x-model="formattedJumlah"
                                                                @input="formatJumlah($event.target.value)"
                                                                placeholder="0"
                                                                x-bind:class="(hcsTotal + (parseInt(rawJumlah) || 0)) > 4500000 ? 'border-rose-500 ring-rose-500 text-rose-600 bg-white' : 'border-gray-300 text-gray-900 bg-white'"
                                                                class="block w-full rounded-2xl focus:border-rose-500 focus:ring-rose-500 font-black transition-all py-2.5 text-center text-xl" required />
                                                            <input type="hidden" name="jumlah" x-model="rawJumlah">
                                                            <div class="absolute left-6 top-1/2 -translate-y-1/2 text-gray-300 font-black text-[10px] uppercase tracking-widest">Bil</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="md:col-span-7 space-y-3">
                                                    <div class="flex justify-between items-end mb-1">
                                                        <div>
                                                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Akumulasi Batch</p>
                                                            <p class="text-sm font-black" :class="(hcsTotal + (parseInt(rawJumlah) || 0)) > 4500000 ? 'text-rose-600' : 'text-gray-900'">
                                                                <span x-text="new Intl.NumberFormat('id-ID').format(hcsTotal + (parseInt(rawJumlah) || 0))"></span>
                                                                <span class="text-gray-300 font-bold">/ 4.500.000</span>
                                                            </p>
                                                        </div>
                                                        <div class="text-right">
                                                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Sisa Kuota</p>
                                                            <p class="text-sm font-black text-emerald-500" x-text="new Intl.NumberFormat('id-ID').format(Math.max(0, 4500000 - (hcsTotal + (parseInt(rawJumlah) || 0))))"></p>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Visual Progress Bar -->
                                                    <div class="h-4 bg-white/80 rounded-full overflow-hidden p-1 shadow-inner border border-gray-100">
                                                        <div class="h-full rounded-full transition-all duration-700 relative overflow-hidden"
                                                             :style="`width: ${Math.min(100, ((hcsTotal + (parseInt(rawJumlah) || 0)) / 4500000) * 100)}%`"
                                                             :class="(hcsTotal + (parseInt(rawJumlah) || 0)) > 4500000 ? 'bg-rose-500' : (currentTheme ? currentTheme.bg : 'bg-rose-500')">
                                                            <div class="absolute inset-0 bg-white/20 animate-shimmer" style="background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent); background-size: 200% 100%;"></div>
                                                        </div>
                                                    </div>

                                                    <div class="grid grid-cols-2 gap-3">
                                                        <div class="bg-white/60 px-3 py-2 rounded-xl border border-white/50 shadow-sm flex justify-between items-center">
                                                            <span class="text-[9px] font-black text-gray-400 uppercase">HCS</span>
                                                            <span class="text-xs font-black text-blue-600" x-show="!isLoadingHcs" x-text="new Intl.NumberFormat('id-ID').format(hcsTotal)"></span>
                                                            <span class="text-[9px] font-black text-blue-400 animate-pulse" x-show="isLoadingHcs">...</span>
                                                        </div>
                                                        <div class="bg-white/60 px-3 py-2 rounded-xl border border-white/50 shadow-sm flex justify-between items-center">
                                                            <span class="text-[9px] font-black text-gray-400 uppercase">HCTS</span>
                                                            <span class="text-xs font-black" :class="currentTheme ? currentTheme.icon : 'text-rose-600'" x-text="new Intl.NumberFormat('id-ID').format(parseInt(rawJumlah) || 0)"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-end pt-4 gap-6 border-t border-gray-100">
                                    <a href="{{ route('hcts-receiving.index') }}" class="text-xs font-black uppercase tracking-[0.2em] text-gray-400 hover:text-gray-600 transition-colors flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        Batal
                                    </a>
                                    <button type="submit" class="text-white font-black px-12 py-5 rounded-[1.5rem] shadow-2xl transition-all active:scale-95 uppercase text-xs tracking-[0.3em] flex items-center gap-3 group"
                                            :class="currentTheme ? (currentTheme.btn + ' shadow-' + currentTheme.bg.replace('bg-', '') + '/30') : 'bg-rose-600 shadow-rose-200'">
                                        <span>Simpan Perubahan</span>
                                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
