<?php

namespace App\Policies;

use App\Models\Leave;
use App\Models\User;

class LeavePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('staff.manage');
    }

    public function view(User $user, Leave $leave): bool
    {
        return $user->can('staff.manage');
    }

    public function create(User $user): bool
    {
        return $user->can('staff.manage');
    }

    public function update(User $user, Leave $leave): bool
    {
        return $user->can('staff.manage');
    }

    public function delete(User $user, Leave $leave): bool
    {
        return $user->can('staff.manage');
    }
}
