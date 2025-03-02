<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    // Specify the table if it is not the default "admins"
    protected $table = 'admin';

    // Define fillable properties if needed
    protected $fillable = [
        'username',
        'full_name',
        'email',
        'img',
        'password',
    ];

    // Disable "remember me" functionality if not needed
    public function getRememberToken()
    {
        return null;
    }

    public function setRememberToken($value)
    {
        // No-op
    }

    public function getRememberTokenName()
    {
        return null;
    }
}
