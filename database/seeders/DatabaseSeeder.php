<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Feedback;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Delivery;    
use App\Models\Image;
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
   public function run(): void
    {
        // 1. Create 5 Users with Feedback
        User::factory(5)->create()->each(function ($user) {
            Feedback::factory(2)->create(['user_id' => $user->id]);
        });

        // 2. Create 10 Products with Images
        $products = Product::factory(10)->create()->each(function ($product) {
            Image::factory(3)->create(['product_id' => $product->id]);
        });

        // 3. Create active Promotions and attach to random products
        $promotions = Promotion::factory(3)->create();
        $products->each(function ($product) use ($promotions) {
            $product->promotions()->attach(
                $promotions->random(rand(1, 2))->pluck('id')->toArray(),
                ['percentage' => rand(5, 50), 'description' => 'Seasonal Discount']
            );
        });

        // 4. Create Orders, tie to Products (Pivot), Payments, and Deliveries
        Order::factory(8)->create()->each(function ($order) use ($products) {
            // Attach random products to the order pivot table
            $order->products()->attach(
                $products->random(rand(1, 3))->pluck('id')->toArray()
            );

            // Generate structural fulfillment details
            Payment::factory()->create(['order_id' => $order->id]);
            Delivery::factory()->create(['order_id' => $order->id]);
        });
    }
}
