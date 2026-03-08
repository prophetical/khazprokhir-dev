<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class , 'index'])->name('dashboard');

    // Batch Tracking
    Route::get('/batch-tracking', [\App\Http\Controllers\BatchTrackingController::class , 'index'])->name('batch-tracking.index');

    // Reports
    Route::get('/reports', [\App\Http\Controllers\ReportController::class , 'index'])->name('reports.index');
    Route::get('/reports/export', [\App\Http\Controllers\ReportController::class , 'export'])->name('reports.export');
    Route::get('/reports/print', [\App\Http\Controllers\ReportController::class , 'print'])->name('reports.print');

    Route::get('/profile', [ProfileController::class , 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class , 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class , 'destroy'])->name('profile.destroy');

    // HCS Receiving Routes (restricted to sortir for creation/editing/deleting)
    Route::middleware('role:sortir')->group(function () {
            Route::get('/hcs-receiving/create', [\App\Http\Controllers\HcsReceivingController::class , 'create'])->name('hcs-receiving.create');
            Route::post('/hcs-receiving', [\App\Http\Controllers\HcsReceivingController::class , 'store'])->name('hcs-receiving.store');
            Route::get('/hcs-receiving/{hcs_receiving}/edit', [\App\Http\Controllers\HcsReceivingController::class , 'edit'])->name('hcs-receiving.edit');
            Route::put('/hcs-receiving/{hcs_receiving}', [\App\Http\Controllers\HcsReceivingController::class , 'update'])->name('hcs-receiving.update');
            Route::delete('/hcs-receiving/{hcs_receiving}', [\App\Http\Controllers\HcsReceivingController::class , 'destroy'])->name('hcs-receiving.destroy');
        }
        );

        // Everyone can view index (with respective policies)
        Route::get('/hcs-receiving', [\App\Http\Controllers\HcsReceivingController::class , 'index'])->name('hcs-receiving.index');

        // HCS Sorting Routes
        Route::middleware('role:sortir')->group(function () {
            Route::get('/hcs-sorting/create', [\App\Http\Controllers\HcsSortingController::class , 'create'])->name('hcs-sorting.create');
            Route::post('/hcs-sorting', [\App\Http\Controllers\HcsSortingController::class , 'store'])->name('hcs-sorting.store');
        }
        );
        Route::get('/hcs-sorting', [\App\Http\Controllers\HcsSortingController::class , 'index'])->name('hcs-sorting.index');
        Route::get('/rekomendasi-penyortiran', [\App\Http\Controllers\RekomendasiPenyortiranController::class , 'index'])->name('rekomendasi-penyortiran.index');
        Route::get('/hcs-sorting-reports', [\App\Http\Controllers\HcsSortingReportController::class , 'index'])->name('hcs-sorting-reports.index');
        Route::get('/hcs-sorting-reports/export', [\App\Http\Controllers\HcsSortingReportController::class , 'export'])->name('hcs-sorting-reports.export');
        Route::get('/hcs-sorting-reports/print', [\App\Http\Controllers\HcsSortingReportController::class , 'print'])->name('hcs-sorting-reports.print');
        Route::middleware('role:sortir')->group(function () {
            Route::get('/hcs-sorting-reports/{hcs_sorting_report}/edit', [\App\Http\Controllers\HcsSortingReportController::class , 'edit'])->name('hcs-sorting-reports.edit');
            Route::put('/hcs-sorting-reports/{hcs_sorting_report}', [\App\Http\Controllers\HcsSortingReportController::class , 'update'])->name('hcs-sorting-reports.update');
            Route::delete('/hcs-sorting-reports/{hcs_sorting_report}', [\App\Http\Controllers\HcsSortingReportController::class , 'destroy'])->name('hcs-sorting-reports.destroy');
        }
        );

        // API to get used packs (requires auth)
        Route::get('/api/packs/used', [\App\Http\Controllers\PackController::class , 'used'])->name('packs.used');

        // Pengemasan Routes
        Route::middleware('role:sortir')->group(function () {
            Route::get('/pengemasan/create', [\App\Http\Controllers\PengemasanController::class , 'create'])->name('pengemasan.create');
            Route::post('/pengemasan', [\App\Http\Controllers\PengemasanController::class , 'store'])->name('pengemasan.store');
        }
        );
        Route::get('/pengemasan', [\App\Http\Controllers\PengemasanController::class , 'index'])->name('pengemasan.index');
        Route::get('/data-pengemasan', [\App\Http\Controllers\PengemasanController::class , 'data'])->name('pengemasan.data');
        Route::get('/pengemasan/{id}', [\App\Http\Controllers\PengemasanController::class , 'show'])->name('pengemasan.show');
    });

require __DIR__ . '/auth.php';
