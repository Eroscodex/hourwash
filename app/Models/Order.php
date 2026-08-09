<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'customer_id',
        'service_id',
        'machine_id',
        'weight_kg',
        'subtotal',
        'delivery_fee',
        'discount',
        'total_amount',
        'payment_status',
        'order_status',
        'pickup_type',
        'pickup_date',
        'pickup_time',
        'estimated_completion',
        'completed_at',
        'notes',
    ];

    protected $casts = [
        'estimated_completion' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function qrCode()
    {
        return $this->hasOne(QrCode::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function pickupDelivery()
    {
        return $this->hasOne(PickupDelivery::class);
    }
}
