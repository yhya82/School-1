<?php

namespace App\Livewire\Academics;

use App\Models\SchoolClass;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ClassIndex extends Component
{
    public function mount(): void
    {
        Gate::authorize('viewAny', SchoolClass::class);
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
            'classes' => SchoolClass::withCount('sections')->orderBy('name')->get(),
        ]);
    }
}
