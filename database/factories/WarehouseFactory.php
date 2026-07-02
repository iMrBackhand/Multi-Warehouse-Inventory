<?php

namespace Database\Factories;

use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Warehouse>
 */
class WarehouseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
         return [
            'warehouse_name' => $this->faker->company(),
            'email'          => $this->faker->unique()->safeEmail(),
            'phone'          => $this->faker->numerify('09#########'),
            'city'           => $this->faker->city(),
        ];
    }
}
