<?php

namespace App\Livewire\Finance;

use App\Models\Payment;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class PaymentIndex extends Component
{
    use WithPagination;

    public function mount(): void
    {
        Gate::authorize('viewAny', Payment::class);
    }

    public function delete(int $id): void
    {
        $payment = Payment::findOrFail($id);
        Gate::authorize('delete', $payment);
        $invoice = $payment->invoice;
        $payment->delete();
        $invoice->recalculateStatus();
    }

    public function render()
    {
        return view('livewire.finance.payment-index', [
            'payments' => Payment::with(['invoice.student.user'])->orderByDesc('payment_date')->paginate(20),
        ]);
    }
}
