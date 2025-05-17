<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Order;
use App\Models\Wallet;
use App\Models\Notification;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'otp',
        'phone',
        'status',
        'is_verify',
        'image',
        'wallet',
        'fcm_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function wallet()
    {
        return $this->hasMany(Wallet::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }
    public function followers()
    {
        return $this->hasMany(Follower::class);
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }
}