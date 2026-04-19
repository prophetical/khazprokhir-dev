<?php

namespace App\Services;

use App\Models\SerialRangeMapping;
use App\Models\XPenggantiSeri;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

/**
 * Catatan author (Syah Roni)
 *  Core engine untuk Pemetaan Penggantian Nomor Seri (Range-to-Range).
 *
 * Menangani empat mode operasi:
 * 1. Penggantian Pack   --> Membuat otomatis 45 baris pemetaan (1 per brood/prefix)
 * 2. Penggantian Brood  --> Membuat 1 baris pemetaan (1 rentang prefix)
 * 3. Penggantian Vell   --> Mengganti 1 lembar (45 bilyet) di seluruh 45 prefix sekaligus
 * 4. Penggantian bilyet --> Memecah rentang yang ada atau membuat baris tunggal baru
 *
 * Seluruh operasi dibungkus dalam transaksi database untuk menjamin atomitas.
 * Pencegahan overlap dimanage oleh constraint EXCLUDE PostgreSQL (GIST).
 */
class ReplacementMappingService
{
    /**
     * Mendaftarkan penggantian pack penuh (45 brood --> 45 baris pemetaan).
     *
     * Fungsi ini secara otomatis menghasilkan seluruh 45 pemetaan prefix untuk pack tersebut.
     * Basis prefix seri pengganti harus disediakan.
     *
     * @param int    $seriId          ID Master seri.
     * @param int    $packNumber      Nomor pack sumber (contoh: 701).
     * @param string $repSeri1Base    Basis seri 1 pengganti (2 karakter, contoh: AA).
     * @param string $repSeri2Base    Basis seri 2 pengganti (2 karakter, contoh: BA).
     * @param int    $repPackNumber   Nomor pack pengganti (untuk perhitungan rentang nomor seri).
     * @param int    $createdBy       ID Pengguna.
     * @return int   Jumlah baris yang dibuat (seharusnya 45).
     */
    public function registerPackReplacement(
        int $seriId,
        int $packNumber,
        string $repSeri1Base,
        string $repSeri2Base,
        int $repPackNumber,
        int $createdBy
    ): int {
        $seri = XPenggantiSeri::findOrFail($seriId);
        $seriLabel = $seri->seri; // e.g., "AB-BB7"

        // Melakukan parsing pada label seri sumber untuk mendapatkan basis prefix.
        $parsed = SerialPrefixGenerator::parseSeriLabel($seriLabel);
        $sourcePrefixes = SerialPrefixGenerator::generatePrefixes($parsed['seri1_base'], $parsed['seri2_base']);
        $replacementPrefixes = SerialPrefixGenerator::generatePrefixes($repSeri1Base, $repSeri2Base);

        // Menghitung rentang nomor seri.
        $sourceRange = SerialPrefixGenerator::calculateSerialRange($packNumber);
        $repRange = SerialPrefixGenerator::calculateSerialRange($repPackNumber);

        // Validasi: jumlah prefix pada sisi sumber dan sisi pengganti harus sama (45).
        if (count($sourcePrefixes) !== count($replacementPrefixes)) {
            throw new InvalidArgumentException('Jumlah prefix source dan replacement tidak cocok.');
        }

        return DB::transaction(function () use ($seriId, $packNumber, $sourcePrefixes, $replacementPrefixes, $sourceRange, $repRange, $createdBy) {
            $now = now();
            $rows = [];

            foreach ($sourcePrefixes as $i => $srcEntry) {
                $repEntry = $replacementPrefixes[$i];

                $rows[] = [
                    'x_pengganti_seri_id' => $seriId,
                    'nomor_pack' => $packNumber,
                    'source_prefix' => $srcEntry['prefix'],
                    'source_start' => $sourceRange['start'],
                    'source_end' => $sourceRange['end'],
                    'replacement_prefix' => $repEntry['prefix'],
                    'replacement_start' => $repRange['start'],
                    'replacement_end' => $repRange['end'],
                    'source_category' => $srcEntry['category'],
                    'unit_type' => 'pack',
                    'created_by' => $createdBy,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            // Melakukan bulk insert — constraint EXCLUDE akan secara otomatis menolak data yang overlap.
            SerialRangeMapping::insert($rows);

            return count($rows);
        });
    }

    /**
     * Mendaftarkan penggantian satu brood (1 rentang prefix --> 1 baris).
     *
     * @param int    $seriId
     * @param int    $packNumber       Nomor pack untuk konteks.
     * @param string $sourcePrefix     Prefix sumber 3-huruf (contoh: ABA).
     * @param int    $sourceStart      Awal nomor seri sumber (contoh: 701001).
     * @param int    $sourceEnd        Akhir nomor seri sumber (contoh: 702000).
     * @param string $replacementPrefix Prefix pengganti 3-huruf.
     * @param int    $replacementStart Awal nomor seri pengganti.
     * @param int    $replacementEnd   Akhir nomor seri pengganti.
     * @param string $category         seri_1 | seri_2 | campuran_1 | campuran_2.
     * @param int    $createdBy
     * @return SerialRangeMapping
     */
    public function registerBroodReplacement(
        int $seriId,
        int $packNumber,
        string $sourcePrefix,
        int $sourceStart,
        int $sourceEnd,
        string $replacementPrefix,
        int $replacementStart,
        int $replacementEnd,
        string $category,
        int $createdBy
    ): SerialRangeMapping {
        // Validasi kesesuaian jumlah bilyet.
        $sourceCount = $sourceEnd - $sourceStart + 1;
        $repCount = $replacementEnd - $replacementStart + 1;

        if ($sourceCount !== $repCount) {
            throw new InvalidArgumentException(
                "Jumlah bilyet source ({$sourceCount}) tidak sama dengan replacement ({$repCount})."
            );
        }

        // Validasi prefix (tidak boleh berakhiran I atau X).
        $this->validatePrefix($sourcePrefix);
        $this->validatePrefix($replacementPrefix);

        return DB::transaction(function () use ($seriId, $packNumber, $sourcePrefix, $sourceStart, $sourceEnd, $replacementPrefix, $replacementStart, $replacementEnd, $category, $createdBy) {
            return SerialRangeMapping::create([
                'x_pengganti_seri_id' => $seriId,
                'nomor_pack' => $packNumber,
                'source_prefix' => strtoupper($sourcePrefix),
                'source_start' => $sourceStart,
                'source_end' => $sourceEnd,
                'replacement_prefix' => strtoupper($replacementPrefix),
                'replacement_start' => $replacementStart,
                'replacement_end' => $replacementEnd,
                'source_category' => $category,
                'unit_type' => 'brood',
                'created_by' => $createdBy,
            ]);
        });
    }

    /**
     * Input inschiet satu bilyet.
     *
     * LOGIKA PEMECAHAN (SPLIT):
     * Jika nomor seri yang diinput ternyata berada di tengah-tengah rentang yang sudah ada (misal: ada data 1001-2000, 
     * lalu kita input penggantian baru untuk nomor 1500), maka sistem akan MEMECAH rentang lama tersebut menjadi 3:
     * 
     * 1. Bagian Kiri (1001-1499): Mempertahankan data inschiet pengganti yang lama.
     * 2. Bilyet Tunggal (1500): Menggunakan data inschiet pengganti yang baru saja diinput.
     * 3. Bagian Kanan (1501-2000): Mempertahankan data inschiet pengganti yang lama.
     * 
     * Hal ini otomatis dilakukan untuk menghindari overlap data, karena database 
     * melarang adanya dua aturan penggantian pada nomor seri yang sama.
     *
     * @param int    $seriId
     * @param string $sourcePrefix     Contoh: ABA.
     * @param int    $sourceSerial     Contoh: 701500.
     * @param string $replacementPrefix Contoh: YNB.
     * @param int    $replacementSerial Contoh: 000001.
     * @param int    $createdBy
     * @return array{action: string, rows_affected: int}
     */
    public function registerSingleBilyet(
        int $seriId,
        string $sourcePrefix,
        int $sourceSerial,
        string $replacementPrefix,
        int $replacementSerial,
        int $createdBy,
        string $unitType = 'bilyet'
    ): array {
        $this->validatePrefix($sourcePrefix);
        $this->validatePrefix($replacementPrefix);

        $sourcePrefix = strtoupper($sourcePrefix);
        $replacementPrefix = strtoupper($replacementPrefix);

        return DB::transaction(function () use ($seriId, $sourcePrefix, $sourceSerial, $replacementPrefix, $replacementSerial, $createdBy, $unitType) {
            // Memeriksa apakah nomor seri ini berada di dalam rentang yang sudah ada.
            $existing = SerialRangeMapping::forSeri($seriId)
                ->containingSourceSerial($sourcePrefix, $sourceSerial)
                ->first();

            if (!$existing) {
                // Tidak ada rentang yang ada --> buat inschiet satu bilyet baru.
                $seriObj = XPenggantiSeri::find($seriId);
                $parsed = SerialPrefixGenerator::parseSeriLabel($seriObj->seri);
                $inferredCategory = SerialPrefixGenerator::categorizePrefix($sourcePrefix, $parsed['seri1_base'], $parsed['seri2_base']);
                
                if ($inferredCategory === 'unknown') {
                    $inferredCategory = 'manual';
                }

                // Turunkan nomor pack dari nomor seri: 700500 --> pack 701.
                $packNumber = intdiv($sourceSerial - 1, 1000) + 1;

                SerialRangeMapping::create([
                    'x_pengganti_seri_id' => $seriId,
                    'nomor_pack' => $packNumber,
                    'source_prefix' => $sourcePrefix,
                    'source_start' => $sourceSerial,
                    'source_end' => $sourceSerial,
                    'replacement_prefix' => $replacementPrefix,
                    'replacement_start' => $replacementSerial,
                    'replacement_end' => $replacementSerial,
                    'source_category' => $inferredCategory,
                    'unit_type' => $unitType,
                    'created_by' => $createdBy,
                ]);

                return ['action' => 'created', 'rows_affected' => 1];
            }

            // ── PEMISAHAN RANGE (RANGE SPLIT) ──
            // Nomor seri berada di dalam rentang yang sudah ada. Pisahkan menjadi hingga 3 bagian.

            $origSourceStart = $existing->source_start;
            $origSourceEnd = $existing->source_end;
            $origRepStart = $existing->replacement_start;
            $origRepEnd = $existing->replacement_end;
            $packNumber = $existing->nomor_pack;
            $category = $existing->source_category;
            $origUnitType = $existing->unit_type;

            // Menghapus baris asli.
            $existing->delete();

            $rowsCreated = 0;

            // BAGIAN 1: FRAGMEN KIRI
            // Membuat ulang rentang dari titik awal asli sampai satu nomor sebelum bilyet baru.
            if ($sourceSerial > $origSourceStart) {
                $leftOffset = $sourceSerial - 1 - $origSourceStart;
                SerialRangeMapping::create([
                    'x_pengganti_seri_id' => $seriId,
                    'nomor_pack' => $packNumber,
                    'source_prefix' => $sourcePrefix,
                    'source_start' => $origSourceStart,
                    'source_end' => $sourceSerial - 1,
                    'replacement_prefix' => $existing->replacement_prefix,
                    'replacement_start' => $origRepStart,
                    'replacement_end' => $origRepStart + $leftOffset,
                    'source_category' => $category,
                    'unit_type' => $origUnitType,
                    'created_by' => $createdBy,
                ]);
                $rowsCreated++;
            }

            // BAGIAN 2: BILYET TUNGGAL
            // Menyimpan penggantian baru yang sedang diinput untuk satu nomor seri ini.
            SerialRangeMapping::create([
                'x_pengganti_seri_id' => $seriId,
                'nomor_pack' => $packNumber,
                'source_prefix' => $sourcePrefix,
                'source_start' => $sourceSerial,
                'source_end' => $sourceSerial,
                'replacement_prefix' => $replacementPrefix,
                'replacement_start' => $replacementSerial,
                'replacement_end' => $replacementSerial,
                'source_category' => $category,
                'unit_type' => $unitType,
                'created_by' => $createdBy,
            ]);
            $rowsCreated++;

            // BAGIAN 3: FRAGMEN KANAN
            // Membuat ulang rentang dari satu nomor setelah bilyet baru sampai titik akhir asli.
            if ($sourceSerial < $origSourceEnd) {
                $offsetToSingle = $sourceSerial - $origSourceStart;
                SerialRangeMapping::create([
                    'x_pengganti_seri_id' => $seriId,
                    'nomor_pack' => $packNumber,
                    'source_prefix' => $sourcePrefix,
                    'source_start' => $sourceSerial + 1,
                    'source_end' => $origSourceEnd,
                    'replacement_prefix' => $existing->replacement_prefix,
                    'replacement_start' => $origRepStart + $offsetToSingle + 1,
                    'replacement_end' => $origRepEnd,
                    'source_category' => $category,
                    'unit_type' => $origUnitType,
                    'created_by' => $createdBy,
                ]);
                $rowsCreated++;
            }

            return ['action' => 'split', 'rows_affected' => $rowsCreated];
        });
    }

    /**
     * Mendaftarkan penggantian Vell (1 lembar = 45 Bilyet di seluruh 45 prefix).
     * 
     * @param int    $seriId          ID Master seri.
     * @param int    $sourceSerial    Nomor seri sumber (contoh: 701500).
     * @param string $repSeri1Base    Basis seri 1 pengganti (2 karakter).
     * @param string $repSeri2Base    Basis seri 2 pengganti (2 karakter).
     * @param int    $replacementSerial Nomor seri pengganti (contoh: 001500).
     * @param int    $createdBy       ID Pengguna.
     * @return int   Jumlah baris yang dibuat atau terpengaruh.
     */
    public function registerVellReplacement(
        int $seriId,
        int $sourceSerial,
        string $repSeri1Base,
        string $repSeri2Base,
        int $replacementSerial,
        int $createdBy
    ): int {
        $seri = XPenggantiSeri::findOrFail($seriId);
        $parsed = SerialPrefixGenerator::parseSeriLabel($seri->seri);
        $sourcePrefixes = SerialPrefixGenerator::generatePrefixes($parsed['seri1_base'], $parsed['seri2_base']);
        $replacementPrefixes = SerialPrefixGenerator::generatePrefixes($repSeri1Base, $repSeri2Base);

        if (count($sourcePrefixes) !== count($replacementPrefixes)) {
            throw new InvalidArgumentException('Jumlah prefix source dan replacement tidak cocok.');
        }

        return DB::transaction(function () use ($seriId, $sourcePrefixes, $sourceSerial, $replacementPrefixes, $replacementSerial, $createdBy) {
            $rowsCreatedTotal = 0;
            foreach ($sourcePrefixes as $i => $srcEntry) {
                $repEntry = $replacementPrefixes[$i];
                $result = $this->registerSingleBilyet(
                    $seriId,
                    $srcEntry['prefix'],
                    $sourceSerial,
                    $repEntry['prefix'],
                    $replacementSerial,
                    $createdBy,
                    'vell' // Menandai logika sebagai bagian dari operasi vell.
                );
                $rowsCreatedTotal += $result['rows_affected'];
            }
            return $rowsCreatedTotal;
        });
    }

    /**
     * Pencarian (Lookup): Mencari data pengganti berdasarkan nomor seri sumber.
     *
     * Menggunakan query pencarian rentang dengan indeks GIST untuk performa O(log n).
     *
     * @param string $prefix Prefix sumber (contoh: ABA).
     * @param int    $serial Nomor seri sumber (contoh: 701500).
     * @return array|null   Informasi penggantian atau null jika tidak ditemukan.
     */
    public function lookupBySourceSerial(string $prefix, int $serial): ?array
    {
        $prefix = strtoupper($prefix);

        $mapping = SerialRangeMapping::containingSourceSerial($prefix, $serial)
            ->with('seri')
            ->first();

        if (!$mapping) {
            return null;
        }

        $replacement = $mapping->calculateReplacement($serial);

        return [
            'source_full' => $prefix . str_pad($serial, 6, '0', STR_PAD_LEFT),
            'replacement_full' => $replacement['full'],
            'replacement_prefix' => $replacement['prefix'],
            'replacement_serial' => $replacement['serial'],
            'offset' => $serial - $mapping->source_start,
            'range_source' => $mapping->source_display,
            'range_replacement' => $mapping->replacement_display,
            'bilyet_count' => $mapping->bilyet_count,
            'pack' => $mapping->nomor_pack,
            'category' => $mapping->source_category,
            'unit_type' => $mapping->unit_type,
            'seri' => $mapping->seri,
            'mapping_id' => $mapping->id,
        ];
    }

    /**
     * Reverse lookup --> mencari data sumber asli berdasarkan nomor seri pengganti.
     *
     * @param string $repPrefix Prefix pengganti (contoh: ZJA).
     * @param int    $repSerial Nomor seri pengganti (contoh: 000500).
     * @return array|null
     */
    public function reverseLookup(string $repPrefix, int $repSerial): ?array
    {
        $repPrefix = strtoupper($repPrefix);

        $mapping = SerialRangeMapping::containingReplacementSerial($repPrefix, $repSerial)
            ->with('seri')
            ->first();

        if (!$mapping) {
            return null;
        }

        $source = $mapping->calculateSource($repSerial);

        return [
            'replacement_full' => $repPrefix . str_pad($repSerial, 6, '0', STR_PAD_LEFT),
            'source_full' => $source['full'],
            'source_prefix' => $source['prefix'],
            'source_serial' => $source['serial'],
            'offset' => $repSerial - $mapping->replacement_start,
            'range_source' => $mapping->source_display,
            'range_replacement' => $mapping->replacement_display,
            'bilyet_count' => $mapping->bilyet_count,
            'pack' => $mapping->nomor_pack,
            'category' => $mapping->source_category,
            'unit_type' => $mapping->unit_type,
            'seri' => $mapping->seri,
            'mapping_id' => $mapping->id,
        ];
    }

    /**
     * Mendapatkan seluruh pemetaan untuk pack tertentu dalam sebuah seri.
     *
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function getMappingsForPack(int $seriId, int $packNumber, int $perPage = 20)
    {
        return SerialRangeMapping::forPack($seriId, $packNumber)
            ->orderBy('source_prefix')
            ->orderBy('source_start')
            ->simplePaginate($perPage);
    }

    /**
     * Mendapatkan seluruh pemetaan untuk sebuah seri.
     *
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function getMappingsForSeri(int $seriId, int $perPage = 20)
    {
        return SerialRangeMapping::forSeri($seriId)
            ->orderBy('nomor_pack')
            ->orderBy('source_prefix')
            ->orderBy('source_start')
            ->simplePaginate($perPage);
    }

    /**
     * Menghapus baris pemetaan tertentu.
     */
    public function deleteMapping(int $mappingId): bool
    {
        return SerialRangeMapping::destroy($mappingId) > 0;
    }

    /**
     * Memvalidasi bahwa huruf ketiga pada prefix bukan 'I' atau 'X'.
     */
    private function validatePrefix(string $prefix): void
    {
        $prefix = strtoupper(trim($prefix));

        if (strlen($prefix) !== 3) {
            throw new InvalidArgumentException("Prefix harus 3 huruf, ditemukan: '{$prefix}'.");
        }

        $thirdLetter = $prefix[2];
        if ($thirdLetter === 'I' || $thirdLetter === 'X') {
            throw new InvalidArgumentException("Prefix tidak boleh berakhiran 'I' atau 'X'. Ditemukan: '{$prefix}'.");
        }
    }
}
