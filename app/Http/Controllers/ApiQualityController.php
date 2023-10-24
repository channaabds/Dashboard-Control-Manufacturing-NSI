<?php

namespace App\Http\Controllers;

use App\Models\HistoryQuality;
use App\Models\Quality;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ApiQualityController extends Controller
{
    public function ipqc() {
        $now = Carbon::now();
        $monthNow = $now->format('m');
        $yearNow = $now->format('Y');
        $date = $now->format('m-Y');

        $historyQuality = HistoryQuality::where('date', $date)
            ->first([
                'target_cam_oqc',
                'target_cnc_oqc',
                'target_mfg_oqc',
            ])->toArray();

        $camIpqc = Quality::whereMonth('date', $monthNow)->whereYear('date', $yearNow)->where('departement', 'IPQC')->where('section', 'CAM')->count();
        $cncIpqc = Quality::whereMonth('date', $monthNow)->whereYear('date', $yearNow)->where('departement', 'IPQC')->where('section', 'CNC')->count();
        $mfgIpqc = Quality::whereMonth('date', $monthNow)->whereYear('date', $yearNow)->where('departement', 'IPQC')->where('section', 'MFG2')->count();

        $dataAktual = [
            'aktual_cam_ipqc' => $camIpqc,
            'aktual_cnc_ipqc' => $cncIpqc,
            'aktual_mfg_ipqc' => $mfgIpqc,
        ];

        $data = Arr::collapse([$historyQuality, $dataAktual]);

        return ["payload" => [
            "status_code" => 200,
            "data" => $data,
            "message" => "data aktual dan target IPQC",
            ]
        ];
    }

    public function oqc() {
        $now = Carbon::now();
        $monthNow = $now->format('m');
        $yearNow = $now->format('Y');
        $date = $now->format('m-Y');

        $historyQuality = HistoryQuality::where('date', $date)
            ->first([
                'target_cam_oqc',
                'target_cnc_oqc',
                'target_mfg_oqc',
            ])->toArray();

        $camOqc = Quality::whereMonth('date', $monthNow)->whereYear('date', $yearNow)->where('departement', 'OQC')->where('section', 'CAM')->count();
        $cncOqc = Quality::whereMonth('date', $monthNow)->whereYear('date', $yearNow)->where('departement', 'OQC')->where('section', 'CNC')->count();
        $mfgOqc = Quality::whereMonth('date', $monthNow)->whereYear('date', $yearNow)->where('departement', 'OQC')->where('section', 'MFG2')->count();

        $dataAktual = [
            'aktual_cam_oqc' => $camOqc,
            'aktual_cnc_oqc' => $cncOqc,
            'aktual_mfg_oqc' => $mfgOqc,
        ];

        $data = Arr::collapse([$historyQuality, $dataAktual]);

        return ["payload" => [
            "status_code" => 200,
            "data" => $data,
            "message" => "data aktual dan target OQC",
            ]
        ];
    }
}
