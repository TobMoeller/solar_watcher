<?php

namespace Database\Factories;

use App\Models\Inverter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InverterStatus>
 */
class InverterStatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'inverter_id' => Inverter::factory(),
            'is_online' => $this->faker->boolean(),
            'udc' => $udc = $this->faker->randomFloat(2, 0, 1000),
            'idc' => $idc = $this->faker->randomFloat(2, 0, 20),
            'pdc' => $pdc = round($udc * $idc, 2),
            'pac' => round($pdc * 0.95, 2)
        ];
    }
}
