<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <h2 class="font-black text-xl text-white leading-tight tracking-tight">Hasil Rekap Khazprokhir</h2>
            </div>
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">

        {{-- Header Informasi Seri --}}
        <div
            class="mb-4 bg-gradient-to-r from-indigo-600 to-violet-700 rounded-2xl p-4 shadow-xl relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-4 group">
            {{-- Dekorasi --}}
            <div
                class="absolute top-0 right-0 -mt-8 -mr-8 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:scale-125 transition-all duration-700">
            </div>

            <div class="relative grid grid-cols-2 md:grid-cols-5 gap-4 flex-1 w-full">
                <div class="flex flex-col">
                    <span class="text-[8px] font-black text-indigo-200 uppercase tracking-widest mb-0.5">Batch</span>
                    <span class="text-lg font-black text-white tracking-widest">{{ $seri->batch }}</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-[8px] font-black text-indigo-200 uppercase tracking-widest mb-0.5">Nomor
                        Seri</span>
                    <span class="text-lg font-black text-white tracking-widest">{{ $seri->seri }}</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-[8px] font-black text-indigo-200 uppercase tracking-widest mb-0.5">Pecahan</span>
                    @php
                        $pchBadge = ['S' => 'bg-lime-400', 'T' => 'bg-gray-300', 'U' => 'bg-amber-300', 'V' => 'bg-purple-400', 'W' => 'bg-green-400', 'X' => 'bg-blue-400', 'Y' => 'bg-red-400'];
                    @endphp
                    <div class="flex items-center gap-2">
                        <span
                            class="inline-flex items-center justify-center w-7 h-7 rounded-lg {{ $pchBadge[$seri->pecahan] ?? 'bg-gray-400' }} text-white text-[10px] font-black shadow-lg border border-white/20">{{ $seri->pecahan }}</span>
                    </div>
                </div>
                <div class="flex flex-col">
                    <span class="text-[8px] font-black text-indigo-200 uppercase tracking-widest mb-0.5">TA</span>
                    <span class="text-lg font-black text-white">{{ $seri->tahun_anggaran }}</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-[8px] font-black text-indigo-200 uppercase tracking-widest mb-0.5">TE</span>
                    <span class="text-lg font-black text-white">{{ $seri->tahun_emisi }}</span>
                </div>
            </div>

            <div class="relative z-10 shrink-0">
                <a href="{{ route('x-pengganti.rekap.print', ['seri_id' => $seri->id]) }}" target="_blank"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white text-indigo-600 hover:bg-black hover:text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow-lg active:scale-95 group/btn">
                    <svg class="w-3.5 h-3.5 group-hover/btn:animate-bounce" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak
                </a>
            </div>
        </div>


        {{-- Navigasi Paginasi Dashboard (Client-Side) --}}
        <div class="mb-6 flex flex-wrap justify-center gap-2">
            @for($p = 1; $p <= 5; $p++)
                <button type="button" onclick="showPage({{ $p }})" id="btn-page-{{ $p }}"
                    class="page-nav-btn px-5 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all shadow-sm border {{ $p == 1 ? 'bg-indigo-600 text-white border-indigo-600 shadow-indigo-100' : 'bg-white text-gray-400 border-gray-100 hover:border-indigo-200 hover:text-indigo-600' }}">
                    Halaman {{ $p }}
                    <span class="block text-[8px] opacity-70">Pack {{ (($p - 1) * 20) + 1 }} - {{ $p * 20 }}</span>
                </button>
            @endfor
        </div>



        {{-- Unified Integrated Table --}}
        <div class="px-2 pb-20">
            <div class="flex items-center justify-between mb-8 px-4">
                <div class="flex flex-col gap-1">
                    <h3 class="text-sm font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.3em]">Rekap X Pengganti</h3>
                    <p class="text-[10px] text-slate-400 font-medium">DATA JUMLAH ASLI X PENGGANTI DAN PENYESUAIAN UNTUK SAP</p>
                </div>
                <button onclick="copySapColumn()"
                    class="group inline-flex items-center gap-2.5 px-6 py-3 bg-gradient-to-r from-indigo-500 via-indigo-600 to-violet-700 hover:from-indigo-600 hover:to-violet-800 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl transition-all shadow-xl shadow-indigo-100 dark:shadow-none active:scale-95 border border-white/20">
                    <svg class="w-4 h-4 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                    </svg>
                    Copy Seluruh Kolom SAP
                </button>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-gray-100 dark:border-slate-700 shadow-2xl overflow-hidden border-b-8 border-b-indigo-500/10">
                <div class="overflow-x-auto">
                    <table class="w-full text-center border-collapse text-[10px]">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-700/50 border-b border-gray-100 dark:border-slate-700">
                                <th class="py-5 border-r border-gray-100 dark:border-slate-700 text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-100/30">Identitas Pack</th>
                                <th colspan="2" class="py-5 border-r border-gray-100 dark:border-slate-700 text-[10px] font-black text-emerald-600 uppercase tracking-widest bg-emerald-50/30">Format Copy SAP</th>
                                <th colspan="4" class="py-5 text-[10px] font-black text-indigo-600 uppercase tracking-widest bg-indigo-50/30">Detail Rekapitulasi</th>
                            </tr>
                            <tr class="bg-white dark:bg-slate-800 border-b border-gray-100 dark:border-slate-700 text-[9px] font-black text-gray-400 uppercase tracking-wider">
                                <th class="w-20 py-3 border-r border-gray-100 dark:border-slate-700">No Pack</th>
                                <th class="w-20 py-3 border-r border-gray-100 dark:border-slate-700">Nama</th>
                                <th class="w-32 py-3 border-r border-gray-100 dark:border-slate-700 text-emerald-600">Copy SAP</th>
                                <th class="w-24 py-3 border-r border-gray-100 dark:border-slate-700">Seri 1</th>
                                <th class="w-24 py-3 border-r border-gray-100 dark:border-slate-700">Seri 2</th>
                                <th class="w-24 py-3 border-r border-gray-100 dark:border-slate-700">Campuran</th>
                                <th class="w-32 py-3 text-indigo-600">Total Group</th>
                            </tr>
                        </thead>
                        <tbody class="">
                            @for($g = 0; $g < 25; $g++)
                                @php
                                    $C = $groupTotals[$g];
                                    $packStart = ($g * 4) + 1;
                                    $packEnd = ($g * 4) + 4;
                                    $pageIdx = ceil(($g + 1) / 5);
                                    
                                    // Row Mapping (10 Rows)
                                    $rows = [
                                        ['nama' => 'CAMPURAN 1', 'val' => min($C, 16000), 'p' => null],
                                        ['nama' => 'CAMPURAN 2', 'val' => min(max($C - 16000, 0), 4000), 'p' => null],
                                    ];
                                    for($i = 0; $i < 4; $i++) {
                                        $pNum = ($g * 4) + $i + 1;
                                        // Limit individual series to max 20,000
                                        $rows[] = ['nama' => "PACK $pNum SERI 1", 'val' => min($grid[$pNum]['s1'], 20000), 'p' => $pNum, 'seri' => 1];
                                        $rows[] = ['nama' => "PACK $pNum SERI 2", 'val' => min($grid[$pNum]['s2'], 20000), 'p' => $pNum, 'seri' => 2];
                                    }
                                @endphp

                                @foreach($rows as $rIdx => $row)
                                    @php 
                                        $isGroupEnd = ($rIdx === 9);
                                        $isPack4End = ($rIdx === 8); // Pack 4 starts at 8, spans to 9
                                        $separatorClass = $isGroupEnd ? 'group-separator' : '';
                                        
                                        $commonCellBase = 'border-r border-gray-100 dark:border-slate-700';
                                        if (!$isGroupEnd) {
                                            $commonCellBase .= ' border-b border-gray-100 dark:border-slate-700';
                                        }
                                    @endphp
                                    <tr data-page="{{ $pageIdx }}" 
                                        class="unified-row {{ $pageIdx > 1 ? 'hidden' : '' }} hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group/row">
                                        
                                        {{-- Column: No Pack --}}
                                        @if($row['p'] && $row['seri'] === 1)
                                            {{-- Individual pack rowspans 2. Pack 4 is the last, so its end is the group end --}}
                                            <td rowspan="2" class="{{ $commonCellBase }} font-black text-slate-500 text-xs bg-slate-50/10 {{ $row['p'] % 4 === 0 ? 'group-separator' : '' }}">
                                                {{ $row['p'] }}
                                            </td>
                                        @elseif(!$row['p'] && $rIdx === 0)
                                            <td rowspan="2" class="{{ $commonCellBase }} font-black text-slate-400 text-[9px] bg-slate-50/10 uppercase tracking-tighter">
                                                {{ $packStart }}-{{ $packEnd }}
                                            </td>
                                        @endif

                                        <td class="py-2.5 px-3 {{ $commonCellBase }} text-left font-bold text-slate-500 dark:text-slate-400 uppercase tracking-normal text-[9px] {{ $separatorClass }}">
                                            {{ $row['nama'] }}
                                        </td>

                                        {{-- Column: SAP Nilai (COPY TARGET) --}}
                                        <td class="sap-value-cell py-2.5 {{ $commonCellBase }} font-black text-xs text-indigo-600 dark:text-indigo-400 bg-emerald-50/5 dark:bg-emerald-400/5 group-hover/row:bg-emerald-50 {{ $separatorClass }}">
                                            {{ $row['val'] }}
                                        </td>

                                        {{-- Columns: Main Rekap (Seri 1, Seri 2, Campuran) --}}
                                        @if($row['p'] && $row['seri'] === 1)
                                            @php $d = $grid[$row['p']]; @endphp
                                            <td rowspan="2" class="{{ $commonCellBase }} font-black text-slate-700 dark:text-white text-[11px] {{ $row['p'] % 4 === 0 ? 'group-separator' : '' }}">
                                                {{ $d['s1'] > 0 ? number_format($d['s1'], 0, ',', '.') : '-' }}
                                            </td>
                                            <td rowspan="2" class="{{ $commonCellBase }} font-black text-slate-700 dark:text-white text-[11px] {{ $row['p'] % 4 === 0 ? 'group-separator' : '' }}">
                                                {{ $d['s2'] > 0 ? number_format($d['s2'], 0, ',', '.') : '-' }}
                                            </td>
                                            <td rowspan="2" class="{{ $commonCellBase }} font-black text-slate-700 dark:text-white text-[11px] {{ $row['p'] % 4 === 0 ? 'group-separator' : '' }}">
                                                {{ $d['camp'] > 0 ? number_format($d['camp'], 0, ',', '.') : '-' }}
                                            </td>
                                        @elseif(!$row['p'] && $rIdx === 0)
                                            <td rowspan="2" colspan="3" class="{{ $commonCellBase }} bg-slate-50/10 font-black text-slate-300 uppercase tracking-widest text-[9px]">
                                                DETAIL PACK {{ $packStart }}-{{ $packEnd }}
                                            </td>
                                        @endif

                                        {{-- Column: Total Group --}}
                                        @if($rIdx === 0)
                                            <td rowspan="10" class="font-black text-sm text-rose-600 bg-rose-50/5 dark:bg-rose-900/10 shadow-[inset_0_0_15px_rgba(225,29,72,0.01)] selection:bg-rose-200 group-separator">
                                                {{ number_format($groupTotals[$g], 0, ',', '.') }}
                                            </td>
                                        @endif

                                    </tr>
                                @endforeach
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    @push('css')
        <style>
            .sap-value-cell {
                user-select: all; /* Memudahkan seleksi teks manual jika diperlukan */
                cursor: pointer;
            }
            .sap-value-cell::selection {
                background-color: #818cf8;
                color: white;
            }
            .group-separator {
                box-shadow: inset 0 -2px 0 0 #6366f1 !important; /* Indigo-500 */
            }
            .dark .group-separator {
                box-shadow: inset 0 -2px 0 0 #818cf8 !important; /* Indigo-400 */
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            function showPage(page) {
                // 1. Sembunyikan semua baris unified
                document.querySelectorAll('.unified-row').forEach(row => {
                    row.classList.add('hidden');
                });

                // 2. Tampilkan baris untuk halaman yang dipilih
                document.querySelectorAll(`.unified-row[data-page="${page}"]`).forEach(row => {
                    row.classList.remove('hidden');
                });

                // 3. Update status tombol navigasi
                document.querySelectorAll('.page-nav-btn').forEach(btn => {
                    btn.classList.remove('bg-indigo-600', 'text-white', 'border-indigo-600', 'shadow-indigo-100');
                    btn.classList.add('bg-white', 'text-gray-400', 'border-gray-100');
                });

                const activeBtn = document.getElementById(`btn-page-${page}`);
                if (activeBtn) {
                    activeBtn.classList.remove('bg-white', 'text-gray-400', 'border-gray-100');
                    activeBtn.classList.add('bg-indigo-600', 'text-white', 'border-indigo-600', 'shadow-indigo-100');
                }

                // 4. Scroll ke atas tabel (opsional agar user tahu konten berubah)
                // window.scrollTo({ top: 300, behavior: 'smooth' });
            }

            function copyColumn(colIndex) {
                // colIndex: 1 = Seri 1, 2 = Seri 2, 3 = Campuran
                const rows = document.querySelectorAll('.rekap-row');
                let clipboardText = "";

                rows.forEach(row => {
                    // Ambil cell sesuai index (0=No Pack, 1=Seri 1, 2=Seri 2, 3=Campuran)
                    const cell = row.cells[colIndex];
                    if (cell) {
                        let val = cell.innerText.trim();
                        // Jika value adalah '-', ubah jadi 0 agar rapi di Excel
                        if (val === '-') val = '0';
                        // Hapus format titik ribuan agar tetap angka murni
                        val = val.replace(/\./g, '');
                        
                        clipboardText += val + "\n";
                    }
                });

                // Copy ke clipboard
                navigator.clipboard.writeText(clipboardText).then(() => {
                    const titles = ["", "Seri 1", "Seri 2", "Campuran"];
                    alert(`Data 100 baris kolom ${titles[colIndex]} berhasil disalin ke clipboard!\nSiap dipaste ke Excel.`);
                }).catch(err => {
                    console.error('Gagal copy: ', err);
                });
            }

            function copySapColumn() {
                const cells = document.querySelectorAll('.sap-value-cell');
                let clipboardText = "";

                cells.forEach(cell => {
                    let val = cell.innerText.trim();
                    clipboardText += val + "\n";
                });

                // Copy ke clipboard
                navigator.clipboard.writeText(clipboardText).then(() => {
                    Swal.fire({
                        title: 'Berhasil di-copy!',
                        text: '250 baris nilai SAP telah disalin ke clipboard.',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false,
                        background: document.documentElement.classList.contains('dark-mode') ? '#1e293b' : '#ffffff',
                        color: document.documentElement.classList.contains('dark-mode') ? '#f8fafc' : '#1e293b',
                    });
                }).catch(err => {
                    console.error('Gagal copy: ', err);
                });
            }

            /**
             * Utility Function: Transform 100 Pack Data to 250 SAP Rows
             * @param {Array} gridData - Array of 100 objects with {s1, s2, camp}
             * @param {Array} groupTotals - Array of 25 numbers (sum of camp per 4 packs)
             * @returns {Array} - Array of 250 objects {name, value}
             */
            function transformToSapFormat(gridData, groupTotals) {
                const sapRows = [];
                for (let g = 0; g < 25; g++) {
                    const C = groupTotals[g];
                    
                    // Logic Campuran
                    sapRows.push({ name: 'CAMPURAN 1', value: Math.min(C, 16000) });
                    sapRows.push({ name: 'CAMPURAN 2', value: Math.min(Math.max(C - 16000, 0), 4000) });

                    // Logic Per Pack (4 pack per grup)
                    for (let i = 1; i <= 4; i++) {
                        const pNum = (g * 4) + i;
                        const pack = gridData[pNum];
                        sapRows.push({ name: `PACK ${pNum} SERI 1`, value: pack.s1 });
                        sapRows.push({ name: `PACK ${pNum} SERI 2`, value: pack.s2 });
                    }
                }
                return sapRows;
            }
        </script>
    @endpush
</x-app-layout>