<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Batch extends Model
{
    protected $table = 'batch';
    
    protected $primaryKey = 'batch_id';

    protected $fillable = [
        'product_id',
        'quantity',
        'batch_number',
        'expiry_date',
        'qr_code_path',
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function sales(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Sale::class, 'batch_id', 'batch_id');
    }
}
