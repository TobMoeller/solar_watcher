<?php

namespace Database\Seeders;

use App\Enums\TimespanUnit;
use App\Models\Inverter;
use App\Models\InverterOutput;
use App\Models\InverterStatus;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class InverterSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $inverters = Inverter::factory(
            new Sequence(['name' => 'links'], ['name' => 'rechts'])
        )->count(2)->create();

        foreach ($inverters as $inverter) {
            $this->createOutputs($inverter);
            $this->createStatus($inverter);
        }
    }

    protected function createOutputs(Inverter $inverter): void
    {
        InverterOutput::factory()
            ->for($inverter)
            ->state(['timespan' => TimespanUnit::DAY])
            ->state(new Sequence(function (Sequence $sequence) {
                return [
                    'recorded_at' => $date = now()->subDays($sequence->index),
                    'created_at' => $date,
                    'updated_at' => $date,
                ];
            }))
            ->count(2 * 365)
            ->create();

        InverterOutput::factory()
            ->for($inverter)
            ->state(['timespan' => TimespanUnit::MONTH])
            ->state(new Sequence(function (Sequence $sequence) {
                return [
                    'recorded_at' => $date = now()->subMonths($sequence->index)->startOfMonth(),
                    'created_at' => $date,
                    'updated_at' => $date,
                ];
            }))
            ->count(2 * 12)
            ->create();

        InverterOutput::factory()
            ->for($inverter)
            ->state(['timespan' => TimespanUnit::YEAR])
            ->state(new Sequence(function (Sequence $sequence) {
                return [
                    'recorded_at' => $date = now()->subYears($sequence->index)->startOfYear(),
                    'created_at' => $date,
                    'updated_at' => $date,
                ];
            }))
            ->count(2)
            ->create();
    }

    protected function createStatus(Inverter $inverter): void
    {
        InverterStatus::factory()
            ->for($inverter)
            ->state(new Sequence(function (Sequence $sequence) {
                return [
                    'created_at' => $date = now()->subMinutes($sequence->index * 5),
                    'updated_at' => $date,
                ];
            }))
            ->count(30 * 24 * (60 / 5)) // 1 month of data
            ->create();
    }
}
