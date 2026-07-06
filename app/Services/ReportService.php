<?php

namespace App\Services;

use App\Models\HcsReceiving;
use App\Models\HctsReceiving;
use App\Models\HctsSubmission;
use App\Models\Pengemasan;
use App\Models\PenyerahanBi;
use App\Models\TargetBulanan;
use App\Models\TargetBulananPengemasan;
use App\Models\TargetTahunan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function getYearOptions(): array
    {
        return Cache::remember('report_year_options', 3600, function () {
            $ta = DB::table('hcs_receivings')->distinct()->pluck('tahun_anggaran')
                ->merge(DB::table('pengemasans')->distinct()->pluck('tahun_anggaran'))
                ->merge(DB::table('penyerahan_bi')->distinct()->pluck('tahun_anggaran'))
                ->merge(DB::table('target_tahunan')->distinct()->pluck('tahun_anggaran'))
                ->unique()
                ->sortDesc()
                ->values()
                ->toArray();

            $te = DB::table('hcs_receivings')->distinct()->pluck('emisi')
                ->merge(DB::table('pengemasans')->distinct()->pluck('tahun_emisi'))
                ->merge(DB::table('penyerahan_bi')->distinct()->pluck('tahun_emisi'))
                ->merge(DB::table('target_tahunan')->distinct()->pluck('tahun_emisi'))
                ->unique()
                ->sortDesc()
                ->values()
                ->toArray();

            if (empty($ta)) {
                $ta = [date('Y')];
            }
            if (empty($te)) {
                $te = ['2022', '2016'];
            }

            return [
                'tahun_anggaran' => $ta,
                'tahun_emisi' => $te,
            ];
        });
    }

    public function getReportData(array $filters): array
    {
        $tanggalLaporan = $filters['tanggal_laporan'];
        $tahunAnggaran = $filters['tahun_anggaran'];
        $tahunEmisi = $filters['tahun_emisi'];

        $pecahanList = ['S', 'T', 'U', 'V', 'W', 'X', 'Y'];

        // Basis otoritatif per (pecahan, batch, seri) → roll-up ke per pecahan.
        $basis = $this->buildPersediaanBasis($filters);

        $rollup = [];
        foreach ($pecahanList as $p) {
            $rollup[$p] = ['diterima' => 0, 'dikemas' => 0, 'diserahkan' => 0, 'siap_kemas' => 0, 'siap_kirim' => 0, 'total' => 0];
        }
        foreach ($basis as $b) {
            $r = &$rollup[$b['pecahan']];
            $r['diterima'] += $b['diterima'];
            $r['dikemas'] += $b['dikemas'];
            $r['diserahkan'] += $b['diserahkan'];
            $r['siap_kemas'] += $b['siap_kemas'];
            $r['siap_kirim'] += $b['siap_kirim'];
            $r['total'] += $b['total'];
            unset($r);
        }

        // --- Aggregated Queries yang tetap (tidak diatribusi per batch) ---

        $penyerahanHariIni = PenyerahanBi::whereIn('pecahan', $pecahanList)
            ->whereDate('tanggal_penyerahan', $tanggalLaporan)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->when($tahunEmisi, fn ($q) => $q->where('tahun_emisi', $tahunEmisi))
            ->selectRaw('pecahan, SUM(jumlah_bilyet) as total')
            ->groupBy('pecahan')
            ->pluck('total', 'pecahan');

        // Kolom "Akumulasi Terima HCS" (kolom l) tetap memakai basis hcs_receivings.
        $receivings = HcsReceiving::whereIn('pecahan', $pecahanList)
            ->whereDate('tanggal_penerimaan', '<=', $tanggalLaporan)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->when($tahunEmisi, fn ($q) => $q->where('emisi', $tahunEmisi))
            ->selectRaw('pecahan, SUM(jumlah) as total')
            ->groupBy('pecahan')
            ->pluck('total', 'pecahan');

        $targets = TargetTahunan::whereIn('pecahan', $pecahanList)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->when($tahunEmisi, fn ($q) => $q->where('tahun_emisi', $tahunEmisi))
            ->selectRaw('pecahan, SUM(target) as total')
            ->groupBy('pecahan')
            ->pluck('total', 'pecahan');

        $reportData = [];
        $totals = [
            'siap_kemas_bilyet' => 0,
            'siap_kirim_bilyet' => 0,
            'total_persediaan_bilyet' => 0,
            'penyerahan_hari_ini_bilyet' => 0,
            'akumulasi_penyerahan_bilyet' => 0,
            'target' => 0,
            'sisa_target' => 0,
            'akumulasi_penerimaan_hcs' => 0,
        ];

        foreach ($pecahanList as $pecahan) {
            $r = $rollup[$pecahan];
            $siapKemasBilyet = $r['siap_kemas'];
            $siapKirimBilyet = $r['siap_kirim'];
            $siapKirimDus = (int) ceil($siapKirimBilyet / 20000);
            $totalPersediaanBilyet = $r['total'];
            $akumulasiPenyerahan = $r['diserahkan'];
            $penyerahanHariIniBilyet = $penyerahanHariIni->get($pecahan, 0);
            $penyerahanHariIniDus = (int) ceil($penyerahanHariIniBilyet / 20000);
            $target = $targets->get($pecahan, 0);
            $akumulasiPenerimaanHcs = $receivings->get($pecahan, 0);
            $sisaTarget = $target - $akumulasiPenyerahan;
            $persentaseTarget = $target > 0 ? ($akumulasiPenyerahan / $target) * 100 : 0;

            $reportData[] = [
                'pecahan' => $pecahan,
                'siap_kemas_bilyet' => $siapKemasBilyet,
                'siap_kirim_bilyet' => $siapKirimBilyet,
                'siap_kirim_dus' => $siapKirimDus,
                'total_persediaan_bilyet' => $totalPersediaanBilyet,
                'penyerahan_hari_ini_bilyet' => $penyerahanHariIniBilyet,
                'penyerahan_hari_ini_dus' => $penyerahanHariIniDus,
                'akumulasi_penyerahan' => $akumulasiPenyerahan,
                'target' => $target,
                'sisa_target' => $sisaTarget,
                'persentase_target' => $persentaseTarget,
                'akumulasi_penerimaan_hcs' => $akumulasiPenerimaanHcs,
            ];

            $totals['siap_kemas_bilyet'] += $siapKemasBilyet;
            $totals['siap_kirim_bilyet'] += $siapKirimBilyet;
            $totals['total_persediaan_bilyet'] += $totalPersediaanBilyet;
            $totals['penyerahan_hari_ini_bilyet'] += $penyerahanHariIniBilyet;
            $totals['akumulasi_penyerahan_bilyet'] += $akumulasiPenyerahan;
            $totals['target'] += $target;
            $totals['sisa_target'] += $sisaTarget;
            $totals['akumulasi_penerimaan_hcs'] += $akumulasiPenerimaanHcs;
        }

        $secondaryData = $this->getSecondaryReportData($filters);

        return [
            'reportData' => $reportData,
            'totals' => $totals,
            'secondaryData' => $secondaryData['data'],
            'secondaryTotals' => $secondaryData['totals'],
            'sisaHariKerja' => $secondaryData['sisaHariKerja'],
        ];
    }

    /**
     * Bangun basis persediaan otoritatif per (pecahan, batch, seri) pada tanggal T.
     * Mengembalikan array berisi: diterima, dikemas, diserahkan, siap_kemas, siap_kirim, total.
     */
    private function buildPersediaanBasis(array $filters, ?string $onlyPecahan = null): array
    {
        $T = $filters['tanggal_laporan'];
        $ta = $filters['tahun_anggaran'];
        $te = $filters['tahun_emisi'];
        $pecahanList = $onlyPecahan ? [$onlyPecahan] : ['S', 'T', 'U', 'V', 'W', 'X', 'Y'];

        // 1) Diterima = SUM(packs.jumlah) untuk pack yang diterima <= T.
        $diterima = DB::table('packs as p')
            ->join('hcs_receivings as r', 'p.hcs_receiving_id', '=', 'r.id')
            ->whereIn('r.pecahan', $pecahanList)
            ->where('r.tahun_anggaran', $ta)
            ->when($te, fn ($q) => $q->where('r.emisi', $te))
            ->whereDate('r.tanggal_penerimaan', '<=', $T)
            ->selectRaw('r.pecahan as pecahan, p.batch as batch, p.seri as seri, COALESCE(SUM(p.jumlah),0) as diterima')
            ->groupByRaw('r.pecahan, p.batch, p.seri')
            ->get();

        // 2) Dikemas = SUM(total_bilyet) pengemasan dengan tanggal <= T.
        //    Fallback jumlah_dus * 20000 bila total_bilyet kosong/nol (data lama).
        $dikemas = DB::table('pengemasans')
            ->whereIn('pecahan', $pecahanList)
            ->where('tahun_anggaran', $ta)
            ->when($te, fn ($q) => $q->where('tahun_emisi', $te))
            ->whereDate('tanggal_pengemasan', '<=', $T)
            ->selectRaw('pecahan, batch, seri, SUM(CASE WHEN total_bilyet IS NULL OR total_bilyet = 0 THEN jumlah_dus * 20000 ELSE total_bilyet END) as dikemas')
            ->groupByRaw('pecahan, batch, seri')
            ->get();

        // 3) Diserahkan = atribusi dus penyerahan ke (batch,seri) via detail_pengemasans.
        //    no_dus unik per (pecahan,TA,TE) → scope pb dan p harus sama.
        $diserahkan = DB::table('penyerahan_bi as pb')
            ->join('detail_pengemasans as dp', function ($j) {
                $j->whereRaw('dp.no_dus BETWEEN pb.nomor_dus_awal AND pb.nomor_dus_akhir');
            })
            ->join('pengemasans as p', 'dp.id_pengemasan', '=', 'p.id')
            ->whereIn('pb.pecahan', $pecahanList)
            ->where('pb.tahun_anggaran', $ta)
            ->when($te, fn ($q) => $q->where('pb.tahun_emisi', $te))
            ->whereColumn('p.tahun_anggaran', 'pb.tahun_anggaran')
            ->whereColumn('p.tahun_emisi', 'pb.tahun_emisi')
            ->whereColumn('p.pecahan', 'pb.pecahan')
            ->whereDate('pb.tanggal_penyerahan', '<=', $T)
            ->whereDate('p.tanggal_pengemasan', '<=', $T)
            ->selectRaw('p.pecahan as pecahan, p.batch as batch, p.seri as seri, COALESCE(SUM(dp.jumlah_bilyet),0) as diserahkan')
            ->groupByRaw('p.pecahan, p.batch, p.seri')
            ->get();

        $map = [];
        foreach ($diterima as $row) {
            $map[$row->pecahan.'|'.$row->batch.'|'.$row->seri] = [
                'pecahan' => $row->pecahan, 'batch' => $row->batch, 'seri' => $row->seri,
                'diterima' => (int) $row->diterima, 'dikemas' => 0, 'diserahkan' => 0,
            ];
        }
        foreach ($dikemas as $row) {
            $k = $row->pecahan.'|'.$row->batch.'|'.$row->seri;
            if (! isset($map[$k])) {
                $map[$k] = ['pecahan' => $row->pecahan, 'batch' => $row->batch, 'seri' => $row->seri, 'diterima' => 0, 'dikemas' => 0, 'diserahkan' => 0];
            }
            $map[$k]['dikemas'] = (int) $row->dikemas;
        }
        foreach ($diserahkan as $row) {
            $k = $row->pecahan.'|'.$row->batch.'|'.$row->seri;
            if (! isset($map[$k])) {
                $map[$k] = ['pecahan' => $row->pecahan, 'batch' => $row->batch, 'seri' => $row->seri, 'diterima' => 0, 'dikemas' => 0, 'diserahkan' => 0];
            }
            $map[$k]['diserahkan'] = (int) $row->diserahkan;
        }

        foreach ($map as &$b) {
            $b['siap_kemas'] = $b['diterima'] - $b['dikemas'];
            $b['siap_kirim'] = max(0, $b['dikemas'] - $b['diserahkan']);
            $b['total'] = $b['siap_kemas'] + $b['siap_kirim'];
        }
        unset($b);

        return array_values($map);
    }

    /**
     * Rincian persediaan per (batch,seri) untuk satu pecahan dan jenis tertentu.
     * jenis ∈ {'kemas','kirim','total'} → menentukan kolom pack & footer_total.
     */
    public function getPersediaanBreakdown(string $pecahan, array $filters, string $jenis): array
    {
        $validJenis = ['kemas' => 'siap_kemas', 'kirim' => 'siap_kirim', 'total' => 'total'];
        if (! array_key_exists($jenis, $validJenis)) {
            $jenis = 'total';
        }

        $basis = $this->buildPersediaanBasis($filters, $pecahan);
        $ranges = $this->buildPackRanges($filters, $pecahan);

        $rows = [];
        $footer = ['diterima' => 0, 'dikemas' => 0, 'diserahkan' => 0, 'siap_kemas' => 0, 'siap_kirim' => 0, 'total' => 0];

        foreach ($basis as $b) {
            $key = $b['batch'].'|'.$b['seri'];
            $pack = match ($jenis) {
                'kemas' => $ranges['kemas'][$key] ?? '-',
                'kirim' => $ranges['kirim'][$key] ?? '-',
                'total' => $ranges['total'][$key] ?? '-',
            };
            $rows[] = [
                'batch' => $b['batch'],
                'seri' => $b['seri'],
                'pack' => $pack,
                'diterima' => $b['diterima'],
                'dikemas' => $b['dikemas'],
                'diserahkan' => $b['diserahkan'],
                'siap_kemas' => $b['siap_kemas'],
                'siap_kirim' => $b['siap_kirim'],
                'total' => $b['total'],
            ];
            $footer['diterima'] += $b['diterima'];
            $footer['dikemas'] += $b['dikemas'];
            $footer['diserahkan'] += $b['diserahkan'];
            $footer['siap_kemas'] += $b['siap_kemas'];
            $footer['siap_kirim'] += $b['siap_kirim'];
            $footer['total'] += $b['total'];
        }

        usort($rows, function ($a, $b) {
            return strcmp($a['batch'], $b['batch']) ?: strcmp($a['seri'], $b['seri']);
        });

        return [
            'rows' => $rows,
            'footer' => $footer,
            'footer_total' => $footer[$validJenis[$jenis]],
            'jenis' => $jenis,
            'unattributed' => $this->computeUnattributedPenyerahan($filters, $pecahan),
        ];
    }

    /**
     * Rentang pack per (batch,seri) untuk siap_kemas, siap_kirim, dan total.
     * Kunci: "batch|seri" => string rentang terformat ("1-4, 9-12").
     */
    private function buildPackRanges(array $filters, string $pecahan): array
    {
        $T = $filters['tanggal_laporan'];
        $ta = $filters['tahun_anggaran'];
        $te = $filters['tahun_emisi'];

        $packRows = DB::table('packs as p')
            ->join('hcs_receivings as r', 'p.hcs_receiving_id', '=', 'r.id')
            ->leftJoin('pengemasans as pg', 'p.id_pengemasan', '=', 'pg.id')
            ->where('r.pecahan', $pecahan)
            ->where('r.tahun_anggaran', $ta)
            ->when($te, fn ($q) => $q->where('r.emisi', $te))
            ->whereDate('r.tanggal_penerimaan', '<=', $T)
            ->select('p.batch', 'p.seri', 'p.pack_number', 'p.id_pengemasan', DB::raw('pg.tanggal_pengemasan as pg_tanggal'))
            ->get();

        // Status per pengemasan: sudah diserahkan sepenuhnya?
        $pgDiserahkan = DB::table('penyerahan_bi as pb')
            ->join('detail_pengemasans as dp', function ($j) {
                $j->whereRaw('dp.no_dus BETWEEN pb.nomor_dus_awal AND pb.nomor_dus_akhir');
            })
            ->join('pengemasans as p', 'dp.id_pengemasan', '=', 'p.id')
            ->where('pb.pecahan', $pecahan)
            ->where('pb.tahun_anggaran', $ta)
            ->when($te, fn ($q) => $q->where('pb.tahun_emisi', $te))
            ->whereColumn('p.tahun_anggaran', 'pb.tahun_anggaran')
            ->whereColumn('p.tahun_emisi', 'pb.tahun_emisi')
            ->whereColumn('p.pecahan', 'pb.pecahan')
            ->whereDate('pb.tanggal_penyerahan', '<=', $T)
            ->whereDate('p.tanggal_pengemasan', '<=', $T)
            ->selectRaw('p.id as pgid, COALESCE(SUM(dp.jumlah_bilyet),0) as diserahkan')
            ->groupByRaw('p.id')
            ->pluck('diserahkan', 'pgid');

        $pgTotal = DB::table('pengemasans')
            ->where('pecahan', $pecahan)
            ->where('tahun_anggaran', $ta)
            ->when($te, fn ($q) => $q->where('tahun_emisi', $te))
            ->whereDate('tanggal_pengemasan', '<=', $T)
            ->selectRaw('id, CASE WHEN total_bilyet IS NULL OR total_bilyet = 0 THEN jumlah_dus * 20000 ELSE total_bilyet END as eff_total')
            ->pluck('eff_total', 'id');

        $kemas = [];
        $kirim = [];
        foreach ($packRows as $row) {
            $key = $row->batch.'|'.$row->seri;
            $num = (int) $row->pack_number;
            if (empty($row->id_pengemasan)) {
                $kemas[$key][] = $num;

                continue;
            }
            $pgTanggal = $row->pg_tanggal;
            $packagedOnOrBeforeT = $pgTanggal !== null && substr($pgTanggal, 0, 10) <= $T;
            if (! $packagedOnOrBeforeT) {
                // Belum dikemas per tanggal T → masuk siap kemas.
                $kemas[$key][] = $num;

                continue;
            }
            $pgid = $row->id_pengemasan;
            $eff = (int) ($pgTotal[$pgid] ?? 0);
            $dis = (int) ($pgDiserahkan[$pgid] ?? 0);
            if ($dis < $eff) {
                // Dikemas (<=T) dan belum sepenuhnya diserahkan → siap kirim.
                $kirim[$key][] = $num;
            }
            // Jika dis >= eff → sudah diserahkan sepenuhnya, tidak masuk mana pun.
        }

        $kemasRanges = [];
        $kirimRanges = [];
        $totalRanges = [];
        $keys = array_unique(array_merge(array_keys($kemas), array_keys($kirim)));
        foreach ($keys as $key) {
            $kemasRanges[$key] = $this->clusterRanges($kemas[$key] ?? []);
            $kirimRanges[$key] = $this->clusterRanges($kirim[$key] ?? []);
            $totalRanges[$key] = $this->clusterRanges(array_merge($kemas[$key] ?? [], $kirim[$key] ?? []));
        }

        return ['kemas' => $kemasRanges, 'kirim' => $kirimRanges, 'total' => $totalRanges];
    }

    private function clusterRanges(array $nums): string
    {
        $nums = array_values(array_unique($nums));
        if (empty($nums)) {
            return '-';
        }
        sort($nums, SORT_NUMERIC);
        $ranges = [];
        $start = $prev = $nums[0];
        $n = count($nums);
        for ($i = 1; $i < $n; $i++) {
            if ($nums[$i] === $prev + 1) {
                $prev = $nums[$i];
            } else {
                $ranges[] = $start === $prev ? (string) $start : "{$start}-{$prev}";
                $start = $prev = $nums[$i];
            }
        }
        $ranges[] = $start === $prev ? (string) $start : "{$start}-{$prev}";

        return implode(', ', $ranges);
    }

    /**
     * Selisih antara jumlah_bilyet yang diklaim penyerahan_bi vs yang teratribusi
     * ke pengemasan (dus yang benar-benar sudah dikemas). > 0 = ada dus belum dikemas.
     */
    private function computeUnattributedPenyerahan(array $filters, string $pecahan): int
    {
        $T = $filters['tanggal_laporan'];
        $ta = $filters['tahun_anggaran'];
        $te = $filters['tahun_emisi'];

        $claimed = (int) PenyerahanBi::where('pecahan', $pecahan)
            ->where('tahun_anggaran', $ta)
            ->when($te, fn ($q) => $q->where('tahun_emisi', $te))
            ->whereDate('tanggal_penyerahan', '<=', $T)
            ->sum('jumlah_bilyet');

        $attributed = (int) DB::table('penyerahan_bi as pb')
            ->join('detail_pengemasans as dp', function ($j) {
                $j->whereRaw('dp.no_dus BETWEEN pb.nomor_dus_awal AND pb.nomor_dus_akhir');
            })
            ->join('pengemasans as p', 'dp.id_pengemasan', '=', 'p.id')
            ->where('pb.pecahan', $pecahan)
            ->where('pb.tahun_anggaran', $ta)
            ->when($te, fn ($q) => $q->where('pb.tahun_emisi', $te))
            ->whereColumn('p.tahun_anggaran', 'pb.tahun_anggaran')
            ->whereColumn('p.tahun_emisi', 'pb.tahun_emisi')
            ->whereColumn('p.pecahan', 'pb.pecahan')
            ->whereDate('pb.tanggal_penyerahan', '<=', $T)
            ->whereDate('p.tanggal_pengemasan', '<=', $T)
            ->sum('dp.jumlah_bilyet');

        return $claimed - $attributed;
    }

    public function getSecondaryReportData(array $filters): array
    {
        $tanggalLaporan = Carbon::parse($filters['tanggal_laporan']);
        $month = $tanggalLaporan->month;
        $targetColumn = 'bulan_'.$month;
        $sisaHariKerja = $this->calculateSisaHariKerja($tanggalLaporan);

        $rawData = $this->fetchSecondaryRawData($filters);

        return $this->calculateSecondaryMetrics($rawData, $sisaHariKerja, $targetColumn);
    }

    private function fetchSecondaryRawData(array $filters): array
    {
        $tanggalLaporan = Carbon::parse($filters['tanggal_laporan']);
        $tahunAnggaran = $filters['tahun_anggaran'];
        $tahunEmisi = $filters['tahun_emisi'];
        $pecahanList = ['S', 'T', 'U', 'V', 'W', 'X', 'Y'];
        $startOfMonth = $tanggalLaporan->copy()->startOfMonth()->toDateString();
        $endDate = $tanggalLaporan->toDateString();

        return [
            'targets' => TargetBulananPengemasan::whereIn('pecahan', $pecahanList)
                ->where('tahun_anggaran', $tahunAnggaran)
                ->where('tahun_emisi', $tahunEmisi)
                ->get()
                ->keyBy('pecahan'),

            'pengemasans' => Pengemasan::whereIn('pecahan', $pecahanList)
                ->where('tahun_anggaran', $tahunAnggaran)
                ->where('tahun_emisi', $tahunEmisi)
                ->whereDate('tanggal_pengemasan', '>=', $startOfMonth)
                ->whereDate('tanggal_pengemasan', '<=', $endDate)
                ->selectRaw('pecahan, SUM(jumlah_dus * 20000) as total')
                ->groupBy('pecahan')
                ->pluck('total', 'pecahan'),

            'kemasGilir' => Pengemasan::whereIn('pecahan', $pecahanList)
                ->where('tahun_anggaran', $tahunAnggaran)
                ->when($tahunEmisi, fn ($q) => $q->where('tahun_emisi', $tahunEmisi))
                ->whereDate('tanggal_pengemasan', '>=', $startOfMonth)
                ->whereDate('tanggal_pengemasan', '<=', $endDate)
                ->selectRaw('pecahan, gilir, SUM(jumlah_dus * 20000) as total')
                ->groupBy('pecahan', 'gilir')
                ->get()
                ->groupBy('pecahan'),

            'hcsSupplier' => HcsReceiving::whereIn('pecahan', $pecahanList)
                ->where('tahun_anggaran', $tahunAnggaran)
                ->when($tahunEmisi, fn ($q) => $q->where('emisi', $tahunEmisi))
                ->whereDate('tanggal_penerimaan', '>=', $startOfMonth)
                ->whereDate('tanggal_penerimaan', '<=', $endDate)
                ->selectRaw('pecahan, supplier, SUM(jumlah) as total')
                ->groupBy('pecahan', 'supplier')
                ->get()
                ->groupBy('pecahan'),
        ];
    }

    private function calculateSecondaryMetrics(array $rawData, int $sisaHariKerja, string $targetColumn): array
    {
        $pecahanList = ['S', 'T', 'U', 'V', 'W', 'X', 'Y'];
        $data = [];
        $totals = [
            'target_penyerahan_bulan' => 0,
            'penyerahan_bulan' => 0,
            'sisa_target_bilyet' => 0,
            'sisa_target_doos' => 0,
            'target_produksi_harian' => 0,
            'kemas_g1' => 0,
            'kemas_g2' => 0,
            'kemas_g3' => 0,
            'total_kemas' => 0,
            'hcs_rikyet' => 0,
            'hcs_cutpack' => 0,
            'total_hcs' => 0,
        ];

        foreach ($pecahanList as $pecahan) {
            $targetRow = $rawData['targets']->get($pecahan);
            $targetBulan = $targetRow ? ($targetRow->{$targetColumn} ?? 0) : 0;
            $pengemasanBulanBilyet = $rawData['pengemasans']->get($pecahan, 0);

            $sisaTargetBilyet = $targetBulan - $pengemasanBulanBilyet;
            $sisaTargetDoos = ceil($sisaTargetBilyet / 20000);
            $targetProduksiHarian = $sisaHariKerja > 0 ? floor($sisaTargetBilyet / $sisaHariKerja) : 0;

            $pecKemas = $rawData['kemasGilir']->get($pecahan);
            $kemasG1 = $pecKemas?->where('gilir', '1')->first()?->total ?? 0;
            $kemasG2 = $pecKemas?->where('gilir', '2')->first()?->total ?? 0;
            $kemasG3 = $pecKemas?->where('gilir', '3')->first()?->total ?? 0;
            $totalKemas = $kemasG1 + $kemasG2 + $kemasG3;

            $pecHcs = $rawData['hcsSupplier']->get($pecahan);
            $hcsRikyet = $pecHcs?->where('supplier', 'Rikyet')->first()?->total ?? 0;
            $hcsCutpack = $pecHcs?->where('supplier', 'Cutpack')->first()?->total ?? 0;
            $totalHcs = $hcsRikyet + $hcsCutpack;

            $data[] = [
                'pecahan' => $pecahan,
                'target_penyerahan_bulan' => $targetBulan,
                'penyerahan_bulan' => $pengemasanBulanBilyet,
                'sisa_target_bilyet' => $sisaTargetBilyet,
                'sisa_target_doos' => $sisaTargetDoos,
                'target_produksi_harian' => $targetProduksiHarian,
                'kemas_g1' => $kemasG1,
                'kemas_g2' => $kemasG2,
                'kemas_g3' => $kemasG3,
                'total_kemas' => $totalKemas,
                'hcs_rikyet' => $hcsRikyet,
                'hcs_cutpack' => $hcsCutpack,
                'total_hcs' => $totalHcs,
            ];

            $totals['target_penyerahan_bulan'] += $targetBulan;
            $totals['penyerahan_bulan'] += $pengemasanBulanBilyet;
            $totals['sisa_target_bilyet'] += $sisaTargetBilyet;
            $totals['sisa_target_doos'] += $sisaTargetDoos;
            $totals['target_produksi_harian'] += $targetProduksiHarian;
            $totals['kemas_g1'] += $kemasG1;
            $totals['kemas_g2'] += $kemasG2;
            $totals['kemas_g3'] += $kemasG3;
            $totals['total_kemas'] += $totalKemas;
            $totals['hcs_rikyet'] += $hcsRikyet;
            $totals['hcs_cutpack'] += $hcsCutpack;
            $totals['total_hcs'] += $totalHcs;
        }

        return ['data' => $data, 'totals' => $totals, 'sisaHariKerja' => $sisaHariKerja];
    }

    public function getHctsInventoryData(array $filters): array
    {
        $tanggalLaporan = $filters['tanggal_laporan'];
        $tahunAnggaran = $filters['tahun_anggaran'];
        $tahunEmisi = $filters['tahun_emisi'];
        $previousDay = Carbon::parse($tanggalLaporan)->subDay()->toDateString();
        $pecahanList = ['S', 'T', 'U', 'V', 'W', 'X', 'Y'];

        // --- Aggregated Queries ---

        $penerimaanH1Map = HctsReceiving::whereIn('pecahan', $pecahanList)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->when($tahunEmisi, fn ($q) => $q->where('emisi', $tahunEmisi))
            ->whereDate('tanggal_penerimaan', $previousDay)
            ->selectRaw('pecahan, SUM(jumlah) as total')
            ->groupBy('pecahan')
            ->pluck('total', 'pecahan');

        $penyerahanHariIniMap = HctsSubmission::whereIn('pecahan', $pecahanList)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->when($tahunEmisi, fn ($q) => $q->where('tahun_emisi', $tahunEmisi))
            ->whereDate('tanggal_penyerahan', $tanggalLaporan)
            ->selectRaw('pecahan, SUM(jumlah_bilyet) as total')
            ->groupBy('pecahan')
            ->pluck('total', 'pecahan');

        $akumulasiTerimaMap = HctsReceiving::whereIn('pecahan', $pecahanList)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->when($tahunEmisi, fn ($q) => $q->where('emisi', $tahunEmisi))
            ->whereDate('tanggal_penerimaan', '<=', $tanggalLaporan)
            ->selectRaw('pecahan, SUM(jumlah) as total')
            ->groupBy('pecahan')
            ->pluck('total', 'pecahan');

        $akumulasiSerahMap = HctsSubmission::whereIn('pecahan', $pecahanList)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->when($tahunEmisi, fn ($q) => $q->where('tahun_emisi', $tahunEmisi))
            ->whereDate('tanggal_penyerahan', '<=', $tanggalLaporan)
            ->selectRaw('pecahan, SUM(jumlah_bilyet) as total')
            ->groupBy('pecahan')
            ->pluck('total', 'pecahan');

        $hctsInventoryData = [];
        foreach ($pecahanList as $pec) {
            $penerimaanH1 = $penerimaanH1Map->get($pec, 0);
            $penyerahanHariIni = $penyerahanHariIniMap->get($pec, 0);
            $akumulasiTerima = $akumulasiTerimaMap->get($pec, 0);
            $akumulasiSerah = $akumulasiSerahMap->get($pec, 0);

            $persediaan = $akumulasiTerima - $akumulasiSerah;
            $hctsInventoryData[$pec] = [
                'penerimaan_h1' => (int) $penerimaanH1,
                'penyerahan_hari_ini' => (int) $penyerahanHariIni,
                'akumulasi_penerimaan' => (int) $akumulasiTerima,
                'akumulasi_penyerahan' => (int) $akumulasiSerah,
                'persediaan' => (int) $persediaan,
                'ct_siap_hitung' => (int) floor($persediaan / 3000000),
            ];
        }

        return $hctsInventoryData;
    }

    public function getTargetAchievementData(array $filters): array
    {
        $tanggalLaporan = $filters['tanggal_laporan'];
        $tahunAnggaran = $filters['tahun_anggaran'];
        $tahunEmisi = $filters['tahun_emisi'];
        $pecahanList = ['S', 'T', 'U', 'V', 'W', 'X', 'Y'];

        // --- Aggregated Queries ---

        $targetMap = TargetTahunan::whereIn('pecahan', $pecahanList)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->where('tahun_emisi', $tahunEmisi)
            ->selectRaw('pecahan, SUM(target) as total')
            ->groupBy('pecahan')
            ->pluck('total', 'pecahan');

        $pengemasanMap = Pengemasan::whereIn('pecahan', $pecahanList)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->where('tahun_emisi', $tahunEmisi)
            ->whereDate('tanggal_pengemasan', '<=', $tanggalLaporan)
            ->selectRaw('pecahan, SUM(total_bilyet) as total_bilyet, SUM(jumlah_dus) as total_dus')
            ->groupBy('pecahan')
            ->get()
            ->keyBy('pecahan');

        $data = [];
        $totals = ['target_bilyet' => 0, 'target_dus' => 0, 'akumulasi_bilyet' => 0, 'akumulasi_dus' => 0, 'sisa_bilyet' => 0, 'sisa_dus' => 0];

        foreach ($pecahanList as $pecahan) {
            $target = $targetMap->get($pecahan, 0);
            $pengRow = $pengemasanMap->get($pecahan);
            $akumulasiBilyet = $pengRow ? $pengRow->total_bilyet : 0;
            $akumulasiDus = $pengRow ? (int) $pengRow->total_dus : 0;

            $sisaBilyet = $target - $akumulasiBilyet;
            $data[] = [
                'pecahan' => $pecahan,
                'target_bilyet' => $target,
                'target_dus' => ceil($target / 20000),
                'akumulasi_bilyet' => $akumulasiBilyet,
                'akumulasi_dus' => $akumulasiDus,
                'sisa_bilyet' => $sisaBilyet,
                'sisa_dus' => $sisaBilyet / 20000,
                'persen' => $target > 0 ? ($akumulasiBilyet / $target) * 100 : 0,
            ];

            $totals['target_bilyet'] += $target;
            $totals['target_dus'] += ceil($target / 20000);
            $totals['akumulasi_bilyet'] += $akumulasiBilyet;
            $totals['akumulasi_dus'] += $akumulasiDus;
            $totals['sisa_bilyet'] += $sisaBilyet;
            $totals['sisa_dus'] += $sisaBilyet / 20000;
        }

        return ['data' => $data, 'totals' => $totals];
    }

    public function getMonthlyTargetAchievementData(array $filters): array
    {
        $tanggalLaporan = Carbon::parse($filters['tanggal_laporan']);
        $startOfMonth = $tanggalLaporan->copy()->startOfMonth()->toDateString();
        $currentDate = $tanggalLaporan->toDateString();
        $targetColumn = 'bulan_'.$tanggalLaporan->month;
        $pecahanList = ['S', 'T', 'U', 'V', 'W', 'X', 'Y'];

        // --- Aggregated Queries ---

        $targets = TargetBulananPengemasan::whereIn('pecahan', $pecahanList)
            ->where('tahun_anggaran', $filters['tahun_anggaran'])
            ->where('tahun_emisi', $filters['tahun_emisi'])
            ->get()
            ->keyBy('pecahan');

        $pengemasanMap = Pengemasan::whereIn('pecahan', $pecahanList)
            ->where('tahun_anggaran', $filters['tahun_anggaran'])
            ->where('tahun_emisi', $filters['tahun_emisi'])
            ->whereDate('tanggal_pengemasan', '>=', $startOfMonth)
            ->whereDate('tanggal_pengemasan', '<=', $currentDate)
            ->selectRaw('pecahan, SUM(total_bilyet) as total_bilyet, SUM(jumlah_dus) as total_dus')
            ->groupBy('pecahan')
            ->get()
            ->keyBy('pecahan');

        $data = [];
        $totals = ['target_bilyet' => 0, 'target_dus' => 0, 'akumulasi_bilyet' => 0, 'akumulasi_dus' => 0, 'sisa_bilyet' => 0, 'sisa_dus' => 0];

        foreach ($pecahanList as $pecahan) {
            $targetRow = $targets->get($pecahan);
            $targetBilyet = $targetRow ? ($targetRow->{$targetColumn} ?? 0) : 0;

            $pengRow = $pengemasanMap->get($pecahan);
            $akumulasiBilyet = $pengRow ? $pengRow->total_bilyet : 0;
            $akumulasiDus = $pengRow ? (int) $pengRow->total_dus : 0;

            $sisaBilyet = $targetBilyet - $akumulasiBilyet;
            $data[] = [
                'pecahan' => $pecahan,
                'target_bilyet' => $targetBilyet,
                'target_dus' => ceil($targetBilyet / 20000),
                'akumulasi_bilyet' => $akumulasiBilyet,
                'akumulasi_dus' => $akumulasiDus,
                'sisa_bilyet' => $sisaBilyet,
                'sisa_dus' => $sisaBilyet / 20000,
                'persen' => $targetBilyet > 0 ? ($akumulasiBilyet / $targetBilyet) * 100 : 0,
            ];

            $totals['target_bilyet'] += $targetBilyet;
            $totals['target_dus'] += ceil($targetBilyet / 20000);
            $totals['akumulasi_bilyet'] += $akumulasiBilyet;
            $totals['akumulasi_dus'] += $akumulasiDus;
            $totals['sisa_bilyet'] += $sisaBilyet;
            $totals['sisa_dus'] += $sisaBilyet / 20000;
        }

        return ['data' => $data, 'totals' => $totals];
    }

    private function calculateSisaHariKerja(Carbon $date): int
    {
        $endOfMonth = $date->copy()->endOfMonth();
        $count = 0;
        $current = $date->copy();
        while ($current <= $endOfMonth) {
            if ($current->isWeekday()) {
                $count++;
            }
            $current->addDay();
        }

        return $count;
    }

    private function getKemasJumlah($pecahan, $ta, $te, $start, $end, $gilir)
    {
        $query = Pengemasan::where('pecahan', $pecahan)->where('tahun_anggaran', $ta)->whereDate('tanggal_pengemasan', '>=', $start)->whereDate('tanggal_pengemasan', '<=', $end)->where('gilir', $gilir);
        if ($te) {
            $query->where('tahun_emisi', $te);
        }

        return $query->sum(DB::raw('jumlah_dus * 20000'));
    }

    private function getHcsJumlah($pecahan, $ta, $te, $start, $end, $supplier)
    {
        $query = HcsReceiving::where('pecahan', $pecahan)->where('tahun_anggaran', $ta)->whereDate('tanggal_penerimaan', '>=', $start)->whereDate('tanggal_penerimaan', '<=', $end)->where('supplier', $supplier);
        if ($te) {
            $query->where('emisi', $te);
        }

        return $query->sum('jumlah');
    }

    public function getRekonsiliasiData(array $filters): array
    {
        $startDate = Carbon::parse($filters['start_date']);
        $endDate = Carbon::parse($filters['end_date']);
        $tahunAnggaran = $filters['tahun_anggaran'];

        $pecahanList = ['S', 'T', 'U', 'V', 'W', 'X', 'Y'];

        // Menentukan bulan yang tercakup
        $months = [];
        $current = $startDate->copy()->startOfMonth();
        while ($current <= $endDate) {
            $months[] = $current->month;
            $current->addMonth();
        }

        // --- Aggregated Queries ---

        $receivingsMap = HcsReceiving::whereIn('pecahan', $pecahanList)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->whereDate('tanggal_penerimaan', '>=', $startDate->toDateString())
            ->whereDate('tanggal_penerimaan', '<=', $endDate->toDateString())
            ->selectRaw('pecahan, SUM(jumlah) as total')
            ->groupBy('pecahan')
            ->pluck('total', 'pecahan');

        $pengemasansMap = Pengemasan::whereIn('pecahan', $pecahanList)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->whereDate('tanggal_pengemasan', '>=', $startDate->toDateString())
            ->whereDate('tanggal_pengemasan', '<=', $endDate->toDateString())
            ->selectRaw('pecahan, SUM(total_bilyet) as total_bilyet, MIN(dus_awal) as min_dus, MAX(dus_akhir) as max_dus')
            ->groupBy('pecahan')
            ->get()
            ->keyBy('pecahan');

        $penyerahanMap = PenyerahanBi::whereIn('pecahan', $pecahanList)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->whereDate('tanggal_penyerahan', '>=', $startDate->toDateString())
            ->whereDate('tanggal_penyerahan', '<=', $endDate->toDateString())
            ->selectRaw('pecahan, SUM(jumlah_bilyet) as total_bilyet, MIN(nomor_dus_awal) as min_dus, MAX(nomor_dus_akhir) as max_dus')
            ->groupBy('pecahan')
            ->get()
            ->keyBy('pecahan');

        // Targets Summation Query
        $targetPengemasanQuery = TargetBulananPengemasan::whereIn('pecahan', $pecahanList)
            ->where('tahun_anggaran', $tahunAnggaran);

        $targetPenyerahanQuery = TargetBulanan::whereIn('pecahan', $pecahanList)
            ->where('tahun_anggaran', $tahunAnggaran);

        // Menjumlahkan nilai untuk seluruh bulan yang tercakup
        $sumColumns = [];
        foreach ($months as $m) {
            $sumColumns[] = "COALESCE(bulan_{$m}, 0)";
        }
        $sumExpression = empty($sumColumns) ? '0' : implode(' + ', $sumColumns);

        $targetPengemasanMap = $targetPengemasanQuery
            ->selectRaw("pecahan, SUM($sumExpression) as total_target")
            ->groupBy('pecahan')
            ->pluck('total_target', 'pecahan');

        $targetPenyerahanMap = $targetPenyerahanQuery
            ->selectRaw("pecahan, SUM($sumExpression) as total_target")
            ->groupBy('pecahan')
            ->pluck('total_target', 'pecahan');

        $data = [];
        $totals = [
            'penerimaan_hcs' => 0,
            'pengemasan_hcs' => 0,
            'penyerahan_hcs' => 0,
            'target_pengemasan' => 0,
            'target_penyerahan' => 0,
        ];

        foreach ($pecahanList as $pecahan) {
            $penerimaan = $receivingsMap->get($pecahan, 0);

            $pengemasanRow = $pengemasansMap->get($pecahan);
            $pengemasan = $pengemasanRow ? (int) $pengemasanRow->total_bilyet : 0;
            $minDus = $pengemasanRow ? $pengemasanRow->min_dus : null;
            $maxDus = $pengemasanRow ? $pengemasanRow->max_dus : null;

            $penyerahanRow = $penyerahanMap->get($pecahan);
            $penyerahan = $penyerahanRow ? (int) $penyerahanRow->total_bilyet : 0;
            $minDusPenyerahan = $penyerahanRow ? $penyerahanRow->min_dus : null;
            $maxDusPenyerahan = $penyerahanRow ? $penyerahanRow->max_dus : null;

            $minDusKemasVal = ($minDus > 0) ? (int) $minDus : '-';
            $maxDusKemasVal = ($maxDus > 0) ? (int) $maxDus : '-';
            $minDusSerahVal = ($minDusPenyerahan > 0) ? (int) $minDusPenyerahan : '-';
            $maxDusSerahVal = ($maxDusPenyerahan > 0) ? (int) $maxDusPenyerahan : '-';

            $targetPengemasan = $targetPengemasanMap->get($pecahan, 0);
            $targetPenyerahan = $targetPenyerahanMap->get($pecahan, 0);

            $data[] = [
                'pecahan' => $pecahan,
                'penerimaan_hcs' => $penerimaan,
                'pengemasan_hcs' => $pengemasan,
                'min_dus_kemas' => $minDusKemasVal,
                'max_dus_kemas' => $maxDusKemasVal,
                'penyerahan_hcs' => $penyerahan,
                'min_dus_serah' => $minDusSerahVal,
                'max_dus_serah' => $maxDusSerahVal,
                'target_pengemasan' => $targetPengemasan,
                'target_penyerahan' => $targetPenyerahan,
            ];

            $totals['penerimaan_hcs'] += $penerimaan;
            $totals['pengemasan_hcs'] += $pengemasan;
            $totals['penyerahan_hcs'] += $penyerahan;
            $totals['target_pengemasan'] += $targetPengemasan;
            $totals['target_penyerahan'] += $targetPenyerahan;
        }

        return ['data' => $data, 'totals' => $totals];
    }
}
