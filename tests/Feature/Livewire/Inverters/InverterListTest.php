<?php

use App\Livewire\Inverters\InverterList;
use App\Models\Inverter;
use App\Models\InverterStatus;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Livewire\Livewire;

it('renders inverter list', function (bool $online) {
    $inverter = Inverter::factory()->create();
    InverterStatus::factory()
        ->for($inverter)
        ->state([
            'is_online' => $online,
            'udc' => 1111.11,
            'idc' => 2222.22,
            'pac' => 3333.33,
            'pdc' => 4444.44,
        ])
        ->create();

    Livewire::test(InverterList::class)
        ->assertOk()
        ->assertSee([
            $online ? __('Online') : __('Offline'),
            'UDC',
            '1111.11',
            'IDC',
            '2222.22',
            'PAC',
            '3333.33',
            'PDC',
            '4444.44',
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
