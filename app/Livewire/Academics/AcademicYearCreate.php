<?php

namespace App\Livewire\Academics;

use App\Models\AcademicYear;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AcademicYearCreate extends Component
{
    public string $name = '';

    public string $start_date = '';

    public string $end_date = '';

    public function mount(): void
    {
        Gate::authorize('create', AcademicYear::class);
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

        AcademicYear::create($data);

        $this->redirectRoute('academics.years.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.academics.academic-year-create');
    }
}
