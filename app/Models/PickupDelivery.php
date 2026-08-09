<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PickupDelivery extends Model
{
    protected $table = 'pickup_delivery';

    protected $fillable = [
        'order_id',
        'type',
        'address',
        'contact_number',
        'scheduled_date',
        'scheduled_time',
        'rider_name',
        'rider_phone',
        'status',
        'picked_up_at',
        'delivered_at',
        'notes',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
