<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Input Penyerahan ke BI') }}
        </h2>
    </x-slot>

    @php
        $themeClasses = [
            'S' => ['bg' => 'bg-lime-500', 'border' => 'border-lime-500', 'ring' => 'focus:ring-lime-500', 'focus' => 'focus:border-lime-500', 'btn' => 'bg-lime-500'],
            'T' => ['bg' => 'bg-gray-400', 'border' => 'border-gray-400', 'ring' => 'focus:ring-gray-400', 'focus' => 'focus:border-gray-400', 'btn' => 'bg-gray-400'],
            'U' => ['bg' => 'bg-amber-400', 'border' => 'border-amber-400', 'ring' => 'focus:ring-amber-400', 'focus' => 'focus:border-amber-400', 'btn' => 'bg-amber-400'],
            'V' => ['bg' => 'bg-purple-500', 'border' => 'border-purple-500', 'ring' => 'focus:ring-purple-500', 'focus' => 'focus:border-purple-500', 'btn' => 'bg-purple-500'],
            'W' => ['bg' => 'bg-green-500', 'border' => 'border-green-500', 'ring' => 'focus:ring-green-500', 'focus' => 'focus:border-green-500', 'btn' => 'bg-green-500'],
            'X' => ['bg' => 'bg-blue-500', 'border' => 'border-blue-500', 'ring' => 'focus:ring-blue-500', 'focus' => 'focus:border-blue-500', 'btn' => 'bg-blue-500'],
            'Y' => ['bg' => 'bg-red-500', 'border' => 'border-red-500', 'ring' => 'focus:ring-red-500', 'focus' => 'focus:border-red-500', 'btn' => 'bg-red-500'],
        ];
    @endphp

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- Panel: Peringatan nomor dus belum dikemas --}}
            @if($missingWarnings->isNotEmpty())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-xl shadow-sm overflow-hidden">
                    <div class="flex items-center gap-3 px-5 py-3 bg-red-100 border-b border-red-200">
                        <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="font-bold text-red-800 text-sm">{{ $missingWarnings->count() }} Penyerahan Belum Lengkap
                            — Nomor Dus Belum Dikemas</h3>
                    </div>
                    <div class="divide-y divide-red-100">
                        @foreach($missingWarnings as $warn)
                            @php
                                $colorMap = ['S' => 'bg-lime-500', 'T' => 'bg-gray-400', 'U' => 'bg-amber-400', 'V' => 'bg-purple-500', 'W' => 'bg-green-500', 'X' => 'bg-blue-500', 'Y' => 'bg-red-500'];
                                $bgColor = $colorMap[$warn['pecahan']] ?? 'bg-gray-400';
                            @endphp
                            <div class="px-5 py-3 flex flex-wrap items-start gap-3">
                                <span
                                    class="{{ $bgColor }} text-white text-[9px] font-black px-2 py-0.5 rounded shrink-0 mt-0.5">{{ $warn['pecahan'] }}</span>
                                <div class="flex flex-wrap gap-2 items-center flex-1">
                                    <span class="text-[10px] font-bold text-gray-500">TA {{ $warn['tahun_anggaran'] }}</span>
                                    <span class="text-gray-300">·</span>
                                    <span class="text-[10px] font-bold text-gray-500">TE {{ $warn['tahun_emisi'] }}</span>
                                    <span class="text-gray-300">·</span>
                                    <span class="text-[10px] font-bold text-indigo-600 font-mono">Penyerahan:
                                        {{ $warn['nomor_range'] }}</span>
                                    <span class="text-gray-300">·</span>
                                    <span class="text-[10px] font-bold text-red-500 font-mono">BA:
                                        {{ $warn['nomor_ba'] }}</span>
                                </div>
                                <div class="w-full pl-7 mt-1">
                                    <span class="text-[10px] text-red-500 font-bold">Nomor Belum Dikemas
                                        ({{ $warn['missing_count'] }} dus):</span>
                                    <span
                                        class="text-[10px] font-mono font-bold text-red-800 ml-1 bg-red-100 px-2 py-0.5 rounded">{{ $warn['missing_ranges'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Peringatan dari sesi setelah redirect --}}
            @if(session('warning_penyerahan'))
                @php $warn = session('warning_penyerahan'); @endphp
                <div class="mb-6 bg-amber-50 border-l-4 border-amber-400 p-5 rounded-r-lg shadow-sm">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                        </svg>
                        <div>
                            <h3 class="font-bold text-amber-800 text-sm">Peringatan: Sebagian Nomor Dus Belum Ada di Sistem
                            </h3>
                            <p class="text-amber-700 text-xs mt-1">Data penyerahan berhasil disimpan, namun terdapat nomor
                                dus yang belum memiliki data pengemasan.</p>
                            <div class="mt-3 grid grid-cols-2 md:grid-cols-5 gap-2 text-xs">
                                <div class="bg-amber-100 rounded p-2"><span class="text-amber-500">Range Diminta</span>
                                    <div class="font-bold text-amber-800">{{ $warn['range_diminta'] }}</div>
                                </div>
                                <div class="bg-amber-100 rounded p-2"><span class="text-amber-500">Jml Diminta</span>
                                    <div class="font-bold text-amber-800">{{ $warn['jumlah_diminta'] }}</div>
                                </div>
                                <div class="bg-green-100 rounded p-2"><span class="text-green-500">Ada di Sistem</span>
                                    <div class="font-bold text-green-800">{{ $warn['jumlah_ada'] }}</div>
                                </div>
                                <div class="bg-red-100 rounded p-2"><span class="text-red-500">Belum Ada</span>
                                    <div class="font-bold text-red-800">{{ $warn['jumlah_belum_ada'] }}</div>
                                </div>
                                <div class="bg-gray-100 rounded p-2"><span class="text-gray-500">Est. Bilyet Belum
                                        Tercatat</span>
                                    <div class="font-bold text-gray-800">
                                        {{ number_format($warn['estimasi_bilyet'], 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div x-data="{
                selectedPecahan: '{{ old('pecahan', '') }}',
                tahunEmisi: '{{ old('tahun_emisi', '') }}',
                tahunAnggaran: '{{ old('tahun_anggaran', '') }}',
                themes: {{ json_encode($themeClasses) }},
                noAwal: {{ old('nomor_dus_awal', 0) }},
                noAkhir: {{ old('nomor_dus_akhir', 0) }},
                bilyetRaw: {{ old('jumlah_bilyet', 0) }},
                bilyetFormatted: '',
                dupWarning: null,
                isChecking: false,
                checkTimeout: null,
                autoDus: true,
                lastDusAkhir: 0,
                fetchingLast: false,

                get currentTheme() { return this.themes[this.selectedPecahan] || null },
                get jumlahDus() {
                    let a = parseInt(this.noAwal) || 0;
                    let b = parseInt(this.noAkhir) || 0;
                    return (b >= a && a > 0) ? (b - a + 1) : 0;
                },
                get dusFromBilyet() {
                    return this.bilyetRaw > 0 ? Math.ceil(this.bilyetRaw / 20000) : 0;
                },
                init() {
                    // Inisialisasi format ribuan
                    if (this.bilyetRaw > 0) this.bilyetFormatted = this.formatRibuan(this.bilyetRaw);
                    this.$watch('bilyetFormatted', (val) => {
                        let numeric = val.replace(/\./g, '');
                        this.bilyetRaw = parseInt(numeric) || 0;
                        if (this.autoDus) this.applyAutoDus();
                    });
                    // Watch trigger cek duplikasi
                    ['selectedPecahan','tahunEmisi','tahunAnggaran','noAwal','noAkhir'].forEach(f => {
                        this.$watch(f, () => this.scheduleDupCheck());
                    });
                    // Auto-fill range dus dari penyerahan terakhir saat filter berubah
                    ['selectedPecahan','tahunAnggaran','tahunEmisi'].forEach(f => {
                        this.$watch(f, () => this.fetchLastDus());
                    });
                    this.$watch('autoDus', (val) => { if (val) this.fetchLastDus(); });
                    // Ambil dus terakhir saat load (jika filter sudah terisi)
                    this.fetchLastDus();
                },
                formatRibuan(n) {
                    if (!n) return '';
                    return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                },
                onBilyetInput(e) {
                    let raw = e.target.value.replace(/\./g, '');
                    if (raw === '' || isNaN(raw)) { this.bilyetFormatted = ''; this.bilyetRaw = 0; return; }
                    this.bilyetFormatted = this.formatRibuan(parseInt(raw));
                    e.target.value = this.bilyetFormatted;
                },
                scheduleDupCheck() {
                    clearTimeout(this.checkTimeout);
                    this.dupWarning = null;
                    if (!this.selectedPecahan || !this.tahunEmisi || !this.tahunAnggaran || !this.noAwal || !this.noAkhir) return;
                    this.checkTimeout = setTimeout(() => this.checkDuplicate(), 600);
                },
                async checkDuplicate() {
                    if (parseInt(this.noAkhir) < parseInt(this.noAwal)) return;
                    this.isChecking = true;
                    try {
                        const params = new URLSearchParams({
                            pecahan: this.selectedPecahan,
                            tahun_emisi: this.tahunEmisi,
                            tahun_anggaran: this.tahunAnggaran,
                            nomor_dus_awal: this.noAwal,
                            nomor_dus_akhir: this.noAkhir,
                        });
                        const res = await fetch('/api/penyerahan-bi/check-duplicate?' + params.toString(), {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        const data = await res.json();
                        this.dupWarning = data.duplicate ? data : null;
                    } catch(e) {}
                    this.isChecking = false;
                },
                applyAutoDus() {
                    if (!this.autoDus) return;
                    const jumlahDus = this.dusFromBilyet;
                    if (jumlahDus <= 0) return;
                    const awal = (parseInt(this.lastDusAkhir) || 0) + 1;
                    this.noAwal = awal;
                    this.noAkhir = awal + jumlahDus - 1;
                },
                async fetchLastDus() {
                    if (!this.selectedPecahan || !this.tahunAnggaran || !this.tahunEmisi) {
                        this.lastDusAkhir = 0;
                        this.applyAutoDus();
                        return;
                    }
                    this.fetchingLast = true;
                    try {
                        const params = new URLSearchParams({
                            pecahan: this.selectedPecahan,
                            tahun_anggaran: this.tahunAnggaran,
                            tahun_emisi: this.tahunEmisi,
                        });
                        const res = await fetch('/api/penyerahan-bi/last-dus?' + params.toString(), {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        const data = await res.json();
                        this.lastDusAkhir = (data.last_dus_akhir || 0);
                    } catch(e) {
                        this.lastDusAkhir = 0;
                    }
                    this.fetchingLast = false;
                    this.applyAutoDus();
                }
            }" class="bg-white overflow-hidden shadow-sm rounded-xl border-t-4 transition-all duration-500"
                :class="currentTheme ? currentTheme.border : 'border-indigo-500'">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-md font-bold text-indigo-800">Form Input Penyerahan ke BI</h2>
                        <a href="{{ route('penyerahan-bi.index') }}"
                            class="text-sm text-gray-500 hover:text-indigo-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Laporan Penyerahan
                        </a>
                    </div>

                    @if($errors->any())
                        <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
                            <ul class="text-red-700 text-sm space-y-1">
                                @foreach($errors->all() as $err)
                                    <li>• {{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Peringatan duplikasi (real-time) --}}
                    <template x-if="dupWarning">
                        <div class="mb-5 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                </svg>
                                <div>
                                    <h4 class="font-bold text-red-800 text-sm">Range Nomor Dus Sudah Ada di Sistem!</h4>
                                    <p class="text-red-700 text-xs mt-1">Range nomor dus ini sebelumnya sudah diinput
                                        dengan identitas yang sama. Periksa kembali data Anda sebelum menyimpan.</p>
                                    <div class="mt-2 grid grid-cols-3 gap-2 text-xs">
                                        <div class="bg-red-100 rounded p-2"><span class="text-red-400">Nomor BA</span>
                                            <div class="font-bold text-red-800 font-mono text-[11px]"
                                                x-text="dupWarning.nomor_ba"></div>
                                        </div>
                                        <div class="bg-red-100 rounded p-2"><span class="text-red-400">Range
                                                Tersimpan</span>
                                            <div class="font-bold text-red-800" x-text="dupWarning.range_tersimpan">
                                            </div>
                                        </div>
                                        <div class="bg-red-100 rounded p-2"><span class="text-red-400">Tanggal</span>
                                            <div class="font-bold text-red-800" x-text="dupWarning.tanggal"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <form method="POST" action="{{ route('penyerahan-bi.store') }}">
                        @csrf
                        {{-- Hidden field untuk nilai numerik bilyet --}}
                        <input type="hidden" name="jumlah_bilyet" :value="bilyetRaw">

                        {{-- Baris 1: Tanggal, Nomor BA, Pecahan --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Tanggal
                                    Penyerahan</label>
                                <input type="date" name="tanggal_penyerahan"
                                    value="{{ old('tanggal_penyerahan', date('Y-m-d')) }}"
                                    class="block w-full border-gray-200 rounded-lg shadow-sm text-sm py-2.5 px-3 transition-all focus:ring-opacity-50"
                                    :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'"
                                    required>
                            </div>
                            <div class="md:col-span-2">
                                <label
                                    class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Nomor
                                    Berita Acara (BA)</label>
                                <input type="text" name="nomor_ba" value="{{ old('nomor_ba') }}"
                                    placeholder="Contoh: BA-123/BI/2025"
                                    class="block w-full border-gray-200 rounded-lg shadow-sm text-sm py-2.5 px-3 transition-all focus:ring-opacity-50 font-mono"
                                    :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'"
                                    required>
                            </div>
                        </div>

                        {{-- Baris 2: Pecahan, TA, TE, Jumlah Bilyet --}}
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-5 mb-5">
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Pecahan</label>
                                <select name="pecahan" x-model="selectedPecahan"
                                    class="block w-full border-gray-200 rounded-lg shadow-sm text-sm py-2.5 px-3 transition-all font-bold focus:ring-opacity-50"
                                    :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'"
                                    required>
                                    <option value="">-- Pilih --</option>
                                    @foreach(['S', 'T', 'U', 'V', 'W', 'X', 'Y'] as $p)
                                        <option value="{{ $p }}" {{ old('pecahan') == $p ? 'selected' : '' }}>{{ $p }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Tahun
                                    Anggaran</label>
                                <select name="tahun_anggaran" x-model="tahunAnggaran"
                                    class="block w-full border-gray-200 rounded-lg shadow-sm text-sm py-2.5 px-3 transition-all font-bold focus:ring-opacity-50"
                                    :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'"
                                    required>
                                    <option value="">-- Pilih --</option>
                                    @foreach($availableYears as $ta)
                                        <option value="{{ $ta }}" {{ old('tahun_anggaran') == $ta ? 'selected' : '' }}>
                                            {{ $ta }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Tahun
                                    Emisi</label>
                                <select name="tahun_emisi" x-model="tahunEmisi"
                                    class="block w-full border-gray-200 rounded-lg shadow-sm text-sm py-2.5 px-3 transition-all font-bold focus:ring-opacity-50"
                                    :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'"
                                    required>
                                    <option value="">-- Pilih --</option>
                                    @foreach($availableEmissions as $te)
                                        <option value="{{ $te }}" {{ old('tahun_emisi') == $te ? 'selected' : '' }}>{{ $te }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Jumlah
                                    Bilyet</label>
                                <input type="text" inputmode="numeric" x-model="bilyetFormatted"
                                    @input="onBilyetInput($event)" placeholder="0"
                                    class="block w-full border-gray-200 rounded-lg shadow-sm text-sm py-2.5 px-3 transition-all focus:ring-opacity-50 font-bold text-right"
                                    :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'"
                                    required>
                                <div class="text-[10px] text-gray-400 mt-1 text-right font-mono" x-show="bilyetRaw > 0"
                                    x-text="dusFromBilyet.toLocaleString('id-ID') + ' dus'"></div>
                            </div>
                        </div>

                        {{-- Baris 3: Range Nomor Dus --}}
                        <div class="bg-indigo-50/70 border border-indigo-100 rounded-xl p-4 mb-5">
                            <div class="flex items-center justify-between mb-3 gap-3 flex-wrap">
                                <label
                                    class="block text-[10px] font-bold text-indigo-400 uppercase tracking-widest">Range
                                    Nomor Dus</label>
                                <label class="flex items-center gap-2 cursor-pointer select-none">
                                    <span class="text-[10px] font-semibold"
                                        :class="autoDus ? 'text-indigo-600' : 'text-gray-400'">Otomatis dari penyerahan
                                        terakhir</span>
                                    <input type="checkbox" x-model="autoDus" class="sr-only">
                                    <span class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors"
                                        :class="autoDus ? 'bg-indigo-500' : 'bg-gray-300'">
                                        <span
                                            class="inline-block h-4 w-4 rounded-full bg-white shadow transition-all duration-200"
                                            :class="autoDus ? 'ml-4' : 'ml-0.5'"></span>
                                    </span>
                                </label>
                            </div>
                            <div class="grid grid-cols-3 gap-4 items-center">
                                <div>
                                    <label class="block text-[10px] text-gray-400 mb-1">Nomor Dus Awal</label>
                                    <input type="number" name="nomor_dus_awal" x-model.number="noAwal"
                                        value="{{ old('nomor_dus_awal') }}" min="1" placeholder="1"
                                        :readonly="autoDus"
                                        class="block w-full border-indigo-200 bg-white rounded-lg shadow-sm text-sm py-2.5 px-3 font-bold transition-all focus:ring-opacity-50"
                                        :class="[currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500', autoDus ? 'bg-indigo-50/60 text-indigo-400' : '']"
                                        required>
                                </div>
                                <div>
                                    <label class="block text-[10px] text-gray-400 mb-1">Nomor Dus Akhir</label>
                                    <input type="number" name="nomor_dus_akhir" x-model.number="noAkhir"
                                        value="{{ old('nomor_dus_akhir') }}" min="1" placeholder="100"
                                        :readonly="autoDus"
                                        class="block w-full border-indigo-200 bg-white rounded-lg shadow-sm text-sm py-2.5 px-3 font-bold transition-all focus:ring-opacity-50"
                                        :class="[currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500', autoDus ? 'bg-indigo-50/60 text-indigo-400' : '']"
                                        required>
                                </div>
                                <div class="text-center">
                                    <div class="text-[10px] text-indigo-400 uppercase font-bold mb-1">Jumlah Dus</div>
                                    <div class="font-black text-indigo-800 text-2xl"
                                        x-text="jumlahDus.toLocaleString('id-ID')">0</div>
                                    <div class="text-[10px] text-indigo-400" x-show="isChecking">
                                        <span class="animate-pulse">Memeriksa duplikasi...</span>
                                    </div>
                                    <div class="text-[10px] text-indigo-400" x-show="fetchingLast && autoDus">
                                        <span class="animate-pulse">Mengambil dus terakhir...</span>
                                    </div>
                                </div>
                            </div>
                            <p class="text-[10px] text-indigo-400/80 mt-2" x-show="autoDus">
                                Range diisi otomatis dari penyerahan terakhir (pecahan, TA, TE) + jumlah dus = ceil(bilyet / 20.000). Nonaktifkan toggle untuk input manual.
                            </p>
                        </div>

                        {{-- Tombol Simpan --}}
                        <div class="flex gap-3">
                            <button type="submit" :disabled="!!dupWarning"
                                class="flex-1 flex justify-center items-center px-6 py-3 border border-transparent rounded-lg font-bold text-sm text-white uppercase tracking-widest shadow-md active:scale-95 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:active:scale-100"
                                :class="currentTheme ? currentTheme.btn : 'bg-indigo-600 hover:bg-indigo-700'">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span
                                    x-text="dupWarning ? 'Range Duplikat — Periksa Kembali' : 'Simpan Penyerahan'"></span>
                            </button>
                            <a href="{{ route('penyerahan-bi.index') }}"
                                class="px-6 py-3 bg-gray-100 border border-gray-200 rounded-lg font-bold text-sm text-gray-600 uppercase tracking-widest shadow-sm hover:bg-gray-200 active:scale-95 transition-all">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>