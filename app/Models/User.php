<?php

namespace App\Models;

use App\Services\EmailNotificationService;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'profile_image',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isOwner(): bool
    {
        return $this->role === 'owner' || $this->role === 'admin';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->role === 'owner';
    }

    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer' || $this->role === 'user';
    }

    public function customerProfile()
    {
        return $this->hasOne(CustomerProfile::class);
    }

    public function staffProfile()
    {
        return $this->hasOne(StaffProfile::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function sendPasswordResetNotification($token)
    {
        // Try Laravel's built-in SMTP notification first (works on localhost)
        try {
            $this->notify(new ResetPassword($token));
        } catch (\Throwable $e) {
            Log::warning('SMTP password reset notification failed, using HTTP API fallback: '.$e->getMessage());
        }

        // Always attempt Brevo/Resend HTTP API (works online on Railway)
        EmailNotificationService::sendPasswordResetEmail($this->getEmailForPasswordReset(), $token);
    }
}
