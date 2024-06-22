<?php

namespace App\Services\Breadcrumbs;

use Illuminate\Contracts\Support\Htmlable;

class Breadcrumb
{
    public function __construct(
        public Htmlable|string $label,
        public ?string $route = null,
        public bool $active = false,
    ) {
        //
    }
}
