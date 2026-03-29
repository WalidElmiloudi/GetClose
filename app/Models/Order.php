<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'total_price',
        'shipping_fee',
        'status',
        'client_id'
    ];

    public function client()
    {
        return $this->belongsTo(User::class,'client_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
