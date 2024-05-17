<?php

namespace App\Policies;

use App\Models\Inverter;
use App\Models\InverterStatus;
use App\Models\User;

class InverterStatusPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('viewAny', Inverter::class);
    }

    public function view(User $user, InverterStatus $inverterStatus): bool
    {
        return $user->can('view', $inverterStatus->inverter);
    }

    public function create(User $user): bool
    {
        return $user->can('create', Inverter::class);
    }

    public function update(User $user, InverterStatus $inverterStatus): bool
    {
        return $user->can('update', $inverterStatus->inverter);
    }

    public function delete(User $user, InverterStatus $inverterStatus): bool
    {
        return $user->can('delete', $inverterStatus->inverter);
    }

    //public function restore(User $user, InverterStatus $inverterStatus): bool
    //{
    //    //
    //}

    //public function forceDelete(User $user, InverterStatus $inverterStatus): bool
    //{
    //    //
    //}
}
