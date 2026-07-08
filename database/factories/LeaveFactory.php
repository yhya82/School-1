<?php

namespace Database\Factories;

use App\Models\Leave;
use App\Models\Staff;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Leave>
 */
class LeaveFactory extends Factory
{
    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-1 month', '+1 month');
        $end = (clone $start)->modify('+'.$this->faker->numberBetween(1, 5).' days');

        return [
            'staff_id' => Staff::factory(),
            'leave_type' => $this->faker->randomElement(['sick', 'casual', 'annual']),
            'start_date' => $start,
            'end_date' => $end,
            'status' => 'pending',
            'approved_by' => null,
        ];
    }
}
