<?php

namespace App\Services;

use App\Models\Product;

class ProductSearchService
{
  public function search($query, $warehouseId = null)
    {
        return Product::where(function ($q) use ($query) {
                $q->where('product_name', 'like', "%{$query}%")
                  ->orWhere('code', 'like', "%{$query}%");
            })
            ->when($warehouseId, function ($q) use ($warehouseId) {
                $q->where('warehouse_id', $warehouseId);
            })
            ->select(
                'id',
                'product_name',
                'code',
                'price',
                'product_quantity',
                'discount'
            )
            ->limit(10)
            ->get();
    }
    public function __construct()
    {
        //
    }
}
