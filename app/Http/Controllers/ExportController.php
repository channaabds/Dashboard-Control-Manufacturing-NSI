<?php

namespace App\Http\Controllers;

use App\Exports\IpqcExport;
use App\Exports\MachineFinishExport;
use App\Exports\MachineRepairsExport;
use App\Exports\MachineRepairsExportHistory;
use App\Exports\MachinesWaitingSparepartExport;
use App\Exports\OqcExport;
use App\Exports\MesinExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class ExportController extends Controller
{
    public function exportMachineRepair(Request $request)
    {
        $minDate = $request->min;
        $maxDate = $request->max;
        return (new MachineRepairsExport($minDate, $maxDate))->download('Mesin-rusak.xlsx');
    }
    public function exportMachineRepairHistory(Request $request)
    {
        $minDate = $request->min;
        $maxDate = $request->max;
        return (new MachineRepairsExportHistory($minDate, $maxDate))->download('Mesin-rusak-history.xlsx');
    }

    public function exportMachineWaitingSparepart(Request $request)
    {
        $minDate = $request->min;
        $maxDate = $request->max;
        return (new MachinesWaitingSparepartExport($minDate, $maxDate))->download('Mesin-Waiting-Sparepart.xlsx');
    }

    public function exportMachineFinish(Request $request)
    {
        $minDate = $request->min;
        $maxDate = $request->max;
        return (new MachineFinishExport($minDate, $maxDate))->download('Mesin-finish.xlsx');
    }

    public function exportIpqc(Request $request)
    {
        $min = $request->min;
        $max = $request->max;
        return (new IpqcExport($min, $max))->download("data-ncr-ipqc-$min-$max.xlsx");
    }

    public function exportOqc(Request $request)
    {
        $min = $request->min;
        $max = $request->max;
        return (new OqcExport($min, $max))->download("data-ncr-oqc-$min-$max.xlsx");
    }
    public function exportMesin(Request $request)
    {
        return Excel::download(new MesinExport, 'data_mesin.xlsx');
    }
}
