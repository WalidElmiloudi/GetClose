<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'total_price',
        'shipping_price',
        'status',
        'shipping_address',
        'shipping_city',
        'shipping_zip',
        'payment_method',
        'client_id',
        'shipping_method_id'
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class);
    }

    public function payment()
    {
        return $this->hasOne(Payement::class);
    }
}
