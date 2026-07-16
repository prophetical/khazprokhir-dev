<?php

namespace App\Console\Commands;

use App\Models\HctsReceiving;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;

class MigrateHctsReceivingCommand extends Command
{
    protected $signature = 'hcts:migrate-receiving
        {--file=docs/migrasi_penerimaan_hcts.csv : Path ke file CSV (delimiter ;)}
        {--user= : ID user pembuat (default user pertama)}
        {--force : Import ulang meski sudah ada penanda migrasi}';

    protected $description = 'Migrasi data penerimaan HCTS dari file CSV/docs ke tabel hcts_receivings.';

    public function handle()
    {
        $file = base_path($this->option('file'));

        if (!is_file($file)) {
            $this->error("File tidak ditemukan: {$file}");
            return self::FAILURE;
        }

        $userId = $this->resolveUser();
        if (!$userId) {
            $this->error('Tidak ada user tersedia untuk created_by.');
            return self::FAILURE;
        }

        if (!$this->option('force') && HctsReceiving::where('nomor_bon', 'like', 'MIGRASI-%')->exists()) {
            $this->warn('Sudah terdapat data migrasi (nomor_bon MIGRASI-*). Gunakan --force untuk import ulang.');
            return self::FAILURE;
        }

        $rows = LazyCollection::make(function () use ($file) {
            $handle = fopen($file, 'r');
            $header = null;
            while (($line = fgetcsv($handle, 0, ';')) !== false) {
                if ($header === null) {
                    $header = array_map('trim', $line);
                    continue;
                }
                if (count($line) < count($header)) {
                    continue;
                }
                yield array_combine($header, array_map('trim', $line));
            }
            fclose($handle);
        });

        $validPecahan = ['S', 'T', 'U', 'V', 'W', 'X', 'Y'];
        $inserted = 0;
        $skipped = 0;
        $errors = [];

        $bar = $this->output->createProgressBar();
        $bar->start();

        $seq = 1;
        foreach ($rows as $row) {
            $bar->advance();

            $tanggal = $row['TANGGAL'] ?? null;
            $pecahan = strtoupper($row['PECAHAN'] ?? '');
            $batch = $row['BATCH'] ?? null;
            $ta = $row['TAHUN ANGGARAN'] ?? null;
            $emisi = $row['TAHUN EMISI'] ?? null;
            $seri = $row['SERI'] ?? null;
            $segel = $row['NO SEGEL'] ?? null;
            $jumlah = $row['JUMLAH'] ?? null;

            if (!$tanggal || !$pecahan || !$batch || !$seri || !$segel || $jumlah === null || $jumlah === '') {
                $skipped++;
                $errors[] = "Baris dilewati (field kosong): " . json_encode($row);
                continue;
            }

            if (!in_array($pecahan, $validPecahan, true)) {
                $skipped++;
                $errors[] = "Baris dilewati (pecahan tidak valid '{$pecahan}'): " . json_encode($row);
                continue;
            }

            if (!preg_match('/^[A-Z]{2}-[A-Z]{2}[0-9]$/', $seri)) {
                $skipped++;
                $errors[] = "Baris dilewati (format seri tidak valid '{$seri}'): " . json_encode($row);
                continue;
            }

            HctsReceiving::create([
                'nomor_bon' => 'MIGRASI-' . str_pad($seq, 4, '0', STR_PAD_LEFT),
                'tanggal_penerimaan' => $tanggal,
                'pecahan' => $pecahan,
                'gilir' => 'Gilir 1',
                'jumlah' => (int) $jumlah,
                'batch' => (string) $batch,
                'seri' => $seri,
                'emisi' => (int) $emisi,
                'tahun_anggaran' => (int) $ta,
                'nomor_segel' => (string) $segel,
                'created_by' => $userId,
            ]);
            $seq++;
            $inserted++;
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Migrasi selesai. {$inserted} baris diinsert, {$skipped} dilewati.");

        foreach ($errors as $err) {
            $this->warn($err);
        }

        return self::SUCCESS;
    }

    protected function resolveUser(): ?int
    {
        if ($this->option('user')) {
            $user = User::find($this->option('user'));
            return $user?->id;
        }

        return User::orderBy('id')->value('id');
    }
}
