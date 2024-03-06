<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class ProductReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'product_id',
        'rating',
        'comment'
    ];

    /**
     * Get the product that owns the review.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
