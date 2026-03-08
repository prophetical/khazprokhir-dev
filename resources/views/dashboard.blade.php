<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Under Construction Banner --}}
            <div class="relative overflow-hidden rounded-2xl shadow-xl" style="background: linear-gradient(135deg, #1e40af 0%, #7c3aed 50%, #db2777 100%);">
                {{-- Decorative pattern --}}
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute -top-10 -right-10 w-60 h-60 rounded-full bg-white"></div>
                    <div class="absolute -bottom-16 -left-10 w-80 h-80 rounded-full bg-white"></div>
                </div>
                <div class="relative px-8 py-10 flex flex-col md:flex-row items-center gap-8">
                    {{-- Icon --}}
                    <div class="shrink-0 flex items-center justify-center w-24 h-24 rounded-2xl bg-white/10 border border-white/20 shadow-inner">
                        <svg class="w-12 h-12 text-yellow-300 drop-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z"/>
                        </svg>
                    </div>
                    {{-- Text --}}
                    <div class="text-center md:text-left flex-1">
                        <div class="inline-flex items-center gap-2 bg-yellow-400/20 border border-yellow-400/30 text-yellow-300 text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full mb-3">
                            <span class="inline-block w-2 h-2 rounded-full bg-yellow-400 animate-pulse"></span>
                            Under Construction
                        </div>
                        <h1 class="text-2xl md:text-3xl font-extrabold text-white tracking-tight mb-2">Dashboard Sedang Dibangun</h1>
                        <p class="text-white/70 text-sm leading-relaxed max-w-xl">
                            Halaman dashboard produksi sedang dalam tahap pengembangan untuk memberikan tampilan yang lebih informatif dan komprehensif.
                            Sementara ini, gunakan menu di sidebar untuk mengakses modul yang sudah tersedia.
                        </p>
                    </div>
                </div>
            </div>

            {{-- System Status & Info Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- System Status --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-green-400 animate-pulse"></span>
                        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-widest">System Status</h3>
                    </div>
                    <div class="p-5 space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Aplikasi</span>
                            <span class="flex items-center gap-1.5 font-semibold text-green-600">
                                <span class="w-2 h-2 rounded-full bg-green-400"></span> Online
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Database</span>
                            <span class="flex items-center gap-1.5 font-semibold text-green-600">
                                <span class="w-2 h-2 rounded-full bg-green-400"></span> Terhubung
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Pengguna Aktif</span>
                            <span class="font-bold text-indigo-700">{{ auth()->user()->name }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Role</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-700 uppercase tracking-wider">
                                {{ ucfirst(auth()->user()->role) }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Waktu Server</span>
                            <span class="font-mono text-xs font-semibold text-gray-700">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">PHP Version</span>
                            <span class="font-mono text-xs font-semibold text-gray-600">{{ phpversion() }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Laravel Version</span>
                            <span class="font-mono text-xs font-semibold text-gray-600">{{ app()->version() }}</span>
                        </div>
                    </div>
                </div>

                {{-- Module Navigation --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-widest">Modul Tersedia</h3>
                    </div>
                    <div class="p-4 space-y-2">
                        @php
                            $modules = [
                                ['icon'=>'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10', 'label'=>'Penerimaan HCS', 'sub'=>'Data Receiving, Batch Tracking, Laporan', 'color'=>'bg-blue-50 text-blue-700 border-blue-100', 'route'=>'hcs-receiving.index'],
                                ['icon'=>'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4', 'label'=>'Penyortiran HCS', 'sub'=>'Data Sortir, Rekomendasi, Laporan', 'color'=>'bg-purple-50 text-purple-700 border-purple-100', 'route'=>'hcs-sorting.index'],
                                ['icon'=>'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', 'label'=>'Pengemasan HCS', 'sub'=>'Input Kemas, Data Pengemasan', 'color'=>'bg-green-50 text-green-700 border-green-100', 'route'=>'pengemasan.index'],
                                ['icon'=>'M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4', 'label'=>'Penyerahan ke BI', 'sub'=>'Input & Laporan Penyerahan', 'color'=>'bg-rose-50 text-rose-700 border-rose-100', 'route'=>'penyerahan-bi.index'],
                            ];
                        @endphp
                        @foreach($modules as $mod)
                            <a href="{{ route($mod['route']) }}"
                               class="flex items-center gap-3 p-3 rounded-lg border {{ $mod['color'] }} hover:shadow-sm transition-all duration-150 group">
                                <div class="shrink-0 flex items-center justify-center w-8 h-8 rounded-lg bg-white/70 shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $mod['icon'] }}"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs font-bold">{{ $mod['label'] }}</div>
                                    <div class="text-[10px] opacity-70 truncate">{{ $mod['sub'] }}</div>
                                </div>
                                <svg class="w-3.5 h-3.5 opacity-40 group-hover:opacity-100 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Info Banner --}}
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-5 flex items-start gap-4">
                <svg class="w-5 h-5 text-blue-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <p class="text-sm font-bold text-blue-800 mb-1">Khazprokhir Management System</p>
                    <p class="text-xs text-blue-600 leading-relaxed">
                        Sistem ini digunakan untuk mengelola alur kerja HCS mulai dari <strong>Penerimaan → Penyortiran → Pengemasan → Penyerahan ke BI</strong>.
                        Dashboard statistik produksi akan segera tersedia di pembaruan berikutnya.
                    </p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
