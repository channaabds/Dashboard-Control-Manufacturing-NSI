<?php

namespace App\Http\Controllers;

use App\Exports\IpqcExport;
use App\Exports\MachineFinishExport;
use App\Exports\MachineRepairsExport;
use App\Exports\OqcExport;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function exportMachineRepair(Request $request) {
        $minDate = $request->min;
        $maxDate = $request->max;
        return (new MachineRepairsExport($minDate, $maxDate))->download('Mesin-rusak.xlsx');
    }

    public function exportMachineFinish(Request $request) {
        $minDate = $request->min;
        $maxDate = $request->max;
        return (new MachineFinishExport($minDate, $maxDate))->download('Mesin-finish.xlsx');
    }

    public function exportIpqc(Request $request) {
        $min = $request->min;
        $max = $request->max;
        return (new IpqcExport($min, $max))->download("data-ncr-ipqc-$min-$max.xlsx");
    }

    public function exportOqc(Request $request) {
        $min = $request->min;
        $max = $request->max;
        return (new OqcExport($min, $max))->download("data-ncr-oqc-$min-$max.xlsx");
    }
}
