<?php

namespace App\Livewire\Academics;

use App\Models\ExamResult;
use App\Models\ExamSubject;
use App\Models\Student;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ExamResultsEntry extends Component
{
    public ExamSubject $examSubject;

    public array $marks = [];

    public function mount(ExamSubject $examSubject): void
    {
        Gate::authorize('enterResults', $examSubject);

        $this->examSubject = $examSubject->load('classSubject.schoolClass', 'classSubject.subject');

        $existing = ExamResult::where('exam_subject_id', $examSubject->id)->pluck('marks_obtained', 'student_id');

        foreach ($this->students() as $student) {
            $this->marks[$student->id] = $existing[$student->id] ?? '';
        }
    }

    protected function students()
    {
        return Student::where('class_id', $this->examSubject->classSubject->class_id)
            ->where('status', 'active')
            ->with('user')
            ->orderBy('admission_no')
            ->get();
    }

    protected function gradeFor(float $marks): string
    {
        $percentage = $this->examSubject->max_marks > 0 ? ($marks / $this->examSubject->max_marks) * 100 : 0;

        return match (true) {
            $percentage >= 90 => 'A',
            $percentage >= 75 => 'B',
            $percentage >= 60 => 'C',
            $marks >= $this->examSubject->pass_marks => 'D',
            default => 'F',
        };
    }

    public function save(): void
    {
        Gate::authorize('enterResults', $this->examSubject);

        $rules = [];
        foreach ($this->marks as $studentId => $value) {
            $rules["marks.$studentId"] = 'nullable|numeric|min:0|max:'.$this->examSubject->max_marks;
        }
        $this->validate($rules);

        foreach ($this->marks as $studentId => $value) {
            if ($value === '' || $value === null) {
                continue;
            }

            ExamResult::updateOrCreate(
                ['exam_subject_id' => $this->examSubject->id, 'student_id' => $studentId],
                ['marks_obtained' => $value, 'grade' => $this->gradeFor((float) $value)]
            );
        }

        $this->redirectRoute('academics.exams.edit', ['exam' => $this->examSubject->exam_id], navigate: true);
    }

    public function render()
    {
        return view('livewire.academics.exam-results-entry', [
            'students' => $this->students(),
        ]);
    }
}
