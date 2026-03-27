<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <script src="{{ asset('vendor/chartjs/chart.min.js') }}"></script>
    <div class="py-12" x-data="dashboardData()">
        <script>
            function dashboardData() {
                let chartInstance = null;
                return {
                    selectedPecahan: 'TOTAL',
                    chartData: @json($chartData),
                    months: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    colorThemes: {
                        'TOTAL': { bg: 'bg-white', button: 'bg-indigo-600', shadow: 'shadow-indigo-100' },
                        'S': { bg: 'bg-emerald-100/50', button: 'bg-emerald-500', shadow: 'shadow-emerald-100' },
                        'T': { bg: 'bg-slate-100/50', button: 'bg-slate-500', shadow: 'shadow-slate-100' },
                        'U': { bg: 'bg-amber-100/50', button: 'bg-amber-500', shadow: 'shadow-amber-100' },
                        'V': { bg: 'bg-purple-100/50', button: 'bg-purple-500', shadow: 'shadow-purple-100' },
                        'W': { bg: 'bg-green-100/50', button: 'bg-green-500', shadow: 'shadow-green-100' },
                        'X': { bg: 'bg-blue-100/50', button: 'bg-blue-500', shadow: 'shadow-blue-100' },
                        'Y': { bg: 'bg-red-100/50', button: 'bg-red-500', shadow: 'shadow-red-100' }
                    },
                    init() {
                        this.$nextTick(() => {
                            const canvas = document.getElementById('unified-chart');
                            if (!canvas) return;
                            const ctx = canvas.getContext('2d');

                            // Gradients
                            const kemasGradient = ctx.createLinearGradient(0, 0, 0, 400);
                            kemasGradient.addColorStop(0, 'rgba(16, 185, 129, 0.25)');
                            kemasGradient.addColorStop(1, 'rgba(16, 185, 129, 0)');

                            const serahGradient = ctx.createLinearGradient(0, 0, 0, 400);
                            serahGradient.addColorStop(0, 'rgba(219, 39, 119, 0.25)');
                            serahGradient.addColorStop(1, 'rgba(219, 39, 119, 0)');

                            const targetGradient = ctx.createLinearGradient(0, 0, 0, 400);
                            targetGradient.addColorStop(0, 'rgba(245, 158, 11, 0.15)');
                            targetGradient.addColorStop(1, 'rgba(245, 158, 11, 0)');

                            chartInstance = new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: this.months,
                                    datasets: [
                                        {
                                            label: 'Pengemasan',
                                            data: [...this.chartData[this.selectedPecahan].pengemasan],
                                            borderColor: '#10b981',
                                            backgroundColor: kemasGradient,
                                            fill: true,
                                            tension: 0.4,
                                            borderWidth: 4,
                                            pointRadius: 4,
                                            pointHoverRadius: 8,
                                            pointBackgroundColor: '#fff',
                                            pointBorderColor: '#10b981',
                                            pointBorderWidth: 3
                                        },
                                        {
                                            label: 'Penyerahan',
                                            data: [...this.chartData[this.selectedPecahan].penyerahan],
                                            borderColor: '#db2777',
                                            backgroundColor: serahGradient,
                                            fill: true,
                                            tension: 0.4,
                                            borderWidth: 4,
                                            pointRadius: 4,
                                            pointHoverRadius: 8,
                                            pointBackgroundColor: '#fff',
                                            pointBorderColor: '#db2777',
                                            pointBorderWidth: 3
                                        },
                                        {
                                            label: 'Target',
                                            data: [...this.chartData[this.selectedPecahan].target],
                                            borderColor: '#f59e0b',
                                            backgroundColor: targetGradient,
                                            fill: true,
                                            tension: 0.4,
                                            borderWidth: 3,
                                            pointRadius: 4,
                                            pointBackgroundColor: '#f59e0b',
                                            pointBorderColor: '#fff',
                                            pointBorderWidth: 2
                                        }
                                    ]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    interaction: { intersect: false, mode: 'index' },
                                    plugins: {
                                        legend: { display: false },
                                        tooltip: {
                                            backgroundColor: '#111827',
                                            padding: 16,
                                            titleFont: { size: 14, weight: '900' },
                                            bodyFont: { size: 14, weight: 'bold' },
                                            usePointStyle: true,
                                            boxPadding: 8,
                                            callbacks: {
                                                label: function (context) {
                                                    let label = context.dataset.label || '';
                                                    if (label) label += ': ';
                                                    if (context.parsed.y !== null) {
                                                        label += new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                                    }
                                                    return label;
                                                }
                                            }
                                        }
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            grid: { color: '#f3f4f6', drawBorder: false },
                                            ticks: {
                                                font: { size: 11, weight: '900' },
                                                color: '#9ca3af',
                                                padding: 10,
                                                callback: value => {
                                                    if (value >= 1000000) return (value / 1000000) + 'M';
                                                    if (value >= 1000) return (value / 1000) + 'k';
                                                    return value;
                                                }
                                            }
                                        },
                                        x: {
                                            grid: { display: false },
                                            ticks: { font: { size: 11, weight: '900' }, color: '#9ca3af', padding: 10 }
                                        }
                                    }
                                }
                            });
                        });
                    },
                    updateChart() {
                        if (!chartInstance) return;

                        const data = this.chartData[this.selectedPecahan];
                        if (!data) return;

                        chartInstance.data.datasets[0].data = [...data.pengemasan];
                        chartInstance.data.datasets[1].data = [...data.penyerahan];
                        chartInstance.data.datasets[2].data = [...data.target];

                        chartInstance.update();
                    },
                    heatmapData: @json($heatmapData),
                    getHeatmapColor(count) {
                        if (count === 0) return 'bg-gray-100/50';
                        if (count < 500000) return 'bg-emerald-200';
                        if (count < 2000000) return 'bg-emerald-400';
                        if (count < 5000000) return 'bg-emerald-600';
                        return 'bg-emerald-800';
                    },
                    formatBilyet(num) {
                        return new Intl.NumberFormat('id-ID').format(num);
                    },
                    initAnalytics() {
                        this.$nextTick(() => {
                            // 1. Pecahan Distribution
                            const pCtx = document.getElementById('pecahan-donut').getContext('2d');
                            new Chart(pCtx, {
                                type: 'doughnut',
                                data: {
                                    labels: Object.keys(@json($pecahanDistribution)),
                                    datasets: [{
                                        data: Object.values(@json($pecahanDistribution)),
                                        backgroundColor: ['#6366f1', '#10b981', '#ec4899', '#f59e0b', '#8b5cf6', '#22c55e', '#3b82f6'],
                                        borderWidth: 0,
                                        hoverOffset: 20
                                    }]
                                },
                                options: this.donutOptions('Distribusi Pecahan')
                            });

                            // 2. Production Lifecycle
                            const lCtx = document.getElementById('lifecycle-donut').getContext('2d');
                            new Chart(lCtx, {
                                type: 'doughnut',
                                data: {
                                    labels: ['Penerimaan', 'Pengemasan', 'Penyerahan'],
                                    datasets: [{
                                        data: [{{ $totalTerimaYear }}, {{ $totalKemasYear }}, {{ $totalSerahYear }}],
                                        backgroundColor: ['#4f46e5', '#10b981', '#f43f5e'],
                                        borderWidth: 0,
                                        hoverOffset: 20
                                    }]
                                },
                                options: this.donutOptions('Alur Produksi')
                            });

                            // 3. Supplier Distribution
                            const sCtx = document.getElementById('supplier-donut').getContext('2d');
                            new Chart(sCtx, {
                                type: 'doughnut',
                                data: {
                                    labels: Object.keys(@json($supplierDistributionYear)),
                                    datasets: [{
                                        data: Object.values(@json($supplierDistributionYear)),
                                        backgroundColor: ['#f97316', '#06b6d4'],
                                        borderWidth: 0,
                                        hoverOffset: 20
                                    }]
                                },
                                options: this.donutOptions('Proporsi Supplier')
                            });
                        });
                    },
                    donutOptions(title) {
                        return {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '75%',
                            plugins: {
                                legend: { 
                                    position: 'bottom',
                                    labels: { 
                                        padding: 20,
                                        usePointStyle: true,
                                        font: { size: 10, weight: '900' },
                                        color: '#6b7280'
                                    }
                                },
                                tooltip: {
                                    backgroundColor: '#111827',
                                    padding: 12,
                                    titleFont: { size: 11, weight: '900' },
                                    bodyFont: { size: 11, weight: 'bold' },
                                    callbacks: {
                                        label: (context) => {
                                            const val = context.raw;
                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                            const perc = ((val / total) * 100).toFixed(1) + '%';
                                            return ` ${context.label}: ${this.formatBilyet(val)} (${perc})`;
                                        }
                                    }
                                }
                            }
                        };
                    }
                };
            }
        </script>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            {{-- Summary Stats Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div
                    class="group bg-indigo-700 rounded-2xl p-4 relative hover:-translate-y-1 overflow-hidden flex flex-col justify-center">
                    <p
                        class="text-indigo-100 text-[9px] font-black uppercase tracking-widest mb-1 opacity-80 leading-none">
                        Total Penerimaan {{ $currentYear }}</p>
                    <p class="text-white text-xl font-black tracking-tight leading-none">
                        {{ number_format($totalTerimaYear, 0, ',', '.') }}
                    </p>
                </div>

                <div
                    class="group bg-emerald-600 rounded-2xl p-4 relative hover:-translate-y-1 overflow-hidden flex flex-col justify-center">
                    <p
                        class="text-emerald-100 text-[9px] font-black uppercase tracking-widest mb-1 opacity-80 leading-none">
                        Total Pengemasan {{ $currentYear }}</p>
                    <p class="text-white text-xl font-black tracking-tight leading-none">
                        {{ number_format($totalKemasYear, 0, ',', '.') }}
                    </p>
                </div>

                <div
                    class="group bg-pink-600 rounded-2xl p-4 relative hover:-translate-y-1 overflow-hidden flex flex-col justify-center">
                    <p
                        class="text-pink-100 text-[9px] font-black uppercase tracking-widest mb-1 opacity-80 leading-none">
                        Total Penyerahan {{ $currentYear }}</p>
                    <p class="text-white text-xl font-black tracking-tight leading-none">
                        {{ number_format($totalSerahYear, 0, ',', '.') }}
                    </p>
                </div>

                <div
                    class="group bg-amber-500 rounded-2xl p-4 hover:-translate-y-1 relative overflow-hidden flex flex-col justify-center">
                    <div class="flex items-center justify-between mb-1 text-[9px]">
                        <p class="text-amber-50 font-black uppercase tracking-widest opacity-80 leading-none">Total
                            Target {{ $currentYear }}</p>
                        <span
                            class="font-black text-white bg-white/20 px-1.5 py-0.5 rounded-full backdrop-blur-sm">{{ $totalTargetYear > 0 ? round(($totalSerahYear / $totalTargetYear) * 100) : 0 }}%</span>
                    </div>
                    <p class="text-white text-xl font-black tracking-tight leading-none mb-2">
                        {{ number_format($totalTargetYear, 0, ',', '.') }}
                    </p>
                    <div class="w-full bg-white/20 h-1 rounded-full overflow-hidden">
                        <div class="bg-white h-full transition-all duration-1000 rounded-full"
                            style="width: {{ $totalTargetYear > 0 ? min(100, ($totalSerahYear / $totalTargetYear) * 100) : 0 }}%">
                        </div>
                    </div>
                </div>
            </div>

            {{-- New Header with Year Filters --}}
            <div
                class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-md font-black text-gray-900 tracking-tight leading-none mb-1">PRODUKSI & TARGET
                        </h2>
                        <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest">Real-time Performance
                            Monitoring</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 bg-gray-50/50 p-2 rounded-2xl border border-gray-100">
                    <form action="{{ route('dashboard') }}" method="GET" class="flex flex-wrap items-center gap-4">
                        <div class="flex items-center">
                            <span class="text-[10px] font-black text-gray-400 uppercase px-4 cursor-default">Tahun
                                Anggaran:</span>
                            <select name="tahun_anggaran" onchange="this.form.submit()"
                                class="bg-white border-0 rounded-xl text-sm font-black text-indigo-600 shadow-sm focus:ring-2 focus:ring-indigo-500 py-2 min-w-[120px] text-center cursor-pointer">
                                @foreach($availableYears as $year)
                                    <option value="{{ $year }}" {{ $currentYear == $year ? 'selected' : '' }}>{{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-px h-6 bg-gray-200 hidden md:block"></div>

                        <div class="flex items-center">
                            <span class="text-[10px] font-black text-gray-400 uppercase px-4 cursor-default">Tahun
                                Emisi:</span>
                            <select name="tahun_emisi" onchange="this.form.submit()"
                                class="bg-white border-0 rounded-xl text-sm font-black text-pink-600 shadow-sm focus:ring-2 focus:ring-pink-500 py-2 min-w-[120px] text-center cursor-pointer">
                                <option value="">Semua Emisi</option>
                                @foreach($availableEmissions as $emisi)
                                    <option value="{{ $emisi }}" {{ $currentTE == $emisi ? 'selected' : '' }}>{{ $emisi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>
            {{-- Side-by-Side Charts Section --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- Unified Production Chart --}}
                <div :class="colorThemes[selectedPecahan].bg"
                    class="p-6 rounded-[2.5rem] shadow-xl shadow-gray-200/40 border border-gray-100 relative overflow-hidden transition-colors duration-700">
                    <div class="flex flex-col xl:flex-row items-start xl:items-center justify-between mb-12 gap-8">
                        <div class="flex items-center gap-6">
                            <div
                                class="w-1.5 h-12 bg-gradient-to-b from-indigo-600 via-pink-500 to-amber-500 rounded-full">
                            </div>
                            <div>
                                <h3
                                    class="text-md font-black text-gray-900 tracking-tighter mb-0.5 uppercase leading-none">
                                    Visualisasi Trends</h3>
                                <p class="text-[7px] text-gray-400 font-black uppercase tracking-[0.2em] leading-none">
                                    Target Penyerahan Bulanan</p>
                            </div>
                        </div>

                        {{-- Legend --}}
                        <div
                            class="flex items-center gap-4 bg-gray-50/50 px-3 py-1.5 rounded-xl border border-gray-100/50">
                            <div class="flex items-center gap-1.5 group">
                                <div
                                    class="w-2 h-2 rounded-full bg-emerald-500 shadow-lg shadow-emerald-200 group-hover:scale-125 transition-transform">
                                </div>
                                <span
                                    class="text-[8px] font-black text-gray-500 uppercase tracking-widest whitespace-nowrap">Pengemasan</span>
                            </div>
                            <div class="flex items-center gap-1.5 group">
                                <div
                                    class="w-2 h-2 rounded-full bg-pink-500 shadow-lg shadow-pink-200 group-hover:scale-125 transition-transform">
                                </div>
                                <span
                                    class="text-[8px] font-black text-gray-500 uppercase tracking-widest whitespace-nowrap">Penyerahan</span>
                            </div>
                            <div class="flex items-center gap-1.5 group">
                                <div class="flex gap-0.5 group-hover:gap-1 transition-all">
                                    <div class="w-1 h-1 rounded-full bg-amber-500"></div>
                                    <div class="w-1 h-1 rounded-full bg-amber-500"></div>
                                    <div class="w-1 h-1 rounded-full bg-amber-500"></div>
                                </div>
                                <span
                                    class="text-[8px] font-black text-gray-500 uppercase tracking-widest whitespace-nowrap">Target</span>
                            </div>
                        </div>
                    </div>

                    {{-- Pecahan Selector Buttons --}}
                    <div
                        class="flex flex-wrap items-center gap-1 bg-gray-50/80 p-1 rounded-2xl border border-gray-100 mb-8 w-fit mx-auto lg:mx-0">
                        <button @click="selectedPecahan = 'TOTAL'; updateChart()"
                            :class="selectedPecahan === 'TOTAL' ? (colorThemes['TOTAL'].button + ' text-white shadow-lg ' + colorThemes['TOTAL'].shadow) : 'text-gray-500 hover:bg-gray-100'"
                            class="px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-[0.1em] transition-all duration-500">
                            OVERVIEW
                        </button>
                        <div class="w-px h-4 bg-gray-200 mx-1 hidden sm:block"></div>
                        @foreach(['S', 'T', 'U', 'V', 'W', 'X', 'Y'] as $pec)
                            <button @click="selectedPecahan = '{{ $pec }}'; updateChart()"
                                :class="selectedPecahan === '{{ $pec }}' ? (colorThemes['{{ $pec }}'].button + ' text-white shadow-md scale-105 ' + colorThemes['{{ $pec }}'].shadow) : 'text-gray-500 hover:bg-gray-100'"
                                class="w-7 h-7 rounded-lg text-[10px] font-black transition-all duration-500 flex items-center justify-center">
                                {{ $pec }}
                            </button>
                        @endforeach
                    </div>

                    <div class="h-[300px] w-full relative">
                        <canvas id="unified-chart"></canvas>
                    </div>
                </div>

                {{-- Compact Heatmap Section --}}
                <div
                    class="bg-white p-6 rounded-[2.5rem] shadow-xl shadow-gray-200/20 border border-gray-100 overflow-hidden">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 shadow-inner">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xs font-black text-gray-900 leading-none uppercase tracking-wider">
                                    Heatmap Pengemasan TA {{ $currentYear }} / TE {{ $currentTE ?: 'SEMUA' }}
                                </h3>
                                <p class="text-[8px] text-gray-400 font-black uppercase tracking-widest mt-0.5">
                                    Visualisasi
                                    Intensitas Produksi Harian</p>
                            </div>
                        </div>
                        <div
                            class="flex items-center gap-3 px-3 py-1.5 bg-gray-50 rounded-xl border border-gray-100 scale-90 origin-right">
                            <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Less</span>
                            <div class="flex gap-0.5">
                                <div class="w-2.5 h-2.5 rounded-sm bg-gray-100"></div>
                                <div class="w-2.5 h-2.5 rounded-sm bg-emerald-200"></div>
                                <div class="w-2.5 h-2.5 rounded-sm bg-emerald-400"></div>
                                <div class="w-2.5 h-2.5 rounded-sm bg-emerald-600"></div>
                                <div class="w-2.5 h-2.5 rounded-sm bg-emerald-800"></div>
                            </div>
                            <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest">More</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-x-4 gap-y-6">
                        @foreach($months as $mIdx)
                            @php
                                $monthObj = \Carbon\Carbon::create($heatmapYear, $mIdx, 1);
                                $monthName = $monthObj->translatedFormat('F');
                                $daysInMonth = $monthObj->daysInMonth;
                                $firstDayOfMonth = $monthObj->dayOfWeekIso; // 1 (Mon) to 7 (Sun)
                            @endphp
                            <div class="space-y-4">
                                <div class="flex flex-col items-center">
                                    <span
                                        class="text-[9px] font-black text-gray-500 uppercase tracking-[0.1em] mb-2">{{ $monthName }}</span>
                                    {{-- Days Initials Header --}}
                                    <div class="grid grid-cols-7 gap-1 w-full text-center px-1 mb-1">
                                        @foreach(['S', 'S', 'R', 'K', 'J', 'S', 'M'] as $day)
                                            <span class="text-[7px] font-black text-gray-300">{{ $day }}</span>
                                        @endforeach
                                    </div>
                                    <div class="grid grid-cols-7 gap-1 w-full justify-items-center">
                                        {{-- Empty slots before first day of month --}}
                                        @for($i = 1; $i < $firstDayOfMonth; $i++)
                                            <div class="w-3 h-3"></div>
                                        @endfor

                                        {{-- Days --}}
                                        @for($d = 1; $d <= $daysInMonth; $d++)
                                            @php
                                                $dateStr = sprintf('%s-%02d-%02d', $heatmapYear, $mIdx, $d);
                                                $count = $heatmapData[$dateStr] ?? 0;
                                            @endphp
                                            <div x-data="{ count: {{ $count }} }"
                                                class="w-3 h-3 rounded-sm transition-all duration-300 hover:scale-150 hover:z-10 cursor-pointer relative group"
                                                :class="getHeatmapColor(count)">
                                                {{-- Tooltip --}}
                                                <div
                                                    class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block z-50">
                                                    <div
                                                        class="bg-gray-900 text-white text-[8px] font-bold py-1.5 px-2 rounded-lg shadow-xl whitespace-nowrap">
                                                        <p class="mb-0.5 text-gray-400">
                                                            {{ \Carbon\Carbon::parse($dateStr)->translatedFormat('d M Y') }}
                                                        </p>
                                                        <p class="text-emerald-400 leading-none"
                                                            x-text="formatBilyet(count) + ' Bilyet'"></p>
                                                    </div>
                                                    <div class="w-1.5 h-1.5 bg-gray-900 rotate-45 mx-auto -mt-1"></div>
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Analytics Donuts Section --}}
            <div x-init="initAnalytics()"
                class="bg-white p-10 rounded-[3rem] shadow-xl shadow-gray-200/20 border border-gray-100 relative overflow-hidden">
                <div class="flex items-center gap-4 mb-12">
                    <div class="w-1.5 h-10 bg-indigo-600 rounded-full"></div>
                    <div>
                        <h3 class="text-xl font-black text-gray-900 tracking-tighter uppercase leading-none mb-1">
                            Ringkasan Analitik Produksi</h3>
                        <p class="text-[9px] text-gray-400 font-black uppercase tracking-[0.2em]">Visualisasi Proporsi &
                            Statistik Tahunan</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                    {{-- Pecahan Donut --}}
                    <div class="space-y-6">
                        <div class="h-[220px] w-full relative">
                            <canvas id="pecahan-donut"></canvas>
                        </div>
                        <p class="text-center text-[9px] font-black text-gray-400 uppercase tracking-widest">Komposisi Pecahan</p>
                    </div>

                    {{-- Lifecycle Donut --}}
                    <div class="space-y-6">
                        <div class="h-[220px] w-full relative">
                            <canvas id="lifecycle-donut"></canvas>
                        </div>
                        <p class="text-center text-[9px] font-black text-gray-400 uppercase tracking-widest">Alur Lifecycle</p>
                    </div>

                    {{-- Supplier Donut --}}
                    <div class="space-y-6">
                        <div class="h-[220px] w-full relative">
                            <canvas id="supplier-donut"></canvas>
                        </div>
                        <p class="text-center text-[9px] font-black text-gray-400 uppercase tracking-widest">Rasio Supplier</p>
                    </div>
                </div>
            </div>

            {{-- Module Grid & System Status --}}
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                {{-- System Status Card --}}
                <div
                    class="lg:col-span-1 bg-white rounded-[2.5rem] shadow-xl shadow-gray-200/20 border border-gray-100 flex flex-col justify-between overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-50 bg-gray-50/50">
                        <h3 class="text-[10px] font-black text-gray-800 uppercase tracking-[0.2em]">Live Status</h3>
                    </div>
                    <div class="p-8 space-y-5">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-[10px] font-black text-gray-400 uppercase">App Engine</span>
                            <span class="flex items-center gap-2 font-black text-emerald-500 text-[10px] uppercase">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Stable
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-[10px] font-black text-gray-400 uppercase">Database</span>
                            <span class="flex items-center gap-2 font-black text-emerald-500 text-[10px] uppercase">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> SYNCED
                            </span>
                        </div>
                        <div class="py-4 border-y border-gray-50">
                            <p class="text-[10px] font-black text-gray-400 uppercase mb-2">Authenticated User</p>
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600 font-black text-xs">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-xs font-black text-gray-800">{{ auth()->user()->name }}</p>
                                    <span
                                        class="text-[9px] font-black text-indigo-500 uppercase tracking-widest">{{ auth()->user()->role }}</span>
                                </div>
                            </div>
                        </div>
                        <div x-data="{ 
                            time: '',
                            init() {
                                this.updateClock();
                                setInterval(() => this.updateClock(), 1000);
                            },
                            updateClock() {
                                const now = new Date();
                                const options = { 
                                    weekday: 'short', 
                                    day: '2-digit', 
                                    month: 'short', 
                                    year: 'numeric', 
                                    hour: '2-digit', 
                                    minute: '2-digit', 
                                    second: '2-digit',
                                    hour12: false,
                                    timeZone: 'Asia/Jakarta'
                                };
                                this.time = now.toLocaleString('id-ID', options).replace(/\./g, ':');
                            }
                        }">
                            <p class="text-[10px] font-black text-gray-400 uppercase mb-1">Server time</p>
                            <p class="text-xs font-black text-gray-700 font-mono" x-text="time"></p>
                        </div>
                    </div>
                    <div class="bg-gray-900 p-4 text-center">
                        <span class="text-[8px] font-black text-white/40 uppercase tracking-[0.4em]">KHAZPROKHIR</span>
                    </div>
                </div>

                {{-- Navigation Grid --}}
                <div class="lg:col-span-3 grid grid-cols-2 sm:grid-cols-3 gap-6">
                    @php
                        $modules = [
                            ['icon' => 'M2.25 13.5h3.86a2.25 2.25 0 012.012 1.244l.256.512a2.25 2.25 0 002.013 1.244h3.218a2.25 2.25 0 002.013-1.244l.256-.512a2.25 2.25 0 012.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18v-4.162c0-2.24-1.815-4.062-4.062-4.062h-11.376c-2.247 0-4.062 1.822-4.062 4.062zM15 7.5l-3 3m0 0l-3-3m3 3v-7.5', 'label' => 'Penerimaan HCS', 'color' => 'indigo', 'route' => 'hcs-receiving.index', 'bg' => 'bg-white'],
                            ['icon' => 'M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z', 'label' => 'Penerimaan HCTS', 'color' => 'rose', 'route' => 'hcts-receiving.index', 'bg' => 'bg-white'],
                            ['icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4', 'label' => 'Penyortiran HCS', 'color' => 'pink', 'route' => 'hcs-sorting.index', 'bg' => 'bg-white'],
                            ['icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', 'label' => 'Pengemasan HCS', 'color' => 'emerald', 'route' => 'pengemasan.index', 'bg' => 'bg-white'],
                            ['icon' => 'M7.5 7.5h-.75A2.25 2.25 0 004.5 9.75v7.5a2.25 2.25 0 002.25 2.25h7.5a2.25 2.25 0 002.25-2.25v-7.5a2.25 2.25 0 00-2.25-2.25h-.75m0-3l-3-3m0 0l-3 3m3-3v11.25m6-2.25h.75a2.25 2.25 0 012.25 2.25v7.5a2.25 2.25 0 01-2.25 2.25h-7.5a2.25 2.25 0 01-2.25-2.25v-.75', 'label' => 'Penyerahan ke BI', 'color' => 'orange', 'route' => 'penyerahan-bi.index', 'bg' => 'bg-white'],
                            ['icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'label' => 'Laporan Harian', 'color' => 'blue', 'route' => 'laporan-harian.index', 'bg' => 'bg-white'],
                            ['icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z', 'label' => 'Pengaturan', 'color' => 'gray', 'route' => 'profile.edit', 'bg' => 'bg-white/50 border-dashed'],
                        ];
                    @endphp
                    @foreach($modules as $mod)
                        <a href="{{ route($mod['route']) }}"
                            class="{{ $mod['bg'] }} flex flex-col items-center justify-center p-8 rounded-[2.5rem] border border-gray-100 hover:border-{{ $mod['color'] }}-400 hover:shadow-2xl hover:shadow-{{ $mod['color'] }}-100/50 hover:-translate-y-2 transition-all duration-500 group">
                            <div
                                class="mb-5 flex items-center justify-center w-16 h-16 rounded-[1.5rem] bg-{{ $mod['color'] }}-50 text-{{ $mod['color'] }}-600 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 shadow-inner">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="{{ $mod['icon'] }}" />
                                </svg>
                            </div>
                            <span
                                class="text-[10px] font-black text-gray-800 uppercase tracking-[0.2em] group-hover:text-{{ $mod['color'] }}-600 transition-colors">{{ $mod['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-app-layout>