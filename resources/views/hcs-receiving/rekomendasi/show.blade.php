<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Detail Rekomendasi Penerimaan') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">

            <!-- Breadcrumb / Back Navigation -->
            <div class="mb-6 flex items-center justify-between">
                <a href="{{ route('rekomendasi-penerimaan.index') }}"
                    class="inline-flex items-center text-sm font-bold text-indigo-600 hover:text-indigo-800 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Summary Batch
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-6 mb-6">
                <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
                    <div class="flex flex-col">
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Pecahan</span>
                        <span class="text-sm font-bold text-gray-900">{{ $params['pecahan'] }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Batch</span>
                        <span class="text-sm font-bold text-gray-900">{{ $params['batch'] }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Seri</span>
                        <span class="text-sm font-bold text-gray-900 italic">{{ $params['seri'] }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Th.
                            Anggaran</span>
                        <span class="text-sm font-bold text-gray-900">{{ $params['tahun_anggaran'] }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Th.
                            Emisi</span>
                        <span class="text-sm font-bold text-gray-900">{{ $params['emisi'] }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-gray-800">Daftar Pack Rekomendasi</h3>
                        <p class="text-sm text-gray-500 mt-1 italic font-medium">Lengkapi kelompok sortir 4 pack berikut
                            ini agar dapat diproses lebih cepat di bagian sortir.</p>
                    </div>

                    <div class="overflow-x-auto rounded-xl border border-gray-100 shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-indigo-50/50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                        Grup #</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                        Supplier</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">
                                        Pack Rekomendasi</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                        Informasi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($recommendations as $rec)
                                    <tr class="hover:bg-amber-50/30 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div
                                                    class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-xs font-black text-gray-600 border border-gray-200">
                                                    {{ $rec['group'] }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $rec['supplier'] === 'Cutpack' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-sky-100 text-sky-700 border border-sky-200' }}">
                                                {{ $rec['supplier'] }}
                                            </span>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-center text-lg font-black text-indigo-600 tracking-tighter">
                                            #{{ $rec['pack_number'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <p class="text-xs text-gray-400 font-medium">
                                                Lengkapi grup {{ $rec['group'] }} (Nomor {{ ($rec['group'] - 1) * 4 + 1 }} -
                                                {{ $rec['group'] * 4 }})
                                            </p>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 whitespace-nowrap text-center">
                                            <div class="flex flex-col items-center">
                                                <div
                                                    class="w-16 h-16 bg-emerald-50 rounded-full flex items-center justify-center mb-4">
                                                    <svg class="w-10 h-10 text-emerald-500" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                                <h4 class="text-lg font-bold text-gray-800">Semua Kelompok Lengkap!</h4>
                                                <p class="text-gray-500 text-sm mt-1">Tidak ada pack rekomendasi untuk batch
                                                    ini.</p>
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