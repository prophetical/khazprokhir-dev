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

                    <form action="{{ route('pengemasan.store') }}" method="POST" id="pengemasanForm" x-data="pengemasanHandler()" x-init="init()">
                        @csrf
                        
                        <!-- Hidden inputs for active selections -->
                        <template x-if="!isManualSisa">
                            <template x-for="chunk in selectedChunks" :key="chunk">
                                <input type="hidden" name="selected_chunks[]" :value="chunk">
                            </template>
                        </template>
                        <template x-if="isManualSisa">
                            <template x-for="pack in selectedPacks" :key="pack">
                                <input type="hidden" name="selected_packs[]" :value="pack">
                            </template>
                        </template>
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
                                            <!-- Toggle Switch Kemas Sisa Pack -->
                                            <div x-show="!(jumlahPack % 4 === 0 && totalBilyetPacks === (jumlahPack * 45000) && jumlahPack > 0) || isManualSisa" 
                                                 class="flex items-center justify-between bg-white border border-gray-200 p-2 rounded-lg shadow-sm">
                                                <div class="flex items-center">
                                                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                    <span class="text-[10px] font-black uppercase tracking-tight" :class="isManualSisa ? 'text-red-600' : 'text-gray-700'">Kemas Sisa Pack (Input Manual)</span>
                                                </div>
                                                <label class="relative inline-flex items-center" :class="!isManualSisa && jumlahPack % 4 === 0 && totalBilyetPacks === (jumlahPack * 45000) && jumlahPack > 0 ? 'cursor-not-allowed opacity-50' : 'cursor-pointer'">
                                                    <input type="checkbox" name="is_manual_sisa" value="1" x-model="isManualSisa" 
                                                           :disabled="!isManualSisa && jumlahPack % 4 === 0 && totalBilyetPacks === (jumlahPack * 45000) && jumlahPack > 0"
                                                           class="sr-only peer">
                                                    <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-red-500 shadow-inner"></div>
                                                </label>
                                            </div>

                                            <div class="flex items-center justify-between bg-white border border-gray-200 p-2 rounded-lg shadow-sm">
                                                <div class="flex items-center">
                                                    <svg class="w-5 h-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                    <span class="text-xs font-bold text-gray-700">Lanjutkan Nomor Dus Manual</span>
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

                                    <h3 class="text-xs font-bold text-gray-800 mb-3 border-b border-gray-200 pb-2 uppercase" x-text="isManualSisa ? 'Pilih Pack Secara Satuan' : 'Pilih Kelompok Pack (Kelipatan 4)'">PILIH KELOMPOK PACK YANG AKAN DIKEMAS</h3>
                                    
                                    <div class="overflow-y-auto pr-1" style="max-height: 280px;">
                                        <!-- Normal Mode: Chunks of 4 -->
                                        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3" x-show="!isManualSisa">
                                            @foreach($chunks as $chunk)
                                                <label class="flex items-center justify-center py-2.5 px-3 text-center border rounded-lg cursor-pointer transition-all duration-200 shadow-sm"
                                                    :class="selectedChunks.includes('{{ $chunk['awal'] }}-{{ $chunk['akhir'] }}') ? 'bg-indigo-50 border-indigo-500 shadow-md ring-2 ring-indigo-200' : 'bg-white border-gray-300 hover:bg-gray-50'">
                                                    <input type="checkbox" value="{{ $chunk['awal'] }}-{{ $chunk['akhir'] }}"
                                                        x-model="selectedChunks"
                                                        class="sr-only">
                                                    <span class="text-xs font-extrabold whitespace-nowrap"
                                                        :class="selectedChunks.includes('{{ $chunk['awal'] }}-{{ $chunk['akhir'] }}') ? 'text-indigo-900' : 'text-gray-700'">
                                                        {{ $chunk['label'] }}
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>

                                        <!-- Sisa Pack Mode: Individual Packs -->
                                        <div class="grid grid-cols-4 md:grid-cols-6 xl:grid-cols-8 gap-2" x-show="isManualSisa">
                                            @foreach($packsData as $pack)
                                                <label class="flex items-center justify-center py-2 px-1 text-center border rounded-lg cursor-pointer transition-all duration-200 shadow-sm overflow-hidden"
                                                    :class="selectedPacks.includes('{{ $pack->pack_number }}') ? 'bg-red-50 border-red-500 shadow-md ring-2 ring-red-200' : 'bg-white border-gray-200 hover:bg-gray-50'">
                                                    <input type="checkbox" 
                                                           x-model="selectedPacks" 
                                                           value="{{ $pack->pack_number }}"
                                                           class="sr-only">
                                                    <div class="flex flex-col">
                                                        <span class="text-[10px] font-black" :class="selectedPacks.includes('{{ $pack->pack_number }}') ? 'text-red-900' : 'text-gray-800'">#{{ $pack->pack_number }}</span>
                                                        <span class="text-[8px] font-bold opacity-70">{{ number_format($pack->jumlah, 0, ',', '.') }}</span>
                                                    </div>
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
                                            :disabled="!isValid"
                                            class="bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-bold text-sm py-2 px-6 rounded-lg transition duration-300 shadow-md hover:shadow-indigo-200 w-full sm:w-auto text-center transform hover:-translate-y-0.5 active:scale-95">
                                        PROSES PENGEMASAN
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- MANUAL DETAIL SECTION (Visible if isManualSisa is TRUE) -->
                        <div x-show="isManualSisa" x-transition.opacity class="mt-8 bg-white border-2 border-red-500 rounded-2xl p-6 shadow-2xl relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-red-50 rounded-bl-full -mr-16 -mt-16 opacity-50"></div>
                            <div class="flex justify-between items-center mb-6 relative z-10">
                                <div>
                                    <h4 class="text-xl font-black text-red-600 uppercase tracking-tighter flex items-center">
                                        <div class="bg-red-600 text-white p-2 rounded-lg mr-3 shadow-lg shadow-red-200">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                        </div>
                                        Rincian Dus Manual (Sisa Pack)
                                    </h4>
                                    <p class="text-xs text-red-400 font-bold mt-1">Masukkan data distribusi bilyet ke dalam setiap nomor dus secara spesifik.</p>
                                </div>
                                <button type="button" @click="addManualBox" class="bg-red-600 text-white px-6 py-2.5 rounded-xl text-sm font-black hover:bg-red-700 transition-all uppercase shadow-lg shadow-red-200 active:scale-95 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    Tambah Baris Dus
                                </button>
                            </div>

                            <div class="overflow-x-auto rounded-xl border border-red-100 shadow-sm relative z-10">
                                <table class="min-w-full divide-y divide-red-100">
                                    <thead class="bg-red-50/50">
                                        <tr>
                                            <th class="px-4 py-3 text-xs font-black text-red-500 uppercase text-center w-24">No Dus</th>
                                            <th class="px-4 py-3 text-xs font-black text-red-500 uppercase text-center">Rentang Pack</th>
                                            <th class="px-4 py-3 text-xs font-black text-red-500 uppercase text-center">Rentang Seri</th>
                                            <th class="px-4 py-3 text-xs font-black text-red-500 uppercase text-center">Jumlah Bilyet (Lbr)</th>
                                            <th class="px-4 py-3 w-14"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-red-50 bg-white">
                                        <template x-for="(box, index) in manualBoxes" :key="index">
                                            <tr class="hover:bg-red-50/20 transition-colors">
                                                <td class="px-4 py-3">
                                                    <input type="number" :name="'manual_details['+index+'][no_dus]'" x-model.number="box.no_dus" required class="w-full border-red-200 rounded-xl text-center text-sm py-2 px-2 font-black text-red-700 focus:ring-red-500 focus:border-red-500">
                                                </td>
                                                <td class="px-4 py-3">
                                                    <div class="flex gap-2 items-center">
                                                        <input type="number" :name="'manual_details['+index+'][pack_awal]'" x-model.number="box.pack_awal" required class="w-full border-gray-200 rounded-xl text-center text-sm py-2 px-2 font-bold focus:ring-red-500 focus:border-red-500" placeholder="Awal">
                                                        <span class="text-gray-400 font-black">-</span>
                                                        <input type="number" :name="'manual_details['+index+'][pack_akhir]'" x-model.number="box.pack_akhir" required class="w-full border-gray-200 rounded-xl text-center text-sm py-2 px-2 font-bold focus:ring-red-500 focus:border-red-500" placeholder="Akhir">
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <div class="flex gap-2 items-center">
                                                        <input type="text" :name="'manual_details['+index+'][seri_awal]'" x-model="box.seri_awal" required class="w-full border-gray-200 rounded-xl text-center text-sm py-2 px-2 uppercase font-bold focus:ring-red-500 focus:border-red-500" placeholder="Seri Awal">
                                                        <span class="text-gray-400 font-black">-</span>
                                                        <input type="text" :name="'manual_details['+index+'][seri_akhir]'" x-model="box.seri_akhir" required class="w-full border-gray-200 rounded-xl text-center text-sm py-2 px-2 uppercase font-bold focus:ring-red-500 focus:border-red-500" placeholder="Seri Akhir">
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <input type="number" :name="'manual_details['+index+'][jumlah_bilyet]'" x-model.number="box.jumlah_bilyet" required class="w-full border-indigo-200 rounded-xl text-right text-sm py-2 px-4 font-black text-indigo-700 bg-indigo-50/30 focus:ring-indigo-500 focus:border-indigo-500">
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <button type="button" @click="removeManualBox(index)" class="bg-gray-100 text-gray-400 hover:bg-red-100 hover:text-red-600 p-2 rounded-lg transition-all active:scale-90">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6 relative z-10">
                                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 flex justify-between items-center shadow-sm">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gray-200 rounded-xl flex items-center justify-center mr-4">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                        </div>
                                        <div>
                                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Total Bilyet Pack Dipilih</span>
                                            <span class="text-xl font-black text-gray-800" x-text="formatNumber(totalBilyetPacks)"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-4 rounded-2xl border flex justify-between items-center shadow-md transition-all duration-500" 
                                     :class="totalBilyetPacks === totalBilyetManualBoxes ? 'border-green-500 bg-green-50 shadow-green-100' : 'border-red-500 bg-red-50 shadow-red-100'">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center mr-4 transition-colors duration-500"
                                             :class="totalBilyetPacks === totalBilyetManualBoxes ? 'bg-green-500 text-white' : 'bg-red-500 text-white'">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <div>
                                            <span class="text-[10px] font-black uppercase tracking-widest block" :class="totalBilyetPacks === totalBilyetManualBoxes ? 'text-green-500' : 'text-red-500'" x-text="totalBilyetPacks === totalBilyetManualBoxes ? 'Distribusi Dus (PAS)' : 'Distribusi Dus (SELISIH!)'"></span>
                                            <span class="text-xl font-black" :class="totalBilyetPacks === totalBilyetManualBoxes ? 'text-green-700' : 'text-red-700'" x-text="formatNumber(totalBilyetManualBoxes)"></span>
                                        </div>
                                    </div>
                                    <template x-if="totalBilyetPacks !== totalBilyetManualBoxes">
                                        <div class="text-right">
                                            <span class="text-[9px] font-black text-red-400 uppercase block">Selisih</span>
                                            <span class="text-sm font-black text-red-600" x-text="formatNumber(totalBilyetPacks - totalBilyetManualBoxes)"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            
                            <template x-if="totalBilyetPacks !== totalBilyetManualBoxes">
                                <div class="mt-4 bg-red-600 text-white px-4 py-2 rounded-xl text-center text-xs font-black animate-pulse shadow-lg shadow-red-200">
                                    PERINGATAN: Total bilyet di rincian dus harus TEPAT SAMA dengan total bilyet pack dipilih!
                                </div>
                            </template>

                            <template x-if="isManualSisa && jumlahPack % 4 === 0 && totalBilyetPacks === (jumlahPack * 45000) && jumlahPack > 0">
                                <div class="mt-4 bg-orange-500 text-white px-4 py-2 rounded-xl text-center text-xs font-black shadow-lg shadow-orange-200">
                                    INFO: Jumlah pack adalah kelipatan 4 (@{{ jumlahPack }} pack) dan bilyet penuh. Silakan matikan mode "Kemas Sisa Pack" untuk menggunakan pengemasan standar otomatis.
                                </div>
                            </template>
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
                isManualSisa: {{ (old('is_manual_sisa') || (isset($auto_fill['is_manual_sisa']) && $auto_fill['is_manual_sisa'] == 1)) ? 'true' : 'false' }},
                dusAwal: '{{ old("dus_awal", "") }}',
                dusAkhir: '{{ old("dus_akhir", "") }}',
                selectedChunks: {!! json_encode($defaultChunks ?? []) !!},
                selectedPacks: {!! (isset($auto_fill['pack_awal']) && isset($auto_fill['pack_akhir']) && isset($auto_fill['is_manual_sisa'])) ? json_encode(range((int)$auto_fill['pack_awal'], (int)$auto_fill['pack_akhir'])) : '[]' !!}.map(String),
                packQuantities: {
                    @foreach($packsData as $pack)
                        {{ $pack->pack_number }}: {{ $pack->jumlah }},
                    @endforeach
                },
                manualBoxes: [],
                lastNumber: {{ $last_number ?? 0 }},

                init() {
                    if (this.isManualSisa && this.manualBoxes.length === 0) {
                        this.addManualBox();
                    }
                },

                get jumlahPack() {
                    if (this.isManualSisa) {
                        return this.selectedPacks.length;
                    }
                    return this.selectedChunks.length * 4;
                },

                get totalBilyetPacks() {
                    if (this.isManualSisa) {
                        return this.selectedPacks.reduce((total, num) => {
                            return total + (this.packQuantities[num] || 0);
                        }, 0);
                    }
                    return this.jumlahPack * 45000;
                },

                get totalBilyetManualBoxes() {
                    return this.manualBoxes.reduce((total, box) => total + (parseInt(box.jumlah_bilyet) || 0), 0);
                },

                get jumlahDus() {
                    if (this.isManualSisa) {
                        return this.manualBoxes.length;
                    }
                    if (this.jumlahPack > 0 && this.jumlahPack % 4 === 0) {
                        return (this.jumlahPack / 4) * 9;
                    }
                    return 0;
                },

                get isValid() {
                    if (this.jumlahPack === 0) return false;
                    if (this.totalBilyetPacks === 0) return false;
                    
                    if (this.isManualSisa) {
                        // Hanya blokir jika kelipatan 4 DAN total bilyet adalah kelipatan penuh 45k
                        if (this.jumlahPack % 4 === 0 && this.totalBilyetPacks === (this.jumlahPack * 45000)) return false;
                        return this.manualBoxes.length > 0 && this.totalBilyetPacks === this.totalBilyetManualBoxes;
                    }
                    return this.jumlahPack % 4 === 0;
                },

                addManualBox() {
                    let nextNo = this.lastNumber + 1;
                    if (this.manualBoxes.length > 0) {
                        nextNo = Math.max(...this.manualBoxes.map(b => b.no_dus)) + 1;
                    }
                    this.manualBoxes.push({
                        no_dus: nextNo,
                        pack_awal: '',
                        pack_akhir: '',
                        seri_awal: this.seri,
                        seri_akhir: this.seri,
                        jumlah_bilyet: 0
                    });
                },

                removeManualBox(index) {
                    this.manualBoxes.splice(index, 1);
                },

                formatNumber(num) {
                    return new Intl.NumberFormat('id-ID').format(num);
                },

                get estimasiRentangDus() {
                    if (this.jumlahDus === 0) return '-';
                    if (this.isManualSisa && this.manualBoxes.length > 0) {
                        const nos = this.manualBoxes.map(b => b.no_dus).filter(n => n > 0);
                        if (nos.length === 0) return '-';
                        return Math.min(...nos) + ' - ' + Math.max(...nos);
                    }
                    const start = this.lastNumber + 1;
                    const end = this.lastNumber + this.jumlahDus;
                    return start + ' - ' + end;
                }
            }
        }
    </script>
</x-app-layout>
