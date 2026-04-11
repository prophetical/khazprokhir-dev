<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Pengemasan HCS') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">
                
                <!-- Filter Section -->
                <div class="bg-white dark:bg-slate-900 overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-indigo-500">
                    <div class="p-6">
                        <form method="GET" action="{{ route('pengemasan.report.index') }}">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Tanggal Laporan</label>
                                    <input type="date" name="tanggal" value="{{ $tanggal }}"
                                        class="block w-full border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-gray-300 rounded-lg shadow-sm text-sm py-3 px-3 focus:border-indigo-500 focus:ring-indigo-500 transition-all">
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Gilir</label>
                                    <select name="gilir"
                                        class="block w-full border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-gray-300 rounded-lg shadow-sm text-sm py-3 px-3 focus:border-indigo-500 focus:ring-indigo-500 transition-all font-bold">
                                        <option value="">Semua Gilir</option>
                                        <option value="1" {{ $gilir == '1' ? 'selected' : '' }}>Gilir 1</option>
                                        <option value="2" {{ $gilir == '2' ? 'selected' : '' }}>Gilir 2</option>
                                        <option value="3" {{ $gilir == '3' ? 'selected' : '' }}>Gilir 3</option>
                                    </select>
                                </div>

                                <div>
                                    <button type="submit"
                                        class="w-full inline-flex justify-center items-center px-4 py-3 bg-indigo-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 shadow-md active:scale-95 transition-all">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                        Tampilkan Laporan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Report Section -->
                <div class="bg-white dark:bg-slate-900 overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-emerald-500">
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                            <div>
                                <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tight">Ringkasan Pengemasan</h3>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">
                                    {{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                                    @if($gilir) | Gilir {{ $gilir }} @endif
                                </p>
                            </div>
                            
                            <div class="flex flex-wrap gap-2">
                                <button onclick="printReport()"
                                    class="inline-flex items-center px-4 py-2 bg-rose-50 dark:bg-rose-900/10 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-800/50 rounded-lg text-xs font-bold uppercase tracking-widest hover:bg-rose-100 dark:hover:bg-rose-900/20 transition-all shadow-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 00-2 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                    </svg>
                                    Cetak PDF (Landscape)
                                </button>
                            </div>
                        </div>

                        <!-- Manual Inputs for Print -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 p-4 bg-gray-50 dark:bg-slate-800/50 rounded-xl border border-gray-100 dark:border-slate-800">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Petugas Pembuat DIK</label>
                                <input type="text" id="petugas_dik" placeholder="Masukkan nama petugas..."
                                    class="block w-full border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-gray-300 rounded-lg shadow-sm text-sm py-3 px-3 focus:border-indigo-500 focus:ring-indigo-500 transition-all font-bold">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Penanggung Jawab</label>
                                <input type="text" id="penanggung_jawab" placeholder="Masukkan nama penanggung jawab..."
                                    class="block w-full border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-gray-300 rounded-lg shadow-sm text-sm py-3 px-3 focus:border-indigo-500 focus:ring-indigo-500 transition-all font-bold">
                            </div>
                        </div>

                        <div class="overflow-x-auto rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-800">
                                <thead class="bg-gray-50 dark:bg-slate-800/50">
                                    <tr class="text-[10px] font-black uppercase text-gray-500 tracking-wider">
                                        <th class="px-6 py-4 text-left">Pecahan</th>
                                        <th class="px-6 py-4 text-center">TA</th>
                                        <th class="px-6 py-4 text-center">TE</th>
                                        <th class="px-6 py-4 text-center" colspan="2">Range Nomor Dus</th>
                                        <th class="px-6 py-4 text-right">Jumlah Bilyet</th>
                                        <th class="px-6 py-4 text-right">Jumlah Dus</th>
                                    </tr>
                                    <tr class="text-[9px] font-bold uppercase text-gray-400 tracking-tighter border-t border-gray-100 dark:border-slate-800">
                                        <th colspan="3"></th>
                                        <th class="px-6 py-2 text-center border-r border-gray-100 dark:border-slate-800">Awal</th>
                                        <th class="px-6 py-2 text-center">Akhir</th>
                                        <th colspan="2"></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-slate-900 divide-y divide-gray-100 dark:divide-slate-800">
                                    @forelse($reports as $row)
                                        <tr class="hover:bg-indigo-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-black bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300">
                                                    {{ $row['pecahan'] }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-gray-600 dark:text-gray-400">
                                                {{ $row['ta'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-gray-600 dark:text-gray-400">
                                                {{ $row['te'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-black text-gray-900 dark:text-white border-r border-gray-50 dark:border-slate-800">
                                                {{ number_format($row['nomor_dus_awal'], 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-black text-gray-900 dark:text-white">
                                                {{ number_format($row['nomor_dus_akhir'], 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-black text-emerald-600 dark:text-emerald-400">
                                                {{ number_format($row['jumlah_bilyet'], 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-black text-indigo-600 dark:text-indigo-400">
                                                {{ number_format($row['jumlah_dus'], 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-6 py-12 text-center">
                                                <div class="flex flex-col items-center">
                                                    <svg class="h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    <p class="text-sm font-bold text-gray-500 uppercase tracking-widest">Tidak ada data pengemasan ditemukan</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if(count($reports) > 0)
                                    <tfoot class="bg-gray-50 dark:bg-slate-800/50">
                                        <tr class="text-xs font-black uppercase text-gray-900 dark:text-white border-t-2 border-gray-200 dark:border-slate-700">
                                            <td colspan="5" class="px-6 py-4 text-right tracking-widest">TOTAL KESELURUHAN</td>
                                            <td class="px-6 py-4 text-right text-emerald-600 dark:text-emerald-400">
                                                {{ number_format(collect($reports)->sum('jumlah_bilyet'), 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 text-right text-indigo-600 dark:text-indigo-400">
                                                {{ number_format(collect($reports)->sum('jumlah_dus'), 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function printReport() {
            const petugas = document.getElementById('petugas_dik').value;
            const pjg = document.getElementById('penanggung_jawab').value;
            
            const params = new URLSearchParams({
                tanggal: "{{ $tanggal }}",
                @if($gilir) gilir: "{{ $gilir }}", @endif
                petugas: petugas,
                penanggung_jawab: pjg
            });

            const url = "{{ route('pengemasan.report.print') }}?" + params.toString();
            window.open(url, '_blank');
        }
    </script>
</x-app-layout>
