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
        $machinesFinish = MachineRepair::where('status_mesin', 'OK Repair (Finish)')->orderBy('tgl_input', 'desc')->orderBy('id', 'desc')->get();
        $MachineRepair = (new MachineRepairController());

        foreach ($machinesFinish as $machineFinish) {
            $addValue = $machinesFinish->find($machineFinish->id);
            $addValue->search = Carbon::parse($machineFinish->tgl_kerusakan)->toDateString();
            $addValue->downtime = $MachineRepair->downtimeTranslator($MachineRepair->addDowntimeByDowntime($machineFinish->prod_downtime, $machineFinish->total_downtime));
        }

        return view('mesin-ok.index', [
            'machinesFinish' => $machinesFinish,
        ]);
    }

    public function destroy($id)
    {
        MachineRepair::find($id)->delete();
        return redirect('mesin-finish')->with('success', 'Data Mesin Berhasil Dihapus!');
    }

    public function export(Request $request) {
        $minDate = $request->min;
        $maxDate = $request->max;
        return (new MachineFinishExport($minDate, $maxDate))->download('Mesin-finish.xlsx');
    }
}
