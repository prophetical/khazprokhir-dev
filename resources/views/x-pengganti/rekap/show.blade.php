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

        {{-- Dashboard Recap - All 100 Packs (Paginated via CSS/JS) --}}
        <div class="max-w-4xl mx-auto">
            <div
                class="bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-2xl overflow-hidden">
                <table class="w-full text-center border-collapse text-[11px]">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-slate-700/50 border-b border-gray-100 dark:border-slate-700">
                            <th rowspan="2"
                                class="w-20 py-4 border-r border-gray-100 dark:border-slate-700 text-[10px] font-black text-gray-400 uppercase">
                                No Pack</th>
                            <th colspan="3"
                                class="py-3 border-b border-gray-100 dark:border-slate-700 text-[10px] font-black text-indigo-600 uppercase tracking-[0.2em]">
                                Jumlah X Pengganti</th>
                            <th rowspan="2"
                                class="w-28 py-4 border-l border-gray-100 dark:border-slate-700 text-[10px] font-black text-rose-600 uppercase leading-tight tracking-wider">
                                Jumlah<br>Campuran</th>
                        </tr>
                        <tr class="bg-gray-50 dark:bg-slate-700/50 border-b border-gray-100 dark:border-slate-700">
                            <th class="py-3 text-[9px] font-black text-gray-400 border-r border-gray-100 dark:border-slate-700 uppercase tracking-widest">
                                <div class="flex items-center justify-center gap-2">
                                    Seri 1
                                    <button onclick="copyColumn(1)" class="p-1 hover:bg-indigo-100 rounded-md transition-colors text-indigo-400 group/copy" title="Copy Seluruh Kolom Seri 1">
                                        <svg class="w-3 h-3 group-hover/copy:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                    </button>
                                </div>
                            </th>
                            <th class="py-3 text-[9px] font-black text-gray-400 border-r border-gray-100 dark:border-slate-700 uppercase tracking-widest">
                                <div class="flex items-center justify-center gap-2">
                                    Seri 2
                                    <button onclick="copyColumn(2)" class="p-1 hover:bg-indigo-100 rounded-md transition-colors text-indigo-400 group/copy" title="Copy Seluruh Kolom Seri 2">
                                        <svg class="w-3 h-3 group-hover/copy:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                    </button>
                                </div>
                            </th>
                            <th class="py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">
                                <div class="flex items-center justify-center gap-2">
                                    Campuran
                                    <button onclick="copyColumn(3)" class="p-1 hover:bg-indigo-100 rounded-md transition-colors text-indigo-400 group/copy" title="Copy Seluruh Kolom Campuran">
                                        <svg class="w-3 h-3 group-hover/copy:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                    </button>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                        @for($p = 1; $p <= 100; $p++)
                            @php
                                $d = $grid[$p];
                                $isStartOfGroup = ($p % 4 == 1);
                                $gIdx = floor(($p - 1) / 4);
                                $pageIdx = ceil($p / 20);
                            @endphp
                            <tr data-page="{{ $pageIdx }}"
                                class="rekap-row {{ $pageIdx > 1 ? 'hidden' : '' }} hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors group">
                                <td
                                    class="py-3 border-r border-gray-100 dark:border-slate-700 font-black text-slate-400 text-sm">
                                    {{ $p }}
                                </td>
                                <td
                                    class="py-3 border-r border-gray-100 dark:border-slate-700 font-black {{ $d['s1'] > 0 ? 'text-slate-700 dark:text-white' : 'text-slate-300 dark:text-slate-600' }} text-sm">
                                    {{ $d['s1'] > 0 ? number_format($d['s1'], 0, ',', '.') : '-' }}
                                </td>
                                <td
                                    class="py-3 border-r border-gray-100 dark:border-slate-700 font-black {{ $d['s2'] > 0 ? 'text-slate-700 dark:text-white' : 'text-slate-300 dark:text-slate-600' }} text-sm">
                                    {{ $d['s2'] > 0 ? number_format($d['s2'], 0, ',', '.') : '-' }}
                                </td>
                                <td
                                    class="py-3 border-r border-gray-100 dark:border-slate-700 font-black {{ $d['camp'] > 0 ? 'text-slate-700 dark:text-white' : 'text-slate-300 dark:text-slate-600' }} text-sm">
                                    {{ $d['camp'] > 0 ? number_format($d['camp'], 0, ',', '.') : '-' }}
                                </td>

                                @if($isStartOfGroup)
                                    <td rowspan="4"
                                        class="py-3 border-l border-gray-100 dark:border-slate-700 bg-rose-50/40 dark:bg-rose-900/20 font-black text-rose-600 text-base shadow-[inset_0_0_20px_rgba(225,29,72,0.05)]">
                                        {{ number_format($groupTotals[$gIdx], 0, ',', '.') }}
                                    </td>
                                @endif
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>



    </div>

    </div>

    @push('scripts')
        <script>
            function showPage(page) {
                // 1. Sembunyikan semua baris
                document.querySelectorAll('.rekap-row').forEach(row => {
                    row.classList.add('hidden');
                });

                // 2. Tampilkan baris untuk halaman yang dipilih
                document.querySelectorAll(`.rekap-row[data-page="${page}"]`).forEach(row => {
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
        </script>
    @endpush
</x-app-layout>