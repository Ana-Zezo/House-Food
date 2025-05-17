<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;


class Notification extends Model
{
    protected $fillable = [
        'notifiable_id',
        'notifiable_type',
        'title',
        'description',
        'is_read'

    ];


    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

}