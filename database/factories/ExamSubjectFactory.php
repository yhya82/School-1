<?php

namespace Database\Factories;

use App\Models\ClassSubject;
use App\Models\Exam;
use App\Models\ExamSubject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ExamSubject>
 */
class ExamSubjectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'exam_id' => Exam::factory(),
            'class_subject_id' => ClassSubject::factory(),
            'exam_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'max_marks' => 100,
            'pass_marks' => 40,
        ];
    }
}
