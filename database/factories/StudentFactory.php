<?php

namespace Database\Factories;

use App\Models\AcademicYear;
use App\Models\Section;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Student>
 */
class StudentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'admission_no' => 'ADM-'.$this->faker->unique()->numerify('#####'),
            'class_id' => SchoolClass::factory(),
            'section_id' => Section::factory(),
            'academic_year_id' => AcademicYear::factory(),
            'dob' => $this->faker->dateTimeBetween('-18 years', '-5 years'),
            'gender' => $this->faker->randomElement(['M', 'F', 'O']),
            'admission_date' => $this->faker->dateTimeBetween('-3 years', 'now'),
            'status' => 'active',
        ];
    }
}
