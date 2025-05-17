<?php

namespace App\Models;

use App\Models\Chef;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    protected $fillable = ['user_id', 'chef_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function chef()
    {
        return $this->belongsTo(Chef::class);
    }
   

}