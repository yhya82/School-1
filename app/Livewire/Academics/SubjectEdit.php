<?php

namespace App\Livewire\Academics;

use App\Models\Subject;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class SubjectEdit extends Component
{
    public Subject $subject;

    public string $name = '';

    public string $code = '';

    public function mount(Subject $subject): void
    {
        Gate::authorize('update', $subject);

        $this->subject = $subject;
        $this->name = $subject->name;
        $this->code = $subject->code;
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:subjects,code,'.$this->subject->id,
        ];
    }

    public function save(): void
    {
        Gate::authorize('update', $this->subject);

        $data = $this->validate();

        $this->subject->update($data);

        $this->redirectRoute('academics.subjects.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.academics.subject-edit');
    }
}
