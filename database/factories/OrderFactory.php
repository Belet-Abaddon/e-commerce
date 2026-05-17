<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_date' => $this->faker->date(),
            'order_address' => $this->faker->address(),
            'delivery_type' => $this->faker->randomElement(['Local', 'Global']),
            'delivery_name' => $this->faker->randomElement(['RoyalExpress', 'Bee', 'FedEx']),
            'user_id' => User::factory(),
            'qty' => $this->faker->numberBetween(1, 5),
        ];
    }
}
