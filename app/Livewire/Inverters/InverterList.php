<?php

namespace App\Livewire\Inverters;

use App\Enums\TimespanUnit;
use App\Models\Inverter;
use App\Services\Breadcrumbs\Breadcrumb;
use App\Services\Breadcrumbs\Breadcrumbs;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class InverterList extends Component
{
    public function render(): View
    {
        Breadcrumbs::add(new Breadcrumb(__('Dashboard'), null, true));

        return view('livewire.inverters.inverter-list')
            ->layout(Auth::check() ? 'layouts.app' : 'layouts.guest', ['header' => __('Inverters')]);
    }

    /**
     * @return LengthAwarePaginator<Inverter>
     */
    #[Computed]
    public function inverters(): LengthAwarePaginator
    {
        return Inverter::query()
            ->with([
                'latestStatus',
                'outputs' => function (Builder $query) {
                    $query->whereDate('recorded_at', now())
                        ->where('timespan', TimespanUnit::DAY);
                },
            ])
            ->paginate(10);
    }
}
