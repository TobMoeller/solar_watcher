<?php

namespace App\Livewire\Inverters;

use App\Enums\TimespanUnit;
use App\Models\Inverter;
use App\Services\Breadcrumbs\Breadcrumb;
use App\Services\Breadcrumbs\Breadcrumbs;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Throwable;

class InverterShow extends Component
{
    public Inverter $inverter;

    #[Url]
    public ?int $selectedYear = null;

    #[Url]
    public ?int $selectedMonth = null;

    public function render(): View
    {
        $header = $this->inverter->name ?? __('Inverter Info');

        Breadcrumbs::add(new Breadcrumb(__('Dashboard'), route('guests.inverters.list')));
        Breadcrumbs::add(new Breadcrumb($header, null, true));

        return view('livewire.inverters.inverter-show')
            ->layout(Auth::check() ? 'layouts.app' : 'layouts.guest', ['header' => $header]);
    }

    public function mount(): void
    {
        $this->selectedYear ??= $this->selectableYears[0] ?? null;
    }

    public function boot(): void
    {
        // eager load inverter relations for inverter details view
        $this->inverter->loadMissing([
            'latestStatus',
            'outputs' => function (Builder $query) {
                $query
                    ->where(function (Builder $query) {
                        $query->whereDate('recorded_at', now())
                            ->where('timespan', TimespanUnit::DAY);
                    })
                    ->orWhere(function (Builder $query) {
                        $query->whereDate('recorded_at', now()->startOfMonth())
                            ->where('timespan', TimespanUnit::MONTH);
                    })
                    ->orWhere(function (Builder $query) {
                        $query->whereDate('recorded_at', now()->startOfYear())
                            ->where('timespan', TimespanUnit::YEAR);
                    });
            },
        ]);
    }

    /**
     * @return array<int, string>
     */
    #[Computed]
    public function selectableYears(): array
    {
        return $this->inverter->outputs()
            ->when(
                DB::getDefaultConnection() === 'mysql',
                fn (Builder $query) => $query->selectRaw('YEAR(recorded_at) as year'),
                fn (Builder $query) => $query->selectRaw('CAST(strftime("%Y", recorded_at) AS INTEGER) as year'),
            )
            ->where('timespan', TimespanUnit::MONTH)
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();
    }

    /**
     * @return array<int, string>
     */
    #[Computed]
    public function selectableMonths(): array
    {
        return $this->inverter->outputs()
            ->when(
                DB::getDefaultConnection() === 'mysql',
                fn (Builder $query) => $query->selectRaw('MONTH(recorded_at) as month'),
                fn (Builder $query) => $query->selectRaw('CAST(strftime("%m", recorded_at) AS INTEGER) as month'),
            )
            ->whereYear('recorded_at', $this->selectedYear)
            ->where('timespan', TimespanUnit::DAY)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('month')
            ->toArray();
    }

    /**
     * @return array<string, string|array<string, mixed>>
     */
    public function getMonthlyOutputForYear(): array
    {
        if (!(
            $this->selectedYear &&
            $date = Carbon::create($this->selectedYear)
        )) {
            return ['status' => '400', 'message' => 'Invalid Date'];
        };

        $output = $this->inverter->outputs()
            ->whereYear('recorded_at', $this->selectedYear)
            ->where('timespan', TimespanUnit::MONTH)
            ->orderBy('recorded_at')
            ->get();

        $range = Collection::make(range(1, 12));

        return [
            'status' => '200',
            'data' => [
                'labels' => $range->map(fn (int $month) => $date->setMonth($month)->locale('EN_en')->monthName),
                'datasets' => [
                    [
                        'label' => __('Output in kWh for :year', ['year' => $this->selectedYear]),
                        'data' => $range->map(fn (int $month) => $output->where('recorded_at', $date->setMonth($month)->startOfMonth())->first()?->output ?? '0'),
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array<string, string|array<string, mixed>>
     */
    public function getDailyOutputForMonth(): array
    {
        if(!(
            $this->selectedYear &&
            $this->selectedMonth &&
            $date = Carbon::create($this->selectedYear, $this->selectedMonth)
        )) {
            return ['status' => '400', 'message' => 'Invalid Date'];
        }

        $output = $this->inverter->outputs()
            ->whereYear('recorded_at', $this->selectedYear)
            ->whereMonth('recorded_at', $this->selectedMonth)
            ->where('timespan', TimespanUnit::DAY)
            ->orderBy('recorded_at')
            ->get();

        $range = Collection::make(range(1, $date->daysInMonth()));

        return [
            'status' => '200',
            'data' => [
                'labels' => $range,
                'datasets' => [
                    [
                        'label' => __('Output in kWh for :month :year', ['month' => $date->locale('EN_en')->monthName, 'year' => $this->selectedYear]),
                        'data' => $range->map(fn (int $day) => $output->where('recorded_at', $date->setDay($day))->first()?->output ?? '0'),
                    ],
                ]
            ],
        ];
    }
}
