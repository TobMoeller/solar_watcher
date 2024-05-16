<?php

namespace Database\Seeders;

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
            InverterOutput::factory()
                ->for($inverter)
                ->count(5)
                ->create();

            InverterStatus::factory()
                ->for($inverter)
                ->count(10)
                ->create();
        }
    }
}
