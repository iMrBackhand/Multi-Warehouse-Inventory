<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    protected $fillable = ['sale_id', 'source_id', 'payment_id', 'amount', 'status'];
     public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
