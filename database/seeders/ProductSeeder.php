<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Supplier;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['NGK CR8E Spark Plug', 'NGK', 'Spark Plug'],
            ['RCB Brake Lever', 'Racing Boy (RCB)', 'Levers'],
            ['RCB Handle Grip', 'Racing Boy (RCB)', 'Handlebars'],
            ['UMA Racing CDI', 'UMA Racing', 'Electrical Parts'],
            ['UMA Racing Ignition Coil', 'UMA Racing', 'Electrical Parts'],
            ['YSS Rear Shock', 'YSS', 'Shock Absorber'],
            ['MTRT Roller Set', 'MTRT', 'CVT Parts'],
            ['Federal Engine Oil 10W40', 'Federal', 'Oil & Lubricants'],
            ['DID 428 Chain', 'DID', 'Drive Chain & Sprocket'],
            ['RK Chain Set', 'RK', 'Drive Chain & Sprocket'],
            ['SKF Wheel Bearing', 'SKF', 'Bearings'],
            ['Koyo Bearing', 'Koyo', 'Bearings'],
            ['Nissin Brake Master', 'Nissin', 'Brake Master Cylinder'],
            ['Brembo Brake Caliper', 'Brembo', 'Brake Caliper'],
            ['Mikuni Carburetor', 'Mikuni', 'Fuel System'],
            ['Keihin Carburetor', 'Keihin', 'Fuel System'],
            ['Yuasa Battery YTX7L', 'Yuasa', 'Battery'],
            ['GS Battery GTZ5S', 'GS Battery', 'Battery'],
            ['Bosch Horn', 'Bosch', 'Electrical Parts'],
            ['Aspira Clutch Cable', 'Aspira', 'Cables'],
        ];

        $warehouse = Warehouse::first();
        $supplier = Supplier::first();

        foreach ($products as $item) {

            $brand = Brand::where('brand_name', $item[1])->first();

            $category = ProductCategory::where(
                'category_name',
                $item[2]
            )->first();

            Product::create([
                'product_name' => $item[0],
                'code' => 'PRD-' . rand(10000,99999),

                'brand_id' => $brand?->id,
                'category_id' => $category?->id,
                'supplier_id' => $supplier?->id,
                'warehouse_id' => $warehouse?->id,

                'price' => rand(150,2000),
                'stock_alert' => 10,
                'product_quantity' => rand(20,40),
                'discount' => 0,
                'status' => 'Pending',
                'active' => 1,
                'note' => 'Sample seeded product',
            ]);
        }
    }
}
