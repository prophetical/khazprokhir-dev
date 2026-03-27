<x-app-layout>
@php App::setLocale('id') @endphp
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('HCS - HCTS Summary') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-10">
            
            <!-- Filters & Actions -->
            <div class="bg-white overflow-hidden shadow-sm rounded-3xl mb-8 border border-gray-100">
                <div class="p-8">
                    <form action="{{ route('hcts-receiving.summary') }}" method="GET">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Dari Tanggal</label>
                                <input name="start_date" type="date" value="{{ $startDate }}" class="block w-full border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500 text-sm py-3 transition-all text-center">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Sampai Tanggal</label>
                                <input name="end_date" type="date" value="{{ $endDate }}" class="block w-full border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500 text-sm py-3 transition-all text-center">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Cari Batch/Seri</label>
                                <input name="search" type="text" value="{{ $search }}" placeholder="INPUT BATCH" class="block w-full border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500 text-sm py-3 transition-all text-center">
                            </div>
                            <div class="flex gap-2">
                                <button type="submit" class="flex-1 bg-gray-900 text-white font-black px-6 py-3 rounded-xl hover:bg-gray-800 transition-all active:scale-95 uppercase text-[10px] tracking-widest">
                                    Filter
                                </button>
                                <a href="{{ route('hcts-receiving.summary') }}" class="inline-flex items-center justify-center p-3 bg-gray-100 rounded-xl text-gray-400 hover:text-gray-600 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                </a>
                            </div>
                        </div>

                        <!-- Export Buttons -->
                        <div class="mt-8 pt-6 border-t border-gray-100 flex flex-wrap gap-3 justify-end">
                            <a href="{{ route('hcts-receiving.summary-export', ['start_date'=>$startDate, 'end_date'=>$endDate, 'search'=>$search]) }}" class="inline-flex items-center px-4 py-2 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-xl font-bold text-[10px] uppercase tracking-widest hover:bg-emerald-100 transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                Excel
                            </a>
                            <a href="{{ route('hcts-receiving.summary-print', ['start_date'=>$startDate, 'end_date'=>$endDate, 'search'=>$search]) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-rose-50 text-rose-700 border border-rose-100 rounded-xl font-bold text-[10px] uppercase tracking-widest hover:bg-rose-100 transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 00-2 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                                PDF / Print
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl border border-gray-100">
                <div class="p-8 text-gray-900">
                    
                    <div class="mb-8">
                        <h3 class="text-xl font-black text-gray-900 uppercase tracking-tighter">Ringkasan Akumulasi HCS & HCTS</h3>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1 italic">Perbandingan Total Penerimaan HCS dan HCTS</p>
                    </div>

                    <div class="overflow-x-auto border border-gray-100 rounded-3xl shadow-xl shadow-gray-100/50">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gradient-to-r from-gray-900 to-indigo-900">
                                    <th class="px-6 py-5 text-left text-[10px] font-black text-white/70 uppercase tracking-[0.2em] border-r border-white/10">Batch / Seri</th>
                                    <th class="px-6 py-5 text-center text-[10px] font-black text-white/70 uppercase tracking-[0.2em] border-r border-white/10">Pecahan</th>
                                    <th class="px-6 py-5 text-center text-[10px] font-black text-white/70 uppercase tracking-[0.2em] border-r border-white/10">Emisi / TA</th>
                                    <th class="px-6 py-5 text-right text-[10px] font-black text-emerald-400 uppercase tracking-[0.2em] border-r border-white/10 bg-white/5">Total HCS</th>
                                    <th class="px-6 py-5 text-right text-[10px] font-black text-rose-400 uppercase tracking-[0.2em] border-r border-white/10 bg-white/5">Total HCTS</th>
                                    <th class="px-6 py-5 text-center text-[10px] font-black text-rose-300 uppercase tracking-[0.2em] border-r border-white/10 bg-rose-900/10">% HCTS</th>
                                    <th class="px-6 py-5 text-right text-[10px] font-black text-white uppercase tracking-[0.2em] bg-white/10">Grand Total</th>
                                    <th class="px-6 py-5 text-right text-[10px] font-black text-white/50 uppercase tracking-[0.2em]">Sisa Kuota HCS</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse ($groups as $group)
                                    @php
                                        $grandTotal = $group->total_hcs + $group->total_hcts;
                                        $remaining = 4500000 - $grandTotal;
                                        $usagePercent = ($grandTotal / 4500000) * 100;
                                        $hctsPercent = $group->total_hcs > 0 ? ($group->total_hcts / $group->total_hcs) * 100 : ($group->total_hcts > 0 ? 100 : 0);
                                    @endphp
                                    <tr class="hover:bg-gray-50/80 transition-all group">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-black text-gray-900">{{ $group->batch }}</span>
                                                <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest">{{ $group->seri }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @php
                                                $pchClasses = [
                                                    'S' => 'bg-lime-500 text-white',
                                                    'T' => 'bg-gray-400 text-white',
                                                    'U' => 'bg-amber-400 text-white',
                                                    'V' => 'bg-purple-500 text-white',
                                                    'W' => 'bg-green-500 text-white',
                                                    'X' => 'bg-blue-500 text-white',
                                                    'Y' => 'bg-red-500 text-white',
                                                ];
                                                $currentClass = $pchClasses[$group->pecahan] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-black {{ $currentClass }} border border-gray-200 shadow-sm">{{ $group->pecahan }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-[11px] font-bold text-gray-400 uppercase tracking-tighter">
                                            {{ $group->emisi }} / {{ $group->tahun_anggaran }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right font-mono text-sm font-bold text-emerald-600 bg-emerald-50/30 group-hover:bg-emerald-50/50 transition-colors">
                                            {{ number_format($group->total_hcs, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right font-mono text-sm font-bold text-rose-600 bg-rose-50/30 group-hover:bg-rose-50/50 transition-colors">
                                            {{ number_format($group->total_hcts, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center bg-rose-50 group-hover:bg-rose-100 transition-colors">
                                            <span class="text-xs font-black text-rose-600">{{ number_format($hctsPercent, 2, ',', '.') }}%</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right bg-indigo-50/30 group-hover:bg-indigo-50/50 transition-colors">
                                            <div class="flex flex-col items-end">
                                                <span class="text-sm font-black text-gray-900">{{ number_format($grandTotal, 0, ',', '.') }}</span>
                                                <div class="w-24 h-1 bg-gray-200 rounded-full mt-1 overflow-hidden">
                                                    <div class="h-full {{ $usagePercent > 90 ? 'bg-rose-500' : 'bg-indigo-500' }}" style="width: {{ $usagePercent }}%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <span class="text-xs font-bold {{ $remaining <= 0 ? 'text-rose-500 font-black' : 'text-gray-400' }}">
                                                {{ $remaining <= 0 ? 'LIMIT CAPAI' : number_format($remaining, 0, ',', '.') }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-12 text-center text-gray-400 italic text-sm">Belum ada data akumulasi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $groups->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
