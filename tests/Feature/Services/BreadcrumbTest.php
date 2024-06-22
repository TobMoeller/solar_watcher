<?php

use App\Models\User;
use App\Services\Breadcrumbs\Breadcrumb;
use App\Services\Breadcrumbs\Breadcrumbs;
use Illuminate\Support\Facades\Blade;

use function Pest\Laravel\actingAs;

it('adds breadcrumbs', function () {
    Breadcrumbs::add(new Breadcrumb('::label::', '::route::', false));
    Breadcrumbs::add(new Breadcrumb('::label2::', '::route2::', true));

    expect(Breadcrumbs::all()[0])
        ->toBeInstanceOf(Breadcrumb::class)
        ->label->toBe('::label::')
        ->route->toBe('::route::')
        ->active->toBeFalse()
        ->and(Breadcrumbs::all()[1])
        ->toBeInstanceOf(Breadcrumb::class)
        ->label->toBe('::label2::')
        ->route->toBe('::route2::')
        ->active->toBeTrue();
});

it('renders breadcrumbs in guest layout', function () {
    Breadcrumbs::add(new Breadcrumb('::label::', '::route::', false));
    Breadcrumbs::add(new Breadcrumb('::label2::', '::route2::', true));

    $view = Blade::render(<<<BLADE
        <x-guest-layout>
            TEST
        </x-guest-layout>
    BLADE);

    expect($view)
        ->toContain(
            '::label::',
            '::route::',
            '::label2::',
            '::route2::',
        );
});

it('renders breadcrumbs in app layout', function () {
    Breadcrumbs::add(new Breadcrumb('::label::', '::route::', false));
    Breadcrumbs::add(new Breadcrumb('::label2::', '::route2::', true));

    actingAs(User::factory()->create());

    $view = Blade::render(<<<BLADE
        <x-app-layout>
            TEST
        </x-app-layout>
    BLADE);

    expect($view)
        ->toContain(
            '::label::',
            '::route::',
            '::label2::',
            '::route2::',
        );
});
