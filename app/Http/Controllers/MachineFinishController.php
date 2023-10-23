<?php

namespace App\Http\Controllers;

use App\Models\MachineRepair;
use Carbon\Carbon;

class MachineFinishController extends Controller
{
    public function index()
    {
        $machineFinishes = MachineRepair::where('status_mesin', 'OK Repair (Finish)')->orderBy('tgl_input', 'desc')->orderBy('id', 'desc')->get();
        $DowntimeController = (new DowntimeController());

        foreach ($machineFinishes as $machineFinish) {
            $addValue = $machineFinishes->find($machineFinish->id);
            $addValue->search = Carbon::parse($machineFinish->tgl_kerusakan)->toDateString();
            $total = $DowntimeController->addDowntimeByDowntime($machineFinish->current_downtime, $machineFinish->total_downtime);
            $addValue->downtime = $DowntimeController->downtimeTranslator($total);
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
}
