<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (! App::environment('local')) {
            return;
        }

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'dev@tobias-moeller.de',
            'password' => Hash::make('password'),
        ]);

        $this->call([
            InverterSeeder::class,
        ]);
    }
}
