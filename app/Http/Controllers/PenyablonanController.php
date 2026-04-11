<?php

namespace App\Http\Controllers;

use App\Models\PenyablonanPenerimaan;
use App\Models\PenyablonanDus;
use App\Models\PenyablonanKerusakan;
use App\Services\PenyablonanService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PenyablonanController extends Controller
{
    protected $service;

    public function __construct(PenyablonanService $service)
    {
        $this->service = $service;
    }

    /**
     * Helper to check for full access (CRUD).
     */
    private function checkFullAccess()
    {
        if (!in_array(auth()->user()->role, ['admin', 'kemas', 'sortir'])) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk melakukan operasi ini.');
        }
    }

    // --- Sub-menu 1: Penerimaan Blanko ---
    public function penerimaan(Request $request)
    {
        $data = PenyablonanPenerimaan::with('user')->orderBy('tanggal', 'desc')->simplePaginate(20);
        return view('penyablonan.penerimaan', compact('data'));
    }

    public function storePenerimaan(Request $request)
    {
        $this->checkFullAccess();

        $request->validate([
            'tanggal' => 'required|date',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        PenyablonanPenerimaan::create([
            'tanggal' => $request->tanggal,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
            'created_by' => auth()->id(),
        ]);

        $this->service->updateStok($request->jumlah, 'tambah');

        return redirect()->back()->with('success', 'Penerimaan blanko berhasil dicatat.');
    }

    // --- Sub-menu 2: Penyablonan Dus ---
    public function dus(Request $request)
    {
        $data = PenyablonanDus::with('user')->orderBy('tanggal', 'desc')->simplePaginate(20);
        return view('penyablonan.dus', compact('data'));
    }

    public function storeDus(Request $request)
    {
        $this->checkFullAccess();

        $request->validate([
            'tanggal' => 'required|date',
            'gilir' => 'required|in:Gilir 1,Gilir 2,Gilir 3',
            'pecahan' => 'required|in:S,T,U,V,W,X,Y',
            'te' => 'required|integer',
            'ta' => 'required|integer',
            'no_awal' => 'required|integer|min:1',
            'no_akhir' => 'required|integer|gte:no_awal',
        ]);

        if ($this->service->checkDuplicateRange($request->pecahan, $request->ta, $request->te, $request->no_awal, $request->no_akhir)) {
            return redirect()->back()->withInput()->with('error', 'Peringatan: Nomor dus tersebut telah tersablon sebelumnya.');
        }

        $jumlah = $this->service->calculateJumlah($request->no_awal, $request->no_akhir);

        PenyablonanDus::create([
            'tanggal' => $request->tanggal,
            'gilir' => $request->gilir,
            'pecahan' => $request->pecahan,
            'te' => $request->te,
            'ta' => $request->ta,
            'no_awal' => $request->no_awal,
            'no_akhir' => $request->no_akhir,
            'jumlah' => $jumlah,
            'created_by' => auth()->id(),
        ]);

        $result = $this->service->updateStok($jumlah, 'kurangi');

        $msg = 'Aktivitas penyablonan berhasil dicatat.';
        if ($result['warning']) {
            $msg .= ' PERINGATAN: Stok blanko menipis (Sisa: ' . $result['stok'] . ')';
        }

        return redirect()->back()->with('success', $msg);
    }

    // --- Sub-menu 3: Kerusakan Blanko ---
    public function kerusakan(Request $request)
    {
        $data = PenyablonanKerusakan::with('user')->orderBy('tanggal', 'desc')->simplePaginate(20);
        return view('penyablonan.kerusakan', compact('data'));
    }

    public function storeKerusakan(Request $request)
    {
        $this->checkFullAccess();

        $request->validate([
            'tanggal' => 'required|date',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'required|string',
        ]);

        PenyablonanKerusakan::create([
            'tanggal' => $request->tanggal,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
            'created_by' => auth()->id(),
        ]);

        $this->service->updateStok($request->jumlah, 'kurangi');

        return redirect()->back()->with('success', 'Data kerusakan blanko berhasil dicatat.');
    }

    // --- Sub-menu 4: Laporan Penyablonan ---
    public function laporan(Request $request)
    {
        $tanggal = $request->input('tanggal');
        $gilir = $request->input('gilir');

        $query = PenyablonanDus::with('user');

        if ($tanggal) {
            $query->whereDate('tanggal', $tanggal);
        }

        if ($gilir) {
            $query->where('gilir', $gilir);
        }

        $data = $query->orderBy('tanggal', 'desc')->simplePaginate(20);
        $sisaStok = $this->service->getCurrentStok();

        return view('penyablonan.laporan', compact('data', 'sisaStok'));
    }

    public function print(Request $request)
    {
        $tanggal = $request->input('tanggal');
        $gilir = $request->input('gilir');

        $query = PenyablonanDus::with('user');

        if ($tanggal) {
            $query->whereDate('tanggal', $tanggal);
        }

        if ($gilir) {
            $query->where('gilir', $gilir);
        }

        $data = $query->orderBy('tanggal', 'desc')->get(); // Get all formatted for print, no pagination
        $sisaStok = $this->service->getCurrentStok();

        return view('penyablonan.print', compact('data', 'sisaStok', 'tanggal', 'gilir'));
    }
}
