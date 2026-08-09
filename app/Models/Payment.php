<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'payment_reference',
        'payment_method',
        'amount',
        'status',
        'paid_at',
        'received_by',
        'notes',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
