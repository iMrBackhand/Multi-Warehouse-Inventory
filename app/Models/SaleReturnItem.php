<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleReturnItem extends Model
{
    protected $fillable = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function salesReturn()
    {
        return $this->belongsTo(SaleReturn::class);
    }
}
