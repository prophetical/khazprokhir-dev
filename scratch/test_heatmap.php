<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Pengemasan;

$currentYear = 2026;
$currentTE = 2022;

$heatmapData = Pengemasan::where('tahun_anggaran', (string)$currentYear)
    ->when($currentTE, fn($q) => $q->where('tahun_emisi', $currentTE))
    ->selectRaw('tanggal_pengemasan as date, SUM(total_bilyet) as total')
    ->groupBy('tanggal_pengemasan')
    ->pluck('total', 'date')
    ->toArray();

print_r($heatmapData);
