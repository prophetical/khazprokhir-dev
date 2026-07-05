<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use App\Traits\SanitizesCsv;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanHarianController extends Controller
{
    use SanitizesCsv;

    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function realtime(Request $request)
    {
        $options = $this->reportService->getYearOptions();
        $tahunAnggaranOptions = $options['tahun_anggaran'];
        $tahunEmisiOptions = $options['tahun_emisi'];

        $filters = [
            'tanggal_laporan' => Carbon::today()->toDateString(),
            'tahun_anggaran' => $request->input('tahun_anggaran', reset($tahunAnggaranOptions) ?: date('Y')),
            'tahun_emisi' => $request->input('tahun_emisi', reset($tahunEmisiOptions) ?: date('Y')),
        ];

        $data = $this->reportService->getReportData($filters);
        $hctsInventoryData = $this->reportService->getHctsInventoryData($filters);
        $targetAchievementData = $this->reportService->getTargetAchievementData($filters);
        $monthlyTargetAchievementData = $this->reportService->getMonthlyTargetAchievementData($filters);

        return view('laporan-harian.realtime', array_merge($filters, $data, [
            'tanggalLaporan' => $filters['tanggal_laporan'],
            'tahunAnggaran' => $filters['tahun_anggaran'],
            'tahunEmisi' => $filters['tahun_emisi'],
            'hctsInventoryData' => $hctsInventoryData,
            'targetAchievementData' => $targetAchievementData,
            'monthlyTargetAchievementData' => $monthlyTargetAchievementData,
            'tahunAnggaranOptions' => $tahunAnggaranOptions,
            'tahunEmisiOptions' => $tahunEmisiOptions,
        ]));
    }

    public function getRealtimePartial(Request $request)
    {
        $options = $this->reportService->getYearOptions();
        $tahunAnggaranOptions = $options['tahun_anggaran'];
        $tahunEmisiOptions = $options['tahun_emisi'];

        $filters = [
            'tanggal_laporan' => Carbon::today()->toDateString(),
            'tahun_anggaran' => $request->input('tahun_anggaran', reset($tahunAnggaranOptions) ?: date('Y')),
            'tahun_emisi' => $request->input('tahun_emisi', reset($tahunEmisiOptions) ?: date('Y')),
        ];

        $data = $this->reportService->getReportData($filters);
        $hctsInventoryData = $this->reportService->getHctsInventoryData($filters);
        $targetAchievementData = $this->reportService->getTargetAchievementData($filters);
        $monthlyTargetAchievementData = $this->reportService->getMonthlyTargetAchievementData($filters);

        return view('laporan-harian.partials.report-tables', array_merge($filters, $data, [
            'tanggalLaporan' => $filters['tanggal_laporan'],
            'tahunAnggaran' => $filters['tahun_anggaran'],
            'tahunEmisi' => $filters['tahun_emisi'],
            'hctsInventoryData' => $hctsInventoryData,
            'targetAchievementData' => $targetAchievementData,
            'monthlyTargetAchievementData' => $monthlyTargetAchievementData,
        ]))->render();
    }

    public function index(Request $request)
    {
        $options = $this->reportService->getYearOptions();
        $tahunAnggaranOptions = $options['tahun_anggaran'];
        $tahunEmisiOptions = $options['tahun_emisi'];

        $filters = $this->getFilters($request, $tahunEmisiOptions);
        $data = $this->reportService->getReportData($filters);

        $reportData = $data['reportData'];
        $totals = $data['totals'];
        $secondaryData = $data['secondaryData'];
        $secondaryTotals = $data['secondaryTotals'];
        $sisaHariKerja = $data['sisaHariKerja'];

        $hctsInventoryData = $this->reportService->getHctsInventoryData($filters);
        $targetAchievementData = $this->reportService->getTargetAchievementData($filters);
        $monthlyTargetAchievementData = $this->reportService->getMonthlyTargetAchievementData($filters);

        return view('laporan-harian.index', array_merge($filters, [
            'reportData' => $reportData,
            'totals' => $totals,
            'secondaryData' => $secondaryData,
            'secondaryTotals' => $secondaryTotals,
            'sisaHariKerja' => $sisaHariKerja,
            'hctsInventoryData' => $hctsInventoryData,
            'targetAchievementData' => $targetAchievementData,
            'monthlyTargetAchievementData' => $monthlyTargetAchievementData,
            'tanggalLaporan' => $filters['tanggal_laporan'],
            'tahunAnggaran' => $filters['tahun_anggaran'],
            'tahunEmisi' => $filters['tahun_emisi'],
            'tahunAnggaranOptions' => $tahunAnggaranOptions,
            'tahunEmisiOptions' => $tahunEmisiOptions,
        ]));
    }

    public function export(Request $request)
    {
        $options = $this->reportService->getYearOptions();
        $filters = $this->getFilters($request, $options['tahun_emisi']);
        $data = $this->reportService->getReportData($filters);
        $reportData = $data['reportData'];
        $totals = $data['totals'];

        $filename = 'laporan_harian_operasional_'.$filters['tanggal_laporan'].'.csv';
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($reportData, $totals) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Pecahan', 'Siap Kemas (Bilyet)', 'Siap Kirim (Bilyet)', 'Siap Kirim (Dus)', 'Total Persediaan (Bilyet)', 'Penyerahan Hari Ini (Bilyet)', 'Penyerahan Hari Ini (Dus)', 'Akumulasi Penyerahan (Bilyet)', 'Target', 'Sisa Target', 'Persentase (%)', 'Akumulasi Penerimaan HCS']);

            foreach ($reportData as $row) {
                fputcsv($file, array_map([$this, 'sanitizeCsvField'], [$row['pecahan'], $row['siap_kemas_bilyet'], $row['siap_kirim_bilyet'], $row['siap_kirim_dus'], $row['total_persediaan_bilyet'], $row['penyerahan_hari_ini_bilyet'], $row['penyerahan_hari_ini_dus'], $row['akumulasi_penyerahan'], $row['target'], $row['sisa_target'], number_format($row['persentase_target'], 1, ',', '.'), $row['akumulasi_penerimaan_hcs']]));
            }

            $totalPct = $totals['target'] > 0 ? ($totals['akumulasi_penyerahan_bilyet'] / $totals['target']) * 100 : 0;
            fputcsv($file, array_map([$this, 'sanitizeCsvField'], ['TOTAL', $totals['siap_kemas_bilyet'], $totals['siap_kirim_bilyet'], $totals['siap_kirim_bilyet'] / 20000, $totals['total_persediaan_bilyet'], $totals['penyerahan_hari_ini_bilyet'], $totals['penyerahan_hari_ini_bilyet'] / 20000, $totals['akumulasi_penyerahan_bilyet'], $totals['target'], $totals['sisa_target'], number_format($totalPct, 1, ',', '.'), $totals['akumulasi_penerimaan_hcs']]));
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function rekonsiliasi(Request $request)
    {
        $options = $this->reportService->getYearOptions();
        $tahunAnggaranOptions = $options['tahun_anggaran'];

        $filters = $this->getRekonsiliasiFilters($request);

        $data = $this->reportService->getRekonsiliasiData($filters);

        return view('laporan-harian.rekonsiliasi', array_merge($filters, [
            'rekonsiliasiData' => $data['data'],
            'totals' => $data['totals'],
            'tahunAnggaranOptions' => $tahunAnggaranOptions,
        ]));
    }

    public function exportRekonsiliasi(Request $request)
    {
        $filters = $this->getRekonsiliasiFilters($request);
        $startDate = $filters['start_date'];
        $endDate = $filters['end_date'];

        $data = $this->reportService->getRekonsiliasiData($filters);
        $rekonsiliasiData = $data['data'];
        $totals = $data['totals'];

        $safeStart = preg_replace('/[^0-9A-Za-z_-]/', '', $startDate);
        $safeEnd = preg_replace('/[^0-9A-Za-z_-]/', '', $endDate);
        $filename = 'rekonsiliasi_data_' . $safeStart . '_to_' . $safeEnd . '.csv';
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($rekonsiliasiData, $totals) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Pecahan', 'Penerimaan HCS', 'Pengemasan HCS', 'No Awal Dus Pengemasan', 'No Akhir Dus Pengemasan', 'Penyerahan HCS', 'No Awal Dus Penyerahan', 'No Akhir Dus Penyerahan', 'Akumulasi Target Pengemasan', 'Akumulasi Target Penyerahan']);

            foreach ($rekonsiliasiData as $row) {
                fputcsv($file, array_map([$this, 'sanitizeCsvField'], [
                    $row['pecahan'], 
                    $row['penerimaan_hcs'], 
                    $row['pengemasan_hcs'], 
                    $row['min_dus_kemas'], 
                    $row['max_dus_kemas'], 
                    $row['penyerahan_hcs'], 
                    $row['min_dus_serah'], 
                    $row['max_dus_serah'], 
                    $row['target_pengemasan'], 
                    $row['target_penyerahan']
                ]));
            }

            fputcsv($file, array_map([$this, 'sanitizeCsvField'], [
                'TOTAL', 
                $totals['penerimaan_hcs'], 
                $totals['pengemasan_hcs'], 
                '-', 
                '-', 
                $totals['penyerahan_hcs'], 
                '-', 
                '-', 
                $totals['target_pengemasan'], 
                $totals['target_penyerahan']
            ]));
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function printRekonsiliasi(Request $request)
    {
        $filters = $this->getRekonsiliasiFilters($request);

        $data = $this->reportService->getRekonsiliasiData($filters);

        return view('laporan-harian.print-rekonsiliasi', array_merge($filters, [
            'rekonsiliasiData' => $data['data'],
            'totals' => $data['totals'],
        ]));
    }

    public function print(Request $request)
    {
        $options = $this->reportService->getYearOptions();
        $filters = $this->getFilters($request, $options['tahun_emisi']);
        $data = $this->reportService->getReportData($filters);
        
        $hctsInventoryData = $this->reportService->getHctsInventoryData($filters);
        $targetAchievementData = $this->reportService->getTargetAchievementData($filters);
        $monthlyTargetAchievementData = $this->reportService->getMonthlyTargetAchievementData($filters);

        return view('laporan-harian.print-operasional', array_merge($filters, $data, [
            'hctsInventoryData' => $hctsInventoryData,
            'targetAchievementData' => $targetAchievementData,
            'monthlyTargetAchievementData' => $monthlyTargetAchievementData,
            'sisaHariKerja' => $data['sisaHariKerja'] ?? 0,
        ]));
    }

    private function getFilters(Request $request, $tahunEmisiOptions = [])
    {
        $defaultEmisi = ! empty($tahunEmisiOptions) ? $tahunEmisiOptions[0] : '2022';
        return [
            'tanggal_laporan' => $request->input('tanggal_laporan', Carbon::today()->toDateString()),
            'tahun_anggaran' => $request->input('tahun_anggaran', date('Y')),
            'tahun_emisi' => $request->input('tahun_emisi', $defaultEmisi),
        ];
    }

    private function getRekonsiliasiFilters(Request $request): array
    {
        $validated = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'tahun_anggaran' => ['nullable', 'integer', 'min:2000', 'max:2100'],
        ]);

        $startDate = $validated['start_date'] ?? Carbon::today()->startOfMonth()->toDateString();
        $endDate = $validated['end_date'] ?? Carbon::today()->toDateString();
        $tahunAnggaran = $validated['tahun_anggaran'] ?? date('Y');

        if (Carbon::parse($startDate)->gt(Carbon::parse($endDate))) {
            [$startDate, $endDate] = [$endDate, $startDate];
        }

        return [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'tahun_anggaran' => $tahunAnggaran,
        ];
    }
}
