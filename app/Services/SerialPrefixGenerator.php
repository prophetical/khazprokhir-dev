<?php

namespace App\Services;

/**
 * Menghasilkan 45 prefix nomor seri (3-huruf huruf seri) untuk sebuah pack.
 *
 * Catatan author (Syah Roni):
 * - Format label seri: "XX-XX9" (contoh: AB-BB7)
 *   - 2 karakter pertama = Basis prefix Seri 1 (contoh: AB)
 *   - Karakter ke 4-5      = Basis prefix Seri 2 (contoh: BB)
 *   - Karakter ke 6 (digit)= Indikator rentang pack (7 --> pack 701-800)
 *
 * - Rotasi huruf ketiga (24 huruf valid, tidak menggunakan huruf I dan X):
 *   Normal:   A B C D E F G H J K L M N O P Q R S T U (20)
 *   Campuran: V W Y Z (4, tidak menggunakan huruf X)
 *
 * - Komposisi per pack:
 *   Seri 1:       20 brood (prefix + A sampai U, tidak menggunakan huruf I)
 *   Campuran 1:   4 brood  (prefix + V, W, Y, Z)
 *   Seri 2:       20 brood (prefix + A sampai U, tidak menggunakan huruf I)
 *   Campuran 2:   1 brood  (prefix + V)
 *   Total: 45 brood = 45.000 bilyet per pack
 */
class SerialPrefixGenerator
{
    /**
     * 20 opsi huruf ketiga normal (melewati huruf I).
     */
    public const SERI_LETTERS = [
        'A',
        'B',
        'C',
        'D',
        'E',
        'F',
        'G',
        'H',
        'J',
        'K',
        'L',
        'M',
        'N',
        'O',
        'P',
        'Q',
        'R',
        'S',
        'T',
        'U',
    ];

    /**
     * 4 opsi huruf ketiga campuran (melewati huruf X).
     */
    public const CAMPURAN_LETTERS_SERI1 = ['V', 'W', 'Y', 'Z'];

    /**
     * Campuran seri 2 hanya memiliki 1 huruf.
     */
    public const CAMPURAN_LETTERS_SERI2 = ['V'];

    /**
     * Melakukan parsing label seri (contoh: "AB-BB7") ke dalam komponen-komponennya.
     *
     * @return array{seri1_base: string, seri2_base: string, digit: int, pack_start: int, pack_end: int}
     */
    public static function parseSeriLabel(string $label): array
    {
        // Format: XX-XX9  (e.g., AB-BB7)
        if (!preg_match('/^([A-Z]{2})-([A-Z]{2})(\d)$/', $label, $m)) {
            throw new \InvalidArgumentException("Format seri label tidak valid: '{$label}'. Harus XX-XX9 (contoh: AB-BB7).");
        }

        $digit = (int) $m[3];
        $packStart = ($digit * 100) + 1; // 7 --> 701
        $packEnd = ($digit * 100) + 100; // 7 --> 800

        return [
            'seri1_base' => $m[1],  // AB
            'seri2_base' => $m[2],  // BB
            'digit' => $digit,
            'pack_start' => $packStart,
            'pack_end' => $packEnd,
        ];
    }

    /**
     * Menghasilkan seluruh 45 entri prefix untuk sebuah pack, beserta informasi kategorinya.
     *
     * @return array<int, array{prefix: string, category: string}>
     *         contoh: [['prefix' => 'ABA', 'category' => 'seri_1'], ...]
     */
    public static function generatePrefixes(string $seri1Base, string $seri2Base): array
    {
        $prefixes = [];

        // Seri 1: 20 normal
        foreach (self::SERI_LETTERS as $letter) {
            $prefixes[] = [
                'prefix' => $seri1Base . $letter,
                'category' => 'seri_1',
            ];
        }

        // Campuran Seri 1: 4
        foreach (self::CAMPURAN_LETTERS_SERI1 as $letter) {
            $prefixes[] = [
                'prefix' => $seri1Base . $letter,
                'category' => 'campuran_1',
            ];
        }

        // Seri 2: 20 normal
        foreach (self::SERI_LETTERS as $letter) {
            $prefixes[] = [
                'prefix' => $seri2Base . $letter,
                'category' => 'seri_2',
            ];
        }

        // Campuran Seri 2: 1
        foreach (self::CAMPURAN_LETTERS_SERI2 as $letter) {
            $prefixes[] = [
                'prefix' => $seri2Base . $letter,
                'category' => 'campuran_2',
            ];
        }

        return $prefixes; // 45 entries
    }

    /**
     * Menghasilkan seluruh 45 prefix dari string label seri.
     *
     * @return array<int, array{prefix: string, category: string}>
     */
    public static function generateFromLabel(string $seriLabel): array
    {
        $parsed = self::parseSeriLabel($seriLabel);

        return self::generatePrefixes($parsed['seri1_base'], $parsed['seri2_base']);
    }

    /**
     * Menghitung rentang nomor seri untuk pack tertentu.
     *
     * @param int $packNumber contoh: 701.
     * @return array{start: int, end: int}  contoh: [701001, 702000].
     */
    public static function calculateSerialRange(int $packNumber): array
    {
        return [
            'start' => (($packNumber - 1) * 1000) + 1,   // 701 --> 700001
            'end' => $packNumber * 1000,                // 701 --> 701000
        ];
    }

    /**
     * Menentukan kategori dari prefix 3-huruf.
     *
     * @return string 'seri_1', 'seri_2', 'campuran_1', 'campuran_2', atau 'unknown'
     */
    public static function categorizePrefix(string $prefix, string $seri1Base, string $seri2Base): string
    {
        $base = substr($prefix, 0, 2);
        $third = substr($prefix, 2, 1);

        if ($base === $seri1Base) {
            return in_array($third, self::CAMPURAN_LETTERS_SERI1) ? 'campuran_1' : 'seri_1';
        }

        if ($base === $seri2Base) {
            return in_array($third, self::CAMPURAN_LETTERS_SERI2) ? 'campuran_2' : 'seri_2';
        }

        return 'unknown';
    }

    /**
     * Memvalidasi apakah huruf ketiga pada prefix valid (bukan I atau X).
     */
    public static function isValidThirdLetter(string $letter): bool
    {
        return in_array(strtoupper($letter), array_merge(self::SERI_LETTERS, self::CAMPURAN_LETTERS_SERI1));
    }
}
