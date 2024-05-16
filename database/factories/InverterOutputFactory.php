<?php

namespace Database\Factories;

use App\Enums\TimespanUnit;
use App\Models\Inverter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InverterOutput>
 */
class InverterOutputFactory extends Factory
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
            'output' => $this->faker->numberBetween(1, 900000),
            'timespan' => $this->faker->randomElement(TimespanUnit::cases()),
            'recorded_at' => $this->faker->dateTimeBetween('-5 years', 'now'),
        ];
    }
}
