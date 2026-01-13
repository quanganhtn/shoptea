<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperCart
 */
class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
    ];

    // Quan hệ user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Quan hệ product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
