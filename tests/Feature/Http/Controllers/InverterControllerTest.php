<?php

use App\Enums\TimespanUnit;
use App\Livewire\Inverters\InverterCharts;
use App\Models\Inverter;
use App\Models\InverterOutput;
use App\Models\User;
use App\Services\Breadcrumbs\Breadcrumbs;
use Illuminate\Database\Eloquent\Factories\Sequence;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('showCombined route renders successfully', function (bool $withUser) {
    $inverters = Inverter::factory()->count(2)->create();
    InverterOutput::factory()
        ->state(new Sequence(
            ['inverter_id' => $inverters->get(0)->id],
            ['inverter_id' => $inverters->get(1)->id],
        ))
        ->state(new Sequence(
            [
                'recorded_at' => now(),
                'output' => 1111.11,
                'timespan' => TimespanUnit::DAY,
            ],
            [
                'recorded_at' => now()->startOfMonth(),
                'output' => 2222.22,
                'timespan' => TimespanUnit::MONTH,
            ],
            [
                'recorded_at' => now()->startOfYear(),
                'output' => 3333.33,
                'timespan' => TimespanUnit::YEAR,
            ],
        ))
        ->count(6)
        ->create();

    if ($withUser) {
        actingAs(User::factory()->create());
    }

    get(route('guests.inverters.show.combined'))
        ->assertOk()
        ->assertSeeLivewire(InverterCharts::class)
        ->assertSee([
            __('Status'),
            __('History'),
            'day:',
            '2222.22',
            'month:',
            '4444.44',
            'year:',
            '6666.66',
        ]);
})->with([true, false]);

it('showCombined route has breadcrumbs', function () {
    get(route('guests.inverters.show.combined'));

    expect(count(Breadcrumbs::all()))
        ->toBe(2)
        ->and(Breadcrumbs::all()[0])
        ->label->toBe(__('Dashboard'))
        ->route->toBe(route('guests.inverters.list'))
        ->and(Breadcrumbs::all()[1])
        ->label->toBe(__('Inverters Combined'))
        ->route->toBe(null)
        ->active->toBeTrue();
});
