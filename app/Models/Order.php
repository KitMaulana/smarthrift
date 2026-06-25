<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id',
        'product_id',
        'courier_id',
        'price',
        'service_fee',
        'total_price',
        'payment_method',
        'status',
        'shipping_address',
        'shipping_phone',
    ];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function courier()
    {
        return $this->belongsTo(User::class, 'courier_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'order_id');
    }
}
