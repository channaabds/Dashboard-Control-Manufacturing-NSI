<?php

namespace App\Console;

use App\Models\MachineRepair;
use App\Models\TotalDowntime;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
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

    // function save current downtime ke database
    public function saveCurrentDowntime($id, $currentDowntime) {
        $machineRepair = MachineRepair::find($id);
        $machineRepair->current_downtime = $currentDowntime;
        $machineRepair->save();
    }

    // function save current downtime atau prod downtime ke database
    public function saveCurrentMonthly($id, $currentMonthlyDowntime) {
        $machineRepair = MachineRepair::find($id);
        $machineRepair->current_monthly_downtime = $currentMonthlyDowntime;
        $machineRepair->save();
    }

    public function saveCurrentToTotalMonthlyDowntime($id) {
        $machineRepair = MachineRepair::find($id);
        $now = Carbon::now();
        $currentMonthly = $this->getInterval($machineRepair->start_monthly_downtime, $now);
        $totalMonthly = $machineRepair->total_monthly_downtime;
        $machineRepair->total_monthly_downtime = $this->addDowntimeByDowntime($currentMonthly, $totalMonthly);

        $machineRepair->current_monthly_downtime = '0:0:0:0';
        $machineRepair->save();
    }

    public function updateMonthly() {
        $now = Carbon::now()->subDay();
        $monthNow = $now->format('m');
        $yearNow = $now->format('Y');
        $machineRepairs = MachineRepair::whereMonth('downtime_month', "$monthNow")->whereYear('downtime_month', "$yearNow")
                            ->whereNotIn('status_mesin', ['OK Repair (Finish)'])
                            ->where('status_aktifitas', 'Stop')
                            ->get(['id']);
        foreach ($machineRepairs as $machineRepair) {
            $this->saveCurrentToTotalMonthlyDowntime($machineRepair->id);
        }
    }

    public function getAllTotalMonthlyDowntime() {
        $now = Carbon::now()->subDay();
        $monthNow = $now->format('m');
        $yearNow = $now->format('Y');
        $totalSeconds = '0:0:0:0';
        $machinesRepair = MachineRepair::whereMonth('downtime_month', "$monthNow")
                            ->whereYear('downtime_month', "$yearNow")
                            ->get();
        foreach ($machinesRepair as $machineRepair) {
            $monthlyDowntime = $machineRepair->total_monthly_downtime;
            $totalSeconds = $this->addDowntimeByDowntime($totalSeconds, $monthlyDowntime);
        }
        return $totalSeconds;
    }

    public function resetMonthly() {
        $now = Carbon::now();
        $nowMonth = $now->subDay();
        $monthNow = $nowMonth->format('m');
        $yearNow = $nowMonth->format('Y');
        $machineRepairs = MachineRepair::whereMonth('downtime_month', "$monthNow")->whereYear('downtime_month', "$yearNow")
                            ->whereNotIn('status_mesin', ['OK Repair (Finish)'])
                            ->where('status_aktifitas', 'Stop')
                            ->get();
        foreach ($machineRepairs as $machineRepair) {
            $machineRepair->current_monthly_downtime = '0:0:0:0';
            $machineRepair->total_monthly_downtime = '0:0:0:0';
            $machineRepair->start_monthly_downtime = $now->startOfMonth();
            $machineRepair->downtime_month = $now->startOfMonth()->format('Y-m-d');
            $machineRepair->save();
        }
    }


    public function downtime() {
        $machineRepairs = MachineRepair::whereNotIn('status_mesin', ['OK Repair (Finish)'])
                ->where('status_aktifitas', 'Stop')
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
        // melakukan create or update ke tabel total_downtime tiap sebulan sekali di awal bulan
        $schedule->call(function () {
            $this->updateMonthly();
            TotalDowntime::updateOrCreate(
                ['bulan_downtime' => Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d')],
                ['total_downtime' => $this->getAllTotalMonthlyDowntime()]
            );
            $this->resetMonthly();
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
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
