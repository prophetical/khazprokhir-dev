<?php

namespace App\Http\Controllers;

use App\Models\HcsSorting;
use Illuminate\Http\Request;

class HcsSortingReportController extends Controller
{
    public function index(Request $request)
    {
        $query = HcsSorting::query();

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_sortir', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_sortir', '<=', $request->tanggal_sampai);
        }
        if ($request->filled('batch')) {
            $query->where('batch', 'like', '%' . $request->batch . '%');
        }
        if ($request->filled('seri')) {
            $query->where('seri', 'like', '%' . $request->seri . '%');
        }
        if ($request->filled('gilir')) {
            $query->where('gilir', $request->gilir);
        }

        $reports = $query->orderBy('tanggal_sortir', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('hcs-sorting-report.index', compact('reports'));
    }

    public function edit(HcsSorting $hcs_sorting_report)
    {
        // Fetch packs related to this reporting context (same pecahan, batch, seri)
        $packsData = \App\Models\Pack::join('hcs_receivings', 'packs.hcs_receiving_id', '=', 'hcs_receivings.id')
            ->where('hcs_receivings.pecahan', $hcs_sorting_report->pecahan)
            ->where('packs.batch', $hcs_sorting_report->batch)
            ->where('packs.seri', $hcs_sorting_report->seri)
            ->where('packs.pack_number', '<=', 100)
            ->select('packs.*', 'packs.supplier as pack_supplier', 'hcs_receivings.pecahan')
            ->get()
            ->keyBy('pack_number');

        return view('hcs-sorting-report.edit', compact('hcs_sorting_report', 'packsData'));
    }

    public function update(Request $request, HcsSorting $hcs_sorting_report)
    {
        $request->validate([
            'supplier' => 'required|in:Rikyet,Cutpack',
            'petugas_1' => 'required',
            'petugas_2' => 'nullable',
            'tanggal_sortir' => 'required|date',
            'gilir' => 'required',
            'selected_packs' => 'required|array|min:4',
        ]);

        $selectedPacks = $request->selected_packs;
        sort($selectedPacks);

        // Validation 1: Must be in groups of 4 and sequential
        $contiguousBlocks = [];
        $currentBlock = [];
        foreach ($selectedPacks as $packNum) {
            $packNum = (int)$packNum;
            if (empty($currentBlock)) {
                $currentBlock[] = $packNum;
            }
            else {
                $lastNum = end($currentBlock);
                if ($packNum == $lastNum + 1) {
                    $currentBlock[] = $packNum;
                }
                else {
                    $contiguousBlocks[] = $currentBlock;
                    $currentBlock = [$packNum];
                }
            }
        }
        if (!empty($currentBlock)) {
            $contiguousBlocks[] = $currentBlock;
        }

        foreach ($contiguousBlocks as $block) {
            if (count($block) % 4 !== 0) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'selected_packs' => 'Pack yang dipilih harus berurutan dan berkelipatan 4 (contoh: 1-4, 5-8, dll).',
                ]);
            }

            if (($block[0] - 1) % 4 !== 0) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'selected_packs' => 'Posisi awal pack yang dipilih tidak valid. Harus dimulai dari kelipatan yang benar (misal: 1, 5, 9, dst).',
                ]);
            }
        }

        // Ensure none of these packs are already sorted by ANOTHER session
        $alreadySorted = \App\Models\Pack::where('batch', $hcs_sorting_report->batch)
            ->where('seri', $hcs_sorting_report->seri)
            ->whereIn('pack_number', $selectedPacks)
            ->whereNotNull('hcs_sorting_id')
            ->where('hcs_sorting_id', '!=', $hcs_sorting_report->id) // Exclude current session
            ->exists();

        if ($alreadySorted) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'selected_packs' => 'Salah satu atau lebih pack yang dipilih sudah disortir oleh sesi lain.',
            ]);
        }

        $jumlahPack = count($selectedPacks);
        $jumlahBilyet = $jumlahPack * 45000;

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // Nullify ALL previously owned packs for this session
            \App\Models\Pack::where('hcs_sorting_id', $hcs_sorting_report->id)
                ->update(['hcs_sorting_id' => null]);

            // Assign the newly selected packs
            \App\Models\Pack::where('batch', $hcs_sorting_report->batch)
                ->where('seri', $hcs_sorting_report->seri)
                ->whereIn('pack_number', $selectedPacks)
                ->update(['hcs_sorting_id' => $hcs_sorting_report->id]);

            // Update the report metadata
            $hcs_sorting_report->update([
                'supplier' => $request->supplier,
                'packs_selected' => $selectedPacks,
                'jumlah_pack' => $jumlahPack,
                'jumlah_bilyet' => $jumlahBilyet,
                'petugas_1' => $request->petugas_1,
                'petugas_2' => $request->petugas_2,
                'tanggal_sortir' => $request->tanggal_sortir,
                'gilir' => $request->gilir,
            ]);

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('hcs-sorting-reports.index')->with('success', 'Data laporan penyortiran berhasil diubah.');
        }
        catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(HcsSorting $hcs_sorting_report)
    {
        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // Nullify the associated packs
            \App\Models\Pack::where('hcs_sorting_id', $hcs_sorting_report->id)
                ->update(['hcs_sorting_id' => null]);

            // Delete the report
            $hcs_sorting_report->delete();

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('hcs-sorting-reports.index')->with('success', 'Data laporan penyortiran berhasil dihapus.');
        }
        catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        $query = HcsSorting::query();

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_sortir', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_sortir', '<=', $request->tanggal_sampai);
        }
        if ($request->filled('batch')) {
            $query->where('batch', 'like', '%' . $request->batch . '%');
        }
        if ($request->filled('seri')) {
            $query->where('seri', 'like', '%' . $request->seri . '%');
        }
        if ($request->filled('gilir')) {
            $query->where('gilir', $request->gilir);
        }

        $tanggalDari = $request->tanggal_dari ?: 'all';
        $tanggalSampai = $request->tanggal_sampai ?: 'all';
        $filename = "report_penyortiran_" . $tanggalDari . "_to_" . $tanggalSampai . ".csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($query) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Tanggal', 'Gilir', 'Batch', 'Seri', 'Pecahan', 'Supplier', 'Pack Terpilih', 'Jumlah Pack', 'Total Bilyet', 'Petugas 1', 'Petugas 2']);

            $query->orderBy('tanggal_sortir', 'desc')->orderBy('created_at', 'desc')->chunk(100, function ($reports) use ($file) {
                    foreach ($reports as $row) {
                        $arr = is_array($row->packs_selected) ? $row->packs_selected : [];
                        sort($arr, SORT_NUMERIC);
                        $ranges = [];
                        $i = 0;
                        while ($i < count($arr)) {
                            $start = $arr[$i];
                            $end = $start;
                            while (isset($arr[$i + 1]) && $arr[$i + 1] == $end + 1) {
                                $end = $arr[$i + 1];
                                $i++;
                            }
                            $ranges[] = ($start == $end) ? $start : $start . '-' . $end;
                            $i++;
                        }
                        $displayStr = implode(' | ', $ranges);

                        fputcsv($file, [
                            $row->tanggal_sortir->format('Y-m-d'),
                            $row->gilir,
                            $row->batch,
                            $row->seri,
                            $row->pecahan,
                            $row->supplier,
                            $displayStr,
                            $row->jumlah_pack,
                            $row->jumlah_bilyet,
                            $row->petugas_1,
                            $row->petugas_2 ?? '-'
                        ]);
                    }
                }
                );

                fclose($file);
            };

        return response()->stream($callback, 200, $headers);
    }

    public function print(Request $request)
    {
        $query = HcsSorting::query();

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_sortir', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_sortir', '<=', $request->tanggal_sampai);
        }
        if ($request->filled('batch')) {
            $query->where('batch', 'like', '%' . $request->batch . '%');
        }
        if ($request->filled('seri')) {
            $query->where('seri', 'like', '%' . $request->seri . '%');
        }
        if ($request->filled('gilir')) {
            $query->where('gilir', $request->gilir);
        }

        $reports = $query->orderBy('tanggal_sortir', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('hcs-sorting-report.print', compact('reports'));
    }
}
