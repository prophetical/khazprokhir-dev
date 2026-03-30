<?php

namespace App\Http\Controllers;

use App\Services\HcsSortingService;
use Illuminate\Http\Request;

class HcsSortingController extends Controller
{
    protected $service;

    public function __construct(HcsSortingService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $options = $this->service->getAvailableFilterOptions();
        $AvailableGroups = $this->service->getAvailableGroups($request->all(), 20);
        $summary = $this->service->getSummaries($request->all());

        return view('hcs-sorting.index', [
            'AvailableGroups' => $AvailableGroups,
            'summaries' => $summary['summaries'],
            'totalAllPacks' => $summary['total'],
            'availableYears' => $options['years'],
            'availableEmissions' => $options['emissions'],
        ]);
    }

    public function create(Request $request)
    {
        $pecahan = $request->query('pecahan');
        $batch = $request->query('batch');
        $seri = $request->query('seri');

        if (!$pecahan || !$batch || !$seri) {
            return redirect()->route('hcs-sorting.index')->with('error', 'Silahkan pilih grup data terlebih dahulu.');
        }

        $packsData = $this->service->getPacksData($pecahan, $batch, $seri);
        if ($packsData->isEmpty()) {
            return redirect()->route('hcs-sorting.index')->with('error', 'Data pack tidak ditemukan.');
        }

        $emisi = $packsData->first()->emisi;
        $tahun_anggaran = $packsData->first()->tahun_anggaran;

        return view('hcs-sorting.create', compact('pecahan', 'batch', 'seri', 'emisi', 'tahun_anggaran', 'packsData'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pecahan' => 'required',
            'batch' => 'required',
            'seri' => 'required',
            'emisi' => 'required',
            'tahun_anggaran' => 'required',
            'supplier' => 'required|in:Rikyet,Cutpack',
            'petugas_1' => 'required',
            'petugas_2' => 'nullable',
            'tanggal' => 'required|date',
            'gilir' => 'required',
            'selected_packs' => 'required|array|min:' . ($request->has('is_manual') ? '1' : '4'),
        ]);

        if ($request->has('is_manual')) $validated['is_manual'] = true;

        try {
            $this->service->processStore($validated, auth()->id());
            return redirect()->route('hcs-sorting.index')->with('success', 'Data penyortiran berhasil disimpan.');
        } catch (\Exception $e) {
            \Log::error('HCS Sorting Store Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem saat menyimpan data penyortiran. Silakan coba lagi.')->withInput();
        }
    }
}
