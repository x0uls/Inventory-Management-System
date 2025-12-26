<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $primaryKey = 'product_id';

    protected $fillable = [
        'product_id',
        'category_id',
        'supplier_id',
        'product_name',
        'description',
        'unit_price',
        'lowstock_alert',
        'image_path',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function (Product $product): void {
            // Delete image from storage when product is deleted
            if ($product->image_path) {
                $imagePath = $product->image_path;
                
                // Remove 'storage/' prefix if present
                if (str_starts_with($imagePath, 'storage/')) {
                    $imagePath = substr($imagePath, 8); // Remove 'storage/' (8 characters)
                }
                
                // Delete the file if it exists
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }
        });
    }

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
