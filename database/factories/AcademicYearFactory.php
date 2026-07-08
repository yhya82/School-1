<?php

namespace Database\Factories;

use App\Models\AcademicYear;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AcademicYear>
 */
class AcademicYearFactory extends Factory
{
    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-1 year', 'now');
        $end = (clone $start)->modify('+1 year');

        return [
            'name' => $start->format('Y').'/'.$end->format('Y'),
            'start_date' => $start,
            'end_date' => $end,
        ];
    }
}
