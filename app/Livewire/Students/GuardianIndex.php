<?php

namespace App\Livewire\Students;

use App\Models\Guardian;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class GuardianIndex extends Component
{
    public function mount(): void
    {
        Gate::authorize('viewAny', Guardian::class);
    }

    public function delete(int $id): void
    {
        $guardian = Guardian::findOrFail($id);
        Gate::authorize('delete', $guardian);
        $guardian->delete();
    }

    public function render()
    {
        return view('livewire.students.guardian-index', [
            'guardians' => Guardian::withCount('students')->orderBy('name')->get(),
        ]);
    }
}
