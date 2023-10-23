<?php

namespace App\Http\Controllers;

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
    public function index()
    {
        $DowntimeController = (new DowntimeController());
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
        $monthlyDowntime = $DowntimeController->totalMonthlyDowntime();
        foreach ($machineRepairs as $machineRepair) {
            $addValue = $machineRepairs->find($machineRepair->id);
            $addValue->search = Carbon::parse($machineRepair->tgl_kerusakan)->toDateString();
            $total = $DowntimeController->addDowntimeByDowntime($machineRepair->current_downtime, $machineRepair->total_downtime);
            $addValue->downtime = $DowntimeController->downtimeTranslator($total);
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
        $DowntimeController = (new DowntimeController);

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
                $DowntimeController->saveCurrentToTotalDowntime($machineRepair->id);
            }
        }
        if ($machineActivityInDB == 'Running' && $machineActivityInput == 'Stop') {
            // downtime lanjut dari yang awalnya stop
            $DowntimeController->updateStartDowntime($machineRepair->id);
        }
        if ($machineActivityInDB == 'Running' && $machineActivityInput == 'Running') {
            // downtime stop(pause) yang awalnya stop(pause)
            // tidak terjadi apa apa
        }

        if ($machineStatusInput == 'OK Repair (Finish)') {
            if ($machineActivityInDB == 'Stop') {
                $DowntimeController->saveCurrentToTotalDowntime($machineRepair->id);
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
}
