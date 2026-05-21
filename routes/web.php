<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DynamicEntityController;
use App\Http\Controllers\DynamicRecordController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ActivityLogController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - SILAKU FSIP
|--------------------------------------------------------------------------
*/

// Guest routes
Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
});

// Language Switcher Route
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard - accessible by all roles
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Entity management - BAAK & Kaprodi can create/edit categories
    Route::middleware('role:BAAK|Kaprodi')->group(function () {
        Route::resource('entities', DynamicEntityController::class);
    });

    // User management - BAAK only
    Route::middleware('role:BAAK')->group(function () {
        Route::resource('users', UserManagementController::class);
        Route::patch('/users/{user}/toggle-active', [UserManagementController::class, 'toggleActive'])
            ->name('users.toggle-active');
    });

    // Pimpinan & Wakil Dekan read-only: browse all entities by category
    Route::middleware('role:Pimpinan|Wakil Dekan')->group(function () {
        Route::get('/pimpinan/data/{category}', [DashboardController::class, 'pimpinanBrowse'])
            ->name('pimpinan.browse')
            ->where('category', 'dosen|mahasiswa');
    });

    // Activity logs - BAAK & Pimpinan & Wakil Dekan
    Route::middleware('role:BAAK|Pimpinan|Wakil Dekan')->group(function () {
        Route::get('/activities', [ActivityLogController::class, 'index'])->name('activities.index');

        // Deletion actions - BAAK only
        Route::middleware('role:BAAK')->group(function () {
            Route::delete('/activities/{activity}', [ActivityLogController::class, 'destroy'])->name('activities.destroy');
            Route::post('/activities/clear', [ActivityLogController::class, 'clear'])->name('activities.clear');
        });
    });

    // View entity details & record detail - all authenticated roles
    Route::get('/entities/{entity}/view', [DynamicEntityController::class, 'show'])
        ->name('entities.view');
    Route::get('/entities/{entity}/records/{record}/detail', [DynamicRecordController::class, 'show'])
        ->name('records.detail');

    // Delete entity - BAAK & Kaprodi only
    Route::middleware('role:BAAK|Kaprodi')->group(function () {
        Route::delete('/entities/{entity}/delete', [DynamicEntityController::class, 'destroy'])
            ->name('entities.delete');
    });

    // Records - BAAK + Kaprodi + Dosen can create/edit
    Route::middleware('role:BAAK|Kaprodi|Dosen')->group(function () {
        // Record CRUD
        Route::get('/entities/{entity}/records/create', [DynamicRecordController::class, 'create'])
            ->name('records.create');
        Route::post('/entities/{entity}/records', [DynamicRecordController::class, 'store'])
            ->name('records.store');
        Route::get('/entities/{entity}/records/{record}', [DynamicRecordController::class, 'show'])
            ->name('records.show');
        Route::get('/entities/{entity}/records/{record}/edit', [DynamicRecordController::class, 'edit'])
            ->name('records.edit');
        Route::put('/entities/{entity}/records/{record}', [DynamicRecordController::class, 'update'])
            ->name('records.update');
        Route::delete('/entities/{entity}/records/{record}', [DynamicRecordController::class, 'destroy'])
            ->name('records.destroy');
    });

    // Export routes - accessible by all authenticated roles
    Route::middleware('auth')->group(function () {
        Route::get('/entities/{entity}/export-excel', [DynamicEntityController::class, 'exportExcel'])
            ->name('entities.export-excel');
        Route::get('/entities/{entity}/export-pdf', [DynamicEntityController::class, 'exportPdf'])
            ->name('entities.export-pdf');
        Route::get('/api/entity/{entity}/chart-data', [DynamicEntityController::class, 'getChartData'])
            ->name('api.entity-chart-data');
    });

    // API endpoint for dashboard chart data (AJAX)
    Route::get('/api/chart-data', function () {
        $service = app(\App\Services\DashboardAggregationService::class);
        return response()->json($service->getChartData());
    })->name('api.chart-data');
});
