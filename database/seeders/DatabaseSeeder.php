<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
        ]);

        $admin = User::factory()->create([
            'name' => 'Mr.Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'),
            'role' => 'Super Admin',
        ]);

        $admin->assignRole('Super Admin');


        $this->call([
            SupplierSeeder::class,
            CustomerSeeder::class,
            WarehouseSeeder::class,
            BrandSeeder::class,
            ProductSeeder::class,
            ProductCategorySeeder::class,
        ]);
    }
}
