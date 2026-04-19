<x-app-layout :full-screen="true">
    {{-- ═══ STICKY ACTION BAR in HEADER ═══ --}}
    <x-slot name="header">
        <div class="flex items-center justify-between w-full gap-4 relative z-[60]">

            {{-- Kiri: Info Seri --}}
            <div class="flex items-center gap-3 min-w-0">
                <a href="{{ route('x-pengganti.cutpack.index') }}"
                    class="p-2 rounded-xl text-white/60 hover:text-white hover:bg-white/10 transition-all shrink-0"
                    title="Kembali ke daftar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                @php
                    $pchBadgeColor = ['S' => 'bg-lime-400', 'T' => 'bg-gray-300', 'U' => 'bg-amber-300', 'V' => 'bg-purple-400', 'W' => 'bg-green-400', 'X' => 'bg-blue-400', 'Y' => 'bg-red-400'];
                @endphp
                <span
                    class="inline-flex items-center justify-center w-8 h-8 rounded-xl {{ $pchBadgeColor[$seri->pecahan] ?? 'bg-gray-400' }} font-black text-white text-sm shadow">{{ $seri->pecahan }}</span>

                <div class="min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-white font-black text-base tracking-wider">{{ $seri->seri }}</span>
                        <span
                            class="px-2 py-0.5 bg-white/10 rounded-lg text-white/70 text-[10px] font-bold uppercase tracking-tight">Batch
                            {{ $seri->batch }}</span>
                        <span
                            class="px-2 py-0.5 bg-white/10 rounded-lg text-white/70 text-[10px] font-bold uppercase tracking-tight">TA
                            {{ $seri->tahun_anggaran }}</span>
                        <span
                            class="px-2 py-0.5 bg-white/10 rounded-lg text-white/70 text-[10px] font-bold uppercase tracking-tight">TE
                            {{ $seri->tahun_emisi }}</span>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[9px] font-bold uppercase tracking-widest mt-0.5"
                            style="color:rgba(255,255,255,0.4);">SEKSI SAIMASINAL</p>
                    </div>
                </div>
            </div>

            {{-- Tengah: Navigasi Halaman --}}
            <div class="flex items-center gap-1.5 shrink-0">
                <button id="btn-page-1" onclick="showPage(1)"
                    class="page-nav-btn active px-4 py-2 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all">
                    <span class="block text-[8px] opacity-70 mb-0.5 leading-none tracking-normal">Halaman 1</span>
                    Pack 1 – 50
                </button>
                <div class="w-px h-8 bg-white/20 mx-1"></div>
                <button id="btn-page-2" onclick="showPage(2)"
                    class="page-nav-btn px-4 py-2 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all">
                    <span class="block text-[8px] opacity-70 mb-0.5 leading-none tracking-normal">Halaman 2</span>
                    Pack 51 – 100
                </button>
            </div>

            {{-- Info Totals di Header --}}
            <div class="hidden md:flex items-center gap-3 px-4 py-1.5 rounded-xl border shrink-0"
                style="background:rgba(255,255,255,0.1); border-color:rgba(255,255,255,0.1);">
                <div class="text-center">
                    <div class="text-[8px] font-black uppercase tracking-widest" style="color:#fbbf24;">SERI 1</div>
                    <div id="grand-total-seri1-header" class="text-sm font-black text-white">0</div>
                </div>
                <div class="w-px h-6" style="background:rgba(255,255,255,0.2);"></div>
                <div class="text-center">
                    <div class="text-[8px] font-black uppercase tracking-widest" style="color:#fbbf24;">SERI 2</div>
                    <div id="grand-total-seri2-header" class="text-sm font-black text-white">0</div>
                </div>
                <div class="w-px h-6" style="background:rgba(255,255,255,0.2);"></div>
                <div class="text-center">
                    <div class="text-[8px] font-black uppercase tracking-widest" style="color:#818cf8;">CAMPURAN</div>
                    <div id="grand-total-campuran-header" class="text-sm font-black text-white">0</div>
                </div>
            </div>

            {{-- Kanan: Aksi --}}
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('x-pengganti.cutpack.pdf', ['seri_id' => $seri->id]) }}" target="_blank"
                    class="inline-flex items-center gap-1.5 px-3 py-2 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    PDF
                </a>
                <button type="submit" form="cutpack-form"
                    class="inline-flex items-center gap-1.5 px-5 py-2 bg-white text-indigo-700 hover:bg-indigo-50 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow active:scale-95">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan
                </button>
            </div>
        </div>
    </x-slot>

    {{-- ═══ MAIN CONTENT ═══ --}}
    <div class="py-3 px-2 sm:px-3">

        {{-- Errors Flash --}}
        @if($errors->any())
            <div
                class="mb-3 p-3 bg-rose-50 dark:bg-rose-900/20 border-l-4 border-rose-500 text-rose-700 dark:text-rose-400 rounded-lg">
                <p class="font-black text-sm mb-1">Terjadi Kesalahan!</p>
                <ul class="list-disc list-inside text-xs space-y-0.5">@foreach($errors->all() as $error)<li>{{ $error }}
                </li>@endforeach</ul>
            </div>
        @endif

        <form id="cutpack-form" action="{{ route('x-pengganti.cutpack.store') }}" method="POST">
            @csrf
            <input type="hidden" name="x_pengganti_seri_id" value="{{ $seri->id }}">
            <input type="hidden" name="packs_json" id="packs-json-input">

            {{-- ══════════════════════════════════
            HALAMAN 1: Pack 1–50
            [Kiri: 1–25] [Kanan: 26–50]
            ══════════════════════════════════ --}}
            <div id="page-1" class="grid grid-cols-2 gap-3">
                {{-- ── KIRI: Pack 1–25 ── --}}
                <div
                    class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700 shadow-sm relative">
                    <div class="">
                        <table class="w-full border-separate border-spacing-0">
                            @include('x-pengganti.cutpack._table-head', ['title' => 'PACK 1 — 25'])
                            <tbody>
                                @include('x-pengganti.cutpack._table-body', ['start' => 1, 'end' => 25, 'gridData' => $gridData])
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ── KANAN: Pack 26–50 ── --}}
                <div
                    class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700 shadow-sm relative">
                    <div class="">
                        <table class="w-full border-separate border-spacing-0">
                            @include('x-pengganti.cutpack._table-head', ['title' => 'PACK 26 — 50'])
                            <tbody>
                                @include('x-pengganti.cutpack._table-body', ['start' => 26, 'end' => 50, 'gridData' => $gridData])
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ══════════════════════════════════
            HALAMAN 2: Pack 51–100
            [Kiri: 51–75] [Kanan: 76–100]
            ══════════════════════════════════ --}}
            <div id="page-2" class="grid grid-cols-2 gap-3" style="display:none">
                {{-- ── KIRI: Pack 51–75 ── --}}
                <div
                    class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700 shadow-sm relative">
                    <div class="">
                        <table class="w-full border-separate border-spacing-0">
                            @include('x-pengganti.cutpack._table-head', ['title' => 'PACK 51 — 75'])
                            <tbody>
                                @include('x-pengganti.cutpack._table-body', ['start' => 51, 'end' => 75, 'gridData' => $gridData])
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ── KANAN: Pack 76–100 ── --}}
                <div
                    class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700 shadow-sm relative">
                    <div class="">
                        <table class="w-full border-separate border-spacing-0">
                            @include('x-pengganti.cutpack._table-head', ['title' => 'PACK 76 — 100'])
                            <tbody>
                                @include('x-pengganti.cutpack._table-body', ['start' => 76, 'end' => 100, 'gridData' => $gridData])
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Footer Sticky: Navigasi + Simpan --}}
            <div
                class="mt-4 flex flex-wrap items-center justify-between gap-3 sticky bottom-0 p-3 bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-2xl shadow-2xl z-40">
                <div class="flex items-center gap-6 overflow-x-auto pb-1">
                    <div class="flex flex-col min-w-max">
                        <span
                            class="text-[8px] font-black text-amber-500 uppercase tracking-widest leading-none mb-1">Total
                            Seri 1</span>
                        <span id="grand-total-seri1" class="text-xl font-black text-slate-800 dark:text-white">0</span>
                    </div>
                    <div class="flex flex-col min-w-max">
                        <span
                            class="text-[8px] font-black text-amber-500 uppercase tracking-widest leading-none mb-1">Total
                            Seri 2</span>
                        <span id="grand-total-seri2" class="text-xl font-black text-slate-800 dark:text-white">0</span>
                    </div>
                    <div class="flex flex-col min-w-max">
                        <span
                            class="text-[8px] font-black text-indigo-500 uppercase tracking-widest leading-none mb-1">Total
                            Campuran</span>
                        <span id="grand-total-campuran"
                            class="text-xl font-black text-slate-800 dark:text-white">0</span>
                    </div>
                    <div class="w-px h-8 bg-gray-200 dark:bg-slate-700 mx-2"></div>
                    <div class="flex flex-col min-w-max">
                        <span
                            class="text-[8px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest leading-none mb-1">Grand
                            Total Bilyet</span>
                        <span id="grand-total-all"
                            class="text-xl font-black text-indigo-600 dark:text-indigo-400">0</span>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" onclick="showPage(1)" id="footer-btn-1"
                        class="footer-page-btn px-6 py-2.5 bg-white dark:bg-slate-700 border-2 border-indigo-200 dark:border-indigo-800 text-indigo-400 dark:text-indigo-500 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all">
                        Halaman 1
                    </button>
                    <button type="button" onclick="showPage(2)" id="footer-btn-2"
                        class="footer-page-btn px-6 py-2.5 bg-white dark:bg-slate-700 border-2 border-gray-100 dark:border-slate-600 text-gray-400 dark:text-slate-500 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all">
                        Halaman 2
                    </button>
                    <button type="submit" form="cutpack-form"
                        class="ml-2 px-10 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-700 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:from-indigo-700 hover:to-violet-800 transition-all shadow-lg active:scale-95 flex items-center gap-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Data
                    </button>
                </div>
            </div>
        </form>
    </div>

    <style>
        .page-nav-btn {
            background: rgba(255, 255, 255, 0.05);
            color: rgba(255, 255, 255, 0.4);
            border: 1.5px solid rgba(255, 255, 255, 0.1);
        }

        .page-nav-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-color: rgba(255, 255, 255, 0.2);
        }

        .page-nav-btn.active {
            background: #ffffff;
            color: #4338ca;
            font-weight: 900;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.15);
            border-color: white;
        }

        .footer-page-btn.active {
            background: #4338ca;
            color: white !important;
            border-color: #4338ca !important;
            box-shadow: 0 4px 15px rgba(67, 56, 202, 0.3);
        }

        .pack-row-first td {
            border-top: 2px solid #6366f1 !important;
        }

        .dark .pack-row-first td {
            border-top: 2px solid #4f46e5 !important;
        }

        .khazai-input {
            transition: all 0.2s;
        }

        .khazai-input:focus {
            border-color: #6366f1 !important;
            background-color: rgba(99, 102, 241, 0.05) !important;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2) !important;
            outline: none !important;
        }

        /* Custom scrollbar for better look */
        .scroll-container::-webkit-scrollbar {
            width: 5px;
        }

        .scroll-container::-webkit-scrollbar-track {
            background: transparent;
        }

        .scroll-container::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .dark .scroll-container::-webkit-scrollbar-thumb {
            background: #475569;
        }
    </style>

    @push('scripts')
        <script>

            // ── 1. Pagination ────────────────────────────────────
            let currentPage = 1;
            function showPage(n) {
                currentPage = n;
                document.getElementById('page-1').style.display = (n === 1) ? 'grid' : 'none';
                document.getElementById('page-2').style.display = (n === 2) ? 'grid' : 'none';

                // Sync Buttons
                document.querySelectorAll('.page-nav-btn, .footer-page-btn').forEach(b => b.classList.remove('active'));
                document.getElementById(`btn-page-${n}`).classList.add('active');
                document.getElementById(`footer-btn-${n}`).classList.add('active');

                // Scroll to top
                document.querySelector('.scroll-container').scrollTo({ top: 0, behavior: 'smooth' });
            }

            // ── 2. Calculation ───────────────────────────────────
            function onlyDigits(input) {
                input.value = input.value.replace(/[^0-9]/g, '');
            }

            function updateGrandTotal() {
                let g1 = 0, g2 = 0, gc = 0;
                document.querySelectorAll('.badg-s1').forEach(el => {
                    const v = parseInt(el.textContent); if (!isNaN(v) && v > 0) g1 += v;
                });
                document.querySelectorAll('.badg-s2').forEach(el => {
                    const v = parseInt(el.textContent); if (!isNaN(v) && v > 0) g2 += v;
                });
                document.querySelectorAll('.badg-c').forEach(el => {
                    const v = parseInt(el.textContent); if (!isNaN(v) && v > 0) gc += v;
                });

                const fmt = n => n.toLocaleString('id-ID');
                const g1El = document.getElementById('grand-total-seri1'); if(g1El) g1El.textContent = fmt(g1);
                const g2El = document.getElementById('grand-total-seri2'); if(g2El) g2El.textContent = fmt(g2);
                const gcEl = document.getElementById('grand-total-campuran'); if(gcEl) gcEl.textContent = fmt(gc);
                const gaEl = document.getElementById('grand-total-all'); if(gaEl) gaEl.textContent = fmt(g1 + g2 + gc);

                // Update Header Totals
                const hdrTotals = { seri1: g1, seri2: g2, campuran: gc };
                ['seri1', 'seri2', 'campuran'].forEach(t => {
                    const el = document.getElementById(`grand-total-${t}-header`);
                    if (el) el.textContent = fmt(hdrTotals[t] || 0);
                });
            }

            // ── 3. Masking & Navigation ──────────────────────────
            function maskSeri(input) {
                let val = input.value.toUpperCase().replace(/[^A-Z0-9-]/g, '');
                if (val.length > 2 && val[2] !== '-') val = val.slice(0, 2) + '-' + val.slice(2);
                input.value = val;
            }

            document.addEventListener('DOMContentLoaded', () => {
                // Enter key navigation
                document.addEventListener('keydown', function (e) {
                    if (e.key !== 'Enter') return;
                    const inp = e.target;
                    if (!inp.classList.contains('seri-pengganti-input')) return;
                    e.preventDefault();

                    const pack = parseInt(inp.dataset.pack);
                    const nPack = pack + 1;
                    if (nPack > 100) return;

                    if (nPack === 51 && currentPage === 1) showPage(2);
                    if (nPack === 1 && currentPage === 2) showPage(1);

                    const next = document.querySelector(`.seri-pengganti-input[data-pack="${nPack}"]`);
                    if (next) { next.focus(); next.select(); }
                });

                updateGrandTotal();

                // Format existing
                document.querySelectorAll('.seri-pengganti-input').forEach(inp => {
                    if (inp.value) formatSeriPengganti(inp);
                });

                document.getElementById('footer-btn-1')?.classList.add('active');
            });

            // ── 4. Smart Submit (JSON Edition) ───────────────────
            document.getElementById('cutpack-form')?.addEventListener('submit', function (e) {
                e.preventDefault();
                const form = e.target;
                const btn = document.querySelector('button[type="submit"][form="cutpack-form"]');

                if (btn) {
                    btn.style.pointerEvents = 'none';
                    btn.style.opacity = '0.7';
                    btn.innerHTML = `<svg class="w-3.5 h-3.5 animate-spin mr-2" fill="none" viewBox="0 0 24 24" style="display:inline-block;"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Menyimpan...`;
                }

                const packsData = {};
                let hasAnyData = false;

                for (let p = 1; p <= 100; p++) {
                    const index = p - 1;
                    const spValue = form.querySelector(`input[name="packs[${index}][seri_pengganti]"]`)?.value?.trim() || '';
                    const nppValue = form.querySelector(`input[name="packs[${index}][nomor_pack_pengganti]"]`)?.value?.trim() || '';
                    const nbpValue = form.querySelector(`input[name="packs[${index}][nomor_bilyet_pengganti]"]`)?.value?.trim() || '';

                    if (spValue || nppValue || nbpValue) {
                        // VALIDATION: Check Seri Pengganti format (XX-XX9)
                        if (spValue && !/^[A-Z]{2}-[A-Z]{2}[0-9]$/.test(spValue)) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Format Seri Salah!',
                                text: `Pack ${p}: Format Seri Pengganti harus XX-XX9 (Contoh: AB-DB6).`,
                                confirmButtonColor: '#4f46e5'
                            });
                            if (btn) {
                                btn.style.pointerEvents = 'auto';
                                btn.style.opacity = '1';
                                btn.innerHTML = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg> Simpan`;
                            }
                            return;
                        }

                        hasAnyData = true;
                        packsData[index] = {
                            nomor_pack: p,
                            seri_pengganti: spValue || null,
                            nomor_pack_pengganti: nppValue || null,
                            nomor_bilyet_pengganti: nbpValue || null
                        };
                    }
                }

                // Virtual Form Submission
                const virtualForm = document.createElement('form');
                virtualForm.method = 'POST';
                virtualForm.action = form.action;

                const addHidden = (name, val) => {
                    const inp = document.createElement('input');
                    inp.type = 'hidden'; inp.name = name; inp.value = val;
                    virtualForm.appendChild(inp);
                };

                addHidden('_token', form.querySelector('input[name="_token"]').value);
                addHidden('x_pengganti_seri_id', form.querySelector('input[name="x_pengganti_seri_id"]').value);
                addHidden('packs_json', hasAnyData ? JSON.stringify(packsData) : '');

                document.body.appendChild(virtualForm);
                virtualForm.submit();
            });
        </script>
    @endpush
</x-app-layout>