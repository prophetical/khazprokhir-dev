<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\HcsReceiving;
use App\Models\Pack;
use App\Models\StockLedger;
use Exception;
use Illuminate\Support\Facades\DB;

class HcsReceivingService
{
    /**
     * Menyimpan data penerimaan HCS baru beserta data pack, pembaruan buku stok, dan log audit.
     *
     * @throws Exception
     */
    public function createReceiving(array $data, int $userId): HcsReceiving
    {
        $this->validateReceiving($data);
        try {
            DB::beginTransaction();

            // Membuat entri data penerimaan HCS
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
                'tahun_anggaran' => $data['tahun_anggaran'],
                'repass' => $data['repass'] ?? null,
                'created_by' => $userId,
            ]);

            // Membuat data pack terkait
            $selectedPacksCount = count($data['packs']);
            $isManual = $data['is_manual'] ?? false;
            $now = now();
            $packsToInsert = [];

            foreach ($data['packs'] as $packNumber) {
                $packsToInsert[] = [
                    'hcs_receiving_id' => $hcs->id,
                    'batch' => $data['batch'],
                    'seri' => $data['seri'],
                    'pack_number' => $packNumber,
                    'supplier' => $data['supplier'],
                    'jumlah' => $isManual ? $data['jumlah'] : 45000,
                    'created_by' => $userId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            // Melakukan Bulk Insert untuk penulisan database yang sangat cepat (menghindari masalah N+1)
            foreach (array_chunk($packsToInsert, 500) as $chunk) {
                Pack::insert($chunk);
            }

            // Memperbarui buku stok (ledger)
            $ledger = StockLedger::firstOrCreate(
                [
                    'pecahan' => $data['pecahan'],
                    'batch' => $data['batch'],
                    'seri' => $data['seri'],
                ]
            );

            $ledger->increment('total_received', $data['jumlah']);
            $ledger->increment('total_packed', $selectedPacksCount);

            // Catat di log audit
            AuditLog::create([
                'user_id' => $userId,
                'action' => 'receiving_created',
                'module' => 'HCS Receiving',
                'record_id' => $hcs->id,
            ]);

            DB::commit();

            return $hcs;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Memperbarui data penerimaan HCS yang sudah ada, mengembalikan posisi stok lama, dan menghitung ulang.
     */
    public function updateReceiving(HcsReceiving $hcs, array $data, int $userId): HcsReceiving
    {
        $this->validateUpdate($hcs, $data);
        try {
            DB::beginTransaction();

            $sortedPacks = $hcs->packs()->whereNotNull('hcs_sorting_id')->get()->keyBy('pack_number');

            // 1. Mengembalikan saldo data buku stok yang lama
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

            // 2. Menghapus data pack lama (hanya yang belum disortir untuk menghindari kesalahan)
            $hcs->packs()->whereNull('hcs_sorting_id')->delete();

            // 3. Memperbarui informasi data penerimaan HCS
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
                'tahun_anggaran' => $data['tahun_anggaran'],
                'repass' => $data['repass'] ?? null,
                'updated_by' => $userId,
            ]);

            // 4. Membuat data pack baru (tanpa membuat ulang pack yang sudah disortir)
            $selectedPacksCount = count($data['packs']);
            $isManual = $data['is_manual'] ?? false;
            $now = now();
            $packsToInsert = [];

            foreach ($data['packs'] as $packNumber) {
                if (! $sortedPacks->has($packNumber)) {
                    $packsToInsert[] = [
                        'hcs_receiving_id' => $hcs->id,
                        'batch' => $data['batch'],
                        'seri' => $data['seri'],
                        'pack_number' => $packNumber,
                        'supplier' => $data['supplier'],
                        'jumlah' => $isManual ? $data['jumlah'] : 45000,
                        'created_by' => $userId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            if (!empty($packsToInsert)) {
                foreach (array_chunk($packsToInsert, 500) as $chunk) {
                    Pack::insert($chunk);
                }
            }

            // 5. Memperbarui data buku stok dengan informasi yang baru
            $newLedger = StockLedger::firstOrCreate([
                'pecahan' => $data['pecahan'],
                'batch' => $data['batch'],
                'seri' => $data['seri'],
            ]);

            $newLedger->increment('total_received', $data['jumlah']);
            $newLedger->increment('total_packed', $selectedPacksCount);

            // 6. Mencatat aktivitas ke dalam log audit
            AuditLog::create([
                'user_id' => $userId,
                'action' => 'receiving_updated',
                'module' => 'HCS Receiving',
                'record_id' => $hcs->id,
            ]);

            DB::commit();

            return $hcs;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Menghapus data penerimaan HCS yang ada dan mengembalikan saldo data buku stok.
     */
    public function deleteReceiving(HcsReceiving $hcs, int $userId): bool
    {
        try {
            DB::beginTransaction();

            if ($hcs->packs()->whereNotNull('hcs_sorting_id')->exists()) {
                throw new Exception('Data tidak dapat dihapus karena pack sudah disortir.');
            }

            // Mengembalikan saldo data buku stok
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

            // Menghapus data pack terkait
            $hcs->packs()->delete();

            $id = $hcs->id;

            // Menghapus data utama penerimaan HCS
            $hcs->delete();

            // Catat di log audit
            AuditLog::create([
                'user_id' => $userId,
                'action' => 'receiving_deleted',
                'module' => 'HCS Receiving',
                'record_id' => $id,
            ]);

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function validateReceiving(array $data)
    {
        $isManual = $data['is_manual'] ?? false;
        $jumlah = $data['jumlah'];
        if ($isManual) {
            if ($jumlah > 45000) throw new Exception('Jumlah bilyet tidak boleh melebihi 45.000 untuk pack tidak full.');
            $packsNeeded = 1;
        } else {
            if ($jumlah % 45000 !== 0) throw new Exception('Jumlah bilyet harus kelipatan 45.000.');
            $packsNeeded = $jumlah / 45000;
        }

        if (count($data['packs']) !== (int)$packsNeeded) {
            throw new Exception("Jumlah packs yang dipilih (".count($data['packs']).") tidak sesuai kebutuhan ($packsNeeded).");
        }
    }

    public function validateUpdate(HcsReceiving $hcs, array $data)
    {
        $this->validateReceiving($data);

        if ($data['pecahan'] !== $hcs->pecahan || $data['batch'] !== $hcs->batch || $data['seri'] !== $hcs->seri || $data['emisi'] != $hcs->emisi || $data['tahun_anggaran'] != $hcs->tahun_anggaran) {
            throw new Exception('Tahun Anggaran, Emisi, Pecahan, Batch, dan Seri tidak boleh diubah.');
        }

        $sortedPacks = $hcs->packs()->whereNotNull('hcs_sorting_id')->pluck('pack_number')->toArray();
        if (!empty($sortedPacks)) {
            if (count($data['packs']) < count($sortedPacks)) throw new Exception('Jumlah pack tidak boleh kurang dari pack yang sudah disortir ('.count($sortedPacks).' pack).');
            if (!empty(array_diff($sortedPacks, $data['packs']))) throw new Exception('Pack yang sudah disortir tidak boleh dibuang.');
        }
    }
}
