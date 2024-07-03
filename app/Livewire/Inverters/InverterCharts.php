<?php

namespace App\Livewire\Inverters;

use App\Enums\TimespanUnit;
use App\Models\Inverter;
use App\Models\InverterOutput;
use App\Models\InverterStatus;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;

class InverterCharts extends Component
{
    public ?Inverter $inverter = null;

    #[Url]
    public ?int $selectedYear = null;

    #[Url]
    public ?int $selectedMonth = null;

    #[Url]
    public ?int $selectedDay = null;

    public function render(): View
    {
        return view('livewire.inverters.inverter-charts');
    }

    public function mount(): void
    {
        $this->selectedYear ??= $this->selectableYears[0] ?? null;
    }

    /**
     * @return array<int, string>
     */
    #[Computed]
    public function selectableYears(): array
    {
        return InverterOutput::query()
            ->when($this->inverter, fn (Builder $query) => $query->where('inverter_id', $this->inverter->id))
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
        return InverterOutput::query()
            ->when($this->inverter, fn (Builder $query) => $query->where('inverter_id', $this->inverter->id))
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
     * @return array<int, string>
     */
    #[Computed]
    public function selectableDays(): array
    {
        if (! (
            $this->inverter &&
            $this->selectedYear &&
            $this->selectedMonth
        )) {
            return [];
        }

        return InverterStatus::query()
            ->where('inverter_id', $this->inverter->id)
            ->when(
                DB::getDefaultConnection() === 'mysql',
                fn (Builder $query) => $query->selectRaw('DAY(recorded_at) as day'),
                fn (Builder $query) => $query->selectRaw('CAST(strftime("%d", recorded_at) AS INTEGER) as day'),
            )
            ->whereYear('recorded_at', $this->selectedYear)
            ->whereMonth('recorded_at', $this->selectedMonth)
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('day')
            ->toArray();
    }

    /**
     * @return array<string, string|array<string, mixed>>
     */
    public function getMonthlyOutputForYear(): array
    {
        if (! (
            $this->selectedYear &&
            $date = Carbon::create($this->selectedYear)
        )) {
            return ['status' => '400', 'message' => 'Invalid Date'];
        }

        $output = InverterOutput::query()
            ->when(
                $this->inverter,
                fn (Builder $query) => $query->select('output', 'recorded_at')->where('inverter_id', $this->inverter->id),
                fn (Builder $query) => $query->selectRaw('SUM(output) as output, recorded_at')->groupBy('recorded_at')
            )
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
                        'data' => $range->map(fn (int $month) => (string) ($output->where('recorded_at', $date->setMonth($month)->startOfMonth())->first()?->output ?? '0')),
                        'yAxisID' => 'left-y-axis',
                    ],
                ],
            ],
            'options' => [
                'scales' => [
                    'left-y-axis' => [
                        'type' => 'linear',
                        'position' => 'left',
                        'title' => [
                            'display' => true,
                            'text' => __('kWh'),
                            'align' => 'end',
                            'color' => '#eab308',
                        ],
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
        if (! (
            $this->selectedYear &&
            $this->selectedMonth &&
            $date = Carbon::create($this->selectedYear, $this->selectedMonth)
        )) {
            return ['status' => '400', 'message' => 'Invalid Date'];
        }

        $output = InverterOutput::query()
            ->when(
                $this->inverter,
                fn (Builder $query) => $query->select('output', 'recorded_at')->where('inverter_id', $this->inverter->id),
                fn (Builder $query) => $query->selectRaw('SUM(output) as output, recorded_at')->groupBy('recorded_at')
            )
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
                        'data' => $range->map(fn (int $day) => (string) ($output->where('recorded_at', $date->setDay($day))->first()?->output ?? '0')),
                        'yAxisID' => 'left-y-axis',
                    ],
                ],
            ],
            'options' => [
                'scales' => [
                    'left-y-axis' => [
                        'type' => 'linear',
                        'position' => 'left',
                        'title' => [
                            'display' => true,
                            'text' => __('kWh'),
                            'align' => 'end',
                            'color' => '#eab308',
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array<string, string|array<string, mixed>>
     */
    public function getStatusForDay(): array
    {
        if (empty($this->inverter)) {
            return ['status' => '400', 'message' => 'Missing Inverter'];
        }

        if (! (
            $this->selectedYear &&
            $this->selectedMonth &&
            $this->selectedDay &&
            $date = Carbon::create($this->selectedYear, $this->selectedMonth, $this->selectedDay)
        )) {
            return ['status' => '400', 'message' => 'Invalid Date'];
        }

        $status = InverterStatus::query()
            ->where('inverter_id', $this->inverter->id)
            ->whereDate('recorded_at', $date)
            ->orderBy('recorded_at')
            ->get();

        return [
            'status' => '200',
            'data' => [
                'labels' => $status->map(fn (InverterStatus $inverterStatus) => $inverterStatus->recorded_at->format('H:i')),
                'datasets' => [
                    [
                        'label' => __('UDC in V'),
                        'data' => $status->map(fn (InverterStatus $inverterStatus) => (string) ($inverterStatus->udc ?? '0')),
                        'borderColor' => $udcColor = '#60a5fa',
                        'backgroundColor' => $udcColor.($transparency = '30'),
                        'yAxisID' => 'right-y-axis-1',
                    ],
                    [
                        'label' => __('IDC in A'),
                        'data' => $status->map(fn (InverterStatus $inverterStatus) => (string) ($inverterStatus->idc ?? '0')),
                        'borderColor' => $idcColor = '#f87171',
                        'backgroundColor' => $idcColor.$transparency,
                        'yAxisID' => 'right-y-axis-2',
                    ],
                    [
                        'label' => __('PAC in W'),
                        'data' => $status->map(fn (InverterStatus $inverterStatus) => (string) ($inverterStatus->pac ?? '0')),
                        'borderColor' => $pacColor = '#eab308',
                        'backgroundColor' => $pacColor.$transparency,
                        'yAxisID' => 'left-y-axis',
                    ],
                    [
                        'label' => __('PDC in W'),
                        'data' => $status->map(fn (InverterStatus $inverterStatus) => (string) ($inverterStatus->pdc ?? '0')),
                        'borderColor' => $pdcColor = '#ca8a04',
                        'backgroundColor' => $pdcColor.$transparency,
                        'yAxisID' => 'left-y-axis',
                    ],
                ],
            ],
            'options' => [
                'scales' => [
                    'left-y-axis' => [
                        'type' => 'linear',
                        'position' => 'left',
                        'title' => [
                            'display' => true,
                            'text' => __('Watt'),
                            'align' => 'end',
                            'color' => $pacColor,
                        ],
                    ],
                    'right-y-axis-1' => [
                        'type' => 'linear',
                        'position' => 'right',
                        'suggestedMax' => $status->max('udc') * 1.5,
                        'title' => [
                            'display' => true,
                            'text' => __('Volt'),
                            'align' => 'end',
                            'color' => $udcColor,
                        ],
                    ],
                    'right-y-axis-2' => [
                        'type' => 'linear',
                        'position' => 'right',
                        'suggestedMax' => $status->max('idc') * 3,
                        'title' => [
                            'display' => true,
                            'text' => __('Ampere'),
                            'align' => 'end',
                            'color' => $idcColor,
                        ],
                    ],
                ],
            ],
        ];
    }
}
