<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Penyablonan Dus') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8 space-y-6">
            @php
                $canCRUD = in_array(auth()->user()->role, ['admin', 'kemas', 'sortir']);
                $themeClasses = [
                    'S' => '#84cc16',
                    'T' => '#6b7280',
                    'U' => '#f59e0b',
                    'V' => '#a855f7',
                    'W' => '#10b981',
                    'X' => '#3b82f6',
                    'Y' => '#ef4444',
                ];
            @endphp

            @if($canCRUD)
            <!-- Form Input -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        Input Aktivitas Penyablonan
                    </h3>

                    <form action="{{ route('penyablonan.dus.store') }}" method="POST" x-data="{ 
                        noAwal: '', 
                        noAkhir: '', 
                        get jumlah() { 
                            let a = parseInt(this.noAwal) || 0;
                            let b = parseInt(this.noAkhir) || 0;
                            return b >= a ? (b - a + 1) : 0;
                        } 
                    }">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-8 gap-4 items-end">
                            <div class="lg:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Tanggal</label>
                                <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required
                                    class="block w-full border-gray-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-2.5">
                            </div>
                            <div class="lg:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Gilir</label>
                                <select name="gilir" required class="block w-full border-gray-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-2.5">
                                    <option value="Gilir 1" {{ old('gilir') == 'Gilir 1' ? 'selected' : '' }}>Gilir 1</option>
                                    <option value="Gilir 2" {{ old('gilir') == 'Gilir 2' ? 'selected' : '' }}>Gilir 2</option>
                                    <option value="Gilir 3" {{ old('gilir') == 'Gilir 3' ? 'selected' : '' }}>Gilir 3</option>
                                </select>
                            </div>
                            <div class="lg:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Pecahan</label>
                                <select name="pecahan" required class="block w-full border-gray-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-2.5 font-bold">
                                    @foreach($themeClasses as $p => $color)
                                        <option value="{{ $p }}" {{ old('pecahan') == $p ? 'selected' : '' }}>{{ $p }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="lg:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Tahun Emisi</label>
                                <input type="number" name="te" value="{{ old('te', date('Y')-4) }}" required
                                    class="block w-full border-gray-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-2.5">
                            </div>
                            <div class="lg:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Tahun Anggaran</label>
                                <input type="number" name="ta" value="{{ old('ta', date('Y')) }}" required
                                    class="block w-full border-gray-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-2.5">
                            </div>
                            <div class="lg:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">No Dus Awal</label>
                                <input type="number" name="no_awal" x-model="noAwal" required min="1"
                                    class="block w-full border-gray-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-2.5">
                            </div>
                            <div class="lg:col-span-1">
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">No Dus Akhir</label>
                                <input type="number" name="no_akhir" x-model="noAkhir" required min="1"
                                    class="block w-full border-gray-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-2.5">
                            </div>
                            <div class="lg:col-span-1">
                                <label class="block text-[10px] font-bold text-emerald-600 uppercase tracking-widest mb-2 px-1">Jumlah</label>
                                <input type="text" :value="jumlah" readonly
                                    class="block w-full bg-emerald-50 border-emerald-200 rounded-lg shadow-sm font-black text-center text-emerald-700 text-sm p-2.5">
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-indigo-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none shadow-md transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Simpan Aktivitas
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            <!-- Riwayat Aktivitas -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Log Penyablonan Terbaru
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 uppercase tracking-widest">
                                <tr>
                                    <th class="px-3 py-3 text-left text-[10px] text-gray-400 font-bold">Waktu</th>
                                    <th class="px-3 py-3 text-center text-[10px] text-gray-400 font-bold">Gilir</th>
                                    <th class="px-3 py-3 text-center text-[10px] text-gray-400 font-bold">PCH</th>
                                    <th class="px-3 py-3 text-center text-[10px] text-gray-400 font-bold">TE</th>
                                    <th class="px-3 py-3 text-center text-[10px] text-gray-400 font-bold">TA</th>
                                    <th class="px-3 py-3 text-center text-[10px] text-gray-400 font-bold">No Awal</th>
                                    <th class="px-3 py-3 text-center text-[10px] text-gray-400 font-bold">No Akhir</th>
                                    <th class="px-3 py-3 text-right text-[10px] text-gray-400 font-bold">Jumlah</th>
                                    <th class="px-3 py-3 text-left text-[10px] text-gray-400 font-bold">Petugas</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100 italic">
                                @forelse ($data as $row)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-3 py-4 whitespace-nowrap text-xs font-bold">{{ $row->tanggal->format('d/m/Y') }}</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-[10px] text-center uppercase font-bold text-gray-500">{{ $row->gilir }}</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded text-xs font-black text-white shadow-sm" style="background-color: {{ $themeClasses[$row->pecahan] }}">
                                            {{ $row->pecahan }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-xs text-center font-bold">{{ $row->te }}</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-xs text-center font-bold">{{ $row->ta }}</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-xs text-center font-black text-indigo-600">{{ $row->no_awal }}</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-xs text-center font-black text-indigo-600">{{ $row->no_akhir }}</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-right font-black text-gray-900">{{ number_format($row->jumlah, 0, ',', '.') }} Dus</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-[10px] font-bold text-gray-400 uppercase tracking-tighter">{{ $row->user->name ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-10 text-center text-sm text-gray-400">Belum ada aktivitas penyablonan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $data->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
