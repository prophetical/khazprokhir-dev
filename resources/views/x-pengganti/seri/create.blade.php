<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <h2 class="font-black text-xl text-gray-800 dark:text-white leading-tight tracking-tight">Tambah Seri</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            @if($errors->any())
                <div class="mb-4 p-4 bg-rose-50 dark:bg-rose-900/20 border-l-4 border-rose-500 text-rose-700 dark:text-rose-400 shadow-sm rounded-r-lg" role="alert">
                    <div class="flex items-center mb-2">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-black text-sm">Terjadi Kesalahan!</span>
                    </div>
                    <ul class="list-disc list-inside text-xs space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('x-pengganti.seri.store') }}" method="POST">
                @csrf
                <div class="bg-white/70 dark:bg-slate-800/80 backdrop-blur-md overflow-hidden shadow-xl shadow-gray-200/50 dark:shadow-slate-900/30 sm:rounded-[1.5rem] border border-white dark:border-slate-700 transition-all duration-500">
                    <div class="p-6 border-b border-gray-50 dark:border-slate-700">
                        <h3 class="text-base font-black text-gray-900 dark:text-white tracking-tight">Data Identifikasi Seri</h3>
                        <p class="text-[10px] text-gray-400 dark:text-slate-500 font-bold uppercase tracking-widest mt-0.5">Isi semua field berikut dengan benar</p>
                    </div>

                    <div class="p-6 space-y-5">
                        {{-- Pecahan --}}
                        <div class="group/input">
                            <label for="pecahan" class="block text-[9px] font-black tracking-widest text-gray-400 dark:text-slate-400 mb-1.5 uppercase group-focus-within/input:text-violet-500 transition-colors">
                                Pecahan <span class="text-rose-500">*</span>
                            </label>
                            <select id="pecahan" name="pecahan" required
                                class="block w-full bg-gray-50/50 dark:bg-slate-700 border-gray-100 dark:border-slate-600 focus:bg-white dark:focus:bg-slate-600 focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500/50 rounded-xl transition-all duration-300 text-base font-black h-[42px] text-center text-center-last dark:text-gray-100">
                                <option value="">— Pilih Pecahan —</option>
                                @foreach($pecahanOptions as $p)
                                    <option value="{{ $p }}" {{ old('pecahan') == $p ? 'selected' : '' }}>{{ $p }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Seri --}}
                        <div class="group/input">
                            <label for="seri" class="block text-[9px] font-black tracking-widest text-gray-400 dark:text-slate-400 mb-1.5 uppercase group-focus-within/input:text-violet-500 transition-colors">
                                Seri <span class="text-rose-500">*</span>
                            </label>
                            <input id="seri" name="seri" type="text" required value="{{ old('seri') }}"
                                placeholder="AA-BB1" maxlength="6"
                                oninput="formatSeri(this)"
                                onkeydown="handleSeriKeydown(event, this)"
                                class="block w-full bg-gray-50/50 dark:bg-slate-700 border-gray-100 dark:border-slate-600 focus:bg-white dark:focus:bg-slate-600 focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500/50 rounded-xl transition-all duration-300 text-base font-bold h-[42px] px-4 dark:text-gray-100 dark:placeholder:text-slate-500 uppercase tracking-[0.2em] text-center" />
                        </div>

                        {{-- Batch --}}
                        <div class="group/input">
                            <label for="batch" class="block text-[9px] font-black tracking-widest text-gray-400 dark:text-slate-400 mb-1.5 uppercase group-focus-within/input:text-violet-500 transition-colors">
                                Batch <span class="text-rose-500">*</span>
                                <span class="text-gray-300 dark:text-slate-600 ml-1 normal-case font-normal">7 digit</span>
                            </label>
                            <input id="batch" name="batch" type="text" required value="{{ old('batch') }}" maxlength="7"
                                inputmode="numeric" oninput="this.value = this.value.replace(/\D/g,'').slice(0,7)"
                                class="block w-full bg-gray-50/50 dark:bg-slate-700 border-gray-100 dark:border-slate-600 focus:bg-white dark:focus:bg-slate-600 focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500/50 rounded-xl transition-all duration-300 text-base font-black h-[42px] px-4 tracking-widest dark:text-gray-100 dark:placeholder:text-slate-500" />
                        </div>

                        {{-- Tahun Anggaran & Tahun Emisi --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div class="group/input">
                                <label for="tahun_anggaran" class="block text-[9px] font-black tracking-widest text-gray-400 dark:text-slate-400 mb-1.5 uppercase group-focus-within/input:text-violet-500 transition-colors">
                                    Tahun Anggaran <span class="text-rose-500">*</span>
                                </label>
                                <input id="tahun_anggaran" name="tahun_anggaran" type="number" required
                                    value="{{ old('tahun_anggaran', date('Y')) }}" min="2025" max="2030"
                                    placeholder=" {{ date('Y') }}"
                                    class="block w-full bg-gray-50/50 dark:bg-slate-700 border-gray-100 dark:border-slate-600 focus:bg-white dark:focus:bg-slate-600 focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500/50 rounded-xl transition-all duration-300 text-base font-black h-[42px] px-4 text-center dark:text-gray-100" />
                            </div>
                            <div class="group/input">
                                <label for="tahun_emisi" class="block text-[9px] font-black tracking-widest text-gray-400 dark:text-slate-400 mb-1.5 uppercase group-focus-within/input:text-violet-500 transition-colors">
                                    Tahun Emisi <span class="text-rose-500">*</span>
                                </label>
                                <input id="tahun_emisi" name="tahun_emisi" type="number" required
                                    value="{{ old('tahun_emisi', date('Y')) }}" min="2022" max="2030"
                                    placeholder=" {{ date('Y') }}"
                                    class="block w-full bg-gray-50/50 dark:bg-slate-700 border-gray-100 dark:border-slate-600 focus:bg-white dark:focus:bg-slate-600 focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500/50 rounded-xl transition-all duration-300 text-base font-black h-[42px] px-4 text-center dark:text-gray-100" />
                            </div>
                        </div>
                    </div>

                    {{-- Aksi --}}
                    <div class="px-6 py-4 bg-gray-50/50 dark:bg-slate-700/30 border-t border-gray-50 dark:border-slate-700 flex items-center justify-between gap-3 rounded-b-[1.5rem]">
                        <a href="{{ route('x-pengganti.seri.index') }}" class="px-5 py-2.5 text-gray-400 hover:text-gray-900 dark:text-slate-500 dark:hover:text-gray-200 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 hover:bg-gray-100 dark:hover:bg-slate-600">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-8 py-2.5 bg-gradient-to-r from-violet-600 to-purple-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:from-violet-700 hover:to-purple-700 transition-all duration-300 shadow-lg shadow-violet-200 dark:shadow-violet-900/30 active:scale-95 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Data Seri
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <style>
        .text-center-last {
            text-align-last: center;
        }
    </style>

    @push('scripts')
    <script>
        function formatSeri(input) {
            let raw = input.value.toUpperCase().replace(/[^A-Z0-9\-]/g, '');
            let clean = raw.replace(/-/g, '');
            let formatted = '';

            if (clean.length <= 2) {
                formatted = clean.replace(/[^A-Z]/g, '');
            } else if (clean.length <= 4) {
                const part1 = clean.substring(0, 2).replace(/[^A-Z]/g, '');
                const part2 = clean.substring(2, 4).replace(/[^A-Z]/g, '');
                formatted = part1 + (part2.length > 0 ? '-' + part2 : '');
            } else {
                const part1 = clean.substring(0, 2).replace(/[^A-Z]/g, '');
                const part2 = clean.substring(2, 4).replace(/[^A-Z]/g, '');
                const part3 = clean.substring(4, 5).replace(/[^0-9]/g, '');
                formatted = part1 + '-' + part2 + part3;
            }

            input.value = formatted;

            // Validasi visual dasar
            if (formatted.length === 6 && /^[A-Z]{2}-[A-Z]{2}[0-9]$/.test(formatted)) {
                input.classList.remove('border-gray-100', 'dark:border-slate-600');
                input.classList.add('border-emerald-500', 'dark:border-emerald-500');
            } else {
                input.classList.remove('border-emerald-500', 'dark:border-emerald-500');
                input.classList.add('border-gray-100', 'dark:border-slate-600');
            }
        }

        function handleSeriKeydown(event, input) {
            if (event.keyCode === 8) { // Backspace
                const pos = input.selectionStart;
                if (input.value[pos - 1] === '-') {
                    event.preventDefault();
                    input.value = input.value.substring(0, pos - 2) + input.value.substring(pos);
                    input.setSelectionRange(pos - 2, pos - 2);
                    formatSeri(input);
                }
            }
        }
    </script>
    @endpush
</x-app-layout>