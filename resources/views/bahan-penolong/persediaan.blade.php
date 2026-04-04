<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <h2 class="font-extrabold text-xl text-white tracking-tight">
                {{ __('Persediaan Bahan Penolong') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ 
        showModal: false, 
        isEdit: false, 
        material: { id: '', nama_bahan: '', kode_material: '', satuan: '', min_stok: 0, keterangan: '' },
        openAdd() {
            this.isEdit = false;
            this.material = { id: '', nama_bahan: '', kode_material: '', satuan: '', min_stok: 0, keterangan: '' };
            this.showModal = true;
        },
        openEdit(m) {
            this.isEdit = true;
            this.material = { ...m };
            this.showModal = true;
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Low Stock Alert Banner --}}
            @if($low_stock_count > 0)
                <div
                    class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-2xl p-4 md:p-5 flex flex-col md:flex-row items-center justify-between gap-4 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div
                            class="flex-shrink-0 w-12 h-12 bg-red-100 dark:bg-red-900/50 rounded-full flex items-center justify-center text-red-600 dark:text-red-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-red-800 dark:text-red-300">Perhatian: Stok Menipis</h3>
                            <p class="text-sm text-red-600 dark:text-red-400 mt-0.5">
                                Terdapat <strong class="font-black">{{ $low_stock_count }}</strong> jenis material yang
                                stoknya sudah mencapai batas minimum.
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('bahan-penolong.persediaan', ['filter' => 'low']) }}"
                        class="whitespace-nowrap px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-semibold transition-colors shadow-sm focus:ring-2 focus:ring-red-500/50 focus:outline-none">
                        Lihat Rincian
                    </a>
                </div>
            @endif

            {{-- Search & Actions Card --}}
            <div
                class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 p-5 mb-8">
                <form action="{{ route('bahan-penolong.persediaan') }}" method="GET"
                    class="flex flex-col lg:flex-row gap-4 items-center">

                    {{-- Search Input --}}
                    <div class="w-full lg:flex-1 relative">
                        <div
                            class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 dark:text-slate-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama atau kode material..."
                            class="w-full pl-10 pr-4 py-2.5 bg-gray-50/50 dark:bg-slate-900/50 border border-gray-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-colors dark:text-slate-200 placeholder:text-gray-400 dark:placeholder:text-slate-600">
                    </div>

                    {{-- Action Buttons --}}
                    <div class="w-full lg:w-auto flex flex-wrap lg:flex-nowrap gap-2 items-center shrink-0">
                        <button type="submit"
                            class="flex-1 lg:flex-none px-5 py-2.5 bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 text-sm font-semibold rounded-xl transition-colors flex items-center justify-center gap-2 border border-indigo-100 dark:border-indigo-800">
                            <span>Cari</span>
                        </button>

                        <div class="h-8 w-px bg-gray-200 dark:bg-slate-700 hidden lg:block mx-1"></div>

                        <a href="{{ route('bahan-penolong.inventory.export', request()->all()) }}"
                            class="flex-1 lg:flex-none px-4 py-2.5 bg-white dark:bg-slate-800 text-gray-700 dark:text-slate-300 text-sm font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors flex items-center justify-center gap-2 border border-gray-200 dark:border-slate-600 shadow-sm"
                            title="Download Excel/CSV">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4-4v8" />
                                <path d="M14 9V5a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-4"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                            </svg>
                            <span class="lg:hidden">CSV</span>
                        </a>

                        <a href="{{ route('bahan-penolong.inventory.print', array_merge(request()->all(), ['autoprint' => 1])) }}"
                            target="_blank"
                            class="flex-1 lg:flex-none px-4 py-2.5 bg-white dark:bg-slate-800 text-gray-700 dark:text-slate-300 text-sm font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors flex items-center justify-center gap-2 border border-gray-200 dark:border-slate-600 shadow-sm"
                            title="Print/PDF Laporan">
                            <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            <span class="lg:hidden">PDF</span>
                        </a>

                        @if(auth()->user()->role !== 'supervisor')
                            <div class="h-8 w-px bg-gray-200 dark:bg-slate-700 hidden lg:block mx-1"></div>
                            <button type="button" @click="openAdd()"
                                class="flex-1 lg:flex-none px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-colors flex items-center justify-center gap-2 focus:ring-2 focus:ring-indigo-500/50 focus:outline-none w-full lg:w-auto mt-2 lg:mt-0 lg:ml-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                <span>Tambah</span>
                            </button>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Main Table Card --}}
            <div
                class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl shadow-indigo-100/50 dark:shadow-none border border-gray-100 dark:border-slate-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-gray-50/50 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-700">
                                <th
                                    class="px-6 py-4 text-[11px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest">
                                    Material</th>
                                <th
                                    class="px-6 py-4 text-[11px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest">
                                    Kode</th>
                                <th
                                    class="px-6 py-4 text-[11px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest text-center">
                                    Stok Saat Ini</th>
                                <th
                                    class="px-6 py-4 text-[11px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest text-center">
                                    Min. Stok</th>
                                <th
                                    class="px-6 py-4 text-[11px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest">
                                    Satuan</th>
                                <th
                                    class="px-6 py-4 text-[11px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest text-right">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-slate-700">
                            @forelse($materials as $m)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-700/30 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span
                                                class="font-bold text-gray-900 dark:text-slate-200">{{ $m->nama_bahan }}</span>
                                            <span
                                                class="text-xs text-gray-400 dark:text-slate-500">{{ Str::limit($m->keterangan, 40) ?: '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-2 py-1 bg-gray-100 dark:bg-slate-900 text-gray-600 dark:text-slate-400 rounded text-[10px] font-mono border border-gray-200 dark:border-slate-700 tracking-tighter uppercase whitespace-nowrap">
                                            {{ $m->kode_material ?: 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="inline-flex flex-col items-center">
                                            <span
                                                class="text-lg font-black {{ $m->stok <= $m->min_stok ? 'text-rose-500' : 'text-emerald-500' }}">
                                                {{ number_format($m->stok) }}
                                            </span>
                                            @if($m->stok <= $m->min_stok)
                                                <span
                                                    class="text-[9px] font-bold text-rose-500 uppercase tracking-tighter animate-pulse">Low
                                                    Stock ⚠️</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center font-medium text-gray-400 dark:text-slate-500">
                                        {{ number_format($m->min_stok) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="text-xs font-bold text-indigo-500 dark:text-indigo-400 uppercase tracking-widest">{{ $m->satuan }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if(auth()->user()->role !== 'supervisor')
                                            <div class="flex justify-end gap-2">
                                                <button @click="openEdit({{ json_encode($m) }})"
                                                    class="p-2 text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded-xl transition-all"
                                                    title="Edit Master Data">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </button>
                                                <form action="{{ route('bahan-penolong.destroy', $m->id) }}" method="POST"
                                                    onsubmit="return confirm('Hapus material ini? Seluruh riwayat transaksi juga akan terhapus.')"
                                                    class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="p-2 text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/30 rounded-xl transition-all"
                                                        title="Hapus">
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
                                            <span class="text-[10px] italic text-gray-300 dark:text-slate-600">Read Only</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-200 dark:text-slate-700 mb-4" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                            <p class="text-gray-400 dark:text-slate-500 font-medium">Belum ada data material
                                                penolong.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($materials->hasPages())
                    <div class="px-6 py-4 bg-gray-50 dark:bg-slate-900/50 border-t border-gray-100 dark:border-slate-700">
                        {{ $materials->links() }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Modal for Add/Edit --}}
        <div x-show="showModal" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showModal" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0" @click="showModal = false"
                    class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" aria-hidden="true">
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showModal" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-white/10">

                    <form :action="isEdit ? `/bahan-penolong/${material.id}` : '{{ route('bahan-penolong.store') }}'"
                        method="POST">
                        @csrf
                        <template x-if="isEdit">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <div class="px-8 pt-8 pb-6">
                            <div class="flex items-center gap-4 mb-8">
                                <div
                                    class="w-12 h-12 bg-indigo-50 dark:bg-indigo-900/30 rounded-2xl flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-gray-900 dark:text-white leading-none"
                                        x-text="isEdit ? 'Edit Jenis Bahan' : 'Tambah Jenis Bahan'"></h3>
                                    <p class="text-sm text-gray-400 dark:text-slate-500 mt-1">Kelola master data
                                        material pengemasan</p>
                                </div>
                            </div>

                            <div class="space-y-5">
                                {{-- Nama Bahan --}}
                                <div class="space-y-1.5">
                                    <label
                                        class="block text-[11px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest ml-1">Nama
                                        Material</label>
                                    <input type="text" name="nama_bahan" x-model="material.nama_bahan" required
                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm dark:text-slate-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all"
                                        placeholder="Contoh: Dus Luar (Outer)">
                                </div>

                                {{-- Kode Material --}}
                                <div class="space-y-1.5">
                                    <label
                                        class="block text-[11px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest ml-1">Kode
                                        Material (Opsional)</label>
                                    <input type="text" name="kode_material" x-model="material.kode_material"
                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm dark:text-slate-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-mono"
                                        placeholder="MT-001">
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    {{-- Satuan --}}
                                    <div class="space-y-1.5">
                                        <label
                                            class="block text-[11px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest ml-1">Satuan</label>
                                        <input type="text" name="satuan" x-model="material.satuan" required
                                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm dark:text-slate-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all"
                                            placeholder="pcs / roll / buah">
                                    </div>
                                    {{-- Min Stok --}}
                                    <div class="space-y-1.5">
                                        <label
                                            class="block text-[11px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest ml-1">Min.
                                            Stok</label>
                                        <input type="number" name="min_stok" x-model="material.min_stok" required
                                            min="0"
                                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm dark:text-slate-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all"
                                            placeholder="Peringatan stok rendah">
                                    </div>
                                </div>

                                {{-- Keterangan --}}
                                <div class="space-y-1.5">
                                    <label
                                        class="block text-[11px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest ml-1">Keterangan</label>
                                    <textarea name="keterangan" x-model="material.keterangan" rows="3"
                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm dark:text-slate-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all"></textarea>
                                </div>
                            </div>
                        </div>

                        <div
                            class="px-8 py-6 bg-gray-50 dark:bg-slate-900/50 flex flex-col-reverse sm:flex-row justify-end gap-3">
                            <button type="button" @click="showModal = false"
                                class="w-full sm:w-auto px-6 py-2.5 text-sm font-bold text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-slate-200 transition-colors">
                                Batal
                            </button>
                            <button type="submit"
                                class="w-full sm:w-auto px-8 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-black rounded-2xl shadow-lg shadow-indigo-100 dark:shadow-none transition-all">
                                <span x-text="isEdit ? 'Simpan Perubahan' : 'Tambah Material'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>