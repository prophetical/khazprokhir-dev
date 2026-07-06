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
@endphp

@if ($unattributed > 0)
    <div
        class="mb-4 rounded-xl border border-amber-200 dark:border-amber-500/30 bg-amber-50 dark:bg-amber-900/20 px-4 py-3 text-xs text-amber-800 dark:text-amber-300">
        <span class="font-black uppercase tracking-wider">Perhatian:</span>
        Terdapat {{ number_format($unattributed, 0, ',', '.') }} bilyet penyerahan yang
        <strong>belum teratribusi</strong> ke pengemasan mana pun (dus penyerahan belum selesai dikemas).
        Jumlah ini tidak masuk ke headline persediaan.
    </div>
@endif

@if ($isEmpty)
    <div
        class="rounded-2xl border border-dashed border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800/30 px-6 py-16 text-center">
        <div
            class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 dark:bg-slate-800">
            <svg class="h-7 w-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0H4m4 0V9a1 1 0 011-1h6a1 1 0 011 1v4" />
            </svg>
        </div>
        <h4 class="text-lg font-black text-gray-700 dark:text-gray-200">Tidak ada persediaan {{ $jenisLabel }}</h4>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Pecahan {{ $pecahan }} — TA {{ $tahunAnggaran }}{{ $tahunEmisi ? ' / TE ' . $tahunEmisi : '' }} —
            {{ $tglFormatted }}
        </p>
        <p class="mt-2 text-xs text-gray-400 dark:text-gray-500">
            Belum ada (batch, seri) dengan persediaan {{ strtolower($jenisLabel) }} pada tanggal laporan ini.
        </p>
    </div>
@else
    <div class="overflow-x-auto rounded-xl border border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-800">
            <thead class="bg-gray-100/80 dark:bg-slate-800/80">
                <tr
                    class="text-[10px] font-black uppercase text-gray-500 dark:text-gray-400 tracking-widest divide-x divide-gray-200 dark:divide-slate-700">
                    @foreach ($columns as $key => $label)
                        <th class="px-3 py-3 text-center {{ $key === $highlight ? $hlHeader : '' }}">
                            {{ $label }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-slate-900 divide-y divide-gray-100 dark:divide-slate-800">
                @foreach ($rows as $row)
                    <tr class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/20 transition duration-150 divide-x divide-gray-100 dark:divide-slate-800">
                        @foreach ($columns as $key => $label)
                            <td class="px-3 py-3 text-center text-[11px] sm:text-xs {{ $key === $highlight ? $hlCell : 'text-gray-600 dark:text-gray-300' }} {{ in_array($key, ['batch', 'seri', 'pack']) ? 'font-bold whitespace-nowrap' : 'tabular-nums' }}">
                                @if ($key === 'pack')
                                    <span class="font-mono text-[10px]">{{ $row['pack'] }}</span>
                                @elseif (in_array($key, ['diterima', 'dikemas', 'diserahkan', 'siap_kemas', 'siap_kirim', 'total']))
                                    {{ $fmt($row[$key]) }}
                                @else
                                    {{ $row[$key] }}
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-900 dark:bg-slate-950 text-white text-xs font-black uppercase">
                <tr class="divide-x divide-gray-700 dark:divide-slate-800">
                    <td class="px-3 py-4 text-center uppercase tracking-widest" colspan="3">Total</td>
                    @foreach (['diterima', 'dikemas', 'diserahkan', 'siap_kemas', 'siap_kirim', 'total'] as $key)
                        <td class="px-3 py-4 text-right tabular-nums {{ $key === $highlight ? 'text-indigo-300' : '' }}">
                            {{ $fmt($footer[$key]) }}
                        </td>
                    @endforeach
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="mt-3 flex items-center justify-between text-[10px] text-gray-400 dark:text-gray-500 px-1">
        <span>{{ count($rows) }} (batch, seri)</span>
        <span>Selisih vs headline: <span class="font-bold text-emerald-600 dark:text-emerald-400">0</span></span>
    </div>
@endif
