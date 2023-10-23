<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use App\Models\MachineRepair;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PurchasingController extends Controller
{

    public function indexDashboardRepair()
    {
        $MachineRepair = (new DowntimeController());
        $machineRepairs = MachineRepair::whereNotIn('status_mesin', ['OK Repair (Finish)'])
                            ->orderBy('tgl_input', 'desc')->orderBy('id', 'desc')->get();
        $jsMachineRepairs = MachineRepair::whereNotIn('status_mesin', ['OK Repair (Finish)'])
                            ->where('status_aktifitas', 'Stop')
                            ->get([
                                'id', 'start_downtime', 'current_downtime', 'current_monthly_downtime',
                                'total_monthly_downtime', 'total_downtime',
                                'downtime_month', 'status_mesin', 'status_aktifitas'
                            ]);
        $machines = Machine:: all();
        $monthlyDowntime = $MachineRepair->totalMonthlyDowntime();
        foreach ($machineRepairs as $machineRepair) {
            $addValue = $machineRepairs->find($machineRepair->id);
            $addValue->search = Carbon::parse($machineRepair->tgl_kerusakan)->toDateString();
            $total = $MachineRepair->addDowntimeByDowntime($machineRepair->current_downtime, $machineRepair->total_downtime);
            $addValue->downtime = $MachineRepair->downtimeTranslator($total);
        }
        return view('purchasing.dashboard-repair.index', [
            'machines' => $machines,
            'machineRepairs' => $machineRepairs,
            'jsMachineRepairs' => $jsMachineRepairs,
            'monthlyDowntime' => $monthlyDowntime,
        ]);
    }

    public function indexDashboardFinish()
    {
        $machineFinishes = MachineRepair::where('status_mesin', 'OK Repair (Finish)')->orderBy('tgl_input', 'desc')->orderBy('id', 'desc')->get();
        $MachineRepair = (new DowntimeController());

        foreach ($machineFinishes as $machineFinish) {
            $addValue = $machineFinishes->find($machineFinish->id);
            $addValue->search = Carbon::parse($machineFinish->tgl_kerusakan)->toDateString();
            $total = $MachineRepair->addDowntimeByDowntime($machineFinish->current_downtime, $machineFinish->total_downtime);
            $addValue->downtime = $MachineRepair->downtimeTranslator($total);
        }

        return view('purchasing.dashboard-finish.index', [
            'machineFinishes' => $machineFinishes,
        ]);
    }

    public function index()
    {
        $MachineRepair = (new DowntimeController());
        $machineRepairs = MachineRepair::whereNotIn('status_mesin', ['OK Repair (Finish)'])->where('status_mesin', 'Waiting Sparepart')
                            ->orderBy('tgl_input', 'desc')->orderBy('id', 'desc')->get();
        $jsMachineRepairs = MachineRepair::whereNotIn('status_mesin', ['OK Repair (Finish)'])
                            ->where('status_aktifitas', 'Stop')->where('status_mesin', 'Waiting Sparepart')
                            ->get([
                                'id', 'start_downtime', 'current_downtime', 'current_monthly_downtime',
                                'total_monthly_downtime', 'total_downtime',
                                'downtime_month', 'status_mesin', 'status_aktifitas'
                            ]);
        $machines = Machine:: all();
        $monthlyDowntime = $MachineRepair->totalMonthlyDowntime();
        foreach ($machineRepairs as $machineRepair) {
            $addValue = $machineRepairs->find($machineRepair->id);
            $addValue->search = Carbon::parse($machineRepair->tgl_kerusakan)->toDateString();
            $total = $MachineRepair->addDowntimeByDowntime($machineRepair->current_downtime, $machineRepair->total_downtime);
            $addValue->downtime = $MachineRepair->downtimeTranslator($total);
        }
        return view('purchasing.dashboard-waiting-sparepart.index', [
            'machines' => $machines,
            'machineRepairs' => $machineRepairs,
            'jsMachineRepairs' => $jsMachineRepairs,
            'monthlyDowntime' => $monthlyDowntime,
        ]);
    }

    public function update(Request $request, MachineRepair $machineRepair)
    {
        $data = $request->except(['_method', '_token']);
        $machineRepair = $machineRepair->find($data['id']);
        $machineRepair->update($data);
        $machineRepair->save();
        return redirect('/purchasing/dashboard-repair')->with('success', 'Data Mesin Rusak Berhasil Diubah!');
    }

    public function destroy(MachineRepair $machineRepair, $id)
    {
        $machineRepair = $machineRepair->find($id);
        $machineRepair->delete();
        return redirect('/purchasing/dashboard-repair')->with('success', 'Data Mesin Sudah Dihapus!');
    }
}
