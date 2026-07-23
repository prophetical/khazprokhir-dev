@php
    $isTasilOrAdmin = in_array(auth()->user()->role, ['tasil', 'admin']);
@endphp

@if($isTasilOrAdmin)
    @if($jenis_laporan === 'harian')
        <div class="bg-amber-50 border border-amber-200 shadow-lg sm:rounded-2xl mb-8 p-1" x-data="{ showCatatan: false }">
            <div class="bg-amber-50/80 rounded-[1.25rem] p-5">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-amber-400 rounded-xl shadow-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-amber-800 uppercase tracking-wide">Belum Diverifikasi</h3>
                            <p class="text-xs text-amber-600 mt-0.5">Laporan harian ini belum diverifikasi. Klik tombol untuk memverifikasi.</p>
                        </div>
                    </div>
                    <button type="button" @click="showCatatan = !showCatatan"
                        class="shrink-0 inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black uppercase tracking-widest rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Verifikasi Laporan Ini
                    </button>
                </div>
                <form method="POST" action="{{ route('laporan-harian.verifikasi') }}"
                    x-show="showCatatan" x-collapse class="mt-4 pt-4 border-t border-amber-200">
                    @csrf
                    <input type="hidden" name="tanggal_laporan" value="{{ $tanggal_laporan ?? '' }}">
                    <input type="hidden" name="tahun_anggaran" value="{{ $tahun_anggaran ?? '' }}">
                    <input type="hidden" name="tahun_emisi" value="{{ $tahun_emisi ?? '' }}">
                    <label class="block text-[10px] font-black text-amber-600 uppercase tracking-widest mb-2">Catatan (Opsional)</label>
                    <textarea name="catatan" rows="2" class="w-full rounded-xl border-amber-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm" placeholder="Tambahkan catatan verifikasi jika diperlukan..."></textarea>
                    <div class="flex justify-end mt-3">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black uppercase tracking-widest rounded-xl shadow-lg transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                            Konfirmasi & Download Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @else
        <div class="bg-amber-50 border border-amber-200 shadow-lg sm:rounded-2xl mb-8 p-1" x-data="{ showCatatan: false }">
            <div class="bg-amber-50/80 rounded-[1.25rem] p-5">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-amber-400 rounded-xl shadow-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-amber-800 uppercase tracking-wide">Belum Diverifikasi</h3>
                            <p class="text-xs text-amber-600 mt-0.5">Data rekonsiliasi periode ini belum diverifikasi. Klik tombol untuk memverifikasi.</p>
                        </div>
                    </div>
                    <button type="button" @click="showCatatan = !showCatatan"
                        class="shrink-0 inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black uppercase tracking-widest rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Verifikasi Rekonsiliasi Ini
                    </button>
                </div>
                <form method="POST" action="{{ route('laporan-harian.rekonsiliasi-verifikasi') }}"
                    x-show="showCatatan" x-collapse class="mt-4 pt-4 border-t border-amber-200">
                    @csrf
                    <input type="hidden" name="start_date" value="{{ $start_date ?? '' }}">
                    <input type="hidden" name="end_date" value="{{ $end_date ?? '' }}">
                    <input type="hidden" name="tahun_anggaran" value="{{ $tahun_anggaran ?? '' }}">
                    <label class="block text-[10px] font-black text-amber-600 uppercase tracking-widest mb-2">Catatan (Opsional)</label>
                    <textarea name="catatan" rows="2" class="w-full rounded-xl border-amber-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm" placeholder="Tambahkan catatan verifikasi jika diperlukan..."></textarea>
                    <div class="flex justify-end mt-3">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black uppercase tracking-widest rounded-xl shadow-lg transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                            Konfirmasi & Download Rekonsiliasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endif
