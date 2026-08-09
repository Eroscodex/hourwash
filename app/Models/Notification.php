<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'reference_id',
        'is_read',
        'read_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
