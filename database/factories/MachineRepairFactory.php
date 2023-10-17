<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MachineRepair>
 */
class MachineRepairFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDowntime = fake()->date('y-m-d') . ' ' . fake()->time();
        return [
            'mesin_id' => mt_rand(1, 20),
            'pic' => fake()->name(),
            'request' => fake()->sentence(),
            'bagian_rusak' => fake()->sentence(),
            'sebab' => fake()->sentence(),
            'analisa' => fake()->sentence(),
            'aksi' => fake()->sentence(),
            'sparepart' => fake()->sentence(),
            'deskripsi' => fake()->sentence(),
            'prl' => fake()->date('y-m-d'),
            'po' => fake()->date('y-m-d'),
            'kedatangan_prl' => fake()->date('y-m-d'),
            'kedatangan_po' => fake()->date('y-m-d'),
            'tgl_kerusakan' => fake()->date('y-m-d') . ' ' . fake()->time(),
            'tgl_input' => fake()->date('y-m-d') . ' ' . fake()->time(),
            'tgl_finish' => fake()->date('y-m-d') . ' ' . fake()->time(),
            'status_mesin' => 'On Repair',
            'status_aktifitas' => 'Stop',
            'stop_by_production' => 0,
            'start_downtime' => $startDowntime,
            'start_monthly_downtime' => $startDowntime,
            'current_downtime' => '0:0:0:0',
            'current_monthly_downtime' => '0:0:0:0',
            'total_downtime' => '0:0:0:0',
            'total_monthly_downtime' => '0:0:0:0',
            'downtime_month' => fake()->date('y-m-d'),
        ];
    }
}
