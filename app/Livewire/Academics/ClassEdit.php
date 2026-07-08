<?php

namespace App\Livewire\Academics;

use App\Models\Section;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ClassEdit extends Component
{
    public SchoolClass $class;

    public string $name = '';

    public ?int $editingSectionId = null;

    public string $sectionName = '';

    public int $sectionCapacity = 30;

    public function mount(SchoolClass $class): void
    {
        Gate::authorize('update', $class);

        $this->class = $class;
        $this->name = $class->name;
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:50',
        ];
    }

    public function save(): void
    {
        Gate::authorize('update', $this->class);

        $data = $this->validate();

        $this->class->update($data);

        $this->redirectRoute('academics.classes.index', navigate: true);
    }

    protected function sectionRules(): array
    {
        return [
            'sectionName' => 'required|string|max:20',
            'sectionCapacity' => 'required|integer|min:1|max:200',
        ];
    }

    public function saveSection(): void
    {
        $data = $this->validate($this->sectionRules());

        if ($this->editingSectionId) {
            $section = Section::findOrFail($this->editingSectionId);
            Gate::authorize('update', $section);
            $section->update([
                'name' => $data['sectionName'],
                'capacity' => $data['sectionCapacity'],
            ]);
        } else {
            Gate::authorize('create', Section::class);
            Section::create([
                'class_id' => $this->class->id,
                'name' => $data['sectionName'],
                'capacity' => $data['sectionCapacity'],
            ]);
        }

        $this->reset(['editingSectionId', 'sectionName']);
        $this->sectionCapacity = 30;
        $this->resetErrorBag();
    }

    public function editSection(int $id): void
    {
        $section = Section::findOrFail($id);
        Gate::authorize('update', $section);

        $this->editingSectionId = $section->id;
        $this->sectionName = $section->name;
        $this->sectionCapacity = $section->capacity;
    }

    public function cancelSectionEdit(): void
    {
        $this->reset(['editingSectionId', 'sectionName']);
        $this->sectionCapacity = 30;
        $this->resetErrorBag();
    }

    public function deleteSection(int $id): void
    {
        $section = Section::findOrFail($id);
        Gate::authorize('delete', $section);
        $section->delete();
    }

    public function render()
    {
        return view('livewire.academics.class-edit', [
            'sections' => Section::where('class_id', $this->class->id)->orderBy('name')->get(),
        ]);
    }
}
