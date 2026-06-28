<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // ← idagdag ito

class CustomersImport implements ToModel, WithHeadingRow // ← idagdag ang WithHeadingRow
{
    public function model(array $row)
    {
        $customer = new Customer();
        $customer->customer_name = $row['customer_name'];
        $customer->email         = $row['email'] ?? null;
        $customer->phone         = $row['phone'] ?? null;
        $customer->address       = $row['address'] ?? null;


        return $customer;
    }
}
