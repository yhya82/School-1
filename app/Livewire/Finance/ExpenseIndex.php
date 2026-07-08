<?php

namespace App\Livewire\Finance;

use App\Models\Expense;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ExpenseIndex extends Component
{
    use WithPagination;

    public function mount(): void
    {
        Gate::authorize('viewAny', Expense::class);
    }

    public function delete(int $id): void
    {
        $expense = Expense::findOrFail($id);
        Gate::authorize('delete', $expense);
        $expense->delete();
    }

    public function render()
    {
        return view('livewire.finance.expense-index', [
            'expenses' => Expense::with('recorder.user')->orderByDesc('expense_date')->paginate(20),
        ]);
    }
}
