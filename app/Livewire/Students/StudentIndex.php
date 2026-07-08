<?php

namespace App\Livewire\Students;

use App\Models\Student;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class StudentIndex extends Component
{
    public function mount(): void
    {
        Gate::authorize('viewAny', Student::class);
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
            'students' => Student::with(['user', 'schoolClass', 'section'])->orderBy('admission_no')->get(),
        ]);
    }
}
