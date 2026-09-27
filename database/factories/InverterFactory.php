<?php

namespace Database\Factories;

use App\Models\Inverter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Inverter>
 */
class InverterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
        ];
    }
}
