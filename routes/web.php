<?php

use App\Http\Controllers\BahanPenolongController;
use App\Http\Controllers\BatchTrackingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HcsKhazaiRegistrationController;
use App\Http\Controllers\HcsReceivingController;
use App\Http\Controllers\HcsSortingController;
use App\Http\Controllers\HcsSortingReportController;
use App\Http\Controllers\HctsInventoryController;
use App\Http\Controllers\HctsReceivingController;
use App\Http\Controllers\HctsSubmissionController;
use App\Http\Controllers\LaporanHarianController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PackController;
use App\Http\Controllers\PengemasanController;
use App\Http\Controllers\PengemasanReportController;
use App\Http\Controllers\PenyablonanController;
use App\Http\Controllers\PenyerahanBiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RekomendasiPenerimaanController;
use App\Http\Controllers\RekomendasiPenyortiranController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SerialMappingController;
use App\Http\Controllers\TargetController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\XPenggantiCutpackController;
use App\Http\Controllers\XPenggantiKhazaiController;
use App\Http\Controllers\XPenggantiRekapController;
use App\Http\Controllers\XPenggantiRikyetController;
use App\Http\Controllers\XPenggantiSeriController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified', RoleMiddleware::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Batch Tracking
    Route::get('/batch-tracking', [BatchTrackingController::class, 'index'])->name('batch-tracking.index');

    // Laporan
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
    Route::get('/reports/print', [ReportController::class, 'print'])->name('reports.print');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Penerimaan HCS Routes
    Route::get('/hcs-receiving/create', [HcsReceivingController::class, 'create'])->name('hcs-receiving.create');
    Route::post('/hcs-receiving', [HcsReceivingController::class, 'store'])->name('hcs-receiving.store');
    Route::get('/hcs-receiving/{hcs_receiving}/edit', [HcsReceivingController::class, 'edit'])->name('hcs-receiving.edit');
    Route::put('/hcs-receiving/{hcs_receiving}', [HcsReceivingController::class, 'update'])->name('hcs-receiving.update');
    Route::delete('/hcs-receiving/{hcs_receiving}', [HcsReceivingController::class, 'destroy'])->name('hcs-receiving.destroy');
    Route::get('/hcs-receiving', [HcsReceivingController::class, 'index'])->name('hcs-receiving.index');
    Route::get('/hcs-receiving/scan', [HcsReceivingController::class, 'scan'])->name('hcs-receiving.scan');
    Route::post('/hcs-receiving/scan-process', [HcsReceivingController::class, 'storeScan'])->name('hcs-receiving.scan-process');
    Route::get('/hcs-receiving/scan-status', [HcsReceivingController::class, 'scanStatus'])->name('hcs-receiving.scan-status');
    Route::post('/hcs-receiving/manual-confirm/{registration}', [HcsReceivingController::class, 'manualConfirm'])->name('hcs-receiving.manual-confirm');
    Route::get('/hcs-receiving/{barcode_token}/history', [HcsReceivingController::class, 'history'])->name('hcs-receiving.history');

    // HCS Khazai Registration Routes (New Module for Khazai)
    Route::get('/hcs-khazai-registration/get-pack-status', [HcsKhazaiRegistrationController::class, 'getPackStatus'])->name('hcs-khazai-registration.get-pack-status');
    Route::resource('hcs-khazai-registration', HcsKhazaiRegistrationController::class);
    Route::get('/hcs-khazai-registration/{id}/barcode', [HcsKhazaiRegistrationController::class, 'barcode'])->name('hcs-khazai-registration.barcode');

    // Rekomendasi Penerimaan
    Route::get('/rekomendasi-penerimaan', [RekomendasiPenerimaanController::class, 'index'])->name('rekomendasi-penerimaan.index');
    Route::get('/rekomendasi-penerimaan/print', [RekomendasiPenerimaanController::class, 'print'])->name('rekomendasi-penerimaan.print');
    Route::get('/rekomendasi-penerimaan/export', [RekomendasiPenerimaanController::class, 'export'])->name('rekomendasi-penerimaan.export');
    Route::get('/rekomendasi-penerimaan/show', [RekomendasiPenerimaanController::class, 'show'])->name('rekomendasi-penerimaan.show');

    // Penyortiran HCS
    Route::get('/hcs-sorting/create', [HcsSortingController::class, 'create'])->name('hcs-sorting.create');
    Route::post('/hcs-sorting', [HcsSortingController::class, 'store'])->name('hcs-sorting.store');
    Route::get('/hcs-sorting', [HcsSortingController::class, 'index'])->name('hcs-sorting.index');
    Route::get('/rekomendasi-penyortiran', [RekomendasiPenyortiranController::class, 'index'])->name('rekomendasi-penyortiran.index');
    Route::get('/hcs-sorting-reports', [HcsSortingReportController::class, 'index'])->name('hcs-sorting-reports.index');
    Route::get('/hcs-sorting-reports/export', [HcsSortingReportController::class, 'export'])->name('hcs-sorting-reports.export');
    Route::get('/hcs-sorting-reports/print', [HcsSortingReportController::class, 'print'])->name('hcs-sorting-reports.print');
    Route::get('/hcs-sorting-reports/{hcs_sorting_report}/edit', [HcsSortingReportController::class, 'edit'])->name('hcs-sorting-reports.edit');
    Route::put('/hcs-sorting-reports/{hcs_sorting_report}', [HcsSortingReportController::class, 'update'])->name('hcs-sorting-reports.update');
    Route::delete('/hcs-sorting-reports/{hcs_sorting_report}/destroy', [HcsSortingReportController::class, 'destroy'])->name('hcs-sorting-reports.destroy');

    // API untuk mendapatkan pack yang sudah digunakan (memerlukan auth)
    Route::get('/api/packs/used', [PackController::class, 'used'])->name('packs.used');

    // API Notifikasi
    Route::get('/notifications/hcs-ready', [PengemasanController::class, 'getReadyToPackNotifications'])->name('notifications.hcs-ready');

    // Pengemasan Routes
    Route::get('/pengemasan/create', [PengemasanController::class, 'create'])->name('pengemasan.create');
    Route::post('/pengemasan', [PengemasanController::class, 'store'])->name('pengemasan.store');
    Route::delete('/pengemasan/{id}', [PengemasanController::class, 'destroy'])->name('pengemasan.destroy');
    Route::get('/pengemasan', [PengemasanController::class, 'index'])->name('pengemasan.index');
    Route::get('/data-pengemasan', [PengemasanController::class, 'data'])->name('pengemasan.data');
    Route::get('/data-pengemasan/export', [PengemasanController::class, 'export'])->name('pengemasan.export');
    Route::get('/data-pengemasan/print', [PengemasanController::class, 'print'])->name('pengemasan.print');

    // Laporan Pengemasan HCS
    Route::get('/pengemasan-report', [PengemasanReportController::class, 'index'])->name('pengemasan.report.index');
    Route::get('/pengemasan-report/print', [PengemasanReportController::class, 'print'])->name('pengemasan.report.print');

    Route::get('/pengemasan/{id}', [PengemasanController::class, 'show'])->name('pengemasan.show');

    // Penyerahan ke BI Routes
    Route::get('/penyerahan-bi', [PenyerahanBiController::class, 'index'])->name('penyerahan-bi.index');
    Route::get('/penyerahan-bi/create', [PenyerahanBiController::class, 'create'])->name('penyerahan-bi.create');
    Route::post('/penyerahan-bi', [PenyerahanBiController::class, 'store'])->name('penyerahan-bi.store');
    Route::get('/penyerahan-bi/{id}/edit', [PenyerahanBiController::class, 'edit'])->name('penyerahan-bi.edit');
    Route::put('/penyerahan-bi/{id}', [PenyerahanBiController::class, 'update'])->name('penyerahan-bi.update');
    Route::delete('/penyerahan-bi/{id}', [PenyerahanBiController::class, 'destroy'])->name('penyerahan-bi.destroy');
    Route::get('/penyerahan-bi/export', [PenyerahanBiController::class, 'export'])->name('penyerahan-bi.export');
    Route::get('/penyerahan-bi/print', [PenyerahanBiController::class, 'print'])->name('penyerahan-bi.print');
    Route::get('/api/penyerahan-bi/check-duplicate', [PenyerahanBiController::class, 'checkDuplicate'])->name('penyerahan-bi.check-duplicate');
    Route::get('/api/penyerahan-bi/last-dus', [PenyerahanBiController::class, 'getLastDus'])->name('penyerahan-bi.last-dus');

    // Laporan Harian
    Route::get('/laporan-harian', [LaporanHarianController::class, 'index'])->name('laporan-harian.index');
    Route::get('/laporan-harian/rekonsiliasi', [LaporanHarianController::class, 'rekonsiliasi'])->name('laporan-harian.rekonsiliasi');
    Route::get('/laporan-harian/rekonsiliasi/export', [LaporanHarianController::class, 'exportRekonsiliasi'])->name('laporan-harian.rekonsiliasi-export');
    Route::get('/laporan-harian/rekonsiliasi/print', [LaporanHarianController::class, 'printRekonsiliasi'])->name('laporan-harian.rekonsiliasi-print');
    Route::get('/laporan-harian/realtime', [LaporanHarianController::class, 'realtime'])->name('laporan-harian.realtime');
    Route::get('/laporan-harian/realtime-partial', [LaporanHarianController::class, 'getRealtimePartial'])->name('laporan-harian.realtime-partial');
    Route::get('/laporan-harian/print', [LaporanHarianController::class, 'print'])->name('laporan-harian.print');
    Route::get('/laporan-harian/export', [LaporanHarianController::class, 'export'])->name('laporan-harian.export');
    Route::get('/laporan-harian/persediaan-detail', [LaporanHarianController::class, 'persediaanDetail'])->name('laporan-harian.persediaan-detail');
    Route::get('/laporan-harian/persediaan-detail-data', [LaporanHarianController::class, 'persediaanDetailData'])->name('laporan-harian.persediaan-detail-data');
    Route::post('/laporan-harian/verifikasi', [LaporanHarianController::class, 'verifikasiHarian'])->name('laporan-harian.verifikasi');
    Route::post('/laporan-harian/rekonsiliasi/verifikasi', [LaporanHarianController::class, 'verifikasiRekonsiliasi'])->name('laporan-harian.rekonsiliasi-verifikasi');
    Route::post('/laporan-harian/verifikasi/destroy', [LaporanHarianController::class, 'destroyVerifikasi'])->name('laporan-harian.verifikasi-destroy');

    // Manajemen Target
    Route::get('/targets', [TargetController::class, 'index'])->name('targets.index');
    Route::get('/targets/create', [TargetController::class, 'create'])->name('targets.create');
    Route::post('/targets', [TargetController::class, 'store'])->name('targets.store');
    Route::get('/targets/{id}/edit', [TargetController::class, 'edit'])->name('targets.edit');
    Route::put('/targets/{id}', [TargetController::class, 'update'])->name('targets.update');
    Route::delete('/targets/{id}', [TargetController::class, 'destroy'])->name('targets.destroy');

    // Penerimaan HCTS Routes
    Route::get('/hcts-receiving/create', [HctsReceivingController::class, 'create'])->name('hcts-receiving.create');
    Route::post('/hcts-receiving', [HctsReceivingController::class, 'store'])->name('hcts-receiving.store');
    Route::get('/hcts-receiving/get-hcs-total', [HctsReceivingController::class, 'getHcsTotal'])->name('hcts-receiving.get-hcs-total');
    Route::get('/hcts-receiving/{hcts_receiving}/edit', [HctsReceivingController::class, 'edit'])->name('hcts-receiving.edit');
    Route::put('/hcts-receiving/{hcts_receiving}', [HctsReceivingController::class, 'update'])->name('hcts-receiving.update');
    Route::delete('/hcts-receiving/{hcts_receiving}', [HctsReceivingController::class, 'destroy'])->name('hcts-receiving.destroy');
    Route::get('/hcts-receiving/export', [HctsReceivingController::class, 'export'])->name('hcts-receiving.export');
    Route::get('/hcts-receiving/print', [HctsReceivingController::class, 'print'])->name('hcts-receiving.print');
    Route::get('/hcts-receiving', [HctsReceivingController::class, 'index'])->name('hcts-receiving.index');
    Route::get('/hcts-hcts-summary/export', [HctsReceivingController::class, 'summaryExport'])->name('hcts-receiving.summary-export');
    Route::get('/hcts-hcts-summary/print', [HctsReceivingController::class, 'summaryPrint'])->name('hcts-receiving.summary-print');
    Route::get('/hcts-hcts-summary', [HctsReceivingController::class, 'summary'])->name('hcts-receiving.summary');

    // Penyerahan HCTS Routes
    Route::get('/hcts-submission/create', [HctsSubmissionController::class, 'create'])->name('hcts-submission.create');
    Route::post('/hcts-submission', [HctsSubmissionController::class, 'store'])->name('hcts-submission.store');
    Route::get('/hcts-submission/{hcts_submission}/edit', [HctsSubmissionController::class, 'edit'])->name('hcts-submission.edit');
    Route::put('/hcts-submission/{hcts_submission}', [HctsSubmissionController::class, 'update'])->name('hcts-submission.update');
    Route::delete('/hcts-submission/{hcts_submission}', [HctsSubmissionController::class, 'destroy'])->name('hcts-submission.destroy');
    Route::get('/hcts-submission/batches', [HctsSubmissionController::class, 'getAvailableBatches'])->name('hcts-submission.available-batches');
    Route::get('/hcts-submission/export', [HctsSubmissionController::class, 'export'])->name('hcts-submission.export');
    Route::get('/hcts-submission/print', [HctsSubmissionController::class, 'print'])->name('hcts-submission.print');
    Route::get('/hcts-submission', [HctsSubmissionController::class, 'index'])->name('hcts-submission.index');

    // Tracking (Traceability)
    Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking.index');

    // HCTS Inventory Routes
    Route::get('/hcts-inventory/export', [HctsInventoryController::class, 'export'])->name('hcts-inventory.export');
    Route::get('/hcts-inventory/print', [HctsInventoryController::class, 'print'])->name('hcts-inventory.print');
    Route::get('/hcts-inventory', [HctsInventoryController::class, 'index'])->name('hcts-inventory.index');
    Route::get('/hcts-inventory/batch-detail', [HctsInventoryController::class, 'getBatchDetail'])->name('hcts-inventory.batch-detail');

    // Messages (Pesan Antar User)
    Route::resource('messages', MessageController::class)->only(['index', 'store', 'edit', 'update', 'destroy']);

    // Bahan Penolong (Auxiliary Materials)
    Route::prefix('bahan-penolong')->name('bahan-penolong.')->group(function () {
        Route::get('/persediaan', [BahanPenolongController::class, 'persediaan'])->name('persediaan');
        Route::get('/inventory/export', [BahanPenolongController::class, 'inventoryExport'])->name('inventory.export');
        Route::get('/inventory/print', [BahanPenolongController::class, 'inventoryPrint'])->name('inventory.print');
        Route::get('/penerimaan', [BahanPenolongController::class, 'penerimaan'])->name('penerimaan');
        Route::get('/pemakaian', [BahanPenolongController::class, 'pemakaian'])->name('pemakaian');
        Route::post('/transaction', [BahanPenolongController::class, 'storeTransaction'])->name('transaction.store');
        Route::put('/transaction/{transaction}', [BahanPenolongController::class, 'updateTransaction'])->name('transaction.update');
        Route::delete('/transaction/{transaction}', [BahanPenolongController::class, 'destroyTransaction'])->name('transaction.destroy');
        Route::get('/export', [BahanPenolongController::class, 'export'])->name('export');
        Route::get('/print', [BahanPenolongController::class, 'print'])->name('print');
    });
    Route::resource('bahan-penolong', BahanPenolongController::class)->only(['store', 'update', 'destroy']);

    // Penyablonan Routes
    Route::prefix('penyablonan')->name('penyablonan.')->group(function () {
        Route::get('/penerimaan', [PenyablonanController::class, 'penerimaan'])->name('penerimaan');
        Route::post('/penerimaan', [PenyablonanController::class, 'storePenerimaan'])->name('penerimaan.store');

        Route::get('/dus', [PenyablonanController::class, 'dus'])->name('dus');
        Route::post('/dus', [PenyablonanController::class, 'storeDus'])->name('dus.store');

        Route::get('/kerusakan', [PenyablonanController::class, 'kerusakan'])->name('kerusakan');
        Route::post('/kerusakan', [PenyablonanController::class, 'storeKerusakan'])->name('kerusakan.store');

        Route::get('/laporan', [PenyablonanController::class, 'laporan'])->name('laporan');
        Route::get('/laporan/print', [PenyablonanController::class, 'print'])->name('laporan.print');
    });

    // X Pengganti Routes
    Route::prefix('x-pengganti')->name('x-pengganti.')->group(function () {
        // Sub-menu 1: Form Input Seri (Master Data)
        Route::get('/seri', [XPenggantiSeriController::class, 'index'])->name('seri.index');
        Route::get('/seri/create', [XPenggantiSeriController::class, 'create'])->name('seri.create');
        Route::post('/seri', [XPenggantiSeriController::class, 'store'])->name('seri.store');
        Route::delete('/seri/{seri}', [XPenggantiSeriController::class, 'destroy'])->name('seri.destroy');

        // Sub-menu 2: Form Input Khazai (Grid Transaksional)
        Route::get('/khazai', [XPenggantiKhazaiController::class, 'index'])->name('khazai.index');
        Route::post('/khazai', [XPenggantiKhazaiController::class, 'store'])->name('khazai.store');
        Route::get('/khazai/input', [XPenggantiKhazaiController::class, 'inputForm'])->name('khazai.input');
        Route::get('/khazai/pdf', [XPenggantiKhazaiController::class, 'exportPdf'])->name('khazai.pdf');

        // Sub-menu 3: Form Input Cutpack (Grid Transaksional Kompleks)
        Route::get('/cutpack', [XPenggantiCutpackController::class, 'index'])->name('cutpack.index');
        Route::post('/cutpack', [XPenggantiCutpackController::class, 'store'])->name('cutpack.store');
        Route::get('/cutpack/input', [XPenggantiCutpackController::class, 'inputForm'])->name('cutpack.input');
        Route::get('/cutpack/pdf', [XPenggantiCutpackController::class, 'exportPdf'])->name('cutpack.pdf');

        // Sub-menu 4: Form Input Rikyet (Grid Transaksional Brood)
        Route::get('/rikyet', [XPenggantiRikyetController::class, 'index'])->name('rikyet.index');
        Route::post('/rikyet', [XPenggantiRikyetController::class, 'store'])->name('rikyet.store');
        Route::get('/rikyet/input', [XPenggantiRikyetController::class, 'inputForm'])->name('rikyet.input');
        Route::get('/rikyet/pdf', [XPenggantiRikyetController::class, 'exportPdf'])->name('rikyet.pdf');

        // Sub-menu 5: Hasil Rekap Khazprokhir (Summary Dashboard)
        Route::get('/rekap', [XPenggantiRekapController::class, 'index'])->name('rekap.index');
        Route::get('/rekap/show', [XPenggantiRekapController::class, 'show'])->name('rekap.show');
        Route::get('/rekap/print', [XPenggantiRekapController::class, 'print'])->name('rekap.print');

        // Sub-menu 6: Serial Range Mapping (Pemetaan Seri Asal ↔ Pengganti)
        Route::prefix('mapping')->name('mapping.')->group(function () {
            Route::get('/', [SerialMappingController::class, 'index'])->name('index');
            Route::get('/create', [SerialMappingController::class, 'create'])->name('create');
            Route::post('/pack', [SerialMappingController::class, 'storePack'])->name('store.pack');
            Route::post('/brood', [SerialMappingController::class, 'storeBrood'])->name('store.brood');
            Route::post('/partial', [SerialMappingController::class, 'storePartial'])->name('store.partial');
            Route::post('/vell', [SerialMappingController::class, 'storeVell'])->name('store.vell');
            Route::post('/single', [SerialMappingController::class, 'storeSingle'])->name('store.single');
            Route::get('/lookup', [SerialMappingController::class, 'lookup'])->name('lookup');
            Route::delete('/{id}', [SerialMappingController::class, 'destroy'])->name('destroy');
            Route::delete('/pack/{seri_id}/{pack_number}', [SerialMappingController::class, 'destroyByPack'])->name('destroy.pack');
            Route::delete('/session/{seri_id}/{timestamp}', [SerialMappingController::class, 'destroyBySession'])->name('destroy.session');
        });

        // API: list masters untuk dropdown
        Route::get('/api/masters', [XPenggantiKhazaiController::class, 'getMasters'])->name('api.masters');
    });

    // User Management (Admin Only, protected in RoleMiddleware)
    Route::resource('users', UserController::class);
});

require __DIR__.'/auth.php';
