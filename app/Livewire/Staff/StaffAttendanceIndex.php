<?php

namespace App\Livewire\Staff;

use App\Models\StaffAttendance;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class StaffAttendanceIndex extends Component
{
    public function mount(): void
    {
        Gate::authorize('viewAny', StaffAttendance::class);
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
            'attendances' => StaffAttendance::with('staff.user')->orderByDesc('date')->get(),
        ]);
    }
}
