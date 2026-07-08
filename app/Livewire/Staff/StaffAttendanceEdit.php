<?php

namespace App\Livewire\Staff;

use App\Models\Staff;
use App\Models\StaffAttendance;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class StaffAttendanceEdit extends Component
{
    public StaffAttendance $attendance;

    public ?int $staff_id = null;

    public string $date = '';

    public string $status = 'present';

    public function mount(StaffAttendance $staffAttendance): void
    {
        Gate::authorize('update', $staffAttendance);

        $this->attendance = $staffAttendance;
        $this->staff_id = $staffAttendance->staff_id;
        $this->date = $staffAttendance->date->format('Y-m-d');
        $this->status = $staffAttendance->status;
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
        Gate::authorize('update', $this->attendance);

        $data = $this->validate();

        $exists = StaffAttendance::where('staff_id', $data['staff_id'])
            ->whereDate('date', $data['date'])
            ->where('id', '!=', $this->attendance->id)
            ->exists();

        if ($exists) {
            $this->addError('date', __('Attendance for this staff member on this date is already recorded.'));

            return;
        }

        $this->attendance->update($data);

        $this->redirectRoute('staff.attendance.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.staff.staff-attendance-edit', [
            'staffMembers' => Staff::with('user')->get(),
        ]);
    }
}
