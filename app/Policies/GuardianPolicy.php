<?php

namespace App\Policies;

use App\Models\Guardian;
use App\Models\User;

class GuardianPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'teacher']);
    }

    public function view(User $user, Guardian $guardian): bool
    {
        return $user->hasAnyRole(['admin', 'teacher']);
    }

    public function create(User $user): bool
    {
        return $user->can('students.manage');
    }

    public function update(User $user, Guardian $guardian): bool
    {
        return $user->can('students.manage');
    }

    public function delete(User $user, Guardian $guardian): bool
    {
        return $user->can('students.manage');
    }
}
