<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperOrder
 */
class Order extends Model
{
    protected $fillable = ['user_id', 'fullname', 'phone', 'address', 'note', 'payment', 'total_price', 'status', 'stock_deducted_at',];
    protected $casts = [
        'stock_deducted_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

}
