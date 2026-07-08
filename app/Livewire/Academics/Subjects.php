<?php

namespace App\Livewire\Academics;

use App\Models\Subject;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Subjects extends Component
{
    public ?int $editingId = null;

    public string $name = '';

    public string $code = '';

    public function mount(): void
    {
        Gate::authorize('viewAny', Subject::class);
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:subjects,code'.($this->editingId ? ','.$this->editingId : ''),
        ];
    }

    public function save(): void
    {
        $data = $this->validate();

        if ($this->editingId) {
            $subject = Subject::findOrFail($this->editingId);
            Gate::authorize('update', $subject);
            $subject->update($data);
        } else {
            Gate::authorize('create', Subject::class);
            Subject::create($data);
        }

        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $subject = Subject::findOrFail($id);
        Gate::authorize('update', $subject);

        $this->editingId = $subject->id;
        $this->name = $subject->name;
        $this->code = $subject->code;
    }

    public function delete(int $id): void
    {
        $subject = Subject::findOrFail($id);
        Gate::authorize('delete', $subject);
        $subject->delete();
    }

    public function resetForm(): void
    {
        $this->reset(['editingId', 'name', 'code']);
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.academics.subjects', [
            'subjects' => Subject::orderBy('name')->get(),
        ]);
    }
}
