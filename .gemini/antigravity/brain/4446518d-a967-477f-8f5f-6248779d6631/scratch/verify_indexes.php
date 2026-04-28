<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

foreach(['hcs_receivings', 'hcs_sortings', 'packs'] as $t) {
    echo "Index for $t:\n";
    $indexes = DB::select("SELECT indexname FROM pg_indexes WHERE tablename = ?", [$t]);
    foreach ($indexes as $idx) {
        echo "- " . $idx->indexname . "\n";
    }
    echo "\n";
}
