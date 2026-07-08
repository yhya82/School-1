<?php

namespace App\Livewire\Staff;

use App\Models\Staff;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class StaffEdit extends Component
{
    public Staff $staff;

    public string $name = '';

    public string $email = '';

    public string $employee_no = '';

    public string $designation = '';

    public string $department = '';

    public string $joining_date = '';

    public function mount(Staff $staff): void
    {
        Gate::authorize('update', $staff);

        $this->staff = $staff;
        $this->name = $staff->user->name;
        $this->email = $staff->user->email;
        $this->employee_no = $staff->employee_no;
        $this->designation = (string) $staff->designation;
        $this->department = (string) $staff->department;
        $this->joining_date = $staff->joining_date->format('Y-m-d');
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,'.$this->staff->user_id,
            'employee_no' => 'required|string|max:30|unique:staff,employee_no,'.$this->staff->id,
            'designation' => 'nullable|string|max:50',
            'department' => 'nullable|string|max:50',
            'joining_date' => 'required|date',
        ];
    }

    public function save(): void
    {
        Gate::authorize('update', $this->staff);

        $data = $this->validate();

        $this->staff->user->update(['name' => $data['name'], 'email' => $data['email']]);
        $this->staff->update(collect($data)->except(['name', 'email'])->toArray());

        $this->redirectRoute('staff.staff.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.staff.staff-edit');
    }
}
