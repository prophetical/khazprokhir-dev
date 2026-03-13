<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengemasan HCS') }}
        </h2>
    </x-slot>
                    @php
                        $themeClasses = [
                            'S' => ['bg' => 'bg-lime-500', 'border' => 'border-lime-500', 'ring' => 'focus:ring-lime-500', 'focus' => 'focus:border-lime-500', 'btn' => 'bg-lime-500', 'text' => 'text-white'],
                            'T' => ['bg' => 'bg-gray-400', 'border' => 'border-gray-400', 'ring' => 'focus:ring-gray-400', 'focus' => 'focus:border-gray-400', 'btn' => 'bg-gray-400', 'text' => 'text-white'],
                            'U' => ['bg' => 'bg-amber-400', 'border' => 'border-amber-400', 'ring' => 'focus:ring-amber-400', 'focus' => 'focus:border-amber-400', 'btn' => 'bg-amber-400', 'text' => 'text-white'],
                            'V' => ['bg' => 'bg-purple-500', 'border' => 'border-purple-500', 'ring' => 'focus:ring-purple-500', 'focus' => 'focus:border-purple-500', 'btn' => 'bg-purple-500', 'text' => 'text-white'],
                            'W' => ['bg' => 'bg-green-500', 'border' => 'border-green-500', 'ring' => 'focus:ring-green-500', 'focus' => 'focus:border-green-500', 'btn' => 'bg-green-500', 'text' => 'text-white'],
                            'X' => ['bg' => 'bg-blue-500', 'border' => 'border-blue-500', 'ring' => 'focus:ring-blue-500', 'focus' => 'focus:border-blue-500', 'btn' => 'bg-blue-500', 'text' => 'text-white'],
                            'Y' => ['bg' => 'bg-red-500', 'border' => 'border-red-500', 'ring' => 'focus:ring-red-500', 'focus' => 'focus:border-red-500', 'btn' => 'bg-red-500', 'text' => 'text-white'],
                        ];
                        $selectedPecahan = request('pecahan', '');
                    @endphp
    <div class="py-8">
        <div class="max-w-10xl mx-auto sm:px-6 lg:px-8">
            <div x-data="{ 
                        selectedPecahan: '{{ $selectedPecahan }}',
                        themes: {{ json_encode($themeClasses) }},
                        get currentTheme() { return this.themes[this.selectedPecahan] || null }
                    }" 
                    class="bg-white overflow-hidden shadow-sm rounded-xl mb-6 border-t-4 transition-all duration-500"
                    :class="currentTheme ? currentTheme.border : 'border-gray-100'">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-md font-bold text-indigo-800">Daftar Pack Siap Kemas</h2>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow-sm" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(!empty($missingGaps))
                        <div class="mb-6 space-y-3">
                            @foreach($missingGaps as $gap)
                                <div class="bg-amber-50 border-l-4 border-amber-400 p-4 rounded-r-md shadow-sm">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-bold text-amber-800">Peringatan: Terdapat nomor dus yang hilang</h3>
                                            <div class="mt-1 text-sm text-amber-700">
                                                Pada Pecahan <span class="font-bold">{{ $gap['pecahan'] }}</span>, 
                                                Tahun Anggaran <span class="font-bold">{{ $gap['tahun_anggaran'] }}</span>, 
                                                Tahun Emisi <span class="font-bold">{{ $gap['tahun_emisi'] }}</span>. 
                                                Range nomor dus yang tidak ditemukan: <span class="font-bold font-mono">{{ $gap['ranges'] }}</span>.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- KOTAK FILTER PENCARIAN -->
                    <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 mb-6 shadow-sm">
                        <form method="GET" action="{{ route('pengemasan.index') }}">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                                <!-- Filter Pecahan -->
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Pecahan</label>
                                    <select name="pecahan" x-model="selectedPecahan" class="block w-full border-gray-200 rounded-lg shadow-sm text-sm py-3 px-3 transition-all font-bold focus:ring-opacity-50"
                                        :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'">
                                        <option value="">Semua</option>
                                        <option value="S">S</option>
                                        <option value="T">T</option>
                                        <option value="U">U</option>
                                        <option value="V">V</option>
                                        <option value="W">W</option>
                                        <option value="X">X</option>
                                        <option value="Y">Y</option>
                                    </select>
                                </div>

                                <!-- Filter Tahun Anggaran -->
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Tahun Anggaran</label>
                                    <select name="tahun_anggaran" class="block w-full border-gray-200 rounded-lg shadow-sm text-sm py-3 px-3 transition-all font-bold focus:ring-opacity-50"
                                        :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'">
                                        <option value="">Semua</option>
                                        <option value="2024" {{ request('tahun_anggaran') == '2024' ? 'selected' : '' }}>2024</option>
                                        <option value="2025" {{ request('tahun_anggaran') == '2025' ? 'selected' : '' }}>2025</option>
                                        <option value="2026" {{ request('tahun_anggaran') == '2026' ? 'selected' : '' }}>2026</option>
                                        <option value="2027" {{ request('tahun_anggaran') == '2027' ? 'selected' : '' }}>2027</option>
                                    </select>
                                </div>

                                <!-- Cari Data Umum -->
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Cari (Batch / Seri)</label>
                                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Batch, Seri..." class="block w-full border-gray-200 rounded-lg shadow-sm text-sm py-3 px-3 transition-all focus:ring-opacity-50"
                                        :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'">
                                </div>

                                <!-- Tombol Aksi -->
                                <div class="flex gap-2">
                                    <button type="submit" class="flex-1 inline-flex justify-center items-center px-4 py-3 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest shadow-md active:scale-95 transition-all duration-500"
                                        :class="currentTheme ? currentTheme.btn : 'bg-gray-800 hover:bg-gray-700'">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                        Cari
                                    </button>
                                    <a href="{{ route('pengemasan.index') }}" class="flex-1 inline-flex justify-center items-center px-4 py-3 bg-gray-100 border border-gray-200 rounded-lg font-bold text-xs text-gray-400 uppercase tracking-widest shadow-sm hover:bg-gray-200 active:scale-95 transition-all @if(!request('search') && !request('pecahan') && !request('tahun_anggaran')) opacity-50 pointer-events-none @endif">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                        Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- DAFTAR PACK SIAP KEMAS -->
                    <div class="mb-10">
                        <div class="overflow-hidden bg-white rounded-xl shadow border border-gray-100">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-indigo-50/50">
                                    <tr>
                                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-widest">TA/TE</th>
                                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-widest">Pecahan</th>
                                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-widest">Batch/Seri</th>
                                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-widest">Rentang Pack</th>
                                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-widest">Jumlah Pack</th>
                                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-widest">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @forelse($readyGroups as $group)
                                        <tr class="hover:bg-indigo-50/30 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <div class="text-sm font-bold text-gray-900">{{ $group['tahun_anggaran'] }}</div>
                                                <div class="text-xs text-gray-500 text-center">{{ $group['emisi'] }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                @php
                                                    $pecahanColors = [
                                                        'S' => 'bg-lime-500 text-white',
                                                        'T' => 'bg-gray-400 text-white',
                                                        'U' => 'bg-amber-400 text-white',
                                                        'V' => 'bg-purple-500 text-white',
                                                        'W' => 'bg-green-500 text-white',
                                                        'X' => 'bg-blue-500 text-white',
                                                        'Y' => 'bg-red-500 text-white',
                                                    ];
                                                    $badgeColor = $pecahanColors[$group['pecahan']] ?? 'bg-gray-100 text-gray-800';
                                                @endphp
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-black {{ $badgeColor }} border border-transparent shadow-sm">
                                                    {{ $group['pecahan'] }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <div class="text-sm font-bold text-gray-900">{{ $group['batch'] }}</div>
                                                <div class="text-xs font-mono text-gray-500">{{ $group['seri'] }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <span class="text-sm font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded">
                                                    {{ $group['pack_awal'] }} - {{ $group['pack_akhir'] }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center font-black text-gray-900">
                                                {{ $group['jumlah_pack'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center space-x-2">
                                                @if($group['has_buntut'])
                                                    <a href="{{ route('pengemasan.create', [
                                                        'tahun_anggaran' => $group['tahun_anggaran'],
                                                        'tahun_emisi' => $group['emisi'],
                                                        'pecahan' => $group['pecahan'],
                                                        'batch' => $group['batch'],
                                                        'seri' => $group['seri'],
                                                        'pack_awal' => $group['pack_awal'],
                                                        'pack_akhir' => $group['pack_akhir'],
                                                        'max_pack_akhir' => $group['pack_akhir'],
                                                        'is_manual_sisa' => 1
                                                    ]) }}" 
                                                       class="inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 transition ease-in-out duration-150 shadow-sm shadow-red-100">
                                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                                        Kemas Pack Buntut
                                                    </a>
                                                @else
                                                    @php
                                                        $maxValidPackAkhir = $group['pack_akhir'];
                                                    @endphp
                                                    <a href="{{ route('pengemasan.create', [
                                                        'tahun_anggaran' => $group['tahun_anggaran'],
                                                        'tahun_emisi' => $group['emisi'],
                                                        'pecahan' => $group['pecahan'],
                                                        'batch' => $group['batch'],
                                                        'seri' => $group['seri'],
                                                        'pack_awal' => $group['pack_awal'],
                                                        'pack_akhir' => $maxValidPackAkhir,
                                                        'max_pack_akhir' => $maxValidPackAkhir
                                                    ]) }}" 
                                                       class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-sm">
                                                        Kemas Semua
                                                    </a>
                                                    <a href="{{ route('pengemasan.create', [
                                                        'tahun_anggaran' => $group['tahun_anggaran'],
                                                        'tahun_emisi' => $group['emisi'],
                                                        'pecahan' => $group['pecahan'],
                                                        'batch' => $group['batch'],
                                                        'seri' => $group['seri'],
                                                        'pack_awal' => $group['pack_awal'],
                                                        'pack_akhir' => '',
                                                        'max_pack_akhir' => $maxValidPackAkhir
                                                    ]) }}"
                                                       class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-bold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 active:bg-gray-100 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-sm">
                                                        Kemas Sebagian
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500 italic">
                                                Tidak ada pack yang berstatus selesai sortir dan siap kemas.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        <div class="mt-6 border-t border-gray-100 pt-4">
                            {{ $readyGroups->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
