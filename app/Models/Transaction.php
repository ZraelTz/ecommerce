<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'amount',
        'invoice',
        'payment_method',
        'payment_status',
        'order_status'
    ];

    /**
     * Get the order that owns the transaction.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
