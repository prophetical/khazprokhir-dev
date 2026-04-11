<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kerusakan Blanko Dus') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @php
                $canCRUD = in_array(auth()->user()->role, ['admin', 'kemas', 'sortir']);
            @endphp

            @if($canCRUD)
            <!-- Form Input -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Catat Kerusakan Blanko
                    </h3>

                    <form action="{{ route('penyablonan.kerusakan.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Tanggal</label>
                                <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required
                                    class="block w-full border-gray-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Jumlah Rusak (Pcs)</label>
                                <input type="number" name="jumlah" placeholder="Contoh: 5" required min="1"
                                    class="block w-full border-gray-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Keterangan Kerusakan</label>
                                <input type="text" name="keterangan" placeholder="Alasan: Robek / Cacat Produksi" required
                                    class="block w-full border-gray-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-rose-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-rose-700 active:bg-rose-900 focus:outline-none shadow-md transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                Simpan Data Kerusakan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            <!-- Riwayat Kerusakan -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Log Kerusakan Blanko
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 uppercase tracking-widest">
                                <tr>
                                    <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-400">Tanggal</th>
                                    <th class="px-6 py-3 text-right text-[10px] font-bold text-gray-400">Jumlah</th>
                                    <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-400">Keterangan</th>
                                    <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-400">Petugas</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100 italic">
                                @forelse ($data as $row)
                                <tr class="hover:bg-red-50/30 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $row->tanggal->locale('id')->isoFormat('D MMMM YYYY') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-black text-right text-rose-600">{{ number_format($row->jumlah, 0, ',', '.') }} Pcs</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $row->keterangan ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-[10px] font-bold text-gray-400 uppercase tracking-tight">{{ $row->user->name ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-sm text-gray-400 italic">Tidak ada laporan kerusakan blanko.</td>
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
