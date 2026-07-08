<?php

namespace App\Livewire\Academics;

use App\Models\ClassSubject;
use App\Models\SchoolClass;
use App\Models\Staff;
use App\Models\Subject;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ClassSubjectEdit extends Component
{
    public ClassSubject $assignment;

    public ?int $class_id = null;

    public ?int $subject_id = null;

    public ?int $teacher_id = null;

    public function mount(ClassSubject $classSubject): void
    {
        Gate::authorize('update', $classSubject);

        $this->assignment = $classSubject;
        $this->class_id = $classSubject->class_id;
        $this->subject_id = $classSubject->subject_id;
        $this->teacher_id = $classSubject->teacher_id;
    }

    protected function rules(): array
    {
        return [
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'nullable|exists:staff,id',
        ];
    }

    public function save(): void
    {
        Gate::authorize('update', $this->assignment);

        $data = $this->validate();

        $exists = ClassSubject::where('class_id', $data['class_id'])
            ->where('subject_id', $data['subject_id'])
            ->where('id', '!=', $this->assignment->id)
            ->exists();

        if ($exists) {
            $this->addError('subject_id', __('This subject is already assigned to this class.'));

            return;
        }

        $this->assignment->update($data);

        $this->redirectRoute('academics.class-subjects.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.academics.class-subject-edit', [
            'classes' => SchoolClass::orderBy('name')->get(),
            'subjects' => Subject::orderBy('name')->get(),
            'teachers' => Staff::with('user')->get(),
        ]);
    }
}
