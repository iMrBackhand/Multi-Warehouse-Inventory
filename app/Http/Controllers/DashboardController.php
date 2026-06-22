<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Warehouse;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.index', [
            'totalUsers' => User::count(),
            'totalSuppliers' => Supplier::count(),
            'totalBrands' => Brand::count(),
            'totalWarehouses' => Warehouse::count(),
        ]);
    }
}
