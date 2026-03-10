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
                            let raw = value.replace(/\./g, '');
                            if (!isNaN(raw) && raw !== '') {
                                this.rawJumlah = raw;
                                this.formattedJumlah = new Intl.NumberFormat('id-ID').format(raw);
                            } else {
                                this.rawJumlah = '';
                                this.formattedJumlah = '';
                            }
                        },
                        formatSeri(value) {
                            let val = value.toUpperCase();
                            if (val.length > 2 && val[2] !== '-') {
                                val = val.slice(0, 2) + '-' + val.slice(2);
                            }
                            this.seriValue = val;
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
                                this.formattedJumlah = new Intl.NumberFormat('id-ID').format(this.rawJumlah);
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

                        <div class="bg-white p-4 rounded-3xl border-2 transition-all duration-500"
                            :class="currentTheme ? currentTheme.border : 'border-gray-100'">

                            <form action="{{ route('hcts-receiving.update', $hcts_receiving->id) }}" method="POST" class="space-y-3">
                                @csrf
                                @method('PUT')

                                <!-- Grid 1: Basic Info -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="nomor_bon" value="Nomor Bon" class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-2 ml-1" />
                                        <x-text-input id="nomor_bon" name="nomor_bon" type="text" class="block w-full border-gray-200 rounded-xl focus:border-rose-500 focus:ring-rose-500 font-bold transition-all py-2 text-center" value="{{ old('nomor_bon', $hcts_receiving->nomor_bon) }}" required />
                                    </div>
                                    <div>
                                        <x-input-label for="tanggal_penerimaan" value="Tanggal Penerimaan" class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-2 ml-1" />
                                        <x-text-input id="tanggal_penerimaan" name="tanggal_penerimaan" type="date" class="block w-full border-gray-200 rounded-xl focus:border-rose-500 focus:ring-rose-500 font-bold transition-all py-2 text-center" value="{{ old('tanggal_penerimaan', $hcts_receiving->tanggal_penerimaan) }}" required />
                                    </div>
                                </div>

                                <!-- Grid 2: Money Specs -->
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-3 p-3 bg-gray-50/50 rounded-3xl border border-gray-100">
                                    <div>
                                        <x-input-label for="pecahan" value="Pecahan" class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-2 ml-1" />
                                        <select id="pecahan" name="pecahan" x-model="selectedPecahan"
                                                class="block w-full border-gray-200 rounded-xl transition-all py-2 text-center font-black"
                                                :class="currentTheme ? (currentTheme.bg + ' ' + currentTheme.text + ' ' + currentTheme.border) : 'focus:border-rose-500 focus:ring-rose-500 font-bold'"
                                                required>
                                            <option value="" class="bg-white text-gray-900">Pilih Pecahan</option>
                                            @foreach(['S'=>'1.000','T'=>'2.000','U'=>'5.000','V'=>'10.000','W'=>'20.000','X'=>'50.000','Y'=>'100.000'] as $key => $val)
                                                <option value="{{ $key }}" class="bg-white text-gray-900" {{ old('pecahan', $hcts_receiving->pecahan) == $key ? 'selected' : '' }}>{{ $key }} ({{ $val }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <x-input-label for="gilir" value="Gilir" class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-2 ml-1" />
                                        <select id="gilir" name="gilir" class="block w-full border-gray-200 rounded-xl focus:border-rose-500 focus:ring-rose-500 font-bold transition-all py-2 text-center" required>
                                            <option value="">Pilih Gilir</option>
                                            <option value="Gilir 1" {{ old('gilir', $hcts_receiving->gilir) == 'Gilir 1' ? 'selected' : '' }}>Gilir 1</option>
                                            <option value="Gilir 2" {{ old('gilir', $hcts_receiving->gilir) == 'Gilir 2' ? 'selected' : '' }}>Gilir 2</option>
                                            <option value="Gilir 3" {{ old('gilir', $hcts_receiving->gilir) == 'Gilir 3' ? 'selected' : '' }}>Gilir 3</option>
                                        </select>
                                    </div>
                                    <div>
                                        <x-input-label for="emisi" value="Tahun Emisi" class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-2 ml-1" />
                                        <select id="emisi" name="emisi" x-model="emisiValue" class="block w-full border-gray-200 rounded-xl focus:border-rose-500 focus:ring-rose-500 font-bold transition-all py-2 text-center" required>
                                            <option value="2016">2016</option>
                                            <option value="2022">2022</option>
                                        </select>
                                    </div>
                                    <div>
                                        <x-input-label for="tahun_anggaran" value="Tahun Anggaran" class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-2 ml-1" />
                                        <select id="tahun_anggaran" name="tahun_anggaran" x-model="tahunAnggaranValue" class="block w-full border-gray-200 rounded-xl focus:border-rose-500 focus:ring-rose-500 font-bold transition-all py-2 text-center" required>
                                            @foreach(['2024','2025','2026','2027'] as $yr)
                                                <option value="{{ $yr }}">{{ $yr }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Grid 3: Batch & Series -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="batch" value="Batch" class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-2 ml-1" />
                                        <x-text-input id="batch" name="batch" type="text" maxlength="10" placeholder="Contoh: 1322019" x-model="batchValue" class="block w-full border-gray-200 rounded-xl focus:border-rose-500 focus:ring-rose-500 font-bold transition-all py-2 text-center" required />
                                    </div>
                                    <div>
                                        <x-input-label for="seri" value="Seri" class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-2 ml-1" />
                                        <x-text-input id="seri" name="seri" type="text" placeholder="Contoh: TF-AU9" 
                                            x-model="seriValue"
                                            @input="formatSeri($event.target.value)"
                                            class="block w-full border-gray-200 rounded-xl focus:border-rose-500 focus:ring-rose-500 font-bold transition-all py-3 uppercase text-center" required />
                                    </div>
                                </div>

                                <!-- Grid 4: Amount & Seal -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="jumlah_display" value="Jumlah (Bilyet)" class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-2 ml-1" />
                                        <div class="relative">
                                            <x-text-input id="jumlah_display" type="text" 
                                                x-model="formattedJumlah"
                                                @input="formatJumlah($event.target.value)"
                                                placeholder="0"
                                                ::class="(hcsTotal + (parseInt(rawJumlah) || 0)) > 4500000 ? 'border-rose-500 ring-rose-500 text-rose-600' : 'border-gray-200 text-gray-600'"
                                                class="block w-full rounded-xl focus:border-rose-500 focus:ring-rose-500 font-black transition-all py-2 text-center" required />
                                            <input type="hidden" name="jumlah" x-model="rawJumlah">
                                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">Bil</div>
                                            
                                            <!-- Real-time HCS Display -->
                                            <div class="absolute right-0 -top-6 flex items-center gap-2">
                                                <span class="text-[9px] font-black uppercase tracking-widest text-gray-400">Total HCS:</span>
                                                <span class="text-[10px] font-black text-rose-600" x-show="!isLoadingHcs" x-text="new Intl.NumberFormat('id-ID').format(hcsTotal)"></span>
                                                <span class="text-[9px] font-black text-rose-400 animate-pulse" x-show="isLoadingHcs">Loading...</span>
                                            </div>
                                        </div>
                                        <p class="text-[10px] mt-2 font-bold uppercase italic flex justify-between items-center" 
                                           :class="(hcsTotal + (parseInt(rawJumlah) || 0)) > 4500000 ? 'text-rose-600' : 'text-gray-400'">
                                            <span>* Pastikan ttal HCS + total HCTS batch ini ≤ 4.500.000</span>
                                            <span x-show="hcsTotal > 0 || rawJumlah > 0" class="font-black bg-rose-50 px-2 py-0.5 rounded-full">
                                                Total: <span x-text="new Intl.NumberFormat('id-ID').format(hcsTotal + (parseInt(rawJumlah) || 0))"></span> / 4.500.000
                                            </span>
                                        </p>
                                    </div>
                                    <div>
                                        <x-input-label for="nomor_segel" value="Nomor Segel" class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-2 ml-1" />
                                        <x-text-input id="nomor_segel" name="nomor_segel" type="text" class="block w-full border-gray-200 rounded-xl focus:border-rose-500 focus:ring-rose-500 font-bold transition-all py-2 text-center" value="{{ old('nomor_segel', $hcts_receiving->nomor_segel) }}" required />
                                    </div>
                                </div>

                                <div class="flex items-center justify-end pt-3 border-t border-gray-100 gap-4">
                                    <a href="{{ route('hcts-receiving.index') }}" class="text-xs font-black uppercase tracking-widest text-gray-400 hover:text-gray-600 transition-colors">Batal</a>
                                    <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-black px-10 py-4 rounded-2xl shadow-xl shadow-rose-100 transition-all active:scale-95 uppercase text-xs tracking-widest"
                                            :class="currentTheme ? currentTheme.btn : 'bg-rose-600'">
                                        Simpan Perubahan HCTS
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
