<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('x-pengganti.mapping.index') }}" class="p-1.5 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition-all">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h2 class="font-black text-xl text-gray-800 dark:text-white leading-tight tracking-tight">Cari Seri Pengganti</h2>
        </div>
    </x-slot>
    <style>
        input::placeholder {
            font-size: 0.7rem !important;
            text-transform: none !important;
            font-weight: 500 !important;
            letter-spacing: normal !important;
            opacity: 0.6;
        }
    </style>
    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Search Card --}}
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 dark:border-slate-700">
                <div class="p-6">
                    <h3 class="text-sm font-black text-gray-700 dark:text-white uppercase tracking-widest mb-4">Lacak Seri Pengganti</h3>

                    <form action="{{ route('x-pengganti.mapping.lookup') }}" method="GET">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                            <div>
                                <label class="block text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1">Prefix (3 Huruf)</label>
                                <input type="text" name="prefix" value="{{ $prefix }}" required maxlength="3" pattern="[A-Za-z]{3}" oninput="this.value = this.value.toUpperCase()"
                                    class="w-full py-2.5 px-4 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-bold uppercase focus:ring-emerald-500 focus:border-emerald-500"
                                    placeholder="Contoh: ABA">
                            </div>
                            <div>
                                <label class="block text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1">Nomor Seri</label>
                                <input type="number" name="serial" value="{{ $serial }}" required min="1"
                                    class="w-full py-2.5 px-4 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-bold focus:ring-emerald-500 focus:border-emerald-500"
                                    placeholder="Contoh: 701500">
                            </div>
                            <div>
                                <label class="block text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1">Mode</label>
                                <select name="mode" class="w-full py-2.5 px-4 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-bold focus:ring-emerald-500 focus:border-emerald-500">
                                    <option value="forward" {{ $mode === 'forward' ? 'selected' : '' }}>Asal → Pengganti</option>
                                    <option value="reverse" {{ $mode === 'reverse' ? 'selected' : '' }}>Pengganti → Asal</option>
                                </select>
                            </div>
                            <div>
                                <button type="submit" class="w-full px-6 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:from-emerald-700 hover:to-teal-700 transition-all shadow-lg active:scale-95 flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    Cari
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Forward Lookup Result --}}
            @if($searched && $mode === 'forward')
                @if($result)
                    <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-2xl border border-emerald-200 dark:border-emerald-800">
                        <div class="p-1 bg-gradient-to-r from-emerald-500 to-teal-500"></div>
                        <div class="p-6">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-black text-emerald-700 dark:text-emerald-400 uppercase tracking-widest">Ditemukan!</h3>
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Mapping Seri Asal → Pengganti</p>
                                </div>
                            </div>

                            {{-- Main Result --}}
                            <div class="flex flex-col md:flex-row items-center justify-center gap-4 mb-8">
                                <div class="text-center px-8 py-5 bg-gray-50 dark:bg-slate-700 rounded-2xl border-2 border-gray-200 dark:border-slate-600 min-w-[200px]">
                                    <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Seri Asal</div>
                                    <div class="text-2xl font-black text-gray-800 dark:text-white font-mono tracking-wider">{{ $result['source_full'] }}</div>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-8 h-8 text-emerald-500 transform md:rotate-0 rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </div>
                                <div class="text-center px-8 py-5 bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl border-2 border-emerald-300 dark:border-emerald-700 min-w-[200px]">
                                    <div class="text-[9px] font-black text-emerald-500 uppercase tracking-widest mb-2">Seri Pengganti</div>
                                    <div class="text-2xl font-black text-emerald-700 dark:text-emerald-400 font-mono tracking-wider">{{ $result['replacement_full'] }}</div>
                                </div>
                            </div>

                            {{-- Detail Grid --}}
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                <div class="p-3 bg-gray-50 dark:bg-slate-700 rounded-xl text-center">
                                    <div class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Offset</div>
                                    <div class="text-sm font-black text-gray-700 dark:text-gray-200 mt-1">{{ $result['offset'] }}</div>
                                </div>
                                <div class="p-3 bg-gray-50 dark:bg-slate-700 rounded-xl text-center">
                                    <div class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Pack</div>
                                    <div class="text-sm font-black text-gray-700 dark:text-gray-200 mt-1">{{ $result['pack'] }}</div>
                                </div>
                                <div class="p-3 bg-gray-50 dark:bg-slate-700 rounded-xl text-center">
                                    <div class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Kategori</div>
                                    <div class="text-sm font-black text-gray-700 dark:text-gray-200 mt-1 capitalize">{{ str_replace('_', ' ', $result['category']) }}</div>
                                </div>
                                <div class="p-3 bg-gray-50 dark:bg-slate-700 rounded-xl text-center">
                                    <div class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Bilyet dlm Range</div>
                                    <div class="text-sm font-black text-gray-700 dark:text-gray-200 mt-1">{{ number_format($result['bilyet_count']) }}</div>
                                </div>
                            </div>

                            {{-- Range Info --}}
                            <div class="mt-4 p-4 bg-violet-50 dark:bg-violet-900/10 rounded-xl">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-[10px] font-bold">
                                    <div><span class="text-gray-400">Range Asal:</span> <span class="font-mono text-gray-700 dark:text-gray-300">{{ $result['range_source'] }}</span></div>
                                    <div><span class="text-gray-400">Range Pengganti:</span> <span class="font-mono text-emerald-700 dark:text-emerald-400">{{ $result['range_replacement'] }}</span></div>
                                    @if($result['seri'])
                                        <div><span class="text-gray-400">Batch:</span> <span class="font-black text-gray-700 dark:text-gray-300">{{ $result['seri']->batch }}</span></div>
                                        <div><span class="text-gray-400">Seri/Pecahan:</span> <span class="font-black text-gray-700 dark:text-gray-300">{{ $result['seri']->seri }} / {{ $result['seri']->pecahan }}</span></div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-2xl border border-rose-200 dark:border-rose-800">
                        <div class="p-1 bg-gradient-to-r from-rose-500 to-pink-500"></div>
                        <div class="p-8 text-center">
                            <div class="w-16 h-16 rounded-2xl bg-rose-50 dark:bg-rose-900/20 flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-rose-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h3 class="text-sm font-black text-rose-600 uppercase tracking-widest mb-1">Tidak Ditemukan</h3>
                            <p class="text-xs text-gray-400 font-bold">Serial <span class="font-mono font-black">{{ strtoupper($prefix) }}{{ $serial }}</span> tidak ditemukan dalam mapping manapun.</p>
                        </div>
                    </div>
                @endif
            @endif

            {{-- Reverse Lookup Result --}}
            @if($searched && $mode === 'reverse')
                @if($reverseResult)
                    <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-2xl border border-blue-200 dark:border-blue-800">
                        <div class="p-1 bg-gradient-to-r from-blue-500 to-indigo-500"></div>
                        <div class="p-6">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-black text-blue-700 dark:text-blue-400 uppercase tracking-widest">Ditemukan! (Reverse)</h3>
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Mapping Pengganti → Seri Asal</p>
                                </div>
                            </div>

                            <div class="flex flex-col md:flex-row items-center justify-center gap-4 mb-8">
                                <div class="text-center px-8 py-5 bg-blue-50 dark:bg-blue-900/20 rounded-2xl border-2 border-blue-300 dark:border-blue-700 min-w-[200px]">
                                    <div class="text-[9px] font-black text-blue-500 uppercase tracking-widest mb-2">Seri Pengganti</div>
                                    <div class="text-2xl font-black text-blue-700 dark:text-blue-400 font-mono tracking-wider">{{ $reverseResult['replacement_full'] }}</div>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-8 h-8 text-blue-500 transform md:rotate-0 rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </div>
                                <div class="text-center px-8 py-5 bg-gray-50 dark:bg-slate-700 rounded-2xl border-2 border-gray-200 dark:border-slate-600 min-w-[200px]">
                                    <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Seri Asal</div>
                                    <div class="text-2xl font-black text-gray-800 dark:text-white font-mono tracking-wider">{{ $reverseResult['source_full'] }}</div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                <div class="p-3 bg-gray-50 dark:bg-slate-700 rounded-xl text-center">
                                    <div class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Offset</div>
                                    <div class="text-sm font-black text-gray-700 dark:text-gray-200 mt-1">{{ $reverseResult['offset'] }}</div>
                                </div>
                                <div class="p-3 bg-gray-50 dark:bg-slate-700 rounded-xl text-center">
                                    <div class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Pack</div>
                                    <div class="text-sm font-black text-gray-700 dark:text-gray-200 mt-1">{{ $reverseResult['pack'] }}</div>
                                </div>
                                <div class="p-3 bg-gray-50 dark:bg-slate-700 rounded-xl text-center">
                                    <div class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Kategori</div>
                                    <div class="text-sm font-black text-gray-700 dark:text-gray-200 mt-1 capitalize">{{ str_replace('_', ' ', $reverseResult['category']) }}</div>
                                </div>
                                <div class="p-3 bg-gray-50 dark:bg-slate-700 rounded-xl text-center">
                                    <div class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Bilyet dlm Range</div>
                                    <div class="text-sm font-black text-gray-700 dark:text-gray-200 mt-1">{{ number_format($reverseResult['bilyet_count']) }}</div>
                                </div>
                            </div>

                            <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/10 rounded-xl">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-[10px] font-bold">
                                    <div><span class="text-gray-400">Range Asal:</span> <span class="font-mono text-gray-700 dark:text-gray-300">{{ $reverseResult['range_source'] }}</span></div>
                                    <div><span class="text-gray-400">Range Pengganti:</span> <span class="font-mono text-blue-700 dark:text-blue-400">{{ $reverseResult['range_replacement'] }}</span></div>
                                    @if($reverseResult['seri'])
                                        <div><span class="text-gray-400">Batch:</span> <span class="font-black text-gray-700 dark:text-gray-300">{{ $reverseResult['seri']->batch }}</span></div>
                                        <div><span class="text-gray-400">Seri/Pecahan:</span> <span class="font-black text-gray-700 dark:text-gray-300">{{ $reverseResult['seri']->seri }} / {{ $reverseResult['seri']->pecahan }}</span></div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-2xl border border-rose-200 dark:border-rose-800">
                        <div class="p-1 bg-gradient-to-r from-rose-500 to-pink-500"></div>
                        <div class="p-8 text-center">
                            <div class="w-16 h-16 rounded-2xl bg-rose-50 dark:bg-rose-900/20 flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-rose-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h3 class="text-sm font-black text-rose-600 uppercase tracking-widest mb-1">Tidak Ditemukan</h3>
                            <p class="text-xs text-gray-400 font-bold">Serial pengganti <span class="font-mono font-black">{{ strtoupper($prefix) }}{{ $serial }}</span> tidak ditemukan.</p>
                        </div>
                    </div>
                @endif
            @endif

        </div>
    </div>
</x-app-layout>
