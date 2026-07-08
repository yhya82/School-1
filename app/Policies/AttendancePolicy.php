<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\User;

class AttendancePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'teacher']);
    }

    public function view(User $user, Attendance $attendance): bool
    {
        return $user->hasAnyRole(['admin', 'teacher']);
    }

    public function create(User $user): bool
    {
        return $user->can('attendance.mark');
    }

    public function update(User $user, Attendance $attendance): bool
    {
        return $user->can('attendance.mark');
    }

    public function delete(User $user, Attendance $attendance): bool
    {
        return $user->can('academics.manage');
    }
}
