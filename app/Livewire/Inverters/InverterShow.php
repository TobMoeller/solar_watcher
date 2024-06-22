<?php

namespace App\Livewire\Inverters;

use App\Enums\TimespanUnit;
use App\Models\Inverter;
use App\Services\Breadcrumbs\Breadcrumb;
use App\Services\Breadcrumbs\Breadcrumbs;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class InverterShow extends Component
{
    public Inverter $inverter;

    public function render(): View
    {
        $header = $this->inverter->name ?? __('Inverter Info');

        Breadcrumbs::add(new Breadcrumb(__('Dashboard'), route('guests.inverters.list')));
        Breadcrumbs::add(new Breadcrumb($header, null, true));

        return view('livewire.inverters.inverter-show')
            ->layout(Auth::check() ? 'layouts.app' : 'layouts.guest', ['header' => $header]);
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
