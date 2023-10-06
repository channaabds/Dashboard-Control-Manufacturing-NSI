<?php

namespace App\Console\Commands;

use App\Models\MachineRepair;
use Illuminate\Console\Command;

class AutoUpdateDowntimeMonthly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'downtime:auto-update-monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ini command auto update';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $machine = MachineRepair::first();
        $this->info($machine);
    }
}
