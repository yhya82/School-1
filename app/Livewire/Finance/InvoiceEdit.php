<?php

namespace App\Livewire\Finance;

use App\Models\Invoice;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class InvoiceEdit extends Component
{
    public Invoice $invoice;

    public string $amount_due = '';

    public string $due_date = '';

    public string $status = 'unpaid';

    public function mount(Invoice $invoice): void
    {
        Gate::authorize('update', $invoice);

        $this->invoice = $invoice->load(['student.user', 'feeStructure', 'payments']);
        $this->amount_due = (string) $invoice->amount_due;
        $this->due_date = $invoice->due_date->format('Y-m-d');
        $this->status = $invoice->status;
    }

    protected function rules(): array
    {
        return [
            'amount_due' => 'required|numeric|min:0.01',
            'due_date' => 'required|date',
            'status' => 'required|in:unpaid,partial,paid,overdue',
        ];
    }

    public function save(): void
    {
        Gate::authorize('update', $this->invoice);

        $data = $this->validate();

        $this->invoice->update($data);

        $this->redirectRoute('finance.invoices.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.finance.invoice-edit');
    }
}
