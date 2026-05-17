<?php

namespace Database\Factories;

use App\Models\Delivery;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
/**
 * @extends Factory<Delivery>
 */
class DeliveryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'delivery_status' => $this->faker->randomElement(['In Transit', 'Out for Delivery', 'Delivered', 'Returned']),
            'order_id' => Order::factory(),
        ];
    }
}
