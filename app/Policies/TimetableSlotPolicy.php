<?php

namespace App\Policies;

use App\Models\TimetableSlot;
use App\Models\User;

class TimetableSlotPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'teacher']);
    }

    public function view(User $user, TimetableSlot $timetableSlot): bool
    {
        return $user->hasAnyRole(['admin', 'teacher']);
    }

    public function create(User $user): bool
    {
        return $user->can('academics.manage');
    }

    public function update(User $user, TimetableSlot $timetableSlot): bool
    {
        return $user->can('academics.manage');
    }

    public function delete(User $user, TimetableSlot $timetableSlot): bool
    {
        return $user->can('academics.manage');
    }
}
