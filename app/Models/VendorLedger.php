<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorLedger extends Model
{
    protected $table = 'vendor_ledgers';

    protected $fillable = [
        'shop_id',
        'order_id',
        'payout_id',
        'refund_id',
        'type',
        'amount',
        'running_balance',
        'payment_date',
        'available_date',
        'is_available',
        'description',
        'metadata'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'running_balance' => 'decimal:2',
        'payment_date' => 'datetime',
        'available_date' => 'datetime',
        'is_available' => 'boolean',
        'metadata' => 'array'
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function payout()
    {
        return $this->belongsTo(Payout::class);
    }

    public function refund()
    {
        return $this->belongsTo(Refund::class);
    }
}
