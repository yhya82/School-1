<?php

namespace App\Policies;

use App\Models\ClassSubject;
use App\Models\User;

class ClassSubjectPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'teacher']);
    }

    public function view(User $user, ClassSubject $classSubject): bool
    {
        return $user->hasAnyRole(['admin', 'teacher']);
    }

    public function create(User $user): bool
    {
        return $user->can('academics.manage');
    }

    public function update(User $user, ClassSubject $classSubject): bool
    {
        return $user->can('academics.manage');
    }

    public function delete(User $user, ClassSubject $classSubject): bool
    {
        return $user->can('academics.manage');
    }
}
