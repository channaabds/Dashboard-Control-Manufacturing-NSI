<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema; // Tambahkan ini
use Carbon\Carbon;
use App\Models\MachineRepair;
use App\Models\TotalDowntime;

class DowntimeController extends Controller
{
    // fungsi ini akan merubah downtime ke bentuk yang mudah dibaca
    // 0:0:0:0 -> 0 Hari 0 Jam 0 Menit 0 Detik
    public function downtimeTranslator($downtime, $isString = false)
    {
        $downtimeParts = explode(':', $downtime);
        $days = $downtimeParts[0];
        $hours = $downtimeParts[1];
        $minutes = $downtimeParts[2];
        $seconds = $downtimeParts[3];

        if ($isString) {
            return $days . ' Hari ' . $hours . ' Jam ' . $minutes . ' Menit ' . $seconds . ' Detik';
        } else {
            return $days . ' Hari </br>' . $hours . ' Jam </br>' . $minutes . ' Menit </br>' . $seconds . ' Detik';
        }
    }

    public function downtimeHoursTranslator($downtime)
    {
        $downtimeParts = explode(':', $downtime);
        $days = $downtimeParts[0];
        $hours = $downtimeParts[1];
        $minutes = $downtimeParts[2];
        $seconds = $downtimeParts[3];
        $hours = $hours + ($days * 24);

        return $hours . ' Jam </br>' . $minutes . ' Menit </br>' . $seconds . ' Detik';
    }

    // function untuk menambahkan antara 2 downtime yang memiliki format '0:0:0:0'
    public function addDowntimeByDowntime($firstDowntime, $secDowntime)
    {
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
        $seconds = $totalSeconds % 60;

        $result = "$days:$hours:$minutes:$seconds";
        return $result;
    }

    // function untuk mendapatkan interval antara waktu strat downtime dan waktu sekarang ini (current downtime)
    public function getInterval($startDowntime, $now)
    {
        $start = Carbon::parse($startDowntime);
        $result = $start->diff($now)->format('%a:%h:%i:%s');
        return $result;
    }

    // function yang akan menyimpan downtime dari kolom current_downtime atau prod_downtime yang sudah dijumlahkan dengan nilai pada kolom total_downtime sebelumnya ke kolom total_downtime dan mereset current_downtime ke '0:0:0:0'
    public function saveCurrentToTotalDowntime($id)
    {
        $machineRepair = MachineRepair::find($id);
        $now = Carbon::now();
        $interval = $this->getInterval($machineRepair->start_downtime, $now);
        $intervalMonthly = $this->getInterval($machineRepair->start_monthly_downtime, $now);

        $totalDowntime = $machineRepair->total_downtime;
        $totalMonthly = $machineRepair->total_monthly_downtime;

        $machineRepair->total_monthly_downtime = $this->addDowntimeByDowntime($intervalMonthly, $totalMonthly);
        $machineRepair->total_downtime = $this->addDowntimeByDowntime($interval, $totalDowntime);

        $machineRepair->current_monthly_downtime = '0:0:0:0';
        $machineRepair->current_downtime = '0:0:0:0';

        $machineRepair->save();
    }

    // public function saveCurrentToTotalDowntime($id)
    // {
    //     $machineRepair = MachineRepair::find($id);
    //     $now = Carbon::now();

    //     $startDowntime = Carbon::parse($machineRepair->start_downtime);
    //     $endDowntime = $now;

    //     if ($startDowntime->month != $endDowntime->month) {
    //         // Hitung downtime dari start hingga akhir bulan pertama
    //         $endOfStartMonth = $startDowntime->copy()->endOfMonth();
    //         $intervalFirstMonth = $this->getInterval($startDowntime, $endOfStartMonth);

    //         // Simpan downtime bulan pertama sebagai baris baru
    //         $newEntryFirstMonth = $machineRepair->replicate(); // Menggandakan baris yang ada
    //         $newEntryFirstMonth->start_downtime = $startDowntime;
    //         $newEntryFirstMonth->end_downtime = $endOfStartMonth;
    //         $newEntryFirstMonth->total_downtime = $intervalFirstMonth;
    //         $newEntryFirstMonth->tgl_kerusakan = $startDowntime; // Samakan dengan start_downtime
    //         $newEntryFirstMonth->save();

    //         // Hitung downtime bulan kedua
    //         $startOfNextMonth = $endDowntime->copy()->startOfMonth();
    //         $intervalNextMonth = $this->getInterval($startOfNextMonth, $endDowntime);

    //         // Simpan downtime bulan kedua sebagai baris baru
    //         $newEntryNextMonth = $machineRepair->replicate(); // Menggandakan baris yang ada
    //         $newEntryNextMonth->start_downtime = $startOfNextMonth;
    //         $newEntryNextMonth->end_downtime = $endDowntime;
    //         $newEntryNextMonth->total_downtime = $intervalNextMonth;
    //         $newEntryNextMonth->tgl_kerusakan = $startDowntime; // Samakan dengan start_downtime
    //         $newEntryNextMonth->save();
    //     } else {
    //         // Jika dalam bulan yang sama, simpan sebagai satu baris
    //         $interval = $this->getInterval($startDowntime, $endDowntime);
    //         $machineRepair->total_downtime = $interval;
    //         $machineRepair->save();
    //     }

    //     // Reset current downtime jika diperlukan
    //     $machineRepair->current_downtime = '0:0:0:0';
    //     $machineRepair->current_monthly_downtime = '0:0:0:0';
    //     $machineRepair->save();
    // }





    // function ini berfungsi untuk mengupdate kolom start_downtime menjadi waktu sekarang ini
    public function updateStartDowntime($id)
    {
        $now = Carbon::now();
        $machineRepair = MachineRepair::find($id);
        $machineRepair->start_downtime = $now;
        $machineRepair->start_monthly_downtime = $now;
        $machineRepair->save();
    }

    public function totalMonthlyDowntime($isString = false, $format = false)
    {
        $now = Carbon::now();
        $monthNow = $now->format('m');
        $yearNow = $now->format('Y');

        // Filter untuk memastikan hanya entri yang tidak mengandung kata "history" pada kolom keterangan
        $machineRepairs = MachineRepair::whereMonth('downtime_month', "$monthNow")
            ->whereYear('downtime_month', "$yearNow")
            ->where(function ($query) {
                $query->whereNull('keterangan') // Keterangan null
                    ->orWhere('keterangan', '') // Keterangan kosong
                    ->orWhere('keterangan', 'NOT LIKE', '%history%'); // Tidak mengandung "history"
            })
            ->get(['start_monthly_downtime', 'status_mesin', 'status_aktifitas', 'current_monthly_downtime', 'total_monthly_downtime']);

        $totalDowntime = '0:0:0:0';
        $downtime = '0:0:0:0';

        foreach ($machineRepairs as $machineRepair) {
            if ($machineRepair->status_mesin == "OK Repair (Finish)") {
                $downtime = $machineRepair->total_monthly_downtime;
            } else {
                if ($machineRepair->status_aktifitas == "Stop") {
                    $monthlyInterval = $this->getInterval($machineRepair->start_monthly_downtime, $now);
                    $downtime = $this->addDowntimeByDowntime($monthlyInterval, $machineRepair->total_monthly_downtime);
                } else {
                    $downtime = $machineRepair->total_monthly_downtime;
                }
            }

            $totalDowntime = $this->addDowntimeByDowntime($totalDowntime, $downtime);
        }

        // Mengembalikan hasil dalam format yang sesuai
        if ($format) {
            $result = $totalDowntime;
        } else {
            if ($isString) {
                $result = $this->downtimeTranslator($totalDowntime, true);
            } else {
                $result = $this->downtimeTranslator($totalDowntime);
            }
        }

        return $result;
    }



    public function getTotalDowntime(Request $request)
    {
        $monthNow = Carbon::now()->format('Y-m');
        $monthFormated = Carbon::create($request->filter)->format('F Y');

        if ($request->filter == $monthNow) {
            $totalDowntime = $this->totalMonthlyDowntime();
            $downtimeInHours = $this->downtimeHoursTranslator($this->totalMonthlyDowntime(false, true));
            return ['days' => $totalDowntime, 'hours' => $downtimeInHours];
        } else {
            $parseRequest = Carbon::parse($request->filter)->format('m Y');
            $monthParts = explode(" ", $parseRequest);
            $month = $monthParts[0];
            $year = $monthParts[1];
            $totalDowntimeDB = TotalDowntime::whereMonth('bulan_downtime', "$month")->whereYear('bulan_downtime', "$year")->get('total_downtime');
            if (count($totalDowntimeDB) > 0) {
                $totalDowntime = $this->downtimeTranslator($totalDowntimeDB[0]->total_downtime);

                $downtimeInHours = $this->downtimeHoursTranslator($totalDowntimeDB[0]->total_downtime);
                return ['days' => $totalDowntime, 'hours' => $downtimeInHours];
            } else {
                return [
                    'days' => "downtime bulan</br>$monthFormated</br>belum terdata",
                    'hours' => "downtime bulan</br>$monthFormated</br>belum terdata",
                ];
            }
        }
    }

    public function downtimeToSeconds($downtime)
    {
        $downtimeParts = explode(':', $downtime);

        $days = intval($downtimeParts[0]);
        $hours = intval($downtimeParts[1]);
        $minutes = intval($downtimeParts[2]);
        $seconds = intval($downtimeParts[3]);

        $totalSeconds = ($days * 86400) + ($hours * 3600) + ($minutes * 60) + $seconds;

        return $totalSeconds;
    }

    // function ini yang menangani ajax request dari halaman dashboard, dan berfungsi sebagai fitur realtime downtime counter dan auto update downtime ke database
    // fungsi realtime menerima data dari view dan tidak melakukan query di fungsinya dengan tujuan mengurangi query ke database supaya lebih efisien
    public function downtime(Request $request)
    {
        $machineRepairs = $request->data;
        $now = Carbon::now();
        $result = [];
        foreach ($machineRepairs as $machineRepair) {
            $interval = $this->getInterval($machineRepair['start_downtime'], $now);
            $total = $this->addDowntimeByDowntime($interval, $machineRepair['total_downtime']);
            $result[$machineRepair['id']] = $total;
        }
        return $result;
    }
}
