<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    use HasFactory;

    // Table name (optional, if different from pluralized model name)
    protected $table = 'user'; // Assuming the table is named 'user_info'

    // Fillable attributes for mass assignment
    protected $fillable = [
        'first_name', 'last_name', 'email',
    ];

    // Primary key (optional, if different from 'id')
    protected $primaryKey = 'id';

    // Relationship to the OrderInfo model (if necessary)
    public function orderInfos()
    {
        return $this->hasMany(OrderInfo::class, 'user_id', 'id');
    }

    // Get the full name of the user
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function addresses()
{
    return $this->hasMany(Address::class, 'user_id');
}

}
