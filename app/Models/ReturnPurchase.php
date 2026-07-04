<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnPurchase extends Model
{
      use HasFactory;

        protected $casts = [
            'purchase_date' => 'date',
        ];
        public function warehouse()
        {
            return $this->belongsTo(Warehouse::class);
        }

        public function supplier()
        {
            return $this->belongsTo(Supplier::class);
        }

        public function returnPurchaseItems()
        {
            return $this->hasMany(ReturnPurchaseItem::class);
        }
}
