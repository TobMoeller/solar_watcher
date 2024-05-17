<?php

namespace App\Policies;

use App\Models\Inverter;
use App\Models\InverterOutput;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class InverterOutputPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('viewAny', Inverter::class);
    }

    public function view(User $user, InverterOutput $inverterOutput): bool
    {
        return $user->can('view', $inverterOutput->inverter);
    }

    public function create(User $user): bool
    {
        return $user->can('create', Inverter::class);
    }

    public function update(User $user, InverterOutput $inverterOutput): bool
    {
        return $user->can('update', $inverterOutput->inverter);
    }

    public function delete(User $user, InverterOutput $inverterOutput): bool
    {
        return $user->can('delete', $inverterOutput->inverter);
    }

    //public function restore(User $user, InverterOutput $inverterOutput): bool
    //{
    //    //
    //}

    //public function forceDelete(User $user, InverterOutput $inverterOutput): bool
    //{
    //    //
    //}
}
