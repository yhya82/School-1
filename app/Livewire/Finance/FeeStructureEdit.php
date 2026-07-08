<?php

namespace App\Livewire\Finance;

use App\Models\AcademicYear;
use App\Models\FeeStructure;
use App\Models\Invoice;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class FeeStructureEdit extends Component
{
    public FeeStructure $feeStructure;

    public ?int $class_id = null;

    public ?int $academic_year_id = null;

    public string $name = '';

    public string $amount = '';

    public string $frequency = 'term';

    public string $due_date = '';

    public ?int $generatedCount = null;

    public function mount(FeeStructure $feeStructure): void
    {
        Gate::authorize('update', $feeStructure);

        $this->feeStructure = $feeStructure;
        $this->class_id = $feeStructure->class_id;
        $this->academic_year_id = $feeStructure->academic_year_id;
        $this->name = $feeStructure->name;
        $this->amount = (string) $feeStructure->amount;
        $this->frequency = $feeStructure->frequency;
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
        Gate::authorize('update', $this->feeStructure);

        $data = $this->validate();

        $this->feeStructure->update($data);

        $this->redirectRoute('finance.fee-structures.index', navigate: true);
    }

    public function generateInvoices(): void
    {
        Gate::authorize('create', Invoice::class);

        $this->validate([
            'due_date' => 'required|date',
        ]);

        $studentIds = Student::where('class_id', $this->feeStructure->class_id)
            ->where('status', 'active')
            ->pluck('id');

        $alreadyInvoiced = Invoice::where('fee_structure_id', $this->feeStructure->id)
            ->whereIn('student_id', $studentIds)
            ->pluck('student_id');

        $toInvoice = $studentIds->diff($alreadyInvoiced);

        foreach ($toInvoice as $studentId) {
            Invoice::create([
                'student_id' => $studentId,
                'fee_structure_id' => $this->feeStructure->id,
                'amount_due' => $this->feeStructure->amount,
                'due_date' => $this->due_date,
                'status' => 'unpaid',
            ]);
        }

        $this->generatedCount = $toInvoice->count();
        $this->reset('due_date');
    }

    public function render()
    {
        return view('livewire.finance.fee-structure-edit', [
            'classes' => SchoolClass::orderBy('name')->get(),
            'academicYears' => AcademicYear::orderByDesc('start_date')->get(),
        ]);
    }
}
