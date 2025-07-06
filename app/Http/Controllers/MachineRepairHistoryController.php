<?php

namespace App\Http\Controllers;

use App\Models\MachineRepair;
use App\Http\Requests\StoreMachineRepairRequest;
use App\Http\Requests\UpdateMachineRepairRequest;
use App\Models\Machine;
use App\Models\TotalDowntime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema; // Tambahkan ini
use Illuminate\Pagination\LengthAwarePaginator;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class MachineRepairHistoryController extends Controller
{

  // public function index()
  // {

  //   // Mengambil data dengan filter 'keterangan' dan mengurutkan
  //   $machineRepairs = MachineRepair::where('keterangan', 'history')
  //     ->orderBy('tgl_input', 'desc')
  //     ->orderBy('id', 'desc')
  //     ->paginate(10);

  //   // Menambahkan nilai search tanpa query tambahan
  //   $machineRepairs->each(function ($machineRepair) {
  //     $machineRepair->search = Carbon::parse($machineRepair->tgl_kerusakan)->toDateString();
  //   });

  //   return view('maintenance.dashboard-repair.history', [
  //     'machineRepairs' => $machineRepairs,
  //   ]);
  // }

  public function index(Request $request)
  {
    // Mengambil data dengan filter 'keterangan' dan mengurutkan
    $machineRepairs = MachineRepair::where('keterangan', 'history')
      ->orderBy('tgl_input', 'desc')
      ->orderBy('id', 'desc');

    // Menambahkan filter berdasarkan rentang tanggal
    if ($request->has('minDate') && $request->has('maxDate')) {
      // Pastikan tanggal min dan max dalam format yang benar
      $minDate = Carbon::parse($request->input('minDate'))->startOfDay();
      $maxDate = Carbon::parse($request->input('maxDate'))->endOfDay();

      // Terapkan filter tanggal
      $machineRepairs->whereBetween('start_monthly_downtime', [$minDate, $maxDate]);
    }

    // Mendapatkan data dengan pagination
    $machineRepairs = $machineRepairs->paginate(10);

    // Menambahkan nilai search tanpa query tambahan
    $machineRepairs->each(function ($machineRepair) {
      $machineRepair->search = Carbon::parse($machineRepair->tgl_kerusakan)->toDateString();
    });

    return view('maintenance.dashboard-repair.history', [
      'machineRepairs' => $machineRepairs,
    ]);
  }


  // public function index(Request $request)
  // {
  //   $DowntimeController = (new DowntimeController());

  //   // Inisialisasi query untuk mengambil data mesin rusak
  //   $query = MachineRepair::where('keterangan', 'history') // Filter keterangan
  //     ->orderBy('tgl_input', 'desc')
  //     ->orderBy('id', 'desc');

  //   // Cek jika ada filter tanggal yang diberikan oleh user
  //   if ($request->has('min') && $request->has('max')) {
  //     $minDate = Carbon::parse($request->min);
  //     $maxDate = Carbon::parse($request->max);

  //     // Filter berdasarkan rentang tanggal
  //     $query->whereBetween('tgl_kerusakan', [$minDate, $maxDate]);
  //   } else {
  //     // Jika tidak ada filter, tampilkan data untuk hari ini saja
  //     $today = Carbon::today()->toDateString();
  //     $query->whereDate('tgl_kerusakan', $today);
  //   }

  //   // Ambil data mesin rusak setelah difilter
  //   $machineRepairs = $query->get();

  //   // Ambil data mesin yang memiliki status 'Stop' untuk ditampilkan di JS
  //   $jsMachineRepairs = MachineRepair::where('status_aktifitas', 'Stop')
  //     ->where('keterangan', 'history') // Filter keterangan
  //     ->get([
  //       'id',
  //       'start_downtime',
  //       'current_downtime',
  //       'current_monthly_downtime',
  //       'total_monthly_downtime',
  //       'total_downtime',
  //       'downtime_month',
  //       'status_mesin',
  //       'status_aktifitas'
  //     ]);

  //   // Hitung total jumlah mesin yang status aktifitasnya 'Stop'
  //   $totalMachineRepairs = MachineRepair::where('status_aktifitas', 'Stop')
  //     ->where('keterangan', 'history') // Filter keterangan
  //     ->count();

  //   // Ambil semua mesin untuk ditampilkan
  //   $machines = Machine::all();

  //   // Mengambil data downtime bulanan
  //   $monthlyDowntime = $DowntimeController->totalMonthlyDowntime();
  //   $monthlyDowntimeToHours = $DowntimeController->totalMonthlyDowntime(false, true);
  //   $hoursMonthlyDowntime = $DowntimeController->downtimeHoursTranslator($monthlyDowntimeToHours);

  //   // Perbarui downtime dan search untuk setiap mesin repair
  //   foreach ($machineRepairs as $machineRepair) {
  //     $addValue = $machineRepairs->find($machineRepair->id);
  //     $addValue->search = Carbon::parse($machineRepair->tgl_kerusakan)->toDateString();
  //     $total = $DowntimeController->addDowntimeByDowntime($machineRepair->current_downtime, $machineRepair->total_downtime);
  //     $addValue->downtime = $DowntimeController->downtimeTranslator($total);
  //   }

  //   // Kirim data ke view
  //   return view('maintenance.dashboard-repair.history', [
  //     'machines' => $machines,
  //     'machineRepairs' => $machineRepairs,
  //     'jsMachineRepairs' => $jsMachineRepairs,
  //     'monthlyDowntime' => $monthlyDowntime,
  //     'totalMachineRepairs' => $totalMachineRepairs,
  //     'hoursMonthlyDowntime' => $hoursMonthlyDowntime,
  //   ]);
  // }




  // public function index(Request $request)
  // {
  //   $DowntimeController = (new DowntimeController());

  //   // Ambil rentang tanggal dari input, jika ada
  //   $minDate = $request->input('min');
  //   $maxDate = $request->input('max');

  //   // Menyiapkan query dasar untuk mengambil data MachineRepair
  //   $query = MachineRepair::where('keterangan', 'history') // Filter keterangan
  //     ->orderBy('tgl_input', 'desc')
  //     ->orderBy('id', 'desc');

  //   // Jika ada input rentang tanggal, filter data berdasarkan tanggal tersebut
  //   if ($minDate && $maxDate) {
  //     $query->whereBetween('tgl_kerusakan', [$minDate, $maxDate]);
  //   }

  //   // Ambil data machine repairs berdasarkan query yang telah difilter
  //   $machineRepairs = $query->get();

  //   // Ambil data mesin dengan status 'Stop' untuk downtime
  //   $jsMachineRepairs = MachineRepair::where('status_aktifitas', 'Stop')
  //     ->where('keterangan', 'history')
  //     ->get([
  //       'id',
  //       'start_downtime',
  //       'current_downtime',
  //       'current_monthly_downtime',
  //       'total_monthly_downtime',
  //       'total_downtime',
  //       'downtime_month',
  //       'status_mesin',
  //       'status_aktifitas'
  //     ]);

  //   // Hitung total mesin dengan status 'Stop'
  //   $totalMachineRepairs = MachineRepair::where('status_aktifitas', 'Stop')
  //     ->where('keterangan', 'history')
  //     ->count();

  //   // Ambil data mesin
  //   $machines = Machine::all();

  //   // Total downtime bulanan
  //   $monthlyDowntime = $DowntimeController->totalMonthlyDowntime();
  //   $monthlyDowntimeToHours = $DowntimeController->totalMonthlyDowntime(false, true);
  //   $hoursMonthlyDowntime = $DowntimeController->downtimeHoursTranslator($monthlyDowntimeToHours);

  //   // Update downtime untuk setiap machine repair
  //   foreach ($machineRepairs as $machineRepair) {
  //     $addValue = $machineRepairs->find($machineRepair->id);
  //     $addValue->search = Carbon::parse($machineRepair->tgl_kerusakan)->toDateString();
  //     $total = $DowntimeController->addDowntimeByDowntime($machineRepair->current_downtime, $machineRepair->total_downtime);
  //     $addValue->downtime = $DowntimeController->downtimeTranslator($total);
  //   }

  //   // Kirim data ke view
  //   return view('maintenance.dashboard-repair.history', [
  //     'machines' => $machines,
  //     'machineRepairs' => $machineRepairs,
  //     'jsMachineRepairs' => $jsMachineRepairs,
  //     'monthlyDowntime' => $monthlyDowntime,
  //     'totalMachineRepairs' => $totalMachineRepairs,
  //     'hoursMonthlyDowntime' => $hoursMonthlyDowntime,
  //   ]);
  // }




  // public function index()
  // {
  //   $DowntimeController = (new DowntimeController());

  //   // Mengambil semua data termasuk yang memiliki status 'OK Repair (Finish)'
  //   $machineRepairs = MachineRepair::where('keterangan', 'history') // Filter keterangan
  //     ->orderBy('tgl_input', 'desc')
  //     ->orderBy('id', 'desc')
  //     ->paginate(10);

  //   $jsMachineRepairs = MachineRepair::where('status_aktifitas', 'Stop')
  //     ->where('keterangan', 'history') // Filter keterangan
  //     ->get([
  //       'id',
  //       'start_downtime',
  //       'current_downtime',
  //       'current_monthly_downtime',
  //       'total_monthly_downtime',
  //       'total_downtime',
  //       'downtime_month',
  //       'status_mesin',
  //       'status_aktifitas'
  //     ]);

  //   $totalMachineRepairs = MachineRepair::where('status_aktifitas', 'Stop')
  //     ->where('keterangan', 'history') // Filter keterangan
  //     ->count();

  //   $machines = Machine::all();
  //   $monthlyDowntime = $DowntimeController->totalMonthlyDowntime();
  //   $monthlyDowntimeToHours = $DowntimeController->totalMonthlyDowntime(false, true);
  //   $hoursMonthlyDowntime = $DowntimeController->downtimeHoursTranslator($monthlyDowntimeToHours);

  //   foreach ($machineRepairs as $machineRepair) {
  //     $addValue = $machineRepairs->find($machineRepair->id);
  //     $addValue->search = Carbon::parse($machineRepair->tgl_kerusakan)->toDateString();
  //     $total = $DowntimeController->addDowntimeByDowntime($machineRepair->current_downtime, $machineRepair->total_downtime);
  //     $addValue->downtime = $DowntimeController->downtimeTranslator($total);
  //   }

  //   return view('maintenance.dashboard-repair.history', [
  //     'machines' => $machines,
  //     'machineRepairs' => $machineRepairs,
  //     'jsMachineRepairs' => $jsMachineRepairs,
  //     'monthlyDowntime' => $monthlyDowntime,
  //     'totalMachineRepairs' => $totalMachineRepairs,
  //     'hoursMonthlyDowntime' => $hoursMonthlyDowntime,
  //   ]);
  // }

  // public function index(Request $request)
  // {
  //   $DowntimeController = (new DowntimeController());

  //   // Memulai query untuk mendapatkan data mesin repair
  //   $query = MachineRepair::where('keterangan', 'history'); // Filter berdasarkan keterangan

  //   // Filter berdasarkan tanggal jika ada inputnya
  //   if ($request->has('min') && $request->has('max')) {
  //     $query->whereBetween('tgl_kerusakan', [
  //       Carbon::parse($request->input('min'))->startOfDay(),
  //       Carbon::parse($request->input('max'))->endOfDay()
  //     ]);
  //   }

  //   // Filter berdasarkan status_mesin jika ada inputnya
  //   if ($request->has('status')) {
  //     $query->where('status_mesin', $request->input('status'));
  //   }

  //   // Menambahkan paginasi setelah filter
  //   $machineRepairs = MachineRepair::where('keterangan', 'history')
  //     ->when(request('min'), function ($query) {
  //       return $query->where('tgl_kerusakan', '>=', request('min'));
  //     })
  //     ->when(request('max'), function ($query) {
  //       return $query->where('tgl_kerusakan', '<=', request('max'));
  //     })
  //     ->orderBy('tgl_input', 'desc')
  //     ->orderBy('id', 'desc')
  //     ->paginate(10);


  //   // Mengambil data mesin yang status aktifitasnya 'Stop' untuk digunakan pada JavaScript
  //   $jsMachineRepairs = MachineRepair::where('status_aktifitas', 'Stop')
  //     ->where('keterangan', 'history')
  //     ->get([
  //       'id',
  //       'start_downtime',
  //       'current_downtime',
  //       'current_monthly_downtime',
  //       'total_monthly_downtime',
  //       'total_downtime',
  //       'downtime_month',
  //       'status_mesin',
  //       'status_aktifitas'
  //     ]);

  //   // Menghitung total mesin yang membutuhkan perbaikan
  //   $totalMachineRepairs = MachineRepair::where('status_aktifitas', 'Stop')
  //     ->where('keterangan', 'history')
  //     ->count();

  //   // Mengambil data mesin untuk keperluan lainnya
  //   $machines = Machine::all();
  //   $monthlyDowntime = $DowntimeController->totalMonthlyDowntime();
  //   $monthlyDowntimeToHours = $DowntimeController->totalMonthlyDowntime(false, true);
  //   $hoursMonthlyDowntime = $DowntimeController->downtimeHoursTranslator($monthlyDowntimeToHours);

  //   // Menambahkan data downtime ke dalam setiap mesin yang diperbaiki
  //   foreach ($machineRepairs as $machineRepair) {
  //     $addValue = $machineRepairs->find($machineRepair->id);
  //     $addValue->search = Carbon::parse($machineRepair->tgl_kerusakan)->toDateString();
  //     $total = $DowntimeController->addDowntimeByDowntime($machineRepair->current_downtime, $machineRepair->total_downtime);
  //     $addValue->downtime = $DowntimeController->downtimeTranslator($total);
  //   }

  //   return view('maintenance.dashboard-repair.history', [
  //     'machines' => $machines,
  //     'machineRepairs' => $machineRepairs,
  //     'jsMachineRepairs' => $jsMachineRepairs,
  //     'monthlyDowntime' => $monthlyDowntime,
  //     'totalMachineRepairs' => $totalMachineRepairs,
  //     'hoursMonthlyDowntime' => $hoursMonthlyDowntime,
  //   ]);
  // }


}
