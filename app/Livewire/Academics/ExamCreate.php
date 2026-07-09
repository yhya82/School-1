<?php

namespace App\Livewire\Academics;

use App\Models\AcademicYear;
use App\Models\Exam;
use App\Models\Setting;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ExamCreate extends Component
{
    public ?int $academic_year_id = null;

    public string $name = '';

    public string $start_date = '';

    public string $end_date = '';

    public function mount(): void
    {
        Gate::authorize('create', Exam::class);

        $this->academic_year_id = Setting::get('current_academic_year_id')
            ? (int) Setting::get('current_academic_year_id')
            : null;
    }

    protected function rules(): array
    {
        return [
            'academic_year_id' => 'required|exists:academic_years,id',
            'name' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ];
    }

    public function save(): void
    {
        $data = $this->validate();

        $exam = Exam::create($data);

        $this->redirectRoute('academics.exams.edit', $exam, navigate: true);
    }

    public function render()
    {
        return view('livewire.academics.exam-create', [
            'academicYears' => AcademicYear::orderByDesc('start_date')->get(),
        ]);
    }
}
