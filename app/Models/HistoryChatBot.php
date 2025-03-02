<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryChatbot extends Model
{
    use HasFactory;

    // If your table name is not the default "history_chatbots", uncomment and adjust the following line:
    protected $table = 'history_chatbot';

    protected $fillable = [
        'user_id',
        'message',
        'isBot',
        'time',
    ];

    // Disable default timestamp columns (created_at, updated_at) if not used.
    public $timestamps = false;

    // Optionally cast columns to their appropriate types.
    protected $casts = [
        'isBot' => 'boolean',
        'time'  => 'datetime', // Adjust as necessary if time is stored differently.
    ];
}
