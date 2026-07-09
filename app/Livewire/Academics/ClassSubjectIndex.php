<?php

namespace App\Livewire\Academics;

use App\Models\ClassSubject;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ClassSubjectIndex extends Component
{
    use WithPagination;

    public function mount(): void
    {
        Gate::authorize('viewAny', ClassSubject::class);
    }

    public function delete(int $id): void
    {
        $assignment = ClassSubject::findOrFail($id);
        Gate::authorize('delete', $assignment);
        $assignment->delete();
    }

    public function render()
    {
        return view('livewire.academics.class-subject-index', [
            'assignments' => ClassSubject::with(['schoolClass', 'subject', 'teacher.user'])
                ->join('classes', 'classes.id', '=', 'class_subject.class_id')
                ->orderBy('classes.name')
                ->select('class_subject.*')
                ->paginate(15),
        ]);
    }
}
