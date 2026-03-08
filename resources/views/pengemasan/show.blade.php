<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Kardus Pengemasan HCS') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @php
                $pecahan = $pengemasan->pecahan ?? '';
                $pecCol = [
                    'S' => 'lime',
                    'T' => 'gray',
                    'U' => 'amber',
                    'V' => 'purple',
                    'W' => 'green',
                    'X' => 'blue',
                    'Y' => 'red',
                ][$pecahan] ?? 'indigo';
                
                $borderColor = "border-{$pecCol}-500";
                if ($pecahan === 'U') $borderColor = "border-{$pecCol}-600";
                $textColor = "text-{$pecCol}-500";
                if ($pecahan === 'U') $textColor = "text-{$pecCol}-600";
            @endphp
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-t-8 {{ $borderColor }}">
                <div class="p-8 text-gray-900">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h2 class="text-3xl font-extrabold text-gray-900 mb-2 flex items-center gap-3">
                                Detail Pengemasan
                                <span class="inline-flex items-center px-4 py-1.5 rounded-2xl border-2 text-xl font-black uppercase tracking-tighter {{ $borderColor }} {{ $textColor }} bg-white shadow-sm">
                                    {{ $pengemasan->batch }} / {{ $pengemasan->seri }}
                                </span>
                            </h2>
                            <p class="text-gray-500 text-sm">Hasil pembentukan {{ $pengemasan->jumlah_dus }} dus dari {{ $pengemasan->jumlah_pack }} pack.</p>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="px-4 py-2 bg-{{ $pecCol }}-50 {{ $pecahan === 'S' || $pecahan === 'U' || $pecahan === 'T' ? 'text-gray-800' : $textColor }} rounded-lg font-bold text-sm mb-2 shadow-sm">
                                {{ $pengemasan->tanggal_pengemasan->format('d F Y') }}
                            </span>
                            <span class="text-xs text-gray-400 font-medium italic">Oleh: {{ $pengemasan->user->name ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                        <div class="bg-gradient-to-br from-{{ $pecCol }}-50 to-white p-4 rounded-xl border border-{{ $pecCol }}-100 shadow-sm">
                            <h4 class="text-[10px] font-bold text-{{ $pecCol }}-400 uppercase tracking-widest mb-3">Informasi Produksi</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500 font-medium">Pecahan:</span>
                                    <span class="text-sm font-black text-gray-900 bg-white px-2 py-0.5 rounded border border-{{ $pecCol }}-100 shadow-sm">{{ $pengemasan->pecahan }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-xs text-gray-500">Batch:</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $pengemasan->batch }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-xs text-gray-500">Seri:</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $pengemasan->seri }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-{{ $pecCol }}-50 to-white p-4 rounded-xl border border-{{ $pecCol }}-100 shadow-sm">
                            <h4 class="text-[10px] font-bold text-{{ $pecCol }}-400 uppercase tracking-widest mb-3">Detail Pack</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-xs text-gray-500">Rentang Pack:</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $pengemasan->pack_awal }} - {{ $pengemasan->pack_akhir }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-xs text-gray-500">Total Pack:</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $pengemasan->jumlah_pack }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-xs text-gray-500">Tahun Anggaran/Emisi:</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $pengemasan->tahun_anggaran }} / {{ $pengemasan->tahun_emisi }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-{{ $pecCol }}-50 to-white p-4 rounded-xl border border-{{ $pecCol }}-100 shadow-sm">
                            <h4 class="text-[10px] font-bold text-{{ $pecCol }}-400 uppercase tracking-widest mb-3">Informasi Dus</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-xs text-gray-500">Total Dus:</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $pengemasan->jumlah_dus }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-xs text-gray-500">Nomor Dus:</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $pengemasan->dus_awal }} - {{ $pengemasan->dus_akhir }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        Tabel Detail Dus
                    </h3>
                    
                    <div class="overflow-x-auto border border-gray-100 rounded-2xl shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">No. Dus</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Konten Pack</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Seri Awal</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Seri Akhir</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Jumlah Bilyet</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($pengemasan->detailPengemasans as $dus)
                                    <tr class="hover:bg-indigo-50/30 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm font-extrabold text-indigo-600">{{ $dus->no_dus }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">
                                            @if($dus->pack_awal == $dus->pack_akhir)
                                                {{ $dus->pack_awal }}
                                            @else
                                                {{ $dus->pack_awal }} - {{ $dus->pack_akhir }}
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">{{ $dus->seri_awal }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">{{ $dus->seri_akhir }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold text-right">{{ number_format($dus->jumlah_bilyet, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-10 flex justify-between items-center">
                        <a href="{{ route('pengemasan.data') }}" class="group flex items-center text-sm font-bold text-gray-400 hover:text-indigo-600 transition-colors">
                            <svg class="w-5 h-5 mr-1 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7" />
                            </svg>
                            Kembali ke Daftar Riwayat
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
