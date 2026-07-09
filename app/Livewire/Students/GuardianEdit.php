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
class GuardianEdit extends Component
{
    public Guardian $guardian;

    public string $name = '';

    public string $phone = '';

    public string $relationship = '';

    public string $email = '';

    public function mount(Guardian $guardian): void
    {
        Gate::authorize('update', $guardian);

        $this->guardian = $guardian;
        $this->name = $guardian->name;
        $this->phone = $guardian->phone;
        $this->relationship = $guardian->relationship;
        $this->email = $guardian->user?->email ?? '';
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'relationship' => 'required|string|max:30',
            'email' => 'nullable|email|unique:users,email,'.($this->guardian->user_id ?? 'NULL'),
        ];
    }

    public function save(): void
    {
        Gate::authorize('update', $this->guardian);

        $data = $this->validate();

        $this->guardian->update([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'relationship' => $data['relationship'],
        ]);

        if ($this->guardian->user) {
            if (! empty($data['email'])) {
                $this->guardian->user->update(['name' => $data['name'], 'email' => $data['email']]);
            }
        } elseif (! empty($data['email'])) {
            $password = Str::password(12);

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($password),
            ]);
            $user->assignRole('parent');
            $this->guardian->update(['user_id' => $user->id]);

            session()->flash('generated_password', $password);
        }

        $this->redirectRoute('students.guardians.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.students.guardian-edit');
    }
}
