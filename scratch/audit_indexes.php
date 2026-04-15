<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

$tables = [
    'x_pengganti_seris',
    'packs',
    'serial_range_mappings',
    'hcs_receivings',
    'hcs_sortings',
    'pengemasans',
    'detail_pengemasans',
    'stock_ledgers'
];

echo "INDEX AUDIT REPORT\n";
echo "==================\n\n";

foreach ($tables as $table) {
    if (!Schema::hasTable($table)) {
        echo "Table $table not found.\n\n";
        continue;
    }
    
    echo "Table: $table\n";
    try {
        $indexes = Schema::getIndexes($table);
        foreach ($indexes as $idx) {
            echo "- name: " . $idx['name'] . "\n";
            echo "  columns: " . implode(', ', $idx['columns']) . "\n";
            echo "  type: " . ($idx['type'] ?? 'btree') . "\n";
            echo "  unique: " . ($idx['unique'] ? 'true' : 'false') . "\n";
        }
    } catch (\Exception $e) {
        echo "Error getting indexes for $table: " . $e->getMessage() . "\n";
    }
    echo "\n";
}
