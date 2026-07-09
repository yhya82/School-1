<?php

namespace App\Livewire\Finance;

use App\Models\AcademicYear;
use App\Models\FeeStructure;
use App\Models\SchoolClass;
use App\Models\Setting;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class FeeStructureCreate extends Component
{
    public ?int $class_id = null;

    public ?int $academic_year_id = null;

    public string $name = '';

    public string $amount = '';

    public string $frequency = 'term';

    public function mount(): void
    {
        Gate::authorize('create', FeeStructure::class);

        $this->academic_year_id = Setting::get('current_academic_year_id')
            ? (int) Setting::get('current_academic_year_id')
            : null;
    }

    protected function rules(): array
    {
        return [
            'class_id' => 'required|exists:classes,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'name' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0.01',
            'frequency' => 'required|in:monthly,term,annual',
        ];
    }

    public function save(): void
    {
        $data = $this->validate();

        $feeStructure = FeeStructure::create($data);

        $this->redirectRoute('finance.fee-structures.edit', $feeStructure, navigate: true);
    }

    public function render()
    {
        return view('livewire.finance.fee-structure-create', [
            'classes' => SchoolClass::orderBy('name')->get(),
            'academicYears' => AcademicYear::orderByDesc('start_date')->get(),
        ]);
    }
}
