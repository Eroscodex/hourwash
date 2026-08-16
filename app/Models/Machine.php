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

    public function getRemainingMinutesAttribute($value)
    {
        if (in_array($this->status, ['washing', 'rinsing', 'drying'])) {
            $baseMins = $value ?: match ($this->status) {
                'washing' => $this->currentOrder?->service?->estimated_minutes ?? 30,
                'rinsing' => 15,
                'drying' => 35,
                default => 30,
            };

            $lastUpdate = $this->last_status_update ?? $this->updated_at;
            if ($lastUpdate) {
                $elapsed = (int) now()->diffInMinutes(Carbon::parse($lastUpdate));

                return max(1, $baseMins - $elapsed);
            }

            return $baseMins;
        }

        return null;
    }
}
