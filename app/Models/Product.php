<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'description',
        'image',
    ];

    protected static function booted()
    {
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });

        static::updating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
    public function getVariantColorsAttribute(): string
    {
        if ($this->variants->isEmpty()) {
            return '-';
        }

        $grouped = $this->variants->groupBy('color');
        
        $result = $grouped->map(function ($items, $color) {
            $sizeCounts = $items->groupBy('size')
                ->map(fn ($sizeItems) => $sizeItems->count())
                ->sortKeys();
            
            $sizeStrings = $sizeCounts->map(fn ($count, $size) => "$size=$count")
                ->join(', ');
            
            return "$color ($sizeStrings)";
        })->join(', ');

        return $result;
    }

    public function discounts()
    {
        return $this->belongsToMany(Discount::class);
    }
}
