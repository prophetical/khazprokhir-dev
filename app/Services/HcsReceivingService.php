<?php

namespace App\Services;

use App\Models\HcsReceiving;
use App\Models\Pack;
use App\Models\StockLedger;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;
use Exception;

class HcsReceivingService
{
    /**
     * Store a new HCS receiving record along with its packs, ledger updates, and audit logs.
     *
     * @param array $data
     * @param int $userId
     * @return HcsReceiving
     * @throws Exception
     */
    public function createReceiving(array $data, int $userId): HcsReceiving
    {
        try {
            DB::beginTransaction();

            // Create HCS Receiving
            $hcs = HcsReceiving::create([
                'nomor_bon' => $data['nomor_bon'],
                'tanggal_penerimaan' => $data['tanggal_penerimaan'],
                'pecahan' => $data['pecahan'],
                'jumlah' => $data['jumlah'],
                'gilir' => $data['gilir'],
                'mesin' => $data['mesin'],
                'supplier' => $data['supplier'],
                'batch' => $data['batch'],
                'seri' => $data['seri'],
                'emisi' => $data['emisi'],
                'repass' => $data['repass'] ?? null,
                'created_by' => $userId,
            ]);

            // Create Packs
            $selectedPacksCount = count($data['packs']);
            foreach ($data['packs'] as $packNumber) {
                Pack::create([
                    'hcs_receiving_id' => $hcs->id,
                    'batch' => $data['batch'],
                    'seri' => $data['seri'],
                    'pack_number' => $packNumber,
                    'supplier' => $data['supplier'],
                    'created_by' => $userId,
                ]);
            }

            // Update Stock Ledger
            $ledger = StockLedger::firstOrCreate(
            [
                'pecahan' => $data['pecahan'],
                'batch' => $data['batch'],
                'seri' => $data['seri'],
            ]
            );

            $ledger->increment('total_received', $data['jumlah']);
            $ledger->increment('total_packed', $selectedPacksCount);

            // Create Audit Log
            AuditLog::create([
                'user_id' => $userId,
                'action' => 'receiving_created',
                'module' => 'HCS Receiving',
                'record_id' => $hcs->id,
            ]);

            DB::commit();

            return $hcs;
        }
        catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
