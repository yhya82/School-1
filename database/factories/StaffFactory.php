<?php

namespace Database\Factories;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Staff>
 */
class StaffFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'employee_no' => 'EMP-'.$this->faker->unique()->numerify('#####'),
            'designation' => $this->faker->randomElement(['Teacher', 'Head of Department', 'Coordinator']),
            'department' => $this->faker->randomElement(['Science', 'Mathematics', 'Languages', 'Arts']),
            'joining_date' => $this->faker->dateTimeBetween('-5 years', 'now'),
        ];
    }
}
