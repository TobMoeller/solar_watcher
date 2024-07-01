<?php

namespace App\Http\Controllers;

use App\Enums\TimespanUnit;
use App\Models\InverterOutput;
use App\Services\Breadcrumbs\Breadcrumb;
use App\Services\Breadcrumbs\Breadcrumbs;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class InverterController extends Controller
{
    public function showCombined(): View
    {
        $header = __('Inverters Combined');

        Breadcrumbs::add(new Breadcrumb(__('Dashboard'), route('guests.inverters.list')));
        Breadcrumbs::add(new Breadcrumb($header, null, true));

        return view('inverters-show-combined', [
            'layout' => Auth::check() ? 'app-layout' : 'guest-layout',
            'header' => $header,
            'outputDay' => $this->outputDay(),
            'outputMonth' => $this->outputMonth(),
            'outputYear' => $this->outputYear(),
        ]);
    }

    protected function outputDay(): ?float
    {
        $output = InverterOutput::query()
            ->where('timespan', TimespanUnit::DAY)
            ->whereDate('recorded_at', now())
            ->sum('output');

        return is_float($output) ? $output : null;
    }

    protected function outputMonth(): ?float
    {
        $output = InverterOutput::query()
            ->where('timespan', TimespanUnit::MONTH)
            ->whereDate('recorded_at', now()->startOfMonth())
            ->sum('output');

        return is_float($output) ? $output : null;
    }

    protected function outputYear(): ?float
    {
        $output = InverterOutput::query()
            ->where('timespan', TimespanUnit::YEAR)
            ->whereDate('recorded_at', now()->startOfYear())
            ->sum('output');

        return is_float($output) ? $output : null;
    }
}
