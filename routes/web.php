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
use App\Http\Controllers\TargetController;
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
Route::get('/', function () {
  if (!auth()->user()) {
    return redirect("/login");
  }

  $departement = auth()->user()->departement;
  $url = $departement;

  if ($departement == 'it') {
    return redirect("/menu");
  }

  if ($departement == 'qc' || $departement == 'qa') {
    $url = 'quality';
  }

  return redirect("/$url");
})->middleware('auth');

Route::prefix('/menu')->middleware(['auth', 'isDepartement: it'])->group(function () {
  Route::get('/', function () {
    return view('menu.index');
  });

  Route::get('/register', [LoginController::class, 'indexRegister']);
  Route::post('/register', [LoginController::class, 'store']);
});


Route::prefix('target')->middleware(['auth', 'isDepartement:it'])->group(function () {
  Route::get('/', [TargetController::class, 'index']);
  Route::put('/update-quality', [TargetController::class, 'updateQuality']);
  Route::put('/update-maintenance', [TargetController::class, 'updateMaintenance']);
  Route::put('/update-sales', [TargetController::class, 'updateSales']);
});

// route untuk menjalankan downtime by ajax
Route::post('/run-downtime', [DowntimeController::class, 'downtime'])->middleware('auth');
Route::post('/get-total-downtime-by-month', [DowntimeController::class, 'getTotalDowntime'])->middleware('auth');

// export routes
Route::prefix('export')->middleware('auth')->group(function () {
  Route::post('/machine-repairs', [ExportController::class, 'exportMachineRepair']);
  Route::post('/machines-waiting-sparepart', [ExportController::class, 'exportMachineWaitingSparepart']);
  Route::post('/machine-finish', [ExportController::class, 'exportMachineFinish']);
  Route::post('/machine-waiting-sparepart', [MachineFinishController::class, 'export']); // masih belum dibuat
  Route::post('/ipqc', [ExportController::class, 'exportIpqc']);
  Route::post('/oqc', [ExportController::class, 'exportOqc']);
});

// maintenance routes
Route::prefix('maintenance')->middleware(['auth', 'isDepartement:maintenance'])->group(function () {
  Route::get('/', function () {
    return redirect('/maintenance/dashboard-repair');
  });

  // main dashboard maintenance routes
  // repair machines
  Route::resource('/dashboard-repair', MachineRepairController::class);

  // finish machine
  Route::get('/dashboard-finish', [MachineFinishController::class, 'index']);
  Route::delete('/dashboard-finish/{id}', [MachineFinishController::class, 'destroy']);

  // machines routes
  Route::resource('/machines', MachineController::class);
});

// quality routes
Route::prefix('quality')->middleware(['auth', 'isDepartement:quality'])->group(function () {
  Route::get('/', function () {
    return redirect('/quality/home');
  });
  Route::get('/home', [QualityController::class, 'indexHome']);
  Route::post('/home', [QualityController::class, 'store']);
  Route::put('/home-edit-ipqc', [QualityController::class, 'updateTargetIpqc']);
  Route::put('/home-edit-oqc', [QualityController::class, 'updateTargetOqc']);
  Route::get('/dashboard-ipqc', [QualityController::class, 'indexIpqc']);
  Route::get('/dashboard-oqc', [QualityController::class, 'indexOqc']);
  Route::put('/dashboard-ipqc/{id}', [QualityController::class, 'updateDataIpqc']);
  Route::put('/dashboard-oqc/{id}', [QualityController::class, 'updateDataOqc']);
});

// purchasing routes
Route::prefix('purchasing')->middleware(['auth', 'isDepartement:purchasing'])->group(function () {
  Route::get('/', function () {
    return redirect('/purchasing/dashboard-waiting-sparepart');
  });
  Route::get('/dashboard-repair', [PurchasingController::class, 'indexDashboardRepair']);
  Route::get('/dashboard-finish', [PurchasingController::class, 'indexDashboardFinish']);
  Route::get('/machines', [PurchasingController::class, 'indexMachine']);
  Route::get('/dashboard-waiting-sparepart', [PurchasingController::class, 'index']);
  Route::put('/dashboard-waiting-sparepart/{id}', [PurchasingController::class, 'update']);
  Route::delete('/dashboard-waiting-sparepart/{id}', [PurchasingController::class, 'destroy']);
});

// login routes
Route::get('/login', [LoginController::class, 'indexLogin'])->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->middleware('guest');
Route::delete('/login', [LoginController::class, 'logout'])->middleware('auth');
