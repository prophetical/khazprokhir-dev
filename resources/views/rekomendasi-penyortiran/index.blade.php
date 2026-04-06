<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Rekomendasi Penyortiran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @php
                $pecahanData = [
                    'S' => ['label' => 'S', 'color' => 'bg-lime-500', 'border' => 'border-lime-100', 'hover' => 'hover:shadow-lime-200', 'text' => 'text-white'],
                    'T' => ['label' => 'T', 'color' => 'bg-gray-400', 'border' => 'border-gray-200', 'hover' => 'hover:shadow-gray-200'],
                    'U' => ['label' => 'U', 'color' => 'bg-amber-400', 'border' => 'border-amber-100', 'hover' => 'hover:shadow-amber-200'],
                    'V' => ['label' => 'V', 'color' => 'bg-purple-500', 'border' => 'border-purple-100', 'hover' => 'hover:shadow-purple-200'],
                    'W' => ['label' => 'W', 'color' => 'bg-green-500', 'border' => 'border-green-100', 'hover' => 'hover:shadow-green-200'],
                    'X' => ['label' => 'X', 'color' => 'bg-blue-500', 'border' => 'border-blue-100', 'hover' => 'hover:shadow-blue-200'],
                    'Y' => ['label' => 'Y', 'color' => 'bg-red-500', 'border' => 'border-red-100', 'hover' => 'hover:shadow-red-200'],
                ];
            @endphp

            <!-- Bagian Ringkasan -->
            <div class="space-y-4 mb-8">
                <!-- Baris 1: S, T, U, V -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach(['S', 'T', 'U', 'V'] as $p)
                        <div
                            class="bg-white p-4 rounded-xl shadow-sm border {{ $pecahanData[$p]['border'] }} {{ $pecahanData[$p]['hover'] }} transition-all duration-300 group">
                            <div class="flex items-center justify-between mb-2">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold text-white {{ $pecahanData[$p]['color'] }} shadow-sm">
                                    {{ $p }}
                                </span>
                                <span class="text-[8px] font-black text-gray-400 uppercase tracking-tighter">PECAHAN
                                    {{ $pecahanData[$p]['label'] }}</span>
                            </div>
                            <div class="text-xl font-black text-gray-900 leading-none mb-1">
                                {{ number_format($summaries[$p]['total_pack'] ?? 0, 0, ',', '.') }}
                                <span class="text-[10px] text-gray-400 font-medium lowercase">PACK</span>
                            </div>
                            @if(($summaries[$p]['total_bilyet'] ?? 0) > 0)
                                <div
                                    class="text-[9px] font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded-full inline-block">
                                    {{ number_format(($summaries[$p]['total_bilyet'] ?? 0), 0, ',', '.') }} <span
                                        class="text-[7px] text-green-400 uppercase">Bilyet</span>
                                </div>
                            @else
                                <div class="text-[9px] font-medium text-gray-300 italic px-2 py-0.5">TIDAK ADA PACK SIAP SORTIR
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Baris 2: W, X, Y & TOTAL -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach(['W', 'X', 'Y'] as $p)
                        <div
                            class="bg-white p-4 rounded-xl shadow-sm border {{ $pecahanData[$p]['border'] }} {{ $pecahanData[$p]['hover'] }} transition-all duration-300 group">
                            <div class="flex items-center justify-between mb-2">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold text-white {{ $pecahanData[$p]['color'] }} shadow-sm">
                                    {{ $p }}
                                </span>
                                <span class="text-[8px] font-black text-gray-400 uppercase tracking-tighter">PECAHAN
                                    {{ $pecahanData[$p]['label'] }}</span>
                            </div>
                            <div class="text-xl font-black text-gray-900 leading-none mb-1">
                                {{ number_format($summaries[$p]['total_pack'] ?? 0, 0, ',', '.') }}
                                <span class="text-[10px] text-gray-400 font-medium lowercase">PACK</span>
                            </div>
                            @if(($summaries[$p]['total_bilyet'] ?? 0) > 0)
                                <div
                                    class="text-[9px] font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded-full inline-block">
                                    {{ number_format(($summaries[$p]['total_bilyet'] ?? 0), 0, ',', '.') }} <span
                                        class="text-[7px] text-green-400 uppercase">Bilyet</span>
                                </div>
                            @else
                                <div class="text-[9px] font-medium text-gray-300 italic px-2 py-0.5">TIDAK ADA PACK SIAP SORTIR
                                </div>
                            @endif
                        </div>
                    @endforeach

                    <!-- Grand Total Card -->
                    <div
                        class="bg-indigo-600 p-4 rounded-xl shadow-md border border-indigo-500 hover:shadow-lg transition-all duration-300 group">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-indigo-100 uppercase tracking-widest">Total Siap
                                Sortir</span>
                            <div class="p-1.5 bg-white/20 rounded-lg text-white">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="text-xl font-black text-white leading-none mb-1">
                            {{ number_format($totalAllPacks, 0, ',', '.') }} <span
                                class="text-[10px] text-indigo-200 font-medium lowercase">pack</span>
                        </div>
                        <div class="text-[10px] font-bold text-white bg-white/30 px-2 py-0.5 rounded-full inline-block">
                            {{ number_format($totalAllBilyet, 0, ',', '.') }} <span
                                class="text-[8px] text-indigo-100 uppercase">Bilyet</span>
                        </div>
                    </div>
                </div>
            </div>

            @php
                $selectedPecahan = request('pecahan', '');

                // Literalnya untuk semua kelas tema yang mungkin untuk memastikan Tailwind JIT menyertakan.
                $themeClasses = [
                    'S' => ['bg' => 'bg-lime-500', 'border' => 'border-lime-500', 'ring' => 'focus:ring-lime-500', 'focus' => 'focus:border-lime-500', 'btn' => 'bg-lime-500', 'text' => 'text-white'],
                    'T' => ['bg' => 'bg-gray-400', 'border' => 'border-gray-400', 'ring' => 'focus:ring-gray-400', 'focus' => 'focus:border-gray-400', 'btn' => 'bg-gray-400', 'text' => 'text-white'],
                    'U' => ['bg' => 'bg-amber-400', 'border' => 'border-amber-400', 'ring' => 'focus:ring-amber-400', 'focus' => 'focus:border-amber-400', 'btn' => 'bg-amber-400', 'text' => 'text-gray-900'],
                    'V' => ['bg' => 'bg-purple-500', 'border' => 'border-purple-500', 'ring' => 'focus:ring-purple-500', 'focus' => 'focus:border-purple-500', 'btn' => 'bg-purple-500', 'text' => 'text-white'],
                    'W' => ['bg' => 'bg-green-500', 'border' => 'border-green-500', 'ring' => 'focus:ring-green-500', 'focus' => 'focus:border-green-500', 'btn' => 'bg-green-500', 'text' => 'text-white'],
                    'X' => ['bg' => 'bg-blue-500', 'border' => 'border-blue-500', 'ring' => 'focus:ring-blue-500', 'focus' => 'focus:border-blue-500', 'btn' => 'bg-blue-500', 'text' => 'text-white'],
                    'Y' => ['bg' => 'bg-red-500', 'border' => 'border-red-500', 'ring' => 'focus:ring-red-500', 'focus' => 'focus:border-red-500', 'btn' => 'bg-red-500', 'text' => 'text-white'],
                ];
            @endphp

            <!-- Filter Section -->
            <div x-data="{ 
                selectedPecahan: '{{ $selectedPecahan }}',
                themes: {{ json_encode($themeClasses) }},
                get currentTheme() { return this.themes[this.selectedPecahan] || null }
            }" class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border-t-4 transition-all duration-500"
                :class="currentTheme ? currentTheme.border : 'border-transparent'">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 shadow-sm transition-all duration-500"
                            :class="currentTheme ? currentTheme.bg : 'bg-gray-50'">
                            <svg class="w-5 h-5 transition-colors duration-500"
                                :class="currentTheme ? currentTheme.text : 'text-indigo-600'" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-gray-700 uppercase tracking-widest">
                            Filter Pencarian
                            <template x-if="selectedPecahan">
                                <span>- <span class="text-gray-900">Pecahan <span
                                            x-text="selectedPecahan"></span></span></span>
                            </template>
                        </h3>
                    </div>

                    <form method="GET" action="{{ route('rekomendasi-penyortiran.index') }}"
                        class="grid grid-cols-1 md:grid-cols-6 gap-4 items-end">
                        <div class="relative">
                            <label for="pecahan"
                                class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1 ml-1">Pecahan</label>
                            <select name="pecahan" id="pecahan" x-model="selectedPecahan"
                                class="block w-full rounded-lg border-gray-200 shadow-sm text-sm py-2.5 text-center transition-all duration-300"
                                :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'">
                                <option value="">Semua</option>
                                @foreach(['S', 'T', 'U', 'V', 'W', 'X', 'Y'] as $p)
                                    <option value="{{ $p }}">{{ $p }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="relative">
                            <label for="tahun_anggaran"
                                class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1 ml-1">TA</label>
                            <select name="tahun_anggaran" id="tahun_anggaran"
                                class="block w-full rounded-lg border-gray-200 shadow-sm text-sm py-2.5 text-center transition-all duration-300"
                                :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'">
                                <option value="">Semua</option>
                                @foreach($availableYears as $year)
                                    <option value="{{ $year }}" {{ request('tahun_anggaran') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="relative">
                            <label for="emisi"
                                class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1 ml-1">Emisi</label>
                            <select name="emisi" id="emisi"
                                class="block w-full rounded-lg border-gray-200 shadow-sm text-sm py-2.5 text-center transition-all duration-300"
                                :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'">
                                <option value="">Semua</option>
                                @foreach($availableEmissions as $emisi)
                                    <option value="{{ $emisi }}" {{ request('emisi') == $emisi ? 'selected' : '' }}>
                                        {{ $emisi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="relative">
                            <label for="batch"
                                class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1 ml-1">Batch</label>
                            <input type="text" name="batch" id="batch" value="{{ request('batch') }}"
                                class="block w-full rounded-lg border-gray-200 shadow-sm text-sm py-2.5 text-center transition-all duration-300"
                                :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'"
                                placeholder="Batch">
                        </div>
                        <div class="relative">
                            <label for="seri"
                                class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1 ml-1">Seri</label>
                            <input type="text" name="seri" id="seri" value="{{ request('seri') }}"
                                class="block w-full rounded-lg border-gray-200 shadow-sm text-sm py-2.5 text-center transition-all duration-300"
                                :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'"
                                placeholder="Seri">
                        </div>
                        <div class="flex gap-2">
                            <button type="submit"
                                class="flex-1 px-4 py-2.5 rounded-lg transition-all duration-300 text-sm font-bold shadow-lg shadow-gray-100 uppercase tracking-wider"
                                :class="currentTheme ? (currentTheme.btn + ' ' + currentTheme.text + ' brightness-90 hover:brightness-100') : 'bg-indigo-600 text-white hover:bg-indigo-700'">
                                Cari
                            </button>
                            <a href="{{ route('rekomendasi-penyortiran.index') }}"
                                class="px-4 py-2.5 bg-gray-50 text-gray-400 rounded-lg hover:bg-gray-100 border border-gray-200 transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Daftar Rekomendasi</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pec</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Batch</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Seri</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Supplier</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">
                                    Pack Rekomendasi</th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jumlah Pack</th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jumlah Bilyet</th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-40">
                                    <span class="sr-only">Aksi</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                                $currentBatchIdentifier = null;
                                $batchIndex = -1;
                            @endphp

                            @forelse ($paginator as $item)
                                @php
                                    $thisIdentifier = $item['pecahan'] . $item['batch'] . $item['seri'] . $item['supplier'];

                                    // Agregasikan semua pack yang valid untuk Batch/Seri/Supplier ini guna membuat tombol "Sortir Semua" pada baris pertamanya
                                    $allItemsForThisIdentifier = [];
                                    foreach ($paginator->items() as $pItem) {
                                        if ($pItem['pecahan'] . $pItem['batch'] . $pItem['seri'] . $pItem['supplier'] == $thisIdentifier) {
                                            $allItemsForThisIdentifier = array_merge($allItemsForThisIdentifier, $pItem['flat_packs']);
                                        }
                                    }

                                    if ($currentBatchIdentifier !== $thisIdentifier) {
                                        $currentBatchIdentifier = $thisIdentifier;
                                        $isFirstOfGroup = true;
                                        $batchIndex++;
                                    } else {
                                        $isFirstOfGroup = false;
                                    }

                                    // Warna baris bergantian berdasarkan grup Batch untuk pengelompokan visual
                                    $rowBgClass = ($batchIndex % 2 == 0) ? 'bg-white' : 'bg-gray-50/50';
                                @endphp

                                @if($isFirstOfGroup)
                                    <!-- Sortir Semua Header Row -->
                                    <tr
                                        class="bg-indigo-50/30 dark:bg-indigo-900/10 border-t-2 border-indigo-100 dark:border-indigo-900/30">
                                        <td colspan="7"
                                            class="px-6 py-2 text-xs text-indigo-800 dark:text-indigo-400 font-semibold text-right">
                                            {{ $item['pecahan'] }} - {{ $item['batch'] }} - {{ $item['seri'] }} - <span
                                                class="uppercase tracking-wider font-bold">{{ $item['supplier'] }}</span>
                                        </td>
                                        <td class="px-6 py-2 text-center">
                                            <a href="{{ route('hcs-sorting.create', ['pecahan' => $item['pecahan'], 'batch' => $item['batch'], 'seri' => $item['seri'], 'selected_packs' => implode(',', $allItemsForThisIdentifier)]) }}"
                                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-bold rounded shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 whitespace-nowrap">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                                    </path>
                                                </svg>
                                                Sortir Semua {{ $item['supplier'] }}
                                            </a>
                                        </td>
                                    </tr>
                                @endif

                                <tr class="{{ $rowBgClass }} hover:bg-gray-100 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded text-xs font-black {{ $themeClasses[$item['pecahan']]['bg'] }} {{ $themeClasses[$item['pecahan']]['text'] }} shadow-sm">
                                            {{ $item['pecahan'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $item['batch'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $item['seri'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($item['supplier'] === 'Rikyet')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                                {{ $item['supplier'] }}
                                            </span>
                                        @elseif($item['supplier'] === 'Cutpack')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                                {{ $item['supplier'] }}
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $item['supplier'] ?? 'Unknown' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td
                                        class="px-6 py-4 text-sm text-gray-900 dark:text-gray-200 font-mono tracking-wide leading-relaxed">
                                        @foreach($item['ranges_str'] as $rangeStr)
                                            <span
                                                class="inline-block bg-indigo-500 dark:bg-slate-800 rounded px-2 py-0.5 mb-1 mr-1 border border-gray-300 dark:border-slate-700 font-medium whitespace-nowrap dark:text-gray-200">{{ $rangeStr }}</span>
                                            @if(!$loop->last)
                                                <span class="text-gray-400 dark:text-gray-600 mx-1">|</span>
                                            @endif
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-right">
                                        {{ number_format($item['total_pack'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-right">
                                        {{ number_format($item['total_bilyet'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <a href="{{ route('hcs-sorting.create', ['pecahan' => $item['pecahan'], 'batch' => $item['batch'], 'seri' => $item['seri']]) }}"
                                            class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                            Sortir
                                            <svg class="ml-1.5 w-3 h-3 text-gray-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-10 whitespace-nowrap text-sm text-gray-500 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                                </path>
                                            </svg>
                                            <p class="text-gray-500">Tidak ada rekomendasi pack valid yang tersedia.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($paginator->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $paginator->links() }} {{-- Menggunakan paginasi tailwind standar Laravel --}}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>