<?php

namespace App\Services;

use App\Models\SerialRangeMapping;
use App\Models\XPenggantiSeri;
use App\Models\XPenggantiPack;
use App\Models\XPenggantiCutpackPack;
use App\Models\XPenggantiRikyetPack;
use Illuminate\Support\Facades\DB;

/**
 * Catatan author (Syah Roni)
 *  MappingAggregatorService — Engine kalkulasi ulang agregat kerusakan.
 *
 * Service ini dipanggil oleh SerialMappingController setiap kali ada
 * operasi insert atau delete di tabel serial_range_mappings, kemudian
 * secara otomatis menghitung ulang (recalculate) jumlah rusak di:
 *
 *   - x_pengganti_packs          (Khazai)  → jumlah_rusak_vell
 *   - x_pengganti_cutpack_packs  (Cutpack) → total_rusak_seri_1 / seri_2 / campuran
 *   - x_pengganti_rikyet_packs   (Rikyet)  → total_rusak_seri_1 / seri_2 / campuran
 *
 * Strategi: REPLACE (bukan INCREMENT).
 * Setiap recalculate mengambil angka segar dari serial_range_mappings,
 * sehingga operasi hapus mapping pun otomatis mengurangi jumlah.
 *
 * Konversi Pack Absolut → Relatif (1–100):
 *   Pack absolut tersimpan di serial_range_mappings.nomor_pack (misal: 701).
 *   Pack relatif = nomor_pack_absolut - pack_start_batch + 1 (misal: 1).
 *   pack_start_batch diambil dari SerialPrefixGenerator::parseSeriLabel().
 *
 * Counting Logic:
 *   Vell  : COUNT(DISTINCT source_start) per pack — 1 vell op = 45 baris, 1 source_start
 *   Bilyet : COUNT(*) per pack per kategori — 1 bilyet = 1 baris (source_start = source_end)
 *   Brood  : COUNT(*) per pack per kategori — 1 brood = 1 baris
 *   Pack   : COUNT(*) per pack per kategori — 1 pack = 45 baris (20+4+20+1 per kategori)
 */
class MappingAggregatorService
{
    /**
     * Titik masuk utama — recalculate semua modul untuk satu seri.
     *
     * @param int $seriId           ID master seri (x_pengganti_seris.id).
     * @param int|null $specificPackNumber Jika diberikan, hanya hitung ulang pack spesifik ini.
     */
    public function recalculateFromMappings(int $seriId, ?int $specificPackNumber = null): void
    {
        $seri = XPenggantiSeri::findOrFail($seriId);

        // Parse label seri (misal "AA-BA7") untuk mendapat pack_start absolut.
        $parsed    = SerialPrefixGenerator::parseSeriLabel($seri->seri);
        $packStart = $parsed['pack_start']; // misal: 701

        DB::transaction(function () use ($seriId, $packStart, $specificPackNumber) {
            $this->recalculateKhazai($seriId, $packStart, $specificPackNumber);
            $this->recalculateCutpack($seriId, $packStart, $specificPackNumber);
            $this->recalculateRikyet($seriId, $packStart, $specificPackNumber);
        });
    }

    // ─────────────────────────────────────────────────────────────────────
    // KHAZAI — inschiet vell
    // ─────────────────────────────────────────────────────────────────────

    /**
     * Hitung ulang kolom jumlah_rusak_vell di x_pengganti_packs.
     *
     * Rumus: jumlah_rusak_vell = COUNT(DISTINCT source_start)
     *        untuk unit_type = 'vell' per nomor_pack absolut.
     *
     * Mengapa DISTINCT source_start?
     *   registerVellReplacement() menciptakan 45 baris (1 per prefix),
     *   semua dengan source_start yang SAMA. COUNT(*) akan menghasilkan 45,
     *   padahal hanya 1 vell. DISTINCT mengembalikan nilai yang benar: 1.
     */
    private function recalculateKhazai(int $seriId, int $packStart, ?int $specificPackNumber = null): void
    {
        $now = now();

        // ① Ambil hitungan dari DB — 1 query via covering index srm_aggregator_idx
        $query = DB::table('serial_range_mappings')
            ->selectRaw('nomor_pack, COUNT(DISTINCT source_start) as jumlah')
            ->where('x_pengganti_seri_id', $seriId)
            ->where('unit_type', 'vell');

        if ($specificPackNumber) {
            $query->where('nomor_pack', $specificPackNumber);
        }

        $vellCounts = $query->groupBy('nomor_pack')
            ->pluck('jumlah', 'nomor_pack'); // Collection: [abs_pack => count]

        // ② Reset pack seri ini (atau spesifik pack) ke 0
        $resetQuery = XPenggantiPack::where('x_pengganti_seri_id', $seriId);
        if ($specificPackNumber) {
            $relPack = (int) $specificPackNumber - $packStart + 1;
            $resetQuery->where('nomor_pack', $relPack);
        }
        $resetQuery->update(['jumlah_rusak_vell' => 0, 'updated_at' => $now]);

        if ($vellCounts->isEmpty()) {
            return;
        }

        // ③ Bangun data upsert — konversi pack absolut ke relatif
        $upsertRows = [];
        foreach ($vellCounts as $absPack => $count) {
            $relPack = (int) $absPack - $packStart + 1;
            if ($relPack < 1 || $relPack > 100) {
                continue;
            }
            $upsertRows[] = [
                'x_pengganti_seri_id' => $seriId,
                'nomor_pack'          => $relPack,
                'jumlah_rusak_vell'   => (int) $count,
                'created_at'          => $now,
                'updated_at'          => $now,
            ];
        }

        // ④ Upsert batch — 1 query untuk semua pack yang terdampak
        if (!empty($upsertRows)) {
            XPenggantiPack::upsert(
                $upsertRows,
                ['x_pengganti_seri_id', 'nomor_pack'],
                ['jumlah_rusak_vell', 'updated_at']
            );
        }
    }

    // ─────────────────────────────────────────────────────────────────────
    // CUTPACK — inschiet bilyet
    // ─────────────────────────────────────────────────────────────────────

    /**
     * Hitung ulang kolom total_rusak_* di x_pengganti_cutpack_packs.
     *
     * Rumus: COUNT(*) per kategori untuk unit_type = 'bilyet'.
     *   - storeSingle() selalu menciptakan 1 baris (source_start = source_end).
     *   - Setiap baris = 1 bilyet rusak.
     *   - Kategori seri_1/seri_2/campuran_1/campuran_2 sudah tersimpan
     *     di kolom source_category.
     */
    private function recalculateCutpack(int $seriId, int $packStart, ?int $specificPackNumber = null): void
    {
        $now = now();

        // ① Satu query agregasi dengan CASE WHEN — efisien via covering index
        $query = DB::table('serial_range_mappings')
            ->selectRaw("
                nomor_pack,
                SUM(CASE WHEN source_category = 'seri_1'                         THEN 1 ELSE 0 END) AS seri_1,
                SUM(CASE WHEN source_category = 'seri_2'                         THEN 1 ELSE 0 END) AS seri_2,
                SUM(CASE WHEN source_category IN ('campuran_1', 'campuran_2', 'manual') THEN 1 ELSE 0 END) AS campuran
            ")
            ->where('x_pengganti_seri_id', $seriId)
            ->where('unit_type', 'bilyet');

        if ($specificPackNumber) {
            $query->where('nomor_pack', $specificPackNumber);
        }

        $bilyetRows = $query->groupBy('nomor_pack')
            ->get()
            ->keyBy('nomor_pack');

        // ② Reset — 1 query
        $resetQuery = XPenggantiCutpackPack::where('x_pengganti_seri_id', $seriId);
        if ($specificPackNumber) {
            $relPack = (int) $specificPackNumber - $packStart + 1;
            $resetQuery->where('nomor_pack', $relPack);
        }
        $resetQuery->update([
                'total_rusak_seri_1'   => 0,
                'total_rusak_seri_2'   => 0,
                'total_rusak_campuran' => 0,
                'updated_at'           => $now,
            ]);

        if ($bilyetRows->isEmpty()) {
            return;
        }

        // ③ Konversi dan bangun upsert rows
        $upsertRows = [];
        foreach ($bilyetRows as $absPack => $row) {
            $relPack = (int) $absPack - $packStart + 1;
            if ($relPack < 1 || $relPack > 100) {
                continue;
            }
            $upsertRows[] = [
                'x_pengganti_seri_id'  => $seriId,
                'nomor_pack'           => $relPack,
                'total_rusak_seri_1'   => (int) $row->seri_1,
                'total_rusak_seri_2'   => (int) $row->seri_2,
                'total_rusak_campuran' => (int) $row->campuran,
                'created_at'           => $now,
                'updated_at'           => $now,
            ];
        }

        // ④ Upsert batch
        if (!empty($upsertRows)) {
            XPenggantiCutpackPack::upsert(
                $upsertRows,
                ['x_pengganti_seri_id', 'nomor_pack'],
                ['total_rusak_seri_1', 'total_rusak_seri_2', 'total_rusak_campuran', 'updated_at']
            );
        }
    }

    // ─────────────────────────────────────────────────────────────────────
    // RIKYET — inschiet brood + inschiet pack
    // ─────────────────────────────────────────────────────────────────────

    /**
     * Hitung ulang kolom total_rusak_* di x_pengganti_rikyet_packs.
     *
     * Rumus: COUNT(*) per kategori untuk unit_type IN ('brood', 'pack').
     *
     *   Mengapa COUNT(*) benar untuk keduanya?
     *   - Brood: registerBroodReplacement() → 1 baris = 1 brood
     *   - Pack:  registerPackReplacement()  → 45 baris = 45 brood (1 pack)
     *     Setiap baris pack sudah memiliki source_category yang tepat
     *     (20 seri_1 + 4 campuran_1 + 20 seri_2 + 1 campuran_2 = 45).
     *     COUNT(*) per kategori langsung menghasilkan hitungan brood
     *     yang benar — tidak perlu perkalian ×45 karena sudah 45 baris.
     *
     * Contoh: 1 pack → 20 baris seri_1 → total_rusak_seri_1 += 20
     *         1 brood seri_1 → 1 baris seri_1 → total_rusak_seri_1 += 1
     */
    private function recalculateRikyet(int $seriId, int $packStart, ?int $specificPackNumber = null): void
    {
        $now = now();

        // ① Satu query agregasi untuk brood dan pack sekaligus
        $query = DB::table('serial_range_mappings')
            ->selectRaw("
                nomor_pack,
                SUM(CASE WHEN source_category = 'seri_1'                         THEN 1 ELSE 0 END) AS seri_1,
                SUM(CASE WHEN source_category = 'seri_2'                         THEN 1 ELSE 0 END) AS seri_2,
                SUM(CASE WHEN source_category IN ('campuran_1', 'campuran_2', 'manual') THEN 1 ELSE 0 END) AS campuran
            ")
            ->where('x_pengganti_seri_id', $seriId)
            ->whereIn('unit_type', ['brood', 'pack']);

        if ($specificPackNumber) {
            $query->where('nomor_pack', $specificPackNumber);
        }

        $broodRows = $query->groupBy('nomor_pack')
            ->get()
            ->keyBy('nomor_pack');

        // ② Reset — 1 query
        $resetQuery = XPenggantiRikyetPack::where('x_pengganti_seri_id', $seriId);
        if ($specificPackNumber) {
            $relPack = (int) $specificPackNumber - $packStart + 1;
            $resetQuery->where('nomor_pack', $relPack);
        }
        $resetQuery->update([
                'total_rusak_seri_1'   => 0,
                'total_rusak_seri_2'   => 0,
                'total_rusak_campuran' => 0,
                'updated_at'           => $now,
            ]);

        if ($broodRows->isEmpty()) {
            return;
        }

        // ③ Konversi dan bangun upsert rows
        $upsertRows = [];
        foreach ($broodRows as $absPack => $row) {
            $relPack = (int) $absPack - $packStart + 1;
            if ($relPack < 1 || $relPack > 100) {
                continue;
            }
            $upsertRows[] = [
                'x_pengganti_seri_id'  => $seriId,
                'nomor_pack'           => $relPack,
                'total_rusak_seri_1'   => (int) $row->seri_1,
                'total_rusak_seri_2'   => (int) $row->seri_2,
                'total_rusak_campuran' => (int) $row->campuran,
                'created_at'           => $now,
                'updated_at'           => $now,
            ];
        }

        // ④ Upsert batch
        if (!empty($upsertRows)) {
            XPenggantiRikyetPack::upsert(
                $upsertRows,
                ['x_pengganti_seri_id', 'nomor_pack'],
                ['total_rusak_seri_1', 'total_rusak_seri_2', 'total_rusak_campuran', 'updated_at']
            );
        }
    }
}
