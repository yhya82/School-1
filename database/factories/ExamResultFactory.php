<?php

namespace Database\Factories;

use App\Models\ExamResult;
use App\Models\ExamSubject;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ExamResult>
 */
class ExamResultFactory extends Factory
{
    public function definition(): array
    {
        $marks = $this->faker->numberBetween(30, 100);

        return [
            'exam_subject_id' => ExamSubject::factory(),
            'student_id' => Student::factory(),
            'marks_obtained' => $marks,
            'grade' => match (true) {
                $marks >= 90 => 'A',
                $marks >= 75 => 'B',
                $marks >= 60 => 'C',
                $marks >= 40 => 'D',
                default => 'F',
            },
        ];
    }
}
