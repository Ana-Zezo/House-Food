<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationAdmin extends Model
{
    protected $fillable = ['title', 'description', 'withdraw_id',];
}