<?php

namespace App\Livewire\Staff;

use App\Models\StaffAttendance;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class StaffAttendanceIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public function mount(): void
    {
        Gate::authorize('viewAny', StaffAttendance::class);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        $attendance = StaffAttendance::findOrFail($id);
        Gate::authorize('delete', $attendance);
        $attendance->delete();
    }

    public function render()
    {
        return view('livewire.staff.staff-attendance-index', [
            'attendances' => StaffAttendance::with('staff.user')
                ->when($this->search, fn ($query, $value) => $query->whereHas('staff.user', fn ($q) => $q->where('name', 'like', "%{$value}%")))
                ->orderByDesc('date')
                ->paginate(15),
        ]);
    }
}
