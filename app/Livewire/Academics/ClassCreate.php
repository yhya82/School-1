<?php

namespace App\Livewire\Academics;

use App\Models\SchoolClass;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ClassCreate extends Component
{
    public string $name = '';

    public function mount(): void
    {
        Gate::authorize('create', SchoolClass::class);
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:50',
        ];
    }

    public function save(): void
    {
        $data = $this->validate();

        $class = SchoolClass::create($data);

        $this->redirectRoute('academics.classes.edit', $class, navigate: true);
    }

    public function render()
    {
        return view('livewire.academics.class-create');
    }
}
