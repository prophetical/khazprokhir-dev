<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validasi prefix seri 3 huruf:
 * - Harus 3 huruf kapital
 * - Huruf ketiga tidak boleh 'I' atau 'X'
 */
class SerialPrefixRule implements ValidationRule
{
    /**
     * Huruf ketiga yang valid: A-Z kecuali I dan X (24 huruf).
     */
    private const VALID_THIRD_LETTERS = [
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
        'V',
        'W',
        'Y',
        'Z',
    ];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = strtoupper(trim($value));

        if (!preg_match('/^[A-Z]{3}$/', $value)) {
            $fail('Huruf seri harus 3 huruf kapital (contoh: ABA, RCV).');
            return;
        }

        $thirdLetter = $value[2];

        if (!in_array($thirdLetter, self::VALID_THIRD_LETTERS)) {
            $fail("Huruf seri tidak boleh berakhiran 'I' atau 'X'. Ditemukan: '{$thirdLetter}'.");
        }
    }
}
