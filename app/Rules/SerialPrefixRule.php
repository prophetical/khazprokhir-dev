<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates a 3-letter serial prefix:
 * - Must be exactly 3 uppercase letters
 * - Third letter must NOT be 'I' or 'X'
 */
class SerialPrefixRule implements ValidationRule
{
    /**
     * Valid third letters: A-Z excluding I and X (24 letters).
     */
    private const VALID_THIRD_LETTERS = [
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H',
        'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q',
        'R', 'S', 'T', 'U', 'V', 'W', 'Y', 'Z',
    ];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = strtoupper(trim($value));

        if (!preg_match('/^[A-Z]{3}$/', $value)) {
            $fail('Huruf seri harus 3 huruf kapital (contoh: ABA, ZZV).');
            return;
        }

        $thirdLetter = $value[2];

        if (!in_array($thirdLetter, self::VALID_THIRD_LETTERS)) {
            $fail("Huruf seri tidak boleh berakhiran 'I' atau 'X'. Ditemukan: '{$thirdLetter}'.");
        }
    }
}
