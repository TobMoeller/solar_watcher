<?php

namespace App\Services\Breadcrumbs;

use Illuminate\Support\Facades\Facade;

class Breadcrumbs extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return BreadcrumbService::class;
    }
}

