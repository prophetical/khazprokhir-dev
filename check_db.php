<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Pengemasan;
use Illuminate\Support\Facades\DB;

$date = '2026-03-10'; // Assuming H-1 if today is 11
$pecahan = 'S';

$data = Pengemasan::where('tanggal_pengemasan', $date)->get();

echo "Data for $date:\n";
foreach ($data as $row) {
    echo "ID: {$row->id}, Pecahan: {$row->pecahan}, TA: {$row->tahun_anggaran}, TE: {$row->tahun_emisi}, Gilir: '{$row->gilir}', Jml Dus: {$row->jumlah_dus}\n";
}

if ($data->isEmpty()) {
    echo "No data found for $date. Checking any data in pengemasans table:\n";
    $latest = Pengemasan::latest('tanggal_pengemasan')->limit(5)->get();
    foreach ($latest as $row) {
        echo "ID: {$row->id}, Date: {$row->tanggal_pengemasan}, Pecahan: {$row->pecahan}, TA: {$row->tahun_anggaran}, TE: {$row->tahun_emisi}, Gilir: '{$row->gilir}', Jml Dus: {$row->jumlah_dus}\n";
    }
}
