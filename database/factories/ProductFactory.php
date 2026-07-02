<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Supplier;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

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
    public function definition(): array
    {
       return [
    'product_name' => fake()->words(2, true),
    'code' => strtoupper(fake()->bothify('PRD-####')),
    'image' => ['default-product.png'],

    'category_id' => ProductCategory::factory(),
    'brand_id' => Brand::inRandomOrder()->value('id'),
    'warehouse_id' => Warehouse::inRandomOrder()->value('id'),
    'supplier_id' => Supplier::inRandomOrder()->value('id'),

    'price' => fake()->randomFloat(2, 100, 10000),
    'stock_alert' => fake()->numberBetween(5, 20),
    'note' => fake()->sentence(),
    'product_quantity' => fake()->numberBetween(1, 500),
    'discount' => fake()->randomFloat(2, 0, 50),
    'status' => fake()->randomElement(['Pending', 'Approved']),
    'active' => '1',
];
    }
}
