<?php

namespace App\Models;


use App\Models\Food;
use App\Models\Withdraw;
use App\Models\OrderItem;
use App\Models\Notification;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Chef extends Authenticatable
{
	use HasFactory, Notifiable, HasApiTokens;



	/**
	 * Mass-assignable attributes.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name',
		'phone',
		'password',
		'email',
		'otp',
		'is_verify',
		'image',
		'wallet',
		'countSubscribe',
		'channel_name',
		'bio',
		'totalOrder',
	];

	public function routeNotificationForFcm()
	{
		return $this->fcm_token;
	}
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

	public function food()
	{
		return $this->hasMany(Food::class); // تأكد من علاقة الشيف مع الأطباق
	}

	public function followers()
	{
		return $this->hasMany(Follower::class); // تأكد من علاقة الشيف مع المتابعين
	}
	public function notifications()
	{
		return $this->morphMany(Notification::class, 'notifiable');
	}
	public function orderItems()
	{
		return $this->hasMany(OrderItem::class);
	}

	public function withdraws()
	{
		return $this->hasMany(Withdraw::class);
	}
}