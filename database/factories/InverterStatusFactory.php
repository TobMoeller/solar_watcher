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
            'udc' => $this->faker->randomFloat(2, 0, 30_000),
            'idc' => $this->faker->randomFloat(2, 0, 30_000),
            'pac' => $this->faker->randomFloat(2, 0, 30_000),
            'pdc' => $this->faker->randomFloat(2, 0, 30_000),
        ];
    }
}
