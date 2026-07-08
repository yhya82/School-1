<?php

namespace App\Policies;

use App\Models\Staff;
use App\Models\User;

class StaffPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'teacher']);
    }

    public function view(User $user, Staff $staff): bool
    {
        return $user->hasAnyRole(['admin', 'teacher']);
    }

    public function create(User $user): bool
    {
        return $user->can('staff.manage');
    }

    public function update(User $user, Staff $staff): bool
    {
        return $user->can('staff.manage');
    }

    public function delete(User $user, Staff $staff): bool
    {
        return $user->can('staff.manage');
    }
}
