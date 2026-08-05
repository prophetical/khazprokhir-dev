<?php

namespace App\Http\Controllers;

use App\Models\DetailPengemasan;
use App\Models\Pack;
use App\Models\Pengemasan;
use App\Services\PengemasanService;
use App\Traits\SanitizesCsv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PengemasanController extends Controller
{
    use SanitizesCsv;

    protected $service;

    public function __construct(PengemasanService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $readyGroups = $this->service->getReadyToPackageGroupsQuery($request->all())
            ->simplePaginate(15)
            ->through(fn($item) => (array)$item)
            ->withQueryString();

        $missingGaps = $this->service->detectMissingDusGaps();

        return view('pengemasan.index', compact('readyGroups', 'missingGaps'));
    }

    public function data(Request $request)
    {
        $query = Pengemasan::with(['user', 'packs']);

        if ($request->filled('pecahan'))
            $query->where('pecahan', $request->pecahan);
        if ($request->filled('tanggal_awal'))
            $query->where('tanggal_pengemasan', '>=', $request->tanggal_awal);
        if ($request->filled('tanggal_akhir'))
            $query->where('tanggal_pengemasan', '<=', $request->tanggal_akhir);
        if ($request->filled('gilir'))
            $query->where('gilir', $request->gilir);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                return $q->where('pecahan', 'ilike', "%{$search}%")
                    ->orWhere('batch', 'ilike', "%{$search}%")
                    ->orWhere('seri', 'ilike', "%{$search}%")
                    ->orWhereHas('user', fn($u) => $u->where('name', 'ilike', "%{$search}%"));
            });
        }

        if ($request->filled('search_dus') && is_numeric($request->search_dus)) {
            $searchDus = (int) $request->search_dus;
            $query->where('dus_awal', '<=', $searchDus)->where('dus_akhir', '>=', $searchDus);
        }

        $sortColumn = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        if ($sortColumn === 'petugas') {
            $query->join('users', 'pengemasans.created_by', '=', 'users.id')->orderBy('users.name', $sortDirection)->select('pengemasans.*');
        } else {
            $query->orderBy($sortColumn, $sortDirection);
        }

        $pengemasans = $query->simplePaginate(20)->withQueryString();
        $missingGaps = $this->service->detectMissingDusGaps();

        return view('pengemasan.data', compact('pengemasans', 'sortColumn', 'sortDirection', 'missingGaps'));
    }

    public function export(Request $request)
    {
        $query = Pengemasan::with(['user', 'packs']);
        if ($request->filled('pecahan'))
            $query->where('pecahan', $request->pecahan);
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                return $q->where('pecahan', 'ilike', "%{$search}%")->orWhere('batch', 'ilike', "%{$search}%")->orWhere('seri', 'ilike', "%{$search}%")->orWhereHas('user', fn($u) => $u->where('name', 'ilike', "%{$search}%"));
            });
        }
        if ($request->filled('search_dus') && is_numeric($request->search_dus)) {
            $searchDus = (int) $request->search_dus;
            $query->where('dus_awal', '<=', $searchDus)->where('dus_akhir', '>=', $searchDus);
        }

        $sortColumn = $request->input('sort', 'tanggal_pengemasan');
        $sortDirection = $request->input('direction', 'desc');
        if ($sortColumn === 'petugas') {
            $query->join('users', 'pengemasans.created_by', '=', 'users.id')->orderBy('users.name', $sortDirection)->select('pengemasans.*');
        } else {
            $query->orderBy($sortColumn, $sortDirection);
        }

        $filename = 'data_pengemasan_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = ['Content-type' => 'text/csv', 'Content-Disposition' => "attachment; filename=$filename", 'Pragma' => 'no-cache', 'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0', 'Expires' => '0'];

        $callback = function () use ($query) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Tanggal', 'Gilir', 'Thn Anggaran', 'Thn Emisi', 'Pecahan', 'Batch', 'Seri', 'Pack Awal', 'Pack Akhir', 'Jml Pack', 'Total Bilyet', 'Dus', 'Dus Awal', 'Dus Akhir', 'Petugas']);
            $query->chunk(100, function ($pengemasans) use ($file) {
                foreach ($pengemasans as $row) {
                    fputcsv($file, array_map([$this, 'sanitizeCsvField'], [
                        $row->tanggal_pengemasan->format('Y-m-d'),
                        $row->gilir,
                        $row->tahun_anggaran,
                        $row->tahun_emisi,
                        $row->pecahan,
                        $row->batch,
                        $row->seri,
                        $row->pack_awal,
                        $row->pack_akhir,
                        $row->jumlah_pack,
                        $row->total_bilyet,
                        $row->jumlah_dus,
                        $row->dus_awal,
                        $row->dus_akhir,
                        $row->user->name ?? '-',
                    ]));
                }
            });
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function print(Request $request)
    {
        $query = Pengemasan::with(['user', 'packs']);
        if ($request->filled('pecahan'))
            $query->where('pecahan', $request->pecahan);
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                return $q->where('pecahan', 'ilike', "%{$search}%")->orWhere('batch', 'ilike', "%{$search}%")->orWhere('seri', 'ilike', "%{$search}%")->orWhereHas('user', fn($u) => $u->where('name', 'ilike', "%{$search}%"));
            });
        }
        if ($request->filled('search_dus') && is_numeric($request->search_dus)) {
            $searchDus = (int) $request->search_dus;
            $query->where('dus_awal', '<=', $searchDus)->where('dus_akhir', '>=', $searchDus);
        }

        $sortColumn = $request->input('sort', 'tanggal_pengemasan');
        $sortDirection = $request->input('direction', 'desc');
        if ($sortColumn === 'petugas') {
            $query->join('users', 'pengemasans.created_by', '=', 'users.id')->orderBy('users.name', $sortDirection)->select('pengemasans.*');
        } else {
            $query->orderBy($sortColumn, $sortDirection);
        }

        $pengemasans = $query->limit(1000)->get();
        return view('pengemasan.print', compact('pengemasans'));
    }

    public function create(Request $request)
    {
        $lastDus = DetailPengemasan::whereHas('pengemasan', function ($q) use ($request) {
            if ($request->filled('pecahan'))
                $q->where('pecahan', $request->pecahan);
            if ($request->filled('tahun_anggaran'))
                $q->where('tahun_anggaran', $request->tahun_anggaran);
            if ($request->filled('tahun_emisi'))
                $q->where('tahun_emisi', $request->tahun_emisi);
        })->orderBy('no_dus', 'desc')->first();

        $packsData = Pack::where('batch', $request->batch)->where('seri', $request->seri)->whereNotNull('hcs_sorting_id')->whereNull('id_pengemasan')->get(['pack_number', 'jumlah']);

        return view('pengemasan.create', [
            'auto_fill' => $request->all(),
            'last_number' => $lastDus ? $lastDus->no_dus : 0,
            'packsData' => $packsData,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_pengemasan' => 'required|date',
            'gilir' => 'required',
            'tahun_anggaran' => 'required',
            'tahun_emisi' => 'required|digits:4',
            'pecahan' => 'required',
            'batch' => 'required',
            'seri' => 'required',
            'selected_chunks' => 'required_without:selected_packs|array',
            'selected_packs' => 'required_without:selected_chunks|array',
            'is_manual' => 'nullable|boolean',
            'is_manual_sisa' => 'nullable|boolean',
            'dus_awal' => $request->boolean('is_manual') ? 'required|numeric|min:1' : 'nullable',
            'dus_akhir' => $request->boolean('is_manual') ? 'required|numeric|gte:dus_awal' : 'nullable',
            'manual_details' => $request->boolean('is_manual_sisa') ? 'required|array' : 'nullable',
        ]);

        try {
            $this->service->processStore($validated, auth()->id());
            return redirect()->route('pengemasan.index')->with('success', 'Data pengemasan berhasil diproses.');
        } catch (\Exception $e) {
            \Log::error('Pengemasan Store Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem saat memproses data. Silakan coba lagi.')->withInput();
        }
    }

    public function show($id)
    {
        $pengemasan = Pengemasan::with(['detailPengemasans', 'user'])->findOrFail($id);
        return view('pengemasan.show', compact('pengemasan'));
    }

    public function destroy($id)
    {
        try {
            $pengemasan = Pengemasan::findOrFail($id);
            $this->service->processDestroy($pengemasan);
            return redirect()->route('pengemasan.data')->with('success', 'Data pengemasan berhasil dihapus.');
        } catch (\Exception $e) {
            \Log::error('Pengemasan Destroy Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem saat menghapus data. Silakan coba lagi.');
        }
    }

    public function getReadyToPackNotifications()
    {
        try {
            // Cache for 60 seconds to prevent DDoS-like behavior on artisan serve
            $readyGroups = Cache::remember('hcs_ready_notifications', 60, function () {
                return $this->service->getReadyToPackageGroups([]);
            });

            return response()->json([
                'status' => 'success',
                'count' => count($readyGroups), // and collection vs array handling
                'data' => $readyGroups
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
