<?php

namespace Database\Factories;

use App\Models\ClassSubject;
use App\Models\Section;
use App\Models\TimetableSlot;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TimetableSlot>
 */
class TimetableSlotFactory extends Factory
{
    public function definition(): array
    {
        $start = $this->faker->numberBetween(8, 14);

        return [
            'section_id' => Section::factory(),
            'class_subject_id' => ClassSubject::factory(),
            'day_of_week' => $this->faker->numberBetween(1, 5),
            'start_time' => sprintf('%02d:00:00', $start),
            'end_time' => sprintf('%02d:00:00', $start + 1),
            'room' => 'Room '.$this->faker->numberBetween(1, 20),
        ];
    }
}
