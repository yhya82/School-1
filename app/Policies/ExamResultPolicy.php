<?php

namespace App\Policies;

use App\Models\ExamResult;
use App\Models\User;

class ExamResultPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'teacher']);
    }

    public function view(User $user, ExamResult $examResult): bool
    {
        return $user->hasAnyRole(['admin', 'teacher']);
    }

    public function delete(User $user, ExamResult $examResult): bool
    {
        return $user->can('academics.manage');
    }
}
