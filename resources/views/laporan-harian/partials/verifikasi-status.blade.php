@php
    $sudahDiverifikasi = !is_null($verifikasi);
    $hariIndonesia = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    $bulanIndonesia = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
@endphp

@if($sudahDiverifikasi && in_array(auth()->user()->role, ['tasil', 'admin']))
    <div class="bg-emerald-50 border border-emerald-200 shadow-lg sm:rounded-2xl mb-8 p-1">
        <div class="bg-emerald-50/80 rounded-[1.25rem] p-5 flex flex-col md:flex-row items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-emerald-500 rounded-xl shadow-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-black text-emerald-800 uppercase tracking-wide">Laporan Terverifikasi</h3>
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
@endif
