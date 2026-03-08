<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Input Pengemasan HCS') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @php
                $pecahan = $auto_fill['pecahan'] ?? '';
                $borderColor = 'border-indigo-600'; // default
                $textColor = 'text-indigo-600';
                $pecahanLabel = 'Belum Dipilih';

                if ($pecahan === 'S') { $borderColor = 'border-lime-500'; $textColor = 'text-lime-500'; $pecahanLabel = 'S'; }
                elseif ($pecahan === 'T') { $borderColor = 'border-gray-500'; $textColor = 'text-gray-500'; $pecahanLabel = 'T'; }
                elseif ($pecahan === 'U') { $borderColor = 'border-amber-600'; $textColor = 'text-amber-600'; $pecahanLabel = 'U'; }
                elseif ($pecahan === 'V') { $borderColor = 'border-purple-500'; $textColor = 'text-purple-500'; $pecahanLabel = 'V'; }
                elseif ($pecahan === 'W') { $borderColor = 'border-green-500'; $textColor = 'text-green-500'; $pecahanLabel = 'W'; }
                elseif ($pecahan === 'X') { $borderColor = 'border-blue-500'; $textColor = 'text-blue-500'; $pecahanLabel = 'X'; }
                elseif ($pecahan === 'Y') { $borderColor = 'border-red-500'; $textColor = 'text-red-500'; $pecahanLabel = 'Y'; }
            @endphp
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl border-t-8 {{ $borderColor }}">
                <div class="p-6 sm:p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center space-x-4">
                            <div class="bg-indigo-100 p-3 rounded-xl">
                                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight flex items-center gap-3">
                                    Input Pengemasan
                                    @if(isset($auto_fill['batch']) || isset($auto_fill['seri']))
                                        <span class="inline-flex items-center px-4 py-1.5 rounded-2xl border-2 text-xl font-black uppercase tracking-tighter {{ $borderColor }} {{ $textColor }} bg-white shadow-sm">
                                            {{ $auto_fill['batch'] ?? '-' }} / {{ $auto_fill['seri'] ?? '-' }}
                                        </span>
                                    @endif
                                </h2>
                                <p class="text-gray-500 text-sm">Form input pengemasan sesuai data penyortiran HCS.</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Pecahan</div>
                            <div class="text-7xl font-black {{ $textColor }}" x-text="pecahan ? (pecahan === 'S' ? 'S' : pecahan === 'T' ? 'T' : pecahan === 'U' ? 'U' : pecahan === 'V' ? 'V' : pecahan === 'W' ? 'W' : pecahan === 'X' ? 'X' : pecahan === 'Y' ? 'Y' : pecahan) : '{{ $pecahanLabel }}'">{{ $pecahanLabel }}</div>
                        </div>
                    </div>

                    <form action="{{ route('pengemasan.store') }}" method="POST" id="pengemasanForm" x-data="pengemasanHandler()">
                        @csrf
                        
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                            
                            <!-- KOLOM KIRI: Identitas Pengemasan (4 kolom) -->
                            <div class="lg:col-span-4 bg-gray-50 p-4 rounded-2xl border border-gray-100 shadow-inner flex flex-col justify-between">
                                <div>
                                    <h3 class="text-xs font-bold text-gray-800 mb-3 border-b border-gray-200 pb-2">Identitas & Tanggal Pengemasan</h3>
                                    <div class="grid grid-cols-2 gap-3 mb-4">
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tanggal</label>
                                            <input type="date" name="tanggal_pengemasan" required class="w-full border-gray-200 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-2 text-xs" value="{{ date('Y-m-d') }}">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Gilir</label>
                                            <select name="gilir" required class="w-full border-gray-200 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-2 text-center text-xs">
                                                <option value="1">Gilir 1</option>
                                                <option value="2">Gilir 2</option>
                                                <option value="3">Gilir 3</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tahun Anggaran</label>
                                            <input type="text" name="tahun_anggaran" x-model="tahun_anggaran" required class="w-full border-gray-200 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-2 text-center text-xs {{ isset($auto_fill['tahun_anggaran']) ? 'bg-gray-100' : '' }}" {{ isset($auto_fill['tahun_anggaran']) ? 'readonly' : '' }}>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tahun Emisi</label>
                                            <input type="number" name="tahun_emisi" x-model="tahun_emisi" required class="w-full border-gray-200 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-2 text-center text-xs {{ isset($auto_fill['tahun_emisi']) ? 'bg-gray-100' : '' }}" {{ isset($auto_fill['tahun_emisi']) ? 'readonly' : '' }}>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Pecahan</label>
                                            @if(isset($auto_fill['pecahan']))
                                                <input type="text" name="pecahan" x-model="pecahan" required readonly class="w-full border-gray-200 rounded-lg shadow-sm bg-gray-100 py-2 px-2 text-center font-bold text-xs">
                                            @else
                                                <select name="pecahan" x-model="pecahan" required class="w-full border-gray-200 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-2 text-center text-xs">
                                                    @foreach(['S', 'T', 'U', 'V', 'W', 'X', 'Y'] as $p)
                                                        <option value="{{ $p }}">{{ $p }}</option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Batch</label>
                                            <input type="text" name="batch" x-model="batch" required class="w-full border-gray-200 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-2 text-center text-xs {{ isset($auto_fill['batch']) ? 'bg-gray-100' : '' }}" {{ isset($auto_fill['batch']) ? 'readonly' : '' }}>
                                        </div>
                                        <div class="col-span-2">
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Seri</label>
                                            <input type="text" name="seri" x-model="seri" required class="w-full border-gray-200 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-2 text-center uppercase text-xs {{ isset($auto_fill['seri']) ? 'bg-gray-100' : '' }}" {{ isset($auto_fill['seri']) ? 'readonly' : '' }} placeholder="XX-XXN">
                                        </div>
                                        
                                        <div class="col-span-2 pt-3 mt-1 relative flex flex-col gap-3">
                                            <!-- Toggle Switch Auto/Manual -->
                                            <div class="flex items-center justify-between bg-white border border-gray-200 p-2 rounded-lg shadow-sm">
                                                <div class="flex items-center">
                                                    <svg class="w-5 h-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                    <span class="text-xs font-bold text-gray-700">Gunakan Input Dus Manual</span>
                                                </div>
                                                <label class="relative inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" name="is_manual" value="1" x-model="isManual" class="sr-only peer">
                                                    <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                                                </label>
                                            </div>

                                            <!-- INPUT MANUAL DUS (Tampil jk isManual = true) -->
                                            <div x-show="isManual" x-transition.opacity class="border border-indigo-100 bg-indigo-50/50 p-3 rounded-lg relative">
                                                <div class="absolute -top-2 left-1/2 transform -translate-x-1/2 bg-white px-2 py-0.5 rounded-full text-[9px] font-black text-indigo-600 tracking-widest uppercase shadow-sm border border-indigo-100">INPUT DUS MANUAL</div>
                                                <div class="grid grid-cols-2 gap-3 pt-2">
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Dus Awal</label>
                                                        <input type="number" name="dus_awal" x-model.number="dusAwal" :required="isManual" min="1" class="w-full border-indigo-200 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-2 text-center text-sm font-black text-indigo-700 bg-white placeholder-indigo-300" placeholder="0">
                                                    </div>
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Dus Akhir</label>
                                                        <input type="number" name="dus_akhir" x-model.number="dusAkhir" :required="isManual" min="1" class="w-full border-indigo-200 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-2 text-center text-sm font-black text-indigo-700 bg-white placeholder-indigo-300" placeholder="0">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-blue-50 p-2 rounded-lg border border-blue-100 text-center">
                                    <div class="text-[10px] font-bold text-blue-500 uppercase tracking-widest mb-1"></div>
                                    <div class="text-xs text-blue-800">Nomor Dus Terakhir : <strong class="text-lg font-black" x-text="lastNumber > 0 ? lastNumber : '0'"></strong></div>
                                </div>
                            </div>

                            <!-- KOLOM KANAN: Pilihan Kelompok Pack (8 kolom) -->
                            <div class="lg:col-span-8 flex flex-col">
                                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 shadow-inner flex-grow flex flex-col">
                                    @php
                                        $startPack = $auto_fill['pack_awal'] ?? 1;
                                        $maxPack = $auto_fill['max_pack_akhir'] ?? $startPack + 3;
                                        $chunks = [];
                                        for ($i = $startPack; $i <= $maxPack; $i += 4) {
                                            if ($i + 3 <= $maxPack) {
                                                $chunks[] = [
                                                    'awal' => $i,
                                                    'akhir' => $i + 3,
                                                    'label' => "Pack $i - " . ($i + 3)
                                                ];
                                            }
                                        }
                                        $isAll = isset($auto_fill['pack_akhir']) && $auto_fill['pack_akhir'] != '';
                                        
                                        $defaultChunks = [];
                                        foreach($chunks as $index => $chunk) {
                                            if ($isAll || $index === 0) {
                                                $defaultChunks[] = "{$chunk['awal']}-{$chunk['akhir']}";
                                            }
                                        }
                                    @endphp

                                    <h3 class="text-xs font-bold text-gray-800 mb-3 border-b border-gray-200 pb-2">PILIH KELOMPOK PACK YANG AKAN DIKEMAS</h3>
                                    
                                    <div class="overflow-y-auto pr-1" style="max-height: 280px;">
                                        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3">
                                            @foreach($chunks as $chunk)
                                                <label class="flex items-center justify-center py-2.5 px-3 text-center border rounded-lg cursor-pointer transition-all duration-200 shadow-sm"
                                                    :class="selectedChunks.includes('{{ $chunk['awal'] }}-{{ $chunk['akhir'] }}') ? 'bg-indigo-50 border-indigo-500 shadow-md ring-2 ring-indigo-200' : 'bg-white border-gray-300 hover:bg-gray-50'">
                                                    <input type="checkbox" name="selected_chunks[]" value="{{ $chunk['awal'] }}-{{ $chunk['akhir'] }}"
                                                        x-model="selectedChunks"
                                                        class="sr-only">
                                                    <span class="text-xs font-extrabold whitespace-nowrap"
                                                        :class="selectedChunks.includes('{{ $chunk['awal'] }}-{{ $chunk['akhir'] }}') ? 'text-indigo-900' : 'text-gray-700'">
                                                        {{ $chunk['label'] }}
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <!-- AUTO CALCULATED FIELDS HORIZONTAL BAR -->
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 mt-4">
                                    <div class="bg-indigo-50 p-2 rounded-lg text-center border border-indigo-100 flex flex-col justify-center">
                                        <div class="text-[9px] font-bold text-indigo-400 uppercase tracking-widest mb-0.5">Total Pack Dipilih</div>
                                        <div class="text-lg font-black leading-none" :class="jumlahPack % 4 === 0 && jumlahPack > 0 ? 'text-indigo-900' : 'text-red-600'" x-text="jumlahPack">0</div>
                                    </div>
                                    <div class="bg-purple-50 p-2 rounded-lg text-center border border-purple-100 flex flex-col justify-center">
                                        <div class="text-[9px] font-bold text-purple-400 uppercase tracking-widest mb-0.5">Estimasi Dus</div>
                                        <div class="text-lg font-black leading-none text-purple-900" x-text="jumlahDus">0</div>
                                    </div>
                                    <!-- Estimasi Kotak Auto / Manual Indicator -->
                                    <template x-if="!isManual">
                                        <div class="bg-green-50 p-2 rounded-lg text-center border border-green-100 flex flex-col justify-center transition-colors duration-300">
                                            <div class="text-[9px] font-bold text-green-600 uppercase tracking-widest mb-0.5 flex items-center justify-center gap-1">
                                                <span>Estimasi Auto</span>
                                            </div>
                                            <div class="text-lg font-black leading-none text-green-700" x-text="estimasiRentangDus">-</div>
                                        </div>
                                    </template>
                                    
                                    <template x-if="isManual">
                                        <div class="bg-green-50 p-2 rounded-lg text-center border border-green-100 flex flex-col justify-center transition-colors duration-300" :class="(dusAkhir - dusAwal + 1) === jumlahDus && jumlahDus > 0 ? 'bg-indigo-50 border-indigo-500' : 'bg-red-50 border-red-500'">
                                            <div class="text-[9px] font-bold text-indigo-600 uppercase tracking-widest mb-0.5 flex items-center justify-center gap-1" :class="(dusAkhir - dusAwal + 1) === jumlahDus && jumlahDus > 0 ? 'text-indigo-600' : 'text-red-500'">
                                                <span x-text="(dusAkhir - dusAwal + 1) === jumlahDus && jumlahDus > 0 ? 'Mode Manual (Aman)' : 'Range Manual Invalid!'"></span>
                                            </div>
                                            <div class="text-lg font-black leading-none" :class="(dusAkhir - dusAwal + 1) === jumlahDus && jumlahDus > 0 ? 'text-indigo-700' : 'text-red-700'" x-text="(dusAwal && dusAkhir) ? (dusAwal + ' - ' + dusAkhir) : '-'">-</div>
                                        </div>
                                    </template>
                                </div>
                                
                                @if ($errors->any())
                                    <div class="bg-red-50 border border-red-200 p-3 mt-4 rounded-lg">
                                        <ul class="list-disc list-inside text-xs text-red-700 font-bold">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="flex items-center justify-end space-x-3 mt-6">
                                    <a href="{{ route('pengemasan.index') }}" class="text-xs font-semibold text-gray-500 hover:text-gray-700 transition duration-200 px-3 py-2">Batal</a>
                                    <button type="submit" 
                                            :disabled="jumlahPack == 0 || jumlahPack % 4 !== 0"
                                            class="bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-bold text-sm py-2 px-6 rounded-lg transition duration-300 shadow-md hover:shadow-indigo-200 w-full sm:w-auto text-center transform hover:-translate-y-0.5 active:scale-95">
                                        PROSES PENGEMASAN
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function pengemasanHandler() {
            return {
                tahun_anggaran: '{{ $auto_fill["tahun_anggaran"] ?? "2026" }}',
                tahun_emisi: '{{ $auto_fill["tahun_emisi"] ?? "2022" }}',
                pecahan: '{{ $auto_fill["pecahan"] ?? "S" }}',
                batch: '{{ $auto_fill["batch"] ?? "" }}',
                seri: '{{ $auto_fill["seri"] ?? "" }}',
                isManual: {{ old('is_manual') ? 'true' : 'false' }},
                dusAwal: '{{ old("dus_awal", "") }}',
                dusAkhir: '{{ old("dus_akhir", "") }}',
                selectedChunks: {!! json_encode($defaultChunks ?? []) !!},
                lastNumber: {{ $last_number ?? 0 }},

                get jumlahPack() {
                    return this.selectedChunks.length * 4;
                },

                get jumlahDus() {
                    if (this.jumlahPack > 0 && this.jumlahPack % 4 === 0) {
                        return (this.jumlahPack / 4) * 9;
                    }
                    return 0;
                },

                get estimasiRentangDus() {
                    if (this.jumlahDus === 0) return '-';
                    const start = this.lastNumber + 1;
                    const end = this.lastNumber + this.jumlahDus;
                    return start + ' - ' + end;
                }
            }
        }
    </script>
</x-app-layout>
