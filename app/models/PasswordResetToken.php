<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetToken extends Model
{
    protected $table = 'password_reset_token';

    public $timestamps = false;

    protected $fillable = [
        'token',
        'user_cccd',
        'expiry_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_cccd', 'cccd');
    }

    public function isExpired()
    {
        return strtotime($this->expiry_date) < time();
    }

    public static function findByToken($token)
    {
        return self::where('token', $token)->first();
    }

    public static function findByUserId($userId)
    {
        return self::where('user_cccd', $userId)->first();
    }

    public static function deleteByUserId($userId)
    {
        return self::where('user_cccd', $userId)->delete();
    }

    public static function deleteExpiredTokens()
    {
        return self::where('expiry_date', '<', now())->delete();
    }
}
