<x-app-layout :full-screen="true">
    <div class="h-screen w-full flex flex-col bg-slate-50 dark:bg-slate-900 overflow-hidden">

        {{-- Top Sticky Action Bar ── --}}
        <x-slot name="header">
            <div class="flex items-center justify-between w-full gap-4">
                {{-- Kiri: Info Seri + Navigasi --}}
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

                    {{-- Info Seri --}}
                    <div class="hidden xl:flex items-center gap-2 mr-2">
                        <span class="text-white font-black text-sm tracking-widest">{{ $seri->seri }}</span>
                        <div
                            class="flex items-center gap-1.5 px-2 py-0.5 bg-white/10 rounded-lg border border-white/5 whitespace-nowrap">
                            <span class="text-white/70 text-[9px] font-bold uppercase tracking-tight">Batch
                                {{ $seri->batch }}</span>
                            <span class="w-1 h-1 bg-white/30 rounded-full"></span>
                            <span class="text-white/70 text-[9px] font-bold uppercase tracking-tight">TA
                                {{ $seri->tahun_anggaran }}</span>
                            <span class="w-1 h-1 bg-white/30 rounded-full"></span>
                            <span class="text-white/70 text-[9px] font-bold uppercase tracking-tight">TE
                                {{ $seri->tahun_emisi }}</span>
                        </div>
                    </div>

                    {{-- Navigasi Halaman (Atas) --}}
                    <div class="flex items-center gap-1.5 ml-2 p-1 bg-white/10 rounded-xl border border-white/10">
                        <button type="button" onclick="showPage(1)" id="btn-page-1"
                            class="page-nav-btn px-4 py-1.5 text-[10px] font-black uppercase tracking-widest rounded-lg transition-all active">
                            Page 1 (1–50)
                        </button>
                        <button type="button" onclick="showPage(2)" id="btn-page-2"
                            class="page-nav-btn px-4 py-1.5 text-[10px] font-black uppercase tracking-widest rounded-lg transition-all">
                            Page 2 (51–100)
                        </button>
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

        {{-- Errors Flash --}}
        @if($errors->any())
            <div
                class="shrink-0 p-4 bg-rose-50 dark:bg-rose-900/30 border-b border-rose-200 dark:border-rose-800 text-rose-600 dark:text-rose-400 text-sm font-medium z-30">
                <ul class="list-disc pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        {{-- Main Interaction Area (Scrollable) --}}
        <div class="flex-1 overflow-y-auto px-2 sm:px-3 py-3">
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
                        class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700 shadow-sm relative overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full border-separate border-spacing-0 relative text-slate-800">
                                @include('x-pengganti.cutpack._table-head', ['title' => 'PACK 1 — 25'])
                                <tbody class="divide-y-0">

                                    @include('x-pengganti.cutpack._table-body', ['start' => 1, 'end' => 25, 'gridData' => $gridData])
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- ── KANAN: Pack 26–50 ── --}}
                    <div
                        class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700 shadow-sm relative overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full border-separate border-spacing-0 relative text-slate-800">
                                @include('x-pengganti.cutpack._table-head', ['title' => 'PACK 26 — 50'])
                                <tbody class="divide-y-0">

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
                        class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700 shadow-sm relative overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full border-separate border-spacing-0 relative text-slate-800">
                                @include('x-pengganti.cutpack._table-head', ['title' => 'PACK 51 — 75'])
                                <tbody class="divide-y-0">

                                    @include('x-pengganti.cutpack._table-body', ['start' => 51, 'end' => 75, 'gridData' => $gridData])
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- ── KANAN: Pack 76–100 ── --}}
                    <div
                        class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700 shadow-sm relative overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full border-separate border-spacing-0 relative text-slate-800">
                                @include('x-pengganti.cutpack._table-head', ['title' => 'PACK 76 — 100'])
                                <tbody class="divide-y-0">

                                    @include('x-pengganti.cutpack._table-body', ['start' => 76, 'end' => 100, 'gridData' => $gridData])
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Footer Action Bar (Sticky) --}}
                <div
                    class="mt-4 flex items-center justify-between gap-3 sticky bottom-0 p-3 bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-2xl shadow-2xl z-40">
                    <div class="flex items-center gap-6">
                        <div class="flex flex-col">
                            <span class="text-[8px] font-black text-amber-500 uppercase tracking-widest">Grand Total
                                Seri 1</span>
                            <span id="grand-total-seri1"
                                class="text-lg font-black text-slate-800 dark:text-white">0</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[8px] font-black text-amber-500 uppercase tracking-widest">Grand Total
                                Seri 2</span>
                            <span id="grand-total-seri2"
                                class="text-lg font-black text-slate-800 dark:text-white">0</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[8px] font-black text-indigo-500 uppercase tracking-widest">Total
                                Campuran</span>
                            <span id="grand-total-campuran"
                                class="text-lg font-black text-slate-800 dark:text-white">0</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="button" onclick="showPage(1)" id="footer-btn-1"
                            class="footer-page-btn px-6 py-2 bg-white dark:bg-slate-700 border-2 border-indigo-300 dark:border-indigo-600 text-indigo-600 dark:text-indigo-400 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all active">
                            ← Halaman 1
                        </button>
                        <button type="button" onclick="showPage(2)" id="footer-btn-2"
                            class="footer-page-btn px-6 py-2 bg-white dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 text-gray-400 dark:text-slate-400 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all">
                            Halaman 2 →
                        </button>
                        <button type="submit" form="cutpack-form"
                            class="px-10 py-2 bg-gradient-to-r from-indigo-600 to-violet-700 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:from-indigo-700 hover:to-violet-800 transition-all shadow-lg active:scale-95 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Cutpack
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <style>
        .page-nav-btn {
            background: rgba(255, 255, 255, 0.05);
            color: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .page-nav-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .page-nav-btn.active {
            background: white;
            color: #4f46e5;
            font-weight: 900;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .footer-page-btn.active {
            background: #4f46e5;
            color: white !important;
            border-color: #4f46e5 !important;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }

        .transition-colors {
            transition: background-color 0.1s ease-in-out;
        }

        .overflow-x-auto::-webkit-scrollbar {
            height: 4px;
        }

        .overflow-x-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
    </style>

    @push('scripts')
        <script>
            // 1. Pagination Logic
            function showPage(n) {
                document.getElementById('page-1').style.display = (n === 1) ? 'grid' : 'none';
                document.getElementById('page-2').style.display = (n === 2) ? 'grid' : 'none';

                // Sync buttons
                document.querySelectorAll('.page-nav-btn, .footer-page-btn').forEach(b => b.classList.remove('active'));
                document.getElementById(`btn-page-${n}`).classList.add('active');
                document.getElementById(`footer-btn-${n}`).classList.add('active');

                // Scroll to top of form area
                document.querySelector('.flex-1.overflow-y-auto').scrollTo({ top: 0, behavior: 'smooth' });
            }

            // 2. Calculation Logic (Logic remains unchanged)
            function calcTotal(p, type) {
                const inputs = document.querySelectorAll(`.calc-${type}-pack-${p}`);
                let sum = 0;
                inputs.forEach(inp => {
                    const val = parseInt(inp.value);
                    if (!isNaN(val)) sum += val;
                });
                const targetEl = document.getElementById(`total-${type}-pack-${p}`);
                if (targetEl) targetEl.value = sum > 0 ? sum : '';
                updateGrandTotal();
            }

            function updateGrandTotal() {
                let g1 = 0, g2 = 0, gc = 0;
                for (let i = 1; i <= 100; i++) {
                    g1 += parseInt(document.getElementById(`total-seri1-pack-${i}`)?.value || 0);
                    g2 += parseInt(document.getElementById(`total-seri2-pack-${i}`)?.value || 0);
                    gc += parseInt(document.getElementById(`total-campuran-pack-${i}`)?.value || 0);
                }
                document.getElementById('grand-total-seri1').textContent = g1.toLocaleString('id-ID');
                document.getElementById('grand-total-seri2').textContent = g2.toLocaleString('id-ID');
                document.getElementById('grand-total-campuran').textContent = gc.toLocaleString('id-ID');
            }

            // 3. Serial Masking
            function maskSeri(input) {
                let val = input.value.toUpperCase().replace(/[^A-Z0-9-]/g, '');
                if (val.length > 2 && val[2] !== '-') val = val.slice(0, 2) + '-' + val.slice(2);
                input.value = val;
            }

            // 4. JSON Submission (Logic remains unchanged)
            document.getElementById('cutpack-form')?.addEventListener('submit', function (e) {
                const form = e.target;
                const btn = document.querySelector('button[type="submit"][form="cutpack-form"]');

                if (btn) {
                    btn.style.pointerEvents = 'none';
                    btn.style.opacity = '0.7';
                    btn.innerHTML = `<svg class="w-4 h-4 animate-spin mr-2" fill="none" viewBox="0 0 24 24" style="display:inline-block;"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Saving...`;
                }

                const packsData = {};
                let hasAnyData = false;

                for (let p = 1; p <= 100; p++) {
                    const packInputs = form.querySelectorAll(`input[name^="packs[${p}]["]:not([type="hidden"])`);
                    let hasRealData = false;
                    packInputs.forEach(inp => { if (inp.value && inp.value.trim() !== '' && inp.value !== '0') hasRealData = true; });

                    if (hasRealData) {
                        hasAnyData = true;
                        const packObj = {
                            nomor_pack: p,
                            seri_pengganti: form.querySelector(`input[name="packs[${p}][seri_pengganti]"]`)?.value || null,
                            total_rusak_seri_1: form.querySelector(`input[name="packs[${p}][total_rusak_seri_1]"]`)?.value || null,
                            total_rusak_seri_2: form.querySelector(`input[name="packs[${p}][total_rusak_seri_2]"]`)?.value || null,
                            total_rusak_campuran: form.querySelector(`input[name="packs[${p}][total_rusak_campuran]"]`)?.value || null,
                            slots: {}
                        };
                        for (let s = 1; s <= 4; s++) {
                            packObj.slots[s] = {
                                slot: s,
                                rusak_seri_1: form.querySelector(`input[name="packs[${p}][slots][${s}][rusak_seri_1]"]`)?.value || null,
                                rusak_seri_2: form.querySelector(`input[name="packs[${p}][slots][${s}][rusak_seri_2]"]`)?.value || null,
                                rusak_campuran: form.querySelector(`input[name="packs[${p}][slots][${s}][rusak_campuran]"]`)?.value || null,
                                nomor_pack_pengganti: form.querySelector(`input[name="packs[${p}][slots][${s}][nomor_pack_pengganti]"]`)?.value || null,
                                nomor_bilyet_pengganti: form.querySelector(`input[name="packs[${p}][slots][${s}][nomor_bilyet_pengganti]"]`)?.value || null
                            };
                        }
                        packsData[p] = packObj;
                    }
                }

                if (hasAnyData) {
                    document.getElementById('packs-json-input').value = JSON.stringify(packsData);
                }

                form.querySelectorAll('input[name^="packs["]').forEach(inp => { inp.disabled = true; });
                setTimeout(() => {
                    form.querySelectorAll('input:disabled').forEach(inp => { if (!inp.classList.contains('pointer-events-none')) inp.disabled = false; });
                    if (btn) { btn.style.pointerEvents = 'auto'; btn.style.opacity = '1'; btn.innerHTML = 'Simpan'; }
                }, 3000);
            });

            // 5. Initial Calculations
            window.addEventListener('DOMContentLoaded', updateGrandTotal);

            // 6. SweetAlert Integration
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    timer: 2000,
                    showConfirmButton: false,
                    background: document.documentElement.classList.contains('dark-mode') ? '#1e293b' : '#fff',
                    color: document.documentElement.classList.contains('dark-mode') ? '#f8fafc' : '#111827'
                });
            @endif
        </script>
    @endpush
</x-app-layout>