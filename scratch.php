<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SerialRangeMapping;
use Illuminate\Support\Facades\DB;
use App\Services\MappingAggregatorService;

$seriId = DB::table('serial_range_mappings')->select('x_pengganti_seri_id')->limit(1)->value('x_pengganti_seri_id');
if (!$seriId) {
    die("No mappings found. Please input data first.\n");
}

$packNumber = DB::table('serial_range_mappings')->where('x_pengganti_seri_id', $seriId)->value('nomor_pack');

$service = new MappingAggregatorService();

echo "--- Benchmarking Aggregator ---\n";

// 1. Full Recalculate
$start = microtime(true);
$service->recalculateFromMappings($seriId);
$timeFull = microtime(true) - $start;
echo "Full Recalculate (100 packs) : " . number_format($timeFull, 4) . "s\n";

// 2. Incremental Recalculate
$start = microtime(true);
$service->recalculateFromMappings($seriId, $packNumber);
$timeInc = microtime(true) - $start;
echo "Incremental (Surgical)       : " . number_format($timeInc, 4) . "s\n";

$speedup = (($timeFull - $timeInc) / $timeFull) * 100;
echo "Speedup                      : " . number_format($speedup, 2) . "%\n";
