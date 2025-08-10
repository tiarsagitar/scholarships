<?php

namespace App\Policies;

use App\Models\User;

class DisbursementPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function markAsPaid(User $user): bool
    {
        // Assuming the user must have a specific role or permission to mark disbursements as paid
        return$user->can('disbursements.mark-paid');
    }

    public function view(User $user): bool
    {
        return $user->can('disbursements.filter');
    }

    public function uploadReceipt(User $user): bool
    {
        return $user->hasPermissionTo('disbursements.upload-receipts');
    }

    public function viewDetails(User $user): bool
    {
        return $user->hasPermissionTo('disbursements.view-details');
    }
}
