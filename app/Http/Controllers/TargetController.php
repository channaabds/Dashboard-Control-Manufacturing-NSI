<?php

namespace App\Http\Controllers;

use App\Models\HistoryQuality;
use App\Models\Target;
use App\Models\TargetMaintenance;
use App\Models\TargetSales;
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

        $targetMaintenance = TargetMaintenance::first();
        if ($targetMaintenance === null) {
            $targetMaintenance = new TargetMaintenance();
        }

        $targetQuality = HistoryQuality::whereMonth('date', $monthNow)->whereYear('date', $yearNow)->get([
            'target_cam_ipqc', 'target_cnc_ipqc', 'target_mfg_ipqc',
            'target_cam_oqc', 'target_cnc_oqc', 'target_mfg_oqc',
        ])->first();
        if ($targetQuality === null) {
            $targetQuality = new HistoryQuality();
        }

        $targetSales = TargetSales::whereYear('tahun', $yearNow)->first();
        $targetSalesShow = TargetSales::whereYear('tahun', $yearNow)->first();
        if ($targetSales === null || $targetSalesShow === null) {
            $targetSales = new TargetSales();
            $targetSalesShow = new TargetSales();
        }

        $bulanList = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];

        foreach ($bulanList as $bulan) {
            $targetSalesShow->$bulan = $this->moneyFormat($targetSalesShow->$bulan);
        }

        return view('target.index', [
            'targetQuality' => $targetQuality,
            'targetMaintenance' => $targetMaintenance,
            'targetSalesShow' => $targetSalesShow,
            'targetSales' => $targetSales,
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

    public function updateSales(Request $request, TargetSales $targetSales) {
        $now = Carbon::now();
        $yearNow = $now->format('Y');
        $tahun = $now->format('Y-m-d');
        $data = $request->except(['_token', '_method']);
        $target = $targetSales::whereYear('tahun', $yearNow)->first();
        if ($target === null) {
            $data['tahun'] = $tahun;
            $targetSales::create($data);
        } else {
            $target->update($data);
        }
        return redirect('/target')->with('success', 'Data Target QMP Sales Berhasil Diubah!');
    }
}
