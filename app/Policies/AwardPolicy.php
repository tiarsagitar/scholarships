<?php

namespace App\Policies;

use App\Models\User;

class AwardPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('awards.create');
    }

    public function createDisbursementSchedule(User $user)
    {
        return $user->hasPermissionTo('awards.create-schedules');
    }

    public function listOwn(User $user)
    {
        return $user->hasPermissionTo('awards.list-own');
    }

    public function viewDisbursements(User $user)
    {
        return $user->hasPermissionTo('disbursements.view');
    }
}
