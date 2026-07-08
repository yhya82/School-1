<?php

namespace App\Livewire\Finance;

use App\Models\Expense;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ExpenseEdit extends Component
{
    public Expense $expense;

    public string $category = '';

    public string $amount = '';

    public string $expense_date = '';

    public string $description = '';

    public function mount(Expense $expense): void
    {
        Gate::authorize('update', $expense);

        $this->expense = $expense;
        $this->category = $expense->category;
        $this->amount = (string) $expense->amount;
        $this->expense_date = $expense->expense_date->format('Y-m-d');
        $this->description = (string) $expense->description;
    }

    protected function rules(): array
    {
        return [
            'category' => 'required|string|max:50',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'description' => 'nullable|string',
        ];
    }

    public function save(): void
    {
        Gate::authorize('update', $this->expense);

        $data = $this->validate();

        $this->expense->update($data);

        $this->redirectRoute('finance.expenses.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.finance.expense-edit');
    }
}
