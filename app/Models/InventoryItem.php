<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    protected $fillable = [
        'name',
        'category',
        'unit',
        'quantity',
        'minimum_stock',
        'unit_cost',
        'status',
    ];
}
