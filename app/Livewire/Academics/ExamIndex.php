<?php

namespace App\Livewire\Academics;

use App\Models\Exam;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ExamIndex extends Component
{
    public function mount(): void
    {
        Gate::authorize('viewAny', Exam::class);
    }

    public function delete(int $id): void
    {
        $exam = Exam::findOrFail($id);
        Gate::authorize('delete', $exam);
        $exam->delete();
    }

    public function render()
    {
        return view('livewire.academics.exam-index', [
            'exams' => Exam::with('academicYear')->withCount('examSubjects')->orderByDesc('start_date')->get(),
        ]);
    }
}
