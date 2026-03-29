<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    protected $fillable = [
        'amount',
        'status',
        'vendor_id'
    ];

    public function vendor()
    {
        return $this->belongsTo(User::class,'vendor_id');
    }
}
