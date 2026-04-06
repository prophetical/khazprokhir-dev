<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <h2 class="font-extrabold text-xl text-white tracking-tight">
                {{ __('Pemakaian Bahan Penolong') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ 
        showModal: false,
        showEditModal: false,
        selectedKategori: 'pemakaian',
        editTransaction: { id: '', bahan_penolong_id: '', jumlah: '', kategori: '', keterangan: '', material_name: '', unit: '' },
        openEdit(t) {
            this.editTransaction = {
                id: t.id,
                bahan_penolong_id: t.bahan_penolong_id,
                jumlah: t.jumlah,
                kategori: t.kategori,
                keterangan: t.keterangan || '',
                material_name: t.bahan_penolong.nama_bahan,
                unit: t.bahan_penolong.satuan
            };
            this.showEditModal = true;
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Filter Card --}}
            <div
                class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl shadow-indigo-100/50 dark:shadow-none border border-gray-100 dark:border-slate-700 p-6 mb-8">
                <form action="{{ route('bahan-penolong.pemakaian') }}" method="GET" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="space-y-1.5">
                            <label
                                class="block text-[11px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest ml-1">Kategori</label>
                            <select name="kategori" onchange="this.form.submit()"
                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-2xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all dark:text-white">
                                <option value="">Semua Kategori</option>
                                <option value="pemakaian" {{ request('kategori') == 'pemakaian' ? 'selected' : '' }}>
                                    Pemakaian Rutin</option>
                                <option value="mutasi" {{ request('kategori') == 'mutasi' ? 'selected' : '' }}>Mutasi /
                                    Perpindahan</option>
                                <option value="rusak" {{ request('kategori') == 'rusak' ? 'selected' : '' }}>Barang Rusak
                                </option>
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label
                                class="block text-[11px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest ml-1">Dari
                                Tanggal</label>
                            <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}"
                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-2xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all dark:text-white">
                        </div>
                        <div class="space-y-1.5">
                            <label
                                class="block text-[11px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest ml-1">Sampai
                                Tanggal</label>
                            <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}"
                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-2xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all dark:text-white">
                        </div>
                        <div class="space-y-1.5">
                            <label
                                class="block text-[11px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest ml-1">Cari
                                Material</label>
                            <div class="relative group">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari..."
                                    class="w-full pl-11 pr-4 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-2xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all dark:text-white">
                                <div
                                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row gap-4 pt-2">
                        <button type="submit"
                            class="flex-1 md:flex-none px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-black rounded-2xl shadow-lg shadow-indigo-100 dark:shadow-none transition-all flex items-center justify-center gap-2 uppercase tracking-widest">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <span>Filter/Cari</span>
                        </button>

                        <a href="{{ route('bahan-penolong.export', array_merge(request()->all(), ['tipe' => 'keluar'])) }}"
                            class="flex-1 md:flex-none px-6 py-3 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-slate-300 text-xs font-black rounded-2xl hover:bg-gray-50 dark:hover:bg-slate-800 transition-all flex items-center justify-center gap-2 uppercase tracking-widest">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>CSV</span>
                        </a>

                        <a href="{{ route('bahan-penolong.print', array_merge(request()->all(), ['tipe' => 'keluar', 'autoprint' => 1])) }}"
                            target="_blank"
                            class="flex-1 md:flex-none px-6 py-3 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-slate-300 text-xs font-black rounded-2xl hover:bg-gray-50 dark:hover:bg-slate-800 transition-all flex items-center justify-center gap-2 uppercase tracking-widest">
                            <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            <span>PDF</span>
                        </a>

                        <div class="flex-1"></div>

                        @if(auth()->user()->role !== 'supervisor')
                            <button type="button" @click="showModal = true"
                                class="flex-1 md:flex-none px-8 py-3 bg-rose-500 hover:bg-rose-600 text-white text-xs font-black rounded-2xl shadow-lg shadow-rose-100 dark:shadow-none transition-all flex items-center justify-center gap-2 uppercase tracking-widest">
                                <span>Input Pemakaian</span>
                            </button>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Tabel Transaksi --}}
            <div
                class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl shadow-indigo-100/50 dark:shadow-none border border-gray-100 dark:border-slate-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-gray-50/50 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-700">
                                <th
                                    class="px-6 py-4 text-[11px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest">
                                    Waktu Keluar</th>
                                <th
                                    class="px-6 py-4 text-[11px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest">
                                    Material</th>
                                <th
                                    class="px-6 py-4 text-[11px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest text-center">
                                    Kategori</th>
                                <th
                                    class="px-6 py-4 text-[11px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest text-center">
                                    Jumlah</th>
                                <th
                                    class="px-6 py-4 text-[11px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest text-center">
                                    Stok Akhir</th>
                                <th
                                    class="px-6 py-4 text-[11px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest">
                                    Keterangan</th>
                                <th
                                    class="px-6 py-4 text-[11px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest">
                                    Petugas</th>
                                <th
                                    class="px-6 py-4 text-[11px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest text-right">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-slate-700">
                            @forelse($transactions as $t)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-700/30 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span
                                                class="font-bold text-gray-900 dark:text-slate-200 capitalize">{{ $t->created_at->translatedFormat('l, d F Y') }}</span>
                                            <span
                                                class="text-[10px] text-gray-400 dark:text-slate-500 uppercase font-black tracking-widest">{{ $t->created_at->format('H:i') }}
                                                WIB</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span
                                                class="font-bold text-rose-600 dark:text-rose-400">{{ $t->bahanPenolong->nama_bahan }}</span>
                                            <span
                                                class="text-[10px] font-mono text-gray-400 uppercase tracking-tighter">{{ $t->bahanPenolong->kode_material ?: 'No Code' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @php
                                            $colorMap = [
                                                'pemakaian' => 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/40 dark:text-indigo-400',
                                                'mutasi' => 'bg-amber-50 text-amber-600 dark:bg-amber-900/40 dark:text-amber-400',
                                                'rusak' => 'bg-rose-50 text-rose-600 dark:bg-rose-900/40 dark:text-rose-400',
                                            ];
                                        @endphp
                                        <span
                                            class="px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest {{ $colorMap[$t->kategori] ?? 'bg-gray-100 text-gray-600' }}">
                                            {{ $t->kategori }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="inline-flex items-baseline gap-1">
                                            <span
                                                class="text-sm font-black text-rose-600">-{{ number_format($t->jumlah) }}</span>
                                            <span
                                                class="text-[9px] font-bold text-gray-400 uppercase">{{ $t->bahanPenolong->satuan }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center font-medium text-gray-600 dark:text-slate-400">
                                        {{ number_format($t->stok_akhir) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="text-xs text-gray-500 dark:text-slate-400 italic">{{ $t->keterangan ?: '-' }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="w-6 h-6 rounded-lg bg-pink-100 dark:bg-pink-900/40 flex items-center justify-center text-[10px] font-black text-pink-600 dark:text-pink-400 uppercase">
                                                {{ substr($t->user->name ?? '?', 0, 1) }}
                                            </div>
                                            <span
                                                class="text-xs font-medium text-gray-600 dark:text-slate-400">{{ $t->user->name ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if(auth()->user()->role !== 'supervisor')
                                            <div class="flex justify-end gap-2 transition-opacity">
                                                <button @click="openEdit({{ json_encode($t) }})"
                                                    class="p-2 text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/40 rounded-xl transition-all"
                                                    title="Ubah Transaksi">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </button>
                                                <form action="{{ route('bahan-penolong.transaction.destroy', $t->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Hapus transaksi ini? Stok akan dikembalikan seperti semula.')"
                                                    class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="p-2 text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/40 rounded-xl transition-all"
                                                        title="Hapus Transaksi">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-[10px] italic text-gray-300">Read Only</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center">
                                        <p class="text-gray-400 dark:text-slate-500 font-medium italic">Belum ada riwayat
                                            pemakaian barang.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($transactions->hasPages())
                    <div class="px-6 py-4 bg-gray-50 dark:bg-slate-900/50 border-t border-gray-100 dark:border-slate-700">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Modal Input Pemakaian --}}
        <div x-show="showModal" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showModal" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0" @click="showModal = false"
                    class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showModal" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-white/10">
                    <form action="{{ route('bahan-penolong.transaction.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="tipe" value="keluar">
                        <div class="px-8 pt-8 pb-6">
                            <div class="flex items-center gap-4 mb-8">
                                <div
                                    class="w-12 h-12 bg-rose-50 dark:bg-rose-900/30 rounded-2xl flex items-center justify-center text-rose-600 dark:text-rose-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 12H4" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-gray-900 dark:text-white leading-none">Pencatatan
                                        Pemakaian</h3>
                                    <p class="text-sm text-gray-400 dark:text-slate-500 mt-1">Kurangi stok untuk
                                        pemakaian, mutasi, atau barang rusak</p>
                                </div>
                            </div>
                            <div class="space-y-5">
                                <div class="space-y-1.5">
                                    <label
                                        class="block text-[11px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest ml-1">Pilih
                                        Material</label>
                                    <select name="bahan_penolong_id" required
                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all dark:text-white">
                                        <option value="">-- Pilih Bahan --</option>
                                        @foreach($materials as $m)
                                            <option value="{{ $m->id }}">{{ $m->nama_bahan }} (Stok: {{ $m->stok }}
                                                {{ $m->satuan }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="space-y-1.5">
                                    <label
                                        class="block text-[11px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest ml-1">Kategori
                                        Transaksi Keluar</label>
                                    <div class="grid grid-cols-3 gap-2">
                                        <label
                                            class="relative flex flex-col items-center p-3 rounded-2xl border cursor-pointer transition-all"
                                            :class="selectedKategori === 'pemakaian' ? 'bg-indigo-50 border-indigo-200 dark:bg-indigo-900/30 dark:border-indigo-500' : 'border-gray-100 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700'">
                                            <input type="radio" name="kategori" value="pemakaian"
                                                x-model="selectedKategori" required
                                                class="absolute inset-0 opacity-0 cursor-pointer">
                                            <span class="text-[10px] font-black uppercase"
                                                :class="selectedKategori === 'pemakaian' ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500 dark:text-slate-400'">Pakai</span>
                                        </label>
                                        <label
                                            class="relative flex flex-col items-center p-3 rounded-2xl border cursor-pointer transition-all"
                                            :class="selectedKategori === 'mutasi' ? 'bg-indigo-50 border-indigo-200 dark:bg-indigo-900/30 dark:border-indigo-500' : 'border-gray-100 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700'">
                                            <input type="radio" name="kategori" value="mutasi"
                                                x-model="selectedKategori" required
                                                class="absolute inset-0 opacity-0 cursor-pointer">
                                            <span class="text-[10px] font-black uppercase"
                                                :class="selectedKategori === 'mutasi' ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500 dark:text-slate-400'">Mutasi</span>
                                        </label>
                                        <label
                                            class="relative flex flex-col items-center p-3 rounded-2xl border cursor-pointer transition-all"
                                            :class="selectedKategori === 'rusak' ? 'bg-indigo-50 border-indigo-200 dark:bg-indigo-900/30 dark:border-indigo-500' : 'border-gray-100 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700'">
                                            <input type="radio" name="kategori" value="rusak" x-model="selectedKategori"
                                                required class="absolute inset-0 opacity-0 cursor-pointer">
                                            <span class="text-[10px] font-black uppercase"
                                                :class="selectedKategori === 'rusak' ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500 dark:text-slate-400'">Rusak</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="space-y-1.5">
                                    <label
                                        class="block text-[11px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest ml-1">Jumlah
                                        Keluar</label>
                                    <input type="number" name="jumlah" required min="1"
                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all dark:text-white"
                                        placeholder="0">
                                </div>
                                <div class="space-y-1.5">
                                    <label
                                        class="block text-[11px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest ml-1">Keterangan
                                        (Opsional)</label>
                                    <textarea name="keterangan" rows="2"
                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all dark:text-white"
                                        placeholder="Isi keterangan pemakaian bahan..."></textarea>
                                </div>
                            </div>
                        </div>
                        <div
                            class="px-8 py-6 bg-gray-50 dark:bg-slate-900/50 flex flex-col-reverse sm:flex-row justify-end gap-3">
                            <button type="button" @click="showModal = false"
                                class="w-full sm:w-auto px-6 py-2.5 text-sm font-bold text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-slate-200 transition-colors">Batal</button>
                            <button type="submit"
                                class="w-full sm:w-auto px-8 py-2.5 bg-rose-500 hover:bg-rose-600 text-white text-xs font-black rounded-2xl shadow-lg shadow-rose-100 dark:shadow-none transition-all">Simpan
                                Transaksi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Modal Edit Pemakaian --}}
        <div x-show="showEditModal" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showEditModal" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0" @click="showEditModal = false"
                    class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showEditModal" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-white/10">
                    <form :action="`/bahan-penolong/transaction/${editTransaction.id}`" method="POST">
                        @csrf @method('PUT')
                        <div class="px-8 pt-8 pb-6">
                            <div class="flex items-center gap-4 mb-8">
                                <div
                                    class="w-12 h-12 bg-indigo-50 dark:bg-indigo-900/30 rounded-2xl flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-gray-900 dark:text-white leading-none">Ubah Data
                                        Pemakaian</h3>
                                    <p class="text-sm text-gray-400 dark:text-slate-500 mt-1"
                                        x-text="editTransaction.material_name"></p>
                                </div>
                            </div>
                            <div class="space-y-5">
                                <div class="space-y-1.5">
                                    <label
                                        class="block text-[11px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest ml-1">Kategori
                                        Transaksi</label>
                                    <div class="grid grid-cols-3 gap-2">
                                        <label
                                            class="relative flex flex-col items-center p-3 rounded-2xl border cursor-pointer transition-all"
                                            :class="editTransaction.kategori === 'pemakaian' ? 'bg-indigo-50 border-indigo-200 dark:bg-indigo-900/30 dark:border-indigo-500' : 'border-gray-100 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700'">
                                            <input type="radio" name="kategori" value="pemakaian"
                                                x-model="editTransaction.kategori" required
                                                class="absolute inset-0 opacity-0 cursor-pointer">
                                            <span class="text-[10px] font-black uppercase"
                                                :class="editTransaction.kategori === 'pemakaian' ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500 dark:text-slate-400'">Pakai</span>
                                        </label>
                                        <label
                                            class="relative flex flex-col items-center p-3 rounded-2xl border cursor-pointer transition-all"
                                            :class="editTransaction.kategori === 'mutasi' ? 'bg-indigo-50 border-indigo-200 dark:bg-indigo-900/30 dark:border-indigo-500' : 'border-gray-100 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700'">
                                            <input type="radio" name="kategori" value="mutasi"
                                                x-model="editTransaction.kategori" required
                                                class="absolute inset-0 opacity-0 cursor-pointer">
                                            <span class="text-[10px] font-black uppercase"
                                                :class="editTransaction.kategori === 'mutasi' ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500 dark:text-slate-400'">Mutasi</span>
                                        </label>
                                        <label
                                            class="relative flex flex-col items-center p-3 rounded-2xl border cursor-pointer transition-all"
                                            :class="editTransaction.kategori === 'rusak' ? 'bg-indigo-50 border-indigo-200 dark:bg-indigo-900/30 dark:border-indigo-500' : 'border-gray-100 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700'">
                                            <input type="radio" name="kategori" value="rusak"
                                                x-model="editTransaction.kategori" required
                                                class="absolute inset-0 opacity-0 cursor-pointer">
                                            <span class="text-[10px] font-black uppercase"
                                                :class="editTransaction.kategori === 'rusak' ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500 dark:text-slate-400'">Rusak</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="space-y-1.5">
                                    <label
                                        class="block text-[11px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest ml-1">Jumlah
                                        Keluar (<span x-text="editTransaction.unit"></span>)</label>
                                    <input type="number" name="jumlah" x-model="editTransaction.jumlah" required min="1"
                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all dark:text-white">
                                </div>
                                <div class="space-y-1.5">
                                    <label
                                        class="block text-[11px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest ml-1">Keterangan</label>
                                    <textarea name="keterangan" x-model="editTransaction.keterangan" rows="2"
                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all dark:text-white"></textarea>
                                </div>
                            </div>
                        </div>
                        <div
                            class="px-8 py-6 bg-gray-50 dark:bg-slate-900/50 flex flex-col-reverse sm:flex-row justify-end gap-3">
                            <button type="button" @click="showEditModal = false"
                                class="w-full sm:w-auto px-6 py-2.5 text-sm font-bold text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-slate-200 transition-colors">Batal</button>
                            <button type="submit"
                                class="w-full sm:w-auto px-8 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-black rounded-2xl shadow-lg shadow-indigo-100 dark:shadow-none transition-all">Simpan
                                Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>