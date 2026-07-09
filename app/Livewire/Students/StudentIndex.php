<?php

namespace App\Livewire\Students;

use App\Models\Student;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class StudentIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public function mount(): void
    {
        Gate::authorize('viewAny', Student::class);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        $student = Student::findOrFail($id);
        Gate::authorize('delete', $student);
        $user = $student->user;
        $student->delete();
        $user?->delete();
    }

    public function render()
    {
        return view('livewire.students.student-index', [
            'students' => Student::with(['user', 'schoolClass', 'section'])
                ->when($this->search, fn ($query, $value) => $query->where(fn ($q) => $q
                    ->where('admission_no', 'like', "%{$value}%")
                    ->orWhereHas('user', fn ($q2) => $q2->where('name', 'like', "%{$value}%"))))
                ->orderBy('admission_no')
                ->paginate(15),
        ]);
    }
}
