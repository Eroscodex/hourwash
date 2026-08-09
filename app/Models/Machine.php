<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    protected $fillable = [
        'machine_code',
        'machine_name',
        'machine_type',
        'status',
        'current_order_id',
        'remaining_minutes',
        'maintenance_note',
        'last_status_update',
    ];

    public function currentOrder()
    {
        return $this->belongsTo(Order::class, 'current_order_id');
    }
}
