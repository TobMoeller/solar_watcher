<?php

namespace App\Policies;

use App\Models\Inverter;
use App\Models\User;

class InverterPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->tokenCan('inverters:viewAny');
    }

    public function view(User $user, Inverter $inverter): bool
    {
        return $user->tokenCan('inverters:view');
    }

    public function create(User $user): bool
    {
        return $user->tokenCan('inverters:create');
    }

    public function update(User $user, Inverter $inverter): bool
    {
        return $user->tokenCan('inverters:update');
    }

    public function delete(User $user, Inverter $inverter): bool
    {
        return $user->tokenCan('inverters:delete');
    }

    // public function restore(User $user, Inverter $inverter): bool
    // {
    //     return true;
    // }

    // public function forceDelete(User $user, Inverter $inverter): bool
    // {
    //     return true;
    // }
}
