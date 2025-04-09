<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodInventory extends Model
{
    protected $table = 'blood_inventory';

    public $timestamps = false;

    protected $fillable = [
        'blood_type',
        'expiration_date',
        'last_updated',
        'quantity',
        'appointment_id'
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }
}
