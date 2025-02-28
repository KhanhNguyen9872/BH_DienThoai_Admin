<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    // Specify table if it's not the pluralized name
    protected $table = 'colors';

    // Optionally, define fillable fields
    protected $fillable = [
        'name',
        'image',
        'quantity',
        'price',
        'product_id', // assuming this links back to your product
    ];

    // Define relationship to the Product model (if needed)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
