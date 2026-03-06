<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Penerimaan HCS: ') . $hcsReceiving->batch }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="hcs-form" action="{{ route('hcs-receiving.update', $hcsReceiving->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="flex flex-col lg:flex-row gap-8">
                            
                            <!-- Left: Form Fields -->
                            <div class="w-full lg:w-1/2 bg-gray-50 p-6 rounded-lg border border-gray-100">
                                <h3 class="text-lg font-medium text-gray-900 mb-6">Detail Penerimaan</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div>
                                        <x-input-label for="nomor_bon" value="Nomor Bon" />
                                        <x-text-input id="nomor_bon" name="nomor_bon" type="text" class="mt-1 block w-full bg-gray-100" value="{{ old('nomor_bon', $hcsReceiving->nomor_bon) }}" readonly required />
                                    </div>

                                    <div>
                                        <x-input-label for="tanggal_penerimaan" value="Tanggal Penerimaan" />
                                        <x-text-input id="tanggal_penerimaan" name="tanggal_penerimaan" type="date" class="mt-1 block w-full" value="{{ old('tanggal_penerimaan', $hcsReceiving->tanggal_penerimaan) }}" required />
                                    </div>

                                    <div>
                                        <x-input-label for="pecahan" value="Pecahan" />
                                        <select id="pecahan" name="pecahan" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                            <option value="">Pilih Pecahan</option>
                                            <option value="S" {{ old('pecahan', $hcsReceiving->pecahan) == 'S' ? 'selected' : '' }}>S - 1.000</option>
                                            <option value="T" {{ old('pecahan', $hcsReceiving->pecahan) == 'T' ? 'selected' : '' }}>T - 2.000</option>
                                            <option value="U" {{ old('pecahan', $hcsReceiving->pecahan) == 'U' ? 'selected' : '' }}>U - 5.000</option>
                                            <option value="V" {{ old('pecahan', $hcsReceiving->pecahan) == 'V' ? 'selected' : '' }}>V - 10.000</option>
                                            <option value="W" {{ old('pecahan', $hcsReceiving->pecahan) == 'W' ? 'selected' : '' }}>W - 20.000</option>
                                            <option value="X" {{ old('pecahan', $hcsReceiving->pecahan) == 'X' ? 'selected' : '' }}>X - 50.000</option>
                                            <option value="Y" {{ old('pecahan', $hcsReceiving->pecahan) == 'Y' ? 'selected' : '' }}>Y - 100.000</option>
                                        </select>
                                    </div>

                                    <div>
                                        <x-input-label for="jumlah_display" value="Jumlah Bilyet" />
                                        <input id="jumlah_display" type="tel" class="mt-1 block w-full text-right font-mono border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" value="{{ old('jumlahDisplay', number_format($hcsReceiving->jumlah, 0, ',', '.')) }}" required />
                                        <input type="hidden" id="jumlah_original" name="jumlah" value="{{ old('jumlah', $hcsReceiving->jumlah) }}">
                                        <p class="text-xs text-gray-500 mt-1">Dibutuhkan <span id="packs_needed_display" class="font-bold text-indigo-600">0</span> packs.</p>
                                    </div>

                                    <div>
                                        <x-input-label for="gilir" value="Gilir" />
                                        <select id="gilir" name="gilir" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                            <option value="">Pilih Gilir</option>
                                            <option value="Gilir 1" {{ old('gilir', $hcsReceiving->gilir) == 'Gilir 1' ? 'selected' : '' }}>Gilir 1</option>
                                            <option value="Gilir 2" {{ old('gilir', $hcsReceiving->gilir) == 'Gilir 2' ? 'selected' : '' }}>Gilir 2</option>
                                            <option value="Gilir 3" {{ old('gilir', $hcsReceiving->gilir) == 'Gilir 3' ? 'selected' : '' }}>Gilir 3</option>
                                        </select>
                                    </div>

                                    <div>
                                        <x-input-label for="mesin" value="Mesin" />
                                        <x-text-input id="mesin" name="mesin" type="text" class="mt-1 block w-full" value="{{ old('mesin', $hcsReceiving->mesin) }}" required />
                                    </div>

                                    <div>
                                        <x-input-label for="supplier" value="Supplier" />
                                        <select id="supplier" name="supplier" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                            <option value="">Pilih Supplier</option>
                                            <option value="Cutpack" {{ old('supplier', $hcsReceiving->supplier) == 'Cutpack' ? 'selected' : '' }}>Cutpack</option>
                                            <option value="Rikyet" {{ old('supplier', $hcsReceiving->supplier) == 'Rikyet' ? 'selected' : '' }}>Rikyet</option>
                                        </select>
                                    </div>

                                    <div>
                                        <x-input-label for="batch" value="Batch (7 digits)" />
                                        <x-text-input id="batch" name="batch" type="text" maxlength="7" class="mt-1 block w-full font-mono uppercase bg-gray-100" value="{{ old('batch', $hcsReceiving->batch) }}" readonly required />
                                    </div>

                                    <div>
                                        <x-input-label for="seri" value="Seri (Format: AA-BB7)" />
                                        <x-text-input id="seri" name="seri" type="text" class="mt-1 block w-full font-mono uppercase bg-gray-100" placeholder="AA-BB7" value="{{ old('seri', $hcsReceiving->seri) }}" readonly required />
                                    </div>

                                    <div>
                                        <x-input-label for="emisi" value="Emisi (Tahun)" />
                                        <x-text-input id="emisi" name="emisi" type="number" min="2000" max="2100" class="mt-1 block w-full" value="{{ old('emisi', $hcsReceiving->emisi) }}" required />
                                    </div>
                                    
                                    <div class="col-span-1 sm:col-span-2 pt-2">
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                            <div class="flex items-center">
                                                <input id="repass" name="repass" value="repass" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('repass', $hcsReceiving->repass) ? 'checked' : '' }}>
                                                <label for="repass" class="ml-2 block text-sm font-medium text-gray-900 border border-gray-200 px-3 py-1 rounded bg-white">Tandai sebagai Repass</label>
                                            </div>
                                            <button type="submit" class="inline-flex justify-center py-2.5 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
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
                                <div class="flex gap-4 mb-4 text-sm font-medium">
                                    <div class="flex items-center"><div class="w-4 h-4 bg-gray-400 opacity-50 mr-2 rounded"></div> Used</div>
                                    <div class="flex items-center"><div class="w-4 h-4 bg-white border border-gray-300 mr-2 rounded"></div> Empty</div>
                                    <div class="flex items-center"><div class="w-4 h-4 bg-blue-300 mr-2 rounded"></div> Cutpack</div>
                                    <div class="flex items-center"><div class="w-4 h-4 bg-green-300 mr-2 rounded"></div> Rikyet</div>
                                </div>

                                <div class="text-sm mb-4 bg-gray-50 p-3 rounded-md border text-gray-700 flex justify-between">
                                    <span>Packs Dipilih: <span id="selected_packs_length" class="font-bold">0</span></span>
                                    <span>Dibutuhkan: <span id="packs_needed_length" class="font-bold">0</span></span>
                                </div>

                                <!-- Hidden Inputs to submit the array -->
                                <div id="hidden_packs_container"></div>

                                <!-- Grid -->
                                <div class="grid grid-rows-10 grid-flow-col gap-1 sm:gap-2 auto-cols-[minmax(0,_1fr)]" id="pack_grid">
                                    @for ($i = 1; $i <= 100; $i++)
                                        <button 
                                            type="button" 
                                            data-pack="{{ $i }}"
                                            class="pack-btn aspect-square flex items-center justify-center text-xs sm:text-sm font-semibold rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-indigo-500 bg-white text-gray-700 border border-gray-300 hover:bg-gray-100">
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
                        usedPacks = data.map(p => parseInt(p.pack_number, 10));
                        selectedPacks = selectedPacks.filter(p => !usedPacks.includes(p));
                        renderGrid();
                    }).catch(e => console.error(e));
                } else {
                    usedPacks = [];
                    renderGrid();
                }
            }

            function togglePack(number) {
                if (usedPacks.includes(number)) return;
                
                let index = selectedPacks.indexOf(number);
                if (index > -1) {
                    selectedPacks.splice(index, 1);
                } else {
                    if (selectedPacks.length < packsNeeded) {
                        selectedPacks.push(number);
                    } else {
                        if (packsNeeded > 0) {
                            alert(`Anda hanya dapat memilih ${packsNeeded} packs sesuai dengan jumlah bilyet.`);
                        } else {
                            alert(`Silakan masukkan Jumlah Bilyet terlebih dahulu.`);
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
                    
                    if (usedPacks.includes(num)) {
                        btn.classList.add('bg-gray-400', 'text-white', 'cursor-not-allowed', 'opacity-50');
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
                    alert('Silakan masukkan Jumlah Bilyet yang valid terlebih dahulu.');
                    return;
                }

                if (selectedPacks.length === 0) {
                    e.preventDefault();
                    alert(`Anda belum memilih pack apapun. Silakan pilih ${packsNeeded} pack pada grid di sebelah kanan.`);
                    return;
                }

                if (selectedPacks.length !== packsNeeded) {
                    e.preventDefault();
                    alert(`Jumlah packs yang dipilih (${selectedPacks.length}) tidak sesuai dengan jumlah bilyet (${jumlahOriginal}). Dibutuhkan ${packsNeeded} packs.`);
                    return;
                }
            });

            init();
        });
    </script>
    @endpush
</x-app-layout>
