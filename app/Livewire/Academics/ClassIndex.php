<?php

namespace App\Livewire\Academics;

use App\Models\SchoolClass;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ClassIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public function mount(): void
    {
        Gate::authorize('viewAny', SchoolClass::class);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        $class = SchoolClass::findOrFail($id);
        Gate::authorize('delete', $class);
        $class->delete();
    }

    public function render()
    {
        return view('livewire.academics.class-index', [
            'classes' => SchoolClass::withCount('sections')
                ->when($this->search, fn ($query, $value) => $query->where('name', 'like', "%{$value}%"))
                ->orderBy('name')
                ->paginate(15),
        ]);
    }
}
