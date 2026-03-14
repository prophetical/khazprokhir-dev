<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Pengemasan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

$tanggalLaporan = '2026-03-12';
$tahunAnggaran = '2025';
$tahunEmisi = '2022';
$pecahan = 'S';

$kemasDate = Carbon::parse($tanggalLaporan)->subDay()->toDateString();
echo "Testing for Tanggal Laporan: $tanggalLaporan (Kemas Date: $kemasDate)\n";

$kemasG1Query = Pengemasan::where('pecahan', $pecahan)
    ->where('tahun_anggaran', $tahunAnggaran)
    ->whereDate('tanggal_pengemasan', $kemasDate)
    ->where('gilir', '1');

if ($tahunEmisi) {
    $kemasG1Query->where('tahun_emisi', $tahunEmisi);
}

$result = $kemasG1Query->sum(DB::raw('jumlah_dus * 20000'));
echo "Result for G1 (with whereDate): $result\n";

$checkPecahanDate = Pengemasan::where('pecahan', $pecahan)
    ->whereDate('tanggal_pengemasan', $kemasDate)
    ->get();
echo "Any data for $pecahan on $kemasDate with whereDate: ".$checkPecahanDate->count()."\n";
foreach ($checkPecahanDate as $c) {
    echo " - Gilir: '{$c->gilir}', TA: {$c->tahun_anggaran}, TE: {$c->tahun_emisi}\n";
}
