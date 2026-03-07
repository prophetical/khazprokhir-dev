<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Proses Penyortiran HCS') }} - Batch: {{ $batch }}, Seri: {{ $seri }}, Pecahan: {{ $pecahan }}
        </h2>
    </x-slot>

    <div class="py-6 min-h-screen bg-gray-100" x-data="sortingGrid()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
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
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Pilih Pack untuk Disortir (1-100)</h3>
                        
                        <!-- Legend -->
                        <div class="flex flex-wrap gap-4 mb-6 text-sm bg-gray-50 p-3 rounded-md border border-gray-200">
                            <div class="flex items-center"><div class="w-4 h-4 rounded bg-blue-500 mr-2 shadow-sm"></div> Rikyet (Siap)</div>
                            <div class="flex items-center"><div class="w-4 h-4 rounded bg-green-500 mr-2 shadow-sm"></div> Cutpack (Siap)</div>
                            <div class="flex items-center"><div class="w-4 h-4 rounded bg-blue-300 border border-blue-500 mr-2" style="background-image: repeating-linear-gradient(45deg, rgba(0,0,0,0.15), rgba(0,0,0,0.15) 3px, transparent 3px, transparent 6px);"></div> Rikyet (Disortir)</div>
                            <div class="flex items-center"><div class="w-4 h-4 rounded bg-green-300 border border-green-500 mr-2" style="background-image: repeating-linear-gradient(45deg, rgba(0,0,0,0.15), rgba(0,0,0,0.15) 3px, transparent 3px, transparent 6px);"></div> Cutpack (Disortir)</div>
                            <div class="flex items-center"><div class="w-4 h-4 rounded bg-gray-100 border border-gray-300 mr-2"></div> Belum Diinput</div>
                        </div>

                        <!-- 10x10 Grid -->
                        <div class="grid grid-flow-col gap-2 mb-6" style="grid-template-rows: repeat(10, minmax(0, 1fr));" @mouseleave="isDragging = false">
                            @for ($i = 1; $i <= 100; $i++)
                                @php
                                    $pack = $packsData->get($i);
                                    $status = 'empty'; // default
                                    $statusClass = 'bg-gray-100 border-gray-300 text-gray-400 cursor-not-allowed';
                                    $isReady = false;

                                    if ($pack) {
                                        $isSorted = !is_null($pack->hcs_sorting_id);
                                        $supplier = strtolower($pack->pack_supplier);

                                        if ($isSorted) {
                                            $status = 'sorted';
                                            // Hatch pattern for sorted packs
                                            $hatchStyle = "background-image: repeating-linear-gradient(45deg, rgba(0,0,0,0.15), rgba(0,0,0,0.15) 5px, transparent 5px, transparent 10px);";
                                            if (str_contains($supplier, 'rikyet')) {
                                                $statusClass = 'bg-blue-300 border-2 border-blue-500 text-gray-800 cursor-not-allowed';
                                            } else {
                                                $statusClass = 'bg-green-300 border-2 border-green-500 text-gray-800 cursor-not-allowed';
                                            }
                                            $statusClass .= '" style="' . $hatchStyle; // Hacky but works since we output this directly into class attribute
                                        } else {
                                            $status = 'ready';
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
                            * Klik dan geser (drag) untuk memilih beberapa pack sekaligus. Harus berurutan dan kelipatan 4.
                        </div>
                    </div>
                </div>

                <!-- Right Column: Form -->
                <div class="w-full lg:w-1/3 bg-white overflow-hidden shadow-sm sm:rounded-lg h-fit">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Detail Penyortiran</h3>
                        
                        <form method="POST" action="{{ route('hcs-sorting.store') }}" id="sortingForm" @submit="validateSubmission">
                            @csrf
                            <input type="hidden" name="pecahan" value="{{ $pecahan }}">
                            <input type="hidden" name="batch" value="{{ $batch }}">
                            <input type="hidden" name="seri" value="{{ $seri }}">
                            
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
                                    <x-input-label for="petugas_1" :value="__('Petugas 1')" />
                                    <x-text-input id="petugas_1" class="block mt-1 w-full" type="text" name="petugas_1" :value="old('petugas_1')" required />
                                </div>

                                <div>
                                    <x-input-label for="petugas_2" :value="__('Petugas 2 (Opsional)')" />
                                    <x-text-input id="petugas_2" class="block mt-1 w-full" type="text" name="petugas_2" :value="old('petugas_2')" />
                                </div>

                                <div>
                                    <x-input-label for="tanggal" :value="__('Tanggal')" />
                                    <x-text-input id="tanggal" class="block mt-1 w-full" type="date" name="tanggal" :value="old('tanggal', date('Y-m-d'))" required />
                                </div>

                                <div>
                                    <x-input-label for="gilir" :value="__('Gilir')" />
                                    <select id="gilir" name="gilir" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>
                                        <option value="">Pilih Gilir</option>
                                        <option value="Gilir 1" {{ old('gilir') == 'Gilir 1' ? 'selected' : '' }}>Gilir 1</option>
                                        <option value="Gilir 2" {{ old('gilir') == 'Gilir 2' ? 'selected' : '' }}>Gilir 2</option>
                                        <option value="Gilir 3" {{ old('gilir') == 'Gilir 3' ? 'selected' : '' }}>Gilir 3</option>
                                    </select>
                                </div>
                                
                                <div class="pt-4 border-t">
                                    <button type="submit" 
                                            class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                            :disabled="selectedPacks.length === 0 || validationError !== ''">
                                        Simpan Data Sortir
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
                selectedPacks: [],
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
                    if (this.isDragging && this.dragStart !== null) {
                        // generate range from dragStart to num
                        let start = Math.min(this.dragStart, num);
                        let end = Math.max(this.dragStart, num);
                        
                        let newSelection = [];
                        for(let i=start; i<=end; i++){
                            newSelection.push(i);
                        }
                        // We reset selected to the currently dragged range. 
                        // To allow multiple blocks, we would need ctrl+click, but requirement says "berurutan" 
                        // It implies maybe a single continuous block or multiple. Let's support multiple contiguous blocks.
                        // For simplicity in UX, we'll let dragging OVERRIDE current selection to form exactly one contiguous block each drag 
                        // (Wait, user might want to select 1-4 and 21-24. We need to append. Let's rethink.)
                    }
                },
                
                // Let's refine the selection logic to simply toggle the pack, and we validate the whole array.
                // Resetting logic:
                startSelection(num) {
                    this.isDragging = true;
                    this.togglePack(num);
                },
                
                onHover(num) {
                     // In a real application, drag-to-select is complex if we want multiple blocks. 
                     // Let's stick to click-to-toggle for reliability, drag just continues toggling the state of dragStart.
                     if(!this.isDragging) return;
                     
                     // If it's already selected, skip
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
                        if (block.length % 4 !== 0) {
                            hasError = true;
                            break;
                        }
                    }

                    if (hasError) {
                        this.validationError = 'Pack yang dipilih harus berurutan dan setiap kelompok harus kelipatan 4.';
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
