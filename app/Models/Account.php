<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    // Specify the table if it doesn't follow the default plural naming convention.
    protected $table = 'account';

    // Fields that can be mass assigned.
    protected $fillable = [
        'username',
        'password',
        'lock_acc',
        'user_id',
    ];

    // Optionally hide the password when serializing the model.
    protected $hidden = [
        'password',
    ];

    public function user()
    {
        return $this->belongsTo(UserInfo::class, 'user_id');
    }
}
