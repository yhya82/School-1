<?php

namespace App\Livewire\Students;

use App\Models\Guardian;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class GuardianIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public function mount(): void
    {
        Gate::authorize('viewAny', Guardian::class);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
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
            'guardians' => Guardian::withCount('students')
                ->when($this->search, fn ($query, $value) => $query->where(fn ($q) => $q
                    ->where('name', 'like', "%{$value}%")
                    ->orWhere('phone', 'like', "%{$value}%")))
                ->orderBy('name')
                ->paginate(15),
        ]);
    }
}
