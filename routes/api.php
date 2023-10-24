<?php

use App\Http\Controllers\ApiQualityController;
use App\Http\Controllers\MaintenanceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/api', function () {
    return ['/dashboard-repair'];
});

Route::prefix('maintenance')->group(function () {
    Route::get('/downtime', [MaintenanceController::class, 'currentDowntime']);
    Route::get('/downtime-1', [MaintenanceController::class, 'lastDowntime']);
    Route::get('/downtime-2', [MaintenanceController::class, 'beforeLastDowntime']);
    Route::get('/data-downtime', [MaintenanceController::class, 'dataMachineRepairs']);
    Route::get('/history-downtime', [MaintenanceController::class, 'historyDowntimes']);
});

Route::prefix('quality')->group(function () {
    Route::get('ipqc', [ApiQualityController::class, 'ipqc']);
    Route::get('oqc', [ApiQualityController::class, 'oqc']);
});
