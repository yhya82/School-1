<?php

namespace App\Livewire\Academics;

use App\Models\AcademicYear;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AcademicYearEdit extends Component
{
    public AcademicYear $year;

    public string $name = '';

    public string $start_date = '';

    public string $end_date = '';

    public function mount(AcademicYear $year): void
    {
        Gate::authorize('update', $year);

        $this->year = $year;
        $this->name = $year->name;
        $this->start_date = $year->start_date->format('Y-m-d');
        $this->end_date = $year->end_date->format('Y-m-d');
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
        Gate::authorize('update', $this->year);

        $data = $this->validate();

        $this->year->update($data);

        $this->redirectRoute('academics.years.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.academics.academic-year-edit');
    }
}
