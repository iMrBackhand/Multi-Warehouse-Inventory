<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnPurchaseItem extends Model
{
    protected $fillable = [];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
        public function returnPurchase()
    {
        return $this->belongsTo(ReturnPurchase::class);
    }
}
