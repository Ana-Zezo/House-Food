<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class Notification extends Model
{
    protected $table = 'notifications';
    use HasFactory;
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
