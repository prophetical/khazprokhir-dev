@php
    $colorMap = [
        'S' => 'bg-lime-500 border-lime-600 text-white',
        'T' => 'bg-gray-400 border-gray-500 text-white',
        'U' => 'bg-amber-400 border-amber-500 text-white',
        'V' => 'bg-purple-500 border-purple-600 text-white',
        'W' => 'bg-green-500 border-green-600 text-white',
        'X' => 'bg-blue-500 border-blue-600 text-white',
        'Y' => 'bg-red-500 border-red-600 text-white',
    ];

    $hariIndonesia = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    $bulanIndonesia = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    $startDateCarbon = \Carbon\Carbon::parse($start_date);
    $endDateCarbon = \Carbon\Carbon::parse($end_date);

    $startDateRange = $hariIndonesia[$startDateCarbon->dayOfWeek] . ', ' . $startDateCarbon->day . ' ' . $bulanIndonesia[$startDateCarbon->month - 1] . ' ' . $startDateCarbon->year;
    $endDateRange = $hariIndonesia[$endDateCarbon->dayOfWeek] . ', ' . $endDateCarbon->day . ' ' . $bulanIndonesia[$endDateCarbon->month - 1] . ' ' . $endDateCarbon->year;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Rekonsiliasi Data') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ 
        search: '', 
    }">
        <div class="w-full mx-auto sm:px-6 lg:px-8 overflow-hidden">
            <!-- Baris Filter & Aksi -->
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl mb-8 border border-gray-100 p-1">
                <div class="bg-gray-50/50 rounded-[1.25rem] p-6">
                    <div class="flex flex-col md:flex-row justify-between items-end gap-8">
                        <form action="{{ route('laporan-harian.rekonsiliasi') }}" method="GET"
                            class="w-full xl:w-auto flex-grow">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                                <!-- Start Date -->
                                <div class="relative">
                                    <label for="start_date"
                                        class="flex items-center gap-2 text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">
                                        Tanggal Mulai
                                    </label>
                                    <input type="date" name="start_date" id="start_date" value="{{ $start_date }}"
                                        class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 py-2 text-center font-black text-gray-700 bg-white">
                                </div>

                                <!-- End Date -->
                                <div class="relative">
                                    <label for="end_date"
                                        class="flex items-center gap-2 text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">
                                        Tanggal Akhir
                                    </label>
                                    <input type="date" name="end_date" id="end_date" value="{{ $end_date }}"
                                        class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 py-2 text-center font-black text-gray-700 bg-white">
                                </div>

                                <!-- Tahun Anggaran -->
                                <div class="relative">
                                    <label for="tahun_anggaran"
                                        class="flex items-center gap-2 text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">
                                        Tahun Anggaran
                                    </label>
                                    <div class="flex gap-3">
                                        <select name="tahun_anggaran" id="tahun_anggaran"
                                            class="flex-grow rounded-xl border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 py-2 text-center font-black text-gray-700 bg-white">
                                            @foreach($tahunAnggaranOptions as $year)
                                                <option value="{{ $year }}" {{ $tahun_anggaran == $year ? 'selected' : '' }}>
                                                    {{ $year }}
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
                        <div class="flex flex-col gap-2 w-full md:w-auto">
                            <span
                                class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0 sm:mb-2 px-1 hidden 2xl:block">&nbsp;</span>
                            <div
                                class="flex flex-wrap gap-3 shrink-0 items-center justify-center sm:justify-end 2xl:justify-start">
                                <!-- Excel -->
                                <a href="{{ route('laporan-harian.rekonsiliasi-export', request()->all()) }}"
                                    class="btn-report btn-excel">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Excel
                                </a>

                                <!-- PDF -->
                                <a href="{{ route('laporan-harian.rekonsiliasi-print', request()->all()) }}"
                                    target="_blank" class="btn-report btn-pdf">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    PDF
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
                        <div class="bg-emerald-50 border border-emerald-200 shadow-lg sm:rounded-2xl mb-8 p-1">
                            <div class="bg-emerald-50/80 rounded-[1.25rem] p-5 flex flex-col md:flex-row items-center gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="p-2.5 bg-emerald-500 rounded-xl shadow-sm">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-black text-emerald-800 uppercase tracking-wide">Data Rekonsiliasi Terverifikasi</h3>
                                        <p class="text-xs text-emerald-600 mt-0.5">
                                            Diverifikasi oleh <span class="font-black">{{ $verifikasi->verifier->name ?? '-' }}</span>
                                            ({{ $verifikasi->verifier->username ?? '-' }} / NP: {{ $verifikasi->verifier->np ?? '-' }})
                                            — Role: <span class="font-black uppercase">{{ $verifikasi->verifier->role ?? '-' }}</span>
                                        </p>
                                        @php
                                            $vAt = \Carbon\Carbon::parse($verifikasi->verified_at);
                                            $verifiedDateStr = $hariIndonesia[$vAt->dayOfWeek] . ', ' . $vAt->day . ' ' . $bulanIndonesia[$vAt->month - 1] . ' ' . $vAt->year;
                                        @endphp
                                        <p class="text-xs text-emerald-600 mt-0.5">
                                            Pada {{ $verifiedDateStr }}, pukul {{ $vAt->format('H:i') }} WIB
                                        </p>
                                        @if($verifikasi->catatan)
                                            <p class="text-xs text-emerald-700 mt-1 italic">Catatan: {{ $verifikasi->catatan }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(auth()->user()->role === 'admin')
                            <form id="deleteVerifikasiForm" method="POST" action="{{ route('laporan-harian.verifikasi-destroy') }}" class="mt-3">
                                @csrf
                                <input type="hidden" name="jenis_laporan" value="rekonsiliasi">
                                <input type="hidden" name="tanggal_mulai" value="{{ $start_date }}">
                                <input type="hidden" name="tanggal_akhir" value="{{ $end_date }}">
                                <input type="hidden" name="tahun_anggaran" value="{{ $tahun_anggaran }}">
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
                                <input type="hidden" name="start_date" value="{{ $start_date }}">
                                <input type="hidden" name="end_date" value="{{ $end_date }}">
                                <input type="hidden" name="tahun_anggaran" value="{{ $tahun_anggaran }}">
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
            </div>

            <!-- Table Sections -->
            <div class="space-y-8 mt-8">
                <!-- Data Rekonsiliasi Table -->
                <div
                    class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl border border-gray-100 transition-all duration-300">
                    <div
                        class="border-b border-gray-100 from-gray-50 to-white px-6 py-5 flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-gray-800 tracking-tight">Data Rekonsiliasi HCS</h3>
                                <p class="text-[11px] font-medium text-gray-500 mt-0.5">{{ $startDateRange }} s/d {{ $endDateRange }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 bg-gray-50/50 rounded-[1.25rem]">
                        <div class="overflow-x-auto rounded-xl border border-gray-100 bg-white shadow-sm">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100/80 dark:bg-slate-800/80">
                                    <tr
                                        class="text-[10px] font-black uppercase text-gray-500 tracking-widest divide-x divide-gray-200">
                                        <th rowspan="2" class="px-6 py-4 text-center align-middle">Pecahan</th>
                                        <th rowspan="2" class="px-6 py-4 text-center align-middle">Penerimaan HCS</th>
                                        <th rowspan="2" class="px-6 py-4 text-center align-middle">Pengemasan HCS</th>
                                        <th colspan="2"
                                            class="px-6 py-2 text-center align-middle border-b border-gray-200 text-indigo-600 dark:text-indigo-400">
                                            Range No. Dus Pengemasan</th>
                                        <th rowspan="2" class="px-6 py-4 text-center align-middle">Penyerahan HCS</th>
                                        <th colspan="2"
                                            class="px-6 py-2 text-center align-middle border-b border-gray-200 text-indigo-600 dark:text-indigo-400">
                                            Range No. Dus Penyerahan</th>
                                        <th rowspan="2" class="px-6 py-4 text-center align-middle">Akumulasi Target
                                            Pengemasan</th>
                                        <th rowspan="2" class="px-6 py-4 text-center align-middle">Akumulasi Target
                                            Penyerahan</th>
                                    </tr>
                                    <tr
                                        class="text-[9px] font-bold text-gray-400 dark:text-gray-500 divide-x divide-gray-200 dark:divide-slate-700 bg-indigo-50/50 dark:bg-indigo-900/30">
                                        <th class="px-6 py-2 text-center align-middle">No Awal</th>
                                        <th class="px-6 py-2 text-center align-middle">No Akhir</th>
                                        <th class="px-6 py-2 text-center align-middle">No Awal</th>
                                        <th class="px-6 py-2 text-center align-middle">No Akhir</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($rekonsiliasiData as $row)
                                        <tr
                                            class="hover:bg-indigo-50/40 transition duration-150 group divide-x divide-gray-100">
                                            <td
                                                class="px-6 py-4 text-center bg-white group-hover:bg-indigo-50/40 z-10 font-black">
                                                <div class="flex justify-center">
                                                    <span
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-sm font-black border shadow-sm {{ $colorMap[$row['pecahan']] ?? 'bg-gray-900 text-white' }}">
                                                        {{ $row['pecahan'] }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-center text-xs font-bold text-gray-600">
                                                {{ $row['penerimaan_hcs'] > 0 ? number_format($row['penerimaan_hcs'], 0, ',', '.') : '-' }}
                                            </td>
                                            <td class="px-6 py-4 text-center text-xs font-bold text-gray-600">
                                                {{ $row['pengemasan_hcs'] > 0 ? number_format($row['pengemasan_hcs'], 0, ',', '.') : '-' }}
                                            </td>
                                            <td class="px-6 py-4 text-center text-xs font-bold text-gray-600">
                                                {{ $row['min_dus_kemas'] }}
                                            </td>
                                            <td class="px-6 py-4 text-center text-xs font-bold text-gray-600">
                                                {{ $row['max_dus_kemas'] }}
                                            </td>
                                            <td class="px-6 py-4 text-center text-xs font-bold text-gray-600">
                                                {{ $row['penyerahan_hcs'] > 0 ? number_format($row['penyerahan_hcs'], 0, ',', '.') : '-' }}
                                            </td>
                                            <td class="px-6 py-4 text-center text-xs font-bold text-gray-600">
                                                {{ $row['min_dus_serah'] }}
                                            </td>
                                            <td class="px-6 py-4 text-center text-xs font-bold text-gray-600">
                                                {{ $row['max_dus_serah'] }}
                                            </td>
                                            <td class="px-6 py-4 text-center text-xs font-bold text-gray-600">
                                                {{ $row['target_pengemasan'] > 0 ? number_format($row['target_pengemasan'], 0, ',', '.') : '-' }}
                                            </td>
                                            <td class="px-6 py-4 text-center text-xs font-bold text-gray-600">
                                                {{ $row['target_penyerahan'] > 0 ? number_format($row['target_penyerahan'], 0, ',', '.') : '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-900 text-white text-xs font-black uppercase">
                                    <tr class="divide-x divide-gray-700">
                                        <td class="px-6 py-5 text-center uppercase tracking-widest">TOTAL</td>
                                        <td class="px-6 py-5 text-center text-amber-300">
                                            {{ $totals['penerimaan_hcs'] > 0 ? number_format($totals['penerimaan_hcs'], 0, ',', '.') : '-' }}
                                        </td>
                                        <td class="px-6 py-5 text-center text-emerald-300">
                                            {{ $totals['pengemasan_hcs'] > 0 ? number_format($totals['pengemasan_hcs'], 0, ',', '.') : '-' }}
                                        </td>
                                        <td class="px-6 py-5 text-center text-gray-400 font-bold">-</td>
                                        <td class="px-6 py-5 text-center text-gray-400 font-bold">-</td>
                                        <td class="px-6 py-5 text-center text-pink-300">
                                            {{ $totals['penyerahan_hcs'] > 0 ? number_format($totals['penyerahan_hcs'], 0, ',', '.') : '-' }}
                                        </td>
                                        <td class="px-6 py-5 text-center text-gray-400 font-bold">-</td>
                                        <td class="px-6 py-5 text-center text-gray-400 font-bold">-</td>
                                        <td class="px-6 py-5 text-center text-blue-300">
                                            {{ $totals['target_pengemasan'] > 0 ? number_format($totals['target_pengemasan'], 0, ',', '.') : '-' }}
                                        </td>
                                        <td class="px-6 py-5 text-center text-indigo-300">
                                            {{ $totals['target_penyerahan'] > 0 ? number_format($totals['target_penyerahan'], 0, ',', '.') : '-' }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
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
</x-app-layout>