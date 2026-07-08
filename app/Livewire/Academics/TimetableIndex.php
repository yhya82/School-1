<?php

namespace App\Livewire\Academics;

use App\Models\Section;
use App\Models\TimetableSlot;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
class TimetableIndex extends Component
{
    #[Url]
    public ?int $section_id = null;

    public function mount(): void
    {
        Gate::authorize('viewAny', TimetableSlot::class);
    }

    public function delete(int $id): void
    {
        $slot = TimetableSlot::findOrFail($id);
        Gate::authorize('delete', $slot);
        $slot->delete();
    }

    public function render()
    {
        $query = TimetableSlot::with(['section.schoolClass', 'classSubject.subject', 'classSubject.teacher.user'])
            ->orderBy('day_of_week')
            ->orderBy('start_time');

        if ($this->section_id) {
            $query->where('section_id', $this->section_id);
        }

        return view('livewire.academics.timetable-index', [
            'slots' => $query->get(),
            'sections' => Section::with('schoolClass')->orderBy('class_id')->orderBy('name')->get(),
        ]);
    }
}
