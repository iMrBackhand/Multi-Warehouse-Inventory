<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            $brands = [
            'Racing Boy (RCB)',
            'UMA Racing',
            'YSS',
            'MTRT',
            'Mutarru',
            'NHK',
            'NGK',
            'DID',
            'RK',
            'SKF',
            'Koyo',
            'Nissin',
            'Brembo',
            'Mikuni',
            'Keihin',
            'Yuasa',
            'GS Battery',
            'Bosch',
            'Aspira',
            'Federal',
        ];

        foreach ($brands as $brand) {
            Brand::create([
                'brand_name' => $brand,
                'image' => 'backend/assets/images/users/no.avif',
            ]);
        }
    }
}
