<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $table = 'voucher'; // Assuming the table is named 'user_info'
    protected $fillable = [
        'code', 
        'discount', 
        'count', 
        'limit_user', 
        'user_id'
    ];

    // Cast JSON columns to arrays
    protected $casts = [
        'limit_user' => 'array',
        'user_id'    => 'array',
    ];
}
