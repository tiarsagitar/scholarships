<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Scholarship;

class ScholarshipPolicy
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
        return $user->hasPermissionTo('scholarships.list') || $user->isAdmin();
    }

    /**
     * Determine whether the user can create scholarships.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('scholarships.create') || $user->isAdmin();
    }

    /**
     * Determine whether the user can update scholarships.
     */
    public function update(User $user, Scholarship $scholarship): bool
    {
        return $user->hasPermissionTo('scholarships.update') || $user->isAdmin();
    }

    /**
     * Determine whether the user can delete scholarships.
     */
    public function delete(User $user, Scholarship $scholarship): bool
    {
        return $user->hasPermissionTo('scholarships.delete') || $user->isAdmin();
    }

    public function viewBudget(User $user, Scholarship $scholarship): bool
    {
        return $user->hasPermissionTo('budgets.view') || $user->isAdmin();
    }

    public function setBudget(User $user, Scholarship $scholarship): bool
    {
        return $user->hasPermissionTo('budgets.create') || $user->isAdmin();
    }
}
