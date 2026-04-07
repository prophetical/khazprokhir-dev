<x-app-layout :full-screen="true">
    {{-- ═══ STICKY ACTION BAR in HEADER ═══ --}}
    <x-slot name="header">
        <div class="flex items-center justify-between w-full gap-4">

            {{-- Kiri: Info Seri --}}
            <div class="flex items-center gap-3 min-w-0">
                <a href="{{ route('x-pengganti.khazai.index') }}"
                    class="p-2 rounded-xl text-white/60 hover:text-white hover:bg-white/10 transition-all shrink-0" title="Kembali ke daftar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                @php
                    $pchBadgeColor = ['S'=>'bg-lime-400','T'=>'bg-gray-300','U'=>'bg-amber-300','V'=>'bg-purple-400','W'=>'bg-green-400','X'=>'bg-blue-400','Y'=>'bg-red-400'];
                @endphp
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl {{ $pchBadgeColor[$seri->pecahan] ?? 'bg-gray-400' }} font-black text-white text-sm shadow">{{ $seri->pecahan }}</span>
                <div class="min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-white font-black text-base tracking-wider">{{ $seri->seri }}</span>
                        <span class="px-2 py-0.5 bg-white/10 rounded-lg text-white/70 text-[10px] font-bold">Batch {{ $seri->batch }}</span>
                        <span class="px-2 py-0.5 bg-white/10 rounded-lg text-white/70 text-[10px] font-bold">TA {{ $seri->tahun_anggaran }}</span>
                        <span class="px-2 py-0.5 bg-white/10 rounded-lg text-white/70 text-[10px] font-bold">TE {{ $seri->tahun_emisi }}</span>
                    </div>
                </div>
            </div>

            {{-- Tengah: Navigasi Halaman --}}
            <div class="flex items-center gap-1.5 shrink-0">
                <button id="btn-page-1" onclick="showPage(1)"
                    class="page-nav-btn active px-4 py-2 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all">
                    <span class="block text-[8px] opacity-70 mb-0.5 leading-none">Pack</span>
                    1 – 50
                </button>
                <div class="w-px h-8 bg-white/20"></div>
                <button id="btn-page-2" onclick="showPage(2)"
                    class="page-nav-btn px-4 py-2 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all">
                    <span class="block text-[8px] opacity-70 mb-0.5 leading-none">Pack</span>
                    51 – 100
                </button>
            </div>

            {{-- Kanan: Total + Aksi --}}
            <div class="flex items-center gap-2 shrink-0">
                <div class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 bg-white/10 rounded-xl border border-white/10">
                    <span class="text-[9px] font-black text-white/60 uppercase tracking-widest">Total Rusak</span>
                    <span id="grand-total-header" class="text-sm font-black text-white">0</span>
                    <span class="text-[9px] text-white/50 font-bold">Vell</span>
                </div>
                <a href="{{ route('x-pengganti.khazai.pdf', ['seri_id' => $seri->id]) }}" target="_blank"
                    class="inline-flex items-center gap-1.5 px-3 py-2 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    PDF
                </a>
                @if(in_array(auth()->user()->role, ['admin', 'sortir', 'kemas', 'khazverutas']))
                <button type="submit" form="khazai-form"
                    class="inline-flex items-center gap-1.5 px-5 py-2 bg-white text-violet-700 hover:bg-violet-50 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow active:scale-95">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    Simpan
                </button>
                @endif
            </div>
        </div>
    </x-slot>

    {{-- ═══ MAIN CONTENT ═══ --}}
    <div class="py-3 px-2 sm:px-3">

        {{-- Flash --}}
        @if(session('success'))
            <div class="mb-3 p-3 bg-emerald-50 dark:bg-emerald-900/20 border-l-4 border-emerald-500 text-emerald-700 dark:text-emerald-400 rounded-r-lg flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span class="font-bold text-sm">{{ session('success') }}</span>
            </div>
        @endif
        @if($errors->any())
            <div class="mb-3 p-3 bg-rose-50 dark:bg-rose-900/20 border-l-4 border-rose-500 text-rose-700 dark:text-rose-400 rounded-r-lg">
                <p class="font-black text-sm mb-1">Terjadi Kesalahan!</p>
                <ul class="list-disc list-inside text-xs space-y-0.5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form id="khazai-form" action="{{ route('x-pengganti.khazai.store') }}" method="POST">
            @csrf
            <input type="hidden" name="x_pengganti_seri_id" value="{{ $seri->id }}">

            {{-- ══════════════════════════════════
                 HALAMAN 1: Pack 1–50
                 [Kiri: 1–25] [Kanan: 26–50]
            ══════════════════════════════════ --}}
            <div id="page-1" class="grid grid-cols-2 gap-2">

                {{-- ── KIRI: Pack 1–25 ── --}}
                <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700 shadow-sm relative">
                    <div class="sticky top-16 z-30 px-3 py-2 bg-slate-800 text-white text-[9px] font-black uppercase tracking-widest text-center border-b border-slate-700 rounded-t-xl">
                        Pack 1 – 25
                    </div>
                    <div class="">
                        <table class="w-full border-collapse" id="table-A">
                            @include('x-pengganti.khazai._table-head')
                            <tbody>
                                @include('x-pengganti.khazai._table-body', ['start' => 1, 'end' => 25, 'gridData' => $gridData])
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ── KANAN: Pack 26–50 ── --}}
                <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700 shadow-sm relative">
                    <div class="sticky top-16 z-30 px-3 py-2 bg-slate-800 text-white text-[9px] font-black uppercase tracking-widest text-center border-b border-slate-700 rounded-t-xl">
                        Pack 26 – 50
                    </div>
                    <div class="">
                        <table class="w-full border-collapse" id="table-B">
                            @include('x-pengganti.khazai._table-head')
                            <tbody>
                                @include('x-pengganti.khazai._table-body', ['start' => 26, 'end' => 50, 'gridData' => $gridData])
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ══════════════════════════════════
                 HALAMAN 2: Pack 51–100
                 [Kiri: 51–75] [Kanan: 76–100]
            ══════════════════════════════════ --}}
            <div id="page-2" class="grid grid-cols-2 gap-2" style="display:none">

                {{-- ── KIRI: Pack 51–75 ── --}}
                <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700 shadow-sm relative">
                    <div class="sticky top-16 z-30 px-3 py-2 bg-slate-800 text-white text-[9px] font-black uppercase tracking-widest text-center border-b border-slate-700 rounded-t-xl">
                        Pack 51 – 75
                    </div>
                    <div class="">
                        <table class="w-full border-collapse" id="table-C">
                            @include('x-pengganti.khazai._table-head')
                            <tbody>
                                @include('x-pengganti.khazai._table-body', ['start' => 51, 'end' => 75, 'gridData' => $gridData])
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ── KANAN: Pack 76–100 ── --}}
                <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700 shadow-sm relative">
                    <div class="sticky top-16 z-30 px-3 py-2 bg-slate-800 text-white text-[9px] font-black uppercase tracking-widest text-center border-b border-slate-700 rounded-t-xl">
                        Pack 76 – 100
                    </div>
                    <div class="">
                        <table class="w-full border-collapse" id="table-D">
                            @include('x-pengganti.khazai._table-head')
                            <tbody>
                                @include('x-pengganti.khazai._table-body', ['start' => 76, 'end' => 100, 'gridData' => $gridData])
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Footer Navigasi + Simpan --}}
            @if(in_array(auth()->user()->role, ['admin', 'sortir', 'kemas', 'khazverutas']))
            <div class="mt-3 flex items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <div class="px-4 py-2 bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-xl shadow-sm">
                        <span class="text-[9px] font-black text-violet-500 uppercase tracking-widest">Grand Total Rusak: </span>
                        <span id="grand-total-footer" class="text-sm font-black text-violet-700 dark:text-violet-300">0</span>
                        <span class="text-[10px] text-violet-400"> Vell</span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="showPage(1)" id="footer-btn-1"
                        class="footer-page-btn px-4 py-2 bg-white dark:bg-slate-700 border-2 border-violet-300 dark:border-violet-600 text-violet-600 dark:text-violet-400 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all">
                        ← Halaman 1
                    </button>
                    <button type="button" onclick="showPage(2)" id="footer-btn-2"
                        class="footer-page-btn px-4 py-2 bg-white dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 text-gray-400 dark:text-slate-400 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all">
                        Halaman 2 →
                    </button>
                    <button type="submit" form="khazai-form"
                        class="px-8 py-2 bg-gradient-to-r from-violet-600 to-purple-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:from-violet-700 hover:to-purple-700 transition-all shadow-lg shadow-violet-200 dark:shadow-violet-900/30 active:scale-95 flex items-center gap-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        Simpan Semua Data
                    </button>
                </div>
            </div>
            @endif

        </form>
    </div>

    <style>
        /* Page nav buttons */
        .page-nav-btn {
            background: rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.6);
            border: 1.5px solid rgba(255,255,255,0.15);
        }
        .page-nav-btn:hover { background: rgba(255,255,255,0.15); color: white; }
        .page-nav-btn.active { background: rgba(255,255,255,0.95); color: #7c3aed; border-color: white; }

        /* Pack border separator */
        .pack-row-first td { border-top: 2.5px solid #8b5cf6 !important; }
        body.dark-mode .pack-row-first td { border-top: 2.5px solid #6d28d9 !important; }

        /* Seri valid/invalid */
        .seri-pengganti-input.is-valid  { border-color: #10b981 !important; }
        .seri-pengganti-input.is-invalid { border-color: #f43f5e !important; }

        /* Footer page buttons active state */
        .footer-page-btn.active {
            background: #7c3aed;
            color: white;
            border-color: #7c3aed;
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

            // Header buttons
            document.querySelectorAll('.page-nav-btn').forEach(b => b.classList.remove('active'));
            document.getElementById(`btn-page-${n}`)?.classList.add('active');

            // Footer buttons
            document.querySelectorAll('.footer-page-btn').forEach(b => b.classList.remove('active'));
            document.getElementById(`footer-btn-${n}`)?.classList.add('active');

            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // ── Hanya angka ─────────────────────────────────────
        function onlyDigits(input) {
            input.value = input.value.replace(/[^0-9]/g, '');
        }

        // ── Kalkulasi per Pack ───────────────────────────────
        function calcPack(packNum) {
            const inputs = document.querySelectorAll(`.rusak-input[data-pack="${packNum}"]`);
            let sum = 0;
            inputs.forEach(inp => { const v = parseInt(inp.value); if (!isNaN(v) && v > 0) sum += v; });
            const el = document.getElementById(`jumlah-${packNum}`);
            if (el) {
                el.textContent = sum > 0 ? sum : '–';
                el.className = sum > 0
                    ? 'text-sm font-black text-violet-700 dark:text-violet-300'
                    : 'text-sm font-black text-gray-300 dark:text-slate-600';
            }
            calcGrandTotal();
        }

        function calcGrandTotal() {
            let grand = 0;
            document.querySelectorAll('.rusak-input').forEach(inp => {
                const v = parseInt(inp.value); if (!isNaN(v) && v > 0) grand += v;
            });
            ['grand-total-header','grand-total-footer'].forEach(id => {
                const el = document.getElementById(id); if (el) el.textContent = grand;
            });
        }

        // ── Enter → pindah baris berikutnya (kolom sama) ────
        document.addEventListener('DOMContentLoaded', () => {
            document.addEventListener('keydown', function(e) {
                if (e.key !== 'Enter') return;
                const inp = e.target;
                if (!inp.classList.contains('khazai-input')) return;
                e.preventDefault();

                const col  = inp.dataset.col;
                const pack = parseInt(inp.dataset.pack);
                const slot = parseInt(inp.dataset.slot);

                let nPack = pack, nSlot = slot + 1;
                if (nSlot > 4) { nSlot = 1; nPack = pack + 1; }
                if (nPack > 100) return;

                // Jika next pack ada di halaman 2, switch halaman
                if (nPack === 51 && currentPage === 1) showPage(2);
                if (nPack === 1  && currentPage === 2) showPage(1);

                const next = document.querySelector(
                    `.khazai-input[data-pack="${nPack}"][data-slot="${nSlot}"][data-col="${col}"]`
                );
                if (next) { next.focus(); next.select(); }
            });

            // Init kalkulasi semua pack
            for (let p = 1; p <= 100; p++) calcPack(p);

            // Validasi seri pengganti yang sudah terisi
            document.querySelectorAll('.seri-pengganti-input').forEach(inp => {
                if (inp.value) formatSeriPengganti(inp);
            });

            // Footer page 1 aktif default
            document.getElementById('footer-btn-1')?.classList.add('active');
        });

        // ── Cegah submit via Enter di form ──────────────────
        document.getElementById('khazai-form')?.addEventListener('keydown', e => {
            if (e.key === 'Enter' && e.target.tagName === 'INPUT') e.preventDefault();
        });

        // ── Auto-format Seri Pengganti ───────────────────────
        function formatSeriPengganti(input) {
            let raw = input.value.toUpperCase().replace(/[^A-Z0-9\-]/g, '');
            let clean = raw.replace(/-/g, '');
            let f = '';
            if (clean.length <= 2) {
                f = clean.replace(/[^A-Z]/g, '');
            } else if (clean.length <= 4) {
                const p1 = clean.substring(0,2).replace(/[^A-Z]/g,'');
                const p2 = clean.substring(2,4).replace(/[^A-Z]/g,'');
                f = p1 + (p2.length > 0 ? '-' + p2 : '');
            } else {
                const p1 = clean.substring(0,2).replace(/[^A-Z]/g,'');
                const p2 = clean.substring(2,4).replace(/[^A-Z]/g,'');
                const p3 = clean.substring(4,5).replace(/[^0-9]/g,'');
                f = p1 + '-' + p2 + p3;
            }
            input.value = f;
            const ok = /^[A-Z]{2}-[A-Z]{2}[0-9]$/.test(f);
            if (!f.length) { input.classList.remove('is-valid','is-invalid'); }
            else if (ok)   { input.classList.add('is-valid'); input.classList.remove('is-invalid'); }
            else           { input.classList.add('is-invalid'); input.classList.remove('is-valid'); }
        }

        function handleSeriKeydown(event, input) {
            if (event.keyCode === 8) {
                const pos = input.selectionStart;
                if (input.value[pos - 1] === '-') {
                    event.preventDefault();
                    input.value = input.value.substring(0, pos - 2) + input.value.substring(pos);
                    input.setSelectionRange(pos - 2, pos - 2);
                    formatSeriPengganti(input);
                }
            }
        }

        // ── Loading state ─────────────────────────────────────
        document.getElementById('khazai-form')?.addEventListener('submit', function() {
            document.querySelectorAll('button[type="submit"]').forEach(btn => {
                btn.disabled = true;
                btn.innerHTML = `<svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Menyimpan...`;
            });
        });
    </script>
    @endpush
</x-app-layout>
