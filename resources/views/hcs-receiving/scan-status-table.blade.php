<div class="grid grid-cols-1 xl:grid-cols-2 gap-6 items-start">
    
    <!-- Area Antrean Menunggu -->
    <div class="bg-white dark:bg-slate-900 shadow-sm rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/50">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h4 class="text-[11px] font-black text-slate-700 dark:text-slate-200 uppercase tracking-widest">Antrean Belum Scan</h4>
            </div>
            <span class="bg-amber-500 text-white text-[9px] font-black px-2.5 py-1 rounded-full shadow-sm">
                {{ $registrations->where('status', 'pending')->count() }} BARCODE
            </span>
        </div>
        
        <div class="max-h-[600px] overflow-y-auto custom-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/30 dark:bg-slate-800/30">
                        <th class="px-6 py-3 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-slate-800">Barcode</th>
                        <th class="px-6 py-3 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-slate-800 text-right">Detail Bon</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($registrations->where('status', 'pending') as $reg)
                        <tr class="group hover:bg-amber-50/30 dark:hover:bg-amber-900/5 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-[12px] font-black text-slate-900 dark:text-slate-100 font-mono tracking-tight">{{ $reg->barcode_token }}</span>
                                    <span class="text-[8px] font-black text-indigo-500 uppercase tracking-widest mt-0.5">PENDING VERIFICATION</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex flex-col items-end gap-2">
                                    <div class="flex flex-col items-end">
                                        <span class="text-[10px] font-black text-slate-600 dark:text-slate-300">{{ $reg->nomor_bon }}</span>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <span class="text-[9px] font-bold text-slate-400 uppercase">{{ $reg->pecahan }}</span>
                                            <span class="text-[9px] font-black text-slate-500">{{ $reg->jumlah }} bilyet</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button onclick='window.openBarcodeGlobal(@json($reg))' 
                                            class="p-1.5 bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-all" title="Lihat Barcode">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m-3 3h2m3 3h-2m3 3h-2m3 3h-2M4 4h16a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2z"></path></svg>
                                        </button>
                                        <button onclick="manualConfirm({{ $reg->id }})" 
                                            class="px-2.5 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-[8px] font-black uppercase tracking-widest transition-all">
                                            TERIMA MANUAL
                                        </button>
                                        <div class="flex items-center gap-1 border-l border-slate-200 dark:border-slate-700 pl-2">
                                            <a href="{{ route('hcs-khazai-registration.edit', $reg->id) }}" 
                                                class="p-1.5 text-slate-400 hover:text-indigo-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-all" title="Edit Registrasi">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </a>
                                            <button onclick="deleteRegistration({{ $reg->id }})" 
                                                class="p-1.5 text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all" title="Hapus Registrasi">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center opacity-20 grayscale">
                                    <svg class="w-12 h-12 mb-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                    <p class="text-[10px] font-black uppercase tracking-[0.3em]">Antrean Kosong</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Area Terproses (Riwayat) -->
    <div class="bg-white dark:bg-slate-900 shadow-sm rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden opacity-90">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/50">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h4 class="text-[11px] font-black text-slate-700 dark:text-slate-200 uppercase tracking-widest">Selesai Scan Hari Ini</h4>
            </div>
            <span class="bg-emerald-500 text-white text-[9px] font-black px-2.5 py-1 rounded-full shadow-sm">
                {{ $registrations->where('status', 'diterima')->count() + $manualReceivings->count() }} SCAN
            </span>
        </div>
        
        <div class="max-h-[600px] overflow-y-auto custom-scrollbar">
            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                <!-- Registrasi Terproses -->
                @foreach($registrations->where('status', 'diterima') as $reg)
                    @php
                        $receiving = \App\Models\HcsReceiving::where('nomor_bon', $reg->nomor_bon)
                                    ->where('batch', $reg->batch)
                                    ->where('seri', $reg->seri)
                                    ->with('packs')
                                    ->first();
                        $isSorted = $receiving ? $receiving->packs->whereNotNull('hcs_sorting_id')->isNotEmpty() : false;
                    @endphp
                    <div class="px-6 py-4 flex items-center justify-between group">
                        <div class="flex flex-col">
                            <span class="text-[11px] font-bold text-slate-400 line-through font-mono">{{ $reg->barcode_token }}</span>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-[8px] font-black text-emerald-500 uppercase tracking-widest">VERIFIED AT {{ $reg->updated_at->format('H:i') }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($receiving)
                                <a href="{{ route('hcs-receiving.edit', $receiving->id) }}" 
                                    class="p-1.5 text-slate-400 hover:text-indigo-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-all" title="Edit Penerimaan">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                @if(!$isSorted)
                                    <button onclick="deleteReceiving({{ $receiving->id }})" 
                                        class="p-1.5 text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all" title="Batalkan Penerimaan">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                @else
                                    <span class="p-1.5 text-slate-300 cursor-not-allowed" title="Sudah Disortir - Tidak dapat dihapus">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                                    </span>
                                @endif
                            @endif
                            <div class="w-6 h-6 bg-emerald-500 rounded-full flex items-center justify-center shadow-lg shadow-emerald-100 dark:shadow-none">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Input Manual Langsung -->
                @foreach($manualReceivings as $item)
                    @php
                        $item->load('packs');
                        $isSorted = $item->packs->whereNotNull('hcs_sorting_id')->isNotEmpty();
                    @endphp
                    <div class="px-6 py-4 flex items-center justify-between group bg-slate-50/50 dark:bg-slate-800/30">
                        <div class="flex flex-col">
                            <span class="text-[11px] font-bold text-slate-900 dark:text-white font-mono">{{ $item->nomor_bon }}</span>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-[8px] font-black text-blue-500 uppercase tracking-widest">DIRECT MANUAL ENTRY • {{ $item->created_at->format('H:i') }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                             <a href="{{ route('hcs-receiving.edit', $item->id) }}" 
                                class="p-1.5 text-slate-400 hover:text-indigo-500 hover:bg-slate-100 rounded-lg transition-all" title="Edit Manual Entry">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </a>
                            @if(!$isSorted)
                                <button onclick="deleteReceiving({{ $item->id }})" 
                                    class="p-1.5 text-slate-400 hover:text-red-500 rounded-lg transition-all" title="Hapus Manual Entry">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            @else
                                <span class="p-1.5 text-slate-300 cursor-not-allowed" title="Sudah Disortir">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                                </span>
                            @endif
                            <div class="flex items-center gap-1.5 bg-blue-50 dark:bg-blue-900/20 px-2.5 py-1 rounded-full">
                                <span class="text-[9px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest">{{ $item->pecahan }}</span>
                                <div class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></div>
                            </div>
                        </div>
                    </div>
                @endforeach

                @if($registrations->where('status', 'diterima')->isEmpty() && $manualReceivings->isEmpty())
                    <div class="px-6 py-20 text-center opacity-30 italic">
                        <p class="text-[9px] font-black uppercase tracking-widest">Belum ada barcode terverifikasi</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(100, 116, 139, 0.15); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(100, 116, 139, 0.3); }
</style>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 3px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(100, 116, 139, 0.1);
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(100, 116, 139, 0.25);
    }
</style>
