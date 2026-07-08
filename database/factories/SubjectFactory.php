<?php

namespace Database\Factories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Subject>
 */
class SubjectFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->unique()->randomElement([
            'Mathematics', 'English', 'Science', 'History', 'Geography', 'Art', 'Physical Education', 'Computer Science',
        ]);

        return [
            'name' => $name,
            'code' => strtoupper(substr($name, 0, 3)).$this->faker->unique()->numberBetween(100, 999),
        ];
    }
}
