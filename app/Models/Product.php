<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'sale_price',
        'sale_starts_at',
        'sale_ends_at',
        'sku',
        'stock',
        'category_id',
        'images',
        'color_options',
        'size_options',
        'featured',
        'is_flash_sale',
        'is_active',
    ];

    protected $casts = [
        'images' => 'array',
        'color_options' => 'array',
        'size_options' => 'array',
        'featured' => 'boolean',
        'is_flash_sale' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'sale_starts_at' => 'datetime',
        'sale_ends_at' => 'datetime',
    ];

    protected $appends = [
        'is_sale_active',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->is_sale_active && $this->price) {
            return round((($this->price - $this->sale_price) / $this->price) * 100);
        }
        return 0;
    }

    public function getFinalPriceAttribute()
    {
        return $this->is_sale_active ? $this->sale_price : $this->price;
    }

    public function getIsSaleActiveAttribute(): bool
    {
        if (!$this->is_flash_sale || !$this->sale_price) {
            return false;
        }

        $now = now();

        if ($this->sale_starts_at && $now->lt($this->sale_starts_at)) {
            return false;
        }

        if ($this->sale_ends_at && $now->gt($this->sale_ends_at)) {
            return false;
        }

        return $this->sale_price < $this->price;
    }

    public function scopeActiveFlashSale($query)
    {
        $now = now();

        return $query->where('is_active', true)
            ->where('is_flash_sale', true)
            ->whereNotNull('sale_price')
            ->whereColumn('sale_price', '<', 'price')
            ->where(function ($q) use ($now) {
                $q->whereNull('sale_starts_at')->orWhere('sale_starts_at', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('sale_ends_at')->orWhere('sale_ends_at', '>=', $now);
            });
    }
}
