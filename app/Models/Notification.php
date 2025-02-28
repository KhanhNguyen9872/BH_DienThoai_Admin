<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';  // Table name
    protected $fillable = ['text', 'url', 'time', 'isRead'];  // Define the fillable columns
    protected $casts = [
        'time' => 'datetime',  // Ensure 'time' is treated as a Carbon instance
        'isRead' => 'boolean', // Ensure 'isRead' is treated as a boolean
    ];
}
