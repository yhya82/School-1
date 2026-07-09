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

    public string $search = '';

    public function mount(): void
    {
        Gate::authorize('viewAny', Payment::class);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
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
            'payments' => Payment::with(['invoice.student.user'])
                ->when($this->search, fn ($query, $value) => $query->whereHas('invoice.student.user', fn ($q) => $q->where('name', 'like', "%{$value}%")))
                ->orderByDesc('payment_date')
                ->paginate(20),
        ]);
    }
}
