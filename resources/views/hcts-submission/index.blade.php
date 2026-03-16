<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div>
                    <h2 class="font-black text-xl text-gray-800 leading-tight tracking-tight">
                        {{ __('Data Penyerahan HCTS') }}
                    </h2>
                </div>
            </div>


        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 shadow-sm rounded-r-lg flex items-center gap-3"
                    role="alert">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-bold text-sm">{{ session('success') }}</span>
                </div>
            @endif
            @if(auth()->user()->role == 'sortir' || auth()->user()->role == 'admin')
                <div class="flex justify-end">
                    <a href="{{ route('hcts-submission.create') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-emerald-700 transition-all duration-200">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        {{ __('Tambah Penyerahan') }}
                    </a>
                </div>
            @endif
            <!-- Filter Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-6">
                    <form action="{{ route('hcts-submission.index') }}" method="GET"
                        class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                        <div class="md:col-span-2 grid grid-cols-2 gap-4">
                            <div class="relative group">
                                <x-input-label for="start_date" :value="__('Mulai Tanggal')"
                                    class="text-[9px] font-black tracking-widest text-emerald-600 mb-1.5 uppercase" />
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-emerald-400 group-hover:text-emerald-500 transition-colors"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <input id="start_date" name="start_date" type="date"
                                        class="block w-full pl-10 pr-3 py-2.5 bg-emerald-50/30 border-emerald-100 focus:border-emerald-500 focus:bg-white focus:ring focus:ring-emerald-200 focus:ring-opacity-50 rounded-xl text-xs font-bold text-gray-700 transition-all duration-300 shadow-sm"
                                        value="{{ $startDate }}" />
                                </div>
                            </div>
                            <div class="relative group">
                                <x-input-label for="end_date" :value="__('Sampai Tanggal')"
                                    class="text-[9px] font-black tracking-widest text-emerald-600 mb-1.5 uppercase" />
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-emerald-400 group-hover:text-emerald-500 transition-colors"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <input id="end_date" name="end_date" type="date"
                                        class="block w-full pl-10 pr-3 py-2.5 bg-emerald-50/30 border-emerald-100 focus:border-emerald-500 focus:bg-white focus:ring focus:ring-emerald-200 focus:ring-opacity-50 rounded-xl text-xs font-bold text-gray-700 transition-all duration-300 shadow-sm"
                                        value="{{ $endDate }}" />
                                </div>
                            </div>
                        </div>

                        <div class="relative group">
                            <x-input-label for="search" :value="__('Cari Data')"
                                class="text-[9px] font-black tracking-widest text-indigo-600 mb-1.5 uppercase" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-indigo-400 group-hover:text-indigo-500 transition-colors"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input id="search" name="search" type="text"
                                    class="block w-full pl-10 pr-3 py-2.5 bg-indigo-50/30 border-indigo-100 focus:border-indigo-500 focus:bg-white focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-xl text-xs font-bold text-gray-700 transition-all duration-300 shadow-sm"
                                    value="{{ $search }}" placeholder="BA, Pecahan, TA..." />
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row gap-2 h-auto md:h-[42px]">
                            <button type="submit"
                                class="flex-1 px-4 py-2 bg-gray-900 text-white text-[10px] font-black tracking-widest rounded-xl hover:bg-gray-800 transition-all duration-200 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Filter
                            </button>
                            <a href="{{ route('hcts-submission.index') }}"
                                class="px-4 py-2 bg-white border border-gray-200 text-gray-400 hover:text-rose-600 rounded-xl transition-all duration-200 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                            </a>
                        </div>
                    </form>

                    <!-- Export Buttons -->
                    <div class="mt-6 pt-6 border-t border-gray-100 flex flex-wrap gap-2 justify-end">
                        <a href="{{ route('hcts-submission.export', request()->all()) }}"
                            class="inline-flex items-center px-4 py-2 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-100 transition-all active:scale-95">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Excel
                        </a>
                        <a href="{{ route('hcts-submission.print', request()->all()) }}" target="_blank"
                            class="inline-flex items-center px-4 py-2 bg-rose-50 text-rose-700 border border-rose-100 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-rose-100 transition-all active:scale-95">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                </path>
                            </svg>
                            PDF
                        </a>
                        <a href="{{ route('hcts-submission.print', array_merge(request()->all(), ['autoprint' => 1])) }}"
                            target="_blank"
                            class="inline-flex items-center px-4 py-2 bg-gray-50 text-gray-700 border border-gray-100 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-100 transition-all active:scale-95">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 00-2 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                </path>
                            </svg>
                            Print
                        </a>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-gray-50/50 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">
                                <th class="px-3 py-3 text-left">Tanggal</th>
                                <th class="px-3 py-3 text-left">Nomor BA</th>
                                <th class="px-2 py-3 text-center">Pec</th>
                                <th class="px-3 py-3 text-center">TA/TE</th>
                                <th class="px-3 py-3 text-right">Jumlah</th>
                                <th class="px-4 py-3 text-left">Batch Detail</th>
                                <th class="px-3 py-3 text-center">Petugas</th>
                                <th class="px-3 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($submissions as $submission)
                                <tr class="hover:bg-gray-50/50 transition-colors group text-[11px]">
                                    <td class="px-3 py-3 font-bold text-gray-600 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($submission->tanggal_penyerahan)->locale('id')->isoFormat('D MMMM YYYY') }}
                                    </td>
                                    <td class="px-3 py-3 font-black text-gray-600 italic underline decoration-gray-100">
                                        {{ $submission->nomor_ba }}</td>
                                    <td class="px-2 py-3 text-center">
                                        @php
                                            $pchClasses = [
                                                'S' => 'bg-lime-500 text-white',
                                                'T' => 'bg-gray-400 text-white',
                                                'U' => 'bg-amber-400 text-white',
                                                'V' => 'bg-purple-500 text-white',
                                                'W' => 'bg-green-500 text-white',
                                                'X' => 'bg-blue-500 text-white',
                                                'Y' => 'bg-red-500 text-white',
                                            ];
                                            $currentClass = $pchClasses[$submission->pecahan] ?? 'bg-gray-500 text-white';
                                        @endphp
                                        <span
                                            class="inline-flex items-center justify-center w-7 h-7 rounded-lg {{ $currentClass }} font-black text-[10px] shadow-sm">
                                            {{ $submission->pecahan }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-3 text-center text-gray-500 font-bold whitespace-nowrap">
                                        {{ $submission->tahun_anggaran }}/{{ $submission->tahun_emisi }}
                                    </td>
                                    <td class="px-3 py-3 text-right font-black text-rose-600">
                                        {{ number_format($submission->jumlah_bilyet, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($submission->batches as $batch)
                                                <span
                                                    class="inline-flex items-center px-1.5 py-0.5 bg-gray-50 text-gray-600 rounded-md text-[9px] font-black border border-gray-100">
                                                    {{ $batch->batch }}
                                                    <span
                                                        class="ml-0.5 text-gray-400 font-bold">({{ number_format($batch->jumlah, 0, ',', '.') }})</span>
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <div
                                                class="w-6 h-6 rounded bg-gray-900 flex items-center justify-center text-[9px] font-black text-white">
                                                {{ strtoupper(substr($submission->user->name ?? '?', 0, 1)) }}
                                            </div>
                                            <span
                                                class="text-[9px] font-black text-gray-500 uppercase truncate max-w-[60px]">{{ $submission->user->name ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-1">
                                            @if(auth()->user()->role == 'sortir' || auth()->user()->role == 'admin')
                                                <a href="{{ route('hcts-submission.edit', $submission) }}"
                                                    class="p-1.5 text-indigo-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all"
                                                    title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                <form action="{{ route('hcts-submission.destroy', $submission) }}" method="POST"
                                                    class="inline-block delete-confirm">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="p-1.5 text-rose-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all"
                                                        title="Hapus">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v2m3 4h.01" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-200 mb-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0l-8 4-8-4m8 4v10">
                                                </path>
                                            </svg>
                                            <p class="text-xs font-black text-gray-400 uppercase tracking-widest italic">
                                                Belum ada data penyerahan ditemukan</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($submissions->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                        {{ $submissions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>