<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CostCategory;

class CostCategoryPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function list(User $user) 
    {
        return $user->hasPermissionTo('cost-categories.list') || $user->isAdmin();
    }

    /**
     * Determine whether the user can create cost-categories.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('cost-categories.create') || $user->isAdmin();
    }
}
