<?php

namespace App\Livewire\Finance;

use App\Models\Invoice;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class InvoiceIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public function mount(): void
    {
        Gate::authorize('viewAny', Invoice::class);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        $invoice = Invoice::findOrFail($id);
        Gate::authorize('delete', $invoice);
        $invoice->delete();
    }

    public function render()
    {
        return view('livewire.finance.invoice-index', [
            'invoices' => Invoice::with(['student.user', 'feeStructure'])
                ->when($this->search, fn ($query, $value) => $query->whereHas('student.user', fn ($q) => $q->where('name', 'like', "%{$value}%")))
                ->orderByDesc('due_date')
                ->paginate(20),
        ]);
    }
}
