<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'canteen_id',
        'total_price',
        'payment_status',
        'order_status'
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function canteen()
    {
        return $this->belongsTo(Canteen::class);
    }
}
