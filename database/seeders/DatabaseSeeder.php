<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Test user
        User::factory()->create([
            'name' => 'Bert',
            'email' => 'norbertojrmgarte@gmail.com',
            'password' => bcrypt('admin123'),
        ]);

        // All seeders in one call (clean way)
        $this->call([
            SupplierSeeder::class,
            CustomerSeeder::class,
            WarehouseSeeder::class,
            BrandSeeder::class,
            ProductSeeder::class,
        ]);
    }
}
