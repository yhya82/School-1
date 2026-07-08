<?php

namespace App\Livewire\Finance;

use App\Models\FeeStructure;
use App\Models\Invoice;
use App\Models\Student;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class InvoiceCreate extends Component
{
    public ?int $student_id = null;

    public ?int $fee_structure_id = null;

    public string $amount_due = '';

    public string $due_date = '';

    public function mount(): void
    {
        Gate::authorize('create', Invoice::class);
    }

    public function updatedFeeStructureId(): void
    {
        if ($this->fee_structure_id) {
            $this->amount_due = (string) FeeStructure::find($this->fee_structure_id)?->amount;
        }
    }

    protected function rules(): array
    {
        return [
            'student_id' => 'required|exists:students,id',
            'fee_structure_id' => 'required|exists:fee_structures,id',
            'amount_due' => 'required|numeric|min:0.01',
            'due_date' => 'required|date',
        ];
    }

    public function save(): void
    {
        $data = $this->validate();

        $exists = Invoice::where('student_id', $data['student_id'])
            ->where('fee_structure_id', $data['fee_structure_id'])
            ->exists();

        if ($exists) {
            $this->addError('fee_structure_id', __('This student already has an invoice for this fee structure.'));

            return;
        }

        Invoice::create([
            ...$data,
            'status' => 'unpaid',
        ]);

        $this->redirectRoute('finance.invoices.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.finance.invoice-create', [
            'students' => Student::with('user')->where('status', 'active')->get(),
            'feeStructures' => FeeStructure::with('schoolClass')->get(),
        ]);
    }
}
