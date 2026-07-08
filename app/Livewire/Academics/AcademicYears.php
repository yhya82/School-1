<?php

namespace App\Livewire\Academics;

use App\Models\AcademicYear;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AcademicYears extends Component
{
    public ?int $editingId = null;

    public string $name = '';

    public string $start_date = '';

    public string $end_date = '';

    public function mount(): void
    {
        Gate::authorize('viewAny', AcademicYear::class);
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:20',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ];
    }

    public function save(): void
    {
        $data = $this->validate();

        if ($this->editingId) {
            $year = AcademicYear::findOrFail($this->editingId);
            Gate::authorize('update', $year);
            $year->update($data);
        } else {
            Gate::authorize('create', AcademicYear::class);
            AcademicYear::create($data);
        }

        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $year = AcademicYear::findOrFail($id);
        Gate::authorize('update', $year);

        $this->editingId = $year->id;
        $this->name = $year->name;
        $this->start_date = $year->start_date->format('Y-m-d');
        $this->end_date = $year->end_date->format('Y-m-d');
    }

    public function delete(int $id): void
    {
        $year = AcademicYear::findOrFail($id);
        Gate::authorize('delete', $year);
        $year->delete();
    }

    public function resetForm(): void
    {
        $this->reset(['editingId', 'name', 'start_date', 'end_date']);
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.academics.academic-years', [
            'years' => AcademicYear::orderByDesc('start_date')->get(),
        ]);
    }
}
