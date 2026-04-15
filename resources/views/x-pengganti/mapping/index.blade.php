<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <h2 class="font-black text-xl text-gray-800 dark:text-white leading-tight tracking-tight">Pemetaan Seri X
                Pengganti
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Header Card --}}
            <div
                class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 dark:border-slate-700">
                <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <h3 class="text-lg font-black text-gray-800 dark:text-white leading-tight">Pemetaan Seri Asal ↔
                            Pengganti</h3>
                        <p class="text-xs text-gray-400 dark:text-slate-500 font-bold uppercase tracking-widest mt-1">
                            Pilih master seri untuk melakukan input mapping range serial
                        </p>
                    </div>
                    <a href="{{ route('x-pengganti.mapping.lookup') }}"
                        class="inline-flex items-center justify-center gap-3 px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:from-emerald-700 hover:to-teal-700 transition-all duration-300 shadow-xl shadow-emerald-200 dark:shadow-emerald-900/20 active:scale-95 group">
                        <svg class="w-4 h-4 transition-transform group-hover:scale-110 duration-300" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Cari Serial
                    </a>
                </div>
            </div>

            {{-- Filter --}}
            <div
                class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 dark:border-slate-700">
                <div class="p-5">
                    <form action="{{ route('x-pengganti.mapping.index') }}" method="GET"
                        class="flex flex-wrap gap-3 items-end">
                        <div class="flex-1 min-w-[160px]">
                            <label
                                class="block text-[9px] font-black tracking-widest text-violet-600 mb-1 uppercase">Cari
                                Batch / Seri</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-violet-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input name="search" type="text" value="{{ $search }}"
                                    class="block w-full pl-10 pr-3 py-2.5 bg-violet-50/30 dark:bg-slate-700 border border-violet-100 dark:border-slate-600 focus:border-violet-500 focus:ring focus:ring-violet-200 rounded-xl text-xs font-bold text-gray-700 dark:text-gray-200 transition-all"
                                    placeholder="Cari batch atau seri..." />
                            </div>
                        </div>
                        <div class="min-w-[120px]">
                            <label
                                class="block text-[9px] font-black tracking-widest text-violet-600 mb-1 uppercase">Pecahan</label>
                            <select name="pecahan"
                                class="block w-full py-2.5 px-3 bg-violet-50/30 dark:bg-slate-700 border border-violet-100 dark:border-slate-600 focus:border-violet-500 rounded-xl text-xs font-black text-gray-700 dark:text-gray-200 text-center-last">
                                <option value="">Semua</option>
                                @foreach($pecahanOptions as $p)
                                    <option value="{{ $p }}" {{ $filterPecahan == $p ? 'selected' : '' }}>{{ $p }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="min-w-[120px]">
                            <label
                                class="block text-[9px] font-black tracking-widest text-violet-600 mb-1 uppercase">Tahun
                                Anggaran</label>
                            <select name="tahun_anggaran"
                                class="block w-full py-2.5 px-3 bg-violet-50/30 dark:bg-slate-700 border border-violet-100 dark:border-slate-600 focus:border-violet-500 rounded-xl text-xs font-black text-gray-700 dark:text-gray-200 text-center-last">
                                <option value="">Semua</option>
                                @foreach($tahunAnggaranList as $ta)
                                    <option value="{{ $ta }}" {{ $filterTA == $ta ? 'selected' : '' }}>{{ $ta }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex gap-2 h-[42px]">
                            <button type="submit"
                                class="px-5 py-2 bg-gray-900 dark:bg-slate-600 text-white text-[10px] font-black tracking-widest rounded-xl hover:bg-gray-800 transition-all flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Filter
                            </button>
                            <a href="{{ route('x-pengganti.mapping.index') }}"
                                class="px-3 py-2 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-400 hover:text-rose-600 rounded-xl transition-all flex items-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Table --}}
            <div
                class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 dark:border-slate-700">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-gray-50/50 dark:bg-slate-700/50 text-[10px] font-black text-gray-400 dark:text-slate-400 uppercase tracking-widest border-b border-gray-100 dark:border-slate-700">
                                <th class="px-4 py-3 text-center">#</th>
                                <th class="px-4 py-3 text-center">Pecahan</th>
                                <th class="px-4 py-3 text-left">Seri</th>
                                <th class="px-4 py-3 text-center">Batch</th>
                                <th class="px-4 py-3 text-center">TA</th>
                                <th class="px-4 py-3 text-center">TE</th>
                                <th class="px-4 py-3 text-center">Petugas</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                            @php
                                $pchClasses = ['S' => 'bg-lime-500', 'T' => 'bg-gray-400', 'U' => 'bg-amber-400', 'V' => 'bg-purple-500', 'W' => 'bg-green-500', 'X' => 'bg-blue-500', 'Y' => 'bg-red-500'];
                            @endphp
                            @forelse($masters as $seri)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-700/50 transition-colors text-[12px]">
                                    <td class="px-4 py-3 text-center text-gray-400 dark:text-slate-500 font-bold">
                                        {{ $masters->firstItem() + $loop->index }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span
                                            class="inline-flex items-center justify-center w-7 h-7 rounded-lg {{ $pchClasses[$seri->pecahan] ?? 'bg-gray-500' }} text-white font-black text-[11px] shadow-sm">{{ $seri->pecahan }}</span>
                                    </td>
                                    <td class="px-4 py-3 font-bold text-gray-700 dark:text-gray-200">{{ $seri->seri }}</td>
                                    <td
                                        class="px-4 py-3 text-center font-black text-gray-600 dark:text-gray-300 tracking-wider">
                                        {{ $seri->batch }}</td>
                                    <td class="px-4 py-3 text-center text-gray-500 dark:text-slate-400 font-bold">
                                        {{ $seri->tahun_anggaran }}</td>
                                    <td class="px-4 py-3 text-center text-gray-500 dark:text-slate-400 font-bold">
                                        {{ $seri->tahun_emisi }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <div
                                                class="w-6 h-6 rounded bg-violet-600 flex items-center justify-center text-[9px] font-black text-white">
                                                {{ strtoupper(substr($seri->user->name ?? '?', 0, 1)) }}</div>
                                            <span
                                                class="text-[9px] font-black text-gray-500 dark:text-slate-400 uppercase truncate max-w-[60px]">{{ $seri->user->name ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <a href="{{ route('x-pengganti.mapping.create', ['seri_id' => $seri->id]) }}"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-violet-600 to-purple-600 text-white text-[9px] font-black uppercase tracking-widest rounded-xl hover:from-violet-700 hover:to-purple-700 transition-all shadow-md active:scale-95"
                                            title="Input Mapping">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                            </svg>
                                            Mapping
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-14 text-center">
                                        <p
                                            class="text-xs font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest italic">
                                            Belum ada data seri</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($masters->hasPages())
                    <div
                        class="px-6 py-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-700/30">
                        {{ $masters->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <style>
        .text-center-last {
            text-align-last: center;
        }
    </style>
</x-app-layout>