<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-lg text-gray-800 leading-tight tracking-tighter">
            {{ __('Registrasi Penerimaan HCS (Khazai)') }}
        </h2>
    </x-slot>

    <div class="py-6 text-[11px]">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-10">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-[2rem] border border-gray-100">
                <div class="p-8 text-gray-900">
                    
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                        <div>
                            <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">Antrean Registrasi HCS</h3>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">Kelola pemuatan data awal dari Seksi Khazai</p>
                        </div>
                        <a href="{{ route('hcs-khazai-registration.create') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-2xl font-black text-[10px] text-white uppercase tracking-[0.2em] hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 transition-all duration-300 shadow-xl shadow-indigo-100 active:scale-95">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                            Registrasi Baru
                        </a>
                    </div>

                    <!-- Search -->
                    <div class="mb-8 p-4 bg-gray-50/50 rounded-[1.5rem] border border-gray-100">
                        <form action="{{ route('hcs-khazai-registration.index') }}" method="GET" class="flex gap-4">
                            <div class="relative flex-grow group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}" 
                                    class="pl-12 w-full border-gray-200 rounded-xl text-[11px] font-bold py-3 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all placeholder-gray-300 shadow-sm" 
                                    placeholder="Cari Nomor Bon, Barcode, atau Batch...">
                            </div>
                            <button type="submit" class="px-8 bg-white border border-gray-200 rounded-xl font-black text-[10px] uppercase tracking-widest text-gray-600 hover:bg-gray-50 transition-all shadow-sm">Filter</button>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead>
                                <tr class="bg-gray-50/50">
                                    <th class="px-4 py-4 text-left font-black text-gray-400 uppercase tracking-widest">Tgl Pembuatan</th>
                                    <th class="px-4 py-4 text-left font-black text-gray-400 uppercase tracking-widest">Nomor Bon</th>
                                    <th class="px-4 py-4 text-left font-black text-gray-400 uppercase tracking-widest">Barcode</th>
                                    <th class="px-4 py-4 text-left font-black text-gray-400 uppercase tracking-widest text-center">Detail</th>
                                    <th class="px-4 py-4 text-left font-black text-gray-400 uppercase tracking-widest">Pecahan</th>
                                    <th class="px-4 py-4 text-left font-black text-gray-400 uppercase tracking-widest">Petugas</th>
                                    <th class="px-4 py-4 text-center font-black text-gray-400 uppercase tracking-widest">Status</th>
                                    <th class="px-4 py-4 text-center font-black text-gray-400 uppercase tracking-widest">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 uppercase">
                                @forelse($registrations as $reg)
                                <tr class="hover:bg-indigo-50/30 transition-all duration-300">
                                    <td class="px-4 py-4 font-bold text-gray-600">{{ $reg->tanggal_pembuatan->isoFormat('D MMM YYYY') }}</td>
                                    <td class="px-4 py-4 font-black text-gray-900 tracking-tighter">{{ $reg->nomor_bon }}</td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-2">
                                            <span class="bg-gray-100 px-3 py-1 rounded-lg font-mono font-black text-indigo-700 ring-1 ring-gray-200">{{ $reg->barcode_token }}</span>
                                            <a href="{{ route('hcs-khazai-registration.barcode', $reg->id) }}" target="_blank" class="text-gray-400 hover:text-indigo-600 transition-colors" title="Cetak Barcode">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                            </a>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <div class="inline-flex items-center gap-2 bg-gray-50 px-3 py-1.5 rounded-xl border border-gray-100">
                                            <span class="text-gray-400 font-bold">{{ $reg->batch }}</span>
                                            <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                            <span class="text-gray-400 font-bold">{{ $reg->seri }}</span>
                                            <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                            <span class="text-gray-900 font-black">{{ number_format($reg->jumlah, 0, ',', '.') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        @php
                                            $pecahanColors = [
                                                'S' => 'bg-lime-500', 'T' => 'bg-gray-500', 'U' => 'bg-amber-500',
                                                'V' => 'bg-purple-500', 'W' => 'bg-emerald-500', 'X' => 'bg-blue-500', 'Y' => 'bg-red-500'
                                            ];
                                        @endphp
                                        <span class="{{ $pecahanColors[$reg->pecahan] ?? 'bg-indigo-500' }} text-white px-2 py-1 rounded-md font-black text-[9px]">{{ $reg->pecahan }}</span>
                                    </td>
                                    <td class="px-4 py-4 font-bold text-gray-400">{{ $reg->petugasKhazai->name ?? '-' }}</td>
                                    <td class="px-4 py-4 text-center">
                                        @if($reg->status === 'diterima')
                                            <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full font-black text-[9px] uppercase tracking-widest ring-1 ring-emerald-200 shadow-sm shadow-emerald-50">DITERIMA</span>
                                        @else
                                            <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full font-black text-[9px] uppercase tracking-widest ring-1 ring-amber-200 shadow-sm shadow-amber-50">TERTUNDA</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex justify-center items-center gap-3">
                                            @if($reg->status !== 'diterima')
                                                <a href="{{ route('hcs-khazai-registration.edit', $reg->id) }}" class="p-2 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition-all duration-300 shadow-sm" title="Edit Data">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </a>
                                                <form action="{{ route('hcs-khazai-registration.destroy', $reg->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="p-2 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition-all duration-300 shadow-sm" title="Hapus Data">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-gray-300">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-center text-gray-400 font-bold uppercase italic">Belum ada antrean registrasi.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-8">
                        {{ $registrations->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
