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
class ClassSubjects extends Component
{
    public ?int $editingId = null;

    public ?int $class_id = null;

    public ?int $subject_id = null;

    public ?int $teacher_id = null;

    public function mount(): void
    {
        Gate::authorize('viewAny', ClassSubject::class);
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

        if ($this->editingId) {
            $assignment = ClassSubject::findOrFail($this->editingId);
            Gate::authorize('update', $assignment);
            $assignment->update($data);
        } else {
            Gate::authorize('create', ClassSubject::class);

            $exists = ClassSubject::where('class_id', $data['class_id'])
                ->where('subject_id', $data['subject_id'])
                ->exists();

            if ($exists) {
                $this->addError('subject_id', __('This subject is already assigned to this class.'));

                return;
            }

            ClassSubject::create($data);
        }

        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $assignment = ClassSubject::findOrFail($id);
        Gate::authorize('update', $assignment);

        $this->editingId = $assignment->id;
        $this->class_id = $assignment->class_id;
        $this->subject_id = $assignment->subject_id;
        $this->teacher_id = $assignment->teacher_id;
    }

    public function delete(int $id): void
    {
        $assignment = ClassSubject::findOrFail($id);
        Gate::authorize('delete', $assignment);
        $assignment->delete();
    }

    public function resetForm(): void
    {
        $this->reset(['editingId', 'class_id', 'subject_id', 'teacher_id']);
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.academics.class-subjects', [
            'assignments' => ClassSubject::with(['schoolClass', 'subject', 'teacher.user'])
                ->join('classes', 'classes.id', '=', 'class_subject.class_id')
                ->orderBy('classes.name')
                ->select('class_subject.*')
                ->get(),
            'classes' => SchoolClass::orderBy('name')->get(),
            'subjects' => Subject::orderBy('name')->get(),
            'teachers' => Staff::with('user')->get(),
        ]);
    }
}
