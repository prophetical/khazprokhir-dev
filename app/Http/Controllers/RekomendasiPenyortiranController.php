<?php

namespace App\Http\Controllers;

use App\Models\Pack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RekomendasiPenyortiranController extends Controller
{
    public function index(Request $request)
    {
        // 0. Ambil daftar tahun dan emisi untuk dropdown
        $availableYears = DB::table('hcs_receivings')->distinct()->whereNotNull('tahun_anggaran')->orderBy('tahun_anggaran', 'desc')->pluck('tahun_anggaran');
        $availableEmissions = DB::table('hcs_receivings')->distinct()->whereNotNull('emisi')->orderBy('emisi', 'desc')->pluck('emisi');

        // Ambil semua pack yang belum disortir, dikelompokkan sesuai data penerimaan
        // Karena datanya belum sampai jutaan, kita bisa query semuanya sekaligus dengan aman.
        $query = Pack::whereNull('hcs_sorting_id')
            ->join('hcs_receivings', 'packs.hcs_receiving_id', '=', 'hcs_receivings.id');

        if ($request->filled('tahun_anggaran')) {
            $query->where('hcs_receivings.tahun_anggaran', $request->tahun_anggaran);
        }
        if ($request->filled('emisi')) {
            $query->where('hcs_receivings.emisi', $request->emisi);
        }

        $unsortedPacksRaw = $query->select('hcs_receivings.pecahan', 'packs.batch', 'packs.seri', 'packs.pack_number', 'packs.supplier')
            ->orderBy('hcs_receivings.pecahan')
            ->orderBy('packs.batch')
            ->orderBy('packs.seri')
            ->orderBy('packs.pack_number')
            ->get();

        // Kelompokkan data berdasarkan Pecahan + Batch + Seri + Supplier
        $grouped = [];
        foreach ($unsortedPacksRaw as $p) {
            $key = $p->pecahan.'|'.$p->batch.'|'.$p->seri.'|'.$p->supplier;
            if (! isset($grouped[$key])) {
                $grouped[$key] = [
                    'pecahan' => $p->pecahan,
                    'batch' => $p->batch,
                    'seri' => $p->seri,
                    'packs' => [],
                    'supplier' => $p->supplier,
                ];
            }
            $grouped[$key]['packs'][] = $p->pack_number;
            // Catatan: Kalau ada lebih dari satu supplier di batch/seri yang sama, kita ambil yang pertama aja buat warna di UI.
        }

        $recommendations = [];

        // Logika utama untuk nyari rentang pack yang valid buat disortir
        foreach ($grouped as $groupKey => $data) {
            $sortedPackNumbers = $data['packs']; // Datanya udah urut berdasarkan nomor pack dari query SQL
            $consecutiveRanges = $this->extractConsecutiveRanges($sortedPackNumbers);

            $validRanges = [];

            foreach ($consecutiveRanges as $range) {
                // Algoritma: cari titik awal yang pas, yaitu pack yang memenuhi syarat (pack - 1) kelipatan 4
                // Terus dari titik awal itu, coba ambil kelompok-kelompok yang isinya 4 pack.

                $validSubRanges = $this->extractValidSubRanges($range);
                if (! empty($validSubRanges)) {
                    $validRanges = array_merge($validRanges, $validSubRanges);
                }
            }

            if (! empty($validRanges)) {
                // Ubah array rentang pack jadi format teks biar gampang dibaca di tabel UI
                $totalPacksAll = 0;
                $rangeStrings = [];
                $flatPacks = [];

                foreach ($validRanges as $vr) {
                    $c = count($vr);
                    $totalPacksAll += $c;
                    $start = $vr[0];
                    $end = $vr[$c - 1];
                    if ($start === $end) {
                        $rangeStrings[] = $start;
                    } else {
                        $rangeStrings[] = $start.'-'.$end;
                    }
                    $flatPacks = array_merge($flatPacks, $vr);
                }

                $recommendations[] = [
                    'pecahan' => $data['pecahan'],
                    'batch' => $data['batch'],
                    'seri' => $data['seri'],
                    'supplier' => $data['supplier'],
                    'ranges_raw' => $validRanges, // Daftar array yang isinya nomor-nomor pack
                    'ranges_str' => $rangeStrings, // contohnya: ["1-8", "13-16"]
                    'flat_packs' => $flatPacks, // contoh datanya: [1,2,3,4,5,6,7,8,13,14,15,16]
                    'total_pack' => $totalPacksAll,
                    'total_bilyet' => $totalPacksAll * 45000,
                ];
            }
        }

        // Handle filtering if any
        if ($request->filled('pecahan')) {
            $recommendations = array_filter($recommendations, fn ($r) => $r['pecahan'] == $request->pecahan);
        }
        if ($request->filled('batch')) {
            $recommendations = array_filter($recommendations, fn ($r) => str_contains($r['batch'], $request->batch));
        }
        if ($request->filled('seri')) {
            $recommendations = array_filter($recommendations, fn ($r) => str_contains($r['seri'], $request->seri));
        }

        // Hitung ringkasan per pecahan dari hasil rekomendasi
        $expectedPecahan = ['S', 'T', 'U', 'V', 'W', 'X', 'Y'];
        $summaries = [];
        foreach ($expectedPecahan as $p) {
            $summaries[$p] = [
                'total_pack' => 0,
                'total_bilyet' => 0,
            ];
        }

        $totalAllPacks = 0;
        $totalAllBilyet = 0;

        foreach ($recommendations as $r) {
            $p = $r['pecahan'];
            if (isset($summaries[$p])) {
                $summaries[$p]['total_pack'] += $r['total_pack'];
                $summaries[$p]['total_bilyet'] += $r['total_bilyet'];
            }
            $totalAllPacks += $r['total_pack'];
            $totalAllBilyet += $r['total_bilyet'];
        }

        // Kita bisa lakukan pagination sederhana secara manual
        $page = $request->get('page', 1);
        $perPage = 20;
        $offset = ($page - 1) * $perPage;

        $totalItems = count($recommendations); // Setelah difilter
        $sliced = array_slice($recommendations, $offset, $perPage);
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator($sliced, $totalItems, $perPage, $page, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);

        return view('rekomendasi-penyortiran.index', compact('paginator', 'summaries', 'totalAllPacks', 'totalAllBilyet', 'availableYears', 'availableEmissions'));
    }

    /**
     * Helper to split an array of ordered numbers into subgroups of completely consecutive numbers.
     * e.g. [1,2,3,4,5,8,9,10] => [[1,2,3,4,5], [8,9,10]]
     */
    private function extractConsecutiveRanges(array $numbers)
    {
        if (empty($numbers)) {
            return [];
        }

        $ranges = [];
        $currentRange = [$numbers[0]];

        for ($i = 1; $i < count($numbers); $i++) {
            if ($numbers[$i] == $numbers[$i - 1] + 1) {
                $currentRange[] = $numbers[$i];
            } else {
                $ranges[] = $currentRange;
                $currentRange = [$numbers[$i]];
            }
        }
        if (! empty($currentRange)) {
            $ranges[] = $currentRange;
        }

        return $ranges;
    }

    /**
     * Given a continuous range of numbers (e.g. [1,2,3,4,5,6,7,8]),
     * Extract the maximum possible valid chunks where length % 4 == 0 AND start_idx % 4 == 1.
     * Often, there's only 1 valid sub-range per consecutive range.
     */
    private function extractValidSubRanges(array $consecutiveNumbers)
    {
        // 1. Find the first pack in this list that satisfies (p-1) % 4 == 0.
        // e.g. packs [2,3,4,5,6,7,8], pack '5' is the first valid start.
        $startIndex = -1;
        for ($i = 0; $i < count($consecutiveNumbers); $i++) {
            if (($consecutiveNumbers[$i] - 1) % 4 === 0) {
                $startIndex = $i;
                break;
            }
        }

        if ($startIndex === -1) {
            // No valid starting point found at all (e.g. [2,3])
            return [];
        }

        // We slice the array from that valid start point
        $sliced = array_slice($consecutiveNumbers, $startIndex);

        // Now we just take as many multiples of 4 as we can.
        $validLength = floor(count($sliced) / 4) * 4;

        if ($validLength < 4) {
            // Found a valid start (like 5), but we only had [5,6,7], length 3. Not enough for a full mod 4 block.
            return [];
        }

        $finalValidRange = array_slice($sliced, 0, $validLength);

        return [$finalValidRange];
    }
}
