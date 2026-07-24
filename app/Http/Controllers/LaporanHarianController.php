<?php

namespace App\Http\Controllers;

use App\Models\VerifikasiLaporan;
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

    public function persediaanDetail(Request $request)
    {
        $params = $this->getPersediaanDetailParams($request);

        $breakdown = $this->reportService->getPersediaanBreakdown($params['pecahan'], [
            'tanggal_laporan' => $params['tanggal_laporan'],
            'tahun_anggaran' => $params['tahun_anggaran'],
            'tahun_emisi' => $params['tahun_emisi'],
        ], $params['jenis']);

        $isRealtime = $params['tanggal_laporan'] === Carbon::today()->toDateString();

        $jenisLabel = [
            'kemas' => 'Siap Kemas',
            'kirim' => 'Siap Kirim',
            'total' => 'Total Persediaan',
        ][$params['jenis']];

        return view('laporan-harian.persediaan-detail', [
            'pecahan' => $params['pecahan'],
            'tanggalLaporan' => $params['tanggal_laporan'],
            'tahunAnggaran' => $params['tahun_anggaran'],
            'tahunEmisi' => $params['tahun_emisi'],
            'jenis' => $params['jenis'],
            'jenisLabel' => $jenisLabel,
            'isRealtime' => $isRealtime,
            'breakdown' => $breakdown,
        ]);
    }

    public function persediaanDetailData(Request $request)
    {
        $params = $this->getPersediaanDetailParams($request);

        $breakdown = $this->reportService->getPersediaanBreakdown($params['pecahan'], [
            'tanggal_laporan' => $params['tanggal_laporan'],
            'tahun_anggaran' => $params['tahun_anggaran'],
            'tahun_emisi' => $params['tahun_emisi'],
        ], $params['jenis']);

        return view('laporan-harian.partials.persediaan-detail-table', [
            'pecahan' => $params['pecahan'],
            'tanggalLaporan' => $params['tanggal_laporan'],
            'tahunAnggaran' => $params['tahun_anggaran'],
            'tahunEmisi' => $params['tahun_emisi'],
            'jenis' => $params['jenis'],
            'breakdown' => $breakdown,
        ])->render();
    }

    private function getPersediaanDetailParams(Request $request): array
    {
        $validated = $request->validate([
            'pecahan' => ['required', 'in:S,T,U,V,W,X,Y'],
            'tanggal_laporan' => ['required', 'date'],
            'tahun_anggaran' => ['required', 'integer', 'min:2000', 'max:2100'],
            'tahun_emisi' => ['nullable', 'string'],
            'jenis' => ['required', 'in:kemas,kirim,total'],
        ]);

        return [
            'pecahan' => $validated['pecahan'],
            'tanggal_laporan' => $validated['tanggal_laporan'],
            'tahun_anggaran' => (string) $validated['tahun_anggaran'],
            'tahun_emisi' => $validated['tahun_emisi'] ?? '',
            'jenis' => $validated['jenis'],
        ];
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

        // Cek status verifikasi untuk tanggal ini
        $verifikasi = VerifikasiLaporan::with('verifier')
            ->where('jenis_laporan', 'harian')
            ->where('tanggal_mulai', $filters['tanggal_laporan'])
            ->where('tanggal_akhir', $filters['tanggal_laporan'])
            ->where('tahun_anggaran', $filters['tahun_anggaran'])
            ->first();

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
            'verifikasi' => $verifikasi,
        ]));
    }

    public function export(Request $request)
    {
        $options = $this->reportService->getYearOptions();
        $filters = $this->getFilters($request, $options['tahun_emisi']);
        $data = $this->reportService->getReportData($filters);
        $reportData = $data['reportData'];
        $totals = $data['totals'];

        // Cek status verifikasi
        $verifikasi = VerifikasiLaporan::with('verifier')
            ->where('jenis_laporan', 'harian')
            ->where('tanggal_mulai', $filters['tanggal_laporan'])
            ->where('tanggal_akhir', $filters['tanggal_laporan'])
            ->where('tahun_anggaran', $filters['tahun_anggaran'])
            ->first();

        $filename = 'laporan_harian_operasional_'.$filters['tanggal_laporan'].'.csv';
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($reportData, $totals, $verifikasi) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Pecahan', 'Siap Kemas (Bilyet)', 'Siap Kirim (Bilyet)', 'Siap Kirim (Dus)', 'Total Persediaan (Bilyet)', 'Penyerahan Hari Ini (Bilyet)', 'Penyerahan Hari Ini (Dus)', 'Akumulasi Penyerahan (Bilyet)', 'Target', 'Sisa Target', 'Persentase (%)', 'Akumulasi Penerimaan HCS']);

            foreach ($reportData as $row) {
                fputcsv($file, array_map([$this, 'sanitizeCsvField'], [$row['pecahan'], $row['siap_kemas_bilyet'], $row['siap_kirim_bilyet'], $row['siap_kirim_dus'], $row['total_persediaan_bilyet'], $row['penyerahan_hari_ini_bilyet'], $row['penyerahan_hari_ini_dus'], $row['akumulasi_penyerahan'], $row['target'], $row['sisa_target'], number_format($row['persentase_target'], 1, ',', '.'), $row['akumulasi_penerimaan_hcs']]));
            }

            $totalPct = $totals['target'] > 0 ? ($totals['akumulasi_penyerahan_bilyet'] / $totals['target']) * 100 : 0;
            fputcsv($file, array_map([$this, 'sanitizeCsvField'], ['TOTAL', $totals['siap_kemas_bilyet'], $totals['siap_kirim_bilyet'], $totals['siap_kirim_bilyet'] / 20000, $totals['total_persediaan_bilyet'], $totals['penyerahan_hari_ini_bilyet'], $totals['penyerahan_hari_ini_bilyet'] / 20000, $totals['akumulasi_penyerahan_bilyet'], $totals['target'], $totals['sisa_target'], number_format($totalPct, 1, ',', '.'), $totals['akumulasi_penerimaan_hcs']]));

            // Tambahkan keterangan verifikasi di bawah tabel
            if ($verifikasi) {
                $hariIndonesia = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                $bulanIndonesia = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                $vAt = Carbon::parse($verifikasi->verified_at);
                $tglVerif = $hariIndonesia[$vAt->dayOfWeek] . ', ' . $vAt->day . ' ' . $bulanIndonesia[$vAt->month - 1] . ' ' . $vAt->year;

                fputcsv($file, []);
                fputcsv($file, ['LAPORAN INI TELAH DIVERIFIKASI']);
                fputcsv($file, ['Diverifikasi oleh', $verifikasi->verifier->name ?? '-']);
                fputcsv($file, ['Username', $verifikasi->verifier->username ?? '-']);
                fputcsv($file, ['NP', $verifikasi->verifier->np ?? '-']);
                fputcsv($file, ['Role', strtoupper($verifikasi->verifier->role ?? '-')]);
                fputcsv($file, ['Tanggal Verifikasi', $tglVerif . ', pukul ' . $vAt->format('H:i') . ' WIB']);
                if ($verifikasi->catatan) {
                    fputcsv($file, ['Catatan', $verifikasi->catatan]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportJson(Request $request)
    {
        $options = $this->reportService->getYearOptions();
        $filters = $this->getFilters($request, $options['tahun_emisi']);
        $data = $this->reportService->getReportData($filters);

        $hctsInventoryData = $this->reportService->getHctsInventoryData($filters);
        $targetAchievementData = $this->reportService->getTargetAchievementData($filters);
        $monthlyTargetAchievementData = $this->reportService->getMonthlyTargetAchievementData($filters);

        $verifikasi = VerifikasiLaporan::with('verifier')
            ->where('jenis_laporan', 'harian')
            ->where('tanggal_mulai', $filters['tanggal_laporan'])
            ->where('tanggal_akhir', $filters['tanggal_laporan'])
            ->where('tahun_anggaran', $filters['tahun_anggaran'])
            ->first();

        $payload = [
            'meta' => [
                'tanggal_laporan' => $filters['tanggal_laporan'],
                'tahun_anggaran' => $filters['tahun_anggaran'],
                'tahun_emisi' => $filters['tahun_emisi'],
                'generated_at' => now()->toIso8601String(),
            ],
            'verifikasi' => $verifikasi ? [
                'verified_by' => $verifikasi->verifier->name ?? null,
                'username' => $verifikasi->verifier->username ?? null,
                'np' => $verifikasi->verifier->np ?? null,
                'role' => $verifikasi->verifier->role ?? null,
                'verified_at' => $verifikasi->verified_at ? Carbon::parse($verifikasi->verified_at)->toIso8601String() : null,
                'catatan' => $verifikasi->catatan,
            ] : null,
            'laporan_persediaan' => [
                'columns' => ['Pecahan', 'Siap Kemas (Bilyet)', 'Siap Kirim (Bilyet)', 'Siap Kirim (Dus)', 'Total Persediaan (Bilyet)', 'Penyerahan Hari Ini (Bilyet)', 'Penyerahan Hari Ini (Dus)', 'Akumulasi Penyerahan (Bilyet)', 'Target', 'Sisa Target', 'Persentase (%)', 'Akumulasi Penerimaan HCS'],
                'data' => $data['reportData'] ?? [],
                'totals' => $data['totals'] ?? [],
            ],
        ];

        $filename = 'laporan_harian_operasional_' . $filters['tanggal_laporan'] . '.json';

        return response()->json($payload, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function rekonsiliasi(Request $request)
    {
        $options = $this->reportService->getYearOptions();
        $tahunAnggaranOptions = $options['tahun_anggaran'];

        $filters = $this->getRekonsiliasiFilters($request);

        $data = $this->reportService->getRekonsiliasiData($filters);

        // Cek status verifikasi untuk rentang tanggal ini
        $verifikasi = VerifikasiLaporan::with('verifier')
            ->where('jenis_laporan', 'rekonsiliasi')
            ->where('tanggal_mulai', $filters['start_date'])
            ->where('tanggal_akhir', $filters['end_date'])
            ->where('tahun_anggaran', $filters['tahun_anggaran'])
            ->first();

        return view('laporan-harian.rekonsiliasi', array_merge($filters, [
            'rekonsiliasiData' => $data['data'],
            'totals' => $data['totals'],
            'tahunAnggaranOptions' => $tahunAnggaranOptions,
            'verifikasi' => $verifikasi,
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

        // Cek status verifikasi
        $verifikasi = VerifikasiLaporan::with('verifier')
            ->where('jenis_laporan', 'rekonsiliasi')
            ->where('tanggal_mulai', $filters['start_date'])
            ->where('tanggal_akhir', $filters['end_date'])
            ->where('tahun_anggaran', $filters['tahun_anggaran'])
            ->first();

        $safeStart = preg_replace('/[^0-9A-Za-z_-]/', '', $startDate);
        $safeEnd = preg_replace('/[^0-9A-Za-z_-]/', '', $endDate);
        $filename = 'rekonsiliasi_data_'.$safeStart.'_to_'.$safeEnd.'.csv';
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($rekonsiliasiData, $totals, $verifikasi) {
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
                    $row['target_penyerahan'],
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
                $totals['target_penyerahan'],
            ]));

            // Tambahkan keterangan verifikasi di bawah tabel
            if ($verifikasi) {
                $hariIndonesia = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                $bulanIndonesia = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                $vAt = Carbon::parse($verifikasi->verified_at);
                $tglVerif = $hariIndonesia[$vAt->dayOfWeek] . ', ' . $vAt->day . ' ' . $bulanIndonesia[$vAt->month - 1] . ' ' . $vAt->year;

                fputcsv($file, []);
                fputcsv($file, ['DATA REKONSILIASI INI TELAH DIVERIFIKASI']);
                fputcsv($file, ['Diverifikasi oleh', $verifikasi->verifier->name ?? '-']);
                fputcsv($file, ['Username', $verifikasi->verifier->username ?? '-']);
                fputcsv($file, ['NP', $verifikasi->verifier->np ?? '-']);
                fputcsv($file, ['Role', strtoupper($verifikasi->verifier->role ?? '-')]);
                fputcsv($file, ['Tanggal Verifikasi', $tglVerif . ', pukul ' . $vAt->format('H:i') . ' WIB']);
                if ($verifikasi->catatan) {
                    fputcsv($file, ['Catatan', $verifikasi->catatan]);
                }
            }

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

        // Cek status verifikasi untuk tanggal ini
        $verifikasi = VerifikasiLaporan::with('verifier')
            ->where('jenis_laporan', 'harian')
            ->where('tanggal_mulai', $filters['tanggal_laporan'])
            ->where('tanggal_akhir', $filters['tanggal_laporan'])
            ->where('tahun_anggaran', $filters['tahun_anggaran'])
            ->first();

        $view = view('laporan-harian.print-operasional', array_merge($filters, $data, [
            'hctsInventoryData' => $hctsInventoryData,
            'targetAchievementData' => $targetAchievementData,
            'monthlyTargetAchievementData' => $monthlyTargetAchievementData,
            'sisaHariKerja' => $data['sisaHariKerja'] ?? 0,
            'verifikasi' => $verifikasi,
        ]));

        return $view;
    }

    private function getFilters(Request $request, $tahunEmisiOptions = [])
    {
        $defaultEmisi = ! empty($tahunEmisiOptions) ? $tahunEmisiOptions[0] : '2022';

        // ConvertEmptyStringsToNull mengubah input tanggal yang dikosongkan menjadi null,
        // sehingga $request->input(..., $default) tidak memakai default. Sanitasi manual
        // agar null/empty/tanggal invalid selalu kembali ke nilai default yang valid
        // (mencegah "Illegal operator and value combination" pada whereDate dengan NULL).
        $tanggalLaporan = $request->input('tanggal_laporan');
        try {
            $tanggalLaporan = $tanggalLaporan
                ? Carbon::parse($tanggalLaporan)->toDateString()
                : Carbon::today()->toDateString();
        } catch (\Throwable $e) {
            $tanggalLaporan = Carbon::today()->toDateString();
        }

        $tahunAnggaran = $request->input('tahun_anggaran');
        if (! $tahunAnggaran || ! ctype_digit((string) $tahunAnggaran)) {
            $tahunAnggaran = date('Y');
        }

        $tahunEmisi = $request->input('tahun_emisi');
        if ($tahunEmisi === null || $tahunEmisi === '') {
            $tahunEmisi = $defaultEmisi;
        }

        return [
            'tanggal_laporan' => $tanggalLaporan,
            'tahun_anggaran' => (string) $tahunAnggaran,
            'tahun_emisi' => (string) $tahunEmisi,
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

    /**
     * Verifikasi laporan harian (per tanggal spesifik).
     * Hanya bisa dilakukan oleh role tasil atau admin.
     */
    public function verifikasiHarian(Request $request)
    {
        $user = auth()->user();
        if (! in_array($user->role, ['tasil', 'admin'])) {
            abort(403, 'Anda tidak memiliki hak untuk memverifikasi laporan.');
        }

        $validated = $request->validate([
            'tanggal_laporan' => ['required', 'date'],
            'tahun_anggaran' => ['required', 'string'],
            'tahun_emisi' => ['nullable', 'string'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        $verifikasi = VerifikasiLaporan::updateOrCreate(
            [
                'jenis_laporan' => 'harian',
                'tanggal_mulai' => $validated['tanggal_laporan'],
                'tanggal_akhir' => $validated['tanggal_laporan'],
                'tahun_anggaran' => $validated['tahun_anggaran'],
            ],
            [
                'tahun_emisi' => $validated['tahun_emisi'] ?? null,
                'verified_by' => $user->id,
                'verified_at' => now(),
                'catatan' => $validated['catatan'] ?? null,
            ]
        );

        $verifikasi->load('verifier');

        $verifiedHtml = view('laporan-harian.partials.verifikasi-status', [
            'verifikasi' => $verifikasi,
            'sudahDiverifikasi' => true,
        ])->render();

        $printUrl = route('laporan-harian.print', [
            'tanggal_laporan' => $validated['tanggal_laporan'],
            'tahun_anggaran' => $validated['tahun_anggaran'],
            'tahun_emisi' => $validated['tahun_emisi'] ?? '',
        ]);

        $jsonUrl = route('laporan-harian.export-json', [
            'tanggal_laporan' => $validated['tanggal_laporan'],
            'tahun_anggaran' => $validated['tahun_anggaran'],
            'tahun_emisi' => $validated['tahun_emisi'] ?? '',
        ]);

        return response()->json([
            'success' => true,
            'print_url' => $printUrl,
            'json_url' => $jsonUrl,
            'verified_html' => $verifiedHtml,
        ]);
    }

    /**
     * Verifikasi laporan rekonsiliasi (per rentang tanggal).
     * Hanya bisa dilakukan oleh role tasil atau admin.
     */
    public function verifikasiRekonsiliasi(Request $request)
    {
        $user = auth()->user();
        if (! in_array($user->role, ['tasil', 'admin'])) {
            abort(403, 'Anda tidak memiliki hak untuk memverifikasi laporan.');
        }

        $validated = $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
            'tahun_anggaran' => ['required', 'string'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        $verifikasi = VerifikasiLaporan::updateOrCreate(
            [
                'jenis_laporan' => 'rekonsiliasi',
                'tanggal_mulai' => $validated['start_date'],
                'tanggal_akhir' => $validated['end_date'],
                'tahun_anggaran' => $validated['tahun_anggaran'],
            ],
            [
                'verified_by' => $user->id,
                'verified_at' => now(),
                'catatan' => $validated['catatan'] ?? null,
            ]
        );

        // Redirect ke halaman export agar otomatis download
        return redirect()->route('laporan-harian.rekonsiliasi-export', [
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'tahun_anggaran' => $validated['tahun_anggaran'],
        ]);
    }

    /**
     * Hapus verifikasi laporan.
     * Hanya bisa dilakukan oleh role admin.
     */
    public function destroyVerifikasi(Request $request)
    {
        $user = auth()->user();
        if ($user->role !== 'admin') {
            abort(403, 'Anda tidak memiliki hak untuk menghapus verifikasi laporan.');
        }

        $validated = $request->validate([
            'jenis_laporan' => ['required', 'string', 'in:harian,rekonsiliasi'],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_akhir' => ['nullable', 'date'],
            'tahun_anggaran' => ['required', 'string'],
        ]);

        $query = VerifikasiLaporan::where('jenis_laporan', $validated['jenis_laporan'])
            ->where('tahun_anggaran', $validated['tahun_anggaran']);

        if (! empty($validated['tanggal_mulai'])) {
            $query->where('tanggal_mulai', $validated['tanggal_mulai']);
        }

        if (! empty($validated['tanggal_akhir'])) {
            $query->where('tanggal_akhir', $validated['tanggal_akhir']);
        }

        $query->delete();

        $hariIndonesia = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $bulanIndonesia = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $isTasilOrAdmin = in_array($user->role, ['tasil', 'admin']);

        if ($validated['jenis_laporan'] === 'harian') {
            if ($isTasilOrAdmin) {
                $replaceHtml = view('laporan-harian.partials.verifikasi-empty', [
                    'jenis_laporan' => 'harian',
                    'tanggal_laporan' => $validated['tanggal_mulai'],
                    'tahun_anggaran' => $validated['tahun_anggaran'],
                    'tahun_emisi' => $validated['tahun_emisi'] ?? '',
                ])->render();
            } else {
                $replaceHtml = '';
            }
        } else {
            if ($isTasilOrAdmin) {
                $replaceHtml = view('laporan-harian.partials.verifikasi-empty', [
                    'jenis_laporan' => 'rekonsiliasi',
                    'start_date' => $validated['tanggal_mulai'],
                    'end_date' => $validated['tanggal_akhir'],
                    'tahun_anggaran' => $validated['tahun_anggaran'],
                ])->render();
            } else {
                $replaceHtml = '';
            }
        }

        return response()->json([
            'success' => true,
            'replace_html' => $replaceHtml,
        ]);
    }
}
