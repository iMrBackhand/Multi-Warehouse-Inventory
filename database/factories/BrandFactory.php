<?php

namespace Database\Factories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Brand>
 */
class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
      return [
            'brand_name' => $this->faker->company(),

            // fake image path (for testing only)
            'image' => 'brands/' . $this->faker->lexify('brand_????') . '.jpg',
        ];
    }
}
