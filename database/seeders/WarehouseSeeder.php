<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehouse = new Warehouse();
        $warehouse->warehouse_name = 'Laguna Warehouse';
        $warehouse->email = 'laguna@warehouse.com';
        $warehouse->phone = '09123456789';
        $warehouse->city = 'Laguna';
        $warehouse->save();

        $warehouse = new Warehouse();
        $warehouse->warehouse_name = 'Cavite Warehouse';
        $warehouse->email = 'cavite@warehouse.com';
        $warehouse->phone = '09987654321';
        $warehouse->city = 'Cavite';
        $warehouse->save();
    }
}
