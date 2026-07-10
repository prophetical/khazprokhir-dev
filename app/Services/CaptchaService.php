<?php

namespace App\Services;

use Illuminate\Session\Store;

class CaptchaService
{
    private const SESSION_KEY = 'captcha.answer';

    private const MIN_OPERAND = 1;

    private const MAX_OPERAND = 20;

    public function __construct(
        private readonly Store $session,
    ) {}

    /**
     * Buat soal penjumlahan acak dan simpan jawabannya di Session.
     * Jawaban tidak pernah dikirim ke browser — hanya soal yang dikembalikan.
     */
    public function generate(): string
    {
        $a = random_int(self::MIN_OPERAND, self::MAX_OPERAND);
        $b = random_int(self::MIN_OPERAND, self::MAX_OPERAND);

        $this->session->put(self::SESSION_KEY, $a + $b);

        return "{$a} + {$b}";
    }

    /**
     * Validasi jawaban pengguna terhadap nilai yang tersimpan di Session.
     */
    public function verify(int $answer): bool
    {
        $expected = $this->session->get(self::SESSION_KEY);

        return is_int($expected) && $expected === $answer;
    }

    /**
     * Hapus jawaban CAPTCHA dari Session agar tidak dapat dipakai ulang.
     */
    public function clear(): void
    {
        $this->session->forget(self::SESSION_KEY);
    }
}
