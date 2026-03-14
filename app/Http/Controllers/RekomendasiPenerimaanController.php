<?php

namespace App\Http\Controllers;

use App\Models\HcsReceiving;
use App\Models\Pack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RekomendasiPenerimaanController extends Controller
{
    public function index(Request $request)
    {
        $query = HcsReceiving::select(
            'pecahan',
            'batch',
            'seri',
            'tahun_anggaran',
            'emisi',
            DB::raw('count(*) as total_receiving')
        )
            ->groupBy('pecahan', 'batch', 'seri', 'tahun_anggaran', 'emisi');

        if ($request->filled('pecahan')) {
            $query->where('pecahan', $request->pecahan);
        }

        if ($request->filled('batch')) {
            $query->where('batch', 'like', '%'.$request->batch.'%');
        }

        if ($request->filled('seri')) {
            $query->where('seri', 'like', '%'.$request->seri.'%');
        }

        $batches = $query->get()->map(function ($item) {
            // Ambil semua pack yang BELUM DISORTIR untuk batch & seri ini
            $unsortedPacks = Pack::with('hcsReceiving')->where([
                'batch' => $item->batch,
                'seri' => $item->seri,
            ])->whereNull('hcs_sorting_id')->get();

            // Grouping by ceil(pack_number / 4)
            $groups = $unsortedPacks->groupBy(function ($pack) {
                return ceil($pack->pack_number / 4);
            }
            );

            $existingUnsortedSingle = []; // Pack yang sudah ada tapi belum ada pasangan (group < 4)
            $recommendedPacks = []; // Pack rekomendasi

            foreach ($groups as $groupNumber => $packsInGroup) {
                $count = $packsInGroup->count();
                if ($count >= 1 && $count < 4) {
                    // Masuk kategori: belum disortir & belum lengkap pasanganya
                    $firstPack = $packsInGroup->first();
                    $supplier = $firstPack->supplier;

                    foreach ($packsInGroup as $pack) {
                        $existingUnsortedSingle[] = [
                            'number' => $pack->pack_number,
                            'supplier' => $supplier,
                            'received_at' => $pack->hcsReceiving ? $pack->hcsReceiving->created_at->format('d M Y') : '-',
                        ];
                    }

                    // Rekomendasi pelengkap
                    $startRange = ($groupNumber - 1) * 4 + 1;
                    $endRange = $groupNumber * 4;
                    $nums = $packsInGroup->pluck('pack_number')->toArray();
                    for ($i = $startRange; $i <= $endRange; $i++) {
                        if (! in_array($i, $nums)) {
                            $recommendedPacks[] = [
                                'number' => $i,
                                'supplier' => $supplier,
                            ];
                        }
                    }
                }
            }

            $item->existing_unsorted_single = $existingUnsortedSingle;
            $item->recommended_packs = $recommendedPacks;

            return $item;
        })->filter(function ($item) {
            return count($item->recommended_packs) > 0;
        });

        return view('hcs-receiving.rekomendasi.index', compact('batches'));
    }

    public function show(Request $request)
    {
        $params = $request->only(['pecahan', 'batch', 'seri', 'tahun_anggaran', 'emisi']);

        // Ambil semua pack yang BELUM DISORTIR dlm batch/seri ini
        $existingPacks = Pack::where([
            'batch' => $params['batch'],
            'seri' => $params['seri'],
        ])->whereNull('hcs_sorting_id')->get();

        $recommendations = [];

        // Grouping packs by group_number = ceil(pack_number / 4)
        $groups = $existingPacks->groupBy(function ($pack) {
            return ceil($pack->pack_number / 4);
        });

        foreach ($groups as $groupNumber => $packsInGroup) {
            $count = $packsInGroup->count();

            // Rekomendasikan jika grup (1-3 packs) masih belum lengkap (4 packs)
            if ($count >= 1 && $count < 4) {
                $existingNumbersInGroup = $packsInGroup->pluck('pack_number')->toArray();

                // Cari pack mana saja dlm group ini (1-4, 5-8, dst) yang belum ada
                $startRange = ($groupNumber - 1) * 4 + 1;
                $endRange = $groupNumber * 4;

                // Supplier mengikuti pack yang sudah ada di grup tersebut
                $supplier = $packsInGroup->first()->supplier;

                for ($i = $startRange; $i <= $endRange; $i++) {
                    if (! in_array($i, $existingNumbersInGroup)) {
                        $recommendations[] = [
                            'group' => $groupNumber,
                            'pecahan' => $params['pecahan'],
                            'batch' => $params['batch'],
                            'seri' => $params['seri'],
                            'tahun_anggaran' => $params['tahun_anggaran'],
                            'emisi' => $params['emisi'],
                            'supplier' => $supplier,
                            'pack_number' => $i,
                        ];
                    }
                }
            }
        }

        return view('hcs-receiving.rekomendasi.show', [
            'params' => $params,
            'recommendations' => $recommendations,
        ]);
    }

    public function print(Request $request)
    {
        $query = HcsReceiving::select(
            'pecahan',
            'batch',
            'seri',
            'tahun_anggaran',
            'emisi'
        )
            ->groupBy('pecahan', 'batch', 'seri', 'tahun_anggaran', 'emisi');

        if ($request->filled('pecahan')) {
            $query->where('pecahan', $request->pecahan);
        }
        if ($request->filled('batch')) {
            $query->where('batch', 'like', '%'.$request->batch.'%');
        }
        if ($request->filled('seri')) {
            $query->where('seri', 'like', '%'.$request->seri.'%');
        }

        $batches = $query->get()->map(function ($item) {
            $unsortedPacks = Pack::where([
                'batch' => $item->batch,
                'seri' => $item->seri,
            ])->whereNull('hcs_sorting_id')->get();

            $groups = $unsortedPacks->groupBy(function ($pack) {
                return ceil($pack->pack_number / 4);
            }
            );

            $existingUnsortedSingle = [];
            $recommendedPacks = [];

            foreach ($groups as $groupNumber => $packsInGroup) {
                $count = $packsInGroup->count();
                if ($count >= 1 && $count < 4) {
                    $supplier = $packsInGroup->first()->supplier;
                    $nums = $packsInGroup->pluck('pack_number')->toArray();
                    foreach ($nums as $n) {
                        $existingUnsortedSingle[] = ['number' => $n, 'supplier' => $supplier];
                    }
                    $startRange = ($groupNumber - 1) * 4 + 1;
                    $endRange = $groupNumber * 4;
                    for ($i = $startRange; $i <= $endRange; $i++) {
                        if (! in_array($i, $nums)) {
                            $recommendedPacks[] = ['number' => $i, 'supplier' => $supplier];
                        }
                    }
                }
            }
            $item->existing_unsorted_single = $existingUnsortedSingle;
            $item->recommended_packs = $recommendedPacks;

            return $item;
        });

        // Hanya tampilkan yang punya rekomendasi
        $batches = $batches->filter(function ($item) {
            return count($item->recommended_packs) > 0;
        });

        return view('hcs-receiving.rekomendasi.print', compact('batches'));
    }

    public function export(Request $request)
    {
        $query = HcsReceiving::select(
            'pecahan',
            'batch',
            'seri',
            'tahun_anggaran',
            'emisi'
        )
            ->groupBy('pecahan', 'batch', 'seri', 'tahun_anggaran', 'emisi');

        if ($request->filled('pecahan')) {
            $query->where('pecahan', $request->pecahan);
        }
        if ($request->filled('batch')) {
            $query->where('batch', 'like', '%'.$request->batch.'%');
        }
        if ($request->filled('seri')) {
            $query->where('seri', 'like', '%'.$request->seri.'%');
        }

        $filename = 'rekomendasi_penerimaan_'.date('Y-m-d').'.csv';
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($query) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Pecahan', 'Batch', 'Seri', 'TA', 'Emisi', 'Pack Existing', 'Pack Rekomendasi']);

            $query->chunk(100, function ($batchesRaw) use ($file) {
                foreach ($batchesRaw as $item) {
                    $unsortedPacks = Pack::where([
                        'batch' => $item->batch,
                        'seri' => $item->seri,
                    ])->whereNull('hcs_sorting_id')->get();

                    $groups = $unsortedPacks->groupBy(function ($pack) {
                        return ceil($pack->pack_number / 4);
                    }
                    );

                    $existing = [];
                    $recommended = [];

                    foreach ($groups as $groupNumber => $packsInGroup) {
                        $count = $packsInGroup->count();
                        if ($count >= 1 && $count < 4) {
                            $nums = $packsInGroup->pluck('pack_number')->toArray();
                            foreach ($nums as $n) {
                                $existing[] = $n;
                            }

                            $startRange = ($groupNumber - 1) * 4 + 1;
                            $endRange = $groupNumber * 4;
                            for ($i = $startRange; $i <= $endRange; $i++) {
                                if (! in_array($i, $nums)) {
                                    $recommended[] = $i;
                                }
                            }
                        }
                    }

                    if (! empty($existing)) {
                        fputcsv($file, [
                            $item->pecahan,
                            $item->batch,
                            $item->seri,
                            $item->tahun_anggaran,
                            $item->emisi,
                            implode(', ', $existing),
                            implode(', ', $recommended),
                        ]);
                    }
                }
            }
            );

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
