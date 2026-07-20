<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ProductImage extends Model
{
     use LogsActivity;
    protected $table = 'product_images'; // ← idagdag ito
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }
}
