<?php

namespace App\Livewire\Academics;

use App\Models\Subject;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class SubjectIndex extends Component
{
    public function mount(): void
    {
        Gate::authorize('viewAny', Subject::class);
    }

    public function delete(int $id): void
    {
        $subject = Subject::findOrFail($id);
        Gate::authorize('delete', $subject);
        $subject->delete();
    }

    public function render()
    {
        return view('livewire.academics.subject-index', [
            'subjects' => Subject::orderBy('name')->get(),
        ]);
    }
}
