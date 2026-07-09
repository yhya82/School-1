<?php

namespace App\Livewire\Staff;

use App\Models\Staff;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class StaffIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public function mount(): void
    {
        Gate::authorize('viewAny', Staff::class);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        $staff = Staff::findOrFail($id);
        Gate::authorize('delete', $staff);
        $user = $staff->user;
        $staff->delete();
        $user?->delete();
    }

    public function render()
    {
        return view('livewire.staff.staff-index', [
            'staffMembers' => Staff::with('user')
                ->when($this->search, fn ($query, $value) => $query->where(fn ($q) => $q
                    ->where('employee_no', 'like', "%{$value}%")
                    ->orWhereHas('user', fn ($q2) => $q2->where('name', 'like', "%{$value}%"))))
                ->orderBy('employee_no')
                ->paginate(15),
        ]);
    }
}
