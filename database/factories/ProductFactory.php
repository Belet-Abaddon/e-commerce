<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ProductType;
use App\Models\Brand;   
/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Product::class;
    public function definition(): array
    {
       return [
        'name' => $this->faker->words(3, true), 
        'description' => $this->faker->paragraph(),
        'price' => $this->faker->randomFloat(2, 10, 1000),
        'product_type_id' => ProductType::factory(),
        'brand_id' => Brand::factory(),
        'status' => $this->faker->randomElement(['active', 'inactive', 'out_of_stock']),
        ];
    }
}
