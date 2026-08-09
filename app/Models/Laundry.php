<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laundry extends Model
{
    protected $fillable = [
        'user_id',
        'item_name',
        'quantity',
        'status',
        'start_time',
        'estimated_hours',
        'finish_time',
        'remarks',
        'qr_code',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'finish_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
