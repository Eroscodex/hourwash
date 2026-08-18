<?php

namespace App\Models;

use Carbon\Carbon;
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

    public function activeOrder()
    {
        return $this->hasOne(Order::class, 'machine_id')
            ->whereNotIn('order_status', ['completed', 'cancelled', 'finish'])
            ->latestOfMany();
    }

    public function getDisplayOrderAttribute()
    {
        return $this->currentOrder ?? $this->activeOrder;
    }

    public function getRemainingMinutesAttribute($value)
    {
        if (in_array($this->status, ['washing', 'rinsing', 'drying'])) {
            $ord = $this->displayOrder;
            $baseMins = ($value !== null && $value > 0) ? (int) $value : match ($this->status) {
                'washing' => $ord?->service?->estimated_minutes ?? 30,
                'rinsing' => 15,
                'drying' => 35,
                default => 30,
            };

            $lastUpdate = $this->last_status_update ?? $this->updated_at;
            if ($lastUpdate) {
                $lastUpdateCarbon = Carbon::parse($lastUpdate);
                if ($lastUpdateCarbon->isPast()) {
                    $elapsed = (int) $lastUpdateCarbon->diffInMinutes(now());
                    if ($elapsed > ($baseMins + 120)) {
                        return min($baseMins, 25);
                    }
                    $remaining = $baseMins - $elapsed;

                    return max(1, min($baseMins, $remaining));
                }
            }

            return $baseMins;
        }

        return null;
    }
}
