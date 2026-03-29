<?php

namespace App\Services;

use App\Models\HcsReceiving;
use Illuminate\Support\Collection;

class ReceivingReportService
{
    public function getReportData(array $filters): array
    {
        $query = HcsReceiving::with('user');
        $this->applyFilters($query, $filters);

        $data = $query->orderBy('tanggal_penerimaan', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($filters['per_page'] ?? 20)
            ->withQueryString();

        $availableYears = HcsReceiving::distinct()->orderBy('tahun_anggaran', 'desc')->pluck('tahun_anggaran');
        $availableEmissions = HcsReceiving::distinct()->orderBy('emisi', 'desc')->pluck('emisi');

        $globalTotals = $this->getTotals($filters, true);
        $globalGrandTotal = $globalTotals->sum();

        return array_merge($filters, compact('data', 'availableYears', 'availableEmissions', 'globalTotals', 'globalGrandTotal'));
    }

    public function getTotals(array $filters, bool $global = false): Collection
    {
        $query = HcsReceiving::selectRaw('pecahan, SUM(jumlah) as total');

        if ($global) {
            // Global totals only respect TA/TE
            if (!empty($filters['tahun_anggaran'])) $query->where('tahun_anggaran', $filters['tahun_anggaran']);
            if (!empty($filters['tahun_emisi'])) $query->where('emisi', $filters['tahun_emisi']);
        } else {
            $this->applyFilters($query, $filters);
        }

        $totals = $query->groupBy('pecahan')->pluck('total', 'pecahan');
        return collect(['S' => 0, 'T' => 0, 'U' => 0, 'V' => 0, 'W' => 0, 'X' => 0, 'Y' => 0])->merge($totals);
    }

    public function getExportQuery(array $filters)
    {
        $query = HcsReceiving::with('user');
        $this->applyFilters($query, $filters);
        return $query;
    }

    protected function applyFilters($query, array $filters)
    {
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('tanggal_penerimaan', [$filters['start_date'], $filters['end_date']]);
        }
        if (!empty($filters['gilir'])) $query->where('gilir', $filters['gilir']);
        if (!empty($filters['pecahan'])) $query->where('pecahan', $filters['pecahan']);
        if (!empty($filters['tahun_anggaran'])) $query->where('tahun_anggaran', $filters['tahun_anggaran']);
        if (!empty($filters['tahun_emisi'])) $query->where('emisi', $filters['tahun_emisi']);
    }
}
