<?php

namespace Database\Factories;

use App\Models\AcademicYear;
use App\Models\Exam;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Exam>
 */
class ExamFactory extends Factory
{
    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-2 months', '+1 month');
        $end = (clone $start)->modify('+5 days');

        return [
            'academic_year_id' => AcademicYear::factory(),
            'name' => $this->faker->randomElement(['Midterm Exam', 'Final Exam', 'Unit Test']),
            'start_date' => $start,
            'end_date' => $end,
        ];
    }
}
