<?php

namespace App\Http\Controllers;

use App\Models\Quality;
use App\Http\Requests\StoreQualityRequest;
use App\Http\Requests\UpdateQualityRequest;
use Illuminate\Http\Request;

class QualityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('quality.home.index');
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
        $quality = Quality::create($data);
        return redirect('/quality/home');
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

    public $departement = null;

    public function dashboard(Request $request) {
        $this->departement = $request->departement;
        $data = Quality::where('departement', $this->departement);
        return view('quality.dashboard.index', [
            'departement' => $this->departement,
            'data' => $data,
        ]);
    }

    // kode masih belum berfungsi sesuai keinginan dan selalu me redirect
    public function checkDepart() {
        if ($this->departement == null) {
            return redirect('/quality/home');
        } else {
            $data = Quality::where('departement', $this->departement);
            return view('quality.dashboard.index', [
                'departement' => $this->departement,
                'data' => $data,
            ]);
        }
    }
}
