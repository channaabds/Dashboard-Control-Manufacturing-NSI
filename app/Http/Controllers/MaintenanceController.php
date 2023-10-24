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
        $downtimeFormat = $DowntimeController->totalMonthlyDowntime(true, true);
        $downtimeInSecond = $DowntimeController->downtimeToSeconds($downtimeFormat);
        return ["payload" => [
            "status_code" => 200,
            "data" => [
                    "downtime" => $DowntimeController->totalMonthlyDowntime(true),
                    "downtimeFormat" => $downtimeFormat,
                    "downtimeInSeconds" => $downtimeInSecond,
                ],
            "message" => "data downtime bulan ini",
            ]
        ];
    }

    public function lastDowntime()
    {
        $month = Carbon::now()->subMonth()->format('Y-m');
        $myRequest = new Request(['filter' => $month]);
        $DowntimeController = (new DowntimeController());
        $downtimeFormat = $DowntimeController->getTotalDowntime($myRequest, true, true);
        $downtimeInSecond = $DowntimeController->downtimeToSeconds($downtimeFormat);
        return ["payload" => [
            "status_code" => 200,
            "data" => [
                    "downtime" => $DowntimeController->getTotalDowntime($myRequest, true),
                    "downtimeFormat" => $downtimeFormat,
                    "downtimeInSeconds" => $downtimeInSecond,
                ],
            "message" => "data downtime satu bulan lalu",
            ]
        ];
    }

    public function beforeLastDowntime()
    {
        $month = Carbon::now()->subMonths(2)->format('Y-m');
        $myRequest = new Request(['filter' => $month]);
        $DowntimeController = (new DowntimeController());
        $downtimeFormat = $DowntimeController->getTotalDowntime($myRequest, true, true);
        $downtimeInSecond = $DowntimeController->downtimeToSeconds($downtimeFormat);
        return ["payload" => [
            "status_code" => 200,
            "data" => [
                    "downtime" => $DowntimeController->getTotalDowntime($myRequest, true),
                    "downtimeFormat" => $downtimeFormat,
                    "downtimeInSeconds" => $downtimeInSecond,
                ],
            "message" => "data downtime dua bulan lalu",
            ]
        ];
    }

    public function dataMachineRepairs() {
        $DowntimeController = (new DowntimeController());
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
                        'tgl_kerusakan',
                    ]);
            $totalDowntime = $DowntimeController->addDowntimeByDowntime($machineRepair->current_downtime, $machineRepair->total_downtime);
            $downtimeInSecond = $DowntimeController->downtimeToSeconds($totalDowntime);
            $array[] = Arr::collapse([$data, [
                                            'no_mesin' => $machine->no_mesin,
                                            'total_downtime' => $totalDowntime,
                                            'downtime_in_seconds' => $downtimeInSecond,
                                        ]
                                    ]);
        }

        return ["payload" => [
            "status_code" => 200,
            "data" => [
                    "machineRepairs" => $array
                ],
            "message" => "data downtime tiap mesin",
            ]
        ];
    }

    public function historyDowntimes() {
        $DowntimeController = (new DowntimeController());
        $data = TotalDowntime::whereBetween('bulan_downtime',[
                                                                Carbon::now()->subMonths(11)->format('Y-m-d'),
                                                                Carbon::now()->format('Y-m-d')
                                                            ])
                                                            ->orderBy('bulan_downtime', 'desc')
                                                            ->get(['total_downtime', 'bulan_downtime']);
        $newData = [];

        foreach ($data as $d) {
            $downtimeInSecond = $DowntimeController->downtimeToSeconds($d->total_downtime);
            $newData[] = Arr::add($d, 'downtime_in_seconds', $downtimeInSecond);
        }

        $totalDowntimeNow = $DowntimeController->totalMonthlyDowntime(true, true);
        $downtimeInSecond = $DowntimeController->downtimeToSeconds($totalDowntimeNow);
        $downtimeNow = [
                        'total_downtime' => $totalDowntimeNow,
                        'bulan_downtime' => Carbon::now()->format('Y-m-d'),
                        'downtime_in_seconds' => $downtimeInSecond,
        ];
        $array = Arr::collapse([[$downtimeNow], $newData]);

        return ["payload" => [
            "status_code" => 200,
            "data" => [
                    "historyDowntimes" => $array,
                ],
            "message" => "history downtime 11 bulan terakhir",
            ]
        ];
    }
}
