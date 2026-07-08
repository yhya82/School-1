<?php

namespace App\Livewire\Staff;

use App\Models\Leave;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class LeaveIndex extends Component
{
    public function mount(): void
    {
        Gate::authorize('viewAny', Leave::class);
    }

    public function delete(int $id): void
    {
        $leave = Leave::findOrFail($id);
        Gate::authorize('delete', $leave);
        $leave->delete();
    }

    public function render()
    {
        return view('livewire.staff.leave-index', [
            'leaves' => Leave::with(['staff.user', 'approver.user'])->orderByDesc('start_date')->get(),
        ]);
    }
}
