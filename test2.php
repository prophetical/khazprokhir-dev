<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $seri = App\Models\XPenggantiSeri::first();
    $s = app(App\Services\ReplacementMappingService::class);
    // Use pack 701, replacement ZZ, YY, pack rep 1
    $r = $s->registerPackReplacement($seri->id, 701, 'ZZ', 'YY', 1, 1);
    echo "SUCCESS: " . $r;
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
