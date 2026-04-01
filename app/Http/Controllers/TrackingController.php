<?php

namespace App\Http\Controllers;

use App\Models\Pack;
use App\Models\PenyerahanBi;
use App\Models\HctsSubmissionBatch;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    /**
     * Display the tracking timeline based on search.
     */
    public function index(Request $request)
    {
        $hasSearched = false;
        $pack = null;
        $submissionStatus = null; // null | 'bi' | 'hcts'
        $submissionData = null;

        if ($request->filled('pack_number') || $request->filled('seri') || $request->filled('tahun_anggaran') || $request->filled('tahun_emisi') || $request->filled('pecahan')) {
            $request->validate([
                'pack_number' => 'required',
                'seri' => ['required', 'regex:/^[a-zA-Z]{2}-[a-zA-Z]{2}\d+$/'],
                'tahun_anggaran' => 'required',
                'tahun_emisi' => 'required',
                'pecahan' => 'required',
            ]);

            $hasSearched = true;

            $pack = Pack::with(['hcsReceiving', 'hcsSorting', 'pengemasan', 'user'])
                ->where('pack_number', $request->pack_number)
                ->where('seri', $request->seri)
                ->whereHas('hcsReceiving', function ($query) use ($request) {
                    $query->where('emisi', $request->tahun_emisi)
                          ->where('tahun_anggaran', $request->tahun_anggaran)
                          ->where('pecahan', $request->pecahan);
                })
                ->first();

            // Jika pack ditemukan, evaluasi status penyerahannya
            if ($pack) {
                // Skenario 1: Penyerahan BI (HCS)
                // Jika sudah dikemas, kita cek apakah nomor dus pack tsb masuk dlm rentang PenyerahanBi
                if ($pack->pengemasan) {
                    $pengemasan = $pack->pengemasan;
                    
                    // Kita asumsikan pengemasan dicatat dan diserahkan dalam satuan batch (semua dus dalam pengemasan ini diserahkan bersamaan)
                    // Atau kita cek apakah setidaknya ada irisan dus
                    $penyerahanBi = PenyerahanBi::where('pecahan', $pack->hcsReceiving->pecahan)
                        ->where('tahun_emisi', $pack->hcsReceiving->emisi)
                        ->where('tahun_anggaran', $pack->hcsReceiving->tahun_anggaran)
                        ->where(function ($query) use ($pengemasan) {
                            $query->where('nomor_dus_awal', '<=', $pengemasan->dus_akhir)
                                  ->where('nomor_dus_akhir', '>=', $pengemasan->dus_awal);
                        })
                        ->orderBy('tanggal_penyerahan', 'desc')
                        ->first();

                    if ($penyerahanBi) {
                        $submissionStatus = 'bi';
                        $submissionData = collect([
                            'tanggal' => $penyerahanBi->tanggal_penyerahan,
                            'nomor_ba' => $penyerahanBi->nomor_ba,
                            'status_data' => $penyerahanBi->status_data
                        ]);
                    }
                }

                // Skenario 2: Penyerahan HCTS
                // Cek batch dari tabel packs yang direlasikan ke hcts_submission_batches
                if (!$submissionStatus && $pack->batch) {
                    $hctsBatch = HctsSubmissionBatch::with('submission')
                        ->where('batch', $pack->batch)
                        ->first();

                    if ($hctsBatch && $hctsBatch->submission) {
                        $submissionStatus = 'hcts';
                        $submissionData = collect([
                            'tanggal' => $hctsBatch->submission->tanggal_penyerahan,
                            'nomor_ba' => $hctsBatch->submission->nomor_ba,
                            'pemasok' => $hctsBatch->submission->pemasok1 . ($hctsBatch->submission->pemasok2 ? ', ' . $hctsBatch->submission->pemasok2 : ''),
                        ]);
                    }
                }
            }
        }

        // Dropdown selection data
        // For simplicity we can hardcode some logical years or pull distinct from Db
        $tahunAnggaranList = \App\Models\HcsReceiving::select('tahun_anggaran')->distinct()->orderBy('tahun_anggaran', 'desc')->pluck('tahun_anggaran');
        $tahunEmisiList = \App\Models\HcsReceiving::select('emisi')->distinct()->orderBy('emisi', 'desc')->pluck('emisi');
        $pecahanList = ['S', 'T', 'U', 'V', 'W', 'X', 'Y'];

        return view('tracking.index', compact(
            'hasSearched', 
            'pack', 
            'submissionStatus', 
            'submissionData',
            'tahunAnggaranList',
            'tahunEmisiList',
            'pecahanList'
        ));
    }
}
