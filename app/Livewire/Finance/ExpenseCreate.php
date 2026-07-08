<?php

namespace App\Livewire\Finance;

use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ExpenseCreate extends Component
{
    public string $category = '';

    public string $amount = '';

    public string $expense_date = '';

    public string $description = '';

    public function mount(): void
    {
        Gate::authorize('create', Expense::class);
        $this->expense_date = now()->format('Y-m-d');
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
        $data = $this->validate();

        $staff = Auth::user()->staff;

        if (! $staff) {
            $this->addError('category', __('You must have a staff profile to record expenses.'));

            return;
        }

        Expense::create([
            ...$data,
            'recorded_by' => $staff->id,
        ]);

        $this->redirectRoute('finance.expenses.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.finance.expense-create');
    }
}
