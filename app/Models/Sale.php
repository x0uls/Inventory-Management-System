<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $primaryKey = 'sales_id';

    protected $fillable = [
        'batch_id',
        'quantity',
        'unit_price',
        'total_amount',
        'date',
        'customer_name',
        'payment_method',
    ];

    protected $casts = [
        'date' => 'datetime',
        'total_amount' => 'decimal:2',
        'unit_price' => 'decimal:2',
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id', 'batch_id');
    }
}
