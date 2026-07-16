<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transfer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transfer_date',
        'from_warehouse_id',
        'to_warehouse_id',
        'discount',
        'shipping',
        'status',
        'note',
        'grand_total',
    ];

    public function fromWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }

    public function toWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }

    // Named to match the blade's $transfer->transferItem usage
    public function transferItem()
    {
        return $this->hasMany(TransferItem::class);
    }
}
