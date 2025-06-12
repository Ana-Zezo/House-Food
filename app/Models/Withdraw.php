<?php

namespace App\Models;

use App\Models\Chef;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Withdraw extends Model
{
    use HasFactory;
    protected $fillable = ['chef_id', 'amount', 'status'];



    public function chef()
    {
        return $this->belongsTo(Chef::class);
    }
}
