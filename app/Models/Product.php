<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'category',
        'stock',
        'sales_price',
        'cost_price',
        'unit_of_measurement'
    ];

    /**
     * Get the images for the product
    */
    public function product_images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Get the reviews for the product
    */
    public function product_reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }
}
