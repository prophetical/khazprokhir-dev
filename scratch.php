<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SerialRangeMapping;
use Illuminate\Support\Facades\DB;

$count = SerialRangeMapping::count();
echo "Total Mappings: $count\n";

$seriId = DB::table('serial_range_mappings')->select('x_pengganti_seri_id')->limit(1)->value('x_pengganti_seri_id');
if ($seriId) {
    $start = microtime(true);
    $totalMappings = SerialRangeMapping::forSeri($seriId)->count();
    $totalBilyet   = SerialRangeMapping::forSeri($seriId)->selectRaw('SUM(source_end - source_start + 1) as total')->value('total') ?? 0;
    $end = microtime(true);
    echo "Stats Calculation Time: " . ($end - $start) . "s\n";
    
    $start = microtime(true);
    (new \App\Services\MappingAggregatorService())->recalculateFromMappings($seriId);
    $end = microtime(true);
    echo "Full Aggregator Time: " . ($end - $start) . "s\n";
}
