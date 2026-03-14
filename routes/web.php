<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Batch Tracking
    Route::get('/batch-tracking', [\App\Http\Controllers\BatchTrackingController::class, 'index'])->name('batch-tracking.index');

    // Reports
    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [\App\Http\Controllers\ReportController::class, 'export'])->name('reports.export');
    Route::get('/reports/print', [\App\Http\Controllers\ReportController::class, 'print'])->name('reports.print');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // HCS Receiving Routes (restricted to sortir for creation/editing/deleting)
    Route::middleware('role:sortir')->group(function () {
        Route::get('/hcs-receiving/create', [\App\Http\Controllers\HcsReceivingController::class, 'create'])->name('hcs-receiving.create');
        Route::post('/hcs-receiving', [\App\Http\Controllers\HcsReceivingController::class, 'store'])->name('hcs-receiving.store');
        Route::get('/hcs-receiving/{hcs_receiving}/edit', [\App\Http\Controllers\HcsReceivingController::class, 'edit'])->name('hcs-receiving.edit');
        Route::put('/hcs-receiving/{hcs_receiving}', [\App\Http\Controllers\HcsReceivingController::class, 'update'])->name('hcs-receiving.update');
        Route::delete('/hcs-receiving/{hcs_receiving}', [\App\Http\Controllers\HcsReceivingController::class, 'destroy'])->name('hcs-receiving.destroy');
    }
    );

    // Everyone can view index (with respective policies)
    Route::get('/hcs-receiving', [\App\Http\Controllers\HcsReceivingController::class, 'index'])->name('hcs-receiving.index');

    // Rekomendasi Penerimaan
    Route::get('/rekomendasi-penerimaan', [\App\Http\Controllers\RekomendasiPenerimaanController::class, 'index'])->name('rekomendasi-penerimaan.index');
    Route::get('/rekomendasi-penerimaan/print', [\App\Http\Controllers\RekomendasiPenerimaanController::class, 'print'])->name('rekomendasi-penerimaan.print');
    Route::get('/rekomendasi-penerimaan/export', [\App\Http\Controllers\RekomendasiPenerimaanController::class, 'export'])->name('rekomendasi-penerimaan.export');
    Route::get('/rekomendasi-penerimaan/show', [\App\Http\Controllers\RekomendasiPenerimaanController::class, 'show'])->name('rekomendasi-penerimaan.show');

    // HCS Sorting Routes
    Route::middleware('role:sortir')->group(function () {
        Route::get('/hcs-sorting/create', [\App\Http\Controllers\HcsSortingController::class, 'create'])->name('hcs-sorting.create');
        Route::post('/hcs-sorting', [\App\Http\Controllers\HcsSortingController::class, 'store'])->name('hcs-sorting.store');
    }
    );
    Route::get('/hcs-sorting', [\App\Http\Controllers\HcsSortingController::class, 'index'])->name('hcs-sorting.index');
    Route::get('/rekomendasi-penyortiran', [\App\Http\Controllers\RekomendasiPenyortiranController::class, 'index'])->name('rekomendasi-penyortiran.index');
    Route::get('/hcs-sorting-reports', [\App\Http\Controllers\HcsSortingReportController::class, 'index'])->name('hcs-sorting-reports.index');
    Route::get('/hcs-sorting-reports/export', [\App\Http\Controllers\HcsSortingReportController::class, 'export'])->name('hcs-sorting-reports.export');
    Route::get('/hcs-sorting-reports/print', [\App\Http\Controllers\HcsSortingReportController::class, 'print'])->name('hcs-sorting-reports.print');
    Route::middleware('role:sortir')->group(function () {
        Route::get('/hcs-sorting-reports/{hcs_sorting_report}/edit', [\App\Http\Controllers\HcsSortingReportController::class, 'edit'])->name('hcs-sorting-reports.edit');
        Route::put('/hcs-sorting-reports/{hcs_sorting_report}', [\App\Http\Controllers\HcsSortingReportController::class, 'update'])->name('hcs-sorting-reports.update');
        Route::delete('/hcs-sorting-reports/{hcs_sorting_report}', [\App\Http\Controllers\HcsSortingReportController::class, 'destroy'])->name('hcs-sorting-reports.destroy');
    }
    );

    // API to get used packs (requires auth)
    Route::get('/api/packs/used', [\App\Http\Controllers\PackController::class, 'used'])->name('packs.used');

    // Pengemasan Routes
    Route::middleware('role:sortir')->group(function () {
        Route::get('/pengemasan/create', [\App\Http\Controllers\PengemasanController::class, 'create'])->name('pengemasan.create');
        Route::post('/pengemasan', [\App\Http\Controllers\PengemasanController::class, 'store'])->name('pengemasan.store');
        Route::delete('/pengemasan/{id}', [\App\Http\Controllers\PengemasanController::class, 'destroy'])->name('pengemasan.destroy');
    }
    );
    Route::get('/pengemasan', [\App\Http\Controllers\PengemasanController::class, 'index'])->name('pengemasan.index');
    Route::get('/data-pengemasan', [\App\Http\Controllers\PengemasanController::class, 'data'])->name('pengemasan.data');
    Route::get('/data-pengemasan/export', [\App\Http\Controllers\PengemasanController::class, 'export'])->name('pengemasan.export');
    Route::get('/data-pengemasan/print', [\App\Http\Controllers\PengemasanController::class, 'print'])->name('pengemasan.print');
    Route::get('/pengemasan/{id}', [\App\Http\Controllers\PengemasanController::class, 'show'])->name('pengemasan.show');
    // Penyerahan ke BI Routes
    Route::get('/penyerahan-bi', [\App\Http\Controllers\PenyerahanBiController::class, 'index'])->name('penyerahan-bi.index');
    Route::middleware('role:sortir')->group(function () {
        Route::get('/penyerahan-bi/create', [\App\Http\Controllers\PenyerahanBiController::class, 'create'])->name('penyerahan-bi.create');
        Route::post('/penyerahan-bi', [\App\Http\Controllers\PenyerahanBiController::class, 'store'])->name('penyerahan-bi.store');
        Route::get('/penyerahan-bi/{id}/edit', [\App\Http\Controllers\PenyerahanBiController::class, 'edit'])->name('penyerahan-bi.edit');
        Route::put('/penyerahan-bi/{id}', [\App\Http\Controllers\PenyerahanBiController::class, 'update'])->name('penyerahan-bi.update');
        Route::delete('/penyerahan-bi/{id}', [\App\Http\Controllers\PenyerahanBiController::class, 'destroy'])->name('penyerahan-bi.destroy');
    });
    Route::get('/penyerahan-bi/export', [\App\Http\Controllers\PenyerahanBiController::class, 'export'])->name('penyerahan-bi.export');
    Route::get('/penyerahan-bi/print', [\App\Http\Controllers\PenyerahanBiController::class, 'print'])->name('penyerahan-bi.print');
    Route::get('/api/penyerahan-bi/check-duplicate', [\App\Http\Controllers\PenyerahanBiController::class, 'checkDuplicate'])->name('penyerahan-bi.check-duplicate');

    // Laporan Harian
    Route::get('/laporan-harian', [\App\Http\Controllers\LaporanHarianController::class, 'index'])->name('laporan-harian.index');
    Route::get('/laporan-harian/print', [\App\Http\Controllers\LaporanHarianController::class, 'print'])->name('laporan-harian.print');
    Route::get('/laporan-harian/export', [\App\Http\Controllers\LaporanHarianController::class, 'export'])->name('laporan-harian.export');

    // Manajemen Target (Admin Only)
    Route::middleware('role:admin')->group(function () {
        Route::get('/targets', [\App\Http\Controllers\TargetController::class, 'index'])->name('targets.index');
        Route::get('/targets/create', [\App\Http\Controllers\TargetController::class, 'create'])->name('targets.create');
        Route::post('/targets', [\App\Http\Controllers\TargetController::class, 'store'])->name('targets.store');
        Route::get('/targets/{id}/edit', [\App\Http\Controllers\TargetController::class, 'edit'])->name('targets.edit');
        Route::put('/targets/{id}', [\App\Http\Controllers\TargetController::class, 'update'])->name('targets.update');
        Route::delete('/targets/{id}', [\App\Http\Controllers\TargetController::class, 'destroy'])->name('targets.destroy');
    }
    );
    // HCTS Receiving Routes
    Route::middleware('role:sortir')->group(function () {
        Route::get('/hcts-receiving/create', [\App\Http\Controllers\HctsReceivingController::class, 'create'])->name('hcts-receiving.create');
        Route::post('/hcts-receiving', [\App\Http\Controllers\HctsReceivingController::class, 'store'])->name('hcts-receiving.store');
        Route::get('/hcts-receiving/get-hcs-total', [\App\Http\Controllers\HctsReceivingController::class, 'getHcsTotal'])->name('hcts-receiving.get-hcs-total');
        Route::get('/hcts-receiving/{hcts_receiving}/edit', [\App\Http\Controllers\HctsReceivingController::class, 'edit'])->name('hcts-receiving.edit');
        Route::put('/hcts-receiving/{hcts_receiving}', [\App\Http\Controllers\HctsReceivingController::class, 'update'])->name('hcts-receiving.update');
        Route::delete('/hcts-receiving/{hcts_receiving}', [\App\Http\Controllers\HctsReceivingController::class, 'destroy'])->name('hcts-receiving.destroy');
    });
    Route::get('/hcts-receiving/export', [\App\Http\Controllers\HctsReceivingController::class, 'export'])->name('hcts-receiving.export');
    Route::get('/hcts-receiving/print', [\App\Http\Controllers\HctsReceivingController::class, 'print'])->name('hcts-receiving.print');
    Route::get('/hcts-receiving', [\App\Http\Controllers\HctsReceivingController::class, 'index'])->name('hcts-receiving.index');
    Route::get('/hcts-hcts-summary/export', [\App\Http\Controllers\HctsReceivingController::class, 'summaryExport'])->name('hcts-receiving.summary-export');
    Route::get('/hcts-hcts-summary/print', [\App\Http\Controllers\HctsReceivingController::class, 'summaryPrint'])->name('hcts-receiving.summary-print');
    Route::get('/hcts-hcts-summary', [\App\Http\Controllers\HctsReceivingController::class, 'summary'])->name('hcts-receiving.summary');

    // HCTS Submission Routes
    Route::middleware('role:sortir')->group(function () {
        Route::get('/hcts-submission/create', [\App\Http\Controllers\HctsSubmissionController::class, 'create'])->name('hcts-submission.create');
        Route::post('/hcts-submission', [\App\Http\Controllers\HctsSubmissionController::class, 'store'])->name('hcts-submission.store');
        Route::get('/hcts-submission/{hcts_submission}/edit', [\App\Http\Controllers\HctsSubmissionController::class, 'edit'])->name('hcts-submission.edit');
        Route::put('/hcts-submission/{hcts_submission}', [\App\Http\Controllers\HctsSubmissionController::class, 'update'])->name('hcts-submission.update');
        Route::delete('/hcts-submission/{hcts_submission}', [\App\Http\Controllers\HctsSubmissionController::class, 'destroy'])->name('hcts-submission.destroy');
        Route::get('/hcts-submission/batches', [\App\Http\Controllers\HctsSubmissionController::class, 'getAvailableBatches'])->name('hcts-submission.available-batches');
    });
    Route::get('/hcts-submission/export', [\App\Http\Controllers\HctsSubmissionController::class, 'export'])->name('hcts-submission.export');
    Route::get('/hcts-submission/print', [\App\Http\Controllers\HctsSubmissionController::class, 'print'])->name('hcts-submission.print');
    Route::get('/hcts-submission', [\App\Http\Controllers\HctsSubmissionController::class, 'index'])->name('hcts-submission.index');

    // HCTS Inventory Routes
    Route::get('/hcts-inventory/export', [\App\Http\Controllers\HctsInventoryController::class, 'export'])->name('hcts-inventory.export');
    Route::get('/hcts-inventory/print', [\App\Http\Controllers\HctsInventoryController::class, 'print'])->name('hcts-inventory.print');
    Route::get('/hcts-inventory', [\App\Http\Controllers\HctsInventoryController::class, 'index'])->name('hcts-inventory.index');
    Route::get('/hcts-inventory/batch-detail', [\App\Http\Controllers\HctsInventoryController::class, 'getBatchDetail'])->name('hcts-inventory.batch-detail');
});

require __DIR__.'/auth.php';
