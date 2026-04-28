<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-lg text-gray-800 leading-tight tracking-tighter">
            {{ __('Registrasi Penerimaan HCS') }}
        </h2>
    </x-slot>

    <div class="py-3 text-[10px]" x-data="{ openDetail: false, activeReg: {} }">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-4">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100">
                <div class="p-5 text-gray-900">

                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-4">
                        <div>
                            <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest">Antrean Registrasi
                                HCS</h3>
                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter">Kelola data awal
                                dari Seksi Khazai</p>
                        </div>
                        <a href="{{ route('hcs-khazai-registration.create') }}"
                            class="inline-flex items-center px-4 py-2.5 bg-indigo-600 border border-transparent rounded-xl font-black text-[9px] text-white uppercase tracking-[0.2em] hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 transition-all duration-300 active:scale-95">
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tambah Penerimaan HCS
                        </a>
                    </div>

                    <!-- Search -->
                    <div class="mb-5 p-3 bg-gray-50/50 rounded-2xl border border-gray-100">
                        <form action="{{ route('hcs-khazai-registration.index') }}" method="GET" class="flex gap-2">
                            <div class="relative flex-grow group">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="pl-10 w-full border-gray-200 rounded-xl text-[10px] font-bold py-2 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all placeholder-gray-300 shadow-sm"
                                    placeholder="Cari Nomor Bon, Barcode, atau Batch...">
                            </div>
                            <button type="submit"
                                class="px-5 bg-white border border-gray-200 rounded-xl font-black text-[9px] uppercase tracking-widest text-gray-600 hover:bg-gray-50 transition-all shadow-sm">Filter</button>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead>
                                <tr class="bg-gray-50/50">
                                    <th
                                        class="px-3 py-2.5 text-left font-black text-gray-400 uppercase tracking-widest">
                                        <a href="{{ route('hcs-khazai-registration.index', array_merge(request()->query(), ['sort' => 'tanggal_pembuatan', 'direction' => ($sort === 'tanggal_pembuatan' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                                            class="flex items-center gap-1 hover:text-indigo-600 transition-colors group">
                                            Tanggal
                                            <span class="inline-flex flex-col">
                                                <svg class="w-3 h-3 {{ $sort === 'tanggal_pembuatan' && $direction === 'asc' ? 'text-indigo-600' : 'text-gray-300 group-hover:text-gray-400' }}"
                                                    fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M7 14l5-5 5 5H7z" />
                                                </svg>
                                                <svg class="w-3 h-3 -mt-1.5 {{ $sort === 'tanggal_pembuatan' && $direction === 'desc' ? 'text-indigo-600' : 'text-gray-300 group-hover:text-gray-400' }}"
                                                    fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M7 10l5 5 5-5H7z" />
                                                </svg>
                                            </span>
                                        </a>
                                    </th>
                                    <th
                                        class="px-3 py-2.5 text-left font-black text-gray-400 uppercase tracking-widest">
                                        <a href="{{ route('hcs-khazai-registration.index', array_merge(request()->query(), ['sort' => 'nomor_bon', 'direction' => ($sort === 'nomor_bon' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                                            class="flex items-center gap-1 hover:text-indigo-600 transition-colors group">
                                            Nomor Bon
                                            <span class="inline-flex flex-col">
                                                <svg class="w-3 h-3 {{ $sort === 'nomor_bon' && $direction === 'asc' ? 'text-indigo-600' : 'text-gray-300 group-hover:text-gray-400' }}"
                                                    fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M7 14l5-5 5 5H7z" />
                                                </svg>
                                                <svg class="w-3 h-3 -mt-1.5 {{ $sort === 'nomor_bon' && $direction === 'desc' ? 'text-indigo-600' : 'text-gray-300 group-hover:text-gray-400' }}"
                                                    fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M7 10l5 5 5-5H7z" />
                                                </svg>
                                            </span>
                                        </a>
                                    </th>
                                    <th
                                        class="px-3 py-2.5 text-left font-black text-gray-400 uppercase tracking-widest">
                                        <a href="{{ route('hcs-khazai-registration.index', array_merge(request()->query(), ['sort' => 'barcode_token', 'direction' => ($sort === 'barcode_token' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                                            class="flex items-center gap-1 hover:text-indigo-600 transition-colors group">
                                            Barcode
                                            <span class="inline-flex flex-col">
                                                <svg class="w-3 h-3 {{ $sort === 'barcode_token' && $direction === 'asc' ? 'text-indigo-600' : 'text-gray-300 group-hover:text-gray-400' }}"
                                                    fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M7 14l5-5 5 5H7z" />
                                                </svg>
                                                <svg class="w-3 h-3 -mt-1.5 {{ $sort === 'barcode_token' && $direction === 'desc' ? 'text-indigo-600' : 'text-gray-300 group-hover:text-gray-400' }}"
                                                    fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M7 10l5 5 5-5H7z" />
                                                </svg>
                                            </span>
                                        </a>
                                    </th>
                                    <th
                                        class="px-3 py-2.5 text-center font-black text-gray-400 uppercase tracking-widest">
                                        <a href="{{ route('hcs-khazai-registration.index', array_merge(request()->query(), ['sort' => 'tahun_anggaran', 'direction' => ($sort === 'tahun_anggaran' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                                            class="flex items-center justify-center gap-1 hover:text-indigo-600 transition-colors group">
                                            TA/TE
                                            <span class="inline-flex flex-col">
                                                <svg class="w-3 h-3 {{ $sort === 'tahun_anggaran' && $direction === 'asc' ? 'text-indigo-600' : 'text-gray-300 group-hover:text-gray-400' }}"
                                                    fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M7 14l5-5 5 5H7z" />
                                                </svg>
                                                <svg class="w-3 h-3 -mt-1.5 {{ $sort === 'tahun_anggaran' && $direction === 'desc' ? 'text-indigo-600' : 'text-gray-300 group-hover:text-gray-400' }}"
                                                    fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M7 10l5 5 5-5H7z" />
                                                </svg>
                                            </span>
                                        </a>
                                    </th>
                                    <th
                                        class="px-3 py-2.5 text-left font-black text-gray-400 uppercase tracking-widest text-center">
                                        <div class="flex items-center justify-center gap-1">
                                            <a href="{{ route('hcs-khazai-registration.index', array_merge(request()->query(), ['sort' => 'batch', 'direction' => ($sort === 'batch' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                                                class="hover:text-indigo-600 transition-colors {{ $sort === 'batch' ? 'text-indigo-600' : '' }}">Batch</a>
                                            <span class="text-gray-300">/</span>
                                            <a href="{{ route('hcs-khazai-registration.index', array_merge(request()->query(), ['sort' => 'seri', 'direction' => ($sort === 'seri' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                                                class="hover:text-indigo-600 transition-colors {{ $sort === 'seri' ? 'text-indigo-600' : '' }}">Seri</a>
                                            <span class="text-gray-300">/ Bilyet</span>
                                        </div>
                                    </th>
                                    <th
                                        class="px-3 py-2.5 text-left font-black text-gray-400 uppercase tracking-widest">
                                        <a href="{{ route('hcs-khazai-registration.index', array_merge(request()->query(), ['sort' => 'pecahan', 'direction' => ($sort === 'pecahan' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                                            class="flex items-center gap-1 hover:text-indigo-600 transition-colors group">
                                            Pecahan
                                            <span class="inline-flex flex-col">
                                                <svg class="w-3 h-3 {{ $sort === 'pecahan' && $direction === 'asc' ? 'text-indigo-600' : 'text-gray-300 group-hover:text-gray-400' }}"
                                                    fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M7 14l5-5 5 5H7z" />
                                                </svg>
                                                <svg class="w-3 h-3 -mt-1.5 {{ $sort === 'pecahan' && $direction === 'desc' ? 'text-indigo-600' : 'text-gray-300 group-hover:text-gray-400' }}"
                                                    fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M7 10l5 5 5-5H7z" />
                                                </svg>
                                            </span>
                                        </a>
                                    </th>
                                    <th
                                        class="px-3 py-2.5 text-left font-black text-gray-400 uppercase tracking-widest">
                                        Petugas</th>
                                    <th
                                        class="px-3 py-2.5 text-center font-black text-gray-400 uppercase tracking-widest">
                                        <a href="{{ route('hcs-khazai-registration.index', array_merge(request()->query(), ['sort' => 'status', 'direction' => ($sort === 'status' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                                            class="flex items-center justify-center gap-1 hover:text-indigo-600 transition-colors group">
                                            Status
                                            <span class="inline-flex flex-col">
                                                <svg class="w-3 h-3 {{ $sort === 'status' && $direction === 'asc' ? 'text-indigo-600' : 'text-gray-300 group-hover:text-gray-400' }}"
                                                    fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M7 14l5-5 5 5H7z" />
                                                </svg>
                                                <svg class="w-3 h-3 -mt-1.5 {{ $sort === 'status' && $direction === 'desc' ? 'text-indigo-600' : 'text-gray-300 group-hover:text-gray-400' }}"
                                                    fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M7 10l5 5 5-5H7z" />
                                                </svg>
                                            </span>
                                        </a>
                                    </th>
                                    <th
                                        class="px-3 py-2.5 text-center font-black text-gray-400 uppercase tracking-widest">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 uppercase">
                                @forelse($registrations as $reg)
                                    <tr class="hover:bg-indigo-50/30 transition-all duration-300">
                                        <td class="px-3 py-2">
                                            <div class="flex flex-col">
                                                <span
                                                    class="font-bold text-gray-700 leading-tight">{{ $reg->tanggal_pembuatan->isoFormat('D MMM YY') }}</span>
                                                <span
                                                    class="text-[9px] text-gray-400 font-medium tracking-tight">{{ $reg->created_at->format('H:i') }}</span>
                                            </div>
                                        </td>
                                        <td class="px-3 py-2 font-black text-gray-900 tracking-tighter">
                                            {{ $reg->nomor_bon }}
                                        </td>
                                        <td class="px-3 py-2">
                                            <div class="flex items-center gap-1.5">
                                                <span
                                                    class="bg-gray-100 px-2 py-0.5 rounded-md font-mono font-black text-indigo-700 ring-1 ring-gray-200">{{ $reg->barcode_token }}</span>
                                                <a href="{{ route('hcs-khazai-registration.barcode', $reg->id) }}"
                                                    target="_blank"
                                                    class="text-gray-400 hover:text-indigo-600 transition-colors"
                                                    title="Cetak Barcode">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 01-1.12 1.227H7.231a1.125 1.125 0 01-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.658M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z">
                                                        </path>
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                        <td class="px-3 py-2 text-center font-bold text-gray-600">
                                            {{ $reg->tahun_anggaran }}/{{ $reg->emisi }}
                                        </td>
                                        <td class="px-3 py-2 text-center">
                                            <div
                                                class="inline-flex items-center gap-1.5 bg-gray-50 px-2 py-0.5 rounded-lg border border-gray-100">
                                                <span class="text-gray-400 font-bold">{{ $reg->batch }}</span>
                                                <span class="w-0.5 h-0.5 bg-gray-300 rounded-full"></span>
                                                <span class="text-gray-400 font-bold">{{ $reg->seri }}</span>
                                                <span class="w-0.5 h-0.5 bg-gray-300 rounded-full"></span>
                                                <span
                                                    class="text-gray-900 font-black">{{ number_format($reg->jumlah, 0, ',', '.') }}</span>
                                            </div>
                                        </td>
                                        <td class="px-3 py-2 text-center">
                                            @php
                                                $pecahanColors = [
                                                    'S' => 'bg-lime-500',
                                                    'T' => 'bg-gray-500',
                                                    'U' => 'bg-amber-500',
                                                    'V' => 'bg-purple-500',
                                                    'W' => 'bg-emerald-500',
                                                    'X' => 'bg-blue-500',
                                                    'Y' => 'bg-red-500'
                                                ];
                                            @endphp
                                            <span
                                                class="{{ $pecahanColors[$reg->pecahan] ?? 'bg-indigo-500' }} text-white px-1.5 py-0.5 rounded-md font-black text-[8px]">{{ $reg->pecahan }}</span>
                                        </td>
                                        <td class="px-3 py-2 font-bold text-gray-400">{{ $reg->petugasKhazai->name ?? '-' }}
                                        </td>
                                        <td class="px-3 py-2 text-center">
                                            @if($reg->status === 'diterima')
                                                <span
                                                    class="bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-full font-black text-[8px] uppercase tracking-widest ring-1 ring-emerald-100 shadow-sm shadow-emerald-50">DITERIMA</span>
                                            @else
                                                <span
                                                    class="bg-amber-50 text-amber-600 px-2 py-0.5 rounded-full font-black text-[8px] uppercase tracking-widest ring-1 ring-amber-100 shadow-sm shadow-amber-50">ANTRE</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2">
                                            <div class="flex justify-center items-center gap-2">
                                                <button @click="activeReg = {{ json_encode($reg) }}; openDetail = true"
                                                    class="p-1.5 bg-indigo-50 text-indigo-400 rounded-lg hover:bg-indigo-600 hover:text-white transition-all duration-300 shadow-sm"
                                                    title="Lihat Detail">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                        </path>
                                                    </svg>
                                                </button>
                                                <a href="{{ route('hcs-khazai-registration.create', ['batch' => $reg->batch, 'seri' => $reg->seri, 'pecahan' => $reg->pecahan, 'emisi' => $reg->emisi, 'tahun_anggaran' => $reg->tahun_anggaran, 'supplier' => $reg->supplier]) }}"
                                                    class="p-1.5 bg-emerald-50 text-emerald-600 rounded-lg hover:bg-emerald-600 hover:text-white transition-all duration-300 shadow-sm"
                                                    title="Input di seri/batch ini">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                </a>
                                                @if($reg->status !== 'diterima')
                                                    <a href="{{ route('hcs-khazai-registration.edit', $reg->id) }}"
                                                        class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition-all duration-300 shadow-sm"
                                                        title="Edit Data">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                    <form action="{{ route('hcs-khazai-registration.destroy', $reg->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit"
                                                            class="p-1.5 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-600 hover:text-white transition-all duration-300 shadow-sm"
                                                            title="Hapus Data">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @else
                                                    <a href="{{ route('hcs-receiving.history', $reg->barcode_token) }}"
                                                        class="p-1.5 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-600 hover:text-white transition-all duration-300 shadow-sm"
                                                        title="Riwayat Perubahan">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                    </a>
                                                    <span class="text-gray-300">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                                            </path>
                                                        </svg>
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9"
                                            class="px-4 py-8 text-center text-gray-400 font-bold uppercase italic">Belum ada
                                            antrean registrasi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-8">
                        {{ $registrations->links() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Detail Registrasi -->
        <div x-show="openDetail" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="openDetail" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 transition-opacity" @click="openDetail = false">
                    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;

                <div x-show="openDetail" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-100">

                    <!-- Header -->
                    <div class="bg-gray-50/50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <div>
                            <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest"
                                x-text="'Detail Registrasi: ' + (activeReg.barcode_token || '')"></h3>
                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter"
                                x-text="activeReg.nomor_bon"></p>
                        </div>
                        <button @click="openDetail = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Content -->
                    <div class="px-6 py-6 max-h-[70vh] overflow-y-auto custom-scrollbar">
                        <!-- Metadata Grid -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-6 mb-8">
                            <div>
                                <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Batch /
                                    Seri</p>
                                <p class="text-xs font-black text-indigo-600"
                                    x-text="(activeReg.batch || '-') + ' / ' + (activeReg.seri || '-')"></p>
                            </div>
                            <div>
                                <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Pecahan
                                </p>
                                <span class="px-2 py-0.5 rounded text-[9px] font-black text-white bg-indigo-500"
                                    x-text="activeReg.pecahan"></span>
                            </div>
                            <div>
                                <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">TA / TE
                                </p>
                                <p class="text-xs font-black text-gray-700"
                                    x-text="(activeReg.tahun_anggaran || '-') + ' / ' + (activeReg.emisi || '-')"></p>
                            </div>
                            <div>
                                <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Jumlah
                                    Bilyet</p>
                                <p class="text-xs font-black text-gray-900"
                                    x-text="new Intl.NumberFormat('id-ID').format(activeReg.jumlah || 0)"></p>
                            </div>
                            <div>
                                <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Gilir /
                                    Mesin</p>
                                <p class="text-xs font-bold text-gray-600"
                                    x-text="(activeReg.gilir || '-') + ' / ' + (activeReg.mesin || '-')"></p>
                            </div>
                            <div>
                                <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Supplier
                                </p>
                                <p class="text-xs font-bold text-gray-600" x-text="activeReg.supplier || '-'"></p>
                            </div>
                        </div>

                        <!-- Pemilihan pack -->
                        <div class="border-t border-gray-100 pt-6">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-[10px] font-black text-gray-900 uppercase tracking-widest">Daftar Nomor
                                    Pack</h4>
                                <span class="bg-gray-100 px-2 py-0.5 rounded-full text-[8px] font-black text-gray-500"
                                    x-text="(activeReg.packs_data ? activeReg.packs_data.length : 0) + ' PACK'"></span>
                            </div>
                            <div class="grid grid-cols-4 sm:grid-cols-6 lg:grid-cols-8 gap-2">
                                <template x-for="packNum in activeReg.packs_data" :key="packNum">
                                    <div class="bg-gray-50 border border-gray-100 rounded-lg py-1.5 text-center text-[10px] font-black text-gray-600 hover:bg-indigo-50 hover:border-indigo-100 hover:text-indigo-600 transition-all cursor-default"
                                        x-text="packNum"></div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="bg-gray-50/50 px-6 py-4 border-t border-gray-100 flex justify-end">
                        <button @click="openDetail = false"
                            class="px-6 py-2 bg-white border border-gray-200 rounded-xl font-black text-[9px] uppercase tracking-widest text-gray-600 hover:bg-gray-50 transition-all shadow-sm">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>