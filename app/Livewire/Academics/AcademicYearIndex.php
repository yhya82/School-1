<?php

namespace App\Livewire\Academics;

use App\Models\AcademicYear;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AcademicYearIndex extends Component
{
    public function mount(): void
    {
        Gate::authorize('viewAny', AcademicYear::class);
    }

    public function delete(int $id): void
    {
        $year = AcademicYear::findOrFail($id);
        Gate::authorize('delete', $year);
        $year->delete();
    }

    public function render()
    {
        return view('livewire.academics.academic-year-index', [
            'years' => AcademicYear::orderByDesc('start_date')->get(),
        ]);
    }
}
