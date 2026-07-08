<?php

namespace Database\Factories;

use App\Models\AcademicYear;
use App\Models\FeeStructure;
use App\Models\SchoolClass;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FeeStructure>
 */
class FeeStructureFactory extends Factory
{
    public function definition(): array
    {
        return [
            'class_id' => SchoolClass::factory(),
            'academic_year_id' => AcademicYear::factory(),
            'name' => $this->faker->randomElement(['Tuition Fee', 'Transport Fee', 'Lab Fee']),
            'amount' => $this->faker->randomFloat(2, 50, 2000),
            'frequency' => $this->faker->randomElement(['monthly', 'term', 'annual']),
        ];
    }
}
