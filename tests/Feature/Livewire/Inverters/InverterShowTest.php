<?php

use App\Enums\TimespanUnit;
use App\Livewire\Inverters\InverterShow;
use App\Models\Inverter;
use App\Models\InverterOutput;
use App\Models\InverterStatus;
use App\Models\User;
use App\Services\Breadcrumbs\Breadcrumbs;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Collection;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('renders successfully', function (bool $withUser) {
    $inverter = Inverter::factory()->create();

    if ($withUser) {
        actingAs(User::factory()->create());
    }

    Livewire::test(InverterShow::class, ['inverter' => $inverter])
        ->assertOk();
})->with([true, false]);

it('shows inverter details', function (bool $online) {
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
    InverterOutput::factory()
        ->for($inverter)
        ->state(new Sequence(
            [
                'recorded_at' => now(),
                'output' => 7777.77,
                'timespan' => TimespanUnit::DAY,
            ],
            [
                'recorded_at' => now()->startOfMonth(),
                'output' => 8888.88,
                'timespan' => TimespanUnit::MONTH,
            ],
            [
                'recorded_at' => now()->startOfYear(),
                'output' => 9999.99,
                'timespan' => TimespanUnit::YEAR,
            ],
        ))
        ->count(3)
        ->create();

    Livewire::test(InverterShow::class, ['inverter' => $inverter])
        ->assertSee([
            $online ? __('Online') : __('Offline'),
            'day:',
            '7777.77',
            'month:',
            '8888.88',
            'year:',
            '9999.99',
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

it('finds the correct detail data', function () {
    $inverter = Inverter::factory()->create();
    $status = InverterStatus::factory()
        ->for($inverter)
        ->create();
    $outputs = InverterOutput::factory()
        ->for($inverter)
        ->state(new Sequence(
            [
                'id' => 1,
                'recorded_at' => now(),
                'timespan' => TimespanUnit::DAY,
            ],
            [
                'id' => 2,
                'recorded_at' => now()->startOfMonth(),
                'timespan' => TimespanUnit::MONTH,
            ],
            [
                'id' => 3,
                'recorded_at' => now()->startOfYear(),
                'timespan' => TimespanUnit::YEAR,
            ],
            [
                'id' => 11,
                'recorded_at' => now()->subYear(),
                'timespan' => TimespanUnit::DAY,
            ],
            [
                'id' => 22,
                'recorded_at' => now()->subYear()->startOfMonth(),
                'timespan' => TimespanUnit::MONTH,
            ],
            [
                'id' => 33,
                'recorded_at' => now()->subYear()->startOfYear(),
                'timespan' => TimespanUnit::YEAR,
            ],
        ))
        ->count(6)
        ->create();

    $livewire = Livewire::test(InverterShow::class, ['inverter' => $inverter])
        ->get('inverter');

    expect($livewire)
        ->relationLoaded('latestStatus')->toBeTrue()
        ->relationLoaded('outputs')->toBeTrue()
        ->latestStatus->is($status)->toBeTrue()
        ->outputs->pluck('id')->toArray()->toMatchArray([1, 2, 3]);
});

it('has breadcrumbs', function () {
    $inverter = Inverter::factory()->create();

    Livewire::test(InverterShow::class, ['inverter' => $inverter]);

    expect(count(Breadcrumbs::all()))
        ->toBe(2)
        ->and(Breadcrumbs::all()[0])
        ->label->toBe(__('Dashboard'))
        ->route->toBe(route('guests.inverters.list'))
        ->and(Breadcrumbs::all()[1])
        ->label->toBe($inverter->name)
        ->route->toBe(null)
        ->active->toBeTrue();
});

it('returns selectable years', function () {
    $inverter = Inverter::factory()->create();
    InverterOutput::factory()
        ->for($inverter)
        ->state(new Sequence(
            [
                'recorded_at' => '2024-01-01',
                'timespan' => TimespanUnit::DAY,
            ],
            [
                'recorded_at' => '2022-01-01',
                'timespan' => TimespanUnit::MONTH,
            ],
            [
                'recorded_at' => '2023-01-01',
                'timespan' => TimespanUnit::MONTH,
            ],
        ))
        ->count(3)
        ->create();

    $livewire = Livewire::test(InverterShow::class, ['inverter' => $inverter]);

    expect($livewire->get('selectableYears'))
        ->toBe([2023, 2022]);
});

it('returns selectable months', function () {
    $inverter = Inverter::factory()->create();
    InverterOutput::factory()
        ->for($inverter)
        ->state(new Sequence(
            [
                'recorded_at' => '2024-01-01',
                'timespan' => TimespanUnit::MONTH,
            ],
            [
                'recorded_at' => '2024-03-01',
                'timespan' => TimespanUnit::DAY,
            ],
            [
                'recorded_at' => '2024-02-01',
                'timespan' => TimespanUnit::DAY,
            ],
        ))
        ->count(3)
        ->create();

    $livewire = Livewire::test(InverterShow::class, ['inverter' => $inverter])
        ->set('selectedYear', 2024);

    expect($livewire->get('selectableMonths'))
        ->toBe([2, 3]);
});

test('getMonthlyOutputForYear returns an error message for invalid date', function () {
    $inverter = Inverter::factory()->create();

    Livewire::test(InverterShow::class, ['inverter' => $inverter])
        ->set('selectedYear', null)
        ->call('getMonthlyOutputForYear')
        ->assertReturned(['status' => '400', 'message' => 'Invalid Date']);
});

test('getDailyOutputForMonth returns an error message for invalid date', function (?int $selectedYear, ?int $selectedMonth) {
    $inverter = Inverter::factory()->create();

    Livewire::test(InverterShow::class, ['inverter' => $inverter])
        ->set('selectedYear', $selectedYear)
        ->set('selectedMonth', $selectedMonth)
        ->call('getDailyOutputForMonth')
        ->assertReturned(['status' => '400', 'message' => 'Invalid Date']);
})->with([
    ['selectedYear' => 2024, 'selectedMonth' => null],
    ['selectedYear' => null, 'selectedMonth' => 2],
]);

it('returns monthly output for a year', function () {
    $inverter = Inverter::factory()->create();
    InverterOutput::factory()
        ->for($inverter)
        ->state(new Sequence(
            [
                'recorded_at' => '2023-01-01',
                'timespan' => TimespanUnit::MONTH,
                'output' => 1111.11,
            ],
            [
                'recorded_at' => '2024-02-01',
                'timespan' => TimespanUnit::MONTH,
                'output' => 2222.22,
            ],
            [
                'recorded_at' => '2024-03-01',
                'timespan' => TimespanUnit::MONTH,
                'output' => 3333.33,
            ],
        ))
        ->count(3)
        ->create();

    Livewire::test(InverterShow::class, ['inverter' => $inverter])
        ->set('selectedYear', 2024)
        ->call('getMonthlyOutputForYear')
        ->assertReturned([
            'status' => '200',
            'data' => [
                'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                'datasets' => [
                    [
                        'label' => 'Output in kWh for 2024',
                        'data' => ['0', '2222.22', '3333.33', '0', '0', '0', '0', '0', '0', '0', '0', '0']
                    ],
                ],
            ],
        ]);
});

it('returns daily output for a month', function () {
    $inverter = Inverter::factory()->create();
    InverterOutput::factory()
        ->for($inverter)
        ->state(new Sequence(
            [
                'recorded_at' => '2023-01-01',
                'timespan' => TimespanUnit::DAY,
                'output' => 1111.11,
            ],
            [
                'recorded_at' => '2024-01-02',
                'timespan' => TimespanUnit::DAY,
                'output' => 2222.22,
            ],
            [
                'recorded_at' => '2024-01-03',
                'timespan' => TimespanUnit::DAY,
                'output' => 3333.33,
            ],
        ))
        ->count(3)
        ->create();

    $dataset = Collection::make(range(1, 31))->map(fn () => '0')->toArray();
    $dataset[1] = '2222.22';
    $dataset[2] = '3333.33';

    Livewire::test(InverterShow::class, ['inverter' => $inverter])
        ->set('selectedYear', 2024)
        ->set('selectedMonth', 1)
        ->call('getDailyOutputForMonth')
        ->assertReturned([
            'status' => '200',
            'data' => [
                'labels' => range(1, 31),
                'datasets' => [
                    [
                        'label' => 'Output in kWh for January 2024',
                        'data' => $dataset,
                    ],
                ],
            ],
        ]);
});





