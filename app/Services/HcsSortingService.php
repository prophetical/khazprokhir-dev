<?php

namespace App\Services;

use App\Models\HcsSorting;
use App\Models\Pack;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class HcsSortingService
{
    public function getAvailableGroups(array $filters, int $perPage)
    {
        $query = Pack::whereNull('hcs_sorting_id')->join('hcs_receivings', 'packs.hcs_receiving_id', '=', 'hcs_receivings.id');
        if (!empty($filters['pecahan'])) $query->where('hcs_receivings.pecahan', $filters['pecahan']);
        if (!empty($filters['batch'])) $query->where('packs.batch', 'like', '%'.$filters['batch'].'%');
        if (!empty($filters['seri'])) $query->where('packs.seri', 'like', '%'.$filters['seri'].'%');
        if (!empty($filters['tahun_anggaran'])) $query->where('hcs_receivings.tahun_anggaran', $filters['tahun_anggaran']);
        if (!empty($filters['emisi'])) $query->where('hcs_receivings.emisi', $filters['emisi']);

        return $query->select('hcs_receivings.pecahan', 'packs.batch', 'packs.seri', DB::raw('count(*) as total_pack'))
            ->groupBy('hcs_receivings.pecahan', 'packs.batch', 'packs.seri')
            ->orderBy('total_pack', 'desc')
            ->paginate($perPage);
    }

    public function getSummaries(array $filters): array
    {
        $query = Pack::whereNull('hcs_sorting_id')->join('hcs_receivings', 'packs.hcs_receiving_id', '=', 'hcs_receivings.id');
        if (!empty($filters['tahun_anggaran'])) $query->where('hcs_receivings.tahun_anggaran', $filters['tahun_anggaran']);
        if (!empty($filters['emisi'])) $query->where('hcs_receivings.emisi', $filters['emisi']);

        $data = $query->select('hcs_receivings.pecahan', DB::raw('count(*) as total_pack'))->groupBy('hcs_receivings.pecahan')->pluck('total_pack', 'hcs_receivings.pecahan')->toArray();
        $expected = ['S', 'T', 'U', 'V', 'W', 'X', 'Y'];
        $summaries = []; $total = 0;
        foreach ($expected as $p) {
            $count = $data[$p] ?? 0;
            $summaries[$p] = $count; $total += $count;
        }
        return ['summaries' => $summaries, 'total' => $total];
    }

    public function getPacksData(string $pecahan, string $batch, string $seri)
    {
        return Pack::join('hcs_receivings', 'packs.hcs_receiving_id', '=', 'hcs_receivings.id')
            ->where('hcs_receivings.pecahan', $pecahan)->where('packs.batch', $batch)->where('packs.seri', $seri)
            ->where('packs.pack_number', '<=', 100)
            ->select('packs.*', 'packs.supplier as pack_supplier', 'hcs_receivings.pecahan', 'hcs_receivings.emisi', 'hcs_receivings.tahun_anggaran')
            ->get()->keyBy('pack_number');
    }

    public function validateContiguousBlocks(array $packs)
    {
        sort($packs);
        $blocks = []; $current = [];
        foreach ($packs as $num) {
            $num = (int)$num;
            if (empty($current)) { $current[] = $num; }
            else {
                if ($num == end($current) + 1) { $current[] = $num; }
                else { $blocks[] = $current; $current = [$num]; }
            }
        }
        if (!empty($current)) $blocks[] = $current;

        foreach ($blocks as $block) {
            if (count($block) % 4 !== 0) throw ValidationException::withMessages(['selected_packs' => 'Pack harus berurutan dan berkelipatan 4 (1-4, 5-8, dst).']);
            if (($block[0] - 1) % 4 !== 0) throw ValidationException::withMessages(['selected_packs' => 'Posisi awal pack tidak valid. Harus dimulai dari 1, 5, 9, dst (kelipatan 4).']);
        }
    }

    public function processStore(array $data, int $userId)
    {
        $selected = $data['selected_packs'];
        sort($selected);

        if (!isset($data['is_manual'])) {
            $this->validateContiguousBlocks($selected);
        }

        $already = Pack::where('batch', $data['batch'])->where('seri', $data['seri'])->whereIn('pack_number', $selected)->whereNotNull('hcs_sorting_id')->exists();
        if ($already) throw ValidationException::withMessages(['selected_packs' => 'Salah satu pack sudah disortir sebelumnya.']);

        $jumlahBilyet = Pack::where('batch', $data['batch'])->where('seri', $data['seri'])->whereIn('pack_number', $selected)->sum('jumlah');

        return DB::transaction(function() use ($data, $selected, $jumlahBilyet, $userId) {
            $sorting = HcsSorting::create([
                'pecahan' => $data['pecahan'], 'batch' => $data['batch'], 'seri' => $data['seri'], 'emisi' => $data['emisi'],
                'tahun_anggaran' => $data['tahun_anggaran'], 'supplier' => $data['supplier'], 'packs_selected' => $selected,
                'jumlah_pack' => count($selected), 'jumlah_bilyet' => $jumlahBilyet, 'petugas_1' => $data['petugas_1'],
                'petugas_2' => $data['petugas_2'], 'tanggal_sortir' => $data['tanggal'], 'gilir' => $data['gilir'], 'created_by' => $userId,
            ]);

            Pack::where('batch', $data['batch'])->where('seri', $data['seri'])->whereIn('pack_number', $selected)->update(['hcs_sorting_id' => $sorting->id]);
            return $sorting;
        });
    }

    public function processUpdate(HcsSorting $model, array $data)
    {
        if ($model->status_kunci_pengemasan) throw new Exception('Data tidak dapat diubah karena sudah dikunci pengemasan.');

        $selected = $data['selected_packs'];
        sort($selected);

        if (!isset($data['is_manual'])) {
            $this->validateContiguousBlocks($selected);
        }

        $already = Pack::where('batch', $model->batch)->where('seri', $model->seri)->whereIn('pack_number', $selected)
            ->whereNotNull('hcs_sorting_id')->where('hcs_sorting_id', '!=', $model->id)->exists();
        if ($already) throw ValidationException::withMessages(['selected_packs' => 'Salah satu pack sudah disortir oleh sesi lain.']);

        $jumlahBilyet = Pack::where('batch', $model->batch)->where('seri', $model->seri)->whereIn('pack_number', $selected)->sum('jumlah');

        return DB::transaction(function() use ($model, $data, $selected, $jumlahBilyet) {
            Pack::where('hcs_sorting_id', $model->id)->update(['hcs_sorting_id' => null]);
            Pack::where('batch', $model->batch)->where('seri', $model->seri)->whereIn('pack_number', $selected)->update(['hcs_sorting_id' => $model->id]);

            $model->update([
                'supplier' => $data['supplier'], 'emisi' => $data['emisi'], 'packs_selected' => $selected,
                'jumlah_pack' => count($selected), 'jumlah_bilyet' => $jumlahBilyet, 'petugas_1' => $data['petugas_1'],
                'petugas_2' => $data['petugas_2'] ?? null, 'tanggal_sortir' => $data['tanggal_sortir'], 'gilir' => $data['gilir'],
            ]);
            return $model;
        });
    }

    public function processDelete(HcsSorting $model)
    {
        if ($model->status_kunci_pengemasan) throw new Exception('Data tidak dapat dihapus karena sudah dikunci pengemasan.');

        return DB::transaction(function() use ($model) {
            Pack::where('hcs_sorting_id', $model->id)->update(['hcs_sorting_id' => null]);
            return $model->delete();
        });
    }

    public function formatPacksToRanges(array $packs): string
    {
        if (empty($packs)) return '';
        sort($packs, SORT_NUMERIC);
        $ranges = []; $i = 0;
        while ($i < count($packs)) {
            $start = $packs[$i]; $end = $start;
            while (isset($packs[$i + 1]) && $packs[$i + 1] == $end + 1) {
                $end = $packs[$i + 1]; $i++;
            }
            $ranges[] = ($start == $end) ? $start : $start.'-'.$end;
            $i++;
        }
        return implode(' | ', $ranges);
    }

    public function getAvailableFilterOptions(): array
    {
        return [
            'years' => DB::table('hcs_receivings')->distinct()->whereNotNull('tahun_anggaran')->orderBy('tahun_anggaran', 'desc')->pluck('tahun_anggaran'),
            'emissions' => DB::table('hcs_receivings')->distinct()->whereNotNull('emisi')->orderBy('emisi', 'desc')->pluck('emisi'),
        ];
    }
}
