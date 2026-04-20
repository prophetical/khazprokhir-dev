<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-lg text-gray-800 leading-tight tracking-tighter">
            {{ __('Riwayat Perubahan HCS') }}
        </h2>
    </x-slot>

    <div class="py-6 text-[11px]">
        <div class="max-w-4xl mx-auto px-4">
            <div class="bg-white overflow-hidden shadow-2xl rounded-[2.5rem] border border-gray-100 p-10">
                
                <div class="flex flex-col md:flex-row justify-between items-start mb-12 gap-6">
                    <div class="space-y-1">
                        <p class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.2em]">Audit Trail Result</p>
                        <h3 class="text-2xl font-black text-gray-900 tracking-tighter">Barcode: <span class="bg-indigo-50 px-2 rounded-lg">{{ $barcode }}</span></h3>
                    </div>
                    <div class="bg-gray-50 border border-gray-100 px-6 py-4 rounded-3xl text-center shadow-inner">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Entri</p>
                        <p class="text-xl font-black text-gray-900">{{ $histories->count() }}</p>
                    </div>
                </div>

                <div class="relative">
                    <!-- Vertical Line -->
                    <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-gray-100 rounded-full"></div>

                    <div class="space-y-10 relative">
                        @forelse($histories as $history)
                            <div class="flex gap-10 items-start group">
                                <div class="relative z-10">
                                    <div class="w-12 h-12 rounded-2xl {{ $loop->first ? 'bg-indigo-600 shadow-xl shadow-indigo-200' : 'bg-white border-2 border-gray-100' }} flex items-center justify-center transition-all duration-500 group-hover:scale-110">
                                        @if($loop->first)
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        @else
                                            <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-grow pt-1 space-y-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <span class="font-black text-gray-900 uppercase tracking-widest text-[13px]">{{ $history->user->name ?? 'System' }}</span>
                                            <span class="px-3 py-1 bg-gray-100 rounded-full font-black text-[9px] text-gray-500 uppercase tracking-tighter shadow-sm border border-gray-200">{{ $history->field_name }}</span>
                                        </div>
                                        <span class="font-bold text-gray-400 text-[10px] bg-gray-50 px-3 py-1 rounded-full">{{ $history->created_at->isoFormat('D MMM YYYY, HH:mm') }}</span>
                                    </div>

                                    <div class="bg-gray-50/50 border border-gray-100 rounded-[1.5rem] p-6 grid grid-cols-1 md:grid-cols-2 gap-6 relative overflow-hidden">
                                        <div class="absolute inset-y-0 left-1/2 w-px bg-gray-100 hidden md:block"></div>
                                        
                                        <div class="space-y-2">
                                            <p class="font-black text-[9px] text-gray-400 uppercase tracking-widest">Nilai Sebelumnya</p>
                                            <div class="text-rose-500 font-bold bg-white p-3 rounded-xl border border-rose-100 shadow-sm overflow-hidden break-words italic">
                                                {{ $history->old_value ?? '(kosong/baru)' }}
                                            </div>
                                        </div>
                                        
                                        <div class="space-y-2">
                                            <p class="font-black text-[9px] text-emerald-500 uppercase tracking-widest">Nilai Baru</p>
                                            <div class="text-emerald-700 font-black bg-white p-3 rounded-xl border border-emerald-100 shadow-sm overflow-hidden break-words">
                                                {{ $history->new_value }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-20 bg-gray-50 rounded-[2rem] border border-dashed border-gray-200">
                                <p class="text-gray-400 font-bold uppercase tracking-widest italic">Belum ada riwayat perubahan ditemukan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="mt-20 pt-8 border-t border-gray-50 flex justify-center">
                    <button onclick="window.history.back()" class="px-10 py-4 bg-white border border-gray-200 rounded-2xl font-black text-[10px] uppercase tracking-[0.3em] text-gray-400 hover:bg-gray-50 hover:text-gray-600 transition-all active:scale-95 shadow-sm">Kembali Ke Laporan</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
