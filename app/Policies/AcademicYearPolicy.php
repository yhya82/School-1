<?php

namespace App\Policies;

use App\Models\AcademicYear;
use App\Models\User;

class AcademicYearPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'teacher']);
    }

    public function view(User $user, AcademicYear $academicYear): bool
    {
        return $user->hasAnyRole(['admin', 'teacher']);
    }

    public function create(User $user): bool
    {
        return $user->can('academics.manage');
    }

    public function update(User $user, AcademicYear $academicYear): bool
    {
        return $user->can('academics.manage');
    }

    public function delete(User $user, AcademicYear $academicYear): bool
    {
        return $user->can('academics.manage');
    }
}
