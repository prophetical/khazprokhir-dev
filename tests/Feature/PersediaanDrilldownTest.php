<?php

namespace Tests\Feature;

use App\Services\ReportService;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PersediaanDrilldownTest extends TestCase
{
    /**
     * Validasi footing: untuk setiap pecahan dan jenis, penjumlahan baris
     * rincian per (batch,seri) harus sama dengan headline per pecahan, dan
     * footer_total dari breakdown harus sama dengan headline.
     *
     * Membutuhkan PostgreSQL (migrations bersifat PG-only sesuai README),
     * dilewati pada driver lain.
     */
    public function test_persediaan_breakdown_footing_matches_headline(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            $this->markTestSkipped('Footing test membutuhkan PostgreSQL (migrations PG-only).');
        }

        $service = app(ReportService::class);
        $options = $service->getYearOptions();
        $ta = $options['tahun_anggaran'][0] ?? date('Y');
        $te = $options['tahun_emisi'][0] ?? '2022';
        $tgl = DB::table('pengemasans')->max('tanggal_pengemasan') ?: date('Y-m-d');

        $filters = [
            'tanggal_laporan' => $tgl,
            'tahun_anggaran' => $ta,
            'tahun_emisi' => $te,
        ];

        $data = $service->getReportData($filters);

        foreach (['S', 'T', 'U', 'V', 'W', 'X', 'Y'] as $pecahan) {
            foreach (['kemas' => 'siap_kemas_bilyet', 'kirim' => 'siap_kirim_bilyet', 'total' => 'total_persediaan_bilyet'] as $jenis => $headlineKey) {
                $breakdown = $service->getPersediaanBreakdown($pecahan, $filters, $jenis);

                $rowsField = $jenis === 'kemas' ? 'siap_kemas' : ($jenis === 'kirim' ? 'siap_kirim' : 'total');
                $rowsSum = array_sum(array_column($breakdown['rows'], $rowsField));

                $headline = collect($data['reportData'])->firstWhere('pecahan', $pecahan)[$headlineKey];

                $this->assertSame(
                    $headline,
                    $rowsSum,
                    "Pecahan {$pecahan}/{$jenis}: SUM baris ({$rowsSum}) != headline ({$headline})"
                );
                $this->assertSame(
                    $headline,
                    $breakdown['footer_total'],
                    "Pecahan {$pecahan}/{$jenis}: footer_total ({$breakdown['footer_total']}) != headline ({$headline})"
                );
            }
        }
    }

    /**
     * Total per (batch,seri) harus sama dengan siap_kemas + siap_kirim per (batch,seri).
     */
    public function test_total_equals_kemas_plus_kirim_per_row(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            $this->markTestSkipped('Footing test membutuhkan PostgreSQL (migrations PG-only).');
        }

        $service = app(ReportService::class);
        $options = $service->getYearOptions();
        $ta = $options['tahun_anggaran'][0] ?? date('Y');
        $te = $options['tahun_emisi'][0] ?? '2022';
        $tgl = DB::table('pengemasans')->max('tanggal_pengemasan') ?: date('Y-m-d');

        $filters = [
            'tanggal_laporan' => $tgl,
            'tahun_anggaran' => $ta,
            'tahun_emisi' => $te,
        ];

        foreach (['S', 'T', 'U', 'V', 'W', 'X', 'Y'] as $pecahan) {
            $breakdown = $service->getPersediaanBreakdown($pecahan, $filters, 'total');

            foreach ($breakdown['rows'] as $row) {
                $expected = $row['siap_kemas'] + $row['siap_kirim'];
                $this->assertSame(
                    $expected,
                    $row['total'],
                    "Pecahan {$pecahan} batch {$row['batch']} seri {$row['seri']}: total != siap_kemas + siap_kirim"
                );
            }
        }
    }
}
