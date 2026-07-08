<?php

namespace App\Livewire\Finance;

use App\Models\Payment;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class PaymentEdit extends Component
{
    public Payment $payment;

    public string $amount_paid = '';

    public string $payment_date = '';

    public string $payment_method = 'cash';

    public string $transaction_ref = '';

    public function mount(Payment $payment): void
    {
        Gate::authorize('update', $payment);

        $this->payment = $payment->load('invoice.student.user');
        $this->amount_paid = (string) $payment->amount_paid;
        $this->payment_date = $payment->payment_date->format('Y-m-d');
        $this->payment_method = $payment->payment_method;
        $this->transaction_ref = (string) $payment->transaction_ref;
    }

    protected function rules(): array
    {
        return [
            'amount_paid' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,card,bank_transfer,online',
            'transaction_ref' => 'nullable|string|max:100|unique:payments,transaction_ref,'.$this->payment->id,
        ];
    }

    public function save(): void
    {
        Gate::authorize('update', $this->payment);

        $data = $this->validate();
        $data['transaction_ref'] = $data['transaction_ref'] ?: null;

        $this->payment->update($data);
        $this->payment->invoice->recalculateStatus();

        $this->redirectRoute('finance.invoices.edit', ['invoice' => $this->payment->invoice_id], navigate: true);
    }

    public function render()
    {
        return view('livewire.finance.payment-edit');
    }
}
