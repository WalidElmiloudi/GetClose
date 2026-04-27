<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    protected $fillable = [
        'amount',
        'status',
        'vendor_id',
        'shop_id',
        'transaction_reference',
        'notes',
        'processing_fee',
        'net_amount'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processing_fee' => 'decimal:2',
        'net_amount' => 'decimal:2'
    ];

    public function vendor()
    {
        return $this->belongsTo(User::class,'vendor_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function ledger()
    {
        return $this->hasMany(VendorLedger::class, 'payout_id');
    }
}
