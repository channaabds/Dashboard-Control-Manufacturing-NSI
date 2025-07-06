<?php

namespace App\Console;

use App\Models\HistoryQuality;
use App\Models\TargetSales;
use App\Models\MachineRepair;
use App\Models\Quality;
use App\Models\TotalDowntime;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
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

    public function saveCurrentDowntime($id, $currentDowntime)
    {
        $machineRepair = MachineRepair::find($id);

        // Cek apakah field keterangan tidak mengandung kata "history"
        if ($machineRepair && (empty($machineRepair->keterangan) || stripos($machineRepair->keterangan, 'history') === false)) {
            $machineRepair->current_downtime = $currentDowntime;
            $machineRepair->save();
        }
    }

    public function saveCurrentMonthly($id, $currentMonthlyDowntime)
    {
        $machineRepair = MachineRepair::find($id);

        // Cek apakah field keterangan tidak mengandung kata "history"
        if ($machineRepair && (empty($machineRepair->keterangan) || stripos($machineRepair->keterangan, 'history') === false)) {
            $machineRepair->current_monthly_downtime = $currentMonthlyDowntime;
            $machineRepair->save();
        }
    }

    public function saveCurrentToTotalMonthlyDowntime($id)
    {
        $machineRepair = MachineRepair::find($id);

        // Cek apakah field keterangan tidak mengandung kata "history"
        if ($machineRepair && (empty($machineRepair->keterangan) || stripos($machineRepair->keterangan, 'history') === false)) {
            $now = Carbon::now();
            $currentMonthly = $this->getInterval($machineRepair->start_monthly_downtime, $now);
            $totalMonthly = $machineRepair->total_monthly_downtime;

            // Update total monthly downtime dengan penjumlahan current dan total downtime
            $machineRepair->total_monthly_downtime = $this->addDowntimeByDowntime($currentMonthly, $totalMonthly);

            // Reset current monthly downtime
            $machineRepair->current_monthly_downtime = '0:0:0:0';
            $machineRepair->save();
        }
    }

    public function updateMonthly()
    {
        $now = Carbon::now()->subDay();
        $monthNow = $now->format('m');
        $yearNow = $now->format('Y');
        $machineRepairs = MachineRepair::whereMonth('downtime_month', "$monthNow")->whereYear('downtime_month', "$yearNow")
            ->whereNotIn('status_mesin', ['OK Repair (Finish)'])
            ->whereIn('status_aktifitas', ['Stop', 'Running'])
            ->whereNull('keterangan') // Tambahkan kondisi ini
            ->get(['id']);
        foreach ($machineRepairs as $machineRepair) {
            $this->saveCurrentToTotalMonthlyDowntime($machineRepair->id);
        }
    }

    // public function getAllTotalMonthlyDowntime()
    // {
    //     $now = Carbon::now()->subDay();
    //     $monthNow = $now->format('m');
    //     $yearNow = $now->format('Y');
    //     $totalSeconds = '0:0:0:0';
    //     $machinesRepair = MachineRepair::whereMonth('downtime_month', "$monthNow")
    //         ->whereYear('downtime_month', "$yearNow")
    //         ->whereNull('keterangan') // Tambahkan kondisi ini
    //         ->where('status_aktifitas', 'Stop')
    //         ->get();
    //     foreach ($machinesRepair as $machineRepair) {
    //         $monthlyDowntime = $machineRepair->total_monthly_downtime;
    //         $totalSeconds = $this->addDowntimeByDowntime($totalSeconds, $monthlyDowntime);
    //     }
    //     return $totalSeconds;
    // }

    public function getAllTotalMonthlyDowntime()
    {
        $now = Carbon::now()->subDay();
        $monthNow = $now->format('m');
        $yearNow = $now->format('Y');
        $totalSeconds = '0:0:0:0'; // Inisialisasi awal downtime

        // Ambil record dengan keterangan 'history' dan status aktivitas 'Stop'
        $machinesRepair = MachineRepair::whereMonth('downtime_month', $monthNow)
            ->whereYear('downtime_month', $yearNow)
            ->where('keterangan', 'LIKE', '%history%')
            ->where('status_aktifitas', 'Stop')
            ->get();

        foreach ($machinesRepair as $machineRepair) {
            $downtime = $machineRepair->total_downtime; // Ambil total_downtime dari database
            $totalSeconds = $this->addDowntimeByDowntime($totalSeconds, $downtime); // Jumlahkan downtime
        }

        return $totalSeconds;
    }


    // public function getAllTotalMonthlyDowntime()
    // {
    //     $now = Carbon::now()->subDay();
    //     $monthNow = $now->format('m');
    //     $yearNow = $now->format('Y');

    //     // Inisialisasi total downtime
    //     $totalSeconds = 0;

    //     // Ambil data dari tabel MachineRepair dengan filter baru
    //     $machinesRepair = MachineRepair::whereMonth('downtime_month', $monthNow)
    //         ->whereYear('downtime_month', $yearNow)
    //         ->where('status_aktifitas', 'Stop')
    //         ->where('keterangan', 'like', '%history%') // Filter untuk keterangan
    //         ->get();

    //     // Loop untuk menjumlahkan total_downtime
    //     foreach ($machinesRepair as $machineRepair) {
    //         $downtime = $machineRepair->total_downtime;

    //         // Konversi downtime ke detik dan jumlahkan
    //         $totalSeconds += $this->convertDowntimeToSeconds($downtime);
    //     }

    //     // Kembalikan total downtime dalam format 'hari:jam:menit:detik'
    //     return $this->convertSecondsToDowntime($totalSeconds);
    // }

    // private function convertDowntimeToSeconds($downtime)
    // {
    //     // Format input 'hari:jam:menit:detik'
    //     list($days, $hours, $minutes, $seconds) = explode(':', $downtime);

    //     // Hitung total detik
    //     return ($days * 86400) + ($hours * 3600) + ($minutes * 60) + $seconds;
    // }

    // private function convertSecondsToDowntime($seconds)
    // {
    //     $days = floor($seconds / 86400); // Hitung jumlah hari
    //     $seconds %= 86400;

    //     $hours = floor($seconds / 3600); // Hitung jumlah jam
    //     $seconds %= 3600;

    //     $minutes = floor($seconds / 60); // Hitung jumlah menit
    //     $seconds %= 60; // Sisa detik

    //     // Format hasil ke 'hari:jam:menit:detik' tanpa nol tambahan
    //     return "$days:$hours:$minutes:$seconds";
    // }

    // private function convertSecondsToDowntime($seconds)
    // {
    //     $days = floor($seconds / 86400); // Hitung jumlah hari
    //     $seconds %= 86400;

    //     $hours = floor($seconds / 3600); // Hitung jumlah jam
    //     $seconds %= 3600;

    //     $minutes = floor($seconds / 60); // Hitung jumlah menit
    //     $seconds %= 60; // Sisa detik

    //     // Format hasil tanpa padding nol
    //     return sprintf('%d:%d:%d:%d', $days, $hours, $minutes, $seconds);
    // }







    public function resetMonthly()
    {
        $now = Carbon::now();
        $nowMonth = $now->subDay();  // Mengurangi satu hari untuk memastikan berada di akhir bulan sebelumnya
        $monthNow = $nowMonth->format('m');  // Mendapatkan bulan saat ini
        $yearNow = $nowMonth->format('Y');  // Mendapatkan tahun saat ini

        // Mengambil semua record yang sesuai dengan bulan dan tahun yang ada dan memiliki status aktifitas "Stop"
        $machineRepairs = MachineRepair::whereMonth('downtime_month', $monthNow)
            ->whereYear('downtime_month', $yearNow)
            ->whereNotIn('status_mesin', ['OK Repair (Finish)'])
            ->whereIn('status_aktifitas', ['Stop', 'Running']) // Tambahkan kondisi ini
            ->whereNull('keterangan') // Tambahkan kondisi ini
            ->get();

        foreach ($machineRepairs as $machineRepair) {
            // Cek apakah field keterangan tidak mengandung kata "history"
            if (empty($machineRepair->keterangan) || stripos($machineRepair->keterangan, 'history') === false) {
                // Reset downtime untuk bulan ini
                $machineRepair->current_monthly_downtime = '0:0:0:0';
                $machineRepair->total_monthly_downtime = '0:0:0:0';
                $machineRepair->start_monthly_downtime = Carbon::now()->startOfMonth();  // Start downtime bulan baru
                $machineRepair->downtime_month = Carbon::now()->startOfMonth()->format('Y-m-d');  // Set downtime bulan baru
                $machineRepair->save();  // Simpan perubahan

                // Mencari record terakhir yang memiliki "history" pada keterangan dan id_case yang sama
                $lastHistoryRecord = MachineRepair::where('keterangan', 'LIKE', '%history%')
                    ->where('id_case', $machineRepair->id_case)  // Mencari berdasarkan id_case
                    ->latest('updated_at')
                    ->first();

                if ($lastHistoryRecord) {
                    // Hitung selisih waktu antara start_monthly_downtime baru dan updated_at pada record history terakhir
                    $timeDifferenceInSeconds = $machineRepair->start_monthly_downtime->diffInSeconds($lastHistoryRecord->updated_at);

                    // Konversi selisih waktu ke format hari:jam:menit:detik
                    $days = floor($timeDifferenceInSeconds / 86400);
                    $hours = floor(($timeDifferenceInSeconds % 86400) / 3600);
                    $minutes = floor(($timeDifferenceInSeconds % 3600) / 60);
                    $seconds = $timeDifferenceInSeconds % 60;

                    // Format total_downtime sebagai string "hari:jam:menit:detik"
                    $totalDowntimeFormatted = "{$days}:{$hours}:{$minutes}:{$seconds}";

                    // Update total_downtime pada lastHistoryRecord tanpa mempengaruhi created_at dan updated_at
                    \DB::table('machine_repairs')
                        ->where('id', $lastHistoryRecord->id)
                        ->update(['total_downtime' => $totalDowntimeFormatted]);
                }

                // Menduplikasi record untuk bulan baru
                $newMachineRepair = $machineRepair->replicate();  // Membuat salinan dari record yang ada
                $newMachineRepair->keterangan = $newMachineRepair->keterangan . 'history';  // Tambahkan "history" di keterangan
                $newMachineRepair->start_downtime = NULL;  // Set start_downtime ke NULL pada record replikasi
                $newMachineRepair->updated_at = $machineRepair->start_monthly_downtime;  // Set updated_at dengan start_monthly_downtime
                $newMachineRepair->current_downtime = '0:0:0:0';  // Set current_downtime ke 0:0:0:0
                $newMachineRepair->total_downtime = '0:0:0:0';  // Set total_downtime ke 0:0:0:0
                $newMachineRepair->save();  // Simpan salinan ke dalam database
            }
        }
    }


    public function downtime()
    {
        $machineRepairs = MachineRepair::whereNotIn('status_mesin', ['OK Repair (Finish)'])
            ->whereIn('status_aktifitas', ['Stop', 'Running']) // Tambahkan kondisi ini
            ->whereNull('keterangan') // Tambahkan kondisi ini
            ->get(['id', 'start_downtime', 'start_monthly_downtime']);

        $now = Carbon::now();
        foreach ($machineRepairs as $machineRepair) {
            $intervalDowntime = $this->getInterval($machineRepair->start_downtime, $now);
            $intervalMonthlyDowntime = $this->getInterval($machineRepair->start_monthly_downtime, $now);
            $this->saveCurrentDowntime($machineRepair->id, $intervalDowntime);
            $this->saveCurrentMonthly($machineRepair->id, $intervalMonthlyDowntime);
        }
    }


    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            $now = Carbon::now();
            $year = $now->format('Y');
            $tahun = $now->addYear()->startOfYear()->format('Y-m-d');
            $data = TargetSales::whereYear('tahun', $year)->get([
                'januari',
                'februari',
                'maret',
                'april',
                'mei',
                'juni',
                'juli',
                'agustus',
                'september',
                'oktober',
                'november',
                'desember',
            ])->first()->toArray();
            $data['tahun'] = $tahun;
            TargetSales::create($data);
        })->yearlyOn(12, 31, '23:00');

        // update data target quality
        $schedule->call(function () {
            $now = Carbon::now();
            $pastDate = $now->subMonth()->startOfMonth();
            $pastMonth = $pastDate->format('m');
            $year = $pastDate->format('Y');

            $camIpqc = Quality::whereMonth('date', $pastMonth)->whereYear('date', $year)->where('departement', 'IPQC')->where('section', 'CAM')->count();
            $cncIpqc = Quality::whereMonth('date', $pastMonth)->whereYear('date', $year)->where('departement', 'IPQC')->where('section', 'CNC')->count();
            $mfgIpqc = Quality::whereMonth('date', $pastMonth)->whereYear('date', $year)->where('departement', 'IPQC')->where('section', 'MFG2')->count();
            $camOqc = Quality::whereMonth('date', $pastMonth)->whereYear('date', $year)->where('departement', 'OQC')->where('section', 'CAM')->count();
            $cncOqc = Quality::whereMonth('date', $pastMonth)->whereYear('date', $year)->where('departement', 'OQC')->where('section', 'CNC')->count();
            $mfgOqc = Quality::whereMonth('date', $pastMonth)->whereYear('date', $year)->where('departement', 'OQC')->where('section', 'MFG2')->count();

            $data = [
                'aktual_cam_ipqc' => $camIpqc,
                'aktual_cnc_ipqc' => $cncIpqc,
                'aktual_mfg_ipqc' => $mfgIpqc,
                'aktual_cam_oqc' => $camOqc,
                'aktual_cnc_oqc' => $cncOqc,
                'aktual_mfg_oqc' => $mfgOqc,
            ];

            HistoryQuality::updateOrCreate(['date' => $pastDate->format('Y-m-d')], $data);

            $historyQuality = HistoryQuality::whereMonth('date', $pastMonth)->whereYear('date', $year)->get(['target_cam_ipqc', 'target_cnc_ipqc', 'target_mfg_ipqc', 'target_cam_oqc', 'target_cnc_oqc', 'target_mfg_oqc'])->toArray();
            HistoryQuality::updateOrCreate(['date' => Carbon::now()->startOfMonth()->format('Y-m-d')], $historyQuality[0]);

        })->monthlyOn(1, '01:00');

        // melakukan create or update ke tabel total_downtime tiap sebulan sekali di awal bulan
        // $schedule->call(function () {
        //     $this->updateMonthly();
        //     TotalDowntime::updateOrCreate(
        //         ['bulan_downtime' => Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d')],
        //         ['total_downtime' => $this->getAllTotalMonthlyDowntime()]
        //     );
        //     $this->resetMonthly();
        // })->monthlyOn(1, '00:01');

        // Melakukan create atau update ke tabel total_downtime tiap sebulan sekali di awal bulan
        $schedule->call(function () {
            // Pertama-tama, lakukan reset bulanan
            $this->resetMonthly();

            // Kemudian, hitung total downtime setelah reset dan update data di tabel total_downtime
            TotalDowntime::updateOrCreate(
                ['bulan_downtime' => Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d')],
                ['total_downtime' => $this->getAllTotalMonthlyDowntime()]
            );

            // Lakukan update bulanan
            $this->updateMonthly();
        })->monthlyOn(1, '00:01');


        // melakukan update downtime downtime setiap satu menit sekali
        $schedule->call(function () {
            $this->downtime();
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
