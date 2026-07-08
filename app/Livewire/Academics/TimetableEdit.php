<?php

namespace App\Livewire\Academics;

use App\Models\ClassSubject;
use App\Models\Section;
use App\Models\TimetableSlot;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class TimetableEdit extends Component
{
    public TimetableSlot $slot;

    public ?int $section_id = null;

    public ?int $class_subject_id = null;

    public int $day_of_week = 1;

    public string $start_time = '';

    public string $end_time = '';

    public string $room = '';

    public function mount(TimetableSlot $timetableSlot): void
    {
        Gate::authorize('update', $timetableSlot);

        $this->slot = $timetableSlot;
        $this->section_id = $timetableSlot->section_id;
        $this->class_subject_id = $timetableSlot->class_subject_id;
        $this->day_of_week = $timetableSlot->day_of_week;
        $this->start_time = substr($timetableSlot->start_time, 0, 5);
        $this->end_time = substr($timetableSlot->end_time, 0, 5);
        $this->room = (string) $timetableSlot->room;
    }

    protected function rules(): array
    {
        return [
            'section_id' => 'required|exists:sections,id',
            'class_subject_id' => 'required|exists:class_subject,id',
            'day_of_week' => 'required|integer|between:1,7',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'room' => 'nullable|string|max:30',
        ];
    }

    public function save(): void
    {
        Gate::authorize('update', $this->slot);

        $data = $this->validate();

        $exists = TimetableSlot::where('section_id', $data['section_id'])
            ->where('day_of_week', $data['day_of_week'])
            ->where('start_time', $data['start_time'])
            ->where('id', '!=', $this->slot->id)
            ->exists();

        if ($exists) {
            $this->addError('start_time', __('This section already has a slot at this day and start time.'));

            return;
        }

        $this->slot->update($data);

        $this->redirectRoute('academics.timetable.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.academics.timetable-edit', [
            'sections' => Section::with('schoolClass')->orderBy('class_id')->orderBy('name')->get(),
            'classSubjects' => ClassSubject::with(['schoolClass', 'subject'])->get(),
        ]);
    }
}
