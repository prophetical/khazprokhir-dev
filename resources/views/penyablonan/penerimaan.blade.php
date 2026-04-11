<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Penerimaan Blanko Dus') }}
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
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Catat Penerimaan Blanko
                    </h3>

                    <form action="{{ route('penyablonan.penerimaan.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Tanggal Terima</label>
                                <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required
                                    class="block w-full border-gray-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Jumlah Terima (Pcs)</label>
                                <input type="number" name="jumlah" placeholder="Contoh: 500" required min="1"
                                    class="block w-full border-gray-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Keterangan</label>
                                <input type="text" name="keterangan" placeholder="Asal kiriman / Nomor DO"
                                    class="block w-full border-gray-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-indigo-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @else
            <div class="bg-amber-50 border-l-4 border-amber-400 p-4 rounded-r-xl shadow-sm">
                <div class="flex">
                    <div class="shrink-0">
                        <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-amber-700 font-medium">
                            Mode Lihat Saja: Role Anda ({{ auth()->user()->role }}) tidak memiliki izin untuk menambah data.
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Riwayat Penerimaan -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Riwayat Penerimaan Blanko
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
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse ($data as $row)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $row->tanggal->locale('id')->isoFormat('D MMMM YYYY') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-black text-right text-indigo-600">{{ number_format($row->jumlah, 0, ',', '.') }} Pcs</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $row->keterangan ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-[10px] font-bold text-gray-400 uppercase">{{ $row->user->name ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-sm text-gray-400 italic">Belum ada data penerimaan blanko.</td>
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
