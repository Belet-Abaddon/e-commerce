<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       return [
            'payment_type' => $this->faker->randomElement(['Credit Card', 'Bank Transfer', 'E-Wallet']),
            'payment_name' => $this->faker->randomElement(['Visa', 'Mastercard', 'PayPal', 'Stripe']),
            'order_id' => Order::factory(),
            'screenshot' => 'receipts/' . $this->faker->uuid() . '.png',
        ];
    }
}
