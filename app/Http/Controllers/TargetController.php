<?php

namespace App\Http\Controllers;

use App\Models\HistoryQuality;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TargetController extends Controller
{
    public function index() {
        $now = Carbon::now();
        $monthNow = $now->format('m');
        $yearNow = $now->format('Y');

        $targetQuality = HistoryQuality::whereMonth('date', $monthNow)->whereYear('date', $yearNow)->get([
            'target_cam_ipqc', 'target_cnc_ipqc', 'target_mfg_ipqc',
            'target_cam_oqc', 'target_cnc_oqc', 'target_mfg_oqc',
        ])->first();
        return view('target.index', [
            'targetQuality' => $targetQuality,
        ]);
    }
}
