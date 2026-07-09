<?php

namespace App\Livewire\ParentPortal;

use App\Models\ExamResult;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ChildResults extends Component
{
    use WithPagination;

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
            'results' => ExamResult::where('exam_results.student_id', $this->student->id)
                ->join('exam_subjects', 'exam_subjects.id', '=', 'exam_results.exam_subject_id')
                ->join('exams', 'exams.id', '=', 'exam_subjects.exam_id')
                ->with(['examSubject.exam', 'examSubject.classSubject.subject'])
                ->orderByDesc('exams.start_date')
                ->select('exam_results.*')
                ->paginate(15),
        ]);
    }
}
