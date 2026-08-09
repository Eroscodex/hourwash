<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    protected $fillable = [
        'order_id',
        'qr_token',
        'qr_image',
        'status',
        'expires_at',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
