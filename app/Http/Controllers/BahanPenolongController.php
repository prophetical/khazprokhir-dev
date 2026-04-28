<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\BahanPenolong;
use App\Models\BahanPenolongTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BahanPenolongController extends Controller
{
    /**
     * Menu: Persediaan (Monitoring Stok)
     */
    public function persediaan(Request $request)
    {
        $query = BahanPenolong::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_bahan', 'like', "%{$search}%")
                  ->orWhere('kode_material', 'like', "%{$search}%");
            });
        }

        if ($request->filter === 'low') {
            $query->whereColumn('stok', '<=', 'min_stok');
        }

        // Hitung stok rendah (global, tidak peduli filter pencarian)
        $low_stock_count = BahanPenolong::whereColumn('stok', '<=', 'min_stok')->count();

        $materials = $query->orderBy('nama_bahan')->simplePaginate(20)->withQueryString();
        
        return view('bahan-penolong.persediaan', compact('materials', 'low_stock_count'));
    }

    /**
     * Export Inventory Master CSV (Filter-aware)
     */
    public function inventoryExport(Request $request)
    {
        $query = BahanPenolong::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_bahan', 'like', "%{$search}%")
                  ->orWhere('kode_material', 'like', "%{$search}%");
            });
        }

        $filename = 'stok_bahan_penolong_'.date('YmdHis').'.csv';
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
        ];

        $callback = function() use ($query) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Nama Bahan', 'Kode material', 'Stok Saat Ini', 'Min. Stok', 'Satuan', 'Keterangan']);

            $query->orderBy('nama_bahan')->chunk(100, function($materials) use ($file) {
                foreach ($materials as $m) {
                    fputcsv($file, [
                        $m->nama_bahan,
                        $m->kode_material ?? '-',
                        $m->stok,
                        $m->min_stok,
                        $m->satuan,
                        $m->keterangan ?? '-'
                    ]);
                }
            });
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Print/PDF Inventory Master (Filter-aware)
     */
    public function inventoryPrint(Request $request)
    {
        $query = BahanPenolong::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_bahan', 'like', "%{$search}%")
                  ->orWhere('kode_material', 'like', "%{$search}%");
            });
        }

        $materials = $query->orderBy('nama_bahan')->get();
        return view('bahan-penolong.inventory_print', compact('materials'));
    }

    /**
     * Menu: Penerimaan (Barang Masuk)
     */
    public function penerimaan(Request $request)
    {
        $query = BahanPenolongTransaction::where('tipe', 'masuk')->with(['bahanPenolong', 'user']);

        if ($request->filled('tanggal_awal')) $query->whereDate('created_at', '>=', $request->tanggal_awal);
        if ($request->filled('tanggal_akhir')) $query->whereDate('created_at', '<=', $request->tanggal_akhir);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('bahanPenolong', function($q) use ($search) {
                $q->where('nama_bahan', 'like', "%{$search}%")
                  ->orWhere('kode_material', 'like', "%{$search}%");
            });
        }
 
        $transactions = $query->latest()->simplePaginate(20)->withQueryString();
        $materials = BahanPenolong::orderBy('nama_bahan')->get();
 
        return view('bahan-penolong.penerimaan', compact('transactions', 'materials'));
    }

    /**
     * Menu: Pemakaian (Barang Keluar / Mutasi / Rusak)
     */
    public function pemakaian(Request $request)
    {
        $query = BahanPenolongTransaction::where('tipe', 'keluar')->with(['bahanPenolong', 'user']);

        if ($request->filled('tanggal_awal')) $query->whereDate('created_at', '>=', $request->tanggal_awal);
        if ($request->filled('tanggal_akhir')) $query->whereDate('created_at', '<=', $request->tanggal_akhir);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('bahanPenolong', function($q) use ($search) {
                $q->where('nama_bahan', 'like', "%{$search}%")
                  ->orWhere('kode_material', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori')) $query->where('kategori', $request->kategori);

        $transactions = $query->latest()->simplePaginate(20)->withQueryString();
        $materials = BahanPenolong::where('stok', '>', 0)->orderBy('nama_bahan')->get();

        return view('bahan-penolong.pemakaian', compact('transactions', 'materials'));
    }

    /**
     * Master Data: Create Material
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_bahan' => 'required|string|max:255',
            'kode_material' => 'nullable|string|max:100',
            'satuan' => 'required|string|max:50',
            'min_stok' => 'required|integer|min:0',
            'keterangan' => 'nullable|string'
        ]);

        BahanPenolong::create($request->only(['nama_bahan', 'kode_material', 'satuan', 'min_stok', 'keterangan']));

        return back()->with('success', 'Bahan Penolong berhasil ditambahkan.');
    }

    /**
     * Master Data: Update Material
     */
    public function update(Request $request, BahanPenolong $bahanPenolong)
    {
        $request->validate([
            'nama_bahan' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'min_stok' => 'required|integer|min:0',
        ]);

        $bahanPenolong->update($request->only(['nama_bahan', 'kode_material', 'satuan', 'min_stok', 'keterangan']));

        return back()->with('success', 'Data bahan berhasil diperbarui.');
    }
    /**
     * Master Data: Delete Material
     */
    public function destroy(BahanPenolong $bahanPenolong)
    {
        $bahanPenolong->delete();
        return back()->with('success', 'Bahan Penolong berhasil dihapus.');
    }

    /**
     * Logic: Proses Transaksi Masuk / Keluar
     */
    public function storeTransaction(Request $request)
    {
        $request->validate([
            'bahan_penolong_id' => 'required|exists:bahan_penolongs,id',
            'tipe' => 'required|in:masuk,keluar',
            'kategori' => 'required|in:penerimaan,pemakaian,mutasi,rusak',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255'
        ]);

        return DB::transaction(function () use ($request) {
            $material = BahanPenolong::lockForUpdate()->find($request->bahan_penolong_id);

            if ($request->tipe === 'keluar' && $material->stok < $request->jumlah) {
                return back()->with('error', 'Stok tidak mencukupi untuk pemakaian ini.');
            }

            // Hitung stok baru
            $stokBaru = ($request->tipe === 'masuk') 
                ? $material->stok + $request->jumlah 
                : $material->stok - $request->jumlah;

            // Catat Transaksi
            BahanPenolongTransaction::create([
                'bahan_penolong_id' => $material->id,
                'tipe' => $request->tipe,
                'kategori' => $request->kategori,
                'jumlah' => $request->jumlah,
                'stok_akhir' => $stokBaru,
                'keterangan' => $request->keterangan,
                'created_by' => Auth::id(),
            ]);

            // Update Stok Master
            $material->update(['stok' => $stokBaru]);

            return back()->with('success', 'Transaksi stok berhasil dicatat.');
        });
    }

    public function updateTransaction(Request $request, BahanPenolongTransaction $transaction)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
            'kategori' => 'required|in:penerimaan,pemakaian,mutasi,rusak',
            'keterangan' => 'nullable|string|max:255'
        ]);

        return DB::transaction(function () use ($request, $transaction) {
            $material = BahanPenolong::lockForUpdate()->find($transaction->bahan_penolong_id);
            $delta = $request->jumlah - $transaction->jumlah;

            // Hitung adjustmen stok
            if ($transaction->tipe === 'masuk') {
                $stokBaru = $material->stok + $delta;
            } else {
                $stokBaru = $material->stok - $delta;
            }

            if ($stokBaru < 0) {
                return back()->with('error', 'Update gagal: Stok material ' . $material->nama_bahan . ' tidak mencukupi jika perubahan ini dilakukan.');
            }

            // Update stok master
            $material->update(['stok' => $stokBaru]);

            // Update transaksi
            $transaction->update([
                'jumlah' => $request->jumlah,
                'kategori' => $request->kategori,
                'keterangan' => $request->keterangan,
                'stok_akhir' => $transaction->tipe === 'masuk' 
                    ? $transaction->stok_akhir + $delta 
                    : $transaction->stok_akhir - $delta
            ]);

            return back()->with('success', 'Transaksi berhasil diperbarui dan stok telah disesuaikan.');
        });
    }

    public function destroyTransaction(BahanPenolongTransaction $transaction)
    {
        return DB::transaction(function () use ($transaction) {
            $material = BahanPenolong::lockForUpdate()->find($transaction->bahan_penolong_id);

            // Revert stok
            if ($transaction->tipe === 'masuk') {
                $stokBaru = $material->stok - $transaction->jumlah;
            } else {
                $stokBaru = $material->stok + $transaction->jumlah;
            }

            if ($stokBaru < 0) {
                return back()->with('error', 'Hapus gagal: Menghapus penerimaan ini akan mengakibatkan stok ' . $material->nama_bahan . ' menjadi negatif.');
            }

            // Update stok master
            $material->update(['stok' => $stokBaru]);

            // Hapus transaksi
            $transaction->delete();

            return back()->with('success', 'Transaksi berhasil dihapus dan stok telah dikembalikan.');
        });
    }

    /**
     * Export Laporan CSV
     */
    public function export(Request $request)
    {
        $query = BahanPenolongTransaction::with(['bahanPenolong', 'user']);
        
        if ($request->filled('tanggal_awal')) $query->whereDate('created_at', '>=', $request->tanggal_awal);
        if ($request->filled('tanggal_akhir')) $query->whereDate('created_at', '<=', $request->tanggal_akhir);
        if ($request->filled('tipe')) $query->where('tipe', $request->tipe);
        if ($request->filled('kategori')) $query->where('kategori', $request->kategori);
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('bahanPenolong', function($q) use ($search) {
                $q->where('nama_bahan', 'like', "%{$search}%")
                  ->orWhere('kode_material', 'like', "%{$search}%");
            });
        }

        $filename = 'laporan_bahan_penolong_'.date('YmdHis').'.csv';
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($query) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Tanggal', 'Nama Bahan', 'Kode material', 'Tipe', 'Kategori', 'Jumlah', 'Satuan', 'Stok Akhir', 'Keterangan', 'Petugas']);

            $query->chunk(100, function($transactions) use ($file) {
                foreach ($transactions as $t) {
                    fputcsv($file, [
                        $t->created_at->format('Y-m-d H:i'),
                        $t->bahanPenolong->nama_bahan,
                        $t->bahanPenolong->kode_material ?? '-',
                        ucfirst($t->tipe),
                        ucfirst($t->kategori),
                        $t->jumlah,
                        $t->bahanPenolong->satuan,
                        $t->stok_akhir,
                        $t->keterangan ?? '-',
                        $t->user->name ?? '-'
                    ]);
                }
            });
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Print View / PDF
     */
    public function print(Request $request)
    {
        $query = BahanPenolongTransaction::with(['bahanPenolong', 'user']);
        if ($request->filled('tanggal_awal')) $query->whereDate('created_at', '>=', $request->tanggal_awal);
        if ($request->filled('tanggal_akhir')) $query->whereDate('created_at', '<=', $request->tanggal_akhir);
        if ($request->filled('tipe')) $query->where('tipe', $request->tipe);
        if ($request->filled('kategori')) $query->where('kategori', $request->kategori);
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('bahanPenolong', function($q) use ($search) {
                $q->where('nama_bahan', 'like', "%{$search}%")
                  ->orWhere('kode_material', 'like', "%{$search}%");
            });
        }
        
        $transactions = $query->latest()->get();
        return view('bahan-penolong.print', compact('transactions'));
    }
}
