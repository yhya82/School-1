<?php

namespace App\Livewire\Staff;

use App\Models\Staff;
use App\Models\StaffAttendance;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class StaffAttendanceCreate extends Component
{
    public ?int $staff_id = null;

    public string $date = '';

    public string $status = 'present';

    public function mount(): void
    {
        Gate::authorize('create', StaffAttendance::class);
    }

    protected function rules(): array
    {
        return [
            'staff_id' => 'required|exists:staff,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late',
        ];
    }

    public function save(): void
    {
        $data = $this->validate();

        $exists = StaffAttendance::where('staff_id', $data['staff_id'])
            ->whereDate('date', $data['date'])
            ->exists();

        if ($exists) {
            $this->addError('date', __('Attendance for this staff member on this date is already recorded.'));

            return;
        }

        StaffAttendance::create($data);

        $this->redirectRoute('staff.attendance.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.staff.staff-attendance-create', [
            'staffMembers' => Staff::with('user')->get(),
        ]);
    }
}
