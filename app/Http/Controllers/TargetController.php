<?php

namespace App\Http\Controllers;

use App\Models\HistoryQuality;
use App\Models\Target;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TargetController extends Controller
{
    public function moneyFormat($amount) {
        return '$' . number_format($amount, 2);
    }


    public function index() {
        $now = Carbon::now();
        $monthNow = $now->format('m');
        $yearNow = $now->format('Y');

        $targetQuality = HistoryQuality::whereMonth('date', $monthNow)->whereYear('date', $yearNow)->get([
            'target_cam_ipqc', 'target_cnc_ipqc', 'target_mfg_ipqc',
            'target_cam_oqc', 'target_cnc_oqc', 'target_mfg_oqc',
        ])->first();
        $target = Target::first();
        $target->target_sales = $this->moneyFormat($target->target_qmp);
        return view('target.index', [
            'targetQuality' => $targetQuality,
            'target' => $target,
        ]);
    }

    public function updateQuality(Request $request) {
        $now = Carbon::now();
        $monthNow = $now->format('m');
        $yearNow = $now->format('Y');
        $data = $request->except(['_token', '_method']);
        $historyQuality = HistoryQuality::whereMonth('date', $monthNow)->whereYear('date', $yearNow)->first();
        $historyQuality->update($data);
        return redirect('/target')->with('success', 'Data Target Quality Berhasil Diubah!');
    }

    public function updateMaintenance(Request $request, Target $target) {
        $data = $request->only('target_maintenance');
        $target->find($request->id)->update($data);
        return redirect('/target')->with('success', 'Data Target Maintenance Berhasil Diubah!');
    }

    public function updateSales(Request $request, Target $target) {
        $data = $request->only('target_qmp');
        $target->find($request->id)->update($data);
        return redirect('/target')->with('success', 'Data Target QMP Sales Berhasil Diubah!');
    }
}
