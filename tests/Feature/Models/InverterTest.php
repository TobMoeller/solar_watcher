<?php

use App\Models\Inverter;
use App\Models\InverterStatus;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Carbon;

it('has a latestStatus relationship', function () {
    $inverter = Inverter::factory()
        ->has(
            InverterStatus::factory()
                ->state(new Sequence(
                    ['id' => 1, 'recorded_at' => now()->subDays(2)],
                    ['id' => 2, 'recorded_at' => now()->subDays(1)]
                ))
                ->count(2),
            'statuses'
        )
        ->create();

    expect($inverter->latestStatus)
        ->id->toBe(2);
});

it('has is_online trait', function (bool $isOnline) {
    Carbon::setTestNow(now());

    $inverter = Inverter::factory()
        ->has(
            InverterStatus::factory()
                ->state([
                    'is_online' => true,
                    'recorded_at' => $isOnline ? now()->subMinutes(29) : now()->subMinutes(31),
                ]),
            'statuses'
        )
        ->create();

    expect($inverter->is_online)
        ->toBe($isOnline);

    Carbon::setTestNow();
})->with([true, false]);
