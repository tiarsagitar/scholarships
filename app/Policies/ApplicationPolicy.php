<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Application;

class ApplicationPolicy
{
    public function create(User $user) {
        return $user->hasPermissionTo('applications.create');
    }

    public function viewOwn(User $user) {
        return $user->hasPermissionTo('applications.view-own');
    }

    public function uploadDocuments(User $user) {
        return $user->hasPermissionTo('applications.upload-documents');
    }

    public function viewDetails(User $user, Application $application) {
        return $user->id === $application->user_id || $user->hasPermissionTo('applications.view-details');
    }
}
