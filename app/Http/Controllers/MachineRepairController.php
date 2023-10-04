<?php

namespace App\Http\Controllers;

use App\Models\MachineRepair;
use App\Http\Requests\StoreMachineRepairRequest;
use App\Http\Requests\UpdateMachineRepairRequest;
use App\Models\Machine;
use Illuminate\Http\Request;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MachineRepairController extends Controller
{

    // function untuk menambahkan antara 2 downtime yang memiliki format '0:0:0:0'
    public function addDowntimeByDowntime($firstDowntime, $secDowntime) {
        $firstDowntimeParts = explode(':', $firstDowntime);
        $secDowntimeParts = explode(':', $secDowntime);

        $firstDowntimeDays = intval($firstDowntimeParts[0]);
        $firstDowntimeHours = intval($firstDowntimeParts[1]);
        $firstDowntimeMinutes = intval($firstDowntimeParts[2]);
        $firstDowntimeSeconds = intval($firstDowntimeParts[3]);

        $secDowntimeDays = intval($secDowntimeParts[0]);
        $secDowntimeHours = intval($secDowntimeParts[1]);
        $secDowntimeMinutes = intval($secDowntimeParts[2]);
        $secDowntimeSeconds = intval($secDowntimeParts[3]);

        $totalSeconds = (($firstDowntimeDays * 86400) + ($firstDowntimeHours * 3600) + ($firstDowntimeMinutes * 60) + $firstDowntimeSeconds) + (($secDowntimeDays * 86400) + ($secDowntimeHours * 3600) + ($secDowntimeMinutes * 60) + $secDowntimeSeconds);

        $days = floor($totalSeconds / 86400);
        $totalSeconds %= 86400;
        $hours = floor($totalSeconds / 3600);
        $totalSeconds %= 3600;
        $minutes = floor($totalSeconds / 60);
        $seconds = $totalSeconds %  60;

        $result = "$days:$hours:$minutes:$seconds";
        return $result;
    }

    // function untuk mendapatkan interval antara waktu strat downtime dan waktu sekarang ini (current downtime)
    public function getInterval($startDowntime, $now) {
        $start = Carbon::parse($startDowntime);
        $result = $start->diff($now)->format('%a:%h:%i:%s');
        return $result;
    }

    // function save current downtime atau prod downtime ke database
    // function ini akan dijalankan selama 1 menit sekali, sehingga menjadi fitur auto update downtime
    public function saveCurrentOrProdDowntime($id, $currentDowntime) {
        $machineRepair = MachineRepair::find($id);
        if ($machineRepair->status_mesin == 'Stop by Prod') {
            $machineRepair->prod_downtime = $currentDowntime;
            $machineRepair->save();
        } else {
            $machineRepair->current_downtime = $currentDowntime;
            $machineRepair->save();
        }
    }

    // function yang akan menyimpan downtime dari kolom current_downtime atau prod_downtime yang sudah dijumlahkan dengan nilai pada kolom total_downtime sebelumnya ke kolom total_downtime dan mereset current_downtime ke '0:0:0:0'
    public function saveCurrentAndProdToTotalDowntime($id) {
        $machine = MachineRepair::find($id);
        $now = Carbon::now();
        if ($machine->status_mesin == 'Stop by Prod') {
            $currentProd = $this->getInterval($machine->start_downtime, $now);
            $machine->total_downtime = $currentProd;
            $machine->save();
            exit;
        }
        $current = $this->getInterval($machine->start_downtime, $now);
        $prod = $machine->prod_downtime;
        $total = $machine->total_downtime;
        $currentAndProd = $this->addDowntimeByDowntime($current, $prod);
        $machine->total_downtime = $this->addDowntimeByDowntime($currentAndProd, $total);
        $machine->current_downtime = '0:0:0:0';
        $machine->save();
    }

    // function ini berfungsi untuk mengupdate kolom start_downtime menjadi waktu sekarang ini
    public function updateStartDowntime($id) {
        $now = Carbon::now();
        $machine = MachineRepair::find($id);
        $machine->start_downtime = $now;
        $machine->save();
    }

    // function ini yang menangani ajax request dari halaman dashboard, dan berfungsi sebagai fitur realtime downtime counter dan auto update downtime ke database
    public function downtime(Request $request) {
        $data = $request->data;
        $now = Carbon::now();
        $result = [];
        foreach ($data as $d) {
            if ($d['status_mesin'] !== 'OK Repair (Finish)' && $d['status_aktifitas'] !== 'Running') {
                $interval = $this->getInterval($d['start_downtime'], $now);
                $result[$d['id']] = $this->addDowntimeByDowntime($interval, $d['total_downtime']);

                if ($now->format('s') == 30) {
                    $this->saveCurrentOrProdDowntime($d['id'], $interval);

                    if (Carbon::today()->startOfMonth()->format('d:H:i') == Carbon::now()->format('d:H:i')) {
                        DB::table('total_downtimes')->updateOrInsert(
                            [
                                'bulan_downtime' => Carbon::now()->subDay()->format('Y-m-d'),
                            ],
                            [
                                'total_downtime' => $this->timerTotalDowntime(),
                            ]);
                    }
                }
            }
        }
        return $result;
    }

    public function index()
    {
        $machinesRepair = MachineRepair::all();
        $jsMachinesRepair = MachineRepair::get(['id', 'start_downtime', 'current_downtime', 'prod_downtime', 'total_downtime', 'monthly_downtime', 'downtime_month', 'status_mesin', 'status_aktifitas'])->toArray();
        $machines = Machine:: all();
        return view('dashboard.index', [
            'machines' => $machines,
            'machinesOnRepair' => $machinesRepair,
            'jsMachinesOnRepair' => $jsMachinesRepair,
        ]);
    }

    public function store(StoreMachineRepairRequest $request)
    {
        return 'hello world';
    }

    public function update(UpdateMachineRepairRequest $request, MachineRepair $machineRepair)
    {
        $data = $request->except(['_method', '_token']);
        $machine = $machineRepair->find($data['id']);
        $machineStatusInDB = $machine->status_mesin;
        $machineStatusInput = $data['status'];
        if ($machineStatusInDB == 'Stop by Prod' && $machineStatusInput != 'Stop by Prod') {
            $this->updateStartDowntime($machine->id);
        }
        $machineActivityInDB = $machine->status_aktifitas;
        $machineActivityInput = $data['aktivitas'];
        if ($machineActivityInDB == 'Stop' && $machineActivityInput == 'Stop') {
            // downtime jalan dari yang awalnya jalan dan save current downtime
            // tidak terjadi apa-apa
        }
        if ($machineActivityInDB == 'Stop' && $machineActivityInput == 'Running') {
            // downtime stop(pause) dari yang awalnya jalan
            $this->saveCurrentAndProdToTotalDowntime($machine->id);
        }
        if ($machineActivityInDB == 'Running' && $machineActivityInput == 'Stop') {
            // downtime lanjut dari yang awalnya stop
            $this->updateStartDowntime($machine->id);
        }
        if ($machineActivityInDB == 'Running' && $machineActivityInput == 'Running') {
            // downtime stop(pause) yang awalnya stop(pause)
            // tidak terjadi apa apa
        }
        $machine->kedatangan_prl = $data['kedatanganPrl'];
        $machine->kedatangan_po = $data['kedatanganPo'];
        $machine->tgl_kerusakan = $data['tanggalKerusakan'];
        $machine->bagian_rusak = $data['bagianRusak'];
        $machine->status_aktifitas = $data['aktivitas'];
        $machine->status_mesin= $data['status'];
        $machine->save();
        $machine->update($data);
        return redirect('/dashboard')->with('success', 'Data Mesin Rusak Berhasil Diubah!');
    }

    public function destroy(MachineRepair $machineRepair)
    {
        //
    }
}
