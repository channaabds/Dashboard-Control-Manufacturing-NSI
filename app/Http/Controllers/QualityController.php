<?php

namespace App\Http\Controllers;

use App\Models\Quality;
use App\Http\Requests\StoreQualityRequest;
use App\Http\Requests\UpdateQualityRequest;
use App\Models\HistoryQuality;
use Carbon\Carbon;
use Illuminate\Http\Request;

class QualityController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $monthNow = $now->format('m');
        $yearNow = $now->format('Y');
        $date = $now->format('m-Y');

        $historyQuality = HistoryQuality::where('date', $date)->first();

        $camIpqc = Quality::whereMonth('date', $monthNow)->whereYear('date', $yearNow)->where('departement', 'IPQC')->where('section', 'CAM')->count();
        $cncIpqc = Quality::whereMonth('date', $monthNow)->whereYear('date', $yearNow)->where('departement', 'IPQC')->where('section', 'CNC')->count();
        $mfgIpqc = Quality::whereMonth('date', $monthNow)->whereYear('date', $yearNow)->where('departement', 'IPQC')->where('section', 'MFG2')->count();
        $camOqc = Quality::whereMonth('date', $monthNow)->whereYear('date', $yearNow)->where('departement', 'OQC')->where('section', 'CAM')->count();
        $cncOqc = Quality::whereMonth('date', $monthNow)->whereYear('date', $yearNow)->where('departement', 'OQC')->where('section', 'CNC')->count();
        $mfgOqc = Quality::whereMonth('date', $monthNow)->whereYear('date', $yearNow)->where('departement', 'OQC')->where('section', 'MFG2')->count();
        return view('quality.home.index', [
            'camIpqc' => $camIpqc,
            'cncIpqc' => $cncIpqc,
            'mfgIpqc' => $mfgIpqc,
            'camOqc' => $camOqc,
            'cncOqc' => $cncOqc,
            'mfgOqc' => $mfgOqc,
            'historyQuality' => $historyQuality,
        ]);
    }

    public function create()
    {
        //
    }

    public function store(StoreQualityRequest $request)
    {
        $data = $request->except('_token');
        $departement = $request->departement;
        $quality = Quality::create($data);

        if ($departement == 'IPQC') {
            return redirect('/quality/dashboard-ipqc');
        } else {
            return redirect('/quality/dashboard-oqc');
        }
    }

    public function show(Quality $quality)
    {
        //
    }

    public function edit(Quality $quality)
    {
        //
    }

    public function update(UpdateQualityRequest $request, Quality $quality)
    {
        //
    }

    public function updateIpqc(UpdateQualityRequest $request, HistoryQuality $historyQuality)
    {
        $now = Carbon::now()->format('m-Y');

        $data = $request->except(['_token', '_method']);

        $historyQuality::updateOrCreate(['date' => $now], $data);
        return redirect('/quality/home')->with('success', 'Update data target IPQC!');
    }

    public function updateOqc(UpdateQualityRequest $request, HistoryQuality $historyQuality)
    {
        $now = Carbon::now()->format('m-Y');

        $data = $request->except(['_token', '_method']);

        $historyQuality::updateOrCreate(['date' => $now], $data);
        return redirect('/quality/home')->with('success', 'Update data target IPQC!');
    }

    public function destroy(Quality $quality)
    {
        //
    }
}
