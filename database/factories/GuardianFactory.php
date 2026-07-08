<?php

namespace Database\Factories;

use App\Models\Guardian;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Guardian>
 */
class GuardianFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => null,
            'name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'relationship' => $this->faker->randomElement(['Father', 'Mother', 'Guardian']),
        ];
    }
}
