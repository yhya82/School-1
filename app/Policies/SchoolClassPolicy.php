<?php

namespace App\Policies;

use App\Models\SchoolClass;
use App\Models\User;

class SchoolClassPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'teacher']);
    }

    public function view(User $user, SchoolClass $schoolClass): bool
    {
        return $user->hasAnyRole(['admin', 'teacher']);
    }

    public function create(User $user): bool
    {
        return $user->can('academics.manage');
    }

    public function update(User $user, SchoolClass $schoolClass): bool
    {
        return $user->can('academics.manage');
    }

    public function delete(User $user, SchoolClass $schoolClass): bool
    {
        return $user->can('academics.manage');
    }
}
