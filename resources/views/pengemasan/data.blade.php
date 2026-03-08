<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Pengemasan HCS') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">
                @php
                    $pecahan = request('pecahan');
                    $borderColor = 'border-indigo-500'; // default
                    if ($pecahan === 'S') $borderColor = 'border-lime-500';
                    elseif ($pecahan === 'T') $borderColor = 'border-gray-500';
                    elseif ($pecahan === 'U') $borderColor = 'border-amber-600';
                    elseif ($pecahan === 'V') $borderColor = 'border-purple-500';
                    elseif ($pecahan === 'W') $borderColor = 'border-green-500';
                    elseif ($pecahan === 'X') $borderColor = 'border-blue-500';
                    elseif ($pecahan === 'Y') $borderColor = 'border-red-500';
                @endphp

                <!-- Kotak Filter & Export -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border-t-4 {{ $borderColor }} transition-all duration-500">
                    <div class="p-6">
                         <h3 class="text-lg font-bold text-gray-900 border-l-4 border-indigo-600 pl-4 mb-8">Laporan Pengemasan HCS</h3>
                        <form method="GET" action="{{ route('pengemasan.data') }}">
                            @if(request('sort'))
                                <input type="hidden" name="sort" value="{{ request('sort') }}">
                                <input type="hidden" name="direction" value="{{ request('direction') }}">
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                                <!-- Filter Pecahan -->
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Pecahan</label>
                                    <select name="pecahan" class="block w-full border-gray-200 rounded-lg shadow-sm text-sm py-3 px-3 transition-all font-bold focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Semua</option>
                                        <option value="S" {{ request('pecahan') == 'S' ? 'selected' : '' }}>S</option>
                                        <option value="T" {{ request('pecahan') == 'T' ? 'selected' : '' }}>T</option>
                                        <option value="U" {{ request('pecahan') == 'U' ? 'selected' : '' }}>U</option>
                                        <option value="V" {{ request('pecahan') == 'V' ? 'selected' : '' }}>V</option>
                                        <option value="W" {{ request('pecahan') == 'W' ? 'selected' : '' }}>W</option>
                                        <option value="X" {{ request('pecahan') == 'X' ? 'selected' : '' }}>X</option>
                                        <option value="Y" {{ request('pecahan') == 'Y' ? 'selected' : '' }}>Y</option>
                                    </select>
                                </div>
                                
                                <!-- Cari Data Umum -->
                                <div class="lg:col-span-2">
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Cari Data (Batch, Seri, dll)</label>
                                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Data..." class="block w-full border-gray-200 rounded-lg shadow-sm text-sm py-3 px-3 transition-all focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <!-- Cari No Dus -->
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Cari No Dus Spesifik</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                        </span>
                                        <input type="number" name="search_dus" value="{{ request('search_dus') }}" placeholder="No Dus" class="block w-full pl-10 border-indigo-200 rounded-lg shadow-sm text-sm py-3 bg-indigo-50 text-indigo-900 focus:border-indigo-500 focus:ring-indigo-500 transition-all">
                                    </div>
                                </div>

                                <!-- Tombol Aksi -->
                                <div class="flex flex-col gap-2">
                                    <div class="flex gap-2 h-full">
                                        <button type="submit" class="flex-1 inline-flex justify-center items-center px-4 py-3 bg-gray-800 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest shadow-md hover:bg-gray-700 active:scale-95 transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                        </button>
                                        <a href="{{ route('pengemasan.data') }}" class="flex-1 inline-flex justify-center items-center px-4 py-3 bg-gray-100 border border-gray-200 rounded-lg font-bold text-xs text-gray-400 uppercase tracking-widest shadow-sm hover:bg-gray-200 active:scale-95 transition-all @if(!request('search') && !request('pecahan') && !request('search_dus')) opacity-50 pointer-events-none @endif">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                        </a>
                                    </div>
                                </div>

                            </div>
                            
                            <!-- Pemisah antara Form Pencarian dan Tombol Export -->
                            <div class="mt-6 pt-4 border-t border-gray-100 flex flex-wrap gap-2 justify-end lg:justify-end">
                                <a href="{{ route('pengemasan.export', request()->all()) }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg hover:bg-emerald-100 transition-colors shadow-sm" title="Export Excel (CSV)">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    Excel
                                </a>
                                <a href="{{ route('pengemasan.print', request()->all()) }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium bg-rose-50 text-rose-700 border border-rose-200 rounded-lg hover:bg-rose-100 transition-colors shadow-sm" title="Export PDF / Print">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                    PDF
                                </a>
                                <a href="{{ route('pengemasan.print', array_merge(request()->all(), ['autoprint' => 1])) }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium bg-gray-50 text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-100 transition-colors shadow-sm" title="Cetak Langsung">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 00-2 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                                    Print
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Kotak Tabel Data (Tanpa Filter di Dalamnya) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 border-t-4 border-gray-200">

                    @php
                        $direction = request('direction') === 'asc' ? 'desc' : 'asc';
                        function sortIcon($column) {
                            if (request('sort') === $column) {
                                return request('direction') === 'asc' ? '↑' : '↓';
                            }
                            return '';
                        }
                    @endphp

                    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider bg-gray-50 border-b border-gray-200">
                                        <a href="{{ route('pengemasan.data', array_merge(request()->query(), ['sort' => 'tanggal_pengemasan', 'direction' => $direction])) }}" class="hover:text-indigo-600 flex items-center">
                                            Tanggal {{ sortIcon('tanggal_pengemasan') }}
                                        </a>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider bg-gray-50 border-b border-gray-200">Identitas</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider bg-gray-50 border-b border-gray-200">Pack Awal-Akhir</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider bg-gray-50 border-b border-gray-200">
                                        <a href="{{ route('pengemasan.data', array_merge(request()->query(), ['sort' => 'jumlah_dus', 'direction' => $direction])) }}" class="hover:text-indigo-600 flex items-center">
                                            Jml Dus {{ sortIcon('jumlah_dus') }}
                                        </a>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider bg-gray-50 border-b border-gray-200">No Dus</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider bg-gray-50 border-b border-gray-200">
                                        <a href="{{ route('pengemasan.data', array_merge(request()->query(), ['sort' => 'petugas', 'direction' => $direction])) }}" class="hover:text-indigo-600 flex items-center">
                                            Petugas {{ sortIcon('petugas') }}
                                        </a>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider bg-gray-50 border-b border-gray-200">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($pengemasans as $p)
                                    <tr class="hover:bg-indigo-50 transition-colors duration-200 group">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">
                                            {{ \Carbon\Carbon::parse($p->tanggal_pengemasan)->locale('id')->isoFormat('D MMMM YYYY') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-900">{{ $p->pecahan }} - {{ $p->batch }} - {{ $p->seri }}</div>
                                            <div class="text-xs text-gray-500">{{ $p->tahun_anggaran }} / {{ $p->tahun_emisi }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            <div class="mb-1">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $p->pack_awal }} - {{ $p->pack_akhir }}
                                                </span>
                                            </div>
                                            <div class="text-xs text-gray-500 font-bold ml-1">({{ $p->jumlah_pack }} pack)</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-purple-100 text-purple-800 font-black">
                                                {{ $p->jumlah_dus }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-bold text-center">
                                            {{ $p->dus_awal }} - {{ $p->dus_akhir }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 flex items-center space-x-2 mt-2">
                                            <div class="h-6 w-6 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600">
                                                {{ substr($p->user->name ?? '?', 0, 1) }}
                                            </div>
                                            <span>{{ $p->user->name ?? '-' }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('pengemasan.show', $p) }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-sm transition-colors duration-200 shadow-sm" title="Lihat Detail">
                                                    <svg class="ml-1.5 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                </a>
                                                
                                                @if(auth()->user()->role === 'sortir' || auth()->user()->role === 'admin')
                                                <form id="delete-form-{{ $p->id }}" action="{{ route('pengemasan.destroy', $p->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" onclick="deletePengemasan({{ $p->id }})" class="inline-flex items-center text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-sm transition-colors duration-200 shadow-sm" title="Hapus Data">
                                                        <svg class="ml-1.5 w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                            </svg>
                                            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada pengemasan</h3>
                                            <p class="mt-1 text-sm text-gray-500">Mulai pengemasan dari menu Pengemasan HCS.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-8 border-t border-gray-100 pt-4">
                        {{ $pengemasans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 -->
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
    
    <script>
        function deletePengemasan(id) {
            Swal.fire({
                title: 'Hapus Data Pengemasan?',
                text: 'Data penyortiran terkait akan dibuka kembali dan bungkus akan dibongkar.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
</x-app-layout>
