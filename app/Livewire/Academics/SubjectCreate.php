<?php

namespace App\Livewire\Academics;

use App\Models\Subject;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class SubjectCreate extends Component
{
    public string $name = '';

    public string $code = '';

    public function mount(): void
    {
        Gate::authorize('create', Subject::class);
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:subjects,code',
        ];
    }

    public function save(): void
    {
        $data = $this->validate();

        Subject::create($data);

        $this->redirectRoute('academics.subjects.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.academics.subject-create');
    }
}
