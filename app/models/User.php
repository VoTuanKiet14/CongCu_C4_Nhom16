<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'cccd';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'cccd',
        'email',
        'password',
        'phone',
        'role_id',
        'user_info_id',
    ];

    // Define relationships
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function userInfo()
    {
        return $this->belongsTo(UserInfo::class, 'user_info_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'user_cccd', 'cccd');
    }

    public function bloodDonationHistories()
    {
        return $this->hasMany(BloodDonationHistory::class, 'user_id', 'cccd');
    }

    public function passwordResetToken()
    {
        return $this->hasOne(PasswordResetToken::class, 'user_cccd', 'cccd');
    }

    // Helper methods
    public function getFullName()
    {
        return $this->userInfo ? $this->userInfo->full_name : null;
    }

    public function getDob()
    {
        return $this->userInfo ? $this->userInfo->dob : null;
    }

    public function getAddress()
    {
        return $this->userInfo ? $this->userInfo->address : null;
    }

    public function getSex()
    {
        return $this->userInfo ? $this->userInfo->sex : null;
    }

    public function isAdmin()
    {
        return $this->role_id == 2; // 2 is ADMIN in the database
    }
}
