<?php

namespace App\Services\Breadcrumbs;

class BreadcrumbService
{
    /** @var array<Breadcrumb> */
    public array $breadcrumbs = [];

    public function add(Breadcrumb $breadcrumb): void
    {
        $this->breadcrumbs[] = $breadcrumb;
    }

    /**
     * @return array<Breadcrumb>
     */
    public function all(): array
    {
        return $this->breadcrumbs;
    }
}
