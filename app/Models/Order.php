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
        'paid_at',
        'refunded_amount',
        'client_id',
        'shipping_method_id'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'refunded_amount' => 'decimal:2'
    ];

    public function client()
    {
        return $this->belongsTo(User::class,'client_id');
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
    
    public function dispute()
    {
        return $this->hasOne(Dispute::class);
    }
    
    public function refunds()
    {
        return $this->hasMany(Refund::class);
    }
}
