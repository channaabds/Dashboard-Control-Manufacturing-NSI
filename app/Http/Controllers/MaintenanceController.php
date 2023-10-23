<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use App\Models\MachineRepair;
use App\Models\TotalDowntime;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class MaintenanceController extends Controller
{
    public function currentDowntime()
    {
        $DowntimeController = (new DowntimeController());
        return ["data" => [
            "downtime" =>$DowntimeController->totalMonthlyDowntime(true)
        ]];
    }

    public function lastDowntime()
    {
        $month = Carbon::now()->subMonth()->format('Y-m');
        $myRequest = new Request(['filter' => $month]);
        $DowntimeController = (new DowntimeController());
        return ["data" => [
            "downtime" =>$DowntimeController->getTotalDowntime($myRequest, true)
        ]];
    }

    public function beforeLastDowntime()
    {
        $month = Carbon::now()->subMonths(2)->format('Y-m');
        $myRequest = new Request(['filter' => $month]);
        $DowntimeController = (new DowntimeController());
        return ["data" => [
            "downtime" =>$DowntimeController->getTotalDowntime($myRequest, true)
        ]];
    }

    public function dataMachineRepairs() {
        $machineRepairs = MachineRepair::whereNotIn('status_mesin', ['OK Repair (Finish)'])
                            ->orderBy('tgl_input', 'desc')->orderBy('id', 'desc')->get([
                                'id', 'mesin_id', 'pic', 'status_aktifitas', 'status_mesin',
                                'tgl_kerusakan', 'current_downtime', 'total_downtime',
                            ]);
        $array = [];
        foreach ($machineRepairs as $machineRepair) {
            $machineId = $machineRepair->mesin_id;
            $machine = Machine::find($machineId);
            $data = $machineRepair->only([
                        'id', 'pic', 'status_aktifitas', 'status_mesin',
                        'tgl_kerusakan', 'current_downtime', 'total_downtime'
                    ]);
            $array[] = Arr::add($data, 'no_mesin', $machine->no_mesin);
        }
        return ["data" => ["machineRepairs" => $array]];
    }

    public function historyDowntimes() {
        $data = TotalDowntime::whereBetween('bulan_downtime',[
                                                                Carbon::now()->subMonths(11)->format('Y-m-d'),
                                                                Carbon::now()->format('Y-m-d')
                                                            ])
                                                            ->orderBy('bulan_downtime', 'desc')
                                                            ->get(['total_downtime', 'bulan_downtime']);
        $DowntimeController = (new DowntimeController());
        $downtimeNow = [
                        'total_downtime' => $DowntimeController->totalMonthlyDowntime(true, true),
                        'bulan_downtime' => Carbon::now()->format('Y-m-d'),
        ];
        $array = Arr::collapse([[$downtimeNow], $data]);
        return ["data" => ["historyDowntimes" => $array]];
    }
}
