<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'id',
        'order_code',
        'user_id',
        'customer_name',
        'customer_phone',
        'customer_address',
        'total_price',
        "payment_method",
        'status',
        'expires_at',
    ];
    protected $with = ['items.product'];

    public function items()
    {
        return $this->hasMany(OrderItem::class)->with('product');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
