<?php

use App\Http\Controllers\DowntimeController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\IpqcController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\MachineFinishController;
use App\Http\Controllers\MachineRepairController;
use App\Http\Controllers\OqcController;
use App\Http\Controllers\PurchasingController;
use App\Http\Controllers\QualityController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// auto redirect route
// Route::get('/', function () {
//   return redirect('/maintenance/dashboard-repair');
// })->middleware('auth');

// route untuk menjalankan downtime by ajax
Route::post('/run-downtime', [DowntimeController::class, 'downtime'])->middleware('auth');

// export routes
Route::prefix('export')->middleware('auth')->group(function () {
  Route::post('/machine-repairs', [ExportController::class, 'exportMachineRepair']);
  Route::post('/machine-finish', [ExportController::class, 'exportMachineFinish']);
  Route::post('/machine-waiting-sparepart', [MachineFinishController::class, 'export']); // masih belum dibuat
  Route::post('/ipqc', [ExportController::class, 'exportIpqc']);
  Route::post('/oqc', [ExportController::class, 'exportOqc']);
});

// maintenance routes
Route::prefix('maintenance')->middleware(['auth', 'isDepartement:maintenance'])->group(function () {
  Route::get('/', function () {
    return redirect('/maintenance/dashboard-repair');
  })->middleware('auth');

  // main dashboard maintenance routes
  // repair machines
  Route::resource('/dashboard-repair', MachineRepairController::class)->middleware('auth');
  Route::post('/get-total-downtime-by-month', [MachineRepairController::class, 'getTotalDowntime'])->middleware('auth');

  // finish machine
  Route::get('/dashboard-finish', [MachineFinishController::class, 'index'])->middleware('auth');
  Route::delete('/dashboard-finish/{id}', [MachineFinishController::class, 'destroy'])->middleware('auth');

  // machines routes
  Route::resource('/machines', MachineController::class)->middleware('auth');
});

// quality routes
Route::prefix('quality')->middleware(['auth', 'isDepartement:quality'])->group(function () {
  Route::get('/', function () {
    return redirect('/quality/home');
  })->middleware('auth');

  Route::resource('/home', QualityController::class)->middleware('auth');
  Route::resource('/dashboard-ipqc', IpqcController::class)->middleware('auth');
  Route::resource('/dashboard-oqc', OqcController::class)->middleware('auth');
});

Route::prefix('purchasing')->middleware(['auth', 'isDepartement:purchasing'])->group(function () {
  Route::get('/', function () {
    return redirect('/purchasing/dashboard-waiting-sparepart');
  })->middleware('auth');
  Route::get('/dashboard-repair', [PurchasingController::class, 'indexDashboardRepair'])->middleware('auth');
  Route::get('/dashboard-finish', [PurchasingController::class, 'indexDashboardFinish'])->middleware('auth');
  Route::resource('/dashboard-waiting-sparepart', PurchasingController::class)->middleware('auth');
});

// login routes
Route::get('/login', [LoginController::class, 'indexLogin'])->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->middleware('guest');
Route::delete('/login', [LoginController::class, 'logout'])->middleware('auth');

// register routes
Route::get('/register', [LoginController::class, 'indexRegister']);
Route::post('/register', [LoginController::class, 'store']);
