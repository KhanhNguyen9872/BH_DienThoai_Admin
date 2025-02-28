<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Table name (optional, if different from pluralized model name)
    protected $table = 'orders';

    // Fillable attributes for mass assignment
    protected $fillable = [
        'id', 'user_id',
    ];

    // Relationship with order_info (one-to-one)
    public function orderInfo()
    {
        return $this->hasOne(OrderInfo::class, 'order_id', 'id');
    }

    // Define relationship to User model to get full_name
    public function user()
    {
        return $this->belongsTo(UserInfo::class, 'user_id', 'id');
    }
    
}
