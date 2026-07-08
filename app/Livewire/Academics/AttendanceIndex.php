<?php

namespace App\Livewire\Academics;

use App\Models\Attendance;
use App\Models\Section;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AttendanceIndex extends Component
{
    use WithPagination;

    #[Url]
    public ?int $section_id = null;

    public function mount(): void
    {
        Gate::authorize('viewAny', Attendance::class);
    }

    public function delete(int $id): void
    {
        $attendance = Attendance::findOrFail($id);
        Gate::authorize('delete', $attendance);
        $attendance->delete();
    }

    public function render()
    {
        $query = Attendance::with(['student.user', 'section.schoolClass'])->orderByDesc('date');

        if ($this->section_id) {
            $query->where('section_id', $this->section_id);
        }

        return view('livewire.academics.attendance-index', [
            'attendances' => $query->paginate(20),
            'sections' => Section::with('schoolClass')->orderBy('class_id')->orderBy('name')->get(),
        ]);
    }
}
