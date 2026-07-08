<?php

namespace Database\Factories;

use App\Models\FeeStructure;
use App\Models\Invoice;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Invoice>
 */
class InvoiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'fee_structure_id' => FeeStructure::factory(),
            'amount_due' => $this->faker->randomFloat(2, 50, 2000),
            'due_date' => $this->faker->dateTimeBetween('now', '+2 months'),
            'status' => 'unpaid',
        ];
    }
}
