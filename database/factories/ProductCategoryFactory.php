<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductCategoryFactory extends Factory
{
    private static array $categories = [
        'Running Shoes',
        'Basketball Jersey',
        'Gym Shorts',
        'Sports Socks',
        'Training Gloves',
        'Compression Pants',
        'Athletic Hoodies',
        'Swimming Goggles',
        'Cycling Shorts',
        'Yoga Pants',
    ];

    private static int $index = 0;

    public function definition(): array
    {
        $name = self::$categories[self::$index % count(self::$categories)];
        self::$index++;

        return [
            'category_name' => $name,
            'category_slug' => Str::slug($name),
        ];
    }
}
