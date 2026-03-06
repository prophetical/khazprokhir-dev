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

    /**
     * Update an existing HCS receiving record, reverting old ledgers and recalculating.
     */
    public function updateReceiving(HcsReceiving $hcs, array $data, int $userId): HcsReceiving
    {
        try {
            DB::beginTransaction();

            // 1. Revert Old Stock Ledger
            $oldPacksCount = $hcs->packs()->count();
            $oldLedger = StockLedger::where([
                'pecahan' => $hcs->pecahan,
                'batch' => $hcs->batch,
                'seri' => $hcs->seri,
            ])->first();

            if ($oldLedger) {
                // Determine actual amount to decrement avoiding negative
                $decReceived = min($oldLedger->total_received, $hcs->jumlah);
                $decPacked = min($oldLedger->total_packed, $oldPacksCount);

                $oldLedger->decrement('total_received', $decReceived);
                $oldLedger->decrement('total_packed', $decPacked);
            }

            // 2. Delete Old Packs
            $hcs->packs()->delete();

            // 3. Update HCS Record
            $hcs->update([
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
                'updated_by' => $userId,
            ]);

            // 4. Create New Packs
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

            // 5. Update New Stock Ledger
            $newLedger = StockLedger::firstOrCreate([
                'pecahan' => $data['pecahan'],
                'batch' => $data['batch'],
                'seri' => $data['seri'],
            ]);

            $newLedger->increment('total_received', $data['jumlah']);
            $newLedger->increment('total_packed', $selectedPacksCount);

            // 6. Audit Log
            AuditLog::create([
                'user_id' => $userId,
                'action' => 'receiving_updated',
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

    /**
     * Delete an existing HCS receiving record and revert side-effects.
     */
    public function deleteReceiving(HcsReceiving $hcs, int $userId): bool
    {
        try {
            DB::beginTransaction();

            // Revert Stock Ledger
            $oldPacksCount = $hcs->packs()->count();
            $ledger = StockLedger::where([
                'pecahan' => $hcs->pecahan,
                'batch' => $hcs->batch,
                'seri' => $hcs->seri,
            ])->first();

            if ($ledger) {
                $decReceived = min($ledger->total_received, $hcs->jumlah);
                $decPacked = min($ledger->total_packed, $oldPacksCount);

                $ledger->decrement('total_received', $decReceived);
                $ledger->decrement('total_packed', $decPacked);
            }

            // Delete Packs
            $hcs->packs()->delete();

            $id = $hcs->id;

            // Delete HCS
            $hcs->delete();

            // Audit
            AuditLog::create([
                'user_id' => $userId,
                'action' => 'receiving_deleted',
                'module' => 'HCS Receiving',
                'record_id' => $id,
            ]);

            DB::commit();

            return true;
        }
        catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
