<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Penerimaan HCS') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-10">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900">
                    
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                        <h3 class="text-lg font-medium text-gray-900">Data Penerimaan HCS</h3>
                        @if(in_array(auth()->user()->role, ['sortir', 'admin']))
                            <a href="{{ route('hcs-receiving.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shrink-0">
                                Tambah Data
                            </a>
                        @endif
                    </div>

                    @php
                        $selectedPecahan = request('pecahan', '');
                        $themeClasses = [
                            'S' => ['bg' => 'bg-lime-500', 'border' => 'border-lime-500', 'ring' => 'focus:ring-lime-500', 'focus' => 'focus:border-lime-500', 'btn' => 'bg-lime-500', 'text' => 'text-gray-900'],
                            'T' => ['bg' => 'bg-gray-400', 'border' => 'border-gray-400', 'ring' => 'focus:ring-gray-400', 'focus' => 'focus:border-gray-400', 'btn' => 'bg-gray-400', 'text' => 'text-white'],
                            'U' => ['bg' => 'bg-amber-400', 'border' => 'border-amber-400', 'ring' => 'focus:ring-amber-400', 'focus' => 'focus:border-amber-400', 'btn' => 'bg-amber-400', 'text' => 'text-gray-900'],
                            'V' => ['bg' => 'bg-purple-500', 'border' => 'border-purple-500', 'ring' => 'focus:ring-purple-500', 'focus' => 'focus:border-purple-500', 'btn' => 'bg-purple-500', 'text' => 'text-white'],
                            'W' => ['bg' => 'bg-green-500', 'border' => 'border-green-500', 'ring' => 'focus:ring-green-500', 'focus' => 'focus:border-green-500', 'btn' => 'bg-green-500', 'text' => 'text-white'],
                            'X' => ['bg' => 'bg-blue-500', 'border' => 'border-blue-500', 'ring' => 'focus:ring-blue-500', 'focus' => 'focus:border-blue-500', 'btn' => 'bg-blue-500', 'text' => 'text-white'],
                            'Y' => ['bg' => 'bg-red-500', 'border' => 'border-red-500', 'ring' => 'focus:ring-red-500', 'focus' => 'focus:border-red-500', 'btn' => 'bg-red-500', 'text' => 'text-white'],
                        ];
                    @endphp

                    <!-- Form Pencarian & Filter -->
                    <div x-data="{ 
                        selectedPecahan: '{{ $selectedPecahan }}',
                        themes: {{ json_encode($themeClasses) }},
                        get currentTheme() { return this.themes[this.selectedPecahan] || null }
                    }" 
                    class="bg-white border-gray-100 shadow-sm rounded-xl mb-8 overflow-hidden border-t-4 transition-all duration-500"
                    :class="currentTheme ? currentTheme.border : 'border-gray-100'">
                        <form action="{{ route('hcs-receiving.index') }}" method="GET">
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-x-4 gap-y-3 items-end">
                                    
                                    {{-- Input Pencarian Utama --}}
                                    <div class="md:col-span-2">
                                        <label for="search" class="block text-[9px] font-black text-gray-400 uppercase tracking-[0.15em] mb-1.5 ml-1">Cari Data</label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="w-3.5 h-3.5 text-gray-400 transition-colors" 
                                                     :class="currentTheme ? currentTheme.text.replace('text-', 'text-opacity-50 text-') : 'group-focus-within:text-indigo-500'"
                                                     fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                            </div>
                                            <input id="search" name="search" type="text" 
                                                class="pl-9 block w-full border-gray-200 rounded-lg shadow-sm text-xs py-2 transition-all text-center filter-input" 
                                                :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'"
                                                value="{{ request('search') }}" placeholder="No. Bon / Batch" />
                                        </div>
                                    </div>

                                    {{-- Filter Pecahan --}}
                                    <div class="md:col-span-1">
                                        <label for="pecahan" class="block text-[9px] font-black text-gray-400 uppercase tracking-[0.15em] mb-1.5 text-center">Pecahan</label>
                                        <select id="pecahan" name="pecahan" x-model="selectedPecahan"
                                                class="block w-full border-gray-200 rounded-lg shadow-sm text-xs py-2 text-center transition-all duration-300 filter-input appearance-none" 
                                                :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'">
                                            <option value="">Semua</option>
                                            @foreach(['S'=>'1.000','T'=>'2.000','U'=>'5.000','V'=>'10.000','W'=>'20.000','X'=>'50.000','Y'=>'100.000'] as $key => $val)
                                                <option value="{{ $key }}">{{ $key }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Filter Supplier --}}
                                    <div class="md:col-span-1">
                                        <label for="supplier" class="block text-[9px] font-black text-gray-400 uppercase tracking-[0.15em] mb-1.5 text-center">Supplier</label>
                                        <select id="supplier" name="supplier" 
                                                class="block w-full border-gray-200 rounded-lg shadow-sm text-xs py-2 text-center transition-all duration-300 filter-input appearance-none" 
                                                :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'">
                                            <option value="">Semua</option>
                                            <option value="Cutpack" {{ request('supplier') == 'Cutpack' ? 'selected' : '' }}>CP</option>
                                            <option value="Rikyet" {{ request('supplier') == 'Rikyet' ? 'selected' : '' }}>RK</option>
                                        </select>
                                    </div>

                                    {{-- Filter Tanggal Mulai --}}
                                    <div class="md:col-span-1">
                                        <label for="start_date" class="block text-[9px] font-black text-gray-400 uppercase tracking-[0.15em] mb-1.5 text-center">Dari</label>
                                        <input id="start_date" name="start_date" type="date" 
                                               class="block w-full border-gray-200 rounded-lg shadow-sm text-[10px] py-2 px-1 text-center transition-all duration-300 filter-input" 
                                               :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'"
                                               value="{{ request('start_date') }}" />
                                    </div>

                                    {{-- Filter Tanggal Selesai --}}
                                    <div class="md:col-span-1">
                                        <label for="end_date" class="block text-[9px] font-black text-gray-400 uppercase tracking-[0.15em] mb-1.5 text-center">Sampai</label>
                                        <input id="end_date" name="end_date" type="date" 
                                               class="block w-full border-gray-200 rounded-lg shadow-sm text-[10px] py-2 px-1 text-center transition-all duration-300 filter-input" 
                                               :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'"
                                               value="{{ request('end_date') }}" />
                                    </div>

                                    {{-- Tombol Aksi Filter --}}
                                    <div class="md:col-span-2 flex gap-2">
                                        @if(request()->anyFilled(['search', 'start_date', 'end_date', 'pecahan', 'supplier']))
                                            <a href="{{ route('hcs-receiving.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-50 hover:bg-gray-100 text-gray-400 rounded-lg transition-all border border-gray-200" title="Reset Filter">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                            </a>
                                        @endif
                                        <button type="submit" id="search-btn"
                                            class="flex-1 inline-flex items-center justify-center px-6 py-2 rounded-lg text-xs font-black transition-all shadow-sm active:scale-95 uppercase tracking-widest"
                                            :class="currentTheme ? (currentTheme.btn + ' ' + currentTheme.text + ' brightness-95 hover:brightness-105') : 'bg-indigo-600 text-white hover:bg-indigo-700'">
                                            <svg class="w-3.5 h-3.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                            <span>Tampilkan Data</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Tag Filter yang Sedang Aktif --}}
                            @if(request()->anyFilled(['search', 'start_date', 'end_date', 'pecahan', 'supplier']))
                                <div class="px-6 pb-6 pt-0 flex flex-wrap gap-2">
                                    @if(request('search'))
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                                            Search: <span class="font-bold ml-1 italic">{{ request('search') }}</span>
                                        </span>
                                    @endif
                                    @if(request('pecahan'))
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                            Pecahan: <span class="font-bold ml-1 uppercase">{{ request('pecahan') }}</span>
                                        </span>
                                    @endif
                                    @if(request('supplier'))
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-100">
                                            Supplier: <span class="font-bold ml-1">{{ request('supplier') }}</span>
                                        </span>
                                    @endif
                                    @if(request('start_date'))
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-rose-50 text-rose-700 border border-rose-100">
                                            Mulai: <span class="font-bold ml-1">{{ \Carbon\Carbon::parse(request('start_date'))->translatedFormat('d M Y') }}</span>
                                        </span>
                                    @endif
                                    @if(request('end_date'))
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-rose-50 text-rose-700 border border-rose-100">
                                            Sampai: <span class="font-bold ml-1">{{ \Carbon\Carbon::parse(request('end_date'))->translatedFormat('d M Y') }}</span>
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th scope="col" class="px-2 py-4 sm:px-4 text-left text-[10px] sm:text-xs font-bold text-gray-500 uppercase tracking-widest">
                                        <a href="{{ route('hcs-receiving.index', array_merge(request()->query(), ['sort_by' => 'tanggal_penerimaan', 'sort_direction' => request('sort_by') === 'tanggal_penerimaan' && request('sort_direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-indigo-600 transition-colors">
                                            Tanggal
                                            @if(request('sort_by') === 'tanggal_penerimaan')
                                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ request('sort_direction') === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path></svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-2 py-4 sm:px-4 text-left text-[10px] sm:text-xs font-bold text-gray-500 uppercase tracking-widest">
                                        <a href="{{ route('hcs-receiving.index', array_merge(request()->query(), ['sort_by' => 'nomor_bon', 'sort_direction' => request('sort_by') === 'nomor_bon' && request('sort_direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-indigo-600 transition-colors">
                                            No. Bon
                                            @if(request('sort_by') === 'nomor_bon')
                                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ request('sort_direction') === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path></svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-2 py-4 sm:px-4 text-left text-[10px] sm:text-xs font-bold text-gray-500 uppercase tracking-widest">
                                        <a href="{{ route('hcs-receiving.index', array_merge(request()->query(), ['sort_by' => 'pecahan', 'sort_direction' => request('sort_by') === 'pecahan' && request('sort_direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-indigo-600 transition-colors">
                                            Pecahan
                                            @if(request('sort_by') === 'pecahan')
                                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ request('sort_direction') === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path></svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-2 py-4 sm:px-4 text-left text-[10px] sm:text-xs font-bold text-gray-500 uppercase tracking-widest">
                                        <a href="{{ route('hcs-receiving.index', array_merge(request()->query(), ['sort_by' => 'jumlah', 'sort_direction' => request('sort_by') === 'jumlah' && request('sort_direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-indigo-600 transition-colors">
                                            Bilyet
                                            @if(request('sort_by') === 'jumlah')
                                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ request('sort_direction') === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path></svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-2 py-4 sm:px-4 text-left text-[10px] sm:text-xs font-bold text-gray-500 uppercase tracking-widest text-center">
                                        TA/TE
                                    </th>
                                    <th scope="col" class="px-2 py-4 sm:px-4 text-left text-[10px] sm:text-xs font-bold text-gray-500 uppercase tracking-widest">
                                        <a href="{{ route('hcs-receiving.index', array_merge(request()->query(), ['sort_by' => 'batch', 'sort_direction' => request('sort_by') === 'batch' && request('sort_direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-indigo-600 transition-colors">
                                            Batch
                                            @if(request('sort_by') === 'batch')
                                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ request('sort_direction') === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path></svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-2 py-4 sm:px-4 text-left text-[10px] sm:text-xs font-bold text-gray-500 uppercase tracking-widest">
                                        <a href="{{ route('hcs-receiving.index', array_merge(request()->query(), ['sort_by' => 'supplier', 'sort_direction' => request('sort_by') === 'supplier' && request('sort_direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-indigo-600 transition-colors">
                                            Supplier
                                            @if(request('sort_by') === 'supplier')
                                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ request('sort_direction') === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path></svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-2 py-4 sm:px-4 text-left text-[10px] sm:text-xs font-bold text-gray-500 uppercase tracking-widest">Operator</th>
                                    <th scope="col" class="px-2 py-4 sm:px-4 text-center text-[10px] sm:text-xs font-bold text-gray-500 uppercase tracking-widest">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($receivings->groupBy(function($item) { return $item->batch . ' / ' . $item->seri . ' / ' . $item->pecahan; }) as $groupKey => $groupItems)
                                    <!-- Group Header Row -->
                                    <tr class="bg-indigo-50/50 border-t border-b border-indigo-100/50">
                                        <td colspan="9" class="px-4 py-3 sm:px-6 text-[10px] sm:text-sm font-bold text-indigo-400">
                                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center w-full gap-1 sm:gap-4">
                                                <span>{{ $groupKey }}</span>
                                                <span class="font-mono text-indigo-700">Total: {{ number_format($groupItems->sum('jumlah'), 0, ',', '.') }} Bilyet</span>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <!-- Group Data Rows -->
                                    @foreach($groupItems as $receiving)
                                        <tr class="hover:bg-indigo-50/30 transition-colors">
                                            <td class="px-2 py-4 sm:px-4 whitespace-nowrap text-[10px] sm:text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($receiving->tanggal_penerimaan)->locale('id')->isoFormat('D MMMM YYYY') }}
                                            </td>
                                            <td class="px-2 py-4 sm:px-4 whitespace-nowrap text-[10px] sm:text-sm font-semibold text-gray-900">
                                                {{ $receiving->nomor_bon }}
                                            </td>
                                            <td class="px-2 py-4 sm:px-4 whitespace-nowrap text-[10px] sm:text-sm">
                                                @php
                                                    $pecahanColors = [
                                                        'S' => 'bg-lime-50 text-lime-700 border-lime-200 dark:bg-lime-900/20 dark:text-lime-400 dark:border-lime-800/40',
                                                        'T' => 'bg-slate-50 text-slate-700 border-slate-200 dark:bg-slate-800/20 dark:text-slate-400 dark:border-slate-700/40',
                                                        'U' => 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-900/20 dark:text-amber-400 dark:border-amber-800/40',
                                                        'V' => 'bg-purple-50 text-purple-700 border-purple-200 dark:bg-purple-900/20 dark:text-purple-400 dark:border-purple-800/40',
                                                        'W' => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-900/20 dark:text-emerald-400 dark:border-emerald-800/40',
                                                        'X' => 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-900/20 dark:text-blue-400 dark:border-blue-800/40',
                                                        'Y' => 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-900/20 dark:text-rose-400 dark:border-rose-800/40',
                                                    ];
                                                    $badgeClass = $pecahanColors[$receiving->pecahan] ?? 'bg-gray-100 text-gray-700 border-gray-200 dark:bg-gray-800/20 dark:text-gray-400 dark:border-gray-700/40';
                                                @endphp
                                                <span class="px-2 py-0.5 sm:px-3 sm:py-1 rounded-md text-[9px] sm:text-[10px] font-black border uppercase tracking-wider {{ $badgeClass }}">
                                                    {{ $receiving->pecahan }}
                                                </span>
                                            </td>
                                            <td class="px-2 py-4 sm:px-4 whitespace-nowrap text-[10px] sm:text-sm font-mono text-indigo-700 font-bold">{{ number_format($receiving->jumlah, 0, ',', '.') }}</td>
                                            <td class="px-2 py-4 sm:px-4 whitespace-nowrap text-[10px] sm:text-sm text-center font-bold text-gray-600">
                                                <div class="text-[8px] sm:text-[10px] text-gray-400 leading-none mb-1">{{ $receiving->tahun_anggaran }}</div>
                                                <div class="text-[10px] sm:text-xs">{{ $receiving->emisi }}</div>
                                            </td>
                                            <td class="px-2 py-4 sm:px-4 whitespace-nowrap text-[10px] sm:text-sm font-mono text-gray-600">
                                                <div>{{ $receiving->batch }}</div>
                                                @if(isset($receiving->packs) && $receiving->packs->whereNotNull('hcs_sorting_id')->isNotEmpty())
                                                    <div class="mt-1">
                                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] bg-red-100 text-red-600 border border-red-200 uppercase tracking-tighter">
                                                            ADA PACK TELAH TERSORTIR
                                                        </span>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-2 py-4 sm:px-4 whitespace-nowrap text-[10px] sm:text-sm text-gray-500">
                                                <span class="px-2 py-0.5 inline-flex text-[10px] sm:text-xs leading-5 font-semibold rounded-full {{ $receiving->supplier === 'Cutpack' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                    {{ $receiving->supplier }}
                                                </span>
                                            </td>
                                            <td class="px-2 py-4 sm:px-4 whitespace-nowrap text-[10px] sm:text-sm text-gray-600 font-medium">{{ $receiving->user->name ?? '-' }}</td>
                                            <td class="px-2 py-4 sm:px-4 whitespace-nowrap text-center text-[10px] sm:text-sm font-medium">
                                                @if(in_array(auth()->user()->role, ['sortir', 'admin']))
                                                    @php $hasSortedPacks = $receiving->packs->whereNotNull('hcs_sorting_id')->isNotEmpty(); @endphp
                                                    <div class="flex justify-center items-center space-x-4">
                                                        <a href="{{ route('hcs-receiving.create', ['batch' => $receiving->batch, 'seri' => $receiving->seri, 'pecahan' => $receiving->pecahan, 'emisi' => $receiving->emisi, 'tahun_anggaran' => $receiving->tahun_anggaran]) }}" class="text-green-600 hover:text-green-800 transition-all hover:scale-110" title="Input di batch ini">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                        </a>
                                                        @if($hasSortedPacks)
                                                            <a href="{{ route('hcs-receiving.edit', $receiving->id) }}" class="text-orange-600 hover:text-orange-800 transition-all hover:scale-110" title="Edit Data (Sebagian Terkunci)">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                                </svg>
                                                            </a>
                                                            <button type="button" class="text-red-600 opacity-50 cursor-not-allowed transition-colors" title="Hapus Data (Disabled)" onclick="alertSorted()">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                </svg>
                                                            </button>
                                                        @else
                                                            <a href="{{ route('hcs-receiving.edit', $receiving->id) }}" class="text-indigo-600 hover:text-indigo-800 transition-all hover:scale-110" title="Edit Data">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                                </svg>
                                                            </a>
                                                            <form id="delete-form-{{ $receiving->id }}" action="{{ route('hcs-receiving.destroy', $receiving->id) }}" method="POST" class="inline-block">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button" class="text-red-600 hover:text-red-900 transition-colors" title="Hapus Data" onclick="confirmDelete('{{ $receiving->id }}')">
                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                    </svg>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="10" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">Belum ada data penerimaan HCS.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $receivings->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data ini akan dihapus beserta seluruh pack yang terkait!",
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
            })
        }

        function alertSorted() {
            Swal.fire({
                title: 'Aksi Ditolak',
                text: 'Data pack sesuai batch, seri, dan pecahan ini sudah disortir. Anda tidak dapat menghapusnya.',
                icon: 'error',
                confirmButtonColor: '#4f46e5'
            });
        }
    </script>
    @endpush

    @push('css')
    <style>
        /* Penyeragaman Tinggi & Penyelarasan */
        #search, #pecahan, #supplier, #start_date, #end_date, #search-btn {
            height: 38px !important;
            box-sizing: border-box;
        }

        /* Perbaikan Tampilan Mode Terang - Visibilitas Lebih Tajam */
        body.light-mode .filter-input {
            border: 1px solid #9ca3af; /* Removed !important to allow dynamic theme colors */
            background-color: #ffffff !important;
        }
        
        body.light-mode .bg-white.border-gray-100.shadow-sm.rounded-xl {
            border: 1px solid #d1d5db; /* Removed !important */
            border-top-width: 4px !important; /* Keep width fixed */
            /* Removed border-top color !important to allow dynamic theme colors */
        }

        body.light-mode label {
            color: #374151 !important; /* Darker Gray-700 label text for sharp contrast */
            font-weight: 700 !important;
        }

        /* Efek Hover untuk Interaksi di Mode Terang */
        body.light-mode .filter-input:hover {
            border-color: #6b7280;
        }
    </style>
    @endpush
</x-app-layout>
