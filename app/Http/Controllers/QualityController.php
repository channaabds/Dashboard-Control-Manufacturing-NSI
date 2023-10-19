<?php

namespace App\Http\Controllers;

use App\Models\Quality;
use App\Http\Requests\StoreQualityRequest;
use App\Http\Requests\UpdateQualityRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class QualityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $now = Carbon::now();
        $monthNow = $now->format('m');
        $yearNow = $now->format('Y');

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
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Display the specified resource.
     */
    public function show(Quality $quality)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quality $quality)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQualityRequest $request, Quality $quality)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quality $quality)
    {
        //
    }
}
