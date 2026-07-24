@php
    // colorMap dipindahkan ke partials.report-tables
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Laporan Harian Operasional') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ 
        search: '', 
        showHcs: true, 
        showMonitoring: true, 
        showHcts: true, 
        showAnnual: true, 
        showMonthly: true 
    }">
        <div class="w-full mx-auto sm:px-6 lg:px-8 overflow-hidden">
            <!-- Baris Filter & Aksi -->
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl mb-8 border border-gray-100 p-1">
                <div class="bg-gray-50/50 rounded-[1.25rem] p-6">
                    <div class="flex flex-col 2xl:flex-row justify-between items-end gap-8">
                        <form action="{{ route('laporan-harian.index') }}" method="GET"
                            class="w-full 2xl:w-auto flex-grow">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                                <!-- Tanggal Laporan -->
                                <div class="relative">
                                    <label for="tanggal_laporan"
                                        class="flex items-center gap-2 text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">
                                        Tanggal Laporan
                                    </label>
                                    <input type="date" name="tanggal_laporan" id="tanggal_laporan"
                                        value="{{ $tanggalLaporan }}"
                                        class="w-full rounded-xl border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 py-3 text-center font-black text-gray-700 bg-white">
                                </div>

                                <!-- Tahun Anggaran -->
                                <div class="relative">
                                    <label for="tahun_anggaran"
                                        class="flex items-center gap-2 text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">
                                        Tahun Anggaran
                                    </label>
                                    <select name="tahun_anggaran" id="tahun_anggaran"
                                        class="w-full rounded-xl border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 py-3 text-center font-black text-gray-700 bg-white">
                                        @foreach($tahunAnggaranOptions as $year)
                                            <option value="{{ $year }}" {{ $tahunAnggaran == $year ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Tahun Emisi -->
                                <div class="relative">
                                    <label for="tahun_emisi"
                                        class="flex items-center gap-2 text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">
                                        Tahun Emisi
                                    </label>
                                    <div class="flex gap-3">
                                        <select name="tahun_emisi" id="tahun_emisi"
                                            class="flex-grow rounded-xl border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 py-3 text-center font-black text-gray-700 bg-white">
                                            @foreach($tahunEmisiOptions as $emisi)
                                                <option value="{{ $emisi }}" {{ $tahunEmisi == $emisi ? 'selected' : '' }}>
                                                    {{ $emisi }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit"
                                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-black px-6 rounded-xl transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- Action Buttons -->
                        <div class="flex flex-col gap-2 w-full 2xl:w-auto">
                            <span
                                class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0 sm:mb-2 px-1 hidden 2xl:block">&nbsp;</span>
                            <div
                                class="flex flex-wrap gap-3 shrink-0 items-center justify-center sm:justify-end 2xl:justify-start">
                                 <!-- Excel -->
                                <a href="{{ route('laporan-harian.export', request()->all()) }}"
                                    class="btn-report btn-excel">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Excel
                                </a>

                                <!-- PDF -->
                                <a :href="'{{ route('laporan-harian.print') }}?' + new URLSearchParams({
                                    tanggal_laporan: '{{ $tanggalLaporan }}',
                                    tahun_anggaran: '{{ $tahunAnggaran }}',
                                    tahun_emisi: '{{ $tahunEmisi }}',
                                    autoprint: 1,
                                    showHcs: showHcs,
                                    showMonitoring: showMonitoring,
                                    showHcts: showHcts,
                                    showAnnual: showAnnual,
                                    showMonthly: showMonthly
                                }).toString()" target="_blank"
                                    class="btn-report btn-pdf">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    PDF
                                </a>

                                <!-- Print -->
                                <a :href="'{{ route('laporan-harian.print') }}?' + new URLSearchParams({
                                    tanggal_laporan: '{{ $tanggalLaporan }}',
                                    tahun_anggaran: '{{ $tahunAnggaran }}',
                                    tahun_emisi: '{{ $tahunEmisi }}',
                                    showHcs: showHcs,
                                    showMonitoring: showMonitoring,
                                    showHcts: showHcts,
                                    showAnnual: showAnnual,
                                    showMonthly: showMonthly
                                }).toString()" target="_blank"
                                    class="btn-report btn-print">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 00-2 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                    </svg>
                                    Print
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="verifikasiContainer">
                {{-- Status Verifikasi & Tombol Verifikasi --}}
                @php
                    $sudahDiverifikasi = !is_null($verifikasi);
                @endphp

                @if($sudahDiverifikasi)
                    @if(in_array(auth()->user()->role, ['tasil', 'admin']))
                        @include('laporan-harian.partials.verifikasi-status')
                        @if(auth()->user()->role === 'admin')
                            <form id="deleteVerifikasiForm" method="POST" action="{{ route('laporan-harian.verifikasi-destroy') }}" class="mt-3">
                                @csrf
                                <input type="hidden" name="jenis_laporan" value="harian">
                                <input type="hidden" name="tanggal_mulai" value="{{ $tanggalLaporan }}">
                                <input type="hidden" name="tanggal_akhir" value="{{ $tanggalLaporan }}">
                                <input type="hidden" name="tahun_anggaran" value="{{ $tahunAnggaran }}">
                                <button type="button" id="btnDeleteVerifikasi"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-[10px] font-black uppercase tracking-widest rounded-xl shadow-lg transition-all duration-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus Verifikasi
                                </button>
                            </form>
                        @endif
                    @endif
                @else
                    @if(in_array(auth()->user()->role, ['tasil', 'admin']))
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
                                <form id="verifikasiForm" method="POST" action="{{ route('laporan-harian.verifikasi') }}"
                                    x-show="showCatatan" x-collapse class="mt-4 pt-4 border-t border-amber-200">
                                    @csrf
                                    <input type="hidden" name="tanggal_laporan" value="{{ $tanggalLaporan }}">
                                    <input type="hidden" name="tahun_anggaran" value="{{ $tahunAnggaran }}">
                                    <input type="hidden" name="tahun_emisi" value="{{ $tahunEmisi }}">
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

                    @endif
                @endif
            </div>

            <script>
                (function() {
                    const form = document.getElementById('verifikasiForm');
                    if (!form) return;
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const formData = new FormData(form);
                        const btn = form.querySelector('button[type="submit"]');
                        const originalText = btn.innerHTML;
                        btn.disabled = true;
                        btn.innerHTML = 'Memproses...';

                        const tanggalLaporan = formData.get('tanggal_laporan');
                        const tahunAnggaran = formData.get('tahun_anggaran');
                        const tahunEmisi = formData.get('tahun_emisi') || '';

                        const baseUrl = new URL('/laporan-harian/export-json', window.location.origin);
                        baseUrl.searchParams.set('tanggal_laporan', tanggalLaporan);
                        baseUrl.searchParams.set('tahun_anggaran', tahunAnggaran);
                        baseUrl.searchParams.set('tahun_emisi', tahunEmisi);

                        fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                        })
                        .then(async response => {
                            const contentType = response.headers.get('content-type') || '';
                            if (!response.ok || !contentType.includes('application/json')) {
                                const text = await response.text();
                                throw new Error(text || 'HTTP ' + response.status);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data && data.success) {
                                if (data.verified_html) {
                                    document.getElementById('verifikasiContainer').outerHTML = data.verified_html;
                                }
                                if (data.print_url) {
                                    window.open(data.print_url, '_blank');
                                }
                                window.open(baseUrl.toString(), '_blank');
                            } else {
                                alert('Gagal memverifikasi laporan.');
                                btn.disabled = false;
                                btn.innerHTML = originalText;
                            }
                        })
                        .catch(error => {
                            console.error('Verifikasi error:', error);
                            alert('Terjadi kesalahan: ' + (error.message || 'Silakan coba lagi.'));
                            btn.disabled = false;
                            btn.innerHTML = originalText;
                        });
                    });
                })();

                (function() {
                    const btnDelete = document.getElementById('btnDeleteVerifikasi');
                    if (!btnDelete) return;
                    const deleteForm = document.getElementById('deleteVerifikasiForm');

                    btnDelete.addEventListener('click', function() {
                        Swal.fire({
                            title: 'Hapus Verifikasi?',
                            text: 'Tindakan ini akan menghapus status verifikasi laporan.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#dc2626',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: 'Ya, Hapus',
                            cancelButtonText: 'Batal',
                        }).then((result) => {
                            if (!result.isConfirmed) return;

                            const formData = new FormData(deleteForm);
                            const originalText = btnDelete.innerHTML;
                            btnDelete.disabled = true;
                            btnDelete.innerHTML = 'Menghapus...';

                            fetch(deleteForm.action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                },
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    document.getElementById('verifikasiContainer').outerHTML = data.replace_html;
                                } else {
                                    alert('Gagal menghapus verifikasi.');
                                    btnDelete.disabled = false;
                                    btnDelete.innerHTML = originalText;
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Terjadi kesalahan.');
                                btnDelete.disabled = false;
                                btnDelete.innerHTML = originalText;
                            });
                        });
                    });
                })();
            </script>

            <!-- Table Sections -->
            <div class="space-y-8 mt-8">
                @include('laporan-harian.partials.report-tables')
            </div>

        </div>
    </div>

</x-app-layout>