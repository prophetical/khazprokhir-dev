@php
    $rows = $breakdown['rows'] ?? [];
    $footer = $breakdown['footer'] ?? [];
    $footerTotal = $breakdown['footer_total'] ?? 0;
    $unattributed = $breakdown['unattributed'] ?? 0;
    $isEmpty = $footerTotal == 0 || count($rows) == 0;
    $jenisLabels = ['kemas' => 'Siap Kemas', 'kirim' => 'Siap Kirim', 'total' => 'Total Persediaan'];
    $jenisLabel = $jenisLabels[$jenis] ?? 'Persediaan';
    $highlight = $jenis;
    $fmt = fn($v) => $v == 0 ? '-' : number_format($v, 0, ',', '.');
    $tglFormatted = \Carbon\Carbon::parse($tanggalLaporan)->locale('id')->isoFormat('dddd, D MMMM Y');
    $columns = [
        'batch' => 'Batch',
        'seri' => 'Seri',
        'pack' => 'Pack',
        'diterima' => 'Diterima',
        'dikemas' => 'Dikemas',
        'diserahkan' => 'Diserahkan',
        'siap_kemas' => 'Siap Kemas',
        'siap_kirim' => 'Siap Kirim',
        'total' => 'Total',
    ];
    $hlHeader = 'bg-indigo-100 dark:bg-indigo-900/40 text-indigo-800 dark:text-indigo-300';
    $hlCell = 'bg-indigo-50/50 dark:bg-indigo-900/20 text-indigo-800 dark:text-indigo-300 font-black';
    $columnColors = [
        'diterima' => 'bg-sky-100 dark:bg-sky-900/30 text-sky-900 dark:text-sky-100',
        'dikemas' => 'bg-amber-100 dark:bg-amber-900/30 text-amber-900 dark:text-amber-100',
        'diserahkan' => 'bg-violet-100 dark:bg-violet-900/30 text-violet-900 dark:text-violet-100',
        'siap_kemas' => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-900 dark:text-emerald-100',
        'siap_kirim' => 'bg-rose-100 dark:bg-rose-900/30 text-rose-900 dark:text-rose-100',
        'total' => 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-900 dark:text-indigo-100',
    ];
    $columnHoverColors = [
        'diterima' => 'hover:bg-sky-200 dark:hover:bg-sky-800/40',
        'dikemas' => 'hover:bg-amber-200 dark:hover:bg-amber-800/40',
        'diserahkan' => 'hover:bg-violet-200 dark:hover:bg-violet-800/40',
        'siap_kemas' => 'hover:bg-emerald-200 dark:hover:bg-emerald-800/40',
        'siap_kirim' => 'hover:bg-rose-200 dark:hover:bg-rose-800/40',
        'total' => 'hover:bg-indigo-200 dark:hover:bg-indigo-800/40',
    ];
@endphp

@if ($unattributed > 0)
    <div
        class="mb-6 rounded-2xl border border-amber-200 dark:border-amber-500/30 bg-amber-50 dark:bg-amber-900/20 px-5 py-4 text-xs text-amber-800 dark:text-amber-300 flex items-start gap-3">
        <svg class="h-5 w-5 mt-0.5 text-amber-500 dark:text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.774-.833-1.732-.833-2.5 0L4.268 17.5c-.774.833.192 2.5 1.732 2.5z" />
        </svg>
        <div>
            <span class="font-black uppercase tracking-wider text-[11px]">Perhatian:</span>
            <span class="ml-1">Terdapat {{ number_format($unattributed, 0, ',', '.') }} bilyet penyerahan yang
                <strong class="font-black">belum teratribusi</strong> ke pengemasan mana pun.
                Jumlah ini tidak masuk ke headline persediaan.</span>
        </div>
    </div>
@endif

@if ($isEmpty)
    <div
        class="rounded-3xl border border-dashed border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800/30 px-8 py-20 text-center">
        <div
            class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-100 dark:bg-slate-800 shadow-inner">
            <svg class="h-8 w-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0H4m4 0V9a1 1 0 011-1h6a1 1 0 011 1v4" />
            </svg>
        </div>
        <h4 class="text-xl font-black text-gray-700 dark:text-gray-200">Tidak ada persediaan {{ $jenisLabel }}</h4>
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 font-medium">
            Pecahan {{ $pecahan }} — TA {{ $tahunAnggaran }}{{ $tahunEmisi ? ' / TE ' . $tahunEmisi : '' }} —
            {{ $tglFormatted }}
        </p>
        <p class="mt-2 text-xs text-gray-400 dark:text-gray-500">
            Belum ada (batch, seri) dengan persediaan {{ strtolower($jenisLabel) }} pada tanggal laporan ini.
        </p>
    </div>
@else
    <div class="overflow-x-auto overflow-y-[visible] rounded-2xl border border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-lg">
        <table class="min-w-full border-separate border-spacing-0">
            <thead>
                <tr
                    class="text-[10px] font-black uppercase tracking-[0.15em] bg-indigo-50 dark:bg-indigo-900/30 text-indigo-900 dark:text-indigo-200">
                    @foreach ($columns as $key => $label)
                        @php
                            $thClass = 'px-4 py-3.5 text-center';
                            if (in_array($key, ['batch', 'seri', 'pack'])) {
                                $thClass .= ' sticky left-0 bg-indigo-50 dark:bg-indigo-900/30 z-20';
                            }
                        @endphp
                        <th class="{{ $thClass }}">{{ $label }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                @foreach ($rows as $row)
                    <tr
                        class="even:bg-gray-50/40 dark:even:bg-slate-800/30 odd:bg-white dark:odd:bg-slate-900 hover:bg-indigo-50/60 dark:hover:bg-indigo-900/20 transition-colors duration-150">
                        @foreach ($columns as $key => $label)
                            @php
                                $isSticky = in_array($key, ['batch', 'seri', 'pack']);
                                $isHighlight = $key === $highlight;
                                $isNum = in_array($key, ['diterima', 'dikemas', 'diserahkan', 'siap_kemas', 'siap_kirim', 'total']);
                                $stickyBase = $isSticky ? 'sticky left-0 z-10' : '';
                                $cellBase = 'px-4 py-3.5 text-xs transition-colors duration-150 ' . $stickyBase . ' ';
                                $textAlign = $isNum ? 'text-right' : 'text-center';
                                $cellBase .= $textAlign . ' ';
                                if ($isHighlight) {
                                    $cellClass = $cellBase . $hlCell;
                                } elseif ($isSticky) {
                                    $cellClass = $cellBase . 'font-bold text-gray-700 dark:text-gray-200';
                                } elseif ($isNum) {
                                    $colorClass = $columnColors[$key] ?? '';
                                    $hoverClass = $columnHoverColors[$key] ?? '';
                                    $cellClass = $cellBase . $colorClass . ' tabular-nums font-semibold ' . $hoverClass;
                                } else {
                                    $cellClass = $cellBase . 'text-gray-600 dark:text-gray-300';
                                }
                            @endphp
                            <td class="{{ $cellClass }}">
                                @if ($key === 'pack')
                                    <span class="font-mono text-[10px] tracking-tight">{{ $row['pack'] }}</span>
                                @elseif ($isNum)
                                    {{ $fmt($row[$key]) }}
                                @else
                                    {{ $row[$key] }}
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
            <tfoot
            class="bg-gray-900 dark:bg-slate-950 text-white text-xs font-black uppercase tracking-[0.15em]">
                <tr class="divide-x divide-gray-700 dark:divide-slate-800">
                    <td class="px-4 py-5 text-center uppercase sticky left-0 bg-gray-900 dark:bg-slate-950 z-20"
                        colspan="3">Total</td>
                    @foreach (['diterima', 'dikemas', 'diserahkan', 'siap_kemas', 'siap_kirim', 'total'] as $key)
                        @php
                            $isHl = $key === $highlight;
                            $numClass = 'px-4 py-5 text-right tabular-nums text-xs ' . ($isHl ? 'text-indigo-300' : 'text-gray-100');
                        @endphp
                        <td class="{{ $numClass }}">
                            {{ $fmt($footer[$key]) }}
                        </td>
                    @endforeach
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="mt-5 flex items-center justify-between text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest px-1">
        <span>{{ count($rows) }} (batch, seri) ditampilkan</span>
        <span class="flex items-center gap-2">
            <svg class="h-3.5 w-3.5 text-emerald-500 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
            Selisih vs headline: <span class="text-emerald-600 dark:text-emerald-400 ml-1">0</span>
        </span>
    </div>
@endif
