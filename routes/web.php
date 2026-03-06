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

    Route::get('/profile', [ProfileController::class , 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class , 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class , 'destroy'])->name('profile.destroy');

    // HCS Receiving Routes (restricted to sortir for creation)
    Route::middleware('role:sortir')->group(function () {
            Route::get('/hcs-receiving/create', [\App\Http\Controllers\HcsReceivingController::class , 'create'])->name('hcs-receiving.create');
            Route::post('/hcs-receiving', [\App\Http\Controllers\HcsReceivingController::class , 'store'])->name('hcs-receiving.store');
        }
        );

        // Everyone can view index (with respective policies)
        Route::get('/hcs-receiving', [\App\Http\Controllers\HcsReceivingController::class , 'index'])->name('hcs-receiving.index');

        // API to get used packs (requires auth)
        Route::get('/api/packs/used', [\App\Http\Controllers\PackController::class , 'used'])->name('packs.used');
    });

require __DIR__ . '/auth.php';
