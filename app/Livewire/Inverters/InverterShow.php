<?php

namespace App\Livewire\Inverters;

use App\Enums\TimespanUnit;
use App\Models\Inverter;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class InverterShow extends Component
{
    public Inverter $inverter;

    public function render(): View
    {
        return view('livewire.inverters.inverter-show')
            ->layout(Auth::check() ? 'layouts.app' : 'layouts.guest', ['header' => $this->inverter->name ?? __('Inverter Info')]);
    }

    public function boot(): void
    {
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
}
