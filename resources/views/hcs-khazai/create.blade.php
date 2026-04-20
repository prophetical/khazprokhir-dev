<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-lg text-gray-800 leading-tight tracking-tighter">
            {{ isset($hcsKhazaiRegistration) ? __('Edit Registrasi HCS') : __('Registrasi HCS Baru') }}
        </h2>
    </x-slot>

    <div class="py-4 text-[10px]" x-data="hcsKhazaiForm()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ isset($hcsKhazaiRegistration) ? route('hcs-khazai-registration.update', $hcsKhazaiRegistration->id) : route('hcs-khazai-registration.store') }}" 
                  method="POST" class="space-y-4">
                @csrf
                @if(isset($hcsKhazaiRegistration)) @method('PUT') @endif

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                    <!-- Left: Combined Info & Metadata -->
                    <div class="lg:col-span-4 space-y-4">
                        <div class="bg-white p-5 rounded-2xl shadow-xl border border-gray-100 flex flex-col gap-4">
                            <div class="flex items-center gap-2 mb-1">
                                <div class="w-1 h-6 bg-indigo-600 rounded-full"></div>
                                <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest">Informasi Dokumen</h3>
                            </div>

                            <div class="space-y-3">
                                <div>
                                    <label class="block font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Nomor Bon</label>
                                    <input type="text" name="nomor_bon" required value="{{ old('nomor_bon', $hcsKhazaiRegistration->nomor_bon ?? '') }}" 
                                        class="w-full border-gray-200 rounded-lg font-bold py-2 px-3 text-xs focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-sm">
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Tanggal</label>
                                        <input type="date" name="tanggal_pembuatan" required value="{{ old('tanggal_pembuatan', isset($hcsKhazaiRegistration) ? $hcsKhazaiRegistration->tanggal_pembuatan->format('Y-m-d') : date('Y-m-d')) }}" 
                                            class="w-full border-gray-200 rounded-lg font-bold py-2 px-3 text-xs focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-sm">
                                    </div>
                                    <div>
                                        <label class="block font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Batch</label>
                                        <input type="text" name="batch" required maxlength="7" x-model="formData.batch"
                                            class="w-full border-gray-200 rounded-lg font-bold py-2 px-3 text-xs focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-sm text-center">
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-3">
                                    <div>
                                        <label class="block font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Pecahan</label>
                                        <select name="pecahan" required x-model="formData.pecahan"
                                            class="w-full border-gray-200 rounded-lg font-bold py-2 px-3 text-xs focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-sm appearance-none">
                                            <option value="">Pilih</option>
                                            @foreach(['S'=>'1.000','T'=>'2.000','U'=>'5.000','V'=>'10.000','W'=>'20.000','X'=>'50.000','Y'=>'100.000'] as $k => $v)
                                                <option value="{{ $k }}">{{ $k }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Seri</label>
                                        <input type="text" name="seri" required x-model="formData.seri"
                                            class="w-full border-gray-200 rounded-lg font-bold py-2 px-3 text-xs focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-sm text-center">
                                    </div>
                                    <div>
                                        <label class="block font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Emisi</label>
                                        <input type="text" name="emisi" required x-model="formData.emisi"
                                            class="w-full border-gray-200 rounded-lg font-bold py-2 px-3 text-xs focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-sm text-center">
                                    </div>
                                </div>
                            </div>

                            <div class="h-px bg-gray-100 my-1"></div>

                            <div class="flex items-center gap-2 mb-1">
                                <div class="w-1 h-6 bg-amber-500 rounded-full"></div>
                                <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest">Metadata Gilir</h3>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Gilir</label>
                                    <select name="gilir" required class="w-full border-gray-200 rounded-lg font-bold py-2 px-3 text-xs focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-sm">
                                        @foreach(['Gilir 1', 'Gilir 2', 'Gilir 3'] as $g)
                                            <option value="{{ $g }}" {{ (old('gilir', $hcsKhazaiRegistration->gilir ?? '') == $g) ? 'selected' : '' }}>{{ $g }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Tahun</label>
                                    <select name="tahun_anggaran" required class="w-full border-gray-200 rounded-lg font-bold py-2 px-3 text-xs focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-sm">
                                        @foreach(['2024','2025','2026','2027'] as $t)
                                            <option value="{{ $t }}" {{ (old('tahun_anggaran', $hcsKhazaiRegistration->tahun_anggaran ?? '2026') == $t) ? 'selected' : '' }}>{{ $t }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Mesin</label>
                                    <input type="text" name="mesin" required value="{{ old('mesin', $hcsKhazaiRegistration->mesin ?? 'KBA 1') }}" 
                                        class="w-full border-gray-200 rounded-lg font-bold py-2 px-3 text-xs focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-sm">
                                </div>
                                <div>
                                    <label class="block font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Supplier</label>
                                    <select name="supplier" required class="w-full border-gray-200 rounded-lg font-bold py-2 px-3 text-xs focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-sm">
                                        <option value="Cutpack" {{ (old('supplier', $hcsKhazaiRegistration->supplier ?? '') == 'Cutpack') ? 'selected' : '' }}>Cutpack (CP)</option>
                                        <option value="Rikyet" {{ (old('supplier', $hcsKhazaiRegistration->supplier ?? '') == 'Rikyet') ? 'selected' : '' }}>Rikyet (RK)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-50">
                                <button type="submit" :disabled="selectedPacks.length !== packsNeeded" 
                                    class="w-full py-3.5 bg-indigo-600 text-white rounded-xl font-black text-[10px] uppercase tracking-[0.2em] shadow-lg shadow-indigo-100 hover:bg-indigo-700 disabled:opacity-50 disabled:grayscale transition-all active:scale-95">
                                    {{ isset($hcsKhazaiRegistration) ? 'Perbarui Data' : 'Simpan & Generate Barcode' }}
                                </button>
                                <a href="{{ route('hcs-khazai-registration.index') }}" class="block w-full mt-3 py-3 text-gray-400 rounded-xl font-black text-[9px] text-center uppercase tracking-[0.2em] border border-gray-100 hover:bg-gray-50 transition-all">Batal</a>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Pack Grid Selection -->
                    <div class="lg:col-span-8">
                        <div class="bg-white p-5 rounded-2xl shadow-xl border border-gray-100 space-y-4 h-full">
                            <div class="flex items-center justify-between mb-1">
                                <div class="flex items-center gap-2">
                                    <div class="w-1 h-6 bg-emerald-500 rounded-full"></div>
                                    <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest">Detail Pack (Vertical Grid)</h3>
                                </div>
                                <div class="flex items-center gap-3 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100">
                                    <span class="font-black text-gray-400 uppercase text-[9px]">Manual:</span>
                                    <label class="relative inline-flex items-center cursor-pointer scale-75">
                                        <input type="checkbox" x-model="formData.isManual" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-500/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                                    </label>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 items-end bg-slate-50 p-4 rounded-xl border border-slate-100">
                                <div>
                                    <label class="block font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Jumlah Bilyet Total</label>
                                    <input type="number" name="jumlah" required x-model.number="formData.jumlah" x-on:input="calculatePacks()"
                                        class="w-full border-gray-200 rounded-lg font-black py-3 px-4 text-base text-indigo-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-inner">
                                </div>
                                <div class="flex items-center justify-center gap-4">
                                    <div class="text-center">
                                        <p class="text-[8px] font-black text-emerald-600 uppercase tracking-widest mb-0.5">Estimasi Pack</p>
                                        <p class="text-xl font-black text-emerald-700 leading-none" x-text="packsNeeded"></p>
                                    </div>
                                    <div class="w-px h-8 bg-emerald-200"></div>
                                    <div class="text-center">
                                        <p class="text-[8px] font-black text-indigo-600 uppercase tracking-widest mb-0.5">Terpilih</p>
                                        <p class="text-xl font-black text-indigo-700 leading-none" x-text="selectedPacks.length"></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Legend -->
                            <div class="bg-gray-50/50 p-2.5 rounded-xl border border-gray-100 flex flex-wrap gap-x-4 gap-y-2 mt-1">
                                <div class="flex items-center gap-1.5 grayscale-[0.5]">
                                    <div class="w-3 h-3 bg-white border border-gray-200 rounded shadow-xs"></div>
                                    <span class="text-[8px] font-bold text-gray-400 uppercase">Kosong</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <div class="w-3 h-3 bg-blue-300 rounded shadow-xs border border-blue-400/20"></div>
                                    <span class="text-[8px] font-bold text-blue-600 uppercase">Cutpack</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <div class="w-3 h-3 bg-green-300 rounded shadow-xs border border-green-400/20"></div>
                                    <span class="text-[8px] font-bold text-green-600 uppercase">Rikyet</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <div class="w-3 h-3 bg-red-500 rounded shadow-xs border border-red-600/20"></div>
                                    <span class="text-[8px] font-bold text-red-600 uppercase">Sorted</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <div class="w-3 h-3 bg-indigo-500 rounded shadow-xs"></div>
                                    <span class="text-[8px] font-bold text-indigo-600 uppercase">Sesi Ini</span>
                                </div>
                            </div>

                            <!-- Pack Grid -->
                            <div class="mt-2 border-t border-gray-50 pt-3 relative">
                                <template x-if="isLoading">
                                    <div class="absolute inset-0 bg-white/60 backdrop-blur-xs z-50 flex items-center justify-center rounded-xl">
                                        <div class="animate-spin rounded-full h-6 w-6 border-2 border-indigo-500 border-t-transparent"></div>
                                    </div>
                                </template>

                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2 flex items-center justify-between">
                                    <span>Pilih Nomor Pack (1-10 Vertikal)</span>
                                    <span x-show="formData.batch && formData.seri" class="text-[8px] text-indigo-400 normal-case font-bold" x-text="'Ref: ' + formData.batch + ' ' + formData.seri"></span>
                                </p>
                                
                                <div class="grid grid-flow-col grid-rows-10 gap-1.5 overflow-x-auto pb-2 min-h-[350px]">
                                    <template x-for="n in 100">
                                        <div @click="togglePack(n)" 
                                            :class="getPackClass(n)"
                                            class="h-8 w-11 flex flex-col items-center justify-center rounded-md font-black text-[9px] cursor-pointer transition-all duration-200 select-none border shrink-0 relative group"
                                            :title="getPackTooltip(n)">
                                            <span x-text="n"></span>
                                            <!-- Tiny Indicator for Source -->
                                            <template x-if="packStatuses[n]">
                                                <div class="absolute -top-1 -right-1 w-2 h-2 rounded-full border border-white shadow-xs"
                                                     :class="packStatuses[n].status === 'sorted' ? 'bg-red-600' : (packStatuses[n].status === 'received' ? 'bg-indigo-600' : 'bg-amber-400')">
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                                <div class="mt-2">
                                    <template x-for="p in selectedPacks">
                                        <input type="hidden" name="packs[]" :value="p">
                                    </template>
                                </div>
                                <p x-show="selectedPacks.length !== packsNeeded" class="mt-3 text-rose-500 font-bold animate-pulse text-[9px]">
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
                    pecahan: '{{ old('pecahan', $hcsKhazaiRegistration->pecahan ?? '') }}',
                    jumlah: {{ old('jumlah', $hcsKhazaiRegistration->jumlah ?? 0) }},
                    batch: '{{ old('batch', $hcsKhazaiRegistration->batch ?? '') }}',
                    seri: '{{ old('seri', $hcsKhazaiRegistration->seri ?? '') }}',
                    emisi: '{{ old('emisi', $hcsKhazaiRegistration->emisi ?? '2022') }}',
                    tahun_anggaran: '{{ old('tahun_anggaran', $hcsKhazaiRegistration->tahun_anggaran ?? '2025') }}',
                    supplier: '{{ old('supplier', $hcsKhazaiRegistration->supplier ?? 'Cutpack') }}',
                    isManual: false,
                },
                selectedPacks: @json($hcsKhazaiRegistration->packs_data ?? []),
                packStatuses: {},
                packsNeeded: 0,
                isLoading: false,
                excludeId: '{{ $hcsKhazaiRegistration->id ?? "" }}',

                calculatePacks() {
                    if (this.formData.isManual) {
                        this.packsNeeded = this.formData.jumlah > 0 ? 1 : 0;
                    } else {
                        this.packsNeeded = Math.floor(this.formData.jumlah / 45000);
                    }
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
                    if (this.selectedPacks.includes(n)) {
                        return 'bg-indigo-600 text-white shadow-lg border-indigo-700 scale-105 z-10';
                    }

                    const status = this.packStatuses[n];
                    if (!status) {
                        return 'bg-white text-gray-400 border-gray-100 hover:bg-gray-50';
                    }

                    if (status.status === 'sorted') {
                        return 'bg-red-500 text-white border-red-600 opacity-90 cursor-not-allowed';
                    }

                    const isCP = status.supplier === 'Cutpack';
                    if (status.status === 'received') {
                        return isCP 
                            ? 'bg-blue-300 text-blue-900 border-blue-400 cursor-not-allowed shadow-inner' 
                            : 'bg-green-300 text-green-900 border-green-400 cursor-not-allowed shadow-inner';
                    }

                    if (status.status === 'pending') {
                        return isCP 
                            ? 'bg-blue-100 text-blue-700 border-blue-200 cursor-not-allowed border-dashed' 
                            : 'bg-green-100 text-green-700 border-green-200 cursor-not-allowed border-dashed';
                    }

                    return 'bg-gray-50 text-gray-400 border-gray-100';
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
                    // Prevent selecting if occupied
                    if (this.packStatuses[n]) return;

                    if (this.selectedPacks.includes(n)) {
                        this.selectedPacks = this.selectedPacks.filter(i => i !== n);
                    } else {
                        if (this.selectedPacks.length < this.packsNeeded) {
                            this.selectedPacks.push(n);
                            this.selectedPacks.sort((a,b) => a-b);
                        }
                    }
                },

                init() {
                    this.calculatePacks();
                    this.fetchPackStatus(); // Initial fetch

                    this.$watch('formData.isManual', () => {
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
