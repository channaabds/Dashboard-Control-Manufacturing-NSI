<?php

namespace App\Http\Controllers;

use App\Exports\MachineFinishExport;
use App\Models\MachineRepair;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MachineFinishController extends Controller
{
    public function index()
    {
        $machineFinishes = MachineRepair::where('status_mesin', 'OK Repair (Finish)')->orderBy('tgl_input', 'desc')->orderBy('id', 'desc')->get();
        $MachineRepair = (new MachineRepairController());

        foreach ($machineFinishes as $machineFinish) {
            $addValue = $machineFinishes->find($machineFinish->id);
            $addValue->search = Carbon::parse($machineFinish->tgl_kerusakan)->toDateString();
            $prodWrAndMtcWr = $MachineRepair->addDowntimeByDowntime($machineFinish->prod_waiting_repair_dt, $machineFinish->mtc_waiting_repair_dt);
            $prodWsAndMtcWs = $MachineRepair->addDowntimeByDowntime($machineFinish->prod_waiting_sparepart_dt, $machineFinish->mtc_waiting_sparepart_dt);
            $prodOrAndMtcOr = $MachineRepair->addDowntimeByDowntime($machineFinish->prod_on_repair_dt, $machineFinish->mtc_on_repair_dt);
            $resultWrAndWs = $MachineRepair->addDowntimeByDowntime($prodWrAndMtcWr, $prodWsAndMtcWs);
            $total = $MachineRepair->addDowntimeByDowntime($resultWrAndWs, $prodOrAndMtcOr);
            $addValue->downtime = $MachineRepair->downtimeTranslator($total);
        }

        return view('maintenance.dashboard-finish.index', [
            'machineFinishes' => $machineFinishes,
        ]);
    }

    public function destroy($id)
    {
        MachineRepair::find($id)->delete();
        return redirect('/maintenance/dashboard-finish')->with('success', 'Data Mesin Berhasil Dihapus!');
    }

    public function export(Request $request) {
        $minDate = $request->min;
        $maxDate = $request->max;
        return (new MachineFinishExport($minDate, $maxDate))->download('Mesin-finish.xlsx');
    }
}
