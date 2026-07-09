<?php

namespace App\Livewire\ParentPortal;

use App\Models\ExamResult;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ChildResults extends Component
{
    public Student $student;

    public function mount(Student $student): void
    {
        $guardian = Auth::user()->guardian;
        abort_unless($guardian && $guardian->students()->where('students.id', $student->id)->exists(), 403);

        $this->student = $student;
    }

    public function render()
    {
        return view('livewire.parent-portal.child-results', [
            'results' => ExamResult::where('student_id', $this->student->id)
                ->with(['examSubject.exam', 'examSubject.classSubject.subject'])
                ->get()
                ->sortByDesc(fn ($result) => $result->examSubject->exam->start_date),
        ]);
    }
}
