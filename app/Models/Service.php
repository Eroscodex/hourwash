<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'description',
        'service_type',
        'price',
        'price_unit',
        'estimated_minutes',
        'status',
    ];
}
