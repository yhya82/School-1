<?php

namespace App\Livewire\Staff;

use App\Models\Leave;
use App\Models\Staff;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class LeaveCreate extends Component
{
    public ?int $staff_id = null;

    public string $leave_type = '';

    public string $start_date = '';

    public string $end_date = '';

    public function mount(): void
    {
        Gate::authorize('create', Leave::class);
    }

    protected function rules(): array
    {
        return [
            'staff_id' => 'required|exists:staff,id',
            'leave_type' => 'required|string|max:30',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ];
    }

    public function save(): void
    {
        $data = $this->validate();

        Leave::create([
            ...$data,
            'status' => 'pending',
        ]);

        $this->redirectRoute('staff.leaves.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.staff.leave-create', [
            'staffMembers' => Staff::with('user')->get(),
        ]);
    }
}
