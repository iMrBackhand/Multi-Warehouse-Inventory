<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
     use HasFactory, SoftDeletes;
    protected $casts = [
            'image' => 'array',
        ];
   public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }
}
