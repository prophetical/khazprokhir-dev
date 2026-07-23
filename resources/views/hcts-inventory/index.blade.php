<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div>
                <h2 class="font-black text-xl text-gray-800 leading-tight tracking-tight">
                    {{ __('Persediaan HCTS') }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-6" x-data="inventoryManager()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Filter Bar -->
            <div
                class="bg-white/70 backdrop-blur-md p-4 sm:rounded-2xl border border-white shadow-sm flex flex-wrap items-center justify-between gap-4">
                <form action="{{ route('hcts-inventory.index') }}" method="GET"
                    class="flex flex-wrap items-center gap-4 w-full sm:w-auto">
                    <div class="flex items-center gap-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Filter TA:</label>
                        <select name="tahun_anggaran" onchange="this.form.submit()"
                            class="bg-gray-50 border-gray-100 rounded-xl text-xs text-center font-black focus:ring-rose-500/10 focus:border-rose-500/50 transition-all h-[38px] min-w-[120px]">
                            <option value="">Semua TA</option>
                            @foreach($availableTA as $itemTA)
                                <option value="{{ $itemTA }}" {{ $ta == $itemTA ? 'selected' : '' }}>{{ $itemTA }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Filter TE:</label>
                        <select name="tahun_emisi" onchange="this.form.submit()"
                            class="bg-gray-50 border-gray-100 rounded-xl text-xs text-center font-black focus:ring-rose-500/10 focus:border-rose-500/50 transition-all h-[38px] min-w-[120px]">
                            <option value="">Semua TE</option>
                            @foreach($availableTE as $itemTE)
                                <option value="{{ $itemTE }}" {{ $te == $itemTE ? 'selected' : '' }}>{{ $itemTE }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>

                @if($ta || $te)
                    <a href="{{ route('hcts-inventory.index') }}"
                        class="text-[9px] font-black uppercase tracking-widest text-rose-500 hover:text-rose-600 transition-colors flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Reset Filter
                    </a>
                @endif

                <div class="flex items-center gap-3">
                    <a href="{{ route('hcts-inventory.export', ['tahun_anggaran' => $ta, 'tahun_emisi' => $te]) }}"
                        class="bg-emerald-50 text-emerald-600 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widestn text-center border border-emerald-100 hover:bg-emerald-100 transition-all flex items-center gap-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export Excel
                    </a>
                    <a href="{{ route('hcts-inventory.print', ['tahun_anggaran' => $ta, 'tahun_emisi' => $te]) }}"
                        class="bg-gray-900 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest text-center hover:bg-black transition-all flex items-center gap-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Print / PDF
                    </a>
                </div>
            </div>

            <!-- Denomination Cards Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
                @php
                    $denomThemes = [
                        'S' => ['bg' => 'bg-lime-500', 'soft' => 'bg-lime-50', 'text' => 'text-lime-700', 'border' => 'border-lime-200'],
                        'T' => ['bg' => 'bg-gray-400', 'soft' => 'bg-gray-50', 'text' => 'text-gray-600', 'border' => 'border-gray-200'],
                        'U' => ['bg' => 'bg-amber-400', 'soft' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200'],
                        'V' => ['bg' => 'bg-purple-500', 'soft' => 'bg-purple-50', 'text' => 'text-purple-700', 'border' => 'border-purple-200'],
                        'W' => ['bg' => 'bg-green-500', 'soft' => 'bg-green-50', 'text' => 'text-green-700', 'border' => 'border-green-200'],
                        'X' => ['bg' => 'bg-blue-500', 'soft' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200'],
                        'Y' => ['bg' => 'bg-red-500', 'soft' => 'bg-red-50', 'text' => 'text-red-700', 'border' => 'border-red-200'],
                    ];
                @endphp

                @foreach($cardStats as $denom => $stock)
                    <div
                        class="{{ $denomThemes[$denom]['soft'] }} {{ $denomThemes[$denom]['border'] }} border p-4 rounded-3xl shadow-sm hover:shadow-md transition-all duration-300 flex flex-col items-center justify-center text-center group overflow-hidden relative">
                        <div
                            class="absolute -right-2 -top-2 w-12 h-12 {{ $denomThemes[$denom]['bg'] }} opacity-10 rounded-full group-hover:scale-150 transition-transform duration-500">
                        </div>
                        <span
                            class="w-8 h-8 rounded-full {{ $denomThemes[$denom]['bg'] }} text-white flex items-center justify-center font-black text-xs mb-2 shadow-lg shadow-{{ $denomThemes[$denom]['bg'] }}/20">{{ $denom }}</span>
                        <h4 class="text-[9px] font-black {{ $denomThemes[$denom]['text'] }} uppercase tracking-widest mb-1">
                            Pecahan {{ $denom }}</h4>
                        <p class="text-sm font-black text-gray-900 dark:text-white">{{ number_format($stock, 0, ',', '.') }}
                        </p>
                    </div>
                @endforeach

                <!-- Total Card -->
                <div
                    class="bg-gray-900 border-gray-800 border p-4 rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-300 flex flex-col items-center justify-center text-center group relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-gray-800 to-transparent opacity-50"></div>
                    <div class="relative z-10">
                        <div
                            class="w-8 h-8 rounded-full bg-rose-500 text-white flex items-center justify-center font-black text-xs mb-2 shadow-lg shadow-rose-500/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <h4 class="text-[9px] font-black text-rose-400 uppercase tracking-widest mb-1">Total Stock</h4>
                        <p class="text-sm font-black text-white">{{ number_format($totalInventory, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Summary Table Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-4 border-b border-gray-50 flex items-center justify-between">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest px-2">
                        {{ __('Rincian Persediaan HCTS Per Batch') }}
                    </h3>
                    @if($ta || $te)
                        <div
                            class="px-3 py-1 bg-rose-50 text-rose-600 rounded-full text-[9px] font-black uppercase tracking-widest border border-rose-100">
                            @if($ta) TA: {{ $ta }} @endif
                            @if($ta && $te) | @endif
                            @if($te) TE: {{ $te }} @endif
                        </div>
                    @endif
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th
                                    class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 text-center">
                                    Pecahan</th>
                                <th
                                    class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">
                                    TA/TE</th>
                                <th
                                    class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 text-right">
                                    Total Penerimaan</th>
                                <th
                                    class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 text-right">
                                    Total Penyerahan</th>
                                <th
                                    class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 text-right">
                                    Persediaan</th>
                                <th
                                    class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 text-center">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($inventory as $item)
                                <tr class="hover:bg-indigo-50/30 transition-colors group cursor-pointer"
                                    @click="toggleDetail('{{ $item->pecahan }}', '{{ $item->tahun_anggaran }}', '{{ $item->tahun_emisi }}')">
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="inline-flex items-center justify-center w-10 h-10 rounded-xl font-black text-sm text-white {{ $denomThemes[$item->pecahan]['bg'] ?? 'bg-gray-500' }} shadow-lg shadow-{{ str_replace('bg-', '', $denomThemes[$item->pecahan]['bg'] ?? 'gray-500') }}/20 uppercase">
                                            {{ $item->pecahan }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span
                                                class="text-sm font-black text-gray-700">{{ $item->tahun_anggaran }}</span>
                                            <span
                                                class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ $item->tahun_emisi }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span
                                            class="text-sm font-bold text-gray-500">{{ number_format($item->total_received, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span
                                            class="text-sm font-bold text-rose-500">{{ number_format($item->total_submitted, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div
                                            class="inline-flex items-center justify-end px-3 py-1 rounded-lg bg-emerald-50 border border-emerald-100">
                                            <span
                                                class="text-sm font-black text-emerald-600">{{ number_format($item->stock, 0, ',', '.') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center">
                                            <button class="p-2 text-indigo-400 hover:text-indigo-600 transition-colors">
                                                <svg class="w-5 h-5 transition-transform duration-200"
                                                    :class="activeKey === '{{ $item->pecahan }}-{{ $item->tahun_anggaran }}-{{ $item->tahun_emisi }}' ? 'rotate-180' : ''"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Detail Row -->
                                <tr x-show="activeKey === '{{ $item->pecahan }}-{{ $item->tahun_anggaran }}-{{ $item->tahun_emisi }}'"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 -translate-y-2"
                                    x-transition:enter-end="opacity-100 translate-y-0" class="bg-gray-50/80">
                                    <td colspan="6" class="px-12 py-6">
                                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                                            <div class="flex items-center gap-3 mb-4">
                                                <div class="p-1.5 bg-indigo-50 rounded-lg">
                                                    <svg class="w-3.5 h-3.5 text-indigo-600" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                                    </svg>
                                                </div>
                                                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                                                    {{ __('Rincian Stok Per Batch') }}
                                                </h4>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                                <template x-if="loadingDetail">
                                                    <div class="col-span-4 flex justify-center py-6">
                                                        <div
                                                            class="animate-spin rounded-full h-6 w-6 border-b-2 border-indigo-600 font-bold">
                                                        </div>
                                                    </div>
                                                </template>

                                                <template x-for="batch in activeBatchDetails" :key="batch.batch">
                                                    <div
                                                        class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm hover:border-indigo-200 transition-all duration-200">
                                                        <div class="flex justify-between items-start mb-2">
                                                            <span
                                                                class="px-2 py-0.5 bg-gray-900 text-white text-[10px] font-black rounded"
                                                                x-text="batch.batch"></span>
                                                            <span
                                                                class="text-[10px] font-black text-emerald-600 uppercase tracking-widest"
                                                                x-text="numberFormat(batch.stock)"></span>
                                                        </div>
                                                        <div class="space-y-1">
                                                            <div class="flex justify-between text-[10px] text-gray-400">
                                                                <span>Diterima:</span>
                                                                <span class="font-bold text-gray-600"
                                                                    x-text="numberFormat(batch.total_received)"></span>
                                                            </div>
                                                            <div class="flex justify-between text-[10px] text-gray-400">
                                                                <span>Diserahkan:</span>
                                                                <span class="font-bold text-rose-400"
                                                                    x-text="numberFormat(batch.total_submitted)"></span>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="mt-3 h-1.5 w-full bg-gray-100 rounded-full overflow-hidden">
                                                            <div class="h-full bg-indigo-500 rounded-full"
                                                                :style="'width: ' + ((batch.stock / batch.total_received) * 100) + '%'">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-200 mb-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0l-8 4-8-4m8 4v10">
                                                </path>
                                            </svg>
                                            <p class="text-xs font-black text-gray-400 uppercase tracking-widest italic">
                                                Tidak ada persediaan HCTS saat ini</p>
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

    <script>
        function inventoryManager() {
            return {
                activeKey: null,
                activeBatchDetails: [],
                loadingDetail: false,

                toggleDetail(pecahan, ta, te) {
                    const key = `${pecahan}-${ta}-${te}`;
                    if (this.activeKey === key) {
                        this.activeKey = null;
                        this.activeBatchDetails = [];
                    } else {
                        this.activeKey = key;
                        this.fetchBatchDetail(pecahan, ta, te);
                    }
                },

                fetchBatchDetail(pecahan, ta, te) {
                    this.loadingDetail = true;
                    this.activeBatchDetails = [];

                    fetch(`{{ route('hcts-inventory.batch-detail') }}?pecahan=${pecahan}&tahun_anggaran=${ta}&tahun_emisi=${te}`)
                        .then(res => res.json())
                        .then(data => {
                            this.activeBatchDetails = data;
                            this.loadingDetail = false;
                        })
                        .catch(err => {
                            console.error(err);
                            this.loadingDetail = false;
                        });
                },

                numberFormat(x) {
                    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                }
            }
        }
    </script>
</x-app-layout>