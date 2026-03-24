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
        'category_id',
        'brand_id',
        'type',
        'default_price',
        'price',
        'discount',
        'image',
        'weight',
        'description',
        'stock',
        'is_active'
    ];  

    protected $casts = [
        'is_digital' => 'boolean',
        'is_games' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $slug = Str::slug($product->name);
            $count = self::where('slug', 'like', "{$slug}%")->count();

            $product->slug = $count ? "{$slug}-{$count}" : $slug;
        });
    }


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }


    // Harga final (setelah diskon)
    public function getFinalPriceAttribute()
    {
        $price = $this->price ?? $this->default_price;

        if ($this->discount > 0) {
            return $price - ($price * $this->discount / 100);
        }

        return $price;
    }

    // Cek apakah produk habis
    public function getIsOutOfStockAttribute()
    {
        return $this->stock <= 0;
    }
}
