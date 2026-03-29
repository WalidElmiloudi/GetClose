<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'title',
        'message',
        'receiver_id'
    ];

    public function receiver()
    {
        return $this->belongsTo(User::class,'receiver_id');
    }
}
