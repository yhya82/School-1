<?php

namespace App\Livewire\Academics;

use App\Models\Exam;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ExamIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public function mount(): void
    {
        Gate::authorize('viewAny', Exam::class);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
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
            'exams' => Exam::with('academicYear')->withCount('examSubjects')
                ->when($this->search, fn ($query, $value) => $query->where('name', 'like', "%{$value}%"))
                ->orderByDesc('start_date')
                ->paginate(15),
        ]);
    }
}
