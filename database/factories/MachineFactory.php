<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Machine>
 */
class MachineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'no_mesin' => fake()->numerify('mesin-###'),
            'tipe_mesin' => fake()->numerify('tipe-mesin-###'),
            'tipe_bartop' => fake()->numerify('bartop-###'),
            'seri_mesin' => fake()->numerify('seri-###'),
        ];
    }
}
