<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-lg text-gray-800 leading-tight tracking-tighter">
            {{ isset($hcsKhazaiRegistration) ? __('Edit Registrasi HCS') : __('Registrasi HCS Baru') }}
        </h2>
    </x-slot>

    <div class="py-1 bg-gray-50/30 h-[calc(100vh-65px)] overflow-hidden" x-data="hcsKhazaiForm()" x-init="init()">
        <div class="max-w-full mx-auto px-4 lg:px-4 h-full">
            <form
                action="{{ isset($hcsKhazaiRegistration) ? route('hcs-khazai-registration.update', $hcsKhazaiRegistration->id) : route('hcs-khazai-registration.store') }}"
                method="POST" @submit="handleSubmit($event)" class="h-full flex flex-col overflow-hidden">
                @csrf
                @if(isset($hcsKhazaiRegistration)) @method('PUT') @endif

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 h-full overflow-hidden">
                    <!-- Left: Combined Info & Metadata -->
                    <div class="lg:col-span-4 h-full overflow-y-auto custom-scrollbar p-1">
                        <div class="bg-white p-3 rounded-2xl shadow-xl border border-gray-100 flex flex-col gap-3">
                            <div class="flex items-center gap-2 mb-1">
                                <div class="w-1 h-6 bg-indigo-600 rounded-full"></div>
                                <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest">Keterangan
                                </h3>
                            </div>

                            <div class="space-y-3">
                                <div>
                                    <label
                                        class="block font-black text-gray-400 uppercase tracking-widest text-[9px] mb-1.5 ml-1">Nomor
                                        Bon</label>
                                    <input type="text" name="nomor_bon" required
                                        value="{{ old('nomor_bon', $hcsKhazaiRegistration->nomor_bon ?? '') }}"
                                        class="w-full border-gray-200 rounded-lg font-bold py-2 px-3 text-xs focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-sm">
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label
                                            class="block font-black text-gray-400 uppercase tracking-widest text-[9px] mb-1.5 ml-1">Tanggal</label>
                                        <input type="date" name="tanggal_pembuatan" required
                                            value="{{ old('tanggal_pembuatan', isset($hcsKhazaiRegistration) ? $hcsKhazaiRegistration->tanggal_pembuatan->format('Y-m-d') : date('Y-m-d')) }}"
                                            class="w-full border-gray-200 rounded-lg font-bold py-1.5 px-3 text-[10px] focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-sm">
                                    </div>
                                    <div>
                                        <label
                                            class="block font-black text-gray-400 uppercase tracking-widest text-[9px] mb-1.5 ml-1">Batch</label>
                                        <input type="text" name="batch" required maxlength="7" x-model="formData.batch"
                                            x-on:input="handleBatchInput($event)" :readonly="isLocked"
                                            :class="isLocked ? 'bg-gray-50 border-gray-100 text-gray-400 cursor-not-allowed' : ''"
                                            class="w-full border-gray-200 rounded-lg font-bold py-1.5 px-3 text-[10px] focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-sm text-center"
                                            placeholder="0000000">
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-3">
                                    <div>
                                        <label
                                            class="block font-black text-gray-400 uppercase tracking-widest text-[9px] mb-1.5 ml-1">Pecahan</label>
                                        <div class="relative">
                                            <select name="pecahan" required x-model="formData.pecahan"
                                                :disabled="isLocked"
                                                :class="isLocked ? 'bg-gray-50 border-gray-100 text-gray-400 cursor-not-allowed opacity-50' : ''"
                                                class="w-full border-gray-200 rounded-lg font-bold py-1.5 px-3 text-[10px] focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-sm appearance-none">
                                                <option value="">Pilih</option>
                                                @foreach(['S' => '1.000', 'T' => '2.000', 'U' => '5.000', 'V' => '10.000', 'W' => '20.000', 'X' => '50.000', 'Y' => '100.000'] as $k => $v)
                                                    <option value="{{ $k }}">{{ $k }}</option>
                                                @endforeach
                                            </select>
                                            <template x-if="isLocked">
                                                <input type="hidden" name="pecahan" x-model="formData.pecahan">
                                            </template>
                                        </div>
                                    </div>
                                    <div>
                                        <label
                                            class="block font-black text-gray-400 uppercase tracking-widest text-[9px] mb-1.5 ml-1">Seri</label>
                                        <input type="text" id="seri" name="seri" required
                                            x-on:input="handleSeriInput($event)" x-model="formData.seri"
                                            :readonly="isLocked"
                                            :class="isLocked ? 'bg-gray-50 border-gray-100 text-gray-400 cursor-not-allowed' : ''"
                                            class="w-full border-gray-200 rounded-lg font-bold py-1.5 px-3 text-[10px] focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-sm text-center uppercase"
                                            placeholder="XX-XX#">
                                    </div>
                                    <div>
                                        <label
                                            class="block font-black text-gray-400 uppercase tracking-widest text-[9px] mb-1.5 ml-1">TE</label>
                                        <input type="text" name="emisi" required x-model="formData.emisi"
                                            :readonly="isLocked"
                                            :class="isLocked ? 'bg-gray-50 border-gray-100 text-gray-400 cursor-not-allowed' : ''"
                                            class="w-full border-gray-200 rounded-lg font-bold py-1.5 px-3 text-[10px] focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-sm text-center">
                                    </div>
                                </div>
                            </div>

                            <!--                           <div class="h-px bg-gray-100 my-1"></div> -->
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label
                                        class="block font-black text-gray-400 uppercase tracking-widest text-[9px] mb-1.5 ml-1">Gilir</label>
                                    <select name="gilir" required
                                        class="w-full border-gray-200 rounded-lg font-bold py-1.5 px-3 text-[10px] focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-sm">
                                        @foreach(['Gilir 1', 'Gilir 2', 'Gilir 3'] as $g)
                                            <option value="{{ $g }}" {{ (old('gilir', $hcsKhazaiRegistration->gilir ?? '') == $g) ? 'selected' : '' }}>{{ $g }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label
                                        class="block font-black text-gray-400 uppercase tracking-widest text-[9px] mb-1.5 ml-1">TA</label>
                                    <div class="relative">
                                        <select name="tahun_anggaran" required x-model="formData.tahun_anggaran"
                                            :disabled="isLocked"
                                            :class="isLocked ? 'bg-gray-50 border-gray-100 text-gray-400 cursor-not-allowed opacity-50' : ''"
                                            class="w-full border-gray-200 rounded-lg font-bold py-1.5 px-3 text-[10px] focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-sm">
                                            @foreach(['2024', '2025', '2026', '2027'] as $t)
                                                <option value="{{ $t }}" {{ (old('tahun_anggaran', $hcsKhazaiRegistration->tahun_anggaran ?? '2026') == $t) ? 'selected' : '' }}>
                                                    {{ $t }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <template x-if="isLocked">
                                            <input type="hidden" name="tahun_anggaran"
                                                x-model="formData.tahun_anggaran">
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label
                                        class="block font-black text-gray-400 uppercase tracking-widest text-[9px] mb-1.5 ml-1">Mesin</label>
                                    <input type="text" name="mesin" required
                                        value="{{ old('mesin', $hcsKhazaiRegistration->mesin ?? '') }}"
                                        class="w-full border-gray-200 rounded-lg font-bold py-1.5 px-3 text-[10px] focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-sm">
                                </div>
                                <div>
                                    <label
                                        class="block font-black text-gray-400 uppercase tracking-widest text-[9px] mb-1.5 ml-1">Supplier</label>
                                    <select name="supplier" required
                                        class="w-full border-gray-200 rounded-lg font-bold py-1.5 px-3 text-[10px] focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-sm">
                                        <option value="Cutpack" {{ (old('supplier', $hcsKhazaiRegistration->supplier ?? '') == 'Cutpack') ? 'selected' : '' }}>Cutpack</option>
                                        <option value="Rikyet" {{ (old('supplier', $hcsKhazaiRegistration->supplier ?? '') == 'Rikyet') ? 'selected' : '' }}>Rikyet</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <button type="submit" :disabled="selectedPacks.length !== packsNeeded"
                                    class="w-full py-3.5 bg-indigo-600 text-white rounded-xl font-black text-[10px] uppercase tracking-[0.2em] shadow-lg shadow-indigo-100 hover:bg-indigo-700 disabled:opacity-50 disabled:grayscale transition-all active:scale-95">
                                    {{ isset($hcsKhazaiRegistration) ? 'Perbarui Data' : 'Simpan & Generate Barcode' }}
                                </button>
                                <a href="{{ route('hcs-khazai-registration.index') }}"
                                    class="block w-full mt-3 py-3 text-gray-400 rounded-xl font-black text-[9px] text-center uppercase tracking-[0.2em] border border-gray-100 hover:bg-gray-50 transition-all">Batal</a>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Pack Grid Selection -->
                    <div class="lg:col-span-8 h-full overflow-y-auto custom-scrollbar p-1">
                        <div
                            class="bg-white p-4 rounded-2xl shadow-xl border border-gray-100 space-y-3 h-full flex flex-col">

                            <div
                                class="grid grid-cols-2 gap-1 items-end bg-slate-50 p-1 rounded-xl border border-slate-100">
                                <div>
                                    <input type="text" x-model="formattedJumlah" x-on:input="handleJumlahInput($event)"
                                        class="w-full border-gray-200 rounded-lg font-black py-2.5 px-4 text-sm text-indigo-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-inner"
                                        placeholder="0">
                                    <input type="hidden" name="jumlah" x-model.number="formData.jumlah">
                                </div>
                                <div class="flex items-center justify-center gap-4">
                                    <div class="text-center">
                                        <p
                                            class="text-[8px] font-black text-emerald-600 uppercase tracking-widest mb-0.5">
                                            Estimasi Pack</p>
                                        <p class="text-lg font-black text-emerald-700 leading-none"
                                            x-text="packsNeeded"></p>
                                    </div>
                                    <div class="w-px h-8 bg-emerald-200"></div>
                                    <div class="text-center">
                                        <p
                                            class="text-[8px] font-black text-indigo-600 uppercase tracking-widest mb-0.5">
                                            Terpilih</p>
                                        <p class="text-lg font-black text-indigo-700 leading-none"
                                            x-text="selectedPacks.length"></p>
                                    </div>
                                                                    <div class="flex items-center justify-between mb-1">
                                                                <div
                                                                    class="flex items-center gap-3 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100">
                                                                    <span class="font-black text-gray-400 uppercase text-[9px]">Pack buntut:</span>
                                                                    <label class="relative inline-flex items-center cursor-pointer scale-75">
                                                                        <input type="checkbox" x-model="formData.isManual" class="sr-only peer">
                                                                        <div
                                                                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-500/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500">
                                                                        </div>
                                                                    </label>
                                                                </div>
                                                                <div class="flex items-center gap-3 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100">
                                                                    <span class="font-black text-gray-400 uppercase text-[9px]">Pilih 10 Pack:</span>
                                                                    <label class="relative inline-flex items-center cursor-pointer scale-75">
                                                                        <input type="checkbox" x-model="isBulkSelect" class="sr-only peer">
                                                                        <div
                                                                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-500/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600">
                                                                        </div>
                                                                    </label>
                                                                </div>
                                                            </div>
                                </div>
                            </div>

                            <!-- Legend -->
                            <div
                                class="bg-gray-50/50 p-2.5 rounded-xl border border-gray-100 flex flex-wrap gap-x-4 gap-y-2 mt-1">
                                <div class="flex items-center gap-1.5 grayscale-[0.5]">
                                    <div class="w-3 h-3 bg-white border border-gray-200 rounded shadow-xs"></div>
                                    <span class="text-[8px] font-bold text-gray-400 uppercase">Kosong</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <div class="w-3 h-3 bg-blue-300 rounded border border-blue-400/20"></div>
                                    <span class="text-[8px] font-bold text-blue-600 uppercase">Cutpack Diterima</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <div class="w-3 h-3 bg-green-300 rounded border border-green-400/20">
                                    </div>
                                    <span class="text-[8px] font-bold text-green-600 uppercase">Rikyet Diterima</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <div class="w-3 h-3 bg-red-500 rounded border border-red-600/20"></div>
                                    <span class="text-[8px] font-bold text-red-600 uppercase">Disortir</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <div class="w-3 h-3 bg-indigo-500 rounded shadow-xs"></div>
                                    <span class="text-[8px] font-bold text-indigo-600 uppercase">Dipilih</span>
                                </div>
                            </div>

                            <!-- Pack Grid -->
                            <div
                                class="mt-1 border-t border-gray-50 pt-2 relative flex-grow overflow-hidden flex flex-col">
                                <template x-if="isLoading">
                                    <div
                                        class="absolute inset-0 bg-white/60 backdrop-blur-xs z-50 flex items-center justify-center rounded-xl">
                                        <div
                                            class="animate-spin rounded-full h-6 w-6 border-2 border-indigo-500 border-t-transparent">
                                        </div>
                                    </div>
                                </template>

                                <div class="grid grid-rows-10 grid-flow-col gap-x-1 gap-y-0.5 relative content-start max-w-2xl mx-auto w-full"
                                    id="pack_grid">
                                    <template x-for="n in 100">
                                        <div class="relative group">
                                            <button type="button" @click="togglePack(n)"
                                                class="pack-btn w-full h-8 sm:h-9 flex items-center justify-center text-[10px] font-black rounded-xl transition-all duration-300 hover:scale-110 hover:z-10 focus:outline-none focus:ring-4 relative"
                                                :class="getPackClass(n)">
                                                <span x-text="n"></span>
                                            </button>

                                            <!-- Rich Tooltip -->
                                            <div class="pointer-events-none absolute z-[100] hidden group-hover:flex items-center"
                                                :class="getTooltipClasses(n)">
                                                <div
                                                    class="bg-gray-900/95 backdrop-blur-sm text-white text-[10px] rounded-xl px-3 py-2 whitespace-nowrap shadow-2xl text-center leading-tight border border-white/10 min-w-[140px]">
                                                    <div
                                                        class="font-black border-b border-white/20 pb-1.5 mb-1.5 flex items-center justify-center gap-2">
                                                        PACK <span x-text="n"></span>
                                                        <template x-if="packStatuses[n]">
                                                            <span class="px-2 py-0.5 rounded-full text-[8px] text-white"
                                                                :class="packStatuses[n].status === 'sorted' ? 'bg-red-500' : 'bg-indigo-500'"
                                                                x-text="packStatuses[n].status === 'sorted' ? 'TERSORTIR' : 'TERISI'"></span>
                                                        </template>
                                                    </div>
                                                    <div class="font-bold uppercase tracking-tighter text-indigo-300"
                                                        x-text="packStatuses[n] ? packStatuses[n].supplier : 'KOSONG'">
                                                    </div>
                                                    <div class="text-gray-400 text-[9px] mt-1 font-medium"
                                                        x-text="packStatuses[n] ? (packStatuses[n].status === 'sorted' ? 'Sudah Disortir' : 'Sudah Terisi') : 'Bisa Dipilih'">
                                                    </div>
                                                </div>
                                                <div class="w-2 h-2 bg-gray-900 rotate-45" :class="getArrowClasses(n)">
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                <div class="mt-2">
                                    <template x-for="p in selectedPacks">
                                        <input type="hidden" name="packs[]" :value="p">
                                    </template>
                                </div>
                                <p x-show="selectedPacks.length !== packsNeeded"
                                    class="mt-3 text-rose-500 font-bold animate-pulse text-[9px]">
                                    * Harap pilih tepat <span x-text="packsNeeded"></span> pack.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function hcsKhazaiForm() {
            return {
                formData: {
                    pecahan: '{{ old('pecahan', request('pecahan', isset($hcsKhazaiRegistration) ? $hcsKhazaiRegistration->pecahan : '')) }}',
                    jumlah: {{ old('jumlah', isset($hcsKhazaiRegistration) ? $hcsKhazaiRegistration->jumlah : 0) }},
                    batch: '{{ old('batch', request('batch', isset($hcsKhazaiRegistration) ? $hcsKhazaiRegistration->batch : '')) }}',
                    seri: '{{ old('seri', request('seri', isset($hcsKhazaiRegistration) ? $hcsKhazaiRegistration->seri : '')) }}',
                    emisi: '{{ old('emisi', request('emisi', isset($hcsKhazaiRegistration) ? $hcsKhazaiRegistration->emisi : '2022')) }}',
                    tahun_anggaran: '{{ old('tahun_anggaran', request('tahun_anggaran', isset($hcsKhazaiRegistration) ? $hcsKhazaiRegistration->tahun_anggaran : '2025')) }}',
                    supplier: '{{ old('supplier', request('supplier', isset($hcsKhazaiRegistration) ? $hcsKhazaiRegistration->supplier : 'Cutpack')) }}',
                    isManual: false,
                },
                isLocked: {{ (request()->hasAny(['batch', 'seri', 'pecahan', 'emisi', 'tahun_anggaran']) && !isset($hcsKhazaiRegistration)) ? 'true' : 'false' }},
                formattedJumlah: '',
                selectedPacks: @json(array_map('intval', isset($hcsKhazaiRegistration) ? $hcsKhazaiRegistration->packs_data : [])),
                packStatuses: {},
                packsNeeded: 0,
                isLoading: false,
                excludeId: '{{ isset($hcsKhazaiRegistration) ? $hcsKhazaiRegistration->id : "" }}',
                isBulkSelect: false,

                calculatePacks() {
                    if (this.formData.isManual) {
                        this.packsNeeded = this.formData.jumlah > 0 ? 1 : 0;
                    } else {
                        this.packsNeeded = Math.floor(this.formData.jumlah / 45000);
                    }
                },

                handleJumlahInput(e) {
                    let cursorPosition = e.target.selectionStart;
                    let oldLength = this.formattedJumlah.length;

                    // Strip non-digits
                    let rawValue = this.formattedJumlah.replace(/\D/g, '');
                    let number = rawValue === '' ? 0 : parseInt(rawValue);

                    if (this.formData.isManual && number > 45000) {
                        Swal.fire({
                            title: 'Batas Terlampaui',
                            text: 'Untuk pack buntut, jumlah bilyet tidak boleh melebihi 45.000.',
                            icon: 'warning',
                            confirmButtonColor: '#4f46e5'
                        });
                        number = 45000;
                    }

                    this.formData.jumlah = number;

                    // Reformat
                    this.formattedJumlah = this.formatNumber(this.formData.jumlah);

                    // Restore cursor position roughly
                    this.$nextTick(() => {
                        let newLength = this.formattedJumlah.length;
                        let diff = newLength - oldLength;
                        e.target.setSelectionRange(cursorPosition + diff, cursorPosition + diff);
                    });

                    this.calculatePacks();
                },

                handleBatchInput(e) {
                    this.formData.batch = e.target.value.replace(/\D/g, '').substring(0, 7);
                    this.formData.batch = this.formData.batch.substring(0, 7);
                },

                handleSeriInput(e) {
                    let cursorPosition = e.target.selectionStart;
                    let oldVal = e.target.value;
                    let val = oldVal.toUpperCase().replace(/[^A-Z0-9]/g, '');
                    let formatted = '';

                    if (val.length > 0) {
                        // First 2 chars: Letters only
                        formatted = val.substring(0, 2).replace(/[^A-Z]/g, '');
                        if (val.length > 2) {
                            // Chars 3-4 (after hyphen): Letters only
                            formatted += '-' + val.substring(2, 4).replace(/[^A-Z0-9]/g, '');
                            // Note: use A-Z0-9 for intermediate typing, but final check is XX-XX#
                            if (val.length > 4) {
                                // Char 5: Number only
                                formatted += val.substring(4, 6).replace(/[^0-9]/g, '');
                            }
                        }
                    }

                    const finalVal = formatted.substring(0, 6);
                    this.formData.seri = finalVal;
                    e.target.value = finalVal;

                    // Restore cursor position
                    this.$nextTick(() => {
                        let newPos = cursorPosition;
                        // If we just added a hyphen and the cursor was after the 2nd char
                        if (oldVal.length < finalVal.length && cursorPosition === 3 && finalVal.charAt(2) === '-') {
                            newPos = 4;
                        }
                        e.target.setSelectionRange(newPos, newPos);
                    });
                },

                formatNumber(n) {
                    if (n === 0 || !n) return '';
                    return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                },

                fetchPackStatus() {
                    if (!this.formData.batch || !this.formData.seri) {
                        this.packStatuses = {};
                        return;
                    }

                    this.isLoading = true;
                    // Debounce simple
                    clearTimeout(this.fetchTimeout);
                    this.fetchTimeout = setTimeout(() => {
                        const params = new URLSearchParams({
                            batch: this.formData.batch,
                            seri: this.formData.seri,
                            pecahan: this.formData.pecahan,
                            emisi: this.formData.emisi,
                            tahun_anggaran: this.formData.tahun_anggaran,
                            exclude_id: this.excludeId
                        });

                        fetch(`{{ route('hcs-khazai-registration.get-pack-status') }}?${params}`)
                            .then(res => res.json())
                            .then(data => {
                                this.packStatuses = data;
                                // Clean up selected packs if they are now occupied
                                this.selectedPacks = this.selectedPacks.filter(n => !this.packStatuses[n]);
                            })
                            .finally(() => this.isLoading = false);
                    }, 500);
                },

                getPackClass(n) {
                    // Status priority: Session Selection > Sorted > Received > Pending
                    const baseClasses = 'pack-btn w-full h-8 sm:h-9 flex items-center justify-center text-[10px] font-black rounded-xl transition-all duration-300 focus:outline-none focus:ring-4';

                    if (this.selectedPacks.includes(n)) {
                        return `${baseClasses} bg-indigo-500 text-white shadow-xl shadow-indigo-300/50 border-indigo-600 scale-105 z-10`;
                    }

                    const status = this.packStatuses[n];
                    if (!status) {
                        return `${baseClasses} bg-white text-gray-400 border-gray-100 hover:bg-gray-50 focus:ring-indigo-500/20`;
                    }

                    if (status.status === 'sorted') {
                        return `${baseClasses} bg-red-600 text-white border-red-700 shadow-lg cursor-not-allowed opacity-90`;
                    }

                    const isCP = status.supplier === 'Cutpack';
                    if (status.status === 'received' || status.status === 'pending') {
                        return isCP
                            ? `${baseClasses} bg-blue-700 text-white border-blue-800 shadow-md cursor-not-allowed`
                            : `${baseClasses} bg-green-700 text-white border-green-800 shadow-md cursor-not-allowed`;
                    }

                    return `${baseClasses} bg-gray-50 text-gray-400 border-gray-100`;
                },

                getPackTooltip(n) {
                    const status = this.packStatuses[n];
                    if (!status) return `Pack ${n} - Tersedia`;

                    const supplier = status.supplier === 'Cutpack' ? 'CP' : 'RK';
                    let label = '';
                    if (status.status === 'sorted') label = 'Sudah Disortir';
                    else if (status.status === 'received') label = 'Sudah Diterima';
                    else if (status.status === 'pending') label = 'Pending Registrasi';

                    return `Pack ${n} [${supplier}] - ${label}`;
                },

                togglePack(n) {
                    if (this.packStatuses[n]) return;

                    if (this.isBulkSelect) {
                        this.toggleBlock(n);
                    } else {
                        this.toggleSingle(n);
                    }
                },

                toggleBlock(n) {
                    if (this.selectedPacks.includes(n)) {
                        this.selectedPacks = this.selectedPacks.filter(i => i !== n);
                        return;
                    }

                    const blockStart = Math.floor((n - 1) / 10) * 10 + 1;
                    const blockEnd = blockStart + 9;

                    const available = [];
                    for (let i = blockStart; i <= blockEnd; i++) {
                        if (this.packStatuses[i]) continue;
                        if (this.selectedPacks.includes(i)) continue;
                        available.push(i);
                    }

                    const remaining = this.packsNeeded - this.selectedPacks.length;
                    if (remaining <= 0) {
                        Swal.fire({
                            title: 'Batas Terlampaui',
                            text: `Anda sudah memilih ${this.packsNeeded} pack.`,
                            icon: 'warning',
                            confirmButtonColor: '#4f46e5'
                        });
                        return;
                    }

                    const toSelect = available.slice(0, remaining);
                    if (toSelect.length === 0) {
                        Swal.fire({
                            title: 'Pack Tidak Tersedia',
                            text: 'Semua pack dalam blok ini sudah terisi atau sudah dipilih.',
                            icon: 'info',
                            confirmButtonColor: '#4f46e5'
                        });
                        return;
                    }

                    const blockAlreadySelected = this.selectedPacks.some(p => p >= blockStart && p <= blockEnd);
                    if (blockAlreadySelected) {
                        this.selectedPacks = this.selectedPacks.filter(p => p < blockStart || p > blockEnd);
                    } else {
                        this.selectedPacks.push(...toSelect);
                    }
                    this.selectedPacks.sort((a, b) => a - b);
                },

                toggleSingle(n) {
                    if (this.selectedPacks.includes(n)) {
                        this.selectedPacks = this.selectedPacks.filter(i => i !== n);
                    } else {
                        if (this.selectedPacks.length < this.packsNeeded) {
                            this.selectedPacks.push(n);
                            this.selectedPacks.sort((a, b) => a - b);
                        } else {
                            if (this.packsNeeded > 0) {
                                Swal.fire({
                                    title: 'Batas Terlampaui',
                                    text: `Anda hanya dapat memilih ${this.packsNeeded} pack sesuai dengan jumlah bilyet.`,
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
                },

                handleSubmit(e) {
                    if (this.formData.jumlah <= 0 || this.packsNeeded <= 0) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Data Tidak Valid',
                            text: 'Silakan masukkan Jumlah Bilyet yang valid terlebih dahulu.',
                            icon: 'error',
                            confirmButtonColor: '#4f46e5'
                        });
                        return;
                    }

                    if (this.selectedPacks.length === 0) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Pack Belum Dipilih',
                            text: `Anda belum memilih pack apapun. Silakan pilih ${this.packsNeeded} pack pada grid di sebelah kanan.`,
                            icon: 'warning',
                            confirmButtonColor: '#4f46e5'
                        });
                        return;
                    }

                    if (this.selectedPacks.length !== this.packsNeeded) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Jumlah Pack Tidak Sesuai',
                            text: `Jumlah packs yang dipilih (${this.selectedPacks.length}) tidak sesuai kebutuhan. Dibutuhkan ${this.packsNeeded} pack(s).`,
                            icon: 'error',
                            confirmButtonColor: '#4f46e5'
                        });
                        return;
                    }

                    if (!this.formData.isManual && (this.formData.jumlah % 45000 !== 0)) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Jumlah Bilyet Tidak Valid',
                            text: 'Jumlah bilyet harus kelipatan 45.000 jika tidak menggunakan mode pack buntut.',
                            icon: 'error',
                            confirmButtonColor: '#4f46e5'
                        });
                        return;
                    }
                },

                getTooltipClasses(n) {
                    const row = (n - 1) % 10;
                    const col = Math.floor((n - 1) / 10);
                    const vClass = (row < 4) ? 'top-full mt-2 flex-col-reverse' : 'bottom-full mb-2 flex-col';
                    const hClass = (col < 3) ? 'left-0 translate-x-0' : (col > 6 ? 'right-0 left-auto translate-x-0' : 'left-1/2 -translate-x-1/2');
                    return `${vClass} ${hClass}`;
                },

                getArrowClasses(n) {
                    const row = (n - 1) % 10;
                    const col = Math.floor((n - 1) / 10);
                    const arrowV = (row < 4) ? '-mb-1' : '-mt-1';
                    const arrowH = (col < 3) ? 'left-3 translate-x-0' : (col > 6 ? 'right-3 translate-x-0' : 'left-1/2 -translate-x-1/2');
                    return `${arrowV} ${arrowH}`;
                },

                init() {
                    // Initialize formatted value
                    this.formattedJumlah = this.formatNumber(this.formData.jumlah);

                    this.calculatePacks();
                    this.fetchPackStatus(); // Initial fetch

                    this.$watch('formData.isManual', (val) => {
                        if (val && this.formData.jumlah > 45000) {
                            Swal.fire({
                                title: 'Batas Terlampaui',
                                text: 'Untuk pack buntut, jumlah bilyet tidak boleh melebihi 45.000.',
                                icon: 'warning',
                                confirmButtonColor: '#4f46e5'
                            });
                            this.formData.jumlah = 45000;
                            this.formattedJumlah = this.formatNumber(45000);
                        }
                        this.calculatePacks();
                        this.selectedPacks = [];
                    });

                    // Watch for metadata changes to refetch status
                    this.$watch('formData.batch', () => this.fetchPackStatus());
                    this.$watch('formData.seri', () => this.fetchPackStatus());
                    this.$watch('formData.pecahan', () => this.fetchPackStatus());
                    this.$watch('formData.emisi', () => this.fetchPackStatus());
                    this.$watch('formData.tahun_anggaran', () => this.fetchPackStatus());
                }
            }
        }
    </script>
</x-app-layout>