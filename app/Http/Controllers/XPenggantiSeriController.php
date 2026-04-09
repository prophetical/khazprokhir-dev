<?php

namespace App\Http\Controllers;

use App\Models\XPenggantiSeri;
use Illuminate\Http\Request;

class XPenggantiSeriController extends Controller
{
    private const PECAHAN_OPTIONS = ['S', 'T', 'U', 'V', 'W', 'X', 'Y'];

    public function index(Request $request)
    {
        $query = XPenggantiSeri::with('user')->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('batch', 'like', "%{$s}%")
                    ->orWhere('seri', 'like', "%{$s}%")
                    ->orWhere('pecahan', 'like', "%{$s}%");
            });
        }
        if ($request->filled('pecahan')) {
            $query->where('pecahan', $request->pecahan);
        }

        return view('x-pengganti.seri.index', [
            'seris' => $query->paginate(15)->withQueryString(),
            'search' => $request->search,
            'pecahan' => $request->pecahan,
            'pecahanOptions' => self::PECAHAN_OPTIONS,
        ]);
    }

    public function create()
    {
        return view('x-pengganti.seri.create', [
            'pecahanOptions' => self::PECAHAN_OPTIONS,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pecahan' => 'required|in:S,T,U,V,W,X,Y',
            'seri' => 'required|string|max:255',
            'batch' => ['required', 'digits:7'],
            'tahun_anggaran' => 'required|digits:4|integer|min:2000|max:2099',
            'tahun_emisi' => 'required|digits:4|integer|min:2000|max:2099',
        ], [
            'batch.digits' => 'Batch harus tepat 7 digit angka.',
        ]);

        $validated['created_by'] = auth()->id();

        XPenggantiSeri::create($validated);

        return redirect()->route('x-pengganti.seri.index')
            ->with('success', 'Data seri berhasil disimpan.');
    }

    public function destroy(XPenggantiSeri $seri)
    {
        try {
            // Cascade delete via FK (packs & details) handled by DB
            $seri->delete();
            return redirect()->route('x-pengganti.seri.index')
                ->with('success', 'Data seri dan seluruh data X Pengganti semua seksi berhasil dihapus.');
        } catch (\Exception $e) {
            \Log::error('XPenggantiSeri Destroy Error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Gagal menghapus data. Silakan coba lagi.']);
        }
    }
}
