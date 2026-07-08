<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\Section;
use App\Models\Staff;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Attendance>
 */
class AttendanceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'section_id' => Section::factory(),
            'date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'status' => $this->faker->randomElement(['present', 'absent', 'late', 'excused']),
            'marked_by' => Staff::factory(),
        ];
    }
}
