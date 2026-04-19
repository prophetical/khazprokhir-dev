<x-app-layout :full-screen="true">
    {{-- ═══ STICKY ACTION BAR in HEADER ═══ --}}
    <x-slot name="header">
        <div class="flex items-center justify-between w-full gap-4">

            {{-- Kiri: Info Seri --}}
            <div class="flex items-center gap-3 min-w-0">
                <a href="{{ route('x-pengganti.rikyet.index') }}" class="p-2 rounded-xl transition-all shrink-0"
                    style="color:rgba(255,255,255,0.6);"
                    onmouseover="this.style.color='#fff'; this.style.background='rgba(255,255,255,0.1)'"
                    onmouseout="this.style.color='rgba(255,255,255,0.6)'; this.style.background='transparent'"
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
                        <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold"
                            style="background:rgba(255,255,255,0.12); color:rgba(255,255,255,0.75);">Batch
                            {{ $seri->batch }}</span>
                    </div>
                    <p class="text-[9px] font-bold uppercase tracking-widest mt-0.5"
                        style="color:rgba(255,255,255,0.4);">SEKSI SAIPARSIAL</p>
                </div>
            </div>

            {{-- Tengah: Navigasi Halaman (Sudah sesuai Khazai) --}}
            <div class="flex items-center gap-1.5 shrink-0">
                <button type="button" id="btn-page-1" onclick="showPage(1)"
                    class="page-nav-btn active px-4 py-2 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all">
                    <span class="block text-[8px] opacity-70 mb-0.5 leading-none">Pack</span>
                    1 – 50
                </button>
                <div class="w-px h-8 bg-white/20"></div>
                <button type="button" id="btn-page-2" onclick="showPage(2)"
                    class="page-nav-btn px-4 py-2 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all">
                    <span class="block text-[8px] opacity-70 mb-0.5 leading-none">Pack</span>
                    51 – 100
                </button>
            </div>

            {{-- Kanan: Grand Total + Aksi --}}
            <div class="flex items-center gap-2 shrink-0">
                {{-- Grand Total --}}
                <div class="hidden md:flex items-center gap-3 px-3 py-1.5 rounded-xl border"
                    style="background:rgba(255,255,255,0.1); border-color:rgba(255,255,255,0.1);">
                    <div class="text-center">
                        <div class="text-[8px] font-black uppercase tracking-widest" style="color:#5eead4;">SERI 1</div>
                        <div id="grand-total-seri1-header" class="text-sm font-black text-white">0</div>
                    </div>
                    <div class="w-px h-6" style="background:rgba(255,255,255,0.2);"></div>
                    <div class="text-center">
                        <div class="text-[8px] font-black uppercase tracking-widest" style="color:#5eead4;">SERI 2</div>
                        <div id="grand-total-seri2-header" class="text-sm font-black text-white">0</div>
                    </div>
                    <div class="w-px h-6" style="background:rgba(255,255,255,0.2);"></div>
                    <div class="text-center">
                        <div class="text-[8px] font-black uppercase tracking-widest" style="color:#5eead4;">CAMPURAN
                        </div>
                        <div id="grand-total-campuran-header" class="text-sm font-black text-white">0</div>
                    </div>
                </div>

                {{-- Export PDF --}}
                <a href="{{ route('x-pengganti.rikyet.pdf', ['seri_id' => $seri->id]) }}" target="_blank"
                    class="inline-flex items-center gap-1.5 px-3 py-2 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all"
                    style="background:rgba(255,255,255,0.1); border:1.5px solid rgba(255,255,255,0.2); color:#fff;">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    PDF
                </a>

                {{-- Simpan --}}
                <button type="submit" form="rikyet-form"
                    class="inline-flex items-center gap-1.5 px-5 py-2 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow active:scale-95"
                    style="background:#ffffff; color:#0f766e;">
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

        @if($errors->any())
            <div
                class="mb-3 p-3 bg-rose-50 dark:bg-rose-900/20 border-l-4 border-rose-500 text-rose-700 dark:text-rose-400 rounded-r-lg">
                <p class="font-black text-sm mb-1">Terjadi Kesalahan!</p>
                <ul class="list-disc list-inside text-xs space-y-0.5">@foreach($errors->all() as $error)<li>{{ $error }}
                </li>@endforeach</ul>
            </div>
        @endif

        <form id="rikyet-form" action="{{ route('x-pengganti.rikyet.store') }}" method="POST">
            @csrf
            <input type="hidden" name="x_pengganti_seri_id" value="{{ $seri->id }}">
            <input type="hidden" name="packs_json" id="packs-json-input">

            {{-- ══════════════════════════════════
            HALAMAN 1: Pack 1–50
            [Kiri: 1–25] [Kanan: 26–50]
            ══════════════════════════════════ --}}
            <div id="page-1" class="grid grid-cols-2 gap-3">

                {{-- ── Kiri: Pack 1–25 ── --}}
                <div
                    class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700 shadow-sm">
                    <table class="w-full border-separate border-spacing-0" id="table-A">
                        @include('x-pengganti.rikyet._table-head', ['title' => 'PACK 1 — 25'])
                        <tbody>
                            @include('x-pengganti.rikyet._table-body', ['start' => 1, 'end' => 25, 'gridData' => $gridData])
                        </tbody>
                    </table>
                </div>

                {{-- ── Kanan: Pack 26–50 ── --}}
                <div
                    class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700 shadow-sm">
                    <table class="w-full border-separate border-spacing-0" id="table-B">
                        @include('x-pengganti.rikyet._table-head', ['title' => 'PACK 26 — 50'])
                        <tbody>
                            @include('x-pengganti.rikyet._table-body', ['start' => 26, 'end' => 50, 'gridData' => $gridData])
                        </tbody>
                    </table>
                </div>

            </div>{{-- /page-1 --}}

            {{-- ══════════════════════════════════
            HALAMAN 2: Pack 51–100
            [Kiri: 51–75] [Kanan: 76–100]
            ══════════════════════════════════ --}}
            <div id="page-2" class="grid grid-cols-2 gap-3" style="display:none">

                {{-- ── Kiri: Pack 51–75 ── --}}
                <div
                    class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700 shadow-sm">
                    <table class="w-full border-separate border-spacing-0" id="table-C">
                        @include('x-pengganti.rikyet._table-head', ['title' => 'PACK 51 — 75'])
                        <tbody>
                            @include('x-pengganti.rikyet._table-body', ['start' => 51, 'end' => 75, 'gridData' => $gridData])
                        </tbody>
                    </table>
                </div>

                {{-- ── Kanan: Pack 76–100 ── --}}
                <div
                    class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700 shadow-sm">
                    <table class="w-full border-separate border-spacing-0" id="table-D">
                        @include('x-pengganti.rikyet._table-head', ['title' => 'PACK 76 — 100'])
                        <tbody>
                            @include('x-pengganti.rikyet._table-body', ['start' => 76, 'end' => 100, 'gridData' => $gridData])
                        </tbody>
                    </table>
                </div>

            </div>{{-- /page-2 --}}

            {{-- Footer Sticky: Navigasi + Simpan --}}
            <div
                class="mt-4 flex items-center justify-between gap-3 sticky bottom-0 p-3 bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-2xl shadow-2xl z-40">
                <div class="flex items-center gap-5">
                    <div class="flex flex-col items-center">
                        <span class="text-[8px] font-black uppercase tracking-widest" style="color:#0d9488;">
                            SERI 1</span>
                        <span id="grand-total-seri1" class="text-lg font-black text-slate-800 dark:text-white">0</span>
                    </div>
                    <div class="w-px h-10 bg-gray-200 dark:bg-slate-600"></div>
                    <div class="flex flex-col items-center">
                        <span class="text-[8px] font-black uppercase tracking-widest" style="color:#0d9488;">
                            SERI 2</span>
                        <span id="grand-total-seri2" class="text-lg font-black text-slate-800 dark:text-white">0</span>
                    </div>
                    <div class="w-px h-10 bg-gray-200 dark:bg-slate-600"></div>
                    <div class="flex flex-col items-center">
                        <span class="text-[8px] font-black uppercase tracking-widest" style="color:#0d9488;">
                            CAMPURAN</span>
                        <span id="grand-total-campuran"
                            class="text-lg font-black text-slate-800 dark:text-white">0</span>
                    </div>
                    <div class="w-px h-10 bg-gray-200 dark:bg-slate-600"></div>
                    <div class="flex flex-col items-center">
                        <span class="text-[8px] font-black uppercase tracking-widest text-slate-500">Grand Total
                            Brood</span>
                        <span id="grand-total-all" class="text-lg font-black" style="color:#0d9488;">0</span>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" onclick="showPage(1)" id="footer-btn-1"
                        class="footer-page-btn px-6 py-2 bg-white dark:bg-slate-700 border-2 border-teal-300 dark:border-teal-600 text-teal-600 dark:text-teal-400 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all active">
                        ← Halaman 1
                    </button>
                    <button type="button" onclick="showPage(2)" id="footer-btn-2"
                        class="footer-page-btn px-6 py-2 bg-white dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 text-gray-400 dark:text-slate-400 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all">
                        Halaman 2 →
                    </button>
                    <button type="submit" form="rikyet-form"
                        class="px-10 py-2.5 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow-lg active:scale-95 flex items-center gap-2"
                        style="background:linear-gradient(135deg, #0d9488, #0891b2);">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Semua Data
                    </button>
                </div>
            </div>

        </form>
    </div>

    <style>
        /* Page nav buttons */
        .page-nav-btn {
            background: rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.6);
            border: 1.5px solid rgba(255, 255, 255, 0.15);
        }

        .page-nav-btn:hover {
            background: rgba(255, 255, 255, 0.15);
            color: white;
        }

        .page-nav-btn.active {
            background: #ffffff;
            color: #0d9488;
            border-color: white;
        }

        /* Footer page buttons active state */
        .footer-page-btn.active {
            background: #0d9488;
            color: white !important;
            border-color: #0d9488 !important;
        }

        /* Seri Pengganti valid/invalid feedback */
        .seri-pengganti-input.is-valid {
            border-color: #10b981 !important;
            background-color: #f0fdf4 !important;
            color: #15803d !important;
        }

        .seri-pengganti-input.is-invalid {
            border-color: #f43f5e !important;
            background-color: #fff1f2 !important;
            color: #be123c !important;
        }

        body.dark-mode .seri-pengganti-input.is-valid {
            background-color: rgba(16, 185, 129, 0.15) !important;
            color: #10b981 !important;
        }

        body.dark-mode .seri-pengganti-input.is-invalid {
            background-color: rgba(244, 63, 94, 0.15) !important;
            color: #fb7185 !important;
        }

        /* Hapus spin button number input */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>

    @push('scripts')
        <script>

            // ── Page Navigation ─────────────────────────────────
            let currentPage = 1;
            function showPage(n) {
                currentPage = n;
                document.getElementById('page-1').style.display = n === 1 ? 'grid' : 'none';
                document.getElementById('page-2').style.display = n === 2 ? 'grid' : 'none';

                // Sync Header & Footer Buttons
                document.querySelectorAll('.page-nav-btn, .footer-page-btn').forEach(b => b.classList.remove('active'));
                document.getElementById(`btn-page-${n}`).classList.add('active');
                document.getElementById(`footer-btn-${n}`).classList.add('active');

                // Scroll to top
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }

            // ── Kalkulasi Total per Pack ──────────────────────────────────
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
                
                const h1El = document.getElementById('grand-total-seri1-header'); if(h1El) h1El.textContent = fmt(g1);
                const h2El = document.getElementById('grand-total-seri2-header'); if(h2El) h2El.textContent = fmt(g2);
                const hcEl = document.getElementById('grand-total-campuran-header'); if(hcEl) hcEl.textContent = fmt(gc);
            }

            // ── Enter → pindah ke slot berikutnya (kolom sama) ───────────
            document.addEventListener('DOMContentLoaded', () => {
                document.addEventListener('keydown', function (e) {
                    if (e.key !== 'Enter') return;
                    const inp = e.target;
                    if (!inp.classList.contains('rikyet-input')) return;
                    e.preventDefault();

                    const pack = parseInt(inp.dataset.pack);
                    const nPack = pack + 1;
                    if (nPack > 100) return;

                    // Switch page if necessary
                    if (nPack === 51 && currentPage === 1) showPage(2);
                    if (nPack === 1 && currentPage === 2) showPage(1);

                    const next = document.querySelector(`.rikyet-input[data-pack="${nPack}"]`);
                    if (next) { next.focus(); next.select(); }
                });

                updateGrandTotal();

                // Init serial replacements validation
                document.querySelectorAll('.seri-pengganti-input').forEach(inp => {
                    if (inp.value) formatSeriPengganti(inp);
                });
            });

            // ── Blokir Enter di form ─────────
            document.getElementById('rikyet-form')?.addEventListener('keydown', e => {
                if (e.key === 'Enter' && e.target.tagName === 'INPUT') e.preventDefault();
            });

            // ── Auto-format & Validasi Seri Pengganti ────────────────────
            function formatSeriPengganti(input) {
                const oldLen = input.value.length;
                const cursor = input.selectionStart;
                const raw = input.value.toUpperCase().replace(/[^A-Z0-9\-]/g, '');
                const clean = raw.replace(/-/g, '');
                let f = '';
                if (clean.length <= 2) {
                    f = clean.replace(/[^A-Z]/g, '');
                } else if (clean.length <= 4) {
                    const p1 = clean.substring(0, 2).replace(/[^A-Z]/g, '');
                    const p2 = clean.substring(2, 4).replace(/[^A-Z]/g, '');
                    f = p1 + (p2.length > 0 ? '-' + p2 : '');
                } else {
                    const p1 = clean.substring(0, 2).replace(/[^A-Z]/g, '');
                    const p2 = clean.substring(2, 4).replace(/[^A-Z]/g, '');
                    const p3 = clean.substring(4, 5).replace(/[^0-9]/g, '');
                    f = p1 + '-' + p2 + p3;
                }
                input.value = f;
                let newCursor = cursor;
                if (f.length > oldLen && f.includes('-') && cursor >= 3) newCursor++;
                input.setSelectionRange(newCursor, newCursor);
                const ok = /^[A-Z]{2}-[A-Z]{2}[0-9]$/.test(f);
                if (!f.length) {
                    input.classList.remove('is-valid', 'is-invalid');
                    input.style.borderColor = '#e5e7eb';
                } else if (ok) {
                    input.classList.add('is-valid');
                    input.classList.remove('is-invalid');
                    input.style.borderColor = '#10b981';
                } else {
                    input.classList.add('is-invalid');
                    input.classList.remove('is-valid');
                    input.style.borderColor = '#f43f5e';
                }
            }

            function handleSeriKeydown(event, input) {
                if (event.keyCode === 8) { // Backspace
                    const pos = input.selectionStart;
                    if (input.value[pos - 1] === '-') {
                        event.preventDefault();
                        input.value = input.value.substring(0, pos - 2) + input.value.substring(pos);
                        input.setSelectionRange(pos - 2, pos - 2);
                        formatSeriPengganti(input);
                    }
                }
            }

            // ── JSON Submit ──
            document.getElementById('rikyet-form')?.addEventListener('submit', function (e) {
                e.preventDefault(); // Stop normal massive DOM submission
                const form = e.target;
                const btns = document.querySelectorAll('button[type="submit"]');

                btns.forEach(btn => {
                    btn.style.pointerEvents = 'none';
                    btn.style.opacity = '0.65';
                    btn.innerHTML = `<svg class="w-3.5 h-3.5 animate-spin mr-1" fill="none" viewBox="0 0 24 24" style="display:inline-block;vertical-align:middle;"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Menyimpan...`;
                });

                const packsData = {};
                let hasAnyData = false;

                for (let p = 1; p <= 100; p++) {
                    const index = p - 1;
                    const spValue = form.querySelector(`input[name="packs[${index}][seri_pengganti]"]`)?.value?.trim() || '';

                    if (spValue) {
                        if (!/^[A-Z]{2}-[A-Z]{2}[0-9]$/.test(spValue)) {
                            return showError(`Pack ${p}: Format Seri Pengganti harus XX-XX9 (Contoh: AB-DB6).`);
                        }

                        packsData[index] = {
                            nomor_pack: p,
                            seri_pengganti: spValue,
                        };
                        hasAnyData = true;
                    }
                }

                function showError(msg) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Format Seri Salah!',
                        text: msg,
                        confirmButtonColor: '#0d9488'
                    });
                    btns.forEach(btn => {
                        btn.style.pointerEvents = 'auto';
                        btn.style.opacity = '1';
                        btn.innerHTML = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg> Simpan`;
                    });
                    return false;
                }

                // ── VIRTUAL FORM SUBMISSION to bypass UI freeze ──
                const virtualForm = document.createElement('form');
                virtualForm.method = 'POST';
                virtualForm.action = form.action;

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = form.querySelector('input[name="_token"]').value;
                virtualForm.appendChild(csrfInput);

                const seriInput = document.createElement('input');
                seriInput.type = 'hidden';
                seriInput.name = 'x_pengganti_seri_id';
                seriInput.value = form.querySelector('input[name="x_pengganti_seri_id"]').value;
                virtualForm.appendChild(seriInput);

                const jsonInput = document.createElement('input');
                jsonInput.type = 'hidden';
                jsonInput.name = 'packs_json';
                jsonInput.value = hasAnyData ? JSON.stringify(packsData) : '';
                virtualForm.appendChild(jsonInput);

                document.body.appendChild(virtualForm);
                virtualForm.submit(); // Browser sends immediately without DOM repaint/freeze
            });
        </script>
    @endpush
</x-app-layout>