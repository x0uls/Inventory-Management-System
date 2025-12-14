<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $primaryKey = 'product_id';

    protected $fillable = [
        'category_id',
        'supplier_id',
        'product_name',
        'description',
        'unit_price',
        'lowstock_alert',
        'image_path',
    ];

    public function batches(): HasMany
    {
        return $this->hasMany(Batch::class, 'product_id', 'product_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function getTotalQuantityAttribute(): int
    {
        return $this->batches()->sum('quantity');
    }

    public function isLowStock(): bool
    {
        return $this->total_quantity <= $this->lowstock_alert;
    }
}
