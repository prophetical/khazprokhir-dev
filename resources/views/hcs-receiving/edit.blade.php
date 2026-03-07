<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Penerimaan HCS: ') . $hcsReceiving->batch }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    @php
                        $selectedPecahan = old('pecahan', $hcsReceiving->pecahan);
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

                    <div x-data="{ 
                        selectedPecahan: '{{ $selectedPecahan }}',
                        themes: {{ json_encode($themeClasses) }},
                        get currentTheme() { return this.themes[this.selectedPecahan] || null }
                    }" 
                    class="border-t-4 transition-all duration-500 pt-6"
                    :class="currentTheme ? currentTheme.border : 'border-transparent'">
                        <form id="hcs-form" action="{{ route('hcs-receiving.update', $hcsReceiving->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                        
                            <div class="flex flex-col lg:flex-row gap-8">
                                
                                <!-- Left: Form Fields -->
                                <div class="w-full lg:w-1/2 bg-gray-50/50 p-6 rounded-lg border border-gray-100 transition-all duration-500">
                                    <h3 class="text-lg font-medium text-gray-900 mb-6 flex items-center">
                                        <div class="w-2 h-6 mr-3 rounded-full transition-all duration-500" :class="currentTheme ? currentTheme.bg : 'bg-indigo-500'"></div>
                                        Detail Penerimaan
                                    </h3>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                        <div>
                                            <x-input-label for="nomor_bon" value="Nomor Bon" />
                                            <input id="nomor_bon" name="nomor_bon" type="text" 
                                                   class="mt-1 block w-full bg-gray-100 border-gray-300 rounded-md shadow-sm opacity-70" 
                                                   value="{{ old('nomor_bon', $hcsReceiving->nomor_bon) }}" readonly required />
                                        </div>

                                        <div>
                                            <x-input-label for="tanggal_penerimaan" value="Tanggal Penerimaan" />
                                            <input id="tanggal_penerimaan" name="tanggal_penerimaan" type="date" 
                                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm transition-all duration-300"
                                                   :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'" 
                                                   value="{{ old('tanggal_penerimaan', $hcsReceiving->tanggal_penerimaan) }}" required />
                                        </div>

                                        <div>
                                            <x-input-label for="pecahan" value="Pecahan" />
                                            <select id="pecahan" name="pecahan" x-model="selectedPecahan"
                                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm transition-all duration-300 {{ $hasSortedPacks ? 'bg-gray-100 pointer-events-none' : '' }}" 
                                                    :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'"
                                                    {{ $hasSortedPacks ? 'readonly tabindex="-1"' : '' }} required>
                                                <option value="">Pilih Pecahan</option>
                                                @foreach(['S' => '1.000', 'T' => '2.000', 'U' => '5.000', 'V' => '10.000', 'W' => '20.000', 'X' => '50.000', 'Y' => '100.000'] as $key => $label)
                                                    <option value="{{ $key }}">{{ $key }} - {{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <x-input-label for="jumlah_display" value="Jumlah Bilyet" />
                                            <input id="jumlah_display" type="tel" 
                                                   class="mt-1 block w-full text-right font-mono border-gray-300 rounded-md shadow-sm transition-all duration-300"
                                                   :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'" 
                                                   value="{{ old('jumlahDisplay', number_format($hcsReceiving->jumlah, 0, ',', '.')) }}" required />
                                            <input type="hidden" id="jumlah_original" name="jumlah" value="{{ old('jumlah', $hcsReceiving->jumlah) }}">
                                            @if($hasSortedPacks)
                                                <p class="text-[10px] text-red-500 mt-1 font-bold uppercase tracking-wider">Minimal pack: {{ $sortedPacksCount }} pack (sudah disortir).</p>
                                            @endif
                                            <p class="text-xs text-gray-500 mt-1">Dibutuhkan <span id="packs_needed_display" class="font-bold transition-colors duration-500" :class="currentTheme ? currentTheme.text.replace('text-', 'text-') : 'text-indigo-600'">0</span> packs.</p>
                                        </div>

                                        <div>
                                            <x-input-label for="gilir" value="Gilir" />
                                            <select id="gilir" name="gilir" 
                                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm transition-all duration-300"
                                                    :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'" 
                                                    required>
                                                <option value="">Pilih Gilir</option>
                                                <option value="Gilir 1" {{ old('gilir', $hcsReceiving->gilir) == 'Gilir 1' ? 'selected' : '' }}>Gilir 1</option>
                                                <option value="Gilir 2" {{ old('gilir', $hcsReceiving->gilir) == 'Gilir 2' ? 'selected' : '' }}>Gilir 2</option>
                                                <option value="Gilir 3" {{ old('gilir', $hcsReceiving->gilir) == 'Gilir 3' ? 'selected' : '' }}>Gilir 3</option>
                                            </select>
                                        </div>

                                        <div>
                                            <x-input-label for="mesin" value="Mesin" />
                                            <input id="mesin" name="mesin" type="text" 
                                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm transition-all duration-300"
                                                   :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'" 
                                                   value="{{ old('mesin', $hcsReceiving->mesin) }}" required />
                                        </div>

                                        <div>
                                            <x-input-label for="supplier" value="Supplier" />
                                            <select id="supplier" name="supplier" 
                                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm transition-all duration-300"
                                                    :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'" 
                                                    required>
                                                <option value="">Pilih Supplier</option>
                                                <option value="Cutpack" {{ old('supplier', $hcsReceiving->supplier) == 'Cutpack' ? 'selected' : '' }}>Cutpack</option>
                                                <option value="Rikyet" {{ old('supplier', $hcsReceiving->supplier) == 'Rikyet' ? 'selected' : '' }}>Rikyet</option>
                                            </select>
                                        </div>

                                        <div>
                                            <x-input-label for="batch" value="Batch (7 digits)" />
                                            <input id="batch" name="batch" type="text" maxlength="7" 
                                                   class="mt-1 block w-full font-mono uppercase bg-gray-100 border-gray-300 rounded-md shadow-sm opacity-70" 
                                                   value="{{ old('batch', $hcsReceiving->batch) }}" readonly required />
                                        </div>

                                        <div>
                                            <x-input-label for="seri" value="Seri (Format: AA-BB7)" />
                                            <input id="seri" name="seri" type="text" 
                                                   class="mt-1 block w-full font-mono uppercase bg-gray-100 border-gray-300 rounded-md shadow-sm opacity-70" 
                                                   placeholder="AA-BB7" value="{{ old('seri', $hcsReceiving->seri) }}" readonly required />
                                        </div>

                                        <div>
                                            <x-input-label for="emisi" value="Emisi (Tahun)" />
                                            <input id="emisi" name="emisi" type="number" min="2000" max="2100" 
                                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm transition-all duration-300"
                                                   :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'" 
                                                   value="{{ old('emisi', $hcsReceiving->emisi) }}" required />
                                        </div>
                                        
                                        <div class="col-span-1 sm:col-span-2 pt-2">
                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                                <div class="flex items-center">
                                                    <input id="repass" name="repass" value="repass" type="checkbox" 
                                                           class="rounded border-gray-300 shadow-sm transition-all duration-300" 
                                                           :class="currentTheme ? (currentTheme.text.replace('text-', 'text-') + ' ' + currentTheme.ring) : 'text-indigo-600 focus:ring-indigo-500'"
                                                           {{ old('repass', $hcsReceiving->repass) ? 'checked' : '' }}>
                                                    <label for="repass" class="ml-2 block text-sm font-medium text-gray-900 border border-gray-200 px-3 py-1 rounded bg-white">Tandai sebagai Repass</label>
                                                </div>
                                                <button type="submit" 
                                                        class="inline-flex justify-center py-2.5 px-6 border border-transparent shadow-sm text-sm font-bold rounded-md transition-all duration-300 uppercase tracking-widest whitespace-nowrap shadow-lg shadow-gray-100"
                                                        :class="currentTheme ? (currentTheme.btn + ' ' + currentTheme.text + ' brightness-95 hover:brightness-105') : 'bg-indigo-600 text-white hover:bg-indigo-700'">
                                                    Update Data
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Right: Pack Grid -->
                                <div class="w-full lg:w-1/2">
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Pack Grid System</h3>
                                    
                                    <!-- Legend -->
                                    <div class="flex flex-wrap gap-4 mb-4 text-[10px] font-bold uppercase tracking-widest text-gray-500">
                                        <div class="flex items-center"><div class="w-3 h-3 bg-white border border-gray-300 mr-2 rounded"></div> Kosong</div>
                                        <div class="flex items-center"><div class="w-3 h-3 bg-blue-300 mr-2 rounded"></div> CP (Dipilih)</div>
                                        <div class="flex items-center"><div class="w-3 h-3 bg-green-300 mr-2 rounded"></div> RK (Dipilih)</div>
                                        <div class="flex items-center"><div class="w-3 h-3 bg-blue-300 opacity-60 mr-2 rounded border border-blue-400"></div> CP (Terpakai)</div>
                                        <div class="flex items-center"><div class="w-3 h-3 bg-green-300 opacity-60 mr-2 rounded border border-green-400"></div> RK (Terpakai)</div>
                                        <div class="flex items-center"><div class="w-3 h-3 bg-red-400 mr-2 rounded border border-red-500"></div> Terpilih</div>
                                    </div>

                                    <div class="text-[10px] font-black mb-4 bg-white p-4 rounded-lg border border-gray-100 flex justify-between uppercase tracking-widest shadow-sm">
                                        <span class="text-gray-400">Packs Dipilih: <span id="selected_packs_length" class="text-lg transition-colors duration-500" :class="currentTheme ? currentTheme.text.replace('text-', 'text-') : 'text-indigo-600'">0</span></span>
                                        <span class="text-gray-400">Dibutuhkan: <span id="packs_needed_length" class="text-lg text-gray-900">0</span></span>
                                    </div>

                                    <!-- Hidden Inputs to submit the array -->
                                    <div id="hidden_packs_container"></div>

                                    <!-- Grid -->
                                    <div class="grid grid-rows-10 grid-flow-col gap-1 sm:gap-2 auto-cols-[minmax(0,_1fr)]" id="pack_grid">
                                        @for ($i = 1; $i <= 100; $i++)
                                            <button 
                                                type="button" 
                                                data-pack="{{ $i }}"
                                                class="pack-btn aspect-square flex items-center justify-center text-xs sm:text-sm font-semibold rounded-md transition-all focus:outline-none focus:ring-2 focus:ring-offset-1 bg-white text-gray-700 border border-gray-300 hover:bg-gray-100"
                                                :class="currentTheme ? currentTheme.ring : 'focus:ring-indigo-500'">
                                                <span>{{ $i }}</span>
                                            </button>
                                        @endfor
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
            let dbPacks = {!! json_encode($hcsReceiving->packs->pluck('pack_number')->toArray()) !!};
            let oldPacks = {!! json_encode(old('packs', null)) !!};
            let selectedPacks = oldPacks ? oldPacks.map(Number) : dbPacks;
            let usedPacks = [];
            const lockedPacks = {!! json_encode($sortedPacks) !!}.map(Number);
            const minPacks = {{ $hasSortedPacks ? $sortedPacksCount : 0 }};

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
                    fetch(`/api/packs/used?batch=${batchVal}&seri=${seriVal}&exclude_hcs_id={{ $hcsReceiving->id }}`)
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
                    btn.className = 'pack-btn aspect-square flex items-center justify-center text-xs sm:text-sm font-semibold rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-indigo-500';
                    
                    const usedPack = usedPacks.find(p => p.pack_number === num);
                    if (usedPack) {
                        btn.setAttribute('title', usedPack.supplier + (usedPack.hcs_sorting_id ? ' - Sudah Disortir (Record Lain)' : ' - Terpakai'));
                        btn.classList.add('cursor-not-allowed');
                        if (usedPack.hcs_sorting_id) {
                            btn.classList.add('bg-red-400', 'text-white', 'border', 'border-red-500', 'opacity-80');
                        } else {
                            btn.classList.add('opacity-60');
                            if (usedPack.supplier === 'Cutpack') {
                                btn.classList.add('bg-blue-300', 'text-blue-900', 'border', 'border-blue-400');
                            } else if (usedPack.supplier === 'Rikyet') {
                                btn.classList.add('bg-green-300', 'text-green-900', 'border', 'border-green-400');
                            } else {
                                btn.classList.add('bg-gray-400', 'text-white', 'border', 'border-gray-500');
                            }
                        }
                    } else if (lockedPacks.includes(num)) {
                        btn.setAttribute('title', 'Sudah Disortir (Terkunci)');
                        btn.classList.add('bg-red-500', 'text-white', 'border', 'border-red-600', 'cursor-not-allowed', 'opacity-90');
                        // Ensure it visually indicates it is selected and cannot be removed
                    } else if (selectedPacks.includes(num)) {
                        if (currentSupplier === 'Cutpack') {
                            btn.classList.add('bg-blue-300', 'text-blue-900', 'border', 'border-blue-400');
                        } else if (currentSupplier === 'Rikyet') {
                            btn.classList.add('bg-green-300', 'text-green-900', 'border', 'border-green-400');
                        } else {
                            btn.classList.add('bg-indigo-500', 'text-white', 'border', 'border-indigo-600');
                        }
                    } else {
                        btn.classList.add('bg-white', 'text-gray-700', 'border', 'border-gray-300', 'hover:bg-gray-100');
                    }
                });
            }

            // Bind Events
            inputJumlahDisplay.addEventListener('input', handleJumlahInput);
            
            inputBatch.addEventListener('input', () => {
                inputBatch.value = inputBatch.value.toUpperCase();
                fetchUsedPacks();
            });
            
            inputSeri.addEventListener('input', () => {
                inputSeri.value = inputSeri.value.toUpperCase();
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
</x-app-layout>
