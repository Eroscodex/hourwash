<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffProfile extends Model
{
    protected $fillable = [
        'user_id',
        'employee_id',
        'position',
        'hire_date',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
