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

    public function estimatedDurationMinutes(): int
    {
        return match ($this->service_type) {
            'wash' => 35,
            'dry' => 40,
            'fold' => 15,
            'wash_dry' => 75,
            'wash_dry_fold' => 90,
            'pickup_delivery' => 120,
            default => (int) ($this->estimated_minutes ?: 30),
        };
    }
}
