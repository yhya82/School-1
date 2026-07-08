<?php

namespace App\Policies;

use App\Models\StudentDocument;
use App\Models\User;

class StudentDocumentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'teacher']);
    }

    public function view(User $user, StudentDocument $studentDocument): bool
    {
        return $user->hasAnyRole(['admin', 'teacher']);
    }

    public function create(User $user): bool
    {
        return $user->can('students.manage');
    }

    public function update(User $user, StudentDocument $studentDocument): bool
    {
        return $user->can('students.manage');
    }

    public function delete(User $user, StudentDocument $studentDocument): bool
    {
        return $user->can('students.manage');
    }
}
