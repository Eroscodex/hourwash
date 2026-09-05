<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerProfile extends Model
{
    protected $fillable = [
        'user_id',
        'address',
        'barangay',
        'city',
        'province',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address,
            $this->barangay ? (str_starts_with(strtolower(trim($this->barangay)), 'brgy') ? trim($this->barangay) : 'Brgy. '.trim($this->barangay)) : null,
            $this->city,
            $this->province,
        ]);

        return count($parts) > 0 ? implode(', ', $parts) : 'Magallanes St., Orosite, Legazpi City, Albay';
    }
}
