<?php

namespace App\Livewire\Academics;

use App\Models\Section;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ClassesManager extends Component
{
    public ?int $editingClassId = null;

    public string $className = '';

    public ?int $sectionClassId = null;

    public ?int $editingSectionId = null;

    public string $sectionName = '';

    public int $sectionCapacity = 30;

    public function mount(): void
    {
        Gate::authorize('viewAny', SchoolClass::class);
    }

    public function saveClass(): void
    {
        $data = $this->validate([
            'className' => 'required|string|max:50',
        ], [], ['className' => 'name']);

        if ($this->editingClassId) {
            $class = SchoolClass::findOrFail($this->editingClassId);
            Gate::authorize('update', $class);
            $class->update(['name' => $data['className']]);
        } else {
            Gate::authorize('create', SchoolClass::class);
            SchoolClass::create(['name' => $data['className']]);
        }

        $this->reset(['editingClassId', 'className']);
        $this->resetErrorBag();
    }

    public function editClass(int $id): void
    {
        $class = SchoolClass::findOrFail($id);
        Gate::authorize('update', $class);

        $this->editingClassId = $class->id;
        $this->className = $class->name;
    }

    public function deleteClass(int $id): void
    {
        $class = SchoolClass::findOrFail($id);
        Gate::authorize('delete', $class);
        $class->delete();
    }

    public function manageSections(int $classId): void
    {
        $this->sectionClassId = $this->sectionClassId === $classId ? null : $classId;
        $this->reset(['editingSectionId', 'sectionName', 'sectionCapacity']);
    }

    public function saveSection(): void
    {
        $data = $this->validate([
            'sectionName' => 'required|string|max:20',
            'sectionCapacity' => 'required|integer|min:1|max:200',
        ]);

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
                'class_id' => $this->sectionClassId,
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

    public function deleteSection(int $id): void
    {
        $section = Section::findOrFail($id);
        Gate::authorize('delete', $section);
        $section->delete();
    }

    public function render()
    {
        return view('livewire.academics.classes-manager', [
            'classes' => SchoolClass::withCount('sections')->orderBy('name')->get(),
            'sections' => $this->sectionClassId
                ? Section::where('class_id', $this->sectionClassId)->orderBy('name')->get()
                : collect(),
        ]);
    }
}
