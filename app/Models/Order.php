<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;


class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';

    protected $fillable = [
        'customer_id',
        'product_id',
        'quantity',
        'amount'
    ];

    //get the transaction associated with this order
    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class);
    }

    /**
     * Get the customer that owns the order.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
