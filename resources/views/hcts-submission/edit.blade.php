<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <h2 class="font-black text-xl text-gray-800 leading-tight tracking-tight">
                {{ __('Edit Penyerahan HCTS') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6" x-data="submissionForm()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($errors->any())
                <div class="mb-4 p-3 bg-rose-50 border-l-4 border-rose-500 text-rose-700 shadow-sm rounded-r-lg"
                    role="alert">
                    <div class="flex items-center mb-1">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-md font-bold">Terjadi Kesalahan!</span>
                    </div>
                    <ul class="list-disc list-inside text-[10px] opacity-90">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('hcts-submission.update', $hcts_submission) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                    <!-- Left: Administration & Specs -->
                    <div class="lg:col-span-4 space-y-4">
                        <!-- Administration Card -->
                        <div
                            class="bg-white/70 backdrop-blur-md overflow-hidden shadow-xl shadow-gray-200/50 sm:rounded-[1.5rem] border border-white relative group transition-all duration-500">
                            <div class="p-5 relative">
                                <div class="flex items-center gap-3 mb-4">
                                    <div
                                        class="w-10 h-10 bg-rose-500 rounded-xl flex items-center justify-center group-hover:scale-105 transition-transform duration-500">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-md font-black text-gray-900 tracking-tighter">
                                            {{ __('Administrasi') }}
                                        </h3>
                                        <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">
                                            Penyerahan ke BI</p>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <div class="group/input">
                                        <x-input-label for="tanggal_penyerahan" :value="__('Tanggal Penyerahan')"
                                            class="text-[9px] font-black tracking-widest text-gray-400 mb-1 group-focus-within/input:text-rose-500 transition-colors" />
                                        <x-text-input id="tanggal_penyerahan" name="tanggal_penyerahan" type="date"
                                            class="block w-full bg-gray-50/50 border-gray-100 focus:bg-white focus:ring-2 focus:ring-rose-500/10 focus:border-rose-500/50 rounded-xl transition-all duration-300 text-md font-bold h-[38px] text-center"
                                            :value="old('tanggal_penyerahan', $hcts_submission->tanggal_penyerahan)"
                                            required />
                                    </div>

                                    <div class="group/input">
                                        <x-input-label for="nomor_ba" :value="__('Nomor BA Penyerahan')"
                                            class="text-[9px] font-black tracking-widest text-gray-400 mb-1 group-focus-within/input:text-rose-500 transition-colors" />
                                        <x-text-input id="nomor_ba" name="nomor_ba" type="text"
                                            class="block w-full bg-gray-50/50 border-gray-100 focus:bg-white focus:ring-2 focus:ring-rose-500/10 focus:border-rose-500/50 rounded-xl transition-all duration-300 text-md font-bold placeholder:text-gray-300 h-[38px] text-center"
                                            :value="old('nomor_ba', $hcts_submission->nomor_ba)"
                                            placeholder="BA/123/BI/2025" required />
                                    </div>

                                    <div class="group/input">
                                        <x-input-label for="pemasok1" :value="__('Pemasok 1')"
                                            class="text-[9px] font-black tracking-widest text-gray-400 mb-1 group-focus-within/input:text-rose-500 transition-colors" />
                                        <x-text-input id="pemasok1" name="pemasok1" type="text"
                                            class="block w-full bg-gray-50/50 border-gray-100 focus:bg-white focus:ring-2 focus:ring-rose-500/10 focus:border-rose-500/50 rounded-xl transition-all duration-300 text-md font-bold h-[38px] text-center uppercase"
                                            :value="old('pemasok1', $hcts_submission->pemasok1)"
                                            oninput="this.value = this.value.toUpperCase()" required />
                                    </div>

                                    <div class="group/input">
                                        <x-input-label for="pemasok2" :value="__('Pemasok 2')"
                                            class="text-[9px] font-black tracking-widest text-gray-400 mb-1 group-focus-within/input:text-rose-500 transition-colors" />
                                        <x-text-input id="pemasok2" name="pemasok2" type="text"
                                            class="block w-full bg-gray-50/50 border-gray-100 focus:bg-white focus:ring-2 focus:ring-rose-500/10 focus:border-rose-500/50 rounded-xl transition-all duration-300 text-md font-bold h-[38px] text-center uppercase"
                                            :value="old('pemasok2', $hcts_submission->pemasok2)"
                                            oninput="this.value = this.value.toUpperCase()" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Main Spec Card -->
                        <div
                            class="bg-white/70 backdrop-blur-md overflow-hidden shadow-xl shadow-gray-200/50 sm:rounded-[1.5rem] border border-white relative group transition-all duration-500">
                            <div class="p-5">
                                <div class="flex items-center gap-3 mb-4">
                                    <div
                                        class="w-10 h-10 bg-rose-500 rounded-xl flex items-center justify-center group-hover:scale-105 transition-transform duration-500">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-md font-black text-gray-900 tracking-tighter">
                                            {{ __('Spesifikasi') }}
                                        </h3>
                                        <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">
                                            Filter Data</p>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="group/input">
                                            <x-input-label for="pecahan" :value="__('Pecahan')"
                                                class="text-[9px] font-black tracking-widest text-gray-400 mb-1 group-focus-within/input:text-rose-500 transition-colors" />
                                            <select id="pecahan" name="pecahan" x-model="pecahan"
                                                @change="fetchAvailableBatches()"
                                                class="block w-full bg-gray-50/50 border-gray-100 focus:bg-white focus:ring-2 focus:ring-rose-500/10 focus:border-rose-500/50 rounded-xl transition-all duration-300 text-md font-black h-[38px] text-center text-center-last"
                                                required>
                                                <option value="">Pilih</option>
                                                @foreach(['S', 'T', 'U', 'V', 'W', 'X', 'Y'] as $p)
                                                    <option value="{{ $p }}">{{ $p }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="group/input">
                                            <x-input-label for="tahun_anggaran" :value="__('TA')"
                                                class="text-[9px] font-black tracking-widest text-gray-400 mb-1 group-focus-within/input:text-rose-500 transition-colors" />
                                            <select id="tahun_anggaran" name="tahun_anggaran" x-model="tahun_anggaran"
                                                @change="fetchAvailableBatches()"
                                                class="block w-full bg-gray-50/50 border-gray-100 focus:bg-white focus:ring-2 focus:ring-rose-500/10 focus:border-rose-500/50 rounded-xl transition-all duration-300 text-md font-black h-[38px] text-center text-center-last"
                                                required>
                                                <option value="">Pilih</option>
                                                @foreach($availableTA as $year)
                                                    <option value="{{ $year }}">{{ $year }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="group/input">
                                        <x-input-label for="tahun_emisi" :value="__('Tahun Emisi')"
                                            class="text-[10px] font-black tracking-widest text-gray-400 mb-1 group-focus-within/input:text-rose-500 transition-colors" />
                                        <select id="tahun_emisi" name="tahun_emisi" x-model="tahun_emisi"
                                            @change="fetchAvailableBatches()"
                                            class="block w-full bg-gray-50/50 border-gray-100 focus:bg-white focus:ring-2 focus:ring-rose-500/10 focus:border-rose-500/50 rounded-xl transition-all duration-300 text-md font-black h-[38px] text-center text-center-last"
                                            required>
                                            <option value="">Pilih Tahun Emisi</option>
                                            @foreach($availableTE as $year)
                                                <option value="{{ $year }}">{{ $year }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="group/input">
                                        <x-input-label for="display_jumlah_bilyet" :value="__('Total Jumlah Bilyet')"
                                            class="text-[10px] font-black tracking-widest text-gray-400 mb-1 group-focus-within/input:text-rose-500 transition-colors" />
                                        <div class="relative">
                                            <x-text-input id="display_jumlah_bilyet" type="text"
                                                x-model="display_jumlah_bilyet" @input="formatBilyetInput($event)"
                                                class="block w-full bg-gray-50/50 border-gray-100 focus:bg-white focus:ring-2 focus:ring-rose-500/10 focus:border-rose-500/50 rounded-xl transition-all duration-300 text-md font-black h-[38px] text-center"
                                                placeholder="0" required />
                                            <input type="hidden" name="jumlah_bilyet" x-model="jumlah_bilyet">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Summary Widget -->
                        <div class="bg-gray-900 overflow-hidden shadow-xl sm:rounded-[1.5rem] relative p-0.5">
                            <div
                                class="bg-gray-950/40 backdrop-blur-sm rounded-[1.4rem] p-5 relative z-10 border border-white/5">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-[12px] font-black text-rose-500 uppercase tracking-[0.3em]">
                                        {{ __('Summary Status') }}
                                    </h3>
                                    <div class="px-2 py-0.5 bg-white/5 rounded-full border border-white/10">
                                        <span class="text-[8px] font-black text-white/50 uppercase tracking-widest"
                                            x-text="calculateTotal() === (jumlah_bilyet || 0) ? 'Balance' : 'Review'">Balance</span>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <div>
                                        <div class="flex justify-between items-end mb-1">
                                            <span
                                                class="text-[8px] font-bold text-gray-500 uppercase tracking-widest">Progress</span>
                                            <div class="flex items-baseline gap-1">
                                                <span class="text-xl font-black text-white"
                                                    x-text="numberFormat(calculateTotal())">0</span>
                                                <span class="text-[10px] font-bold text-gray-500">/ <span
                                                        x-text="numberFormat(jumlah_bilyet || 0)">0</span></span>
                                            </div>
                                        </div>
                                        <div
                                            class="h-2 bg-white/5 rounded-full overflow-hidden p-0.5 border border-white/5">
                                            <div class="h-full bg-rose-500 rounded-full transition-all duration-1000 shadow-[0_0_10px_rgba(244,63,94,0.5)]"
                                                :style="`width: ${Math.min((calculateTotal() / (jumlah_bilyet || 1)) * 100, 100)}%`">
                                            </div>
                                        </div>
                                    </div>

                                    <template x-if="calculateTotal() != (jumlah_bilyet || 0) && jumlah_bilyet > 0">
                                        <div
                                            class="p-2 bg-rose-500/10 rounded-xl border border-rose-500/20 flex items-center gap-2">
                                            <div
                                                class="w-6 h-6 bg-rose-500/20 rounded-lg flex items-center justify-center shrink-0">
                                                <svg class="w-3 h-3 text-rose-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <p
                                                class="text-[8px] font-black text-rose-400 uppercase tracking-widest leading-none">
                                                Mismatch!</p>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Dynamic Rows -->
                    <div class="lg:col-span-8">
                        <div
                            class="bg-white/70 backdrop-blur-md overflow-hidden shadow-xl shadow-gray-200/50 sm:rounded-[2rem] border border-white h-full flex flex-col transition-all duration-500">
                            <div class="p-6 border-b border-gray-50 flex items-center justify-between bg-white/50">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 bg-rose-50 rounded-xl flex items-center justify-center text-rose-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-md font-black text-gray-900 tracking-tighter">
                                            {{ __('Komposisi Batch') }}
                                        </h3>
                                        <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mt-0.5"
                                            x-text="selectedBatches.length + ' Baris Aktif'">0 Baris Aktif</p>
                                    </div>
                                </div>

                                <button type="button" @click="addBatchRow()"
                                    class="group flex items-center gap-2 px-4 py-2 bg-blue-500 text-white text-[9px] font-black tracking-widest rounded-xl hover:bg-rose-600 transition-all duration-500">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    {{ __('Tambah Baris') }}
                                </button>
                            </div>

                            <div class="flex-1 p-6 overflow-y-auto max-h-[500px] scrollbar-hide space-y-3">
                                <template x-for="(row, index) in selectedBatches" :key="index">
                                    <div x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 translate-y-4"
                                        x-transition:enter-end="opacity-100 translate-y-0"
                                        class="flex flex-col md:flex-row gap-4 p-5 bg-gray-50/50 rounded-2xl border border-gray-100/50 hover:bg-white hover:shadow-lg transition-all duration-300 group/row">

                                        <div class="flex-1 flex flex-col md:flex-row gap-4">
                                            <div class="flex-1 group/input">
                                                <x-input-label :value="__('Nomor Batch')"
                                                    class="text-[8px] font-black tracking-widest text-gray-400 mb-1 group-focus-within/row:text-rose-500 transition-colors" />
                                                <select x-bind:name="'batches['+index+'][batch]'" x-model="row.batch"
                                                    class="block w-full bg-white border-gray-100 focus:ring-2 focus:ring-rose-500/10 focus:border-rose-500/50 rounded-xl transition-all duration-300 text-md font-black h-[38px] text-center text-center-last"
                                                    required>
                                                    <option value="">Pilih Batch</option>
                                                    <template x-for="avail in availableBatchesList" :key="avail.batch">
                                                        <option :value="avail.batch"
                                                            x-text="avail.batch + ' (Stok: ' + numberFormat(avail.stock) + ')'">
                                                        </option>
                                                    </template>
                                                    {{-- Re-add current batch if it's not in the available list --}}
                                                    <template
                                                        x-if="row.batch && !availableBatchesList.find(b => b.batch === row.batch)">
                                                        <option :value="row.batch" x-text="row.batch + ' (Current)'">
                                                        </option>
                                                    </template>
                                                </select>
                                            </div>
                                            <div class="flex-1 group/input">
                                                <x-input-label :value="__('Jumlah Bilyet')"
                                                    class="text-[8px] font-black tracking-widest text-gray-400 mb-1 group-focus-within/row:text-rose-500 transition-colors" />
                                                <div class="relative">
                                                    <x-text-input type="text" x-model="row.display_jumlah"
                                                        @input="formatRowJumlah($event, index)"
                                                        class="block w-full bg-white border-gray-100 focus:ring-2 focus:ring-rose-500/10 focus:border-rose-500/50 rounded-xl transition-all duration-300 text-md font-black h-[38px] text-center"
                                                        placeholder="0" required />
                                                    <input type="hidden" x-bind:name="'batches['+index+'][jumlah]'"
                                                        x-model="row.jumlah">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex items-end pb-0.5">
                                            <button type="button" @click="removeBatchRow(index)"
                                                class="group/del p-2.5 bg-rose-50 text-rose-400 hover:bg-rose-500 hover:text-white rounded-xl transition-all duration-500">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v2m3 4h.01">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </template>

                                <template x-if="selectedBatches.length === 0">
                                    <div
                                        class="flex flex-col items-center justify-center py-12 border-2 border-dashed border-gray-100/50 rounded-2xl bg-gray-50/20">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                            {{ __('Klik Tambah Baris untuk memulai') }}
                                        </p>
                                    </div>
                                </template>
                            </div>

                            <div
                                class="p-6 border-t border-gray-50 bg-gray-50/30 backdrop-blur-sm flex items-center justify-end gap-3 rounded-b-[2rem]">
                                <a href="{{ route('hcts-submission.index') }}"
                                    class="px-5 py-2 text-gray-400 text-[9px] font-black tracking-widest hover:text-gray-900 transition-colors">
                                    {{ __('Batal') }}
                                </a>
                                <button type="submit"
                                    :disabled="calculateTotal() != jumlah_bilyet || calculateTotal() === 0"
                                    :class="calculateTotal() != jumlah_bilyet || calculateTotal() === 0 ? 'opacity-30 grayscale cursor-not-allowed' : 'hover:bg-rose-700 active:scale-95'"
                                    class="px-8 py-2.5 bg-rose-600 text-white text-[9px] font-black tracking-widest rounded-xl transition-all duration-500">
                                    {{ __('Perbarui Records') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    function submissionForm() {
                        return {
                            pecahan: '{{ old('pecahan', $hcts_submission->pecahan) }}',
                            tahun_anggaran: '{{ old('tahun_anggaran', $hcts_submission->tahun_anggaran) }}',
                            tahun_emisi: '{{ old('tahun_emisi', $hcts_submission->tahun_emisi) }}',
                            jumlah_bilyet: {{ old('jumlah_bilyet', $hcts_submission->jumlah_bilyet ?? 0) }},
                            display_jumlah_bilyet: '',
                            availableBatchesList: [],
                            selectedBatches: {!! json_encode(old('batches', $hcts_submission->batches->map(fn($b) => ['batch' => $b->batch, 'jumlah' => $b->jumlah, 'display_jumlah' => '']))) !!},

                            init() {
                                this.display_jumlah_bilyet = this.numberFormat(this.jumlah_bilyet);
                                this.selectedBatches.forEach(b => {
                                    b.display_jumlah = this.numberFormat(b.jumlah);
                                });
                                this.fetchAvailableBatches();
                            },

                            fetchAvailableBatches() {
                                if (this.pecahan && this.tahun_anggaran && this.tahun_emisi) {
                                    fetch(`{{ route('hcts-submission.available-batches') }}?pecahan=${this.pecahan}&tahun_anggaran=${this.tahun_anggaran}&tahun_emisi=${this.tahun_emisi}&exclude_id={{ $hcts_submission->id }}`)
                                        .then(res => res.json())
                                        .then(data => {
                                            this.availableBatchesList = data;
                                        });
                                } else {
                                    this.availableBatchesList = [];
                                }
                            },

                            addBatchRow() {
                                this.selectedBatches.push({
                                    batch: '',
                                    jumlah: 0,
                                    display_jumlah: ''
                                });
                            },

                            removeBatchRow(index) {
                                this.selectedBatches.splice(index, 1);
                            },

                            calculateTotal() {
                                return this.selectedBatches.reduce((acc, curr) => acc + (parseInt(curr.jumlah) || 0), 0);
                            },

                            formatBilyetInput(e) {
                                let val = e.target.value.replace(/\D/g, "");
                                this.jumlah_bilyet = parseInt(val) || 0;
                                this.display_jumlah_bilyet = this.numberFormat(this.jumlah_bilyet);
                                if (this.jumlah_bilyet === 0) this.display_jumlah_bilyet = "";
                            },

                            formatRowJumlah(e, index) {
                                let val = e.target.value.replace(/\D/g, "");
                                this.selectedBatches[index].jumlah = parseInt(val) || 0;
                                this.selectedBatches[index].display_jumlah = this.numberFormat(this.selectedBatches[index].jumlah);
                                if (this.selectedBatches[index].jumlah === 0) this.selectedBatches[index].display_jumlah = "";
                            },

                            numberFormat(x) {
                                if (!x && x !== 0) return "";
                                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            }
                        }
                    }
                </script>
</x-app-layout>