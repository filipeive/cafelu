<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SyncIngestController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// === RESTPOS CLOUD SYNC ENDPOINTS ===
Route::prefix('sync')->group(function () {
    Route::get('/status', [SyncIngestController::class, 'status'])->name('api.sync.status');
    Route::post('/verify-license', [SyncIngestController::class, 'verifyLicense'])->name('api.sync.verify');
    Route::post('/ingest', [SyncIngestController::class, 'ingest'])->name('api.sync.ingest');
});
