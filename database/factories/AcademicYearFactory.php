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
        $startYear = $this->faker->unique()->numberBetween(1990, 2100);
        $start = \Carbon\Carbon::createFromDate($startYear, 1, 1);
        $end = (clone $start)->addYear();

        return [
            'name' => $start->format('Y').'/'.$end->format('Y'),
            'start_date' => $start,
            'end_date' => $end,
        ];
    }
}
