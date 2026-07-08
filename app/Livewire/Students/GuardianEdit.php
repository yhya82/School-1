<?php

namespace App\Livewire\Students;

use App\Models\Guardian;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class GuardianEdit extends Component
{
    public Guardian $guardian;

    public string $name = '';

    public string $phone = '';

    public string $relationship = '';

    public function mount(Guardian $guardian): void
    {
        Gate::authorize('update', $guardian);

        $this->guardian = $guardian;
        $this->name = $guardian->name;
        $this->phone = $guardian->phone;
        $this->relationship = $guardian->relationship;
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'relationship' => 'required|string|max:30',
        ];
    }

    public function save(): void
    {
        Gate::authorize('update', $this->guardian);

        $data = $this->validate();

        $this->guardian->update($data);

        $this->redirectRoute('students.guardians.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.students.guardian-edit');
    }
}
