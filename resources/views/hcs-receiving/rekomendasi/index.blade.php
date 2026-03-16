<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Rekomendasi Penerimaan HCS') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-6 text-gray-900">

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
                    }" class="mb-8 bg-white p-6 rounded-xl border-t-4 shadow-sm transition-all duration-500"
                        :class="currentTheme ? currentTheme.border : 'border-gray-100'">
                        <form method="GET" action="{{ route('rekomendasi-penerimaan.index') }}">
                            <div class="flex flex-col md:flex-row gap-5 items-end">
                                <div class="w-full md:w-1/4">
                                    <label for="pecahan"
                                        class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 text-center">Pecahan</label>
                                    <select name="pecahan" id="pecahan" x-model="selectedPecahan"
                                        class="block w-full border-gray-200 rounded-lg shadow-sm text-sm py-3 text-center transition-all duration-300 font-bold"
                                        :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'">
                                        <option value="">Semua</option>
                                        @foreach(['S', 'T', 'U', 'V', 'W', 'X', 'Y'] as $p)
                                            <option value="{{ $p }}">{{ $p }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="w-full md:w-1/4">
                                    <label for="batch"
                                        class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 text-center">Batch</label>
                                    <input id="batch" name="batch" type="text"
                                        class="block w-full border-blue-200 rounded-lg shadow-sm text-sm py-3 text-center transition-all duration-300"
                                        :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'"
                                        value="{{ request('batch') }}" placeholder="Batch" />
                                </div>
                                <div class="w-full md:w-1/4">
                                    <label for="seri"
                                        class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 text-center">Seri</label>
                                    <input id="seri" name="seri" type="text"
                                        class="block w-full border-gray-200 rounded-lg shadow-sm text-sm py-3 text-center transition-all duration-300"
                                        :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'"
                                        value="{{ request('seri') }}" placeholder="Seri" />
                                </div>
                                <div class="w-full md:w-1/4 flex gap-2">
                                    <button type="submit"
                                        class="flex-1 inline-flex justify-center items-center px-4 py-3 rounded-lg font-bold text-xs uppercase tracking-widest transition-all shadow-md active:scale-95 text-white"
                                        :class="currentTheme ? (currentTheme.btn + ' brightness-95 hover:brightness-105') : 'bg-indigo-600 hover:bg-indigo-700'">
                                        Cari
                                    </button>
                                    <a href="{{ route('rekomendasi-penerimaan.index') }}"
                                        class="flex-1 inline-flex justify-center items-center px-4 py-3 bg-gray-100 border border-gray-200 rounded-lg font-bold text-xs text-gray-400 uppercase tracking-widest shadow-sm hover:bg-gray-200 transition-all text-center">
                                        Reset
                                    </a>
                                </div>
                            </div>

                            <!-- Action Buttons & Legend -->
                            <div
                                class="mt-6 pt-4 border-t border-gray-100 flex flex-wrap items-center justify-between gap-4">
                                <!-- Legend -->
                                <div
                                    class="flex flex-wrap items-center gap-4 bg-gray-50/50 px-4 py-2 rounded-lg border border-gray-100">
                                    <span
                                        class="text-[10px] font-black uppercase tracking-widest text-gray-400">Keterangan:</span>
                                    <div class="flex items-center gap-2">
                                        <div class="w-3 h-3 rounded bg-emerald-500 border border-emerald-600"></div>
                                        <span
                                            class="text-[10px] font-bold text-gray-600 uppercase tracking-tighter">Pack
                                            dari Cutpack</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="w-3 h-3 rounded bg-sky-500 border border-sky-600"></div>
                                        <span
                                            class="text-[10px] font-bold text-gray-600 uppercase tracking-tighter">Pack
                                            dari Rikyet</span>
                                    </div>
                                </div>

                                <div class="flex flex-wrap gap-2 justify-end">
                                    <a href="{{ route('rekomendasi-penerimaan.export', request()->all()) }}"
                                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg hover:bg-emerald-100 transition-colors shadow-sm"
                                        title="Export Excel">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Excel
                                    </a>
                                    <a href="{{ route('rekomendasi-penerimaan.print', request()->all()) }}"
                                        target="_blank"
                                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium bg-rose-50 text-rose-700 border border-rose-200 rounded-lg hover:bg-rose-100 transition-colors shadow-sm"
                                        title="Export PDF / Print">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        PDF
                                    </a>
                                    <a href="{{ route('rekomendasi-penerimaan.print', request()->all()) }}"
                                        target="_blank"
                                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium bg-gray-50 text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-100 transition-colors shadow-sm"
                                        title="Cetak / PDF">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 00-2 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                        Print
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto rounded-xl border border-gray-100 shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                @php
                                    $headers = ['Pecahan', 'Batch', 'Seri', 'Tahun Anggaran', 'Emisi', 'Pack Existing', 'Pack Rekomendasi', 'Aksi'];
                                @endphp
                                <tr>
                                    @foreach($headers as $header)
                                        <th scope="col"
                                            class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest {{ $header === 'Aksi' ? 'text-center' : '' }}">
                                            {{ $header }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            @php
                                $colorMap = [
                                    'S' => 'bg-lime-500 border-lime-600 text-white',
                                    'T' => 'bg-gray-400 border-gray-500 text-white',
                                    'U' => 'bg-amber-400 border-amber-500 text-white',
                                    'V' => 'bg-purple-500 border-purple-600 text-white',
                                    'W' => 'bg-green-500 border-green-600 text-white',
                                    'X' => 'bg-blue-500 border-blue-600 text-white',
                                    'Y' => 'bg-red-500 border-red-600 text-white',
                                ];
                            @endphp
                            @forelse($batches as $batch)
                                                        <tr class="hover:bg-indigo-50/30 transition-colors">
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <span
                                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $colorMap[$batch->pecahan] ?? 'bg-gray-100 text-gray-800' }}">
                                                                    {{ $batch->pecahan }}
                                                                </span>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                                                {{ $batch->batch }}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 italic font-medium">
                                                                {{ $batch->seri }}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-700">
                                                                {{ $batch->tahun_anggaran }}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-700">
                                                                {{ $batch->emisi }}</td>
                                                            <td class="px-6 py-4">
                                                                <div class="flex flex-wrap gap-2 max-w-[280px]">
                                                                    @forelse($batch->existing_unsorted_single as $pack)
                                                                        <div x-data="{ open: false }" class="relative">
                                                                            <span @mouseenter="open = true" @mouseleave="open = false"
                                                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg shadow-sm text-[10px] font-black cursor-pointer transition-all duration-300 transform hover:scale-110 hover:-translate-y-0.5 {{ $pack['supplier'] === 'Cutpack' ? 'bg-emerald-500 text-white border border-emerald-600' : 'bg-sky-500 text-white border border-sky-600' }}">
                                                                                {{ $pack['number'] }}
                                                                            </span>
                                                                            <!-- Tooltip -->
                                                                            <div x-show="open" x-transition:enter="transition ease-out duration-200"
                                                                                x-transition:enter-start="opacity-0 translate-y-1"
                                                                                x-transition:enter-end="opacity-100 translate-y-0"
                                                                                x-transition:leave="transition ease-in duration-150"
                                                                                x-transition:leave-start="opacity-100 translate-y-0"
                                                                                x-transition:leave-end="opacity-0 translate-y-1"
                                                                                class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 z-50 w-32 shadow-xl pointer-events-none"
                                                                                style="display: none;">
                                                                                <div
                                                                                    class="bg-gray-900 text-white rounded-lg p-2 text-[9px] text-center border border-white/10 backdrop-blur-sm">
                                                                                    <div
                                                                                        class="font-black text-rose-400 border-b border-white/10 pb-1 mb-1 uppercase tracking-tighter">
                                                                                        {{ $pack['supplier'] }} Existing</div>
                                                                                    <div class="flex flex-col gap-0.5">
                                                                                        <span class="text-white/60">Diterima Pada:</span>
                                                                                        <span
                                                                                            class="font-black text-emerald-400">{{ $pack['received_at'] }}</span>
                                                                                    </div>
                                                                                    <div
                                                                                        class="absolute top-full left-1/2 -translate-x-1/2 border-8 border-transparent border-t-gray-900">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @empty
                                                                        <span class="text-xs text-gray-400 font-medium italic">-</span>
                                                                    @endforelse
                                                                </div>
                                                            </td>
                                                            <td class="px-6 py-4">
                                                                <div class="flex flex-wrap gap-2 max-w-[280px]">
                                                                    @forelse($batch->recommended_packs as $pack)
                                                                        <div x-data="{ open: false }" class="relative">
                                                                            <span @mouseenter="open = true" @mouseleave="open = false"
                                                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg shadow-sm text-[10px] font-black cursor-pointer transition-all duration-300 transform hover:scale-110 hover:-translate-y-0.5 {{ $pack['supplier'] === 'Cutpack' ? 'bg-emerald-500 text-white border border-emerald-600' : 'bg-sky-500 text-white border border-sky-600' }}">
                                                                                {{ $pack['number'] }}
                                                                            </span>
                                                                            <!-- Tooltip -->
                                                                            <div x-show="open" x-transition:enter="transition ease-out duration-200"
                                                                                x-transition:enter-start="opacity-0 translate-y-1"
                                                                                x-transition:enter-end="opacity-100 translate-y-0"
                                                                                x-transition:leave="transition ease-in duration-150"
                                                                                x-transition:leave-start="opacity-100 translate-y-0"
                                                                                x-transition:leave-end="opacity-0 translate-y-1"
                                                                                class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 z-50 w-32 shadow-xl pointer-events-none"
                                                                                style="display: none;">
                                                                                <div
                                                                                    class="bg-gray-900 text-white rounded-lg p-2 text-[9px] text-center border border-white/10 backdrop-blur-sm">
                                                                                    <div
                                                                                        class="font-black text-sky-400 border-b border-white/10 pb-1 mb-1 uppercase tracking-tighter">
                                                                                        Rekomendasi</div>
                                                                                    <div class="flex flex-col gap-0.5">
                                                                                        <span class="text-white/60">Supplier:</span>
                                                                                        <span
                                                                                            class="font-black text-amber-400 uppercase">{{ $pack['supplier'] }}</span>
                                                                                    </div>
                                                                                    <div
                                                                                        class="absolute top-full left-1/2 -translate-x-1/2 border-8 border-transparent border-t-gray-900">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @empty
                                                                        <span class="text-xs text-gray-400 font-medium italic">-</span>
                                                                    @endforelse
                                                                </div>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                                <a href="{{ route('rekomendasi-penerimaan.show', [
                                    'pecahan' => $batch->pecahan,
                                    'batch' => $batch->batch,
                                    'seri' => $batch->seri,
                                    'tahun_anggaran' => $batch->tahun_anggaran,
                                    'emisi' => $batch->emisi
                                ]) }}"
                                                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold uppercase tracking-widest rounded-lg transition-all shadow-md active:scale-95">
                                                                    <svg class="w-3.5 h-3.5 mr-2" fill="none" stroke="currentColor"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                                    </svg>
                                                                    Detail
                                                                </a>
                                                            </td>
                                                        </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 whitespace-nowrap text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-200 mb-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                            <p class="text-gray-500 italic text-sm">Tidak ada data batch yang ditemukan.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>