<?php

namespace App\Livewire\Academics;

use App\Models\Subject;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class SubjectIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public function mount(): void
    {
        Gate::authorize('viewAny', Subject::class);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
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
            'subjects' => Subject::when($this->search, fn ($query, $value) => $query->where(fn ($q) => $q
                    ->where('name', 'like', "%{$value}%")
                    ->orWhere('code', 'like', "%{$value}%")))
                ->orderBy('name')
                ->paginate(15),
        ]);
    }
}
