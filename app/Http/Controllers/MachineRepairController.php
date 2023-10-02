<?php

namespace App\Http\Controllers;

use App\Models\MachineRepair;
use App\Http\Requests\StoreMachineRepairRequest;
use App\Http\Requests\UpdateMachineRepairRequest;
use App\Models\Machine;
use Illuminate\Http\Request;

use Carbon\Carbon;

class MachineRepairController extends Controller
{
    public function addCurrentWithTotalDowntime($currentDowntime, $totalDowntime) {
        $currentDowntimeParts = explode(':', $currentDowntime);
        $totalDowntimeParts = explode(':', $totalDowntime);

        $currentDowntimeDays = intval($currentDowntimeParts[0]);
        $currentDowntimeHours = intval($currentDowntimeParts[1]);
        $currentDowntimeMinutes = intval($currentDowntimeParts[2]);
        $currentDowntimeSeconds = intval($currentDowntimeParts[3]);

        $totalDowntimeDays = intval($totalDowntimeParts[0]);
        $totalDowntimeHours = intval($totalDowntimeParts[1]);
        $totalDowntimeMinutes = intval($totalDowntimeParts[2]);
        $totalDowntimeSeconds = intval($totalDowntimeParts[3]);

        $days = $currentDowntimeDays + $totalDowntimeDays;
        $hours = $currentDowntimeHours + $totalDowntimeHours;
        $minutes = $currentDowntimeMinutes + $totalDowntimeMinutes;
        $seconds = $currentDowntimeSeconds + $totalDowntimeSeconds;

        $result = "$days:$hours:$minutes:$seconds";
        return $result;
    }

    public function runDowntime($startDowntime, $totalDowntime) {
        $now = Carbon::now();
        $start = Carbon::parse($startDowntime);
        $currentDowntime = $start->diff($now)->format('%a:%h:%i:%s');
        $result = $this->addCurrentWithTotalDowntime($currentDowntime, $totalDowntime);
        return $result;
    }

    // public function stopDowntime(Request $request) {

    // }

    public function downtime(Request $request) {
        $data = $request->data;
        $result = [];
        foreach ($data as $d) {
            $result[$d['id']] = $this->runDowntime($d['start_downtime'], $d['total_downtime']);
        }
        return $result;
    }

    public function index()
    {
        $machinesRepair = MachineRepair::all();
        $jsMachinesRepair = MachineRepair::get(['id', 'start_downtime', 'current_downtime', 'total_downtime', 'month_downtime'])->toArray();
        $machines = Machine:: all();
        return view('dashboard.index', [
            'machines' => $machines,
            'machinesOnRepair' => $machinesRepair,
            'jsMachinesOnRepair' => $jsMachinesRepair,
        ]);
    }

    public function store(StoreMachineRepairRequest $request)
    {
        return 'hello world';
    }

    public function update(UpdateMachineRepairRequest $request, MachineRepair $machineRepair)
    {
        $data = $request->except(['_method', '_token']);
        if ($data['status'] == 'OK Repair (Finish)' && $data['aktivitas'] = 'Running') {
            return 'downtime stop';
            exit;
        }
        return 'downtime jalan';
        // return dd($data['status'], $data['aktivitas']);
        // $machine = $machineRepair->find($data['id']);
        // $machine->update($data);
        // return redirect('/dashboard')->with('success', 'Data Mesin Rusak Berhasil Diubah!');
    }

    public function destroy(MachineRepair $machineRepair)
    {
        //
    }
}
