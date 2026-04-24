<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'estimated_days',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'float',
        'estimated_days' => 'integer'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
