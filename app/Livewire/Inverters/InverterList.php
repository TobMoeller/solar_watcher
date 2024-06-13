<?php

namespace App\Livewire\Inverters;

use App\Models\Inverter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class InverterList extends Component
{
    public function render(): View
    {
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
            ->with('latestStatus')
            ->paginate(10);
    }
}
