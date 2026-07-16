<?php

namespace App\Console\Commands;

use App\Models\PenyerahanBi;
use App\Services\PenyerahanBiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MigratePenyerahanHcs extends Command
{
    protected $signature = 'migrate:penyerahan-hcs
        {--file=docs/migrasi_penyerahan_hcs.csv : Path ke file CSV sumber}
        {--user=1 : ID user pembuat data (created_by)}
        {--dry-run : Tampilkan data tanpa menyimpan ke database}';

    protected $description = 'Migrasi data penyerahan HCS dari CSV ke tabel penyerahan_bi';

    public function handle(PenyerahanBiService $service): int
    {
        $path = base_path($this->option('file'));
        if (!File::exists($path)) {
            $this->error("File tidak ditemukan: {$path}");
            return self::FAILURE;
        }

        $userId = (int) $this->option('user');
        $dryRun = (bool) $this->option('dry-run');

        $rows = $this->parseCsv($path);
        if (empty($rows)) {
            $this->warn('Tidak ada baris data yang bisa dibaca.');
            return self::SUCCESS;
        }

        $this->info('Ditemukan ' . count($rows) . ' baris data.' . ($dryRun ? ' (DRY RUN)' : ''));
        $this->newLine();

        $headers = ['Tanggal', 'Nomor BA', 'Pecahan', 'TA', 'TE', 'Dus Awal', 'Dus Akhir', 'Jml Dus', 'Jml Bilyet', 'Status'];
        $tableRows = [];
        $success = 0;
        $skipped = 0;

        foreach ($rows as $i => $row) {
            $line = $i + 2; // +1 header, +1 index base 1

            $data = [
                'tanggal_penyerahan' => $row['tanggal_penyerahan'],
                'nomor_ba' => $row['nomor_ba'],
                'pecahan' => $row['pecahan'],
                'tahun_emisi' => $row['tahun_emisi'],
                'tahun_anggaran' => $row['tahun_anggaran'],
                'nomor_dus_awal' => $row['nomor_dus_awal'],
                'nomor_dus_akhir' => $row['nomor_dus_akhir'],
                'jumlah_bilyet' => $row['jumlah_bilyet'],
            ];

            $overlap = $service->checkOverlap($data);
            $statusData = $this->guessStatus($service, $data);

            $tableRows[] = [
                $data['tanggal_penyerahan'],
                $data['nomor_ba'],
                $data['pecahan'],
                $data['tahun_anggaran'],
                $data['tahun_emisi'],
                $data['nomor_dus_awal'],
                $data['nomor_dus_akhir'],
                ($data['nomor_dus_akhir'] - $data['nomor_dus_awal'] + 1),
                number_format($data['jumlah_bilyet'], 0, ',', '.'),
                $overlap ? 'LEWATI (overlap)' : $statusData,
            ];

            if ($overlap) {
                $skipped++;
                $this->warn("Baris {$line}: dilewati (range dus overlap dengan nomor BA {$overlap->nomor_ba}).");
                continue;
            }

            if (!$dryRun) {
                $service->processUpsert($data, null, $userId);
            }
            $success++;
        }

        $this->table($headers, $tableRows);

        if ($dryRun) {
            $this->info('Dry run selesai. Tidak ada data yang disimpan.');
        } else {
            $this->info("Migrasi selesai. {$success} data disimpan, {$skipped} dilewati (overlap).");
        }

        return self::SUCCESS;
    }

    protected function guessStatus(PenyerahanBiService $service, array $data): string
    {
        // Reuse the same logic the service uses for status_data.
        try {
            $ref = new \ReflectionMethod($service, 'processUpsert');
        } catch (\Throwable) {
            return '-';
        }
        // compute deterministically to preview without writing
        $awal = (int) $data['nomor_dus_awal'];
        $akhir = (int) $data['nomor_dus_akhir'];
        $jumlah = $akhir - $awal + 1;

        $covering = \App\Models\Pengemasan::where('pecahan', $data['pecahan'])
            ->where('tahun_emisi', $data['tahun_emisi'])
            ->where('tahun_anggaran', $data['tahun_anggaran'])
            ->get(['dus_awal', 'dus_akhir']);

        $existing = collect();
        foreach ($covering as $p) {
            foreach (range($p->dus_awal, $p->dus_akhir) as $n) {
                $existing->push($n);
            }
        }
        $existing = $existing->unique();
        $jumlahAda = collect(range($awal, $akhir))->filter(fn($n) => $existing->contains($n))->count();

        return (($jumlah - $jumlahAda) === 0) ? 'Lengkap' : 'Belum Lengkap';
    }

    protected function parseCsv(string $path): array
    {
        $content = File::get($path);
        // Normalize line endings and split
        $lines = preg_split('/\r\n|\r|\n/', $content);
        $lines = array_filter($lines, fn($l) => trim($l) !== '');
        if (empty($lines)) {
            return [];
        }

        $rows = [];
        $first = true;
        foreach ($lines as $line) {
            if ($first) {
                $first = false;
                continue; // header
            }
            $cols = str_getcsv($line, ';');
            if (count($cols) < 9) {
                continue;
            }

            [$tanggal, $pecahan, $ta, $te, $doos, $jumlahBilyet, $dusAwal, $dusAkhir, $nomorBa] = $cols;

            $tanggal = trim($tanggal);
            $parsedDate = $this->parseDate($tanggal);
            if ($parsedDate === null) {
                $this->warn("Tanggal tidak valid dilewati: {$tanggal}");
                continue;
            }

            $rows[] = [
                'tanggal_penyerahan' => $parsedDate,
                'pecahan' => strtoupper(trim($pecahan)),
                'tahun_anggaran' => trim($ta),
                'tahun_emisi' => trim($te),
                'nomor_dus_awal' => (int) trim($dusAwal),
                'nomor_dus_akhir' => (int) trim($dusAkhir),
                'jumlah_bilyet' => (int) str_replace(['.', ','], '', trim($jumlahBilyet)),
                'nomor_ba' => trim($nomorBa),
            ];
        }

        return $rows;
    }

    protected function parseDate(string $value): ?string
    {
        // Format dd/mm/yyyy
        if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $value, $m)) {
            $d = str_pad($m[1], 2, '0', STR_PAD_LEFT);
            $mo = str_pad($m[2], 2, '0', STR_PAD_LEFT);
            $y = $m[3];
            if (checkdate((int) $mo, (int) $d, (int) $y)) {
                return "{$y}-{$mo}-{$d}";
            }
        }
        return null;
    }
}
