<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Penyortiran HCS') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-900 uppercase">Total Jumlah Pack Siap Sortir</h3>
                    </div>

                    @php
                        $pecahanData = [
                            'S' => ['label' => 'S', 'color' => 'bg-lime-500', 'border' => 'border-lime-100', 'hover' => 'hover:shadow-lime-100', 'text' => 'text-white'],
                            'T' => ['label' => 'T', 'color' => 'bg-gray-400', 'border' => 'border-gray-100', 'hover' => 'hover:shadow-gray-100', 'text' => 'text-white'],
                            'U' => ['label' => 'U', 'color' => 'bg-amber-400', 'border' => 'border-amber-100', 'hover' => 'hover:shadow-amber-100', 'text' => 'text-white'],
                            'V' => ['label' => 'V', 'color' => 'bg-purple-500', 'border' => 'border-purple-100', 'hover' => 'hover:shadow-purple-100', 'text' => 'text-white'],
                            'W' => ['label' => 'W', 'color' => 'bg-green-500', 'border' => 'border-green-100', 'hover' => 'hover:shadow-green-100', 'text' => 'text-white'],
                            'X' => ['label' => 'X', 'color' => 'bg-blue-500', 'border' => 'border-blue-100', 'hover' => 'hover:shadow-blue-100', 'text' => 'text-white'],
                            'Y' => ['label' => 'Y', 'color' => 'bg-red-500', 'border' => 'border-red-100', 'hover' => 'hover:shadow-red-100', 'text' => 'text-white'],
                        ];
                    @endphp

                    <!-- SUMMARY CARDS -->
                    <div class="mb-8 space-y-4">
                        <!-- Row 1: S, T, U, V -->
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                            @foreach(['S', 'T', 'U', 'V'] as $p)
                                <div class="bg-white p-4 rounded-xl shadow-sm border {{ $pecahanData[$p]['border'] }} {{ $pecahanData[$p]['hover'] }} transition-all duration-300 group">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold {{ $pecahanData[$p]['color'] }} {{ $pecahanData[$p]['text'] }} shadow-sm">
                                            {{ $p }}
                                        </span>
                                        <span class="text-[8px] font-black text-gray-400 uppercase tracking-tighter">Pecahan {{ $p }}</span>
                                    </div>
                                    <div class="text-xl font-black text-gray-900 leading-none mb-1">
                                        {{ number_format($summaries[$p] ?? 0, 0, ',', '.') }}
                                        <span class="text-[10px] text-gray-400 font-medium lowercase">pack</span>
                                    </div>
                                    <div class="text-[9px] font-bold text-gray-600 bg-gray-50 px-2 py-0.5 rounded-full inline-block">
                                        {{ number_format(($summaries[$p] ?? 0) * 45000, 0, ',', '.') }} <span class="text-[7px] text-gray-400 uppercase">Bilyet</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!-- Row 2: W, X, Y & Total -->
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                            @foreach(['W', 'X', 'Y'] as $p)
                                <div class="bg-white p-4 rounded-xl shadow-sm border {{ $pecahanData[$p]['border'] }} {{ $pecahanData[$p]['hover'] }} transition-all duration-300 group">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold {{ $pecahanData[$p]['color'] }} {{ $pecahanData[$p]['text'] }} shadow-sm">
                                            {{ $p }}
                                        </span>
                                        <span class="text-[8px] font-black text-gray-400 uppercase tracking-tighter">Pecahan {{ $p }}</span>
                                    </div>
                                    <div class="text-xl font-black text-gray-900 leading-none mb-1">
                                        {{ number_format($summaries[$p] ?? 0, 0, ',', '.') }}
                                        <span class="text-[10px] text-gray-400 font-medium lowercase">pack</span>
                                    </div>
                                    <div class="text-[9px] font-bold text-gray-600 bg-gray-50 px-2 py-0.5 rounded-full inline-block">
                                        {{ number_format(($summaries[$p] ?? 0) * 45000, 0, ',', '.') }} <span class="text-[7px] text-gray-400 uppercase">Bilyet</span>
                                    </div>
                                </div>
                            @endforeach
                            <!-- TOTAL CARD -->
                            <div class="bg-indigo-600 p-4 rounded-xl shadow-lg border border-indigo-500 transition-all duration-300 group">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-white text-indigo-600 shadow-sm">
                                        ALL
                                    </span>
                                    <span class="text-[8px] font-black text-indigo-200 uppercase tracking-tighter">Total Keseluruhan</span>
                                </div>
                                <div class="text-xl font-black text-white leading-none mb-1">
                                    {{ number_format($totalAllPacks, 0, ',', '.') }}
                                    <span class="text-[10px] text-indigo-200 font-medium lowercase">pack</span>
                                </div>
                                <div class="text-[9px] font-bold text-white bg-white/20 px-2 py-0.5 rounded-full inline-block">
                                    {{ number_format($totalAllPacks * 45000, 0, ',', '.') }} <span class="text-[7px] text-indigo-200 uppercase">Bilyet</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    @php
                        $selectedPecahan = request('pecahan', '');
                        $themeClasses = [
                            'S' => ['bg' => 'bg-lime-500', 'border' => 'border-lime-500', 'ring' => 'focus:ring-lime-500', 'focus' => 'focus:border-lime-500', 'btn' => 'bg-lime-500', 'text' => 'text-white'],
                            'T' => ['bg' => 'bg-gray-400', 'border' => 'border-gray-400', 'ring' => 'focus:ring-gray-400', 'focus' => 'focus:border-gray-400', 'btn' => 'bg-gray-400', 'text' => 'text-white'],
                            'U' => ['bg' => 'bg-amber-400', 'border' => 'border-amber-400', 'ring' => 'focus:ring-amber-400', 'focus' => 'focus:border-amber-400', 'btn' => 'bg-amber-400', 'text' => 'text-white'],
                            'V' => ['bg' => 'bg-purple-500', 'border' => 'border-purple-500', 'ring' => 'focus:ring-purple-500', 'focus' => 'focus:border-purple-500', 'btn' => 'bg-purple-500', 'text' => 'text-white'],
                            'W' => ['bg' => 'bg-green-500', 'border' => 'border-green-500', 'ring' => 'focus:ring-green-500', 'focus' => 'focus:border-green-500', 'btn' => 'bg-green-500', 'text' => 'text-white'],
                            'X' => ['bg' => 'bg-blue-500', 'border' => 'border-blue-500', 'ring' => 'focus:ring-blue-500', 'focus' => 'focus:border-blue-500', 'btn' => 'bg-blue-500', 'text' => 'text-white'],
                            'Y' => ['bg' => 'bg-red-500', 'border' => 'border-red-500', 'ring' => 'focus:ring-red-500', 'focus' => 'focus:border-red-500', 'btn' => 'bg-red-500', 'text' => 'text-white'],
                        ];
                    @endphp

                    <!-- SEARCH & FILTER FORM -->
                    <div x-data="{ 
                        selectedPecahan: '{{ $selectedPecahan }}',
                        themes: {{ json_encode($themeClasses) }},
                        get currentTheme() { return this.themes[this.selectedPecahan] || null }
                    }" 
                    class="mb-8 bg-white p-6 rounded-xl border-t-4 shadow-sm transition-all duration-500"
                    :class="currentTheme ? currentTheme.border : 'border-gray-100'">
                        <form method="GET" action="{{ route('hcs-sorting.index') }}" class="flex flex-col md:flex-row gap-5 items-end">
                            <div class="w-full md:w-1/4">
                                <label for="pecahan" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 text-center">Pecahan</label>
                                <select name="pecahan" id="pecahan" x-model="selectedPecahan"
                                        class="block w-full border-gray-200 rounded-lg shadow-sm text-sm py-3 text-center transition-all duration-300"
                                        :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'">
                                    <option value="">Semua</option>
                                    @foreach(['S', 'T', 'U', 'V', 'W', 'X', 'Y'] as $p)
                                        <option value="{{ $p }}">{{ $p }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-full md:w-1/4">
                                <label for="batch" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 text-center">Batch</label>
                                <input id="batch" name="batch" type="text" 
                                       class="block w-full border-gray-200 rounded-lg shadow-sm text-sm py-3 text-center transition-all duration-300"
                                       :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'"
                                       value="{{ request('batch') }}" placeholder="Batch" />
                            </div>
                            <div class="w-full md:w-1/4">
                                <label for="seri" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 text-center">Seri</label>
                                <input id="seri" name="seri" type="text" 
                                       class="block w-full border-gray-200 rounded-lg shadow-sm text-sm py-3 text-center transition-all duration-300"
                                       :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'"
                                       value="{{ request('seri') }}" placeholder="Seri" />
                            </div>
                            <div class="w-full md:w-1/4 flex gap-2">
                                <button type="submit" 
                                        class="flex-1 inline-flex justify-center items-center px-4 py-3 rounded-lg font-bold text-xs uppercase tracking-widest transition-all shadow-md active:scale-95"
                                        :class="currentTheme ? (currentTheme.btn + ' ' + currentTheme.text + ' brightness-95 hover:brightness-105') : 'bg-gray-800 text-white hover:bg-gray-700'">
                                    Cari
                                </button>
                                <a href="{{ route('hcs-sorting.index') }}" 
                                   class="flex-1 inline-flex justify-center items-center px-4 py-3 bg-gray-100 border border-gray-200 rounded-lg font-bold text-xs text-gray-400 uppercase tracking-widest shadow-sm hover:bg-gray-200 transition-all text-center">
                                    Reset
                                </a>
                            </div>
                        </form>
                    </div>

                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900 border-l-4 border-indigo-600 pl-2">Daftar Data Siap Sortir</h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-400 uppercase tracking-widest">
                                        Pecahan
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-400 uppercase tracking-widest">
                                        Batch
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-400 uppercase tracking-widest">
                                        Seri
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-400 uppercase tracking-widest">
                                        Total Pack Siap Sortir
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-400 uppercase tracking-widest">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($AvailableGroups as $group)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="inline-flex items-center px-3 py-1 rounded text-xs font-black {{ $themeClasses[$group->pecahan]['bg'] }} {{ $themeClasses[$group->pecahan]['text'] }} shadow-sm">
                                                {{ $group->pecahan }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center font-medium">
                                            {{ $group->batch }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center font-medium">
                                            {{ $group->seri }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-black">
                                            {{ number_format($group->total_pack, 0, ',', '.') }}
                                            <span class="text-[10px] text-gray-400 font-medium lowercase">pack</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                            <a href="{{ route('hcs-sorting.create', ['pecahan' => $group->pecahan, 'batch' => $group->batch, 'seri' => $group->seri]) }}" 
                                               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-bold text-[10px] text-white uppercase tracking-widest hover:bg-indigo-700 active:scale-95 transition-all shadow-sm">
                                                Pilih & Sortir
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Tidak ada data HCS yang siap disortir.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $AvailableGroups->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
