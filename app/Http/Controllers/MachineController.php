<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use App\Http\Requests\StoreMachineRequest;
use App\Http\Requests\UpdateMachineRequest;
use App\Models\MachineRepair;
use Illuminate\Http\Request;

class MachineController extends Controller
{
    public function index()
    {
        $machines = Machine::all();

        return view('machines.index', [
            'machines' => $machines,
        ]);
    }

    public function create(Request $request)
    {
        //
    }

    public function store(StoreMachineRequest $request)
    {
        Machine::create($request->all());
        return redirect('/machines')->with('success', 'Data Mesin Baru Berhasil Ditambahkan!');
    }

    public function show(Machine $machine)
    {
        //
    }

    public function edit(Machine $machine)
    {
        //
    }

    public function update(UpdateMachineRequest $request, Machine $machine)
    {
        $data = $request->except(['_token', '_method', 'id']);
        $machine->find($request->id)->update($data);
        return redirect('/machines')->with('success', 'Data Mesin Berhasil Diubah!');
    }

    public function destroy($id)
    {
        MachineRepair::where('mesin_id', $id)->delete();
        Machine::find($id)->delete();
        return redirect('/machines')->with('success', 'Data Mesin Berhasil Dihapus!');
    }
}
