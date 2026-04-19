<?php

namespace App\Console\Commands;

use App\Models\SerialRangeMapping;
use App\Services\MappingAggregatorService;
use Illuminate\Console\Command;

class SyncMappingsCommand extends Command
{
    protected $signature = 'mapping:sync';
    protected $description = 'Melakukan sinkronisasi silang semua data mapping serial_range_mappings ke tabel Khazai, Cutpack, dan Rikyet secara paksa.';

    public function handle(MappingAggregatorService $aggregatorService)
    {
        $this->info('Mulai sinkronisasi Mapping -> Modules...');

        $seriIds = SerialRangeMapping::select('x_pengganti_seri_id')->distinct()->pluck('x_pengganti_seri_id');
        
        if ($seriIds->isEmpty()) {
            $this->warn('Belum ada data mapping sama sekali.');
            return;
        }

        $bar = $this->output->createProgressBar($seriIds->count());
        $bar->start();

        foreach ($seriIds as $seriId) {
            try {
                $aggregatorService->recalculateFromMappings((int) $seriId);
            } catch (\Exception $e) {
                $this->error("\nError pada seri ID {$seriId}: " . $e->getMessage());
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        
        $vell = \App\Models\XPenggantiPack::where('jumlah_rusak_vell', '>', 0)->count();
        $cutpack = \App\Models\XPenggantiCutpackPack::where('total_rusak_campuran', '>', 0)
            ->orWhere('total_rusak_seri_1', '>', 0)
            ->orWhere('total_rusak_seri_2', '>', 0)->count();
        $rikyet = \App\Models\XPenggantiRikyetPack::where('total_rusak_seri_1', '>', 0)
            ->orWhere('total_rusak_seri_2', '>', 0)
            ->orWhere('total_rusak_campuran', '>', 0)->count();

        $this->info("Sinkronisasi Selesai.");
        $this->line("Detail Pack Terdampak:");
        $this->line("  - Khazai  : {$vell} pack ber-Vell");
        $this->line("  - Cutpack : {$cutpack} pack ber-Bilyet");
        $this->line("  - Rikyet  : {$rikyet} pack ber-Brood/Pack");
    }
}
