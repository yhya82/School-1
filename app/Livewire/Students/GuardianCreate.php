<?php

namespace App\Livewire\Students;

use App\Models\Guardian;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class GuardianCreate extends Component
{
    public string $name = '';

    public string $phone = '';

    public string $relationship = '';

    public string $email = '';

    public function mount(): void
    {
        Gate::authorize('create', Guardian::class);
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'relationship' => 'required|string|max:30',
            'email' => 'nullable|email|unique:users,email',
        ];
    }

    public function save(): void
    {
        $data = $this->validate();

        $userId = null;

        if (! empty($data['email'])) {
            $password = Str::password(12);

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($password),
            ]);
            $user->assignRole('parent');
            $userId = $user->id;

            session()->flash('generated_password', $password);
        }

        Guardian::create([
            'user_id' => $userId,
            'name' => $data['name'],
            'phone' => $data['phone'],
            'relationship' => $data['relationship'],
        ]);

        $this->redirectRoute('students.guardians.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.students.guardian-create');
    }
}
