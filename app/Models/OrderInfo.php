<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderInfo extends Model
{
    use HasFactory;

    // Table name (optional, if different from pluralized model name)
    protected $table = 'order_info';

    // Fillable attributes for mass assignment
    protected $fillable = [
        'order_id', 'products', 'totalPrice', 'payment', 'status', 'address', 'orderAt', // Make sure to include 'user_id'
    ];

    // Relationship with orders (inverse of the above one-to-one)
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    // Cast 'products' as an array for easy access
    protected $casts = [
        'products' => 'array', // Cast 'products' to array for easy access
    ];
}
