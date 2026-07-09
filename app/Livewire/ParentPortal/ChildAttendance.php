<?php

namespace App\Livewire\ParentPortal;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ChildAttendance extends Component
{
    use WithPagination;

    public Student $student;

    public function mount(Student $student): void
    {
        $guardian = Auth::user()->guardian;
        abort_unless($guardian && $guardian->students()->where('students.id', $student->id)->exists(), 403);

        $this->student = $student;
    }

    public function render()
    {
        return view('livewire.parent-portal.child-attendance', [
            'attendances' => Attendance::where('student_id', $this->student->id)->orderByDesc('date')->paginate(20),
        ]);
    }
}
