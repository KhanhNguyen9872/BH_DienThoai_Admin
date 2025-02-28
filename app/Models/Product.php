<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // Specify the table name since it is not plural
    protected $table = 'product';

    protected $fillable = [
        'name',
        'description',
        'favorite',
        'color',
    ];

    protected $casts = [
        'favorite' => 'array',
        'color'    => 'array',
    ];
}
