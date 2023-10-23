<?php

namespace App\Http\Controllers;

use App\Exports\IpqcExport;
use App\Models\Quality;
use Carbon\Carbon;
use Illuminate\Http\Request;

class IpqcController extends Controller
{
    public function index()
    {
        $data = Quality::where('departement', 'IPQC')->orderBy('date', 'desc')->get();
        return view('quality.dashboard-ipqc.index', [
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
        // return redirect('/quality/dashboard-ipqc');
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
        return redirect('/quality/dashboard-ipqc')->with('success', 'Data NCR / LOT TAG Berhasil Diubah!');
    }

    public function destroy(Quality $quality, $id)
    {
        $data = $quality->find($id);
        $data->delete();
        return redirect('/quality/dashboard-ipqc')->with('success', 'Data NCR / LOT TAG Sudah Dihapus!');
    }
}
