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
                        <h3 class="text-lg font-medium text-gray-900">Data Receiving</h3>
                        @if(auth()->user()->role === 'sortir')
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

                    <!-- Search & Filter Form -->
                    <div x-data="{ 
                        selectedPecahan: '{{ $selectedPecahan }}',
                        themes: {{ json_encode($themeClasses) }},
                        get currentTheme() { return this.themes[this.selectedPecahan] || null }
                    }" 
                    class="bg-white border-gray-100 shadow-sm rounded-xl mb-8 overflow-hidden border-t-4 transition-all duration-500"
                    :class="currentTheme ? currentTheme.border : 'border-gray-100'">
                        <form action="{{ route('hcs-receiving.index') }}" method="GET">
                            <div class="p-6">
                                <div class="flex flex-col xl:flex-row gap-5 items-end">
                                    
                                    {{-- Primary Search --}}
                                    <div class="flex-1 w-full">
                                        <label for="search" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Cari Data</label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                                <svg class="w-4 h-4 text-gray-400 transition-colors" 
                                                     :class="currentTheme ? currentTheme.text.replace('text-', 'text-opacity-50 text-') : 'group-focus-within:text-indigo-500'"
                                                     fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                            </div>
                                            <input id="search" name="search" type="text" 
                                                class="pl-10 block w-full border-gray-200 rounded-lg shadow-sm text-sm py-3 transition-all text-center" 
                                                :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'"
                                                value="{{ request('search') }}" placeholder="" />
                                        </div>
                                    </div>

                                    {{-- Denomination & Supplier Group --}}
                                    <div class="grid grid-cols-2 gap-4 w-full xl:w-auto">
                                        <div class="w-full xl:w-32">
                                            <label for="pecahan" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 text-center">Pecahan</label>
                                            <select id="pecahan" name="pecahan" x-model="selectedPecahan"
                                                    class="block w-full border-gray-200 rounded-lg shadow-sm text-sm py-3 text-center transition-all duration-300"
                                                    :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'">
                                                <option value="">Semua</option>
                                                @foreach(['S'=>'1.000','T'=>'2.000','U'=>'5.000','V'=>'10.000','W'=>'20.000','X'=>'50.000','Y'=>'100.000'] as $key => $val)
                                                    <option value="{{ $key }}">{{ $key }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="w-full xl:w-32">
                                            <label for="supplier" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 text-center">Supplier</label>
                                            <select id="supplier" name="supplier" 
                                                    class="block w-full border-gray-200 rounded-lg shadow-sm text-sm py-3 text-center transition-all duration-300"
                                                    :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'">
                                                <option value="">Semua</option>
                                                <option value="Cutpack" {{ request('supplier') == 'Cutpack' ? 'selected' : '' }}>CP</option>
                                                <option value="Rikyet" {{ request('supplier') == 'Rikyet' ? 'selected' : '' }}>RK</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Date Range Group --}}
                                    <div class="grid grid-cols-2 gap-4 w-full xl:w-auto">
                                        <div class="w-full xl:w-36">
                                            <label for="start_date" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 text-center">Dari</label>
                                            <input id="start_date" name="start_date" type="date" 
                                                   class="block w-full border-gray-200 rounded-lg shadow-sm text-sm py-2.5 px-2 text-center transition-all duration-300" 
                                                   :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'"
                                                   value="{{ request('start_date') }}" />
                                        </div>
                                        <div class="w-full xl:w-36">
                                            <label for="end_date" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 text-center">Sampai</label>
                                            <input id="end_date" name="end_date" type="date" 
                                                   class="block w-full border-gray-200 rounded-lg shadow-sm text-sm py-2.5 px-2 text-center transition-all duration-300" 
                                                   :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'"
                                                   value="{{ request('end_date') }}" />
                                        </div>
                                    </div>

                                    {{-- Actions --}}
                                    <div class="flex gap-2 w-full xl:w-auto">
                                        <button type="submit" 
                                            class="flex-1 xl:flex-none inline-flex items-center justify-center px-8 py-3 rounded-lg text-sm font-bold transition-all shadow-md active:scale-95 uppercase tracking-wider"
                                            :class="currentTheme ? (currentTheme.btn + ' ' + currentTheme.text + ' brightness-95 hover:brightness-105') : 'bg-indigo-600 text-white hover:bg-indigo-700'">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg> Cari
                                        </button>
                                        @if(request()->anyFilled(['search', 'start_date', 'end_date', 'pecahan', 'supplier']))
                                            <a href="{{ route('hcs-receiving.index') }}" class="xl:flex-none inline-flex items-center justify-center px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-400 rounded-lg transition-all border border-gray-200">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Active Filter Tags --}}
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
                                    <th scope="col" class="px-4 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">
                                        <a href="{{ route('hcs-receiving.index', array_merge(request()->query(), ['sort_by' => 'tanggal_penerimaan', 'sort_direction' => request('sort_by') === 'tanggal_penerimaan' && request('sort_direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-indigo-600 transition-colors">
                                            Tanggal
                                            @if(request('sort_by') === 'tanggal_penerimaan')
                                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ request('sort_direction') === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path></svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-4 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">
                                        <a href="{{ route('hcs-receiving.index', array_merge(request()->query(), ['sort_by' => 'nomor_bon', 'sort_direction' => request('sort_by') === 'nomor_bon' && request('sort_direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-indigo-600 transition-colors">
                                            No. Bon
                                            @if(request('sort_by') === 'nomor_bon')
                                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ request('sort_direction') === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path></svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-4 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">
                                        <a href="{{ route('hcs-receiving.index', array_merge(request()->query(), ['sort_by' => 'pecahan', 'sort_direction' => request('sort_by') === 'pecahan' && request('sort_direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-indigo-600 transition-colors">
                                            Pecahan
                                            @if(request('sort_by') === 'pecahan')
                                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ request('sort_direction') === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path></svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-4 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">
                                        <a href="{{ route('hcs-receiving.index', array_merge(request()->query(), ['sort_by' => 'jumlah', 'sort_direction' => request('sort_by') === 'jumlah' && request('sort_direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-indigo-600 transition-colors">
                                            Bilyet
                                            @if(request('sort_by') === 'jumlah')
                                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ request('sort_direction') === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path></svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-4 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">
                                        <a href="{{ route('hcs-receiving.index', array_merge(request()->query(), ['sort_by' => 'batch', 'sort_direction' => request('sort_by') === 'batch' && request('sort_direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-indigo-600 transition-colors">
                                            Batch
                                            @if(request('sort_by') === 'batch')
                                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ request('sort_direction') === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path></svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-4 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">
                                        <a href="{{ route('hcs-receiving.index', array_merge(request()->query(), ['sort_by' => 'supplier', 'sort_direction' => request('sort_by') === 'supplier' && request('sort_direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-indigo-600 transition-colors">
                                            Supplier
                                            @if(request('sort_by') === 'supplier')
                                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ request('sort_direction') === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path></svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-4 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Operator</th>
                                    <th scope="col" class="px-4 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-widest">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($receivings->groupBy(function($item) { return $item->batch . ' / ' . $item->seri; }) as $groupKey => $groupItems)
                                    <!-- Group Header Row -->
                                    <tr class="bg-indigo-50 border-t border-b border-indigo-100">
                                        <td colspan="8" class="px-6 py-3 text-sm font-bold text-indigo-900">
                                            {{ $groupKey }}
                                        </td>
                                    </tr>
                                    
                                    <!-- Group Data Rows -->
                                    @foreach($groupItems as $receiving)
                                        <tr class="hover:bg-indigo-50/30 transition-colors">
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">{{ $receiving->tanggal_penerimaan }}</td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                                {{ $receiving->nomor_bon }}
                                                @if(isset($receiving->packs) && $receiving->packs->whereNotNull('hcs_sorting_id')->isNotEmpty())
                                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                        Ada Pack Disortir
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm">
                                                @php
                                                    $pecahanColors = [
                                                        'S' => 'bg-stone-100 text-stone-700 border-stone-200',
                                                        'T' => 'bg-slate-100 text-slate-700 border-slate-200',
                                                        'U' => 'bg-orange-100 text-orange-700 border-orange-200',
                                                        'V' => 'bg-purple-100 text-purple-700 border-purple-200',
                                                        'W' => 'bg-green-100 text-green-700 border-green-200',
                                                        'X' => 'bg-blue-100 text-blue-700 border-blue-200',
                                                        'Y' => 'bg-red-100 text-red-700 border-red-200',
                                                    ];
                                                    $badgeClass = $pecahanColors[$receiving->pecahan] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                                                @endphp
                                                <span class="px-3 py-1 rounded-md text-xs font-bold border {{ $badgeClass }}">
                                                    {{ $receiving->pecahan }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm font-mono text-indigo-700 font-bold">{{ number_format($receiving->jumlah, 0, ',', '.') }}</td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm font-mono text-gray-600">{{ $receiving->batch }}</td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ $receiving->supplier === 'Cutpack' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                    {{ $receiving->supplier }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">{{ $receiving->user->name ?? '-' }}</td>
                                            <td class="px-4 py-4 whitespace-nowrap text-center text-sm font-medium">
                                                @if(auth()->user()->role === 'sortir')
                                                    @php $hasSortedPacks = $receiving->packs->whereNotNull('hcs_sorting_id')->isNotEmpty(); @endphp
                                                    <div class="flex justify-center items-center space-x-4">
                                                        <a href="{{ route('hcs-receiving.create', ['batch' => $receiving->batch, 'seri' => $receiving->seri, 'pecahan' => $receiving->pecahan]) }}" class="text-green-600 hover:text-green-800 transition-all hover:scale-110" title="Input di batch ini">
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
                                        <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">Belum ada data receiving.</td>
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
                text: "Data ini akan dihapus beserta seluruh packs dan stock ledgernya!",
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
                text: 'Data pack sesuai batch, seri, dan pecahan ini sudah disortir. Anda tidak dapat mengedit ataupun menghapusnya.',
                icon: 'error',
                confirmButtonColor: '#4f46e5'
            });
        }
    </script>
    @endpush
</x-app-layout>
