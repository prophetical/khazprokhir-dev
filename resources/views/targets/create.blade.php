<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Target Baru') }}
        </h2>
    </x-slot>

    @php
        $themeClasses = [
            'S' => ['bg' => 'bg-lime-500',   'border' => 'border-lime-500',   'ring' => 'focus:ring-lime-500',   'focus' => 'focus:border-lime-500',   'btn' => 'bg-lime-500'],
            'T' => ['bg' => 'bg-gray-400',   'border' => 'border-gray-400',   'ring' => 'focus:ring-gray-400',   'focus' => 'focus:border-gray-400',   'btn' => 'bg-gray-400'],
            'U' => ['bg' => 'bg-amber-400',  'border' => 'border-amber-400',  'ring' => 'focus:ring-amber-400',  'focus' => 'focus:border-amber-400',  'btn' => 'bg-amber-400'],
            'V' => ['bg' => 'bg-purple-500', 'border' => 'border-purple-500', 'ring' => 'focus:ring-purple-500', 'focus' => 'focus:border-purple-500', 'btn' => 'bg-purple-500'],
            'W' => ['bg' => 'bg-green-500',  'border' => 'border-green-500',  'ring' => 'focus:ring-green-500',  'focus' => 'focus:border-green-500',  'btn' => 'bg-green-500'],
            'X' => ['bg' => 'bg-blue-500',   'border' => 'border-blue-500',   'ring' => 'focus:ring-blue-500',   'focus' => 'focus:border-blue-500',   'btn' => 'bg-blue-500'],
            'Y' => ['bg' => 'bg-red-500',    'border' => 'border-red-500',    'ring' => 'focus:ring-red-500',    'focus' => 'focus:border-red-500',    'btn' => 'bg-red-500'],
        ];
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
    @endphp

    <div class="py-12" x-data="{
        selectedPecahan: '{{ old('pecahan', '') }}',
        themes: {{ json_encode($themeClasses) }},
        annualTarget: {{ old('target_tahunan', 0) }},
        monthlyTargets: {
            @foreach($months as $num => $name)
                {{ $num }}: {{ old('bulan_'.$num, 0) }},
            @endforeach
        },
        annualFormatted: '',
        monthlyFormatted: {},

        get currentTheme() { return this.themes[this.selectedPecahan] || null },
        get totalMonthly() {
            return Object.values(this.monthlyTargets).reduce((a, b) => (parseInt(a) || 0) + (parseInt(b) || 0), 0);
        },
        formatRibuan(n) {
            if (!n || isNaN(n)) return '';
            return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        },
        init() {
            this.annualFormatted = this.formatRibuan(this.annualTarget);
            @foreach($months as $num => $name)
                this.monthlyFormatted[{{ $num }}] = this.formatRibuan(this.monthlyTargets[{{ $num }}]);
            @endforeach
        },
        updateAnnual(val) {
            let numeric = val.replace(/\./g, '');
            this.annualTarget = parseInt(numeric) || 0;
            this.annualFormatted = this.formatRibuan(this.annualTarget);
        },
        updateMonthly(num, val) {
            let numeric = val.replace(/\./g, '');
            this.monthlyTargets[num] = parseInt(numeric) || 0;
            this.monthlyFormatted[num] = this.formatRibuan(this.monthlyTargets[num]);
        }
    }">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border-t-4 transition-all duration-500"
                 :class="currentTheme ? currentTheme.border : 'border-indigo-500'">
                <div class="p-8 text-gray-900">
                    <div class="flex justify-between items-center mb-8">
                        <div class="flex flex-col">
                            <h3 class="text-lg font-bold text-indigo-800">Form Target Baru</h3>
                            <p class="text-xs text-gray-500 mt-1">Input target tahunan dan rincian target per bulan.</p>
                        </div>
                        <a href="{{ route('targets.index') }}" class="text-sm text-gray-400 hover:text-indigo-600 flex items-center gap-1 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Kembali ke Daftar
                        </a>
                    </div>

                    @if($errors->any())
                        <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg shadow-sm">
                            <ul class="text-red-700 text-sm space-y-1">
                                @foreach($errors->all() as $err)
                                    <li>• {{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('targets.store') }}">
                        @csrf
                        <input type="hidden" name="target_tahunan" :value="annualTarget">
                        @foreach($months as $num => $name)
                            <input type="hidden" name="bulan_{{ $num }}" :value="monthlyTargets[{{ $num }}]">
                        @endforeach

                        {{-- Section 1: Identitas & Target Tahunan --}}
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Pecahan</label>
                                <select name="pecahan" x-model="selectedPecahan"
                                    class="block w-full border-gray-200 rounded-lg shadow-sm text-sm py-2.5 px-3 transition-all font-bold focus:ring-opacity-50"
                                    :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'" required>
                                    <option value="">-- Pilih --</option>
                                    @foreach(['S','T','U','V','W','X','Y'] as $p)
                                        <option value="{{ $p }}">Pecahan {{ $p }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Tahun Anggaran</label>
                                <input type="number" name="tahun_anggaran" value="{{ old('tahun_anggaran', date('Y')) }}"
                                    class="block w-full border-gray-200 rounded-lg shadow-sm text-sm py-2.5 px-3 transition-all font-bold focus:ring-opacity-50"
                                    :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'" required>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Tahun Emisi</label>
                                <input type="number" name="tahun_emisi" value="{{ old('tahun_emisi', '2022') }}"
                                    class="block w-full border-gray-200 rounded-lg shadow-sm text-sm py-2.5 px-3 transition-all font-bold focus:ring-opacity-50"
                                    :class="currentTheme ? (currentTheme.focus + ' ' + currentTheme.ring) : 'focus:border-indigo-500 focus:ring-indigo-500'" required>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-indigo-400 uppercase tracking-widest mb-2">Target Tahunan</label>
                                <input type="text" x-model="annualFormatted" @input="updateAnnual($event.target.value)"
                                    class="block w-full border-indigo-100 bg-indigo-50/30 rounded-lg shadow-sm text-sm py-2.5 px-3 transition-all font-black text-indigo-700 text-right focus:ring-indigo-500 focus:border-indigo-500" placeholder="0" required>
                                <div class="text-[9px] text-gray-400 mt-1 text-right italic" x-show="annualTarget > 50000000000">Maksimal 50 Milyar</div>
                            </div>
                        </div>

                        <div class="relative mb-8">
                            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                <div class="w-full border-t border-gray-100"></div>
                            </div>
                            <div class="relative flex justify-start">
                                <span class="pr-3 bg-white text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Rincian Target Bulanan</span>
                            </div>
                        </div>

                        {{-- Section 2: Target Bulanan --}}
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-x-6 gap-y-5 mb-10">
                            @foreach($months as $num => $name)
                                <div>
                                    <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">{{ $name }}</label>
                                    <input type="text" x-model="monthlyFormatted[{{ $num }}]" @input="updateMonthly({{ $num }}, $event.target.value)"
                                        class="block w-full border-gray-100 bg-gray-50/50 rounded-lg shadow-sm text-xs py-2 px-3 transition-all font-bold text-right focus:ring-gray-300 focus:border-gray-300" placeholder="0">
                                </div>
                            @endforeach
                        </div>

                        {{-- Footer Info & Submit --}}
                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 mb-8 flex flex-col md:flex-row justify-between items-center gap-4">
                            <div class="flex items-center gap-4">
                                <div class="flex flex-col">
                                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Akumulasi Bulanan</span>
                                    <span class="text-xl font-black text-gray-700" x-text="formatRibuan(totalMonthly) || 0">0</span>
                                </div>
                                <div class="h-8 w-px bg-gray-200 hidden md:block"></div>
                                <div class="flex flex-col">
                                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Selisih vs Tahunan</span>
                                    <span class="text-xs font-bold" :class="totalMonthly == annualTarget ? 'text-emerald-500' : 'text-amber-500'">
                                        <span x-text="totalMonthly == annualTarget ? '✓ Sesuai' : '⚠ Tidak harus sama'"></span>
                                    </span>
                                </div>
                            </div>

                            <div class="flex gap-3 w-full md:w-auto">
                                <button type="submit"
                                    class="flex-1 md:flex-none justify-center items-center px-10 py-3 border border-transparent rounded-lg font-bold text-sm text-white uppercase tracking-widest shadow-lg active:scale-95 transition-all duration-300"
                                    :class="currentTheme ? currentTheme.btn : 'bg-indigo-600 hover:bg-indigo-700'">
                                    Simpan Target
                                </button>
                                <a href="{{ route('targets.index') }}" class="flex-1 md:flex-none text-center px-8 py-3 bg-white border border-gray-200 rounded-lg font-bold text-sm text-gray-500 uppercase tracking-widest shadow-sm hover:bg-gray-50 active:scale-95 transition-all">
                                    Batal
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
