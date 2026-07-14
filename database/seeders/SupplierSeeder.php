<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'supplier_name' => 'Yohingco Trading',
                'email' => 'sales@yohingco.com',
                'phone' => '09171234501',
                'address' => 'Caloocan City',
            ],
            [
                'supplier_name' => 'PYRAKAM Corporation',
                'email' => 'sales@pyrakam.com',
                'phone' => '09171234502',
                'address' => 'Quezon City',
            ],
            [
                'supplier_name' => 'Innomoto Cycleparts Inc.',
                'email' => 'sales@innomoto.com',
                'phone' => '09171234503',
                'address' => 'Caloocan City',
            ],
            [
                'supplier_name' => 'Philippine SGC Corporation',
                'email' => 'sales@sgc.com.ph',
                'phone' => '09171234504',
                'address' => 'Makati City',
            ],
            [
                'supplier_name' => 'JT Motorflex Corporation',
                'email' => 'sales@jtmotorflex.com',
                'phone' => '09171234505',
                'address' => 'Quezon City',
            ],
            [
                'supplier_name' => "People's Choice Cycle Parts",
                'email' => 'sales@durasbl.com',
                'phone' => '09171234506',
                'address' => 'Caloocan City',
            ],
            [
                'supplier_name' => 'Kyle Motors',
                'email' => 'sales@kylemotors.com',
                'phone' => '09171234507',
                'address' => 'Cebu City',
            ],
            [
                'supplier_name' => 'Honda Genuine Parts',
                'email' => 'parts@hondaph.com',
                'phone' => '09171234508',
                'address' => 'Parañaque City',
            ],
            [
                'supplier_name' => 'Yamaha Genuine Parts',
                'email' => 'parts@yamaha-motor.com.ph',
                'phone' => '09171234509',
                'address' => 'Muntinlupa City',
            ],
            [
                'supplier_name' => 'Kawasaki Genuine Parts',
                'email' => 'parts@kmc.com.ph',
                'phone' => '09171234510',
                'address' => 'Muntinlupa City',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
