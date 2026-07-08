<?php

namespace App\Policies;

use App\Models\StaffAttendance;
use App\Models\User;

class StaffAttendancePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('staff.manage');
    }

    public function view(User $user, StaffAttendance $staffAttendance): bool
    {
        return $user->can('staff.manage');
    }

    public function create(User $user): bool
    {
        return $user->can('staff.manage');
    }

    public function update(User $user, StaffAttendance $staffAttendance): bool
    {
        return $user->can('staff.manage');
    }

    public function delete(User $user, StaffAttendance $staffAttendance): bool
    {
        return $user->can('staff.manage');
    }
}
