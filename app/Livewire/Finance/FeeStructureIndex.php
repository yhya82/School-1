<?php

namespace App\Livewire\Finance;

use App\Models\FeeStructure;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class FeeStructureIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public function mount(): void
    {
        Gate::authorize('viewAny', FeeStructure::class);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        $feeStructure = FeeStructure::findOrFail($id);
        Gate::authorize('delete', $feeStructure);
        $feeStructure->delete();
    }

    public function render()
    {
        return view('livewire.finance.fee-structure-index', [
            'feeStructures' => FeeStructure::with(['schoolClass', 'academicYear'])->withCount('invoices')
                ->when($this->search, fn ($query, $value) => $query->where('name', 'like', "%{$value}%"))
                ->orderByDesc('id')
                ->paginate(15),
        ]);
    }
}
