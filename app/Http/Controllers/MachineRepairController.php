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
    public function saveCurrentToTotalDowntime($id) {
        $machineRepair = MachineRepair::find($id);
        $now = Carbon::now();
        $interval = $this->getInterval($machineRepair->start_downtime, $now);
        $intervalMonthly = $this->getInterval($machineRepair->start_monthly_downtime, $now);

        $totalDowntime = $machineRepair->total_downtime;
        $totalMonthly = $machineRepair->total_monthly_downtime;

        $machineRepair->total_monthly_downtime = $this->addDowntimeByDowntime($intervalMonthly, $totalMonthly);
        $machineRepair->total_downtime = $this->addDowntimeByDowntime($interval, $totalDowntime);

        $machineRepair->current_monthly_downtime = '0:0:0:0';
        $machineRepair->current_downtime = '0:0:0:0';

        $machineRepair->save();
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
            } else {
                if ($machineRepair->status_aktifitas == "Stop") {
                    $monthlyInterval = $this->getInterval($machineRepair->start_monthly_downtime, $now);
                    $downtime = $this->addDowntimeByDowntime($monthlyInterval, $machineRepair->total_monthly_downtime);
                } else {
                    $downtime = $machineRepair->total_monthly_downtime;
                }
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
            $interval = $this->getInterval($machineRepair['start_downtime'], $now);
            $total = $this->addDowntimeByDowntime($interval, $machineRepair['total_downtime']);
            $result[$machineRepair['id']] = $total;
        }
        return $result;
    }

    public function index()
    {
        $machineRepairs = MachineRepair::whereNotIn('status_mesin', ['OK Repair (Finish)'])
                            ->orderBy('tgl_input', 'desc')->orderBy('id', 'desc')->get();
        $jsMachineRepairs = MachineRepair::whereNotIn('status_mesin', ['OK Repair (Finish)'])
                            ->where('status_aktifitas', 'Stop')
                            ->get([
                                'id', 'start_downtime', 'current_downtime', 'current_monthly_downtime',
                                'total_monthly_downtime', 'total_downtime',
                                'downtime_month', 'status_mesin', 'status_aktifitas'
                            ]);
        $totalMachineRepairs = MachineRepair::whereNotIn('status_mesin', ['OK Repair (Finish)'])
                                ->where('status_aktifitas', 'Stop')->count();
        $machines = Machine:: all();
        $monthlyDowntime = $this->totalMonthlyDowntime();
        foreach ($machineRepairs as $machineRepair) {
            $addValue = $machineRepairs->find($machineRepair->id);
            $addValue->search = Carbon::parse($machineRepair->tgl_kerusakan)->toDateString();
            $total = $this->addDowntimeByDowntime($machineRepair->current_downtime, $machineRepair->total_downtime);
            $addValue->downtime = $this->downtimeTranslator($total);
        }
        return view('maintenance.dashboard-repair.index', [
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
        $dataPayload = $request->except(['_token', 'stopByProd']);
        $machine = Machine::where('no_mesin', $dataPayload['noMesin'])->get('id')->first();

        if ($dataPayload['tgl_kerusakan'] === null) {
            $dataPayload['tgl_kerusakan'] = $now;
        }

        $startDowntime = $dataPayload['tgl_kerusakan'];

        $start = Carbon::parse($startDowntime);

        $addExtraData = [];
        $extraData = [
            'mesin_id' => $machine->id,
            'start_downtime' => $startDowntime,
            'start_monthly_downtime' => $startDowntime,
        ];

        if ($dataPayload['status_mesin'] == 'OK Repair (Finish)') {
            if ($dataPayload['finish'] !== null) {
                $end = Carbon::parse($dataPayload['finish']);
                $downtime = $start->diff($end)->format('%a:%h:%i:%s');
                $addExtraData = [
                    'total_downtime' => $downtime,
                    'total_monthly_downtime' => $downtime,
                    'tgl_finish' => Carbon::create($dataPayload['finish']),
                ];
            } else {
                $downtime = $start->diff($now)->format('%a:%h:%i:%s');
                $addExtraData = [
                    'total_downtime' => $downtime,
                    'total_monthly_downtime' => $downtime,
                    'tgl_finish' => Carbon::now(),
                ];
            }
        } else {
            $downtime = $start->diff($now)->format('%a:%h:%i:%s');
            if ($dataPayload['status_aktifitas'] == 'Running') {
                $addExtraData = [
                    'total_downtime' => $downtime,
                    'total_monthly_downtime' => $downtime,
                ];
            } else {
                $addExtraData = [
                    'current_downtime' => $downtime,
                    'current_monthly_downtime' => $downtime,
                ];
            }
        }

        $data = Arr::except($dataPayload, ['noMesin', 'finish']);
        $insertData = Arr::collapse([$extraData, $data, $addExtraData]);
        DB::table('machine_repairs')->insert($insertData);
        return redirect('/maintenance/dashboard-repair')->with('success', 'Data Baru Berhasil Ditambahkan!');;
    }

    public function update(UpdateMachineRepairRequest $request, MachineRepair $machineRepair)
    {
        $data = $request->except(['_method', '_token']);
        $machineRepair = $machineRepair->find($data['id']);

        $machineStatusInput = $data['status'];
        $machineActivityInDB = $machineRepair->status_aktifitas;
        $machineActivityInput = $data['aktivitas'];

        if ($machineActivityInDB == 'Stop' && $machineActivityInput == 'Stop') {
            // downtime jalan yang awalnya jalan
            // tidak terjadi apa apa
        }
        if ($machineActivityInDB == 'Stop' && $machineActivityInput == 'Running') {
            // downtime stop(pause) dari yang awalnya jalan
            if ($machineStatusInput != 'OK Repair (Finish)') {
                $this->saveCurrentToTotalDowntime($machineRepair->id);
            }
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
                $this->saveCurrentToTotalDowntime($machineRepair->id);
            }
            $machineRepair->tgl_finish = Carbon::now();
        }

        $machineRepair->status_aktifitas = $data['aktivitas'];
        $machineRepair->status_mesin = $data['status'];
        $machineRepair->update($data);
        $machineRepair->save();
        return redirect('/maintenance/dashboard-repair')->with('success', 'Data Mesin Rusak Berhasil Diubah!');
    }

    public function destroy(MachineRepair $machineRepair, $id)
    {
        $machineRepair = $machineRepair->find($id);
        $machineRepair->delete();
        return redirect('/maintenance/dashboard-repair')->with('success', 'Data Mesin Sudah Dihapus!');
    }

    public function export(Request $request) {
        $minDate = $request->min;
        $maxDate = $request->max;
        return (new MachineRepairsExport($minDate, $maxDate))->download('Mesin-rusak.xlsx');
    }
}
