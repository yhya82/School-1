<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\Staff;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Expense>
 */
class ExpenseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category' => $this->faker->randomElement(['Utilities', 'Maintenance', 'Supplies', 'Salaries']),
            'amount' => $this->faker->randomFloat(2, 20, 5000),
            'expense_date' => $this->faker->dateTimeBetween('-2 months', 'now'),
            'description' => $this->faker->sentence(),
            'recorded_by' => Staff::factory(),
        ];
    }
}
