<?php

namespace App\Livewire\Staff;

use App\Models\Staff;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class StaffIndex extends Component
{
    public function mount(): void
    {
        Gate::authorize('viewAny', Staff::class);
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
            'staffMembers' => Staff::with('user')->orderBy('employee_no')->get(),
        ]);
    }
}
