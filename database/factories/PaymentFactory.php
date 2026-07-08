<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'invoice_id' => Invoice::factory(),
            'amount_paid' => $this->faker->randomFloat(2, 50, 2000),
            'payment_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'payment_method' => $this->faker->randomElement(['cash', 'card', 'bank_transfer', 'online']),
            'transaction_ref' => $this->faker->unique()->uuid(),
        ];
    }
}
