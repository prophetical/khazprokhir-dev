<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengemasan HCS') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-10xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-md font-bold text-indigo-800">Daftar Pack Siap Kemas</h2>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow-sm" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(!empty($missingGaps))
                        <div class="mb-6 space-y-3">
                            @foreach($missingGaps as $gap)
                                <div class="bg-amber-50 border-l-4 border-amber-400 p-4 rounded-r-md shadow-sm">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-bold text-amber-800">Peringatan: Terdapat nomor dus yang hilang</h3>
                                            <div class="mt-1 text-sm text-amber-700">
                                                Pada Pecahan <span class="font-bold">{{ $gap['pecahan'] }}</span>, 
                                                Tahun Anggaran <span class="font-bold">{{ $gap['tahun_anggaran'] }}</span>, 
                                                Tahun Emisi <span class="font-bold">{{ $gap['tahun_emisi'] }}</span>. 
                                                Range nomor dus yang tidak ditemukan: <span class="font-bold font-mono">{{ $gap['ranges'] }}</span>.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- DAFTAR PACK SIAP KEMAS -->
                    <div class="mb-10">
                        <div class="overflow-hidden bg-white rounded-xl shadow border border-gray-100">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-indigo-50/50">
                                    <tr>
                                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-widest">Tahun<br>Anggaran/Emisi</th>
                                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-widest">Pecahan</th>
                                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-widest">Batch & Seri</th>
                                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-widest">Rentang Pack</th>
                                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-widest">Jumlah Pack</th>
                                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-widest">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @forelse($readyGroups as $group)
                                        <tr class="hover:bg-indigo-50/30 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <div class="text-sm font-bold text-gray-900">{{ $group['tahun_anggaran'] }}</div>
                                                <div class="text-xs text-gray-500 text-center">{{ $group['emisi'] }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-black bg-gray-100 text-gray-800 border border-gray-200 shadow-sm">
                                                    {{ $group['pecahan'] }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <div class="text-sm font-bold text-gray-900">{{ $group['batch'] }}</div>
                                                <div class="text-xs font-mono text-gray-500">{{ $group['seri'] }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <span class="text-sm font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded">
                                                    {{ $group['pack_awal'] }} - {{ $group['pack_akhir'] }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center font-black text-gray-900">
                                                {{ $group['jumlah_pack'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center space-x-2">
                                                @php
                                                    $maxValidPackAkhir = $group['pack_awal'] + (intdiv($group['jumlah_pack'], 4) * 4) - 1;
                                                @endphp
                                                <a href="{{ route('pengemasan.create', [
                                                    'tahun_anggaran' => $group['tahun_anggaran'],
                                                    'tahun_emisi' => $group['emisi'],
                                                    'pecahan' => $group['pecahan'],
                                                    'batch' => $group['batch'],
                                                    'seri' => $group['seri'],
                                                    'pack_awal' => $group['pack_awal'],
                                                    'pack_akhir' => $maxValidPackAkhir,
                                                    'max_pack_akhir' => $maxValidPackAkhir
                                                ]) }}" 
                                                   class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-sm">
                                                    Kemas Semua
                                                </a>
                                                <a href="{{ route('pengemasan.create', [
                                                    'tahun_anggaran' => $group['tahun_anggaran'],
                                                    'tahun_emisi' => $group['emisi'],
                                                    'pecahan' => $group['pecahan'],
                                                    'batch' => $group['batch'],
                                                    'seri' => $group['seri'],
                                                    'pack_awal' => $group['pack_awal'],
                                                    'pack_akhir' => '',
                                                    'max_pack_akhir' => $maxValidPackAkhir
                                                ]) }}"
                                                   class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-bold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 active:bg-gray-100 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-sm">
                                                    Kemas Sebagian
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500 italic">
                                                Tidak ada pack yang berstatus selesai sortir dan siap kemas.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
