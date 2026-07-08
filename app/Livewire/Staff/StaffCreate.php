<?php

namespace App\Livewire\Staff;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class StaffCreate extends Component
{
    public string $name = '';

    public string $email = '';

    public string $employee_no = '';

    public string $designation = '';

    public string $department = '';

    public string $joining_date = '';

    public string $role = 'teacher';

    public function mount(): void
    {
        Gate::authorize('create', Staff::class);
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'employee_no' => 'required|string|max:30|unique:staff,employee_no',
            'designation' => 'nullable|string|max:50',
            'department' => 'nullable|string|max:50',
            'joining_date' => 'required|date',
            'role' => 'required|in:teacher,staff',
        ];
    }

    public function save(): void
    {
        $data = $this->validate();

        $password = Str::password(12);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($password),
        ]);
        $user->assignRole($data['role']);

        Staff::create([
            'user_id' => $user->id,
            'employee_no' => $data['employee_no'],
            'designation' => $data['designation'],
            'department' => $data['department'],
            'joining_date' => $data['joining_date'],
        ]);

        session()->flash('generated_password', $password);

        $this->redirectRoute('staff.staff.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.staff.staff-create');
    }
}
