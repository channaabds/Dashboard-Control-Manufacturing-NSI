<?php

namespace App\Http\Controllers;

use App\Exports\OqcExport;
use App\Models\Quality;
use Illuminate\Http\Request;

class OqcController extends Controller
{
    public function index()
    {
        $data = Quality::where('departement', 'OQC')->orderBy('date', 'desc')->get();
        return view('quality.dashboard-oqc.index', [
            'data' => $data,
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        // $data = $request->except('_token');
        // $quality = Quality::create($data);
        // return redirect('/quality/dashboard-oqc');
    }

    public function show(Quality $quality)
    {
        //
    }

    public function edit(Quality $quality)
    {
        //
    }

    public function update(Request $request, Quality $quality)
    {
        $update = $request->except(['_method', '_token']);
        $data = $quality->find($update['id']);
        $data->update($update);
        $data->save();
        return redirect('/quality/dashboard-oqc')->with('success', 'Data NCR / LOT TAG Berhasil Diubah!');
    }

    public function destroy(Quality $quality, $id)
    {
        $data = $quality->find($id);
        $data->delete();
        return redirect('/quality/dashboard-oqc')->with('success', 'Data NCR / LOT TAG Sudah Dihapus!');
    }
}
