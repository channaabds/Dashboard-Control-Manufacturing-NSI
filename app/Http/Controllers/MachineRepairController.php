<?php

namespace App\Http\Controllers;

use App\Exports\MachineRepairsExport;
use App\Models\MachineRepair;
use App\Http\Requests\StoreMachineRepairRequest;
use App\Http\Requests\UpdateMachineRepairRequest;
use App\Models\Machine;
use App\Models\TotalDowntime;
use Illuminate\Http\Request;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class MachineRepairController extends Controller
{
    // fungsi ini akan merubah downtime ke bentuk yang mudah dibaca
    // 0:0:0:0 -> 0 Hari 0 Jam 0 Menit 0 Detik
    public function downtimeTranslator($downtime) {
        $downtimeParts = explode(':', $downtime);
        $days = $downtimeParts[0];
        $hours = $downtimeParts[1];
        $minutes = $downtimeParts[2];
        $seconds = $downtimeParts[3];

        return $days . ' Hari </br>' . $hours . ' Jam </br>' . $minutes .  ' Menit </br>' . $seconds . ' Detik';
    }

    // function untuk menambahkan antara 2 downtime yang memiliki format '0:0:0:0'
    public function addDowntimeByDowntime($firstDowntime, $secDowntime) {
        $firstDowntimeParts = explode(':', $firstDowntime);
        $secDowntimeParts = explode(':', $secDowntime);

        $firstDowntimeDays = intval($firstDowntimeParts[0]);
        $firstDowntimeHours = intval($firstDowntimeParts[1]);
        $firstDowntimeMinutes = intval($firstDowntimeParts[2]);
        $firstDowntimeSeconds = intval($firstDowntimeParts[3]);

        $secDowntimeDays = intval($secDowntimeParts[0]);
        $secDowntimeHours = intval($secDowntimeParts[1]);
        $secDowntimeMinutes = intval($secDowntimeParts[2]);
        $secDowntimeSeconds = intval($secDowntimeParts[3]);

        $totalSeconds = (($firstDowntimeDays * 86400) + ($firstDowntimeHours * 3600) + ($firstDowntimeMinutes * 60) + $firstDowntimeSeconds) + (($secDowntimeDays * 86400) + ($secDowntimeHours * 3600) + ($secDowntimeMinutes * 60) + $secDowntimeSeconds);

        $days = floor($totalSeconds / 86400);
        $totalSeconds %= 86400;
        $hours = floor($totalSeconds / 3600);
        $totalSeconds %= 3600;
        $minutes = floor($totalSeconds / 60);
        $seconds = $totalSeconds %  60;

        $result = "$days:$hours:$minutes:$seconds";
        return $result;
    }

    // function untuk mendapatkan interval antara waktu strat downtime dan waktu sekarang ini (current downtime)
    public function getInterval($startDowntime, $now) {
        $start = Carbon::parse($startDowntime);
        $result = $start->diff($now)->format('%a:%h:%i:%s');
        return $result;
    }

    // function yang akan menyimpan downtime dari kolom current_downtime atau prod_downtime yang sudah dijumlahkan dengan nilai pada kolom total_downtime sebelumnya ke kolom total_downtime dan mereset current_downtime ke '0:0:0:0'
    public function saveCurrentOrProdToTotalDowntime($id) {
        $machineRepair = MachineRepair::find($id);
        $now = Carbon::now();
        if ($machineRepair->status_mesin == 'Stop by Prod') {
            $currentProd = $this->getInterval($machineRepair->start_downtime, $now);
            $machineRepair->prod_downtime = $currentProd;
            $machineRepair->save();
        } else {
            $currentDowntime = $this->getInterval($machineRepair->start_downtime, $now);
            $currentMonthly = $this->getInterval($machineRepair->start_monthly_downtime, $now);

            $totalDowntime = $machineRepair->total_downtime;
            $totalMonthly = $machineRepair->total_monthly_downtime;

            $machineRepair->total_downtime = $this->addDowntimeByDowntime($currentDowntime, $totalDowntime);
            $machineRepair->total_monthly_downtime = $this->addDowntimeByDowntime($currentMonthly, $totalMonthly);

            $machineRepair->current_downtime = '0:0:0:0';
            $machineRepair->current_monthly_downtime = '0:0:0:0';
            $machineRepair->save();
        }
    }

    // function ini berfungsi untuk mengupdate kolom start_downtime menjadi waktu sekarang ini
    public function updateStartDowntime($id) {
        $now = Carbon::now();
        $machineRepair = MachineRepair::find($id);
        $machineRepair->start_downtime = $now;
        $machineRepair->start_monthly_downtime = $now;
        $machineRepair->save();
    }

    public function totalMonthlyDowntime() {
        $now = Carbon::now();
        $monthNow = $now->format('m');
        $yearNow = $now->format('Y');
        $machineRepairs = MachineRepair::whereMonth('downtime_month', "$monthNow")->whereYear('downtime_month', "$yearNow")
                        ->get(['start_monthly_downtime', 'status_mesin', 'status_aktifitas', 'current_monthly_downtime', 'total_monthly_downtime']);

        $totalDowntime = '0:0:0:0';
        $downtime = '0:0:0:0';
        foreach ($machineRepairs as $machineRepair) {
            if ($machineRepair->status_mesin == "OK Repair (Finish)") {
                $downtime = $machineRepair->total_monthly_downtime;
            }

            if ($machineRepair->status_mesin != "Ok Repair (Finish)") {
                if ($machineRepair->status_aktifitas == "Stop") {
                    $downtime = $this->addDowntimeByDowntime($machineRepair->current_monthly_downtime, $machineRepair->total_monthly_downtime);
                } else {
                    $downtime = $machineRepair->total_monthly_downtime;
                }
            }

            if ($machineRepair->status_mesin == "Stop by Prod") {
                $downtime = '0:0:0:0';
            }

            $totalDowntime = $this->addDowntimeByDowntime($totalDowntime, $downtime);
        }

        $result = $this->downtimeTranslator($totalDowntime);

        return $result;
    }

    public function getTotalDowntime(Request $request) {
        $monthNow = Carbon::now()->format('F Y');

        if ($request->filter == $monthNow) {
            $totalDowntime = $this->totalMonthlyDowntime();
            return $totalDowntime;
        } else {
            $parseRequest = Carbon::parse($request->filter)->format('m Y');
            $monthParts = explode(" ", $parseRequest);
            $month = $monthParts[0];
            $year = $monthParts[1];
            $totalDowntimeDB = TotalDowntime::whereMonth('bulan_downtime', "$month")->whereYear('bulan_downtime', "$year")->get('total_downtime');
            $totalDowntime = $this->downtimeTranslator($totalDowntimeDB[0]->total_downtime);
            return $totalDowntime;
        }
        return ["filter" => $request->filter, "bulanSekarang" => $monthNow];
    }

    // function ini yang menangani ajax request dari halaman dashboard, dan berfungsi sebagai fitur realtime downtime counter dan auto update downtime ke database
    // fungsi realtime menerima data dari view dan tidak melakukan query di fungsinya dengan tujuan mengurangi query ke database supaya lebih efisien
    public function downtime(Request $request) {
        $machineRepairs = $request->data;
        $now = Carbon::now();
        $result = [];
        foreach ($machineRepairs as $machineRepair) {
            if ($machineRepair['status_mesin'] == 'Stop by Prod') {
                $interval = $this->getInterval($machineRepair['start_downtime'], $now);
                $result[$machineRepair['id']] = $interval;
            } else {
                $interval = $this->getInterval($machineRepair['start_downtime'], $now);
                $total = $this->addDowntimeByDowntime($machineRepair['prod_downtime'], $machineRepair['total_downtime']);
                $result[$machineRepair['id']] = $this->addDowntimeByDowntime($interval, $total);
            }
        }
        return $result;
    }

    public function index()
    {
        $machineRepairs = MachineRepair::whereNotIn('status_mesin', ['OK Repair (Finish)'])->orderBy('tgl_input', 'desc')->orderBy('id', 'desc')->get();
        $jsMachineRepairs = MachineRepair::whereNotIn('status_mesin', ['OK Repair (Finish)'])
                            ->where('status_aktifitas', 'Stop')
                            ->get([
                                'id', 'start_downtime', 'current_downtime', 'prod_downtime',
                                'total_downtime', 'current_monthly_downtime', 'total_monthly_downtime',
                                'downtime_month', 'status_mesin', 'status_aktifitas'
                            ]);
        $totalMachineRepairs = MachineRepair::whereNotIn('status_mesin', ['OK Repair (Finish)'])->where('status_aktifitas', 'Stop')->count();
        $machines = Machine:: all();
        $monthlyDowntime = $this->totalMonthlyDowntime();
        foreach ($machineRepairs as $machineRepair) {
            $addValue = $machineRepairs->find($machineRepair->id);
            $addValue->search = Carbon::parse($machineRepair->tgl_kerusakan)->toDateString();
            $addValue->downtime = $this->downtimeTranslator($this->addDowntimeByDowntime($machineRepair->prod_downtime, $machineRepair->total_downtime));
        }
        return view('dashboard-repair.index', [
            'machines' => $machines,
            'machineRepairs' => $machineRepairs,
            'jsMachineRepairs' => $jsMachineRepairs,
            'monthlyDowntime' => $monthlyDowntime,
            'totalMachineRepairs' => $totalMachineRepairs,
        ]);
    }

    public function store(StoreMachineRepairRequest $request)
    {
        $request->validate([
            'noMesin' => 'required|exists:machines,no_mesin',
            'request' => 'required',
            'status_mesin' => 'required',
            'status_aktifitas' => 'required',
        ]);

        $now = Carbon::now();
        $dataPayload = $request->except(['_token']);
        $machine = Machine::where('no_mesin', $dataPayload['noMesin'])->get('id')->first();

        if ($dataPayload['tgl_kerusakan'] === null) {
            $dataPayload['tgl_kerusakan'] = $now;
        }

        $startDowntime = $dataPayload['tgl_kerusakan'];

        $downtime = '0:0:0:0';
        $defaultDowntime = $downtime;
        $start = Carbon::parse($startDowntime);

        $addExtraData = [];
        $extraData = [
            'mesin_id' => $machine->id,
            'start_downtime' => $startDowntime,
            'start_monthly_downtime' => $startDowntime,
            'downtime_month' => $now->format('Y-m-d'),
        ];

        if ($dataPayload['status_mesin'] == 'OK Repair (Finish)') {
            if ($dataPayload['finish'] !== null) {
                $end = Carbon::parse($dataPayload['finish']);
                $downtime = $start->diff($end)->format('%a:%h:%i:%s');
                $addExtraData = [
                    'current_downtime' => $defaultDowntime,
                    'prod_downtime' => $defaultDowntime,
                    'total_downtime' => $downtime,
                    'current_monthly_downtime' => $defaultDowntime,
                    'total_monthly_downtime' => $downtime,
                ];
            } else {
                $end = $now;
                $downtime = $start->diff($end)->format('%a:%h:%i:%s');
                $addExtraData = [
                    'current_downtime' => $defaultDowntime,
                    'prod_downtime' => $defaultDowntime,
                    'total_downtime' => $downtime,
                    'current_monthly_downtime' => $defaultDowntime,
                    'total_monthly_downtime' => $downtime,
                ];
            }
        } else if ($dataPayload['status_mesin'] == 'Stop by Prod') {
            $downtime = $start->diff($now)->format('%a:%h:%i:%s');
            $addExtraData = [
                'current_downtime' => $defaultDowntime,
                'prod_downtime' => $downtime,
                'total_downtime' => $defaultDowntime,
                'current_monthly_downtime' => $defaultDowntime,
                'total_monthly_downtime' => $defaultDowntime,
            ];
        } else {
            $downtime = $start->diff($now)->format('%a:%h:%i:%s');
            $addExtraData = [
                'current_downtime' => $downtime,
                'prod_downtime' => $defaultDowntime,
                'total_downtime' => $defaultDowntime,
                'current_monthly_downtime' => $downtime,
                'total_monthly_downtime' => $defaultDowntime,
            ];
        }


        $data = Arr::except($dataPayload, ['noMesin', 'finish']);
        $insertData = Arr::collapse([$extraData, $data, $addExtraData]);
        DB::table('machine_repairs')->insert($insertData);
        return redirect('/dashboard-repair')->with('success', 'Data Baru Berhasil Ditambahkan!');;
    }

    public function update(UpdateMachineRepairRequest $request, MachineRepair $machineRepair)
    {
        $data = $request->except(['_method', '_token']);
        $machineRepair = $machineRepair->find($data['id']);
        $machineStatusInDB = $machineRepair->status_mesin;
        $machineStatusInput = $data['status'];

        $machineActivityInDB = $machineRepair->status_aktifitas;
        $machineActivityInput = $data['aktivitas'];

        if ($machineActivityInDB == 'Stop' && $machineActivityInput == 'Stop') {
            // ketika mengupdate dari status stop by prod ke status lain
            if ($machineStatusInDB == 'Stop by Prod' && $machineStatusInput != 'Stop by Prod') {
                $this->saveCurrentOrProdToTotalDowntime($machineRepair->id);
                $this->updateStartDowntime($machineRepair->id);
            }
        }
        if ($machineActivityInDB == 'Stop' && $machineActivityInput == 'Running') {
            // downtime stop(pause) dari yang awalnya jalan
            $this->saveCurrentOrProdToTotalDowntime($machineRepair->id);
        }
        if ($machineActivityInDB == 'Running' && $machineActivityInput == 'Stop') {
            // downtime lanjut dari yang awalnya stop
            $this->updateStartDowntime($machineRepair->id);
        }
        if ($machineActivityInDB == 'Running' && $machineActivityInput == 'Running') {
            // downtime stop(pause) yang awalnya stop(pause)
            // tidak terjadi apa apa
        }

        if ($machineStatusInput == 'OK Repair (Finish)') {
            if ($machineActivityInDB == 'Stop') {
                $this->saveCurrentOrProdToTotalDowntime($machineRepair->id);
            }
            $machineRepair->tgl_finish = Carbon::now();
        }

        $machineRepair->kedatangan_prl = $data['kedatanganPrl'];
        $machineRepair->kedatangan_po = $data['kedatanganPo'];
        $machineRepair->tgl_kerusakan = $data['tanggalKerusakan'];
        $machineRepair->bagian_rusak = $data['bagianRusak'];
        $machineRepair->status_aktifitas = $data['aktivitas'];
        $machineRepair->status_mesin = $data['status'];
        $machineRepair->update($data);
        $machineRepair->save();
        return redirect('/dashboard-repair')->with('success', 'Data Mesin Rusak Berhasil Diubah!');
    }

    public function destroy(MachineRepair $machineRepair, $id)
    {
        $machineRepair = $machineRepair->find($id);
        $machineRepair->delete();
        return redirect('/dashboard-repair')->with('success', 'Data Mesin Sudah Dihapus!');
    }

    public function finish($id) {
        $machineRepair = MachineRepair::find($id);
        if ($machineRepair->status_aktifitas == 'Stop') {
            $this->saveCurrentOrProdToTotalDowntime($id);
        }
        $machineRepair->tgl_finish = Carbon::now();
        $machineRepair->status_mesin = 'OK Repair (Finish)';
        $machineRepair->save();
        return redirect('/dashboard-finish')->with('success', 'Kerja Bagus, Mesin Sudah Selesai Diperbaiki!');
    }

    public function export(Request $request) {
        $minDate = $request->min;
        $maxDate = $request->max;
        return (new MachineRepairsExport($minDate, $maxDate))->download('Mesin-rusak.xlsx');
    }
}
