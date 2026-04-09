<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <h2 class="font-black text-xl text-gray-800 dark:text-white leading-tight tracking-tight">Form Input Cutpack</h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Flash --}}
            @if(session('success'))
                <div class="mb-5 p-4 bg-emerald-50 dark:bg-emerald-900/20 border-l-4 border-emerald-500 text-emerald-700 dark:text-emerald-400 shadow-sm rounded-r-lg flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span class="font-bold text-sm">{{ session('success') }}</span>
                </div>
            @endif

            {{-- Card: Pilih Data Master Seri --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden">

                {{-- Card Header --}}
                <div class="p-5 border-b border-gray-100 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 7.125C2.25 6.504 2.754 6 3.375 6h17.25c.621 0 1.125.504 1.125 1.125v1.125c0 .621-.504 1.125-1.125 1.125H3.375A1.125 1.125 0 012.25 8.25V7.125zM2.25 12c0-.621.504-1.125 1.125-1.125h17.25c.621 0 1.125.504 1.125 1.125v1.125c0 .621-.504 1.125-1.125 1.125H3.375A1.125 1.125 0 012.25 13.125V12zM2.25 16.875c0-.621.504-1.125 1.125-1.125h17.25c.621 0 1.125.504 1.125 1.125v1.125c0 .621-.504 1.125-1.125 1.125H3.375a1.125 1.125 0 01-1.125-1.125v-1.125z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-black text-gray-700 dark:text-gray-200">Pilih Data Master Seri</p>
                            <p class="text-[10px] text-gray-400 dark:text-slate-500 font-bold">Klik <span class="text-amber-500 font-black">Buka &amp; Input</span> — tabel pengisian akan terbuka di tab baru</p>
                        </div>
                    </div>
                    <a href="{{ route('x-pengganti.seri.create') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-amber-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-amber-700 transition-all whitespace-nowrap shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        Tambah Seri Baru
                    </a>
                </div>

                {{-- Filter --}}
                <div class="px-5 py-4 bg-gray-50/50 dark:bg-slate-700/20 border-b border-gray-100 dark:border-slate-700">
                    <form method="GET" action="{{ route('x-pengganti.cutpack.index') }}" class="flex flex-wrap gap-3 items-end">
                        {{-- Search --}}
                        <div class="flex-1 min-w-[180px]">
                            <label class="block text-[9px] font-black tracking-widest text-amber-600 mb-1 uppercase">Cari Seri / Batch</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                                <input name="search" type="text" value="{{ $search }}"
                                    placeholder="Ketik seri atau batch..."
                                    class="block w-full pl-10 pr-4 py-2 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 focus:border-amber-500 rounded-xl text-xs font-bold text-gray-700 dark:text-gray-200 dark:placeholder:text-slate-500">
                            </div>
                        </div>

                        {{-- Pecahan --}}
                        <div class="min-w-[90px]">
                            <label class="block text-[9px] font-black tracking-widest text-amber-600 mb-1 uppercase">Pecahan</label>
                            <select name="pecahan" class="block w-full py-2 px-3 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 focus:border-amber-500 rounded-xl text-xs font-black text-gray-700 dark:text-gray-200 text-center-last">
                                <option value="">Semua</option>
                                @foreach($pecahanOptions as $p)
                                    <option value="{{ $p }}" {{ $filterPecahan == $p ? 'selected' : '' }}>{{ $p }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tahun Anggaran --}}
                        <div class="min-w-[110px]">
                            <label class="block text-[9px] font-black tracking-widest text-amber-600 mb-1 uppercase">Tahun Anggaran</label>
                            <select name="tahun_anggaran" class="block w-full py-2 px-3 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 focus:border-amber-500 rounded-xl text-xs font-black text-gray-700 dark:text-gray-200 text-center-last">
                                <option value="">Semua</option>
                                @foreach($tahunAnggaranList as $ta)
                                    <option value="{{ $ta }}" {{ $filterTA == $ta ? 'selected' : '' }}>{{ $ta }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tahun Emisi --}}
                        <div class="min-w-[100px]">
                            <label class="block text-[9px] font-black tracking-widest text-amber-600 mb-1 uppercase">Tahun Emisi</label>
                            <select name="tahun_emisi" class="block w-full py-2 px-3 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 focus:border-amber-500 rounded-xl text-xs font-black text-gray-700 dark:text-gray-200 text-center-last">
                                <option value="">Semua</option>
                                @foreach($tahunEmisiList as $te)
                                    <option value="{{ $te }}" {{ $filterTE == $te ? 'selected' : '' }}>{{ $te }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="h-[38px] px-5 bg-gray-900 dark:bg-slate-600 text-white text-[10px] font-black tracking-widest rounded-xl hover:bg-gray-800 transition-all flex items-center gap-2">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
                            Filter
                        </button>
                        <a href="{{ route('x-pengganti.cutpack.index') }}" class="h-[38px] px-3 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-400 hover:text-rose-600 rounded-xl transition-all flex items-center" title="Reset">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </a>
                    </form>
                </div>

                {{-- Tabel Master --}}
                @php
                    $pchClasses = ['S'=>'bg-lime-500','T'=>'bg-gray-400','U'=>'bg-amber-400','V'=>'bg-purple-500','W'=>'bg-green-500','X'=>'bg-blue-500','Y'=>'bg-red-500'];
                @endphp
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50 dark:bg-slate-700/50 text-[10px] font-black text-gray-400 dark:text-slate-400 uppercase tracking-widest border-b border-gray-100 dark:border-slate-700">
                                <th class="px-4 py-3 text-center w-10">#</th>
                                <th class="px-4 py-3 text-center w-16">Pecahan</th>
                                <th class="px-4 py-3">Seri</th>
                                <th class="px-4 py-3 text-center">Batch</th>
                                <th class="px-4 py-3 text-center">T. Anggaran</th>
                                <th class="px-4 py-3 text-center">T. Emisi</th>
                                <th class="px-4 py-3 text-center">Tgl Input</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                            @forelse($masters as $i => $master)
                                <tr class="hover:bg-indigo-50/40 dark:hover:bg-indigo-900/10 transition-colors">
                                    <td class="px-4 py-3 text-center text-gray-400 dark:text-slate-500 font-bold text-xs">{{ $masters->firstItem() + $i }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg {{ $pchClasses[$master->pecahan] ?? 'bg-gray-500' }} text-white font-black text-[11px] shadow-sm">{{ $master->pecahan }}</span>
                                    </td>
                                    <td class="px-4 py-3 font-black text-gray-700 dark:text-gray-200 text-sm tracking-wider">{{ $master->seri }}</td>
                                    <td class="px-4 py-3 text-center font-black text-gray-600 dark:text-gray-300 text-sm tracking-widest">{{ $master->batch }}</td>
                                    <td class="px-4 py-3 text-center text-gray-500 dark:text-slate-400 font-bold text-xs">{{ $master->tahun_anggaran }}</td>
                                    <td class="px-4 py-3 text-center text-gray-500 dark:text-slate-400 font-bold text-xs">{{ $master->tahun_emisi }}</td>
                                    <td class="px-4 py-3 text-center text-gray-500 dark:text-slate-400 font-bold text-xs">{{ $master->created_at->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <a href="{{ route('x-pengganti.cutpack.input', ['seri_id' => $master->id]) }}"
                                            target="_blank"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gradient-to-r from-indigo-600 to-blue-600 text-white text-[10px] font-black uppercase tracking-widest rounded-lg hover:from-indigo-700 hover:to-blue-700 transition-all shadow-sm shadow-indigo-200 dark:shadow-indigo-900/30 whitespace-nowrap">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            Buka &amp; Input
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-14 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-14 h-14 rounded-2xl bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center mb-3">
                                                <svg class="w-7 h-7 text-indigo-200 dark:text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            </div>
                                            <p class="text-xs font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest">Tidak ada data seri ditemukan</p>
                                            <a href="{{ route('x-pengganti.seri.create') }}" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-indigo-700 transition-all">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                                Tambah Seri Baru
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($masters->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-700/30">
                        {{ $masters->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>

    <style>.text-center-last { text-align-last: center; }</style>
</x-app-layout>