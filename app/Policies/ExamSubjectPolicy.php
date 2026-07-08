<?php

namespace App\Policies;

use App\Models\ExamSubject;
use App\Models\User;

class ExamSubjectPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'teacher']);
    }

    public function view(User $user, ExamSubject $examSubject): bool
    {
        return $user->hasAnyRole(['admin', 'teacher']);
    }

    public function create(User $user): bool
    {
        return $user->can('academics.manage');
    }

    public function update(User $user, ExamSubject $examSubject): bool
    {
        return $user->can('academics.manage');
    }

    public function delete(User $user, ExamSubject $examSubject): bool
    {
        return $user->can('academics.manage');
    }

    /**
     * Whether the user may enter/edit marks for this exam-subject:
     * admins always can; teachers only for the class-subject they're assigned to teach.
     */
    public function enterResults(User $user, ExamSubject $examSubject): bool
    {
        if ($user->can('academics.manage')) {
            return true;
        }

        return $user->can('results.enter')
            && $user->staff
            && $examSubject->classSubject->teacher_id === $user->staff->id;
    }
}
