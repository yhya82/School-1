<?php

namespace App\Livewire\Academics;

use App\Models\Attendance;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AttendanceMark extends Component
{
    public ?int $section_id = null;

    public string $date = '';

    public array $statuses = [];

    public function mount(): void
    {
        Gate::authorize('create', Attendance::class);

        $this->date = now()->format('Y-m-d');
    }

    public function updatedSectionId(): void
    {
        $this->loadStatuses();
    }

    public function updatedDate(): void
    {
        $this->loadStatuses();
    }

    protected function loadStatuses(): void
    {
        if (! $this->section_id || ! $this->date) {
            return;
        }

        $existing = Attendance::where('section_id', $this->section_id)
            ->whereDate('date', $this->date)
            ->pluck('status', 'student_id');

        foreach ($this->students() as $student) {
            $this->statuses[$student->id] = $existing[$student->id] ?? 'present';
        }
    }

    protected function students()
    {
        return $this->section_id
            ? Student::where('section_id', $this->section_id)->where('status', 'active')->with('user')->orderBy('admission_no')->get()
            : collect();
    }

    public function save(): void
    {
        $this->validate([
            'section_id' => 'required|exists:sections,id',
            'date' => 'required|date',
        ]);

        $staff = \App\Models\Staff::where('user_id', Auth::id())->first();

        foreach ($this->statuses as $studentId => $status) {
            $attendance = Attendance::where('student_id', $studentId)->whereDate('date', $this->date)->first();

            $attributes = [
                'section_id' => $this->section_id,
                'status' => $status,
                'marked_by' => $staff?->id,
            ];

            if ($attendance) {
                $attendance->update($attributes);
            } else {
                Attendance::create([
                    'student_id' => $studentId,
                    'date' => $this->date,
                    ...$attributes,
                ]);
            }
        }

        $this->redirectRoute('academics.attendance.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.academics.attendance-mark', [
            'sections' => Section::with('schoolClass')->orderBy('class_id')->orderBy('name')->get(),
            'students' => $this->students(),
        ]);
    }
}
