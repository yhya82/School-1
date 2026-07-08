<?php

namespace App\Livewire\Staff;

use App\Models\Leave;
use App\Models\Staff;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class LeaveEdit extends Component
{
    public Leave $leave;

    public ?int $staff_id = null;

    public string $leave_type = '';

    public string $start_date = '';

    public string $end_date = '';

    public string $status = 'pending';

    public function mount(Leave $leave): void
    {
        Gate::authorize('update', $leave);

        $this->leave = $leave;
        $this->staff_id = $leave->staff_id;
        $this->leave_type = $leave->leave_type;
        $this->start_date = $leave->start_date->format('Y-m-d');
        $this->end_date = $leave->end_date->format('Y-m-d');
        $this->status = $leave->status;
    }

    protected function rules(): array
    {
        return [
            'staff_id' => 'required|exists:staff,id',
            'leave_type' => 'required|string|max:30',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:pending,approved,rejected',
        ];
    }

    public function save(): void
    {
        Gate::authorize('update', $this->leave);

        $data = $this->validate();

        if ($data['status'] !== 'pending' && ! $this->leave->approved_by) {
            $approverStaff = Staff::where('user_id', Auth::id())->first();
            $data['approved_by'] = $approverStaff?->id;
        }

        $this->leave->update($data);

        $this->redirectRoute('staff.leaves.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.staff.leave-edit', [
            'staffMembers' => Staff::with('user')->get(),
        ]);
    }
}
