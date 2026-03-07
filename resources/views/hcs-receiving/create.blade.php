<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Data Penerimaan HCS') }}
        </h2>
    </x-slot>

    <div class="py-6 bg-gray-50/30 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/70 backdrop-blur-xl overflow-hidden shadow-[0_20px_50px_-12px_rgba(0,0,0,0.1)] sm:rounded-3xl border border-white">
                    @php
                        $selectedPecahan = old('pecahan', request('pecahan', ''));
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
                    }" 
                    class="border-t-8 transition-all duration-700"
                    :class="currentTheme ? currentTheme.border : 'border-indigo-500'">
                        <form id="hcs-form" action="{{ route('hcs-receiving.store') }}" method="POST">
                        @csrf
                        
                            <div class="flex flex-col lg:flex-row gap-0">
                                
                                <!-- Left: Form Fields -->
                                <div class="w-full lg:w-[45%] p-6 lg:p-8 border-r border-gray-100 bg-white/40">
                                    <div class="mb-8 flex items-center justify-between">
                                        <h3 class="text-xl font-black text-gray-900 tracking-tight flex items-center">
                                            <span class="w-2 h-8 mr-4 rounded-full transition-all duration-700" :class="currentTheme ? currentTheme.bg : 'bg-indigo-500'"></span>
                                            Detail Penerimaan
                                        </h3>
                                        <div class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border transition-all duration-700"
                                            :class="currentTheme ? (currentTheme.border + ' ' + currentTheme.icon + ' ' + currentTheme.soft) : 'border-indigo-200 text-indigo-600 bg-indigo-50'">
                                            HCS
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5">
                                        <div class="space-y-1.5">
                                            <label for="nomor_bon" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Nomor Bon</label>
                                            <input id="nomor_bon" name="nomor_bon" type="text" 
                                                   class="block w-full py-2.5 px-4 border-gray-200 rounded-xl bg-white/50 backdrop-blur-sm shadow-sm transition-all duration-300 focus:ring-4 placeholder-gray-300 font-bold text-sm"
                                                   :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring.replace('ring-', 'ring-').replace('focus:', '')) : 'focus:border-indigo-500 focus:ring-indigo-500/20'" 
                                                   value="{{ old('nomor_bon') }}" placeholder="Nomor Bon" required />
                                        </div>

                                        <div class="space-y-1.5">
                                            <label for="tanggal_penerimaan" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Tanggal Penerimaan</label>
                                            <input id="tanggal_penerimaan" name="tanggal_penerimaan" type="date" 
                                                   class="block w-full py-2.5 px-4 border-gray-200 rounded-xl bg-white/50 backdrop-blur-sm shadow-sm transition-all duration-300 focus:ring-4 font-bold text-sm"
                                                   :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring.replace('focus:', '')) : 'focus:border-indigo-500 focus:ring-indigo-500/20'" 
                                                   value="{{ old('tanggal_penerimaan', date('Y-m-d')) }}" required />
                                        </div>

                                        <div class="space-y-1.5">
                                            <label for="pecahan" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Pecahan</label>
                                            <select id="pecahan" name="pecahan" x-model="selectedPecahan"
                                                    class="block w-full py-2.5 px-4 border-gray-200 rounded-xl bg-white/50 backdrop-blur-sm shadow-sm transition-all duration-300 focus:ring-4 font-black text-sm" 
                                                    :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring.replace('focus:', '')) : 'focus:border-indigo-500 focus:ring-indigo-500/20 text-indigo-600'"
                                                    required>
                                                <option value="">Pilih Pecahan</option>
                                                @foreach(['S' => '1.000', 'T' => '2.000', 'U' => '5.000', 'V' => '10.000', 'W' => '20.000', 'X' => '50.000', 'Y' => '100.000'] as $key => $label)
                                                    <option value="{{ $key }}">{{ $key }} - {{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="space-y-1.5">
                                            <label for="jumlah_display" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Jumlah Bilyet</label>
                                            <div class="relative">
                                                <input id="jumlah_display" type="tel" 
                                                    class="block w-full py-3 px-4 text-right font-black text-xl pr-14 border-gray-200 rounded-xl bg-white/50 backdrop-blur-sm shadow-sm transition-all duration-300 focus:ring-4 placeholder-gray-300"
                                                    :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring.replace('focus:', '')) : 'focus:border-indigo-500 focus:ring-indigo-500/20'" 
                                                    value="{{ old('jumlahDisplay') }}" placeholder="0" required />
                                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-300 font-bold text-xs">Bilyet</div>
                                            </div>
                                            <input type="hidden" id="jumlah_original" name="jumlah" value="{{ old('jumlah', 0) }}">
                                        </div>

                                        <div class="space-y-1.5">
                                            <label for="gilir" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Gilir</label>
                                            <select id="gilir" name="gilir" 
                                                    class="block w-full py-2.5 px-4 border-gray-200 rounded-xl bg-white/50 backdrop-blur-sm shadow-sm transition-all duration-300 focus:ring-4 font-bold text-sm"
                                                    :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring.replace('focus:', '')) : 'focus:border-indigo-500 focus:ring-indigo-500/20'" 
                                                    required>
                                                <option value="">Pilih Gilir</option>
                                                <option value="Gilir 1" {{ old('gilir') == 'Gilir 1' ? 'selected' : '' }}>Gilir 1</option>
                                                <option value="Gilir 2" {{ old('gilir') == 'Gilir 2' ? 'selected' : '' }}>Gilir 2</option>
                                                <option value="Gilir 3" {{ old('gilir') == 'Gilir 3' ? 'selected' : '' }}>Gilir 3</option>
                                            </select>
                                        </div>

                                        <div class="space-y-1.5">
                                            <label for="mesin" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Mesin</label>
                                            <input id="mesin" name="mesin" type="text" 
                                                   class="block w-full py-2.5 px-4 border-gray-200 rounded-xl bg-white/50 backdrop-blur-sm shadow-sm transition-all duration-300 focus:ring-4 placeholder-gray-300 font-bold text-sm"
                                                   :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring.replace('focus:', '')) : 'focus:border-indigo-500 focus:ring-indigo-500/20'" 
                                                   value="{{ old('mesin') }}" placeholder="Mesin" required />
                                        </div>

                                        <div class="space-y-1.5">
                                            <label for="supplier" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Supplier</label>
                                            <select id="supplier" name="supplier" 
                                                    class="block w-full py-2.5 px-4 border-gray-200 rounded-xl bg-white/50 backdrop-blur-sm shadow-sm transition-all duration-300 focus:ring-4 font-bold text-sm"
                                                    :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring.replace('focus:', '')) : 'focus:border-indigo-500 focus:ring-indigo-500/20'" 
                                                    required>
                                                <option value="">Pilih Supplier</option>
                                                <option value="Cutpack" {{ old('supplier') == 'Cutpack' ? 'selected' : '' }}>Cutpack</option>
                                                <option value="Rikyet" {{ old('supplier') == 'Rikyet' ? 'selected' : '' }}>Rikyet</option>
                                            </select>
                                        </div>

                                        <div class="space-y-1.5">
                                            <label for="batch" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Batch</label>
                                            <input id="batch" name="batch" type="text" maxlength="7" 
                                                   class="block w-full py-2.5 px-4 font-mono uppercase border-gray-200 rounded-xl bg-white/50 backdrop-blur-sm shadow-sm transition-all duration-300 focus:ring-4 tracking-tighter placeholder-gray-300 font-bold text-sm"
                                                   :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring.replace('focus:', '')) : 'focus:border-indigo-500 focus:ring-indigo-500/20'" 
                                                   value="{{ old('batch', request('batch')) }}" placeholder="0000000" required />
                                        </div>

                                        <div class="space-y-1.5">
                                            <label for="seri" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Seri</label>
                                            <input id="seri" name="seri" type="text" 
                                                   class="block w-full py-2.5 px-4 font-mono uppercase border-gray-200 rounded-xl bg-white/50 backdrop-blur-sm shadow-sm transition-all duration-300 focus:ring-4 tracking-[0.2em] placeholder-gray-300 font-bold text-sm"
                                                   :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring.replace('focus:', '')) : 'focus:border-indigo-500 focus:ring-indigo-500/20'" 
                                                   placeholder="Seri" value="{{ old('seri', request('seri')) }}" required />
                                        </div>

                                        <div class="space-y-1.5">
                                            <label for="emisi" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Emisi (Tahun)</label>
                                            <input id="emisi" name="emisi" type="number" min="2000" max="2100" 
                                                   class="block w-full py-2.5 px-4 border-gray-200 rounded-xl bg-white/50 backdrop-blur-sm shadow-sm transition-all duration-300 focus:ring-4 placeholder-gray-300 font-bold text-sm"
                                                   :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring.replace('focus:', '')) : 'focus:border-indigo-500 focus:ring-indigo-500/20'" 
                                                   value="{{ old('emisi', date('Y')) }}" required />
                                        </div>
                                        
                                        <div class="col-span-1 sm:col-span-2 mt-4">
                                            <div class="p-6 rounded-[2rem] border border-gray-100 bg-white/80 shadow-inner flex flex-col sm:flex-row items-center justify-between gap-6 overflow-hidden relative group">
                                                <div class="absolute -right-4 -top-4 w-24 h-24 bg-gray-50 rounded-full blur-3xl transition-all duration-700 group-hover:bg-indigo-50"></div>
                                                
                                                <div class="flex items-center relative gap-4">
                                                    <div class="relative inline-flex items-center cursor-pointer group">
                                                        <input id="repass" name="repass" value="repass" type="checkbox" 
                                                            class="w-5 h-5 rounded-lg border-gray-300 shadow-sm transition-all duration-300 text-indigo-600 focus:ring-indigo-500" 
                                                            {{ old('repass') ? 'checked' : '' }}>
                                                        <label for="repass" class="ml-3 text-sm font-bold text-gray-600 cursor-pointer">Tandai sebagai Repass</label>
                                                    </div>
                                                    <div class="h-8 w-[1px] bg-gray-100 hidden sm:block"></div>
                                                    <div class="text-[10px] font-black uppercase tracking-widest text-gray-400 leading-tight">
                                                        Total Packs:<br>
                                                        <span id="packs_needed_display" class="text-xl transition-colors duration-500" :class="currentTheme ? currentTheme.icon : 'text-indigo-600'">0</span>
                                                    </div>
                                                </div>

                                                <button type="submit" 
                                                        class="w-full sm:w-auto inline-flex justify-center items-center py-3 px-8 border border-transparent shadow-xl text-xs font-black rounded-xl transition-all duration-500 uppercase tracking-[0.2em] relative overflow-hidden group"
                                                        :class="currentTheme ? (currentTheme.btn + ' ' + currentTheme.text) : 'bg-indigo-600 text-white hover:bg-indigo-700'">
                                                    <span>Simpan Penerimaan</span>
                                                    <svg class="ml-2 w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Right: Pack Grid -->
                                <div class="w-full lg:w-[55%] p-8 lg:p-10 bg-gray-50/20 backdrop-blur-sm">
                                    <div class="mb-1">
                                        <h3 class="text-xl font-black text-gray-900 tracking-tight flex items-center">
                                            <svg class="w-6 h-6 mr-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                            Tabel Penerimaan HCS
                                        </h3>
                                    </div>
                                    
                                    <!-- Summary Cards -->
                                    <div class="grid grid-cols-2 gap-2 mb-1">
                                        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm transition-all duration-500 hover:shadow-md">
                                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-1">Pack Dipilih</span>
                                            <div class="flex items-end gap-1">
                                                <span id="selected_packs_length" class="text-xl font-black leading-none transition-colors duration-500" :class="currentTheme ? currentTheme.icon : 'text-indigo-600'">0</span>
                                                <span class="text-[10px] font-bold text-gray-300 mb-1 uppercase tracking-tight">Pack</span>
                                            </div>
                                        </div>
                                        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-1">Dibutuhkan</span>
                                            <div class="flex items-end gap-1">
                                                <span id="packs_needed_length" class="text-xl font-black leading-none text-gray-900">0</span>
                                                <span class="text-[10px] font-bold text-gray-300 mb-1 uppercase tracking-tight">Pack</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Legend -->
                                    <div class="bg-white/40 p-4 rounded-xl border border-white mb-1">
                                        <h4 class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3 ml-1">Keterangan</h4>
                                        <div class="flex flex-wrap gap-x-6 gap-y-3">
                                            <div class="flex items-center gap-1"><div class="w-4 h-4 bg-white border border-gray-200 rounded-lg shadow-sm"></div> <span class="text-[10px] font-bold text-gray-600">Kosong</span></div>
                                            <div class="flex items-center gap-1"><div class="w-4 h-4 bg-blue-300 rounded-lg shadow-sm"></div> <span class="text-[10px] font-bold text-gray-600">Cutpack</span></div>
                                            <div class="flex items-center gap-1"><div class="w-4 h-4 bg-green-300 rounded-lg shadow-sm"></div> <span class="text-[10px] font-bold text-gray-600">Rikyet</span></div>
                                            <div class="flex items-center gap-1"><div class="w-4 h-4 bg-red-400 border border-red-500 rounded-lg shadow-lg shadow-red-500/20"></div> <span class="text-[10px] font-bold text-gray-600">Tersortir</span></div>
                                            <div class="flex items-center gap-1">
                                                <div class="flex -space-x-2">
                                                    <div class="w-4 h-4 bg-blue-100 border border-blue-200 rounded-full"></div>
                                                    <div class="w-4 h-4 bg-green-100 border border-green-200 rounded-full"></div>
                                                </div>
                                                <span class="text-[10px] font-bold text-gray-400 ml-3">Siap Sortir</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Hidden Inputs to submit the array -->
                                    <div id="hidden_packs_container"></div>

                                    <!-- Grid Container -->
                                    <div class="relative group/grid">
                                        <div class="absolute inset-0 bg-indigo-500/5 blur-[100px] rounded-full opacity-0 group-hover/grid:opacity-100 transition-opacity duration-1000"></div>
                                        <div class="grid grid-rows-10 grid-flow-col gap-1 sm:gap-1.5 auto-cols-[minmax(0,_1fr)] relative" id="pack_grid">
                                            @for ($i = 1; $i <= 100; $i++)
                                                <button 
                                                    type="button" 
                                                    data-pack="{{ $i }}"
                                                    class="pack-btn aspect-square flex items-center justify-center text-[10px] sm:text-xs font-black rounded-lg transition-all duration-300 bg-white text-gray-400 border border-gray-100 shadow-sm hover:scale-105 hover:z-10 focus:outline-none focus:ring-4"
                                                    :class="currentTheme ? (currentTheme.ring.replace('focus:', '')) : 'focus:ring-indigo-500/20'">
                                                    <span>{{ $i }}</span>
                                                </button>
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

            let jumlahOriginal = parseInt(inputJumlahOriginal.value, 10) || 0;
            let packsNeeded = 0;
            let selectedPacks = {!! json_encode(array_map('intval', old('packs', []))) !!} || [];
            let usedPacks = []; // array of { pack_number, supplier }

            function init() {
                if (jumlahOriginal > 0) {
                    inputJumlahDisplay.value = jumlahOriginal.toLocaleString('id-ID');
                }
                updateCalculations(jumlahOriginal);
                
                if (inputBatch.value.length === 7 && inputSeri.value.length > 5) {
                    fetchUsedPacks();
                }
                renderGrid();
            }

            function handleJumlahInput(e) {
                let textValue = String(e.target.value);
                let rawDigits = textValue.replace(/\D/g, '');
                let number = parseInt(rawDigits, 10) || 0;
                
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

            function updateCalculations(numVal) {
                packsNeeded = Math.floor(numVal / 45000) || 0;
                spanPacksNeededDisplay.textContent = packsNeeded;
                spanPacksNeededLength.textContent = packsNeeded;
                
                if (selectedPacks.length > packsNeeded) {
                    selectedPacks = selectedPacks.slice(0, packsNeeded);
                }
            }

            function fetchUsedPacks() {
                const batchVal = inputBatch.value.toUpperCase();
                const seriVal = inputSeri.value.toUpperCase();
                
                if (batchVal.length === 7 && seriVal.length > 0) {
                    fetch(`/api/packs/used?batch=${batchVal}&seri=${seriVal}`)
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
                // Update hidden inputs
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

                // Update button classes
                const buttons = gridContainer.querySelectorAll('.pack-btn');
                buttons.forEach(btn => {
                    const num = parseInt(btn.getAttribute('data-pack'), 10);
                    
                    // Reset to base classes
                    btn.className = 'pack-btn aspect-square flex items-center justify-center text-[10px] sm:text-xs font-black rounded-lg transition-all duration-300 bg-white text-gray-400 border border-gray-100 shadow-sm hover:scale-110 hover:z-10 focus:outline-none focus:ring-4';
                    
                    const usedPack = usedPacks.find(p => p.pack_number === num);
                    if (usedPack) {
                        btn.setAttribute('title', usedPack.supplier + (usedPack.hcs_sorting_id ? ' - Sudah Disortir' : ' - Terpakai'));
                        btn.classList.add('cursor-not-allowed', 'shadow-none');
                        if (usedPack.hcs_sorting_id) {
                            btn.classList.add('bg-red-400', 'text-white', 'border-red-500', 'shadow-lg', 'shadow-red-500/20');
                        } else {
                            btn.classList.remove('rounded-lg');
                            btn.classList.add('rounded-full', 'border-transparent');
                            if (usedPack.supplier === 'Cutpack') {
                                btn.classList.add('bg-blue-100', 'text-blue-400');
                            } else if (usedPack.supplier === 'Rikyet') {
                                btn.classList.add('bg-green-100', 'text-green-400');
                            } else {
                                btn.classList.add('bg-gray-100', 'text-gray-300');
                            }
                        }
                    } else if (selectedPacks.includes(num)) {
                        btn.classList.add('shadow-lg', 'text-white');
                        if (currentSupplier === 'Cutpack') {
                            btn.classList.add('bg-blue-400', 'border-blue-500', 'shadow-blue-500/20');
                        } else if (currentSupplier === 'Rikyet') {
                            btn.classList.add('bg-green-400', 'border-green-500', 'shadow-green-500/20');
                        } else {
                            btn.classList.add('bg-indigo-500', 'border-indigo-600', 'shadow-indigo-500/20');
                        }
                    } else {
                        btn.classList.add('hover:bg-gray-50', 'hover:text-gray-600', 'hover:border-gray-200');
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
                        text: `Jumlah packs yang dipilih (${selectedPacks.length}) tidak sesuai dengan jumlah bilyet (${jumlahOriginal}). Dibutuhkan ${packsNeeded} packs.`,
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
</x-app-layout>
