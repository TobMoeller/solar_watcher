<?php

use App\Enums\TimespanUnit;
use App\Livewire\Inverters\InverterList;
use App\Models\Inverter;
use App\Models\InverterOutput;
use App\Models\InverterStatus;
use App\Services\Breadcrumbs\Breadcrumbs;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Livewire\Livewire;

it('renders inverter list', function (bool $online) {
    $inverter = Inverter::factory()->create();
    InverterStatus::factory()
        ->for($inverter)
        ->state([
            'is_online' => $online,
            'recorded_at' => now(),
            'udc' => 1111.11,
            'idc' => 2222.22,
            'pac' => 3333.33,
            'pdc' => 4444.44,
        ])
        ->create();
    InverterOutput::factory()
        ->for($inverter)
        ->state([
            'recorded_at' => now(),
            'output' => 9999.99,
            'timespan' => TimespanUnit::DAY,
        ])
        ->create();

    Livewire::test(InverterList::class)
        ->assertOk()
        ->assertSee([
            __('Combined'),
            route('guests.inverters.show.combined'),
            $online ? __('Online') : __('Offline'),
            '9999.99',
            'UDC',
            '1111.11',
            'IDC',
            '2222.22',
            'PAC',
            '3333.33',
            'PDC',
            '4444.44',
            route('guests.inverters.show', ['inverter' => $inverter]),
        ]);
})->with([true, false]);

it('does not render status info if it has none', function () {
    Inverter::factory()->create();

    Livewire::test(InverterList::class)
        ->assertOk()
        ->assertSee(__('Offline'))
        ->assertDontSee([
            'UDC',
            'IDC',
            'PAC',
            'PDC',
        ]);
});

it('displays multiple inverters', function () {
    $inverters = Inverter::factory()
        ->state(['name' => fn () => fake()->unique()->name()])
        ->count(20)
        ->create();

    $livewire = Livewire::test(InverterList::class);
    $livewire->assertOk()
        ->assertSee($inverters->where('id', '<=', 10)->pluck('name')->toArray())
        ->assertDontSee($inverters->where('id', '>', 10)->pluck('name')->toArray());

    $inverters = $livewire->get('inverters');
    expect($inverters)
        ->toBeInstanceOf(LengthAwarePaginator::class)
        ->count()->toBe(10)
        ->first()->relationLoaded('latestStatus')->toBeTrue();
});

it('has breadcrumbs', function () {
    Livewire::test(InverterList::class);

    expect(count(Breadcrumbs::all()))
        ->toBe(1)
        ->and(Breadcrumbs::all()[0])
        ->label->toBe(__('Dashboard'))
        ->route->toBe(null)
        ->active->toBeTrue();
});
