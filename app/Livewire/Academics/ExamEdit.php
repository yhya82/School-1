<?php

namespace App\Livewire\Academics;

use App\Models\AcademicYear;
use App\Models\ClassSubject;
use App\Models\Exam;
use App\Models\ExamSubject;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ExamEdit extends Component
{
    public Exam $exam;

    public ?int $academic_year_id = null;

    public string $name = '';

    public string $start_date = '';

    public string $end_date = '';

    public ?int $editingExamSubjectId = null;

    public ?int $class_subject_id = null;

    public string $exam_date = '';

    public int $max_marks = 100;

    public int $pass_marks = 40;

    public function mount(Exam $exam): void
    {
        Gate::authorize('update', $exam);

        $this->exam = $exam;
        $this->academic_year_id = $exam->academic_year_id;
        $this->name = $exam->name;
        $this->start_date = $exam->start_date->format('Y-m-d');
        $this->end_date = $exam->end_date->format('Y-m-d');
    }

    protected function rules(): array
    {
        return [
            'academic_year_id' => 'required|exists:academic_years,id',
            'name' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ];
    }

    public function save(): void
    {
        Gate::authorize('update', $this->exam);

        $data = $this->validate();

        $this->exam->update($data);

        $this->redirectRoute('academics.exams.index', navigate: true);
    }

    protected function examSubjectRules(): array
    {
        return [
            'class_subject_id' => 'required|exists:class_subject,id',
            'exam_date' => 'nullable|date',
            'max_marks' => 'required|integer|min:1',
            'pass_marks' => 'required|integer|min:0|lte:max_marks',
        ];
    }

    public function saveExamSubject(): void
    {
        $data = $this->validate($this->examSubjectRules());

        if ($this->editingExamSubjectId) {
            $examSubject = ExamSubject::findOrFail($this->editingExamSubjectId);
            Gate::authorize('update', $examSubject);
            $examSubject->update($data);
        } else {
            Gate::authorize('create', ExamSubject::class);

            $exists = ExamSubject::where('exam_id', $this->exam->id)
                ->where('class_subject_id', $data['class_subject_id'])
                ->exists();

            if ($exists) {
                $this->addError('class_subject_id', __('This class subject is already part of this exam.'));

                return;
            }

            ExamSubject::create([
                ...$data,
                'exam_id' => $this->exam->id,
            ]);
        }

        $this->resetExamSubjectForm();
    }

    public function editExamSubject(int $id): void
    {
        $examSubject = ExamSubject::findOrFail($id);
        Gate::authorize('update', $examSubject);

        $this->editingExamSubjectId = $examSubject->id;
        $this->class_subject_id = $examSubject->class_subject_id;
        $this->exam_date = (string) $examSubject->exam_date?->format('Y-m-d');
        $this->max_marks = $examSubject->max_marks;
        $this->pass_marks = $examSubject->pass_marks;
    }

    public function resetExamSubjectForm(): void
    {
        $this->reset(['editingExamSubjectId', 'class_subject_id', 'exam_date']);
        $this->max_marks = 100;
        $this->pass_marks = 40;
        $this->resetErrorBag();
    }

    public function deleteExamSubject(int $id): void
    {
        $examSubject = ExamSubject::findOrFail($id);
        Gate::authorize('delete', $examSubject);
        $examSubject->delete();
    }

    public function render()
    {
        return view('livewire.academics.exam-edit', [
            'academicYears' => AcademicYear::orderByDesc('start_date')->get(),
            'classSubjects' => ClassSubject::with(['schoolClass', 'subject'])->get(),
            'examSubjects' => ExamSubject::with(['classSubject.schoolClass', 'classSubject.subject'])
                ->where('exam_id', $this->exam->id)
                ->get(),
        ]);
    }
}
