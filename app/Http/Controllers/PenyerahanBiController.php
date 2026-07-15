<?php

namespace App\Http\Controllers;

use App\Models\PenyerahanBi;
use App\Services\PenyerahanBiService;
use App\Traits\SanitizesCsv;
use Illuminate\Http\Request;

class PenyerahanBiController extends Controller
{
    use SanitizesCsv;

    protected $service;

    public function __construct(PenyerahanBiService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $options = $this->service->getAvailableFilterOptions();
        $query = PenyerahanBi::with('user');
        $this->applyFilters($query, $request);

        $sortCol = $request->input('sort', 'tanggal_penyerahan');
        $sortDir = $request->input('direction', 'desc');
        $allowed = ['tanggal_penyerahan', 'nomor_ba', 'pecahan', 'tahun_emisi', 'tahun_anggaran', 'nomor_dus_awal', 'jumlah_dus', 'jumlah_bilyet', 'status_data'];
        $sortCol = in_array($sortCol, $allowed) ? $sortCol : 'tanggal_penyerahan';

        $penyerahans = $query->orderBy($sortCol, $sortDir)->simplePaginate(20)->withQueryString();
        $missingWarnings = $this->service->getIncompletePenyerahanWarnings();

        return view('penyerahan-bi.index', [
            'penyerahans' => $penyerahans,
            'missingWarnings' => $missingWarnings,
            'availableYears' => $options['years'],
            'availableEmissions' => $options['emissions'],
        ]);
    }

    public function create()
    {
        $options = $this->service->getAvailableFilterOptions();
        return view('penyerahan-bi.create', [
            'missingWarnings' => $this->service->getIncompletePenyerahanWarnings(),
            'availableYears' => $options['years'],
            'availableEmissions' => $options['emissions'],
        ]);
    }

    public function edit($id)
    {
        return view('penyerahan-bi.edit', [
            'penyerahan' => PenyerahanBi::findOrFail($id),
            'missingWarnings' => $this->service->getIncompletePenyerahanWarnings(),
            'availableYears' => $this->service->getAvailableFilterOptions()['years'],
            'availableEmissions' => $this->service->getAvailableFilterOptions()['emissions'],
        ]);
    }

    public function checkDuplicate(Request $request)
    {
        $overlap = $this->service->checkOverlap($request->all(), $request->exclude_id);
        if ($overlap) {
            return response()->json([
                'duplicate' => true,
                'nomor_ba' => $overlap->nomor_ba,
                'range_tersimpan' => $overlap->nomor_dus_awal.' – '.$overlap->nomor_dus_akhir,
                'tanggal' => $overlap->tanggal_penyerahan->format('d/m/Y'),
            ]);
        }
        return response()->json(['duplicate' => false]);
    }

    public function getLastDus(Request $request)
    {
        $validated = $request->validate([
            'pecahan' => 'required|string|max:10',
            'tahun_anggaran' => 'required|string|max:10',
            'tahun_emisi' => 'required|string|max:10',
        ]);

        $last = PenyerahanBi::where('pecahan', $validated['pecahan'])
            ->where('tahun_anggaran', $validated['tahun_anggaran'])
            ->where('tahun_emisi', $validated['tahun_emisi'])
            ->max('nomor_dus_akhir');

        return response()->json(['last_dus_akhir' => (int) ($last ?? 0)]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal_penyerahan' => 'required|date',
            'nomor_ba' => 'required|string|max:255',
            'pecahan' => 'required|string|max:10',
            'tahun_emisi' => 'required|digits:4',
            'tahun_anggaran' => 'required|string|max:10',
            'nomor_dus_awal' => 'required|integer|min:1',
            'nomor_dus_akhir' => 'required|integer|gte:nomor_dus_awal',
            'jumlah_bilyet' => 'required|integer|min:1',
        ]);

        if ($this->service->checkOverlap($data)) {
            return back()->withInput()->withErrors(['nomor_dus_awal' => 'Range dus sudah tercatat dalam penyerahan lain.']);
        }

        $this->service->processUpsert($data, null, auth()->id());
        return redirect()->route('penyerahan-bi.index')->with('success', 'Data penyerahan berhasil disimpan.');
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'tanggal_penyerahan' => 'required|date',
            'nomor_ba' => 'required|string|max:255',
            'pecahan' => 'required|string|max:10',
            'tahun_emisi' => 'required|digits:4',
            'tahun_anggaran' => 'required|string|max:10',
            'nomor_dus_awal' => 'required|integer|min:1',
            'nomor_dus_akhir' => 'required|integer|gte:nomor_dus_awal',
            'jumlah_bilyet' => 'required|integer|min:1',
        ]);

        if ($this->service->checkOverlap($data, $id)) {
            return back()->withInput()->withErrors(['nomor_dus_awal' => 'Range dus sudah tercatat dalam penyerahan lain.']);
        }

        $this->service->processUpsert($data, $id, auth()->id());
        return redirect()->route('penyerahan-bi.index')->with('success', 'Data penyerahan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        PenyerahanBi::findOrFail($id)->delete();
        return redirect()->route('penyerahan-bi.index')->with('success', 'Data penyerahan berhasil dihapus.');
    }

    public function export(Request $request)
    {
        $query = PenyerahanBi::with('user');
        $this->applyFilters($query, $request);
        $rows = $query->orderBy('tanggal_penyerahan', 'desc')->get();

        $filename = 'laporan_penyerahan_bi_'.now()->format('Ymd_His').'.csv';
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=\"{$filename}\""];

        $callback = function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Tanggal', 'Nomor BA', 'Pecahan', 'TA', 'TE', 'No Dus Awal', 'No Dus Akhir', 'Jml Dus', 'Jml Bilyet', 'Status', 'Petugas']);
            foreach ($rows as $r) {
                fputcsv($handle, array_map([$this, 'sanitizeCsvField'], [
                    $r->tanggal_penyerahan->format('d/m/Y'), $r->nomor_ba, $r->pecahan, $r->tahun_anggaran, $r->tahun_emisi, $r->nomor_dus_awal, $r->nomor_dus_akhir, $r->jumlah_dus, $r->jumlah_bilyet, $r->status_data, $r->user->name ?? '-'
                ]));
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function print(Request $request)
    {
        $query = PenyerahanBi::with('user');
        $this->applyFilters($query, $request);
        $penyerahans = $query->orderBy('tanggal_penyerahan', 'desc')->get();
        return view('penyerahan-bi.print', compact('penyerahans'));
    }

    protected function applyFilters($query, Request $request)
    {
        if ($request->filled('pecahan')) $query->where('pecahan', $request->pecahan);
        if ($request->filled('tahun_anggaran')) $query->where('tahun_anggaran', $request->tahun_anggaran);
        if ($request->filled('tahun_emisi')) $query->where('tahun_emisi', $request->tahun_emisi);
        if ($request->filled('nomor_ba')) $query->where('nomor_ba', 'like', '%'.$request->nomor_ba.'%');
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                return $q->where('nomor_ba', 'like', "%$s%")->orWhere('pecahan', 'like', "%$s%")->orWhere('tahun_anggaran', 'like', "%$s%");
            });
        }
        if ($request->filled('tanggal_awal')) $query->where('tanggal_penyerahan', '>=', $request->tanggal_awal);
        if ($request->filled('tanggal_akhir')) $query->where('tanggal_penyerahan', '<=', $request->tanggal_akhir);
    }
}
