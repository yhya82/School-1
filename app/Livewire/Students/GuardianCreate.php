<?php

namespace App\Livewire\Students;

use App\Models\Guardian;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class GuardianCreate extends Component
{
    public string $name = '';

    public string $phone = '';

    public string $relationship = '';

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
        ];
    }

    public function save(): void
    {
        $data = $this->validate();

        Guardian::create($data);

        $this->redirectRoute('students.guardians.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.students.guardian-create');
    }
}
