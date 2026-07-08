<?php

namespace Database\Factories;

use App\Models\ClassSubject;
use App\Models\SchoolClass;
use App\Models\Staff;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ClassSubject>
 */
class ClassSubjectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'class_id' => SchoolClass::factory(),
            'subject_id' => Subject::factory(),
            'teacher_id' => Staff::factory(),
        ];
    }
}
