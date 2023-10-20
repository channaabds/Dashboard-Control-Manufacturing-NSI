<?php

namespace App\Http\Controllers;

use App\Exports\IpqcExport;
use App\Models\Quality;
use Carbon\Carbon;
use Illuminate\Http\Request;

class IpqcController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Quality::where('departement', 'IPQC')->orderBy('date', 'desc')->get();
        return view('quality.dashboard-ipqc.index', [
            'data' => $data,
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
    public function store(Request $request)
    {
        // $data = $request->except('_token');
        // $quality = Quality::create($data);
        // return redirect('/quality/dashboard-ipqc');
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
    public function update(Request $request, Quality $quality)
    {
        $update = $request->except(['_method', '_token']);
        $data = $quality->find($update['id']);
        $data->update($update);
        $data->save();
        return redirect('/quality/dashboard-ipqc')->with('success', 'Data NCR / LOT TAG Berhasil Diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quality $quality, $id)
    {
        $data = $quality->find($id);
        $data->delete();
        return redirect('/quality/dashboard-ipqc')->with('success', 'Data NCR / LOT TAG Sudah Dihapus!');
    }
}
