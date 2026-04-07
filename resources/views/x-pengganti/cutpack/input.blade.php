<x-app-layout :full-screen="true">
    <div class="h-screen w-full flex flex-col bg-gray-50 dark:bg-slate-900">

        {{-- Top Sticky Action Bar ── --}}
        <x-slot name="header">
            <div class="flex items-center justify-between w-full gap-4">
                {{-- Kiri: Info Seri --}}
                <div class="flex items-center gap-3 min-w-0">
                    <a href="{{ route('x-pengganti.cutpack.index') }}"
                        class="p-2 rounded-xl text-white/60 hover:text-white hover:bg-white/10 transition-all shrink-0" title="Kembali ke daftar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </a>
                    @php
                        $pchBadgeColor = ['S'=>'bg-lime-400','T'=>'bg-gray-300','U'=>'bg-amber-300','V'=>'bg-purple-400','W'=>'bg-green-400','X'=>'bg-blue-400','Y'=>'bg-red-400'];
                    @endphp
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl {{ $pchBadgeColor[$seri->pecahan] ?? 'bg-gray-400' }} font-black text-white text-sm shadow">{{ $seri->pecahan }}</span>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-white font-black text-base tracking-wider">{{ $seri->seri }}</span>
                            <span class="px-2 py-0.5 bg-white/10 rounded-lg text-white/70 text-[10px] font-bold">Batch {{ $seri->batch }}</span>
                            <span class="px-2 py-0.5 bg-white/10 rounded-lg text-white/70 text-[10px] font-bold">TA {{ $seri->tahun_anggaran }}</span>
                            <span class="px-2 py-0.5 bg-white/10 rounded-lg text-white/70 text-[10px] font-bold">TE {{ $seri->tahun_emisi }}</span>
                        </div>
                    </div>
                </div>

                {{-- Kanan: Aksi --}}
                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('x-pengganti.cutpack.pdf', ['seri_id' => $seri->id]) }}" target="_blank"
                        class="inline-flex items-center gap-1.5 px-3 py-2 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        PDF
                    </a>
                    <button type="submit" form="cutpack-form"
                        class="inline-flex items-center gap-1.5 px-5 py-2 bg-white text-indigo-700 hover:bg-indigo-50 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow active:scale-95">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        Simpan
                    </button>
                </div>
            </div>
        </x-slot>

        {{-- Errors Flash --}}
        @if ($errors->any())
            <div class="shrink-0 p-4 bg-rose-50 dark:bg-rose-900/30 border-b border-rose-200 dark:border-rose-800 text-rose-600 dark:text-rose-400 text-sm font-medium z-30">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('success'))
            <div class="shrink-0 p-4 text-sm font-bold bg-emerald-50 dark:bg-emerald-900/30 border-b border-emerald-200 dark:border-emerald-800 text-emerald-600 dark:text-emerald-400 z-30 flex items-center justify-center">
                {{ session('success') }}
            </div>
        @endif
        {{-- Main Grid Content (Scrollable Area) --}}
        <div class="flex-1 overflow-x-auto overflow-y-auto w-full relative group">
            
            <form id="cutpack-form" action="{{ route('x-pengganti.cutpack.store') }}" method="POST">
                @csrf
                <input type="hidden" name="x_pengganti_seri_id" value="{{ $seri->id }}">

                <div class="grid grid-cols-1 xl:grid-cols-2 divide-y xl:divide-y-0 xl:divide-x-2 xl:divide-slate-300 dark:xl:divide-slate-700 bg-white dark:bg-slate-800 items-start w-full">
                    
                    @php
                        $ranges = [
                            ['start' => 1,  'end' => 50,  'title' => 'PACK 1 - 50'],
                            ['start' => 51, 'end' => 100, 'title' => 'PACK 51 - 100'],
                        ];
                    @endphp

                    @foreach($ranges as $range)
                        <div class="overflow-x-auto relative border-b border-gray-200 dark:border-slate-700 xl:border-b-0">
                            {{-- Header Kolom --}}
                            <div class="sticky left-0 w-full px-4 py-2 bg-slate-800 text-white font-black text-[13px] text-center tracking-widest uppercase border-b border-slate-900 border-r border-slate-700 shadow-sm z-30">
                                GRID {{ $range['title'] }}
                            </div>
                            
                            <table class="w-full text-[11px] text-left border-collapse min-w-[660px]">
                                <thead class="sticky top-0 z-20 whitespace-nowrap">
                                    {{-- Row 1 --}}
                                    <tr>
                                        <th rowspan="3" class="w-12 px-1 py-1.5 bg-slate-800 text-white text-center border-b border-r border-slate-700 font-black text-[11px]">NO.<br>PACK</th>
                                        <th colspan="6" class="px-2 py-1.5 bg-amber-500 text-white text-center border-b border-r border-amber-600 font-black text-[11px] uppercase shadow-sm">INSCHIET LEMBAR BILYET</th>
                                        <th colspan="3" class="px-2 py-1.5 bg-indigo-500 text-white text-center border-b border-indigo-600 font-black text-[11px] uppercase shadow-sm">KETERANGAN</th>
                                    </tr>
                                    {{-- Row 2 --}}
                                    <tr>
                                        <th colspan="3" class="px-1 py-1 bg-slate-700 text-slate-200 border-b border-r border-slate-600 text-center font-bold text-[9px] tracking-widest uppercase shadow-inner">JUMLAH RUSAK BILYET</th>
                                        <th colspan="3" class="px-1 py-1 bg-slate-800 text-amber-300 border-b border-r border-slate-600 text-center font-black text-[9px] tracking-widest uppercase shadow-inner">TOTAL RUSAK</th>
                                        <th rowspan="2" class="w-[70px] px-1 py-1 bg-indigo-600 text-white border-b border-r border-indigo-700 text-center font-bold text-[9px] uppercase leading-tight align-middle shadow-inner">PACK PGT</th>
                                        <th rowspan="2" class="w-24 px-1 py-1 bg-indigo-700 text-white border-b border-r border-indigo-800 text-center font-black text-[9.5px] uppercase align-middle shadow-inner">SERI PENGGANTI</th>
                                        <th rowspan="2" class="w-[70px] px-1 py-1 bg-indigo-600 text-white border-b border-indigo-700 text-center font-bold text-[9px] uppercase leading-tight align-middle shadow-inner">BLYT PGT</th>
                                    </tr>
                                    {{-- Row 3 --}}
                                    <tr>
                                        <th class="w-16 px-1 py-1 bg-slate-600 text-slate-100 border-b border-r border-slate-500 text-center font-bold text-[8px] uppercase">SERI 1</th>
                                        <th class="w-16 px-1 py-1 bg-slate-600 text-slate-100 border-b border-r border-slate-500 text-center font-bold text-[8px] uppercase">SERI 2</th>
                                        <th class="w-[85px] px-1 py-1 bg-slate-600 text-slate-100 border-b border-r border-slate-500 text-center font-bold text-[8px] uppercase">CMPRN</th>
                                        <th class="w-14 px-1 py-1 bg-slate-900 text-amber-200 border-b border-r border-slate-700 text-center font-bold text-[8px] uppercase">SERI 1</th>
                                        <th class="w-14 px-1 py-1 bg-slate-900 text-amber-200 border-b border-r border-slate-700 text-center font-bold text-[8px] uppercase">SERI 2</th>
                                        <th class="w-16 px-1 py-1 bg-slate-900 text-amber-200 border-b border-r border-slate-700 text-center font-bold text-[8px] uppercase">CMPRN</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y-0 divide-transparent">
                                    
                                    @for ($p = $range['start']; $p <= $range['end']; $p++)
                                        
                                        {{-- Pack Boundary Helper --}}
                                        <tr><td colspan="10" class="border-b-4 border-slate-900 dark:border-black h-[3px] bg-slate-200 dark:bg-slate-900 relative"></td></tr>

                                        @for ($s = 1; $s <= 4; $s++)
                                            <tr class="hover:bg-amber-50/50 dark:hover:bg-slate-700/50 group/row {{ $s % 2 == 0 ? 'bg-gray-50/50 dark:bg-slate-800/20' : 'bg-white dark:bg-slate-800/60' }} transition-colors">
                                                
                                                {{-- HIDDEN INPUTS --}}
                                                @if ($s === 1)
                                                    <input type="hidden" name="packs[{{$p}}][nomor_pack]" value="{{$p}}">
                                                @endif
                                                <input type="hidden" name="packs[{{$p}}][slots][{{$s}}][slot]" value="{{$s}}">
                                                
                                                {{-- NO PACK (rowSpan 4) --}}
                                                @if ($s === 1)
                                                    <td rowspan="4" class="align-middle text-center border-r border-l border-slate-400 dark:border-slate-600 bg-slate-100 dark:bg-slate-900 border-b border-b-slate-400">
                                                        <div class="text-xl font-black text-slate-800 dark:text-slate-200">{{ $p }}</div>
                                                    </td>
                                                @endif
                                                
                                                {{-- JUMLAH RUSAK --}}
                                                <td class="p-0.5 border-r border-b border-gray-200 dark:border-slate-700">
                                                    <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, ''); calcTotal({{$p}}, 'seri1')" 
                                                        name="packs[{{$p}}][slots][{{$s}}][rusak_seri_1]" 
                                                        value="{{ $gridData[$p]['slots'][$s]['rusak_seri_1'] ?? '' }}" 
                                                        class="calc-seri1-pack-{{$p}} w-full h-8 px-1 text-center bg-transparent border-none focus:ring-2 focus:ring-amber-500 rounded font-bold text-gray-900 dark:text-white placeholder-gray-300 dark:placeholder-slate-600 arrow-nav text-[12px]"
                                                        placeholder="-" autocomplete="off">
                                                </td>
                                                <td class="p-0.5 border-r border-b border-gray-200 dark:border-slate-700">
                                                    <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, ''); calcTotal({{$p}}, 'seri2')" 
                                                        name="packs[{{$p}}][slots][{{$s}}][rusak_seri_2]" 
                                                        value="{{ $gridData[$p]['slots'][$s]['rusak_seri_2'] ?? '' }}" 
                                                        class="calc-seri2-pack-{{$p}} w-full h-8 px-1 text-center bg-transparent border-none focus:ring-2 focus:ring-amber-500 rounded font-bold text-gray-900 dark:text-white placeholder-gray-300 dark:placeholder-slate-600 arrow-nav text-[12px]"
                                                        placeholder="-" autocomplete="off">
                                                </td>
                                                <td class="p-0.5 border-r-2 border-b border-slate-300 dark:border-slate-600">
                                                    <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, ''); calcTotal({{$p}}, 'campuran')" 
                                                        name="packs[{{$p}}][slots][{{$s}}][rusak_campuran]" 
                                                        value="{{ $gridData[$p]['slots'][$s]['rusak_campuran'] ?? '' }}" 
                                                        class="calc-campuran-pack-{{$p}} w-full h-8 px-1 text-center bg-transparent border-none focus:ring-2 focus:ring-amber-500 rounded font-bold text-gray-900 dark:text-white placeholder-gray-300 dark:placeholder-slate-600 arrow-nav text-[12px]"
                                                        placeholder="-" autocomplete="off">
                                                </td>
                                                
                                                {{-- TOTAL RUSAK (READ ONLY) (rowSpan 4) --}}
                                                @if ($s === 1)
                                                    <td rowspan="4" class="p-0.5 border-r border-b border-slate-300 dark:border-slate-600 bg-amber-50 dark:bg-amber-900/10 align-middle">
                                                        <input type="text" readonly id="total-seri1-pack-{{$p}}" 
                                                            name="packs[{{$p}}][total_rusak_seri_1]" 
                                                            value="{{ $gridData[$p]['total_rusak_seri_1'] ?? '' }}" 
                                                            class="w-full text-center bg-transparent border-none focus:ring-0 text-amber-700 dark:text-amber-500 font-black text-sm p-0 pointer-events-none"
                                                            placeholder="0" tabindex="-1">
                                                    </td>
                                                    <td rowspan="4" class="p-0.5 border-r border-b border-slate-300 dark:border-slate-600 bg-amber-50 dark:bg-amber-900/10 align-middle">
                                                        <input type="text" readonly id="total-seri2-pack-{{$p}}" 
                                                            name="packs[{{$p}}][total_rusak_seri_2]" 
                                                            value="{{ $gridData[$p]['total_rusak_seri_2'] ?? '' }}" 
                                                            class="w-full text-center bg-transparent border-none focus:ring-0 text-amber-700 dark:text-amber-500 font-black text-sm p-0 pointer-events-none"
                                                            placeholder="0" tabindex="-1">
                                                    </td>
                                                    <td rowspan="4" class="p-0.5 border-r-2 border-b border-slate-300 dark:border-slate-600 bg-amber-50 dark:bg-amber-900/10 align-middle">
                                                        <input type="text" readonly id="total-campuran-pack-{{$p}}" 
                                                            name="packs[{{$p}}][total_rusak_campuran]" 
                                                            value="{{ $gridData[$p]['total_rusak_campuran'] ?? '' }}" 
                                                            class="w-full text-center bg-transparent border-none focus:ring-0 text-amber-700 dark:text-amber-500 font-black text-sm p-0 pointer-events-none"
                                                            placeholder="0" tabindex="-1">
                                                    </td>
                                                @endif

                                                {{-- KETERANGAN --}}
                                                <td class="p-0.5 border-r border-b border-gray-200 dark:border-slate-700 bg-indigo-50/30 dark:bg-indigo-900/10">
                                                    <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                                        name="packs[{{$p}}][slots][{{$s}}][nomor_pack_pengganti]" 
                                                        value="{{ $gridData[$p]['slots'][$s]['nomor_pack_pengganti'] ?? '' }}" 
                                                        class="w-full h-8 px-1 text-center bg-transparent border-none ring-1 ring-transparent focus:ring-indigo-500 rounded font-bold text-indigo-900 dark:text-indigo-300 placeholder-indigo-300/50 dark:placeholder-indigo-800/50 arrow-nav text-[12px]"
                                                        placeholder="-" autocomplete="off">
                                                </td>
                                                
                                                {{-- SERI PENGGANTI (rowSpan 4) --}}
                                                @if ($s === 1)
                                                    <td rowspan="4" class="p-1 border-r border-b border-slate-300 dark:border-slate-600 bg-indigo-50/50 dark:bg-indigo-900/20 align-middle">
                                                        <input type="text" oninput="maskSeri(this)"
                                                            name="packs[{{$p}}][seri_pengganti]" 
                                                            value="{{ $gridData[$p]['seri_pengganti'] ?? '' }}" 
                                                            class="w-full h-[52px] px-1 text-center bg-white dark:bg-slate-800 border border-indigo-200 dark:border-indigo-600 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg font-black text-[13px] tracking-widest uppercase text-indigo-900 dark:text-indigo-400 placeholder-indigo-200 dark:placeholder-slate-600 shadow-sm transition-all"
                                                            placeholder="XX-XX9" autocomplete="off" maxlength="6">
                                                    </td>
                                                @endif

                                                <td class="p-0.5 border-r-2 border-b border-slate-800 bg-indigo-50/30 dark:bg-indigo-900/10">
                                                    <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                                        name="packs[{{$p}}][slots][{{$s}}][nomor_bilyet_pengganti]" 
                                                        value="{{ $gridData[$p]['slots'][$s]['nomor_bilyet_pengganti'] ?? '' }}" 
                                                        class="w-full h-8 px-1 text-center bg-transparent border-none ring-1 ring-transparent focus:ring-indigo-500 rounded font-bold text-indigo-900 dark:text-indigo-300 placeholder-indigo-300/50 dark:placeholder-indigo-800/50 arrow-nav text-[12px]"
                                                        placeholder="-" autocomplete="off">
                                                </td>

                                            </tr>
                                        @endfor
                                    @endfor
                                    
                                    <tr><td colspan="10" class="h-1 bg-slate-800"></td></tr>

                                </tbody>
                            </table>
                            </div>
                        @endforeach

                    </div>
                </form>
            </div>
        </div>

    {{-- Frontend Auto-Calculation and Arrow Navigation Logic --}}
    <script>
        // 1. Logic Summing Tiga Kategori Rusak
        function calcTotal(packNumber, type) {
            const inputs = document.querySelectorAll(`.calc-${type}-pack-${packNumber}`);
            let sum = 0;
            let hasValue = false;
            
            inputs.forEach(input => {
                let val = parseInt(input.value);
                if (!isNaN(val)) {
                    sum += val;
                    hasValue = true;
                }
            });

            const totalUi = document.getElementById(`total-${type}-pack-${packNumber}`);
            if (hasValue) {
                totalUi.value = sum;
                // Add a small bump animation
                totalUi.classList.add('scale-110');
                setTimeout(() => totalUi.classList.remove('scale-110'), 150);
            } else {
                totalUi.value = '';
            }
        }

        // 2. Logic Masking Seri Pengganti (XX-XX9)
        function maskSeri(el) {
            let cursor = el.selectionStart;
            let val = el.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            
            let masked = '';
            for(let i=0; i<val.length; i++) {
                if(i < 4) { // Only Letters
                    if(/[A-Z]/.test(val[i])) {
                        if(i === 2 && !masked.includes('-')) masked += '-';
                        masked += val[i];
                    } else {
                        break;
                    }
                } else if (i === 4) { // Only Number at 5th position
                    if(/[0-9]/.test(val[i])) {
                        masked += val[i];
                    } else {
                        break;
                    }
                }
            }
            
            let didAddStrip = masked.length === 3 && val.length === 2 && cursor === 2;
            
            el.value = masked;

            // Simple cursor preservation logic
            if (didAddStrip) cursor++;
            el.setSelectionRange(cursor, cursor);
        }

        // 3. Enter to Move Down Column Navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault(); // Prevent form submit
                if(e.target.tagName.toLowerCase() === 'input' && e.target.type !== 'hidden') {
                    const name = e.target.name;
                    if (name) {
                        // Cek apakah itu sebuah slot (punya koordinat p dan s)
                        let match = name.match(/packs\[(\d+)\]\[slots\]\[(\d+)\]\[(.*?)\]/);
                        if (match) {
                            let p = parseInt(match[1]);
                            let s = parseInt(match[2]);
                            let key = match[3];
                            
                            s++; // Ke slot bawahnya
                            if (s > 4) {
                                s = 1; // Pindah pack selanjutnya, slot ke 1
                                p++;
                            }
                            if (p <= 100) {
                                let nextInput = document.querySelector(`input[name="packs[${p}][slots][${s}][${key}]"]`);
                                if (nextInput) {
                                    nextInput.focus();
                                    nextInput.select();
                                    return;
                                }
                            }
                        }
                        
                        // Cek apakah itu sebuah input Seri Pengganti (pack)
                        let sumMatch = name.match(/packs\[(\d+)\]\[seri_pengganti\]/);
                        if (sumMatch) {
                            let p = parseInt(sumMatch[1]);
                            p++; // Ke pack bawahnya
                            if (p <= 100) {
                                let nextInput = document.querySelector(`input[name="packs[${p}][seri_pengganti]"]`);
                                if (nextInput) {
                                    nextInput.focus();
                                    nextInput.select();
                                    return;
                                }
                            }
                        }
                    }
                }
            }
        });
    </script>

    <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
        
        .transition-colors { transition: background-color 0.15s ease-in-out; }
    </style>
</x-app-layout>
