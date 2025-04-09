<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    protected $table = 'user_info';

    public $timestamps = false;

    protected $fillable = [
        'address',
        'dob',
        'full_name',
        'sex',
    ];

    // Define relationships
    public function user()
    {
        return $this->hasOne(User::class, 'user_info_id');
    }
}