<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'sku',
        'image_url',
        'category',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'is_active' => 'boolean',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->ordered();
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->primary();
    }

    public function getPrimaryImageUrlAttribute(): string
    {
        if ($this->primaryImage) {
            return $this->primaryImage->image_url;
        }
        
        if ($this->images->first()) {
            return $this->images->first()->image_url;
        }
        
        return $this->image_url ?? asset('images/default-product.jpg');
    }

    public function hasImages(): bool
    {
        return $this->images->isNotEmpty();
    }

    public function getImageCountAttribute(): int
    {
        return $this->images->count();
    }
}
