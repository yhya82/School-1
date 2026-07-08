<?php

namespace App\Livewire\Finance;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
class PaymentCreate extends Component
{
    #[Url(as: 'invoice')]
    public ?int $invoice_id = null;

    public string $amount_paid = '';

    public string $payment_date = '';

    public string $payment_method = 'cash';

    public string $transaction_ref = '';

    public function mount(): void
    {
        Gate::authorize('create', Payment::class);
        $this->payment_date = now()->format('Y-m-d');
    }

    protected function rules(): array
    {
        return [
            'invoice_id' => 'required|exists:invoices,id',
            'amount_paid' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,card,bank_transfer,online',
            'transaction_ref' => 'nullable|string|max:100|unique:payments,transaction_ref',
        ];
    }

    public function save(): void
    {
        $data = $this->validate();
        $data['transaction_ref'] = $data['transaction_ref'] ?: null;

        $payment = Payment::create($data);
        $payment->invoice->recalculateStatus();

        $this->redirectRoute('finance.invoices.edit', ['invoice' => $data['invoice_id']], navigate: true);
    }

    public function render()
    {
        return view('livewire.finance.payment-create', [
            'invoices' => Invoice::with(['student.user', 'feeStructure'])->where('status', '!=', 'paid')->get(),
        ]);
    }
}
