<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Laporan Penyortiran HCS') }} - Batch: {{ $hcs_sorting_report->batch }}, Seri: {{ $hcs_sorting_report->seri }}, Pecahan: {{ $hcs_sorting_report->pecahan }}
        </h2>
    </x-slot>

    <div class="py-6 min-h-screen bg-gray-100" x-data="sortingGrid()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Terjadi Kesalahan!</strong>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Left Column: Grid Selection -->
                <div class="flex-1 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center border-b pb-2 mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Ubah Pack Disortir (1-100)</h3>
                        </div>
                        
                        <!-- Legend -->
                        <div class="flex flex-wrap gap-4 mb-6 text-sm bg-gray-50 p-3 rounded-md border border-gray-200">
                            <div class="flex items-center"><div class="w-4 h-4 rounded bg-blue-500 mr-2 shadow-sm"></div> Rikyet (Tersedia)</div>
                            <div class="flex items-center"><div class="w-4 h-4 rounded bg-green-500 mr-2 shadow-sm"></div> Cutpack (Tersedia)</div>
                            <div class="flex items-center"><div class="w-4 h-4 rounded bg-blue-200 border border-blue-400 mr-2" style="background-image: repeating-linear-gradient(45deg, rgba(0,0,0,0.1), rgba(0,0,0,0.1) 3px, transparent 3px, transparent 6px);"></div> Rikyet (Disortir Sesi Lain)</div>
                            <div class="flex items-center"><div class="w-4 h-4 rounded bg-green-200 border border-green-400 mr-2" style="background-image: repeating-linear-gradient(-45deg, rgba(0,0,0,0.1), rgba(0,0,0,0.1) 3px, transparent 3px, transparent 6px);"></div> Cutpack (Disortir Sesi Lain)</div>
                            <div class="flex items-center"><div class="w-4 h-4 rounded ring-2 ring-indigo-500 bg-white mr-2"></div> Sedang Dipilih</div>
                            <div class="flex items-center"><div class="w-4 h-4 rounded bg-gray-100 border border-gray-300 mr-2"></div> Belum Diinput</div>
                        </div>

                        <!-- 10x10 Grid -->
                        <div class="grid grid-flow-col gap-2 mb-6" style="grid-template-rows: repeat(10, minmax(0, 1fr));" @mouseleave="isDragging = false">
                            @for ($i = 1; $i <= 100; $i++)
                                @php
                                    $pack = $packsData->get($i);
                                    $statusClass = 'bg-gray-100 border-gray-300 text-gray-400 cursor-not-allowed';
                                    $isReady = false;

                                    if ($pack) {
                                        $supplier = strtolower($pack->pack_supplier);
                                        // A pack is sorted by ANOTHER session if it has an id and it doesn't match the current one
                                        $isSortedByOther = !is_null($pack->hcs_sorting_id) && $pack->hcs_sorting_id !== $hcs_sorting_report->id;

                                        if ($isSortedByOther) {
                                            // Disabled / Sorted by other
                                            if (str_contains($supplier, 'rikyet')) {
                                                $statusClass = 'bg-blue-200 text-blue-900 cursor-not-allowed border-blue-400 shadow-inner';
                                                $hatchStyle = "background-image: repeating-linear-gradient(45deg, rgba(0,0,0,0.1), rgba(0,0,0,0.1) 4px, transparent 4px, transparent 8px);";
                                            } else {
                                                $statusClass = 'bg-green-200 text-green-900 cursor-not-allowed border-green-400 shadow-inner';
                                                $hatchStyle = "background-image: repeating-linear-gradient(-45deg, rgba(0,0,0,0.1), rgba(0,0,0,0.1) 4px, transparent 4px, transparent 8px);";
                                            }
                                            $statusClass .= '" style="' . $hatchStyle; 
                                        } else {
                                            // Available for THIS session
                                            $isReady = true;
                                            if (str_contains($supplier, 'rikyet')) {
                                                $statusClass = 'bg-blue-500 text-white hover:bg-blue-600 cursor-pointer shadow-sm';
                                            } else {
                                                $statusClass = 'bg-green-500 text-white hover:bg-green-600 cursor-pointer shadow-sm';
                                            }
                                        }
                                    }
                                @endphp

                                <div 
                                    class="h-10 w-full flex items-center justify-center rounded text-sm font-bold border select-none transition-colors 
                                           {{ $statusClass }}"
                                    :class="{
                                        'ring-4 ring-yellow-400 ring-inset opacity-90 scale-105 z-10': isSelected({{ $i }})
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
                        <div x-show="validationError" x-cloak class="text-red-600 text-sm font-medium mt-2 p-3 bg-red-50 rounded border border-red-200" x-text="validationError"></div>
                        <div class="text-sm text-gray-500 mt-2">
                            * Klik dan geser (drag) untuk memilih/membatalkan pilihan beberapa pack sekaligus. Harus berurutan dan kelipatan 4.
                        </div>
                    </div>
                </div>

                <!-- Right Column: Form -->
                <div class="w-full lg:w-1/3 bg-white overflow-hidden shadow-sm sm:rounded-lg h-fit">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Detail Laporan</h3>
                        
                        <form method="POST" action="{{ route('hcs-sorting-reports.update', $hcs_sorting_report->id) }}" id="sortingForm" @submit="validateSubmission">
                            @csrf
                            @method('PUT')
                            
                            <!-- Hidden inputs for selected packs -->
                            <template x-for="pack in selectedPacks" :key="pack">
                                <input type="hidden" name="selected_packs[]" :value="pack">
                            </template>

                            <div class="space-y-4">
                                <!-- Summary Info -->
                                <div class="bg-gray-50 p-4 rounded-md border border-gray-200 space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Total Pack Dipilih:</span>
                                        <span class="font-bold text-indigo-700" x-text="selectedPacks.length"></span>
                                    </div>
                                    <div class="flex justify-between border-t pt-2">
                                        <span class="text-sm text-gray-600">Total Bilyet:</span>
                                        <span class="font-bold text-green-700 text-lg" x-text="formatNumber(totalBilyet)"></span>
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="supplier" :value="__('Supplier')" />
                                    <select id="supplier" name="supplier" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>
                                        <option value="Cutpack" {{ old('supplier', $hcs_sorting_report->supplier) == 'Cutpack' ? 'selected' : '' }}>Cutpack</option>
                                        <option value="Rikyet" {{ old('supplier', $hcs_sorting_report->supplier) == 'Rikyet' ? 'selected' : '' }}>Rikyet</option>
                                    </select>
                                </div>

                                <div>
                                    <x-input-label for="emisi" :value="__('Emisi / TA')" />
                                    <div class="grid grid-cols-2 gap-2">
                                        <x-text-input class="block mt-1 w-full bg-gray-50 opacity-70" type="text" name="emisi" :value="old('emisi', $hcs_sorting_report->emisi)" readonly required />
                                        <x-text-input class="block mt-1 w-full bg-gray-50 opacity-70" type="text" name="tahun_anggaran" :value="old('tahun_anggaran', $hcs_sorting_report->tahun_anggaran)" readonly required />
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="petugas_1" :value="__('Petugas 1')" />
                                    <x-text-input id="petugas_1" class="block mt-1 w-full" type="text" name="petugas_1" :value="old('petugas_1', $hcs_sorting_report->petugas_1)" required />
                                </div>

                                <div>
                                    <x-input-label for="petugas_2" :value="__('Petugas 2 (Opsional)')" />
                                    <x-text-input id="petugas_2" class="block mt-1 w-full" type="text" name="petugas_2" :value="old('petugas_2', $hcs_sorting_report->petugas_2)" />
                                </div>

                                <div>
                                    <x-input-label for="tanggal_sortir" :value="__('Tanggal Sortir')" />
                                    <x-text-input id="tanggal_sortir" class="block mt-1 w-full" type="date" name="tanggal_sortir" :value="old('tanggal_sortir', $hcs_sorting_report->tanggal_sortir->format('Y-m-d'))" required />
                                </div>

                                <div>
                                    <x-input-label for="gilir" :value="__('Gilir')" />
                                    <select id="gilir" name="gilir" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>
                                        <option value="Gilir 1" {{ old('gilir', $hcs_sorting_report->gilir) == 'Gilir 1' ? 'selected' : '' }}>Gilir 1</option>
                                        <option value="Gilir 2" {{ old('gilir', $hcs_sorting_report->gilir) == 'Gilir 2' ? 'selected' : '' }}>Gilir 2</option>
                                        <option value="Gilir 3" {{ old('gilir', $hcs_sorting_report->gilir) == 'Gilir 3' ? 'selected' : '' }}>Gilir 3</option>
                                    </select>
                                </div>
                                
                                <div class="flex items-center justify-end mt-6 gap-3 pt-4 border-t">
                                    <a href="{{ route('hcs-sorting-reports.index') }}" class="w-1/3 text-center inline-flex justify-center items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                        Batal
                                    </a>
                                    <button type="submit" 
                                            class="w-2/3 flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                            :disabled="selectedPacks.length === 0 || validationError !== ''">
                                        Simpan Perubahan
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
            Alpine.data('sortingGrid', () => ({
                // Initialize with existing packs
                selectedPacks: {!! json_encode(old('selected_packs', $hcs_sorting_report->packs_selected)) !!}.map(Number),
                isDragging: false,
                dragStart: null,
                validationError: '',
                bilyetPerPack: 45000,
                
                get totalBilyet() {
                    return this.selectedPacks.length * this.bilyetPerPack;
                },

                formatNumber(num) {
                    return new Intl.NumberFormat('id-ID').format(num);
                },

                isSelected(num) {
                    return this.selectedPacks.includes(num);
                },

                // Resetting logic:
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
                        if (block.length % 4 !== 0 || (block[0] - 1) % 4 !== 0) {
                            hasError = true;
                            break;
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
