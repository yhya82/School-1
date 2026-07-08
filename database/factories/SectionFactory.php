<?php

namespace Database\Factories;

use App\Models\SchoolClass;
use App\Models\Section;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Section>
 */
class SectionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'class_id' => SchoolClass::factory(),
            'name' => $this->faker->randomElement(['A', 'B', 'C']),
            'capacity' => 30,
        ];
    }
}
