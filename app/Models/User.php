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
        'stamps_count',
        'completed_cards_count',
        'discount_rewards_available',
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
            'stamps_count' => 'integer',
            'completed_cards_count' => 'integer',
            'discount_rewards_available' => 'integer',
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

    public function isRider(): bool
    {
        return $this->role === 'rider';
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

    /**
     * Add stamp(s) to customer's Frequent User Card.
     * Card capacity is 12 stamps. Reaching 12 stamps completes 1 card and unlocks a discount reward.
     */
    public function addStamp(int $count = 1): void
    {
        $newStamps = $this->stamps_count + $count;

        while ($newStamps >= 12) {
            $newStamps -= 12;
            $this->completed_cards_count++;
            $this->discount_rewards_available++;
        }

        $this->stamps_count = $newStamps;
        $this->save();
    }

    /**
     * Check if customer has earned discount rewards available.
     */
    public function hasDiscountReward(): bool
    {
        return $this->discount_rewards_available > 0;
    }

    /**
     * Consume 1 earned discount reward.
     */
    public function useDiscountReward(): bool
    {
        if ($this->discount_rewards_available > 0) {
            $this->discount_rewards_available--;
            $this->save();

            return true;
        }

        return false;
    }

    /**
     * Send password reset notification through Brevo.
     */
    public function sendPasswordResetNotification($token): void
    {
        if (app()->environment('testing')) {
            $this->notify(new ResetPassword($token));

            return;
        }

        Log::info('Sending password reset notification', [
            'user_id' => $this->id,
            'email' => $this->email,
        ]);

        EmailNotificationService::sendPasswordResetEmail(
            $this->email,
            $token
        );
    }
}
