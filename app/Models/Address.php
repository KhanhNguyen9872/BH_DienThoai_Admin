<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    // Define the table if it's not the plural of the model name.
    protected $table = 'address';

    // Specify which attributes are mass assignable.
    protected $fillable = [
        'user_id',
        'full_name',
        'address',
        'phone',
    ];
}
