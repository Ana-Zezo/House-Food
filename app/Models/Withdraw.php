<?php

namespace App\Models;

use App\Models\Chef;
use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    protected $fillable = ['chef_id', 'amount', 'status', 'totalOrder'];


    public function chef()
    {
        return $this->belongsTo(Chef::class);
    }
}