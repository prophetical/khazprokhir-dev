<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <h2 class="font-black text-xl text-gray-800 dark:text-white leading-tight tracking-tight">Master Data Seri
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Welcome & Action Card --}}
            <div
                class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 dark:border-slate-700">
                <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex items-center gap-4">
                        <div>
                            <h3 class="text-lg font-black text-gray-800 dark:text-white leading-tight">Master Data Seri
                            </h3>
                            <p
                                class="text-xs text-gray-400 dark:text-slate-500 font-bold uppercase tracking-widest mt-1">
                                Kelola daftar seri bilyet & vell pengganti</p>
                        </div>
                    </div>

                    @if(in_array(auth()->user()->role, ['admin', 'sortir', 'kemas', 'khazverutas']))
                        <a href="{{ route('x-pengganti.seri.create') }}"
                            class="inline-flex items-center justify-center gap-3 px-6 py-3 bg-gradient-to-r from-violet-600 to-purple-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:from-violet-700 hover:to-purple-700 transition-all duration-300 shadow-xl shadow-violet-200 dark:shadow-violet-900/20 active:scale-95 group">
                            <svg class="w-4 h-4 transition-transform group-hover:rotate-90 duration-300" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Seri Baru
                        </a>
                    @endif
                </div>
            </div>

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 border-l-4 border-emerald-500 text-emerald-700 dark:text-emerald-400 shadow-sm rounded-r-lg flex items-center gap-3"
                    role="alert">
                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-bold text-sm">{{ session('success') }}</span>
                </div>
            @endif

            {{-- Filter --}}
            <div
                class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 dark:border-slate-700">
                <div class="p-5">
                    <form action="{{ route('x-pengganti.seri.index') }}" method="GET"
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
                                    <option value="{{ $p }}" {{ $pecahan == $p ? 'selected' : '' }}>{{ $p }}</option>
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
                            <a href="{{ route('x-pengganti.seri.index') }}"
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

            {{-- Tabel Data --}}
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
                                <th class="px-4 py-3 text-center">Tahun Anggaran</th>
                                <th class="px-4 py-3 text-center">Tahun Emisi</th>
                                <th class="px-4 py-3 text-center">Petugas</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                            @php
                                $pchClasses = [
                                    'S' => 'bg-lime-500',
                                    'T' => 'bg-gray-400',
                                    'U' => 'bg-amber-400',
                                    'V' => 'bg-purple-500',
                                    'W' => 'bg-green-500',
                                    'X' => 'bg-blue-500',
                                    'Y' => 'bg-red-500',
                                ];
                            @endphp
                            @forelse($seris as $seri)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-700/50 transition-colors text-[12px]">
                                    <td class="px-4 py-3 text-center text-gray-400 dark:text-slate-500 font-bold">
                                        {{ $seris->firstItem() + $loop->index }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span
                                            class="inline-flex items-center justify-center w-7 h-7 rounded-lg {{ $pchClasses[$seri->pecahan] ?? 'bg-gray-500' }} text-white font-black text-[11px] shadow-sm">
                                            {{ $seri->pecahan }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 font-bold text-gray-700 dark:text-gray-200">{{ $seri->seri }}</td>
                                    <td
                                        class="px-4 py-3 text-center font-black text-gray-600 dark:text-gray-300 tracking-wider">
                                        {{ $seri->batch }}
                                    </td>
                                    <td class="px-4 py-3 text-center text-gray-500 dark:text-slate-400 font-bold">
                                        {{ $seri->tahun_anggaran }}
                                    </td>
                                    <td class="px-4 py-3 text-center text-gray-500 dark:text-slate-400 font-bold">
                                        {{ $seri->tahun_emisi }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <div
                                                class="w-6 h-6 rounded bg-violet-600 flex items-center justify-center text-[9px] font-black text-white">
                                                {{ strtoupper(substr($seri->user->name ?? '?', 0, 1)) }}
                                            </div>
                                            <span
                                                class="text-[9px] font-black text-gray-500 dark:text-slate-400 uppercase truncate max-w-[60px]">{{ $seri->user->name ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-1">
                                            {{-- Aksi: buka Khazai untuk seri ini --}}
                                            <a href="{{ route('x-pengganti.khazai.index', ['seri_id' => $seri->id]) }}"
                                                class="p-1.5 text-violet-400 hover:text-violet-600 hover:bg-violet-50 dark:hover:bg-violet-900/30 rounded-lg transition-all"
                                                title="Buka Form Khazai">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 10h18M3 14h18M10 3v18M14 3v18" />
                                                </svg>
                                            </a>
                                            @if(in_array(auth()->user()->role, ['admin', 'sortir', 'kemas', 'khazverutas']))
                                                <form action="{{ route('x-pengganti.seri.destroy', $seri) }}" method="POST"
                                                    class="inline-block delete-confirm">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="p-1.5 text-rose-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/30 rounded-lg transition-all"
                                                        title="Hapus">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v2m3 4h.01" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-14 text-center">
                                        <div class="flex flex-col items-center">
                                            <div
                                                class="w-16 h-16 rounded-2xl bg-violet-50 dark:bg-violet-900/20 flex items-center justify-center mb-4">
                                                <svg class="w-8 h-8 text-violet-200 dark:text-violet-700" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <p
                                                class="text-xs font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest italic">
                                                Belum ada data seri</p>
                                            @if(in_array(auth()->user()->role, ['admin', 'sortir', 'kemas', 'khazverutas']))
                                                <a href="{{ route('x-pengganti.seri.create') }}"
                                                    class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-violet-600 text-white text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-violet-700 transition-all">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                    </svg>
                                                    Tambah Seri Pertama
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($seris->hasPages())
                    <div
                        class="px-6 py-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-700/30">
                        {{ $seris->links() }}
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

    <script>
        // Konfirmasi sebelum hapus
        document.querySelectorAll('.delete-confirm').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                if (confirm('Yakin ingin menghapus data seri ini beserta seluruh data Khazai-nya? Tindakan ini tidak dapat dibatalkan.')) {
                    this.submit();
                }
            });
        });
    </script>
</x-app-layout>