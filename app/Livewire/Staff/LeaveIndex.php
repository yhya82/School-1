<?php

namespace App\Livewire\Staff;

use App\Models\Leave;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class LeaveIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public function mount(): void
    {
        Gate::authorize('viewAny', Leave::class);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
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
            'leaves' => Leave::with(['staff.user', 'approver.user'])
                ->when($this->search, fn ($query, $value) => $query->whereHas('staff.user', fn ($q) => $q->where('name', 'like', "%{$value}%")))
                ->orderByDesc('start_date')
                ->paginate(15),
        ]);
    }
}
