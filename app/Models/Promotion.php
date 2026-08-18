<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'discount_type',
        'discount_value',
        'minimum_amount',
        'start_date',
        'end_date',
        'usage_limit',
        'usage_count',
        'status',
    ];
}
