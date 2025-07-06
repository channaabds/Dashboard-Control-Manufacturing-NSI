<?php

namespace App\Http\Controllers;

use App\Models\MachineRepair;
use App\Http\Requests\StoreMachineRepairRequest;
use App\Http\Requests\UpdateMachineRepairRequest;
use App\Models\Machine;
use App\Models\TotalDowntime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema; // Tambahkan ini

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class MachineRepairController extends Controller
{
    public function index()
    {
        $DowntimeController = (new DowntimeController());

        // Menambahkan filter untuk hanya mengambil yang memiliki keterangan kosong
        $machineRepairs = MachineRepair::whereNotIn('status_mesin', ['OK Repair (Finish)'])
            ->where(function ($query) {
                $query->whereNull('keterangan')
                    ->orWhere('keterangan', ''); // Memastikan keterangan kosong
            })
            ->orderBy('tgl_input', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        $jsMachineRepairs = MachineRepair::whereNotIn('status_mesin', ['OK Repair (Finish)'])
            ->where('status_aktifitas', 'Stop')
            ->where(function ($query) {
                $query->whereNull('keterangan')
                    ->orWhere('keterangan', ''); // Memastikan keterangan kosong
            })
            ->get([
                'id',
                'start_downtime',
                'current_downtime',
                'current_monthly_downtime',
                'total_monthly_downtime',
                'total_downtime',
                'downtime_month',
                'status_mesin',
                'status_aktifitas'
            ]);

        $totalMachineRepairs = MachineRepair::whereNotIn('status_mesin', ['OK Repair (Finish)'])
            ->where('status_aktifitas', 'Stop')
            ->where(function ($query) {
                $query->whereNull('keterangan')
                    ->orWhere('keterangan', ''); // Memastikan keterangan kosong
            })
            ->count();

        $machines = Machine::all();
        $monthlyDowntime = $DowntimeController->totalMonthlyDowntime();
        $monthlyDowntimeToHours = $DowntimeController->totalMonthlyDowntime(false, true);
        $hoursMonthlyDowntime = $DowntimeController->downtimeHoursTranslator($monthlyDowntimeToHours);

        foreach ($machineRepairs as $machineRepair) {
            $addValue = $machineRepairs->find($machineRepair->id);
            $addValue->search = Carbon::parse($machineRepair->tgl_kerusakan)->toDateString();
            $total = $DowntimeController->addDowntimeByDowntime($machineRepair->current_downtime, $machineRepair->total_downtime);
            $addValue->downtime = $DowntimeController->downtimeTranslator($total);
        }

        return view('maintenance.dashboard-repair.index', [
            'machines' => $machines,
            'machineRepairs' => $machineRepairs,
            'jsMachineRepairs' => $jsMachineRepairs,
            'monthlyDowntime' => $monthlyDowntime,
            'totalMachineRepairs' => $totalMachineRepairs,
            'hoursMonthlyDowntime' => $hoursMonthlyDowntime,
        ]);
    }





    public function store(StoreMachineRepairRequest $request)
    {
        $request->validate([
            'noMesin' => 'required|exists:machines,no_mesin',
            'request' => 'required',
            'status_mesin' => 'required',
            'status_aktifitas' => 'required',
        ]);

        $now = Carbon::now();
        $dataPayload = $request->except(['_token', 'stopByProd']);
        $machine = Machine::where('no_mesin', $dataPayload['noMesin'])->get('id')->first();

        if ($dataPayload['tgl_kerusakan'] === null) {
            $dataPayload['tgl_kerusakan'] = $now;
        }

        $startDowntime = $dataPayload['tgl_kerusakan'];
        $start = Carbon::parse($startDowntime);

        $addExtraData = [];
        $extraData = [
            'mesin_id' => $machine->id,
            'start_downtime' => $startDowntime,
            'start_monthly_downtime' => $startDowntime,
            'downtime_month' => $now->format('Y-m-d'),
            'tgl_input' => $now->format('Y-m-d'),
        ];

        // Generate id_case
        $machineId = $machine->id;

        // Cek semua id_case yang sudah ada dengan mesin_id ini
        $existingCases = DB::table('machine_repairs')
            ->where('mesin_id', $machineId)
            ->whereNotNull('id_case')
            ->where('id_case', 'like', $machineId . '%')
            ->pluck('id_case')
            ->toArray();

        function generateNextLetter($letters)
        {
            $length = strlen($letters);
            for ($i = $length - 1; $i >= 0; $i--) {
                if ($letters[$i] !== 'z') {
                    $letters[$i] = chr(ord($letters[$i]) + 1);
                    return $letters;
                }
                $letters[$i] = 'a';
            }
            return 'a' . $letters;
        }

        $idCaseSuffix = 'a';

        if (!empty($existingCases)) {
            $usedSuffixes = [];
            foreach ($existingCases as $case) {
                $suffix = str_replace($machineId, '', $case);
                if (!empty($suffix)) {
                    $usedSuffixes[] = $suffix;
                }
            }

            while (in_array($idCaseSuffix, $usedSuffixes)) {
                $idCaseSuffix = generateNextLetter($idCaseSuffix);
            }
        }

        $idCase = $machineId . $idCaseSuffix;
        $extraData['id_case'] = $idCase;

        // Pengecekan downtime berjalan
        if ($dataPayload['status_mesin'] == 'OK Repair (Finish)') {
            // Set semua downtime ke 0 jika status mesin adalah 'OK Repair (Finish)'
            $addExtraData = [
                'total_downtime' => '0:0:0:0',
                'total_monthly_downtime' => '0:0:0:0',
                'tgl_finish' => $now, // Atur tgl_finish sebagai sekarang
                'id_case' => $idCase,
            ];
        } else {
            $end = $now;
            $downtime = $start->diff($end)->format('%a:%h:%i:%s');
            $addExtraData = [
                'current_downtime' => $downtime,
                'current_monthly_downtime' => $downtime,
                'id_case' => $idCase,
            ];
        }

        $data = Arr::except($dataPayload, ['noMesin', 'finish']);
        $insertData = Arr::collapse([$extraData, $data, $addExtraData]);
        DB::table('machine_repairs')->insert($insertData);

        // Duplication of the record
        $duplicatedRecord = $insertData;
        $duplicatedRecord['start_downtime'] = null;
        $duplicatedRecord['keterangan'] = 'history';
        $duplicatedRecord['updated_at'] = $insertData['start_downtime'];
        $duplicatedRecord['total_monthly_downtime'] = '0:0:0:0';
        $duplicatedRecord['current_monthly_downtime'] = '0:0:0:0';
        $duplicatedRecord['current_downtime'] = '0:0:0:0';
        DB::table('machine_repairs')->insert($duplicatedRecord);

        return redirect('/maintenance/dashboard-repair')->with('success', 'Data Baru Berhasil Ditambahkan!');
    }




    // public function update(UpdateMachineRepairRequest $request, MachineRepair $machineRepair)
    // {
    //     $DowntimeController = new DowntimeController;

    //     // Ambil data yang ada sebelum diperbarui
    //     $existingData = $machineRepair->find($request->id);

    //     // Simpan status aktivitas yang ada dan input
    //     $machineStatusInput = $request->status;
    //     $machineActivityInDB = $existingData->status_aktifitas;
    //     $machineActivityInput = $request->aktivitas;

    //     // Logika untuk mengelola status mesin dan aktivitas
    //     if ($machineActivityInDB == 'Stop' && $machineActivityInput == 'Running') {
    //         if ($machineStatusInput != 'OK Repair (Finish)') {
    //             $DowntimeController->saveCurrentToTotalDowntime($existingData->id);
    //         }
    //     }
    //     if ($machineActivityInDB == 'Running' && $machineActivityInput == 'Stop') {
    //         $DowntimeController->updateStartDowntime($existingData->id);
    //     }

    //     if ($machineStatusInput == 'OK Repair (Finish)') {
    //         if ($machineActivityInDB == 'Stop') {
    //             $DowntimeController->saveCurrentToTotalDowntime($existingData->id);
    //         }
    //         $existingData->tgl_finish = Carbon::now();
    //     }

    //     // Update status aktivitas dan status mesin
    //     $existingData->status_aktifitas = $request->aktivitas;
    //     $existingData->status_mesin = $request->status;

    //     // Simpan perubahan pada data yang ada
    //     $existingData->update($request->except(['_method', '_token']));
    //     $existingData->save();

    //     // Menduplikasi baris dengan 'keterangan' yang diubah menjadi 'history'
    //     $newRecord = $existingData->replicate(); // Menduplikasi record
    //     $newRecord->keterangan = 'history'; // Mengisi keterangan
    //     $newRecord->id_case = $existingData->id_case; // Samakan id_case dengan record asli

    //     // Set start_downtime menjadi null tanpa pengecekan status
    //     $newRecord->start_downtime = null; // Set start_downtime ke null setiap kali menduplikasi

    //     // Mencari record sebelumnya yang memiliki mesin_id yang sama dan waktu updated_at sebelum record ini
    //     $previousRecord = $machineRepair->where('id_case', $existingData->id_case)
    //         ->where('updated_at', '<', $existingData->updated_at)
    //         ->orderBy('updated_at', 'desc')
    //         ->first();

    //     if ($previousRecord) {
    //         // Hitung total_downtime dari selisih antara waktu sekarang dan updated_at record sebelumnya
    //         $previousUpdatedAt = $previousRecord->updated_at;
    //         $currentUpdatedAt = Carbon::now();
    //         $duration = $currentUpdatedAt->diffInSeconds($previousUpdatedAt);

    //         // Menghitung hari, jam, menit, dan detik
    //         $days = floor($duration / 86400); // 86400 detik dalam satu hari
    //         $hours = floor(($duration % 86400) / 3600); // Jam
    //         $minutes = floor(($duration % 3600) / 60); // Menit
    //         $seconds = $duration % 60; // Detik

    //         // Nonaktifkan timestamp sementara untuk menyimpan total_downtime tanpa mengubah updated_at
    //         $previousRecord->timestamps = false;
    //         $previousRecord->total_downtime = sprintf("%d:%d:%d:%d", $days, $hours, $minutes, $seconds);
    //         $previousRecord->save(); // Simpan perubahan pada previousRecord
    //         $previousRecord->timestamps = true; // Aktifkan kembali timestamps
    //     }

    //     // Set total_downtime pada record baru menjadi "0:0:0:0"
    //     $newRecord->total_downtime = '0:0:0:0';
    //     $newRecord->save(); // Simpan record baru

    //     return redirect('/maintenance/dashboard-repair')->with('success', 'Data Mesin Rusak Berhasil Diubah!');

    // }


    // public function update(UpdateMachineRepairRequest $request, MachineRepair $machineRepair)
    // {
    //     $DowntimeController = new DowntimeController;

    //     // Ambil data yang ada sebelum diperbarui
    //     $existingData = $machineRepair->find($request->id);

    //     // Simpan status aktivitas yang ada dan input
    //     $machineStatusInput = $request->status;
    //     $machineActivityInDB = $existingData->status_aktifitas;
    //     $machineActivityInput = $request->aktivitas;

    //     // Logika untuk mengelola status mesin dan aktivitas
    //     if ($machineActivityInDB == 'Stop' && $machineActivityInput == 'Running') {
    //         if ($machineStatusInput != 'OK Repair (Finish)') {
    //             $DowntimeController->saveCurrentToTotalDowntime($existingData->id);
    //         }
    //     }
    //     if ($machineActivityInDB == 'Running' && $machineActivityInput == 'Stop') {
    //         $DowntimeController->updateStartDowntime($existingData->id);
    //     }

    //     if ($machineStatusInput == 'OK Repair (Finish)') {
    //         if ($machineActivityInDB == 'Stop') {
    //             $DowntimeController->saveCurrentToTotalDowntime($existingData->id);
    //         }
    //         $existingData->tgl_finish = Carbon::now();
    //     }

    //     // Update status aktivitas dan status mesin
    //     $existingData->status_aktifitas = $request->aktivitas;
    //     $existingData->status_mesin = $request->status;

    //     // Simpan perubahan pada data yang ada
    //     $existingData->update($request->except(['_method', '_token']));
    //     $existingData->save();

    //     // Menduplikasi baris dengan 'keterangan' yang diubah menjadi 'history'
    //     $newRecord = $existingData->replicate();
    //     $newRecord->keterangan = 'history';
    //     $newRecord->id_case = $existingData->id_case;
    //     $newRecord->start_downtime = null;

    //     // Mencari record sebelumnya yang memiliki mesin_id yang sama dan waktu updated_at sebelum record ini
    //     $previousRecord = $machineRepair->where('id_case', $existingData->id_case)
    //         ->where('updated_at', '<', $existingData->updated_at)
    //         ->orderBy('updated_at', 'desc')
    //         ->first();

    //     if ($previousRecord) {
    //         // Hitung total_downtime dari selisih antara waktu sekarang dan updated_at record sebelumnya
    //         $previousUpdatedAt = $previousRecord->updated_at;
    //         $currentUpdatedAt = Carbon::now();
    //         $duration = $currentUpdatedAt->diffInSeconds($previousUpdatedAt);

    //         // Menghitung hari, jam, menit, dan detik
    //         $days = floor($duration / 86400);
    //         $hours = floor(($duration % 86400) / 3600);
    //         $minutes = floor(($duration % 3600) / 60);
    //         $seconds = $duration % 60;

    //         // Nonaktifkan timestamp sementara untuk menyimpan total_downtime tanpa mengubah updated_at
    //         $previousRecord->timestamps = false;
    //         $previousRecord->total_downtime = sprintf("%d:%d:%d:%d", $days, $hours, $minutes, $seconds);
    //         $previousRecord->save();
    //         $previousRecord->timestamps = true;

    //         // Jika bulan updated_at terbaru berbeda dengan bulan tgl_kerusakan dari previousRecord
    //         if ($currentUpdatedAt->format('Y-m') != Carbon::parse($previousRecord->tgl_kerusakan)->format('Y-m')) {
    //             // Set tgl_kerusakan newRecord menjadi awal bulan dari currentUpdatedAt dengan format datetime
    //             $newRecord->tgl_kerusakan = $currentUpdatedAt->copy()->startOfMonth()->toDateTimeString();
    //         }
    //     }

    //     // Set total_downtime pada record baru menjadi "0:0:0:0"
    //     $newRecord->total_downtime = '0:0:0:0';
    //     $newRecord->save();

    //     return redirect('/maintenance/dashboard-repair')->with('success', 'Data Mesin Rusak Berhasil Diubah!');
    // }




    // public function update(UpdateMachineRepairRequest $request, MachineRepair $machineRepair)
    // {
    //     $DowntimeController = new DowntimeController;

    //     // Ambil data yang ada sebelum diperbarui
    //     $existingData = $machineRepair->find($request->id);

    //     // Simpan status aktivitas yang ada dan input
    //     $machineStatusInput = $request->status;
    //     $machineActivityInDB = $existingData->status_aktifitas;
    //     $machineActivityInput = $request->aktivitas;

    //     // Logika untuk mengelola status mesin dan aktivitas
    //     if ($machineActivityInDB == 'Stop' && $machineActivityInput == 'Running') {
    //         if ($machineStatusInput != 'OK Repair (Finish)') {
    //             $DowntimeController->saveCurrentToTotalDowntime($existingData->id);
    //         }
    //     }
    //     if ($machineActivityInDB == 'Running' && $machineActivityInput == 'Stop') {
    //         $DowntimeController->updateStartDowntime($existingData->id);
    //     }

    //     if ($machineStatusInput == 'OK Repair (Finish)') {
    //         if ($machineActivityInDB == 'Stop') {
    //             $DowntimeController->saveCurrentToTotalDowntime($existingData->id);
    //         }
    //         $existingData->tgl_finish = Carbon::now();
    //     }

    //     // Update status aktivitas dan status mesin
    //     $existingData->status_aktifitas = $request->aktivitas;
    //     $existingData->status_mesin = $request->status;

    //     // Simpan perubahan pada data yang ada
    //     $existingData->update($request->except(['_method', '_token']));
    //     $existingData->save();

    //     // Menduplikasi baris dengan 'keterangan' yang diubah menjadi 'history'
    //     $newRecord = $existingData->replicate();
    //     $newRecord->keterangan = 'history';
    //     $newRecord->id_case = $existingData->id_case;
    //     $newRecord->start_downtime = null;

    //     // Set nilai total_monthly_downtime, current_monthly_downtime, current_downtime ke 0:0:0:0
    //     $newRecord->total_monthly_downtime = '0:0:0:0';
    //     $newRecord->current_monthly_downtime = '0:0:0:0';
    //     $newRecord->current_downtime = '0:0:0:0';

    //     // Ambil record terakhir yang memiliki keterangan 'history'
    //     $lastHistoryRecord = $machineRepair->where('id_case', $existingData->id_case)
    //         ->where('keterangan', 'history')
    //         ->orderBy('updated_at', 'desc')
    //         ->first();

    //     $lastHistoryMonth = $lastHistoryRecord ? $lastHistoryRecord->updated_at->format('Y-m') : null;
    //     $currentMonth = Carbon::now()->format('Y-m');

    //     if ($lastHistoryMonth !== $currentMonth) {
    //         // Set downtime_month_next pada record asli jika bulan berganti
    //         $existingData->downtime_month_next = Carbon::now()->startOfMonth()->toDateString();
    //         $existingData->save();

    //         // Buat dua record untuk bulan baru
    //         $newRecord1 = $newRecord->replicate();
    //         $newRecord1->updated_at = Carbon::now()->startOfMonth()->toDateTimeString();
    //         $newRecord1->total_downtime = '0:0:0:0';
    //         $newRecord1->status_aktifitas = 'Stop';
    //         $newRecord1->total_monthly_downtime = '0:0:0:0';
    //         $newRecord1->current_monthly_downtime = '0:0:0:0';
    //         $newRecord1->current_downtime = '0:0:0:0';
    //         $newRecord1->save();

    //         $newRecord2 = $newRecord->replicate();
    //         $newRecord2->updated_at = Carbon::now();
    //         $newRecord2->total_downtime = '0:0:0:0';
    //         $newRecord2->total_monthly_downtime = '0:0:0:0';
    //         $newRecord2->current_monthly_downtime = '0:0:0:0';
    //         $newRecord2->current_downtime = '0:0:0:0';
    //         $newRecord2->save();

    //         $previousRecord = $machineRepair->where('id_case', $existingData->id_case)
    //             ->where('updated_at', '<', $newRecord1->updated_at)
    //             ->orderBy('updated_at', 'desc')
    //             ->first();

    //         if ($previousRecord) {
    //             $previousUpdatedAt = $previousRecord->updated_at;
    //             $duration = $newRecord1->updated_at->diffInSeconds($previousUpdatedAt);

    //             $days = floor($duration / 86400);
    //             $hours = floor(($duration % 86400) / 3600);
    //             $minutes = floor(($duration % 3600) / 60);
    //             $seconds = $duration % 60;

    //             $previousRecord->timestamps = false;
    //             $previousRecord->total_downtime = sprintf("%d:%d:%d:%d", $days, $hours, $minutes, $seconds);
    //             $previousRecord->save();
    //             $previousRecord->timestamps = true;
    //         }

    //     } else {
    //         // Jika bulan sama, buat satu record saja
    //         $newRecord->total_downtime = '0:0:0:0';
    //         $newRecord->total_monthly_downtime = '0:0:0:0';
    //         $newRecord->current_monthly_downtime = '0:0:0:0';
    //         $newRecord->current_downtime = '0:0:0:0';
    //         $newRecord->save();
    //     }

    //     $previousRecord = $machineRepair->where('id_case', $existingData->id_case)
    //         ->where('updated_at', '<', $existingData->updated_at)
    //         ->orderBy('updated_at', 'desc')
    //         ->first();

    //     if ($previousRecord) {
    //         $previousUpdatedAt = $previousRecord->updated_at;
    //         $currentUpdatedAt = Carbon::now();
    //         $duration = $currentUpdatedAt->diffInSeconds($previousUpdatedAt);

    //         $days = floor($duration / 86400);
    //         $hours = floor(($duration % 86400) / 3600);
    //         $minutes = floor(($duration % 3600) / 60);
    //         $seconds = $duration % 60;

    //         $previousRecord->timestamps = false;
    //         $previousRecord->total_downtime = sprintf("%d:%d:%d:%d", $days, $hours, $minutes, $seconds);
    //         $previousRecord->save();
    //         $previousRecord->timestamps = true;
    //     }

    //     return redirect('/maintenance/dashboard-repair')->with('success', 'Data Mesin Rusak Berhasil Diubah!');
    // }

    public function update(UpdateMachineRepairRequest $request, MachineRepair $machineRepair)
    {
        $DowntimeController = new DowntimeController;

        // Ambil data yang ada sebelum diperbarui
        $existingData = $machineRepair->find($request->id);

        // Simpan status aktivitas dan status mesin yang ada di database sebelum perubahan
        $originalMachineStatus = $existingData->status_mesin;
        $originalMachineActivity = $existingData->status_aktifitas;

        // Simpan input status mesin dan aktivitas
        $machineStatusInput = $request->status;
        $machineActivityInput = $request->aktivitas;

        // Logika untuk mengelola status mesin dan aktivitas
        if ($originalMachineActivity == 'Stop' && $machineActivityInput == 'Running') {
            if ($machineStatusInput != 'OK Repair (Finish)') {
                $DowntimeController->saveCurrentToTotalDowntime($existingData->id);
            }
        }
        if ($originalMachineActivity == 'Running' && $machineActivityInput == 'Stop') {
            $DowntimeController->updateStartDowntime($existingData->id);
        }

        if ($machineStatusInput == 'OK Repair (Finish)') {
            if ($originalMachineActivity == 'Stop') {
                $DowntimeController->saveCurrentToTotalDowntime($existingData->id);
            }
            $existingData->tgl_finish = Carbon::now();
        }

        // Update status aktivitas dan status mesin
        $existingData->status_aktifitas = $machineActivityInput;
        $existingData->status_mesin = $machineStatusInput;

        // Simpan perubahan pada data yang ada
        $existingData->update($request->except(['_method', '_token']));
        $existingData->save();

        // Cek apakah perubahan hanya pada `status_mesin` atau `status_aktifitas`
        $isStatusOrActivityChanged =
            $originalMachineActivity != $machineActivityInput ||
            $originalMachineStatus != $machineStatusInput;

        if ($isStatusOrActivityChanged) {
            // Menduplikasi baris dengan 'keterangan' yang diubah menjadi 'history'
            $newRecord = $existingData->replicate(); // Menduplikasi record
            $newRecord->keterangan = 'history'; // Mengisi keterangan
            $newRecord->id_case = $existingData->id_case; // Samakan id_case dengan record asli

            // Set start_downtime menjadi null tanpa pengecekan status
            $newRecord->start_downtime = null;

            // Mencari record sebelumnya yang memiliki mesin_id yang sama dan waktu updated_at sebelum record ini
            $previousRecord = $machineRepair->where('id_case', $existingData->id_case)
                ->where('updated_at', '<', $existingData->updated_at)
                ->orderBy('updated_at', 'desc')
                ->first();

            if ($previousRecord) {
                // Hitung total_downtime dari selisih antara waktu sekarang dan updated_at record sebelumnya
                $previousUpdatedAt = $previousRecord->updated_at;
                $currentUpdatedAt = Carbon::now();
                $duration = $currentUpdatedAt->diffInSeconds($previousUpdatedAt);

                // Menghitung hari, jam, menit, dan detik
                $days = floor($duration / 86400); // 86400 detik dalam satu hari
                $hours = floor(($duration % 86400) / 3600); // Jam
                $minutes = floor(($duration % 3600) / 60); // Menit
                $seconds = $duration % 60; // Detik

                // Nonaktifkan timestamp sementara untuk menyimpan total_downtime tanpa mengubah updated_at
                $previousRecord->timestamps = false;
                $previousRecord->total_downtime = sprintf("%d:%d:%d:%d", $days, $hours, $minutes, $seconds);
                $previousRecord->save(); // Simpan perubahan pada previousRecord
                $previousRecord->timestamps = true; // Aktifkan kembali timestamps
            }

            // Set total_downtime pada record baru menjadi "0:0:0:0"
            $newRecord->total_downtime = '0:0:0:0';
            $newRecord->total_monthly_downtime = '0:0:0:0';
            $newRecord->current_monthly_downtime = '0:0:0:0';
            $newRecord->current_downtime = '0:0:0:0';
            $newRecord->save(); // Simpan record baru
        }

        return redirect('/maintenance/dashboard-repair')->with('success', 'Data Mesin Rusak Berhasil Diubah!');
    }


    public function destroy(MachineRepair $machineRepair, $id)
    {
        // Cari record berdasarkan ID
        $machineRepair = $machineRepair->findOrFail($id);

        // Ambil id_case dari record yang ditemukan
        $idCase = $machineRepair->id_case;

        // Hapus semua record yang memiliki id_case yang sama
        MachineRepair::where('id_case', $idCase)->delete();

        // Redirect dengan pesan sukses
        return redirect('/maintenance/dashboard-repair')->with('success', 'Semua data terkait berhasil dihapus!');
    }

}
