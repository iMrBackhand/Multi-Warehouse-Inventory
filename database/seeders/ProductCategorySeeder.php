<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Engine Parts',
            'Transmission Parts',
            'Brake System',
            'Suspension',
            'Steering Parts',
            'Electrical Parts',
            'Lighting',
            'Fuel System',
            'Cooling System',
            'Exhaust System',
            'Drive Chain & Sprocket',
            'Tires',
            'Wheels & Rims',
            'Bearings',
            'Cables',
            'Handlebars',
            'Levers',
            'Foot Pegs',
            'Body Fairings',
            'Seat & Seat Cover',
            'Mirrors',
            'Speedometer & Gauges',
            'Battery',
            'Spark Plug',
            'Oil & Lubricants',
            'Air Filter',
            'Oil Filter',
            'Fuel Filter',
            'Brake Pads',
            'Brake Disc',
            'Brake Master Cylinder',
            'Brake Caliper',
            'Shock Absorber',
            'Front Fork',
            'Clutch Parts',
            'CVT Parts',
            'Piston & Cylinder',
            'Camshaft',
            'Crankshaft',
            'Gaskets & Seals',
        ];

        foreach ($categories as $category) {
            ProductCategory::create([
                'category_name' => $category,
                'category_slug' => Str::slug($category),
            ]);
        }
    }
}
