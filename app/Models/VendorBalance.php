<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorBalance extends Model
{
    protected $fillable = [
        'shop_id',
        'total_earnings',
        'total_refunds',
        'current_balance',
        'available_balance',
        'pending_balance',
        'total_withdrawn'
    ];

    protected $casts = [
        'total_earnings' => 'decimal:2',
        'total_refunds' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'available_balance' => 'decimal:2',
        'pending_balance' => 'decimal:2',
        'total_withdrawn' => 'decimal:2'
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function ledger()
    {
        return $this->hasMany(VendorLedger::class, 'shop_id');
    }
}
