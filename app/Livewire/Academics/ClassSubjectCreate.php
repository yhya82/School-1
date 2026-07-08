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
class ClassSubjectCreate extends Component
{
    public ?int $class_id = null;

    public ?int $subject_id = null;

    public ?int $teacher_id = null;

    public function mount(): void
    {
        Gate::authorize('create', ClassSubject::class);
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
        $data = $this->validate();

        $exists = ClassSubject::where('class_id', $data['class_id'])
            ->where('subject_id', $data['subject_id'])
            ->exists();

        if ($exists) {
            $this->addError('subject_id', __('This subject is already assigned to this class.'));

            return;
        }

        ClassSubject::create($data);

        $this->redirectRoute('academics.class-subjects.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.academics.class-subject-create', [
            'classes' => SchoolClass::orderBy('name')->get(),
            'subjects' => Subject::orderBy('name')->get(),
            'teachers' => Staff::with('user')->get(),
        ]);
    }
}
